@extends('layouts.app')

@section('title', 'Pembayaran Gagal' . ' — ' . \App\Models\Setting::get('site_name', '' . \App\Models\Setting::get('site_name', 'Skolah.com') . ''))

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-12 lg:py-20">

        {{-- Failed Card --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

            {{-- Header --}}
            @php
                $isPending = $order && $order->status === 'pending';
            @endphp
            <div class="{{ $isPending ? 'bg-gradient-to-r from-amber-500 to-orange-500' : 'bg-gradient-to-r from-red-500 to-rose-500' }} px-6 py-8 text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                    @if($isPending)
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    @else
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    @endif
                </div>
                <h1 class="text-2xl font-bold text-white">
                    {{ $isPending ? 'Pembayaran Belum Selesai' : 'Pembayaran Gagal' }}
                </h1>
                <p class="{{ $isPending ? 'text-amber-100' : 'text-red-100' }} mt-2 text-sm">
                    {{ $isPending ? 'Pembayaran Anda dibatalkan atau belum diselesaikan.' : 'Maaf, pembayaranmu tidak dapat diproses.' }}
                </p>
            </div>

            {{-- Body --}}
            <div class="p-6 lg:p-8">

                @if(session('warning'))
                <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-xl text-sm text-amber-800 flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    <span>{{ session('warning') }}</span>
                </div>
                @endif

                @if($order)
                {{-- Order Info --}}
                <div class="bg-gray-50 rounded-xl p-5 mb-6">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">No. Order</span>
                            <p class="font-semibold text-gray-900 mt-0.5">{{ $order->order_number }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Status</span>
                            <p class="mt-0.5">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium {{ $isPending ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-700' }}">
                                    @if($isPending)
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                                    @else
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                    @endif
                                    {{ $order->status_label }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <span class="text-gray-500">Total</span>
                            <p class="font-bold text-gray-900 text-lg mt-0.5">{{ $order->total_formatted }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Tanggal</span>
                            <p class="font-semibold text-gray-900 mt-0.5">
                                {{ tanggal_waktu_indo($order->created_at) }}
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Kemungkinan Penyebab --}}
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <div class="text-sm">
                            <p class="font-medium text-amber-800">Kemungkinan Penyebab</p>
                            <ul class="text-amber-700 mt-1 space-y-1">
                                <li>• Saldo atau limit tidak mencukupi</li>
                                <li>• Koneksi terputus saat proses pembayaran</li>
                                <li>• Pembayaran dibatalkan atau waktu habis</li>
                                <li>• Terjadi masalah pada bank / e-wallet</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Apa yang bisa dilakukan --}}
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="text-sm">
                            <p class="font-medium text-blue-800">Yang Bisa Kamu Lakukan</p>
                            <ul class="text-blue-600 mt-1 space-y-1">
                                <li>• Coba ulangi pembayaran dengan menambahkan item ke keranjang lagi</li>
                                <li>• Gunakan metode pembayaran yang berbeda</li>
                                <li>• Hubungi bank / e-wallet untuk memastikan tidak ada masalah di akun kamu</li>
                                <li>• Jika masalah berlanjut, hubungi tim support kami</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- CTA Buttons --}}
                <div class="flex flex-col sm:flex-row items-center gap-3">
                    @if($isPending)
                    <a href="{{ route('dashboard.orders.pay', $order->order_number) }}"
                       class="w-full sm:w-auto text-center px-6 py-3 rounded-xl bg-primary-600 text-white font-semibold text-sm hover:bg-primary-700 transition-colors shadow-sm">
                        💳 Bayar Sekarang
                    </a>
                    @else
                    <a href="{{ route('cart') }}"
                       class="w-full sm:w-auto text-center px-6 py-3 rounded-xl bg-primary-600 text-white font-semibold text-sm hover:bg-primary-700 transition-colors">
                        ← Kembali ke Keranjang
                    </a>
                    @endif
                    <a href="{{ route('dashboard.orders') }}"
                       class="w-full sm:w-auto text-center px-6 py-3 rounded-xl border border-gray-300 text-gray-700 font-medium text-sm hover:bg-gray-50 transition-colors">
                        Lihat Riwayat Pesanan
                    </a>
                    <a href="mailto:{{\App\Models\Setting::get('site_email', 'support@skolah.com')}}"
                       class="w-full sm:w-auto text-center px-6 py-3 rounded-xl text-gray-500 text-sm hover:text-primary-600 transition-colors">
                        Hubungi Support
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
