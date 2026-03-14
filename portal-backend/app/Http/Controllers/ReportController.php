<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleComment;
use App\Models\ArticleLike;
use App\Models\Category;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\BlockedClient;
use App\Models\Gallery;
use App\Models\SiteSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display the reports index page.
     */
    public function index()
    {
        return view('reports.index');
    }

    /**
     * Get common report settings from site settings.
     */
    private function getReportSettings(): array
    {
        return [
            // General Site Settings (fallback)
            'site_name' => SiteSetting::get('site_name', 'Portal Admin'),
            'site_address' => SiteSetting::get('site_address', ''),
            'site_phone' => SiteSetting::get('site_phone', ''),
            'site_email' => SiteSetting::get('site_email', ''),
            'site_city' => SiteSetting::get('site_city', ''),

            // Letterhead Settings - Organization Hierarchy
            'letterhead_parent_org_1' => SiteSetting::get('letterhead_parent_org_1', ''),
            'letterhead_parent_org_2' => SiteSetting::get('letterhead_parent_org_2', ''),
            'letterhead_org_name' => SiteSetting::get('letterhead_org_name', ''),

            // Letterhead Settings - Address
            'letterhead_street' => SiteSetting::get('letterhead_street', ''),
            'letterhead_district' => SiteSetting::get('letterhead_district', ''),
            'letterhead_city' => SiteSetting::get('letterhead_city', ''),
            'letterhead_province' => SiteSetting::get('letterhead_province', ''),
            'letterhead_postal_code' => SiteSetting::get('letterhead_postal_code', ''),

            // Letterhead Settings - Contact
            'letterhead_phone' => SiteSetting::get('letterhead_phone', ''),
            'letterhead_fax' => SiteSetting::get('letterhead_fax', ''),
            'letterhead_email' => SiteSetting::get('letterhead_email', ''),
            'letterhead_website' => SiteSetting::get('letterhead_website', ''),

            // Media Settings
            'logo_url' => SiteSetting::get('logo_url', ''),
            'letterhead_url' => SiteSetting::get('letterhead_url', ''),
            'signature_url' => SiteSetting::get('signature_url', ''),
            'stamp_url' => SiteSetting::get('stamp_url', ''),
            
            // Signature & Stamp Size Settings
            'signature_size' => (int) SiteSetting::get('signature_size', 80),
            'stamp_size' => (int) SiteSetting::get('stamp_size', 85),

            // Leader / Organization Settings
            'leader_name' => SiteSetting::get('leader_name', ''),
            'leader_title' => SiteSetting::get('leader_title', ''),
            'leader_nip' => SiteSetting::get('leader_nip', ''),
            'signature_cc' => SiteSetting::get('signature_cc', ''),

            // Print Info
            'printed_by' => auth()->user()->name ?? 'System',
            'printed_at' => Carbon::now()->locale('id')->isoFormat('D MMMM Y, HH:mm'),
        ];
    }

    /**
     * Parse date range from request.
     */
    private function parseDateRange(Request $request): array
    {
        $hasStartDate = $request->filled('start_date');
        $hasEndDate = $request->filled('end_date');
        $hasDateFilter = $hasStartDate || $hasEndDate;

        $startDate = $hasStartDate 
            ? Carbon::parse($request->input('start_date'))->startOfDay() 
            : null;
        
        $endDate = $hasEndDate 
            ? Carbon::parse($request->input('end_date'))->endOfDay() 
            : null;

        return [$startDate, $endDate, $hasDateFilter];
    }

    /**
     * Format date for display.
     */
    private function formatDate(?Carbon $date): string
    {
        return $date ? $date->locale('id')->isoFormat('D MMMM Y') : '';
    }

    /**
     * Apply date filter to a query builder.
     */
    private function applyDateFilter($query, ?Carbon $startDate, ?Carbon $endDate, bool $hasDateFilter, string $column = 'created_at')
    {
        if ($hasDateFilter) {
            if ($startDate && $endDate) {
                $query->whereBetween($column, [$startDate, $endDate]);
            } elseif ($startDate) {
                $query->where($column, '>=', $startDate);
            } elseif ($endDate) {
                $query->where($column, '<=', $endDate);
            }
        }
        return $query;
    }

    /**
     * ============================================================
     * REPORT 1: Laporan Data Pengguna
     * ============================================================
     */
    public function generateUserReport(Request $request)
    {
        [$startDate, $endDate, $hasDateFilter] = $this->parseDateRange($request);
        
        $query = User::query();
        $this->applyDateFilter($query, $startDate, $endDate, $hasDateFilter);

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('created_at', 'desc')->get();

        $data = [
            'settings' => $this->getReportSettings(),
            'title' => 'Laporan Data Pengguna',
            'date_from' => $this->formatDate($startDate),
            'date_to' => $this->formatDate($endDate),
            'has_date_filter' => $hasDateFilter,
            'items' => $users,
            'doc_number' => '001',
        ];

        $pdf = Pdf::loadView('reports.pdf.users', $data);
        $pdf->setPaper('A4', 'portrait');
        
        $filename = 'laporan-pengguna-' . ($startDate ? $startDate->format('Ymd') : 'all') . '-' . ($endDate ? $endDate->format('Ymd') : 'now') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * ============================================================
     * REPORT 2: Laporan Data Berita/Artikel
     * ============================================================
     */
    public function generateArticleReport(Request $request)
    {
        [$startDate, $endDate, $hasDateFilter] = $this->parseDateRange($request);
        
        $query = Article::with(['author', 'categoryRelation']);
        $this->applyDateFilter($query, $startDate, $endDate, $hasDateFilter);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('author_id')) {
            $query->where('author_id', $request->author_id);
        }

        $articles = $query->orderBy('created_at', 'desc')->get();

        $data = [
            'settings' => $this->getReportSettings(),
            'title' => 'Laporan Data Berita',
            'date_from' => $this->formatDate($startDate),
            'date_to' => $this->formatDate($endDate),
            'has_date_filter' => $hasDateFilter,
            'items' => $articles,
            'doc_number' => '002',
        ];

        $pdf = Pdf::loadView('reports.pdf.articles', $data);
        $pdf->setPaper('A4', 'landscape');
        
        $filename = 'laporan-berita-' . ($startDate ? $startDate->format('Ymd') : 'all') . '-' . ($endDate ? $endDate->format('Ymd') : 'now') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * ============================================================
     * REPORT 3: Laporan Data Kategori
     * ============================================================
     */
    public function generateCategoryReport(Request $request)
    {
        [$startDate, $endDate, $hasDateFilter] = $this->parseDateRange($request);
        
        $query = Category::withCount('articles');
        $this->applyDateFilter($query, $startDate, $endDate, $hasDateFilter);

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active === 'true');
        }

        $categories = $query->orderBy('sort_order', 'asc')->get();

        $data = [
            'settings' => $this->getReportSettings(),
            'title' => 'Laporan Data Kategori',
            'date_from' => $this->formatDate($startDate),
            'date_to' => $this->formatDate($endDate),
            'has_date_filter' => $hasDateFilter,
            'items' => $categories,
            'doc_number' => '003',
        ];

        $pdf = Pdf::loadView('reports.pdf.categories', $data);
        $pdf->setPaper('A4', 'portrait');
        
        $filename = 'laporan-kategori-' . ($startDate ? $startDate->format('Ymd') : 'all') . '-' . ($endDate ? $endDate->format('Ymd') : 'now') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * ============================================================
     * REPORT 4: Laporan Data Gallery/Media
     * ============================================================
     */
    public function generateGalleryReport(Request $request)
    {
        [$startDate, $endDate, $hasDateFilter] = $this->parseDateRange($request);
        
        $query = Gallery::with('uploader');
        $this->applyDateFilter($query, $startDate, $endDate, $hasDateFilter);

        if ($request->filled('media_type')) {
            $query->where('media_type', $request->media_type);
        }

        if ($request->filled('album')) {
            $query->where('album', $request->album);
        }

        if ($request->filled('is_published')) {
            $query->where('is_published', $request->is_published === 'true');
        }

        $galleries = $query->orderBy('created_at', 'desc')->get();

        $data = [
            'settings' => $this->getReportSettings(),
            'title' => 'Laporan Data Gallery',
            'date_from' => $this->formatDate($startDate),
            'date_to' => $this->formatDate($endDate),
            'has_date_filter' => $hasDateFilter,
            'items' => $galleries,
            'doc_number' => '004',
        ];

        $pdf = Pdf::loadView('reports.pdf.galleries', $data);
        $pdf->setPaper('A4', 'portrait');
        
        $filename = 'laporan-gallery-' . ($startDate ? $startDate->format('Ymd') : 'all') . '-' . ($endDate ? $endDate->format('Ymd') : 'now') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * ============================================================
     * REPORT 5: Laporan Interaksi Publik
     * ============================================================
     */
    public function generateInteractionReport(Request $request)
    {
        [$startDate, $endDate, $hasDateFilter] = $this->parseDateRange($request);
        
        // Get articles with counts
        $query = Article::withCount([
                'comments',
                'comments as spam_comments_count' => function ($q) {
                    $q->where('status', 'spam');
                },
                'likes',
            ])
            ->where('status', 'published');

        $this->applyDateFilter($query, $startDate, $endDate, $hasDateFilter, 'published_at');

        $articles = $query->orderByDesc('views')->get();

        // For each article, find the top commenter
        $articles->each(function ($article) {
            $topCommenter = ArticleComment::where('article_id', $article->id)
                ->where('status', 'visible')
                ->select('user_id', DB::raw('COUNT(*) as comment_count'))
                ->groupBy('user_id')
                ->orderByDesc('comment_count')
                ->with('user:id,name')
                ->first();
            
            $article->top_commenter_name = $topCommenter?->user?->name ?? '-';
        });

        // Summary stats
        $summary = [
            'total_articles' => $articles->count(),
            'total_comments' => $articles->sum('comments_count'),
            'total_spam' => $articles->sum('spam_comments_count'),
            'total_likes' => $articles->sum('likes_count'),
            'total_views' => $articles->sum('views'),
        ];

        $data = [
            'settings' => $this->getReportSettings(),
            'title' => 'Laporan Interaksi Publik',
            'date_from' => $this->formatDate($startDate),
            'date_to' => $this->formatDate($endDate),
            'has_date_filter' => $hasDateFilter,
            'items' => $articles,
            'summary' => $summary,
            'doc_number' => '005',
        ];

        $pdf = Pdf::loadView('reports.pdf.interactions', $data);
        $pdf->setPaper('A4', 'landscape');
        
        $filename = 'laporan-interaksi-' . ($startDate ? $startDate->format('Ymd') : 'all') . '-' . ($endDate ? $endDate->format('Ymd') : 'now') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * ============================================================
     * REPORT 6: Laporan Activity Log
     * ============================================================
     */
    public function generateActivityLogReport(Request $request)
    {
        [$startDate, $endDate, $hasDateFilter] = $this->parseDateRange($request);
        
        $query = ActivityLog::with('user');
        $this->applyDateFilter($query, $startDate, $endDate, $hasDateFilter);

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $activityLogs = $query->orderBy('created_at', 'desc')->get();

        $data = [
            'settings' => $this->getReportSettings(),
            'title' => 'Laporan Activity Log',
            'date_from' => $this->formatDate($startDate),
            'date_to' => $this->formatDate($endDate),
            'has_date_filter' => $hasDateFilter,
            'items' => $activityLogs,
            'doc_number' => '006',
        ];

        $pdf = Pdf::loadView('reports.pdf.activity-logs', $data);
        $pdf->setPaper('A4', 'landscape');
        
        $filename = 'laporan-activity-log-' . ($startDate ? $startDate->format('Ymd') : 'all') . '-' . ($endDate ? $endDate->format('Ymd') : 'now') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * ============================================================
     * REPORT 7: Laporan Keamanan & IP Terblokir
     * ============================================================
     */
    public function generateSecurityReport(Request $request)
    {
        [$startDate, $endDate, $hasDateFilter] = $this->parseDateRange($request);
        
        $query = BlockedClient::query();
        $this->applyDateFilter($query, $startDate, $endDate, $hasDateFilter);

        if ($request->filled('is_blocked')) {
            $query->where('is_blocked', $request->is_blocked === 'true');
        }

        $blockedClients = $query->orderBy('created_at', 'desc')->get();

        // Security logs from ActivityLog
        $securityLogQuery = ActivityLog::securityLogs()->with('user');
        $this->applyDateFilter($securityLogQuery, $startDate, $endDate, $hasDateFilter);
        $securityLogs = $securityLogQuery->orderBy('created_at', 'desc')->limit(50)->get();

        // Security summary
        $securitySummary = [
            'total_blocked' => BlockedClient::where('is_blocked', true)->count(),
            'active_blocks' => BlockedClient::activeBlocks()->count(),
            'total_failed_logins' => ActivityLog::where('action', ActivityLog::ACTION_LOGIN_FAILED)->count(),
            'recent_failed_logins' => ActivityLog::where('action', ActivityLog::ACTION_LOGIN_FAILED)
                ->where('created_at', '>=', now()->subDays(7))->count(),
        ];

        $data = [
            'settings' => $this->getReportSettings(),
            'title' => 'Laporan Keamanan & IP Terblokir',
            'date_from' => $this->formatDate($startDate),
            'date_to' => $this->formatDate($endDate),
            'has_date_filter' => $hasDateFilter,
            'items' => $blockedClients,
            'security_logs' => $securityLogs,
            'security_summary' => $securitySummary,
            'doc_number' => '007',
        ];

        $pdf = Pdf::loadView('reports.pdf.security', $data);
        $pdf->setPaper('A4', 'landscape');
        
        $filename = 'laporan-keamanan-' . ($startDate ? $startDate->format('Ymd') : 'all') . '-' . ($endDate ? $endDate->format('Ymd') : 'now') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * ============================================================
     * REPORT 8: Laporan Statistik & Rekapitulasi
     * Single-table executive summary — no duplicate data from other reports.
     * ============================================================
     */
    public function generateStatisticsReport(Request $request)
    {
        // Build flat rows: each row is [indikator, jumlah]
        $rows = [];

        // Pengguna
        $rows[] = ['Total Pengguna Terdaftar', number_format(User::count())];
        $usersByRole = User::select('role', DB::raw('COUNT(*) as total'))
            ->groupBy('role')->orderByDesc('total')->get();
        foreach ($usersByRole as $r) {
            $label = match($r->role) {
                'super_admin' => 'Super Admin',
                'admin' => 'Admin',
                'editor' => 'Editor',
                'author' => 'Author',
                'member' => 'Member',
                default => ucfirst($r->role),
            };
            $rows[] = ['  — ' . $label, number_format($r->total)];
        }

        // Artikel
        $rows[] = ['Total Artikel / Berita', number_format(Article::count())];
        $rows[] = ['  — Published', number_format(Article::where('status', 'published')->count())];
        $rows[] = ['  — Draft', number_format(Article::where('status', 'draft')->count())];
        $rows[] = ['  — Pending', number_format(Article::where('status', 'pending')->count())];
        $rows[] = ['Total Kategori', number_format(Category::count())];
        $rows[] = ['Total Views Keseluruhan', number_format(Article::sum('views'))];

        // Gallery
        $rows[] = ['Total Media Gallery', number_format(Gallery::count())];
        $rows[] = ['  — Gambar (Image)', number_format(Gallery::where('media_type', 'image')->count())];
        $rows[] = ['  — Video', number_format(Gallery::where('media_type', 'video')->count())];
        $rows[] = ['  — Sudah Dipublikasikan', number_format(Gallery::where('is_published', true)->count())];

        // Interaksi
        $rows[] = ['Total Komentar', number_format(ArticleComment::count())];
        $rows[] = ['  — Komentar Visible', number_format(ArticleComment::where('status', 'visible')->count())];
        $rows[] = ['  — Komentar Spam', number_format(ArticleComment::where('status', 'spam')->count())];
        $rows[] = ['Total Likes', number_format(ArticleLike::count())];

        // Keamanan
        $rows[] = ['IP Terblokir Aktif', number_format(BlockedClient::activeBlocks()->count())];
        $rows[] = ['Login Gagal (7 Hari Terakhir)', number_format(
            ActivityLog::where('action', ActivityLog::ACTION_LOGIN_FAILED)
                ->where('created_at', '>=', now()->subDays(7))->count()
        )];

        // Activity Log
        $rows[] = ['Total Aktivitas (7 Hari Terakhir)', number_format(
            ActivityLog::where('created_at', '>=', now()->subDays(7))->count()
        )];
        $rows[] = ['Total Aktivitas (Keseluruhan)', number_format(ActivityLog::count())];

        $data = [
            'settings' => $this->getReportSettings(),
            'title' => 'Laporan Statistik & Rekapitulasi',
            'rows' => $rows,
            'doc_number' => '008',
        ];

        $pdf = Pdf::loadView('reports.pdf.statistics', $data);
        $pdf->setPaper('A4', 'portrait');
        
        $filename = 'laporan-statistik-rekapitulasi-' . now()->format('Ymd-His') . '.pdf';
        
        return $pdf->download($filename);
    }
}
