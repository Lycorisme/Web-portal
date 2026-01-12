<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use App\Http\Resources\ArticleResource;
use App\Models\Tag;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    /**
     * Display a listing of active tags (public).
     */
    public function index(Request $request): JsonResponse
    {
        $query = Tag::where('is_active', true)->withCount('articles');

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        // Sort by popularity
        if ($request->get('sort') === 'popular') {
            $query->orderByDesc('articles_count');
        } else {
            $query->orderBy('name');
        }

        // Limit for dropdown/autocomplete
        if ($request->filled('limit')) {
            $tags = $query->limit(min($request->limit, 100))->get();
        } else {
            $tags = $query->paginate(min($request->get('per_page', 50), 100));
            
            return response()->json([
                'success' => true,
                'data' => TagResource::collection($tags),
                'meta' => [
                    'current_page' => $tags->currentPage(),
                    'last_page' => $tags->lastPage(),
                    'per_page' => $tags->perPage(),
                    'total' => $tags->total(),
                ],
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => TagResource::collection($tags),
        ]);
    }

    /**
     * Display the specified tag with its articles (public).
     */
    public function show(Request $request, string $slug): JsonResponse
    {
        $tag = Tag::where('slug', $slug)->where('is_active', true)->firstOrFail();

        // Get articles for this tag
        $articles = Article::with(['author', 'categoryRelation', 'tags'])
            ->published()
            ->whereHas('tags', function ($q) use ($tag) {
                $q->where('tags.id', $tag->id);
            })
            ->withCount(['likes', 'comments'])
            ->orderByDesc('published_at')
            ->paginate(min($request->get('per_page', 15), 50));

        return response()->json([
            'success' => true,
            'data' => [
                'tag' => new TagResource($tag),
                'articles' => ArticleResource::collection($articles),
            ],
            'meta' => [
                'current_page' => $articles->currentPage(),
                'last_page' => $articles->lastPage(),
                'per_page' => $articles->perPage(),
                'total' => $articles->total(),
            ],
        ]);
    }

    /**
     * Store a newly created tag (authenticated - admin only).
     */
    public function store(Request $request): JsonResponse
    {
        if (!$request->user()->canManageTags()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk membuat tag',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50|unique:tags,name',
            'slug' => 'nullable|string|max:50|unique:tags,slug',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $tag = Tag::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Tag berhasil dibuat',
            'data' => new TagResource($tag),
        ], 201);
    }

    /**
     * Update the specified tag (authenticated - admin only).
     */
    public function update(Request $request, int $id): JsonResponse
    {
        if (!$request->user()->canManageTags()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengedit tag',
            ], 403);
        }

        $tag = Tag::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:50|unique:tags,name,' . $id,
            'slug' => 'nullable|string|max:50|unique:tags,slug,' . $id,
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $tag->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Tag berhasil diperbarui',
            'data' => new TagResource($tag),
        ]);
    }

    /**
     * Remove the specified tag (authenticated - admin only).
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        if (!$request->user()->canManageTags()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk menghapus tag',
            ], 403);
        }

        $tag = Tag::findOrFail($id);
        $tag->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tag berhasil dihapus',
        ]);
    }
}
