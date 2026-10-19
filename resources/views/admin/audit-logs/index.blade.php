@extends('layouts.admin')

@section('title', 'Audit & System Log')

@section('page-header')
    <span class="text-base font-semibold text-gray-900">Audit & Log Sistem</span>
@endsection

@section('content')
    {{-- Alerts --}}
    @if(session('success'))
        <div class="mb-5 flex items-center gap-3 bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-sm text-green-700">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-5 flex items-center gap-3 bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-700">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
            <p class="text-xs text-gray-500 font-medium">Request Hari Ini</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_today']) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
            <p class="text-xs text-gray-500 font-medium">7 Hari Terakhir</p>
            <p class="text-2xl font-bold text-blue-600 mt-1">{{ number_format($stats['total_week']) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
            <p class="text-xs text-gray-500 font-medium">User Aktif Hari Ini</p>
            <p class="text-2xl font-bold text-purple-600 mt-1">{{ number_format($stats['unique_users']) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
            <p class="text-xs text-gray-500 font-medium">Error Request (24 Jam)</p>
            <p class="text-2xl font-bold {{ $stats['error_count'] > 0 ? 'text-red-600' : 'text-green-600' }} mt-1">
                {{ number_format($stats['error_count']) }}
            </p>
        </div>
    </div>

    {{-- Tab Navigation --}}
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-1.5 bg-gray-100 p-1.5 rounded-2xl w-fit">
            <a href="{{ route('admin.audit-logs.index', array_merge(request()->except('tab'), ['tab' => 'audit'])) }}"
               class="px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-150 flex items-center gap-2
               {{ $currentTab === 'audit' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                <span>📋 Audit Log Aktivitas</span>
                <span class="px-2 py-0.5 rounded-full text-xs {{ $currentTab === 'audit' ? 'bg-blue-100 text-blue-700' : 'bg-gray-200 text-gray-600' }}">
                    {{ number_format($logs->total()) }}
                </span>
            </a>
            <a href="{{ route('admin.audit-logs.index', array_merge(request()->except('tab'), ['tab' => 'system'])) }}"
               class="px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-150 flex items-center gap-2
               {{ $currentTab === 'system' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                <span>🚨 System Error Log (laravel.log)</span>
                @if(($systemLogs['total'] ?? 0) > 0)
                    <span class="px-2 py-0.5 rounded-full text-xs bg-red-100 text-red-700 font-bold">
                        {{ $systemLogs['total'] }}
                    </span>
                @endif
            </a>
        </div>

        @if($currentTab === 'system')
            <div class="flex items-center gap-3">
                <span class="text-xs text-gray-500 font-mono">
                    File: <strong>{{ $systemLogs['file'] ?? '-' }}</strong> ({{ $systemLogs['size'] ?? '0 B' }})
                </span>
                <form action="{{ route('admin.audit-logs.clear-system') }}" method="POST"
                      onsubmit="return confirm('Apakah Anda yakin ingin mengosongkan file log sistem?')">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 rounded-xl border border-red-200 bg-red-50 text-red-600 hover:bg-red-100 text-xs font-semibold transition">
                        🗑️ Bersihkan Log
                    </button>
                </form>
            </div>
        @endif
    </div>

    @if($currentTab === 'audit')
        {{-- ========================================================================= --}}
        {{-- TAB 1: AUDIT LOGS (HTTP REQUESTS) --}}
        {{-- ========================================================================= --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-4 mb-6 shadow-sm">
            <form method="GET" class="flex flex-wrap items-end gap-3">
                <input type="hidden" name="tab" value="audit">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs text-gray-500 font-medium mb-1">Cari</label>
                    <input type="text" name="search" placeholder="URL, route, IP..." value="{{ request('search') }}"
                           class="w-full rounded-xl border border-gray-300 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 font-medium mb-1">Method</label>
                    <select name="method" class="rounded-xl border border-gray-300 px-4 py-2 text-sm">
                        <option value="">Semua</option>
                        @foreach($methods as $m)
                            <option value="{{ $m }}" {{ request('method') === $m ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 font-medium mb-1">Area</label>
                    <select name="route" class="rounded-xl border border-gray-300 px-4 py-2 text-sm">
                        <option value="">Semua</option>
                        @foreach($routePrefixes as $prefix => $label)
                            <option value="{{ $prefix }}" {{ request('route') === $prefix ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 font-medium mb-1">Status</label>
                    <select name="status" class="rounded-xl border border-gray-300 px-4 py-2 text-sm">
                        <option value="">Semua</option>
                        <option value="2xx" {{ request('status') === '2xx' ? 'selected' : '' }}>2xx Success</option>
                        <option value="3xx" {{ request('status') === '3xx' ? 'selected' : '' }}>3xx Redirect</option>
                        <option value="4xx" {{ request('status') === '4xx' ? 'selected' : '' }}>4xx Client Error</option>
                        <option value="5xx" {{ request('status') === '5xx' ? 'selected' : '' }}>5xx Server Error</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 font-medium mb-1">Dari</label>
                    <input type="date" name="from" value="{{ request('from') }}"
                           class="rounded-xl border border-gray-300 px-4 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 font-medium mb-1">Sampai</label>
                    <input type="date" name="to" value="{{ request('to') }}"
                           class="rounded-xl border border-gray-300 px-4 py-2 text-sm">
                </div>
                <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 shadow-sm transition">
                    Filter
                </button>
                @if(request()->hasAny(['search','method','route','status','from','to','user_id']))
                    <a href="{{ route('admin.audit-logs.index', ['tab' => 'audit']) }}" class="text-sm text-gray-500 hover:text-gray-700 py-2">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Waktu</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">User</th>
                            <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Method</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">URL / Route</th>
                            <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">IP</th>
                            <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($logs as $log)
                            @php
                                $hasError = $log->status_code >= 400 || ! empty($log->payload['_error']);
                            @endphp
                            <tr class="hover:bg-gray-50/80 transition {{ $hasError ? 'bg-red-50/30' : '' }}">
                                <td class="px-4 py-3 text-xs text-gray-500 whitespace-nowrap">
                                    {{ $log->created_at->format('d/m/Y H:i:s') }}
                                </td>
                                <td class="px-4 py-3">
                                    @if($log->user_id)
                                        <a href="{{ route('admin.audit-logs.index', ['tab' => 'audit', 'user_id' => $log->user_id]) }}"
                                           class="text-xs font-medium text-blue-600 hover:underline truncate max-w-[120px] block"
                                           title="{{ $log->user->name }}">
                                            {{ Str::limit($log->user->name, 20) }}
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-400">Guest</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-bold {{ $log->method_badge }}">
                                        {{ $log->method }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 max-w-[320px]">
                                    @if($log->route_name)
                                        <span class="block text-xs font-mono font-semibold text-gray-800 truncate" title="{{ $log->url }}">
                                            {{ $log->route_name }}
                                        </span>
                                    @endif
                                    <span class="block text-xs text-gray-400 truncate font-mono" title="{{ $log->url }}">
                                        {{ Str::limit($log->url, 65) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-bold {{ $log->status_badge }}">
                                        {{ $log->status_code }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('admin.audit-logs.index', ['tab' => 'audit', 'search' => $log->ip_address]) }}"
                                       class="text-xs font-mono text-gray-600 hover:text-blue-600">
                                        {{ $log->ip_address }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    <button type="button"
                                            onclick="showPayload({{ $log->id }})"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition shadow-sm
                                            {{ $hasError ? 'bg-red-100 text-red-700 hover:bg-red-200 border border-red-200' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                        @if($hasError)
                                            <svg class="w-3.5 h-3.5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                            </svg>
                                            <span>Detail Error</span>
                                        @else
                                            <svg class="w-3.5 h-3.5 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            <span>Lihat</span>
                                        @endif
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Belum ada audit log yang sesuai kriteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">{{ $logs->links() }}</div>

    @else
        {{-- ========================================================================= --}}
        {{-- TAB 2: SYSTEM LOGS (laravel.log) --}}
        {{-- ========================================================================= --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-4 mb-6 shadow-sm">
            <form method="GET" class="flex flex-wrap items-end gap-3">
                <input type="hidden" name="tab" value="system">
                <div class="flex-1 min-w-[240px]">
                    <label class="block text-xs text-gray-500 font-medium mb-1">Cari Pesan Log / Error</label>
                    <input type="text" name="sys_search" placeholder="Cari teks error, controller, class..." value="{{ request('sys_search') }}"
                           class="w-full rounded-xl border border-gray-300 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 font-medium mb-1">Tingkat / Level</label>
                    <select name="sys_level" class="rounded-xl border border-gray-300 px-4 py-2 text-sm">
                        <option value="">Semua Level</option>
                        <option value="ERROR" {{ strtoupper(request('sys_level')) === 'ERROR' ? 'selected' : '' }}>🚨 ERROR</option>
                        <option value="WARNING" {{ strtoupper(request('sys_level')) === 'WARNING' ? 'selected' : '' }}>⚠️ WARNING</option>
                        <option value="INFO" {{ strtoupper(request('sys_level')) === 'INFO' ? 'selected' : '' }}>ℹ️ INFO</option>
                        <option value="DEBUG" {{ strtoupper(request('sys_level')) === 'DEBUG' ? 'selected' : '' }}>🐞 DEBUG</option>
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 shadow-sm transition">
                    Filter Log
                </button>
                @if(request()->hasAny(['sys_search', 'sys_level']))
                    <a href="{{ route('admin.audit-logs.index', ['tab' => 'system']) }}" class="text-sm text-gray-500 hover:text-gray-700 py-2">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- Log Entries List --}}
        <div class="space-y-4">
            @forelse($systemLogs['entries'] as $entry)
                @php
                    $isError = in_array($entry['level'], ['ERROR', 'CRITICAL', 'ALERT', 'EMERGENCY']);
                    $isWarning = $entry['level'] === 'WARNING';
                @endphp
                <div class="bg-white rounded-2xl border {{ $isError ? 'border-red-200 shadow-red-50/50' : ($isWarning ? 'border-amber-200' : 'border-gray-200') }} shadow-sm overflow-hidden"
                     x-data="{ showTrace: false }">
                    <div class="p-4 sm:p-5 {{ $isError ? 'bg-red-50/20' : ($isWarning ? 'bg-amber-50/20' : '') }}">
                        <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold
                                    {{ $isError ? 'bg-red-100 text-red-700 border border-red-200' : ($isWarning ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700') }}">
                                    {{ $entry['level'] }}
                                </span>
                                <span class="text-xs font-mono text-gray-500">{{ $entry['timestamp'] }}</span>
                                <span class="text-xs px-2 py-0.5 rounded bg-gray-100 text-gray-600 font-mono">{{ $entry['env'] }}</span>
                            </div>
                            @if($entry['trace'])
                                <button type="button" @click="showTrace = !showTrace"
                                        class="text-xs font-semibold px-2.5 py-1 rounded-lg border transition flex items-center gap-1
                                        {{ $isError ? 'border-red-200 text-red-700 hover:bg-red-100' : 'border-gray-200 text-gray-700 hover:bg-gray-100' }}">
                                    <span x-text="showTrace ? 'Tutup Stack Trace' : 'Lihat Stack Trace'"></span>
                                    <svg class="w-3.5 h-3.5 transition-transform" :class="showTrace ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                            @endif
                        </div>
                        <p class="text-sm font-semibold {{ $isError ? 'text-red-900' : 'text-gray-900' }} break-words font-mono">
                            {{ $entry['message'] }}
                        </p>
                    </div>

                    @if($entry['trace'])
                        <div x-show="showTrace" x-cloak class="border-t border-gray-100 bg-gray-900 p-4">
                            <pre class="text-xs text-gray-300 font-mono overflow-x-auto whitespace-pre-wrap max-h-96 leading-relaxed">{{ $entry['trace'] }}</pre>
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="font-medium text-gray-600">Tidak ada log error yang ditemukan.</p>
                    <p class="text-xs text-gray-400 mt-1">Sistem berjalan dengan normal.</p>
                </div>
            @endforelse
        </div>
    @endif

    {{-- ========================================================================= --}}
    {{-- MODAL DETAIL AUDIT LOG (RICH ERROR & PAYLOAD INSPECTION) --}}
    {{-- ========================================================================= --}}
    <div id="payloadModal" class="fixed inset-0 z-50 hidden" x-data="{ show: false }" x-show="show" x-cloak>
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="show = false; document.getElementById('payloadModal').classList.add('hidden')"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[88vh] flex flex-col overflow-hidden border border-gray-100">
                
                {{-- Modal Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center gap-2.5">
                        <span id="modalMethodBadge" class="px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">POST</span>
                        <span id="modalStatusBadge" class="px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">200</span>
                        <h3 class="font-bold text-gray-900 text-sm">Detail Audit Log</h3>
                    </div>
                    <button @click="show = false; document.getElementById('payloadModal').classList.add('hidden')"
                            class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-200 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="p-6 overflow-y-auto space-y-5 flex-1">
                    
                    {{-- Error Card (Jika Terjadi Error) --}}
                    <div id="errorCard" class="hidden rounded-xl border border-red-300 bg-red-50 p-4 space-y-2">
                        <div class="flex items-start gap-2.5">
                            <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                            <div class="flex-1">
                                <h4 class="text-xs font-bold uppercase text-red-800 tracking-wider">Detail Kesalahan / Exception</h4>
                                <p id="errorMessage" class="text-sm font-semibold text-red-900 mt-1 font-mono break-words"></p>
                                <p id="errorLocation" class="text-xs text-red-700 mt-1 font-mono break-all"></p>
                            </div>
                        </div>

                        <div id="errorTraceContainer" class="hidden mt-3 pt-3 border-t border-red-200">
                            <p class="text-xs font-bold text-red-800 mb-1">Stack Trace:</p>
                            <pre id="errorTrace" class="bg-gray-900 text-red-300 text-xs rounded-xl p-3 overflow-x-auto whitespace-pre-wrap max-h-48 font-mono"></pre>
                        </div>
                    </div>

                    {{-- Uploaded Files Info --}}
                    <div id="filesCard" class="hidden rounded-xl border border-blue-200 bg-blue-50/50 p-4">
                        <h4 class="text-xs font-bold uppercase text-blue-800 tracking-wider mb-2 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                            File yang Diunggah
                        </h4>
                        <div id="filesList" class="space-y-1.5"></div>
                    </div>

                    {{-- Metadata Grid --}}
                    <div id="payloadMeta" class="grid grid-cols-2 gap-3 text-sm bg-gray-50 rounded-xl p-4 border border-gray-100"></div>

                    {{-- Request Input Payload --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-xs font-bold text-gray-600 uppercase tracking-wider">Parameter / Input Form</p>
                            <span id="payloadSizeBadge" class="text-xs text-gray-400"></span>
                        </div>
                        <pre id="payloadContent" class="bg-gray-900 text-emerald-400 text-xs rounded-xl p-4 overflow-x-auto whitespace-pre-wrap max-h-56 font-mono"></pre>
                    </div>

                </div>

                {{-- Modal Footer --}}
                <div class="px-6 py-3 bg-gray-50 border-t border-gray-200 text-right">
                    <button type="button"
                            @click="show = false; document.getElementById('payloadModal').classList.add('hidden')"
                            class="px-4 py-2 rounded-xl bg-gray-200 text-gray-700 text-xs font-semibold hover:bg-gray-300 transition">
                        Tutup
                    </button>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script nonce="{{ $cspNonce ?? '' }}">
    function showPayload(logId) {
        const modal = document.getElementById('payloadModal');
        const metaEl = document.getElementById('payloadMeta');
        const contentEl = document.getElementById('payloadContent');
        const methodBadge = document.getElementById('modalMethodBadge');
        const statusBadge = document.getElementById('modalStatusBadge');
        const errorCard = document.getElementById('errorCard');
        const errorMessage = document.getElementById('errorMessage');
        const errorLocation = document.getElementById('errorLocation');
        const errorTrace = document.getElementById('errorTrace');
        const errorTraceContainer = document.getElementById('errorTraceContainer');
        const filesCard = document.getElementById('filesCard');
        const filesList = document.getElementById('filesList');

        metaEl.innerHTML = '<p class="col-span-2 text-gray-400 text-sm">Memuat data...</p>';
        contentEl.textContent = '';
        errorCard.classList.add('hidden');
        errorTraceContainer.classList.add('hidden');
        filesCard.classList.add('hidden');
        filesList.innerHTML = '';

        modal.classList.remove('hidden');
        modal.__x.$data.show = true;

        fetch(`{{ url('admin/audit-logs') }}/${logId}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            // Badges
            methodBadge.textContent = data.method;
            methodBadge.className = `px-2.5 py-1 rounded-full text-xs font-bold ${getMethodClass(data.method)}`;

            statusBadge.textContent = data.status;
            statusBadge.className = `px-2.5 py-1 rounded-full text-xs font-bold ${getStatusClass(data.status)}`;

            // Metadata Grid
            metaEl.innerHTML = `
                <div><span class="text-xs text-gray-500 font-medium">Pengguna</span><p class="font-semibold text-gray-900 mt-0.5">${data.user}</p></div>
                <div><span class="text-xs text-gray-500 font-medium">Waktu Request</span><p class="font-semibold text-gray-900 mt-0.5">${data.created_at}</p></div>
                <div><span class="text-xs text-gray-500 font-medium">Alamat IP</span><p class="font-mono text-gray-900 mt-0.5">${data.ip}</p></div>
                <div><span class="text-xs text-gray-500 font-medium">Nama Route</span><p class="font-mono text-gray-900 mt-0.5">${data.route_name || '-'}</p></div>
                <div class="col-span-2"><span class="text-xs text-gray-500 font-medium">URL Lengkap</span><p class="font-mono text-xs text-gray-800 break-all mt-0.5 bg-white p-2 rounded-lg border border-gray-200">${data.url}</p></div>
                <div class="col-span-2"><span class="text-xs text-gray-500 font-medium">User Agent</span><p class="text-xs text-gray-600 break-all mt-0.5">${data.user_agent || '-'}</p></div>
            `;

            // Error Card Inspection
            if (data.error) {
                errorCard.classList.remove('hidden');
                errorMessage.textContent = data.error.message || 'Terjadi kesalahan server.';
                
                if (data.error.file) {
                    errorLocation.textContent = `Lokasi: ${data.error.file}${data.error.line ? ':' + data.error.line : ''}`;
                } else if (data.error.errors) {
                    errorLocation.innerHTML = data.error.errors.map(e => `• ${e}`).join('<br>');
                } else {
                    errorLocation.textContent = '';
                }

                if (data.error.trace && Array.isArray(data.error.trace) && data.error.trace.length > 0) {
                    errorTraceContainer.classList.remove('hidden');
                    errorTrace.textContent = data.error.trace.join("\n");
                }
            } else if (data.status >= 500) {
                errorCard.classList.remove('hidden');
                errorMessage.textContent = `Server merespon dengan status HTTP ${data.status}. Silakan periksa tab "System Error Log (laravel.log)" untuk melihat rincian error dari sistem.`;
                errorLocation.textContent = '';
            }

            // Files Card
            if (data.files && Object.keys(data.files).length > 0) {
                filesCard.classList.remove('hidden');
                let filesHtml = '';
                for (const [key, f] of Object.entries(data.files)) {
                    if (f && typeof f === 'object') {
                        filesHtml += `
                            <div class="flex items-center justify-between text-xs font-mono bg-white p-2.5 rounded-lg border border-blue-100">
                                <div>
                                    <span class="font-bold text-gray-700">${key}:</span>
                                    <span class="text-blue-700 font-semibold">${f.original_name || '-'}</span>
                                    <span class="text-gray-400">(${f.mime_type || '-'})</span>
                                </div>
                                <span class="px-2 py-0.5 rounded bg-gray-100 font-medium text-gray-600">${f.size || '-'}</span>
                            </div>
                        `;
                    }
                }
                filesList.innerHTML = filesHtml || '<p class="text-xs text-gray-500">File metadata tidak tersedia.</p>';
            }

            // Input Payload
            if (data.input && Object.keys(data.input).length > 0) {
                contentEl.textContent = JSON.stringify(data.input, null, 2);
            } else {
                contentEl.textContent = 'Tidak ada parameter form yang dikirimkan (payload kosong).';
            }
        })
        .catch(err => {
            metaEl.innerHTML = '<p class="col-span-2 text-red-500 text-sm">Gagal memuat detail log.</p>';
            contentEl.textContent = err.message;
        });
    }

    function getMethodClass(method) {
        switch(method) {
            case 'POST': return 'bg-emerald-100 text-emerald-800';
            case 'PUT':
            case 'PATCH': return 'bg-amber-100 text-amber-800';
            case 'DELETE': return 'bg-red-100 text-red-800';
            default: return 'bg-blue-100 text-blue-800';
        }
    }

    function getStatusClass(status) {
        if (status >= 200 && status < 300) return 'bg-emerald-100 text-emerald-800';
        if (status >= 300 && status < 400) return 'bg-blue-100 text-blue-800';
        if (status >= 400 && status < 500) return 'bg-amber-100 text-amber-800';
        return 'bg-red-100 text-red-800';
    }
</script>
@endpush
