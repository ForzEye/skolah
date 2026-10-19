<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Halaman daftar audit log dengan filter, search, dan System Error Log viewer.
     */
    public function index(Request $request)
    {
        $currentTab = $request->input('tab', 'audit');

        // ── 1. Query Audit Logs (DB) ──────────────────────────────────────────
        $query = AuditLog::with('user')->latest('created_at');

        // Filter: search keyword (url, route_name, ip_address)
        if ($search = $request->input('search')) {
            $query->search($search);
        }

        // Filter: user_id
        if ($userId = $request->input('user_id')) {
            $query->byUser((int) $userId);
        }

        // Filter: HTTP method
        if ($method = $request->input('method')) {
            $query->byMethod($method);
        }

        // Filter: route prefix (admin, instructor, checkout, webhook)
        if ($routePrefix = $request->input('route')) {
            $query->byRoute($routePrefix);
        }

        // Filter: status code range
        if ($status = $request->input('status')) {
            match ($status) {
                '2xx' => $query->whereBetween('status_code', [200, 299]),
                '3xx' => $query->whereBetween('status_code', [300, 399]),
                '4xx' => $query->whereBetween('status_code', [400, 499]),
                '5xx' => $query->whereBetween('status_code', [500, 599]),
                default => null,
            };
        }

        // Filter: date range
        if ($from = $request->input('from')) {
            $query->where('created_at', '>=', $from);
        }
        if ($to = $request->input('to')) {
            $query->where('created_at', '<=', $to . ' 23:59:59');
        }

        $logs = $query->paginate(30)->withQueryString();

        // Data untuk dropdown filter
        $methods = ['POST', 'PUT', 'PATCH', 'DELETE'];
        $routePrefixes = [
            'admin.'      => 'Admin',
            'instructor.' => 'Instructor',
            'checkout'    => 'Checkout',
            'midtrans'    => 'Webhook',
        ];

        // Statistik ringkas
        $stats = [
            'total_today'   => AuditLog::whereDate('created_at', today())->count(),
            'total_week'    => AuditLog::where('created_at', '>=', now()->subWeek())->count(),
            'unique_users'  => AuditLog::whereDate('created_at', today())->distinct('user_id')->count('user_id'),
            'error_count'   => AuditLog::where('created_at', '>=', now()->subDay())
                                        ->where('status_code', '>=', 400)->count(),
        ];

        // ── 2. System Error Logs (laravel.log) ────────────────────────────────
        $systemLogs = $this->getSystemLogs($request);

        return view('admin.audit-logs.index', compact(
            'logs', 'methods', 'routePrefixes', 'stats', 'systemLogs', 'currentTab'
        ));
    }

    /**
     * Detail payload audit log (modal/JSON).
     */
    public function show(AuditLog $auditLog)
    {
        $auditLog->load('user');

        $payload = $auditLog->payload ?? [];

        // Deteksi detail error dan files
        $error = $payload['_error'] ?? null;
        $files = $payload['_files'] ?? null;

        // Input murni (tanpa metadata internal)
        $input = $payload;
        unset($input['_error'], $input['_files'], $input['_notice'], $input['_size']);

        return response()->json([
            'id'         => $auditLog->id,
            'user'       => $auditLog->user?->name ?? 'Guest',
            'method'     => $auditLog->method,
            'url'        => $auditLog->url,
            'route_name' => $auditLog->route_name,
            'status'     => $auditLog->status_code,
            'ip'         => $auditLog->ip_address,
            'user_agent' => $auditLog->user_agent,
            'created_at' => $auditLog->created_at->format('d M Y H:i:s'),
            'error'      => $error,
            'files'      => $files,
            'input'      => ! empty($input) ? $input : null,
            'raw'        => $payload,
        ]);
    }

    /**
     * Bersihkan file log sistem (laravel.log).
     */
    public function clearSystemLogs(Request $request)
    {
        try {
            $logDir = storage_path('logs');
            $files  = glob($logDir . '/*.log');

            foreach ($files as $file) {
                if (is_file($file)) {
                    @file_put_contents($file, '');
                }
            }

            return back()->with('success', 'File system log berhasil dikosongkan.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal mengosongkan log: ' . $e->getMessage());
        }
    }

    /**
     * Membaca dan mem-parsing log Laravel dari storage/logs/
     */
    protected function getSystemLogs(Request $request): array
    {
        $logDir = storage_path('logs');
        if (! is_dir($logDir)) {
            return ['entries' => [], 'file' => null, 'size' => '0 B', 'total' => 0];
        }

        // Cari file log terbaru
        $files = glob($logDir . '/*.log');
        if (empty($files)) {
            return ['entries' => [], 'file' => null, 'size' => '0 B', 'total' => 0];
        }

        // Urutkan file berdasarkan waktu modifikasi terbaru
        usort($files, fn($a, $b) => filemtime($b) <=> filemtime($a));
        $selectedFile = $files[0];

        $filterLevel = strtoupper($request->input('sys_level', ''));
        $search = strtolower($request->input('sys_search', ''));

        $maxBytes = 1024 * 1024 * 3; // Max baca 3MB terakhir
        $fileSize = filesize($selectedFile);

        $fp = fopen($selectedFile, 'r');
        if (! $fp) {
            return ['entries' => [], 'file' => basename($selectedFile), 'size' => formatBytes($fileSize), 'total' => 0];
        }

        if ($fileSize > $maxBytes) {
            fseek($fp, $fileSize - $maxBytes);
            // Buang baris parsial pertama
            fgets($fp);
        }

        $rawContent = '';
        while (! feof($fp)) {
            $rawContent .= fread($fp, 8192);
        }
        fclose($fp);

        // Pattern Monolog: [YYYY-MM-DD HH:MM:SS] env.LEVEL: message
        $pattern = '/^\[(\d{4}-\d{2}-\d{2}[ T]\d{2}:\d{2}:\d{2}(?:\.\d+)?)\]\s+([a-zA-Z0-9_-]+)\.([A-Z]+):\s+(.*)$/m';

        preg_match_all($pattern, $rawContent, $matches, PREG_OFFSET_CAPTURE);

        $entries = [];
        $totalMatches = count($matches[0]);

        for ($i = 0; $i < $totalMatches; $i++) {
            $fullMatch = $matches[0][$i][0];
            $offset = $matches[0][$i][1];
            $timestamp = $matches[1][$i][0];
            $env = $matches[2][$i][0];
            $level = strtoupper($matches[3][$i][0]);
            $headerMessage = $matches[4][$i][0];

            $nextOffset = ($i + 1 < $totalMatches) ? $matches[0][$i + 1][1] : strlen($rawContent);
            $body = substr($rawContent, $offset + strlen($fullMatch), $nextOffset - ($offset + strlen($fullMatch)));
            $body = trim($body);

            // Filter level
            if ($filterLevel && $level !== $filterLevel) {
                continue;
            }

            // Filter search
            if ($search) {
                $combined = strtolower($headerMessage . ' ' . $body);
                if (! str_contains($combined, $search)) {
                    continue;
                }
            }

            $entries[] = [
                'id'        => $i + 1,
                'timestamp' => $timestamp,
                'env'       => $env,
                'level'     => $level,
                'message'   => $headerMessage,
                'trace'     => $body ?: null,
            ];
        }

        // Urutkan dari yang terbaru ke terlama
        $entries = array_reverse($entries);

        return [
            'entries' => array_slice($entries, 0, 100),
            'file'    => basename($selectedFile),
            'size'    => formatBytes($fileSize),
            'total'   => count($entries),
        ];
    }
}
