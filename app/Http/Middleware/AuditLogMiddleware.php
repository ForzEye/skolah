<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Fase 9 — Audit Log Middleware
 *
 * Mencatat aksi penting (POST, PUT, PATCH, DELETE) ke tabel audit_logs.
 * Bisa dipakai sebagai route middleware ('audit') atau group middleware.
 *
 * Payload disanitasi — field sensitif (password, token, secret, dll) dihapus.
 */
class AuditLogMiddleware
{
    /**
     * HTTP methods yang dicatat.
     */
    protected array $methods = ['POST', 'PUT', 'PATCH', 'DELETE'];

    /**
     * Field yang TIDAK boleh masuk ke audit log payload.
     */
    protected array $sensitiveFields = [
        'password',
        'password_confirmation',
        'current_password',
        'new_password',
        'new_password_confirmation',
        'token',
        'secret',
        '_token',
        'credit_card',
        'card_number',
        'cvv',
        'server_key',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $caughtException = null;
        $response = null;

        try {
            $response = $next($request);
        } catch (\Throwable $e) {
            $caughtException = $e;
            throw $e;
        } finally {
            try {
                if ($this->shouldLog($request)) {
                    $statusCode = $caughtException 
                        ? (method_exists($caughtException, 'getStatusCode') ? $caughtException->getStatusCode() : 500)
                        : ($response?->getStatusCode() ?? 500);

                    $payload = $this->buildFullPayload($request, $response, $caughtException);

                    AuditLog::create([
                        'user_id'     => auth()->id(),
                        'ip_address'  => $request->ip(),
                        'method'      => $request->method(),
                        'url'         => substr($request->fullUrl(), 0, 1000),
                        'route_name'  => $request->route()?->getName(),
                        'payload'     => $payload,
                        'status_code' => $statusCode,
                        'user_agent'  => substr($request->userAgent() ?? '', 0, 500),
                        'created_at'  => now(),
                    ]);
                }
            } catch (\Throwable $e) {
                // Jangan sampai audit log gagal → break user flow
                Log::error('AuditLog middleware logging error: ' . $e->getMessage(), [
                    'url'    => $request->fullUrl(),
                    'method' => $request->method(),
                ]);
            }
        }

        return $response;
    }

    /**
     * Apakah request ini perlu dicatat.
     */
    protected function shouldLog(Request $request): bool
    {
        return in_array($request->method(), $this->methods);
    }

    /**
     * Bangun payload lengkap termasuk file uploads dan detail error jika terjadi.
     */
    protected function buildFullPayload(Request $request, ?Response $response, ?\Throwable $caughtException): ?array
    {
        $payload = $this->sanitizePayload($request) ?? [];

        // ── 1. Catat metadata file uploads ─────────────────────────────────
        $files = $this->extractUploadedFiles($request);
        if (! empty($files)) {
            $payload['_files'] = $files;
        }

        // ── 2. Tangkap detail error jika ada ───────────────────────────────
        $exception = $caughtException ?? ($response?->exception ?? null);

        if ($exception instanceof \Throwable) {
            $payload['_error'] = [
                'type'      => 'exception',
                'class'     => get_class($exception),
                'message'   => $exception->getMessage(),
                'file'      => $exception->getFile(),
                'line'      => $exception->getLine(),
                'trace'     => array_slice(explode("\n", $exception->getTraceAsString()), 0, 12),
            ];
        } elseif ($response && $response->getStatusCode() >= 400) {
            if (session()->has('errors')) {
                $payload['_error'] = [
                    'type'    => 'validation',
                    'message' => 'Validasi input gagal',
                    'errors'  => session('errors')->all(),
                ];
            } elseif (session()->has('error')) {
                $payload['_error'] = [
                    'type'    => 'session_error',
                    'message' => session('error'),
                ];
            }
        }

        return empty($payload) ? null : $payload;
    }

    /**
     * Ekstrak metadata file yang diupload tanpa menyimpan konten binary.
     */
    protected function extractUploadedFiles(Request $request): array
    {
        $files = [];

        foreach ($request->allFiles() as $key => $file) {
            if ($file instanceof \Illuminate\Http\UploadedFile) {
                $files[$key] = [
                    'original_name' => $file->getClientOriginalName(),
                    'size'          => formatBytes($file->getSize()),
                    'mime_type'     => $file->getClientMimeType(),
                    'extension'     => $file->getClientOriginalExtension(),
                ];
            } elseif (is_array($file)) {
                $files[$key] = array_map(function ($f) {
                    if ($f instanceof \Illuminate\Http\UploadedFile) {
                        return [
                            'original_name' => $f->getClientOriginalName(),
                            'size'          => formatBytes($f->getSize()),
                            'mime_type'     => $f->getClientMimeType(),
                            'extension'     => $f->getClientOriginalExtension(),
                        ];
                    }
                    return null;
                }, $file);
            }
        }

        return $files;
    }

    /**
     * Sanitize request payload — hapus field sensitif, batasi ukuran.
     */
    protected function sanitizePayload(Request $request): ?array
    {
        $data = $request->except($this->sensitiveFields);

        // Hapus objek file dari data (sudah ditangani di extractUploadedFiles)
        $data = array_filter($data, fn($value) => !($value instanceof \Illuminate\Http\UploadedFile));

        if (empty($data)) {
            return null;
        }

        // Truncate string values yang terlalu panjang (max 500 chars per value)
        array_walk_recursive($data, function (&$value) {
            if (is_string($value) && strlen($value) > 500) {
                $value = substr($value, 0, 500) . '...[truncated]';
            }
        });

        // Batasi total payload agar tidak terlalu besar di DB
        $encoded = json_encode($data, JSON_UNESCAPED_UNICODE);
        if ($encoded && strlen($encoded) > 10000) {
            return ['_notice' => 'Payload too large, truncated', '_size' => strlen($encoded)];
        }

        return $data;
    }
}
