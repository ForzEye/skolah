<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    /**
     * Konfigurasi Midtrans dari config/midtrans.php
     */
    protected function configure(): void
    {
        \Midtrans\Config::$serverKey    = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized  = config('midtrans.is_sanitized', true);
        \Midtrans\Config::$is3ds        = config('midtrans.is_3ds', true);

        // Inisialisasi curlOptions dan bypass verifikasi CA lokal jika server tidak memiliki CA bundle
        \Midtrans\Config::$curlOptions = [
            CURLOPT_HTTPHEADER     => [],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
        ];
    }

    /**
     * Buat Snap Token untuk order yang diberikan.
     *
     * @throws \Exception Jika Midtrans API gagal
     */
    public function createSnapToken(Order $order): string
    {
        $this->configure();

        $order->loadMissing(['user', 'items']);

        // Gunakan order_id yang sudah tersimpan (untuk retry), atau buat baru
        $midtransOrderId = $order->midtrans_order_id ?? $order->order_number;

        $items = $order->items->map(fn($item) => [
            'id'       => (string) $item->itemable_id,
            'price'    => (int) $item->price,
            'quantity' => (int) $item->quantity,
            'name'     => substr($item->item_name, 0, 50),
        ])->values()->toArray();

        // PENTING: Jika ada diskon, tambahkan sebagai item negatif agar total item_details == gross_amount
        if ($order->discount_amount > 0) {
            $items[] = [
                'id'       => 'DISCOUNT',
                'price'    => (int) ($order->discount_amount * -1),
                'quantity' => 1,
                'name'     => 'Potongan Harga / Diskon',
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id'     => $midtransOrderId,
                'gross_amount' => (int) $order->total,
            ],
            'item_details' => $items,
            'customer_details' => [
                'first_name'      => $order->user->name,
                'email'           => $order->user->email,
                'phone'           => $order->user->phone ?? '',
            ],
            'expiry' => [
                'start_time' => now()->format('Y-m-d H:i:s O'),
                'unit'       => 'hours',
                'duration'   => 24,
            ],
            'callbacks' => [
                'finish'    => route('checkout.success'),
                'unfinish'  => route('checkout.failed'),
                'error'     => route('checkout.failed'),
            ],
            'gopay' => [
                'enable_callback' => true,
                'callback_url'    => route('checkout.success'),
            ],
            'shopeepay' => [
                'callback_url'    => route('checkout.success'),
            ],
            'notification_url' => route('api.midtrans.webhook'),
        ];

        try {
            $response = \Midtrans\Snap::createTransaction($params);
            $token = $response->token;
            $redirectUrl = $response->redirect_url;
        } catch (\Exception $e) {
            \Log::error('Midtrans Snap Error: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'midtransOrderId' => $midtransOrderId,
                'is_production' => config('midtrans.is_production'),
            ]);

            // Jika order_id duplikat, tambahkan suffix unik (standar Midtrans)
            if (str_contains(strtolower($e->getMessage()), 'order_id') || str_contains(strtolower($e->getMessage()), 'taken') || str_contains(strtolower($e->getMessage()), 'already')) {
                $midtransOrderId = $order->order_number . '-' . time();
                $params['transaction_details']['order_id'] = $midtransOrderId;
                $response = \Midtrans\Snap::createTransaction($params);
                $token = $response->token;
                $redirectUrl = $response->redirect_url;
            } else {
                throw $e;
            }
        }

        $order->update([
            'midtrans_snap_token' => $token,
            'midtrans_order_id'   => $midtransOrderId,
        ]);

        return $redirectUrl;
    }

    /**
     * Verifikasi signature key dari Midtrans webhook payload.
     *
     * Algoritma: SHA512(order_id + status_code + gross_amount + server_key)
     */
    public function verifySignature(array $payload): bool
    {
        $signature = hash('sha512',
            ($payload['order_id']     ?? '') .
            ($payload['status_code']  ?? '') .
            ($payload['gross_amount'] ?? '') .
            config('midtrans.server_key')
        );

        $valid = hash_equals($signature, $payload['signature_key'] ?? '');

        if (! $valid) {
            Log::warning('Midtrans signature mismatch', [
                'order_id' => $payload['order_id'] ?? null,
            ]);
        }

        return $valid;
    }

    /**
     * Cek apakah transaksi dianggap sukses.
     */
    public function isPaymentSuccess(array $payload): bool
    {
        $status     = $payload['transaction_status'] ?? '';
        $fraudStatus = $payload['fraud_status'] ?? 'accept';

        return ($status === 'capture' && $fraudStatus === 'accept')
            || $status === 'settlement';
    }

    /**
     * Cek apakah transaksi gagal / dibatalkan.
     */
    public function isPaymentFailed(array $payload): bool
    {
        return in_array($payload['transaction_status'] ?? '', ['deny', 'cancel', 'expire', 'failure']);
    }

    /**
     * Query status transaksi langsung ke Midtrans API.
     *
     * Berguna sebagai fallback saat webhook belum diterima
     * (misalnya di sandbox, atau saat redirect onSuccess dari Snap.js).
     *
     * @return array|null Payload dari Midtrans, atau null jika gagal.
     */
    public function getTransactionStatus(string $orderNumber): ?array
    {
        $this->configure();

        try {
            $status = \Midtrans\Transaction::status($orderNumber);
            return (array) $status;
        } catch (\Throwable $e) {
            Log::warning('Midtrans getTransactionStatus failed', [
                'order_number' => $orderNumber,
                'error'        => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Mengambil preferensi merchant dari API Midtrans (v3/merchant-preferences).
     * Sesuai dengan dokumentasi yang diberikan pengguna.
     */
    public function getMerchantPreferences(): ?array
    {
        $serverKey = config('midtrans.server_key');
        if (! $serverKey) return null;

        $isProduction = config('midtrans.is_production');
        $baseUrl = $isProduction 
            ? 'https://app.midtrans.com/snap/v3' 
            : 'https://app.sandbox.midtrans.com/snap/v3';

        try {
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Accept'       => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->withBasicAuth($serverKey, '')
                ->get($baseUrl . '/merchant-preferences');

            if ($response->successful()) {
                return $response->json();
            }

            Log::warning('Midtrans getMerchantPreferences failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            
            return null;
        } catch (\Throwable $e) {
            Log::error('Midtrans getMerchantPreferences exception', [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }
}
