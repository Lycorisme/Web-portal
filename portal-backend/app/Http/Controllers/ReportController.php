<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\BlockedClient;
use App\Models\Gallery;
use App\Models\SiteSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
        $startDate = $request->input('start_date') 
            ? Carbon::parse($request->input('start_date'))->startOfDay() 
            : Carbon::now()->startOfDay();
        
        $endDate = $request->input('end_date') 
            ? Carbon::parse($request->input('end_date'))->endOfDay() 
            : Carbon::now()->endOfDay();

        return [$startDate, $endDate];
    }

    /**
     * Format date range for display.
     */
    private function formatDateRange(Carbon $startDate, Carbon $endDate): string
    {
        return $startDate->locale('id')->isoFormat('D MMMM Y') . ' - ' . $endDate->locale('id')->isoFormat('D MMMM Y');
    }

    /**
     * Generate Article Report PDF.
     */
    public function generateArticleReport(Request $request)
    {
        [$startDate, $endDate] = $this->parseDateRange($request);
        
        $query = Article::with(['author', 'categoryRelation'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        // Filter by status if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category if provided
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by author if provided
        if ($request->filled('author_id')) {
            $query->where('author_id', $request->author_id);
        }

        $articles = $query->orderBy('created_at', 'desc')->get();

        $data = [
            'settings' => $this->getReportSettings(),
            'title' => 'Laporan Data Artikel',
            'period' => $this->formatDateRange($startDate, $endDate),
            'items' => $articles,
            'columns' => ['No', 'Judul', 'Kategori', 'Penulis', 'Status', 'Tanggal Publish'],
        ];

        $pdf = Pdf::loadView('reports.pdf.articles', $data);
        $pdf->setPaper('A4', 'portrait');
        
        $filename = 'laporan-artikel-' . $startDate->format('Ymd') . '-' . $endDate->format('Ymd') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Generate Category Report PDF.
     */
    public function generateCategoryReport(Request $request)
    {
        [$startDate, $endDate] = $this->parseDateRange($request);
        
        $query = Category::withCount('articles')
            ->whereBetween('created_at', [$startDate, $endDate]);

        // Filter by status if provided
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active === 'true');
        }

        $categories = $query->orderBy('sort_order', 'asc')->get();

        $data = [
            'settings' => $this->getReportSettings(),
            'title' => 'Laporan Data Kategori',
            'period' => $this->formatDateRange($startDate, $endDate),
            'items' => $categories,
            'columns' => ['No', 'Nama Kategori', 'Slug', 'Jumlah Artikel', 'Status', 'Dibuat'],
        ];

        $pdf = Pdf::loadView('reports.pdf.categories', $data);
        $pdf->setPaper('A4', 'portrait');
        
        $filename = 'laporan-kategori-' . $startDate->format('Ymd') . '-' . $endDate->format('Ymd') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Generate User Report PDF.
     */
    public function generateUserReport(Request $request)
    {
        [$startDate, $endDate] = $this->parseDateRange($request);
        
        $query = User::whereBetween('created_at', [$startDate, $endDate]);

        // Filter by role if provided
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('created_at', 'desc')->get();

        $data = [
            'settings' => $this->getReportSettings(),
            'title' => 'Laporan Data Pengguna',
            'period' => $this->formatDateRange($startDate, $endDate),
            'items' => $users,
            'columns' => ['No', 'Nama', 'Email', 'Role', 'Status', 'Login Terakhir'],
        ];

        $pdf = Pdf::loadView('reports.pdf.users', $data);
        $pdf->setPaper('A4', 'portrait');
        
        $filename = 'laporan-pengguna-' . $startDate->format('Ymd') . '-' . $endDate->format('Ymd') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Generate Activity Log Report PDF.
     */
    public function generateActivityLogReport(Request $request)
    {
        [$startDate, $endDate] = $this->parseDateRange($request);
        
        $query = ActivityLog::with('user')
            ->whereBetween('created_at', [$startDate, $endDate]);

        // Filter by action if provided
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by level if provided
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        // Filter by user if provided
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $activityLogs = $query->orderBy('created_at', 'desc')->get();

        $data = [
            'settings' => $this->getReportSettings(),
            'title' => 'Laporan Activity Log',
            'period' => $this->formatDateRange($startDate, $endDate),
            'items' => $activityLogs,
            'columns' => ['No', 'Tanggal', 'User', 'Action', 'Deskripsi', 'IP Address'],
        ];

        $pdf = Pdf::loadView('reports.pdf.activity-logs', $data);
        $pdf->setPaper('A4', 'portrait');
        
        $filename = 'laporan-activity-log-' . $startDate->format('Ymd') . '-' . $endDate->format('Ymd') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Generate Blocked Client Report PDF.
     */
    public function generateBlockedClientReport(Request $request)
    {
        [$startDate, $endDate] = $this->parseDateRange($request);
        
        $query = BlockedClient::whereBetween('created_at', [$startDate, $endDate]);

        // Filter by status if provided
        if ($request->filled('is_blocked')) {
            $query->where('is_blocked', $request->is_blocked === 'true');
        }

        $blockedClients = $query->orderBy('created_at', 'desc')->get();

        $data = [
            'settings' => $this->getReportSettings(),
            'title' => 'Laporan IP Terblokir',
            'period' => $this->formatDateRange($startDate, $endDate),
            'items' => $blockedClients,
            'columns' => ['No', 'IP Address', 'Alasan', 'Diblokir Sampai', 'Status', 'User Agent'],
        ];

        $pdf = Pdf::loadView('reports.pdf.blocked-clients', $data);
        $pdf->setPaper('A4', 'portrait');
        
        $filename = 'laporan-ip-terblokir-' . $startDate->format('Ymd') . '-' . $endDate->format('Ymd') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Generate Gallery Report PDF.
     */
    public function generateGalleryReport(Request $request)
    {
        [$startDate, $endDate] = $this->parseDateRange($request);
        
        $query = Gallery::with('uploader')
            ->whereBetween('created_at', [$startDate, $endDate]);

        // Filter by media type if provided
        if ($request->filled('media_type')) {
            $query->where('media_type', $request->media_type);
        }

        // Filter by album if provided
        if ($request->filled('album')) {
            $query->where('album', $request->album);
        }

        // Filter by published status if provided
        if ($request->filled('is_published')) {
            $query->where('is_published', $request->is_published === 'true');
        }

        $galleries = $query->orderBy('created_at', 'desc')->get();

        $data = [
            'settings' => $this->getReportSettings(),
            'title' => 'Laporan Data Gallery',
            'period' => $this->formatDateRange($startDate, $endDate),
            'items' => $galleries,
            'columns' => ['No', 'Judul', 'Album', 'Tipe Media', 'Uploader', 'Tanggal Upload'],
        ];

        $pdf = Pdf::loadView('reports.pdf.galleries', $data);
        $pdf->setPaper('A4', 'portrait');
        
        $filename = 'laporan-gallery-' . $startDate->format('Ymd') . '-' . $endDate->format('Ymd') . '.pdf';
        
        return $pdf->download($filename);
    }
}
