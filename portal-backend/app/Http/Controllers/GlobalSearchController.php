<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Gallery;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GlobalSearchController extends Controller
{
    /**
     * Perform a global search across multiple entities.
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $limit = 5;

        if (strlen($query) < 2) {
            return response()->json([
                'results' => [],
                'total' => 0,
            ]);
        }

        $user = Auth::user();
        $results = [];

        // Search Articles
        $articles = Article::where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('excerpt', 'like', "%{$query}%");
            })
            ->when($user->isAuthor(), function ($q) use ($user) {
                // Authors can only see their own articles
                $q->where('author_id', $user->id);
            })
            ->with('author:id,name')
            ->orderBy('updated_at', 'desc')
            ->limit($limit)
            ->get(['id', 'title', 'slug', 'status', 'thumbnail', 'author_id', 'updated_at']);

        if ($articles->count() > 0) {
            $results[] = [
                'type' => 'articles',
                'label' => 'Artikel',
                'icon' => 'file-text',
                'items' => $articles->map(function ($article) {
                    return [
                        'id' => $article->id,
                        'title' => $article->title,
                        'subtitle' => $article->author?->name ?? 'Unknown',
                        'url' => route('articles') . '?edit=' . $article->id,
                        'image' => $article->image_url,
                        'badge' => $this->getStatusBadge($article->status),
                    ];
                }),
            ];
        }

        // Search Galleries
        $galleries = Gallery::where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('album', 'like', "%{$query}%");
            })
            ->orderBy('updated_at', 'desc')
            ->limit($limit)
            ->get(['id', 'title', 'album', 'image_path', 'is_published', 'updated_at']);

        if ($galleries->count() > 0) {
            $results[] = [
                'type' => 'galleries',
                'label' => 'Galeri',
                'icon' => 'image',
                'items' => $galleries->map(function ($gallery) {
                    return [
                        'id' => $gallery->id,
                        'title' => $gallery->title,
                        'subtitle' => $gallery->album ?? 'Tanpa Album',
                        'url' => route('galleries') . '?edit=' . $gallery->id,
                        'image' => $gallery->image_url,
                        'badge' => $gallery->is_published
                            ? ['label' => 'Published', 'color' => 'emerald']
                            : ['label' => 'Draft', 'color' => 'slate'],
                    ];
                }),
            ];
        }

        // Search Categories (only for editors and above)
        if ($user->canManageCategories()) {
            $categories = Category::where('name', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->orderBy('sort_order', 'asc')
                ->limit($limit)
                ->get(['id', 'name', 'slug', 'color', 'icon', 'is_active']);

            if ($categories->count() > 0) {
                $results[] = [
                    'type' => 'categories',
                    'label' => 'Kategori',
                    'icon' => 'folder',
                    'items' => $categories->map(function ($category) {
                        return [
                            'id' => $category->id,
                            'title' => $category->name,
                            'subtitle' => $category->slug,
                            'url' => route('categories') . '?edit=' . $category->id,
                            'color' => $category->color,
                            'badge' => $category->is_active
                                ? ['label' => 'Aktif', 'color' => 'emerald']
                                : ['label' => 'Nonaktif', 'color' => 'slate'],
                        ];
                    }),
                ];
            }
        }

        // Search Tags (only for editors and above)
        if ($user->canManageTags()) {
            $tags = Tag::where('name', 'like', "%{$query}%")
                ->orderBy('name', 'asc')
                ->limit($limit)
                ->get(['id', 'name', 'slug', 'is_active']);

            if ($tags->count() > 0) {
                $results[] = [
                    'type' => 'tags',
                    'label' => 'Tag',
                    'icon' => 'tag',
                    'items' => $tags->map(function ($tag) {
                        return [
                            'id' => $tag->id,
                            'title' => $tag->name,
                            'subtitle' => $tag->slug,
                            'url' => route('tags') . '?edit=' . $tag->id,
                            'badge' => $tag->is_active
                                ? ['label' => 'Aktif', 'color' => 'emerald']
                                : ['label' => 'Nonaktif', 'color' => 'slate'],
                        ];
                    }),
                ];
            }
        }

        // Search Users (only for admins)
        if ($user->canManageUsers()) {
            $users = User::where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('email', 'like', "%{$query}%")
                      ->orWhere('position', 'like', "%{$query}%");
                })
                ->orderBy('name', 'asc')
                ->limit($limit)
                ->get(['id', 'name', 'email', 'role', 'profile_photo']);

            if ($users->count() > 0) {
                $results[] = [
                    'type' => 'users',
                    'label' => 'Pengguna',
                    'icon' => 'users',
                    'items' => $users->map(function ($u) {
                        return [
                            'id' => $u->id,
                            'title' => $u->name,
                            'subtitle' => $u->email,
                            'url' => route('users') . '?edit=' . $u->id,
                            'image' => $u->avatar,
                            'badge' => ['label' => ucfirst(str_replace('_', ' ', $u->role)), 'color' => $this->getRoleColor($u->role)],
                        ];
                    }),
                ];
            }
        }

        // Calculate total results
        $total = collect($results)->sum(fn($group) => count($group['items']));

        return response()->json([
            'results' => $results,
            'total' => $total,
            'query' => $query,
        ]);
    }

    /**
     * Get status badge configuration.
     */
    private function getStatusBadge(string $status): array
    {
        return match ($status) {
            'published' => ['label' => 'Published', 'color' => 'emerald'],
            'draft' => ['label' => 'Draft', 'color' => 'slate'],
            'pending' => ['label' => 'Pending', 'color' => 'amber'],
            'rejected' => ['label' => 'Rejected', 'color' => 'rose'],
            default => ['label' => ucfirst($status), 'color' => 'slate'],
        };
    }

    /**
     * Get role color.
     */
    private function getRoleColor(string $role): string
    {
        return match ($role) {
            'super_admin' => 'rose',
            'admin' => 'violet',
            'editor' => 'blue',
            'author' => 'emerald',
            'member' => 'slate',
            default => 'slate',
        };
    }
}
