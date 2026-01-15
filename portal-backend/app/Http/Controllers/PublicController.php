<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Gallery;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Landing page - Homepage publik
     */
    public function index()
    {
        // Featured article (most recent published with high views)
        $featuredArticle = Article::published()
            ->with(['author', 'categoryRelation'])
            ->orderByDesc('views')
            ->first();

        // Latest articles (excluding featured)
        $latestArticles = Article::published()
            ->with(['author', 'categoryRelation'])
            ->when($featuredArticle, fn($q) => $q->where('id', '!=', $featuredArticle->id))
            ->latest('published_at')
            ->take(6)
            ->get();

        // Popular articles (by views)
        $popularArticles = Article::published()
            ->with(['author', 'categoryRelation'])
            ->orderByDesc('views')
            ->take(5)
            ->get();

        // Categories with article count
        $categories = Category::where('is_active', true)
            ->withCount(['articles' => fn($q) => $q->published()])
            ->orderBy('sort_order')
            ->get();

        // Featured galleries
        $galleries = Gallery::published()
            ->featured()
            ->latest('published_at')
            ->take(6)
            ->get();

        // Site settings
        $siteSettings = [
            'site_name' => SiteSetting::get('site_name', 'BTIKP Portal'),
            'site_tagline' => SiteSetting::get('site_tagline', 'Portal Berita & Informasi'),
            'logo_url' => SiteSetting::get('logo_url', ''),
        ];

        // Popular Tags
        $popularTags = \App\Models\Tag::where('is_active', true)
            ->withCount('articles')
            ->orderByDesc('articles_count')
            ->take(8)
            ->get();

        return view('public.index', compact(
            'featuredArticle',
            'latestArticles',
            'popularArticles',
            'categories',
            'galleries',
            'siteSettings',
            'popularTags'
        ));
    }

    /**
     * List all published articles with pagination
     */
    public function listArticles(Request $request)
    {
        $query = Article::published()
            ->with(['author', 'categoryRelation', 'tags']);

        // Filter by category
        if ($request->filled('kategori')) {
            $query->whereHas('categoryRelation', fn($q) => 
                $q->where('slug', $request->kategori)
            );
        }

        // Filter by tag
        if ($request->filled('tag')) {
            $query->whereHas('tags', fn($q) => 
                $q->where('slug', $request->tag)
            );
        }

        // Search
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $articles = $query->latest('published_at')->paginate(12);

        $categories = Category::where('is_active', true)
            ->withCount(['articles' => fn($q) => $q->published()])
            ->orderBy('sort_order')
            ->get();

        $siteSettings = [
            'site_name' => SiteSetting::get('site_name', 'BTIKP Portal'),
        ];

        return view('public.articles', compact('articles', 'categories', 'siteSettings'));
    }

    /**
     * Show single article detail
     */
    public function showArticle($slug)
    {
        $article = Article::where('slug', $slug)
            ->published()
            ->with(['author', 'categoryRelation', 'tags', 'visibleComments.user', 'visibleComments.visibleReplies.user'])
            ->firstOrFail();

        // Increment views
        $article->increment('views');

        // Log view activity for statistics
        try {
            \App\Models\ActivityLog::log(
                \App\Models\ActivityLog::ACTION_VIEW,
                'Melihat artikel: ' . $article->title,
                $article
            );
        } catch (\Exception $e) {
            // Ignore logging errors to prevent blocking the user
            // This can happen if no user exists in DB yet (User::first() fails)
        }

        // Related articles (same category)
        $relatedArticles = Article::published()
            ->where('id', '!=', $article->id)
            ->where('category_id', $article->category_id)
            ->with(['author'])
            ->latest('published_at')
            ->take(4)
            ->get();

        // Check if current user has liked
        $hasLiked = false;
        if (auth()->check()) {
            $hasLiked = $article->likes()->where('user_id', auth()->id())->exists();
        }

        $siteSettings = [
            'site_name' => SiteSetting::get('site_name', 'BTIKP Portal'),
        ];

        return view('public.article-detail', compact(
            'article',
            'relatedArticles',
            'hasLiked',
            'siteSettings'
        ));
    }

    /**
     * Show gallery page
     */
    public function showGallery(Request $request)
    {
        $query = Gallery::published();

        // Filter by album
        if ($request->filled('album')) {
            $query->where('album', $request->album);
        }

        // Filter by media type
        if ($request->filled('type')) {
            $query->where('media_type', $request->type);
        }

        $galleries = $query->latest('published_at')->paginate(12);

        // Get unique albums
        $albums = Gallery::published()
            ->select('album')
            ->distinct()
            ->whereNotNull('album')
            ->pluck('album');

        $siteSettings = [
            'site_name' => SiteSetting::get('site_name', 'BTIKP Portal'),
        ];

        return view('public.gallery', compact('galleries', 'albums', 'siteSettings'));
    }
}
