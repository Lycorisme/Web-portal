<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ArticleResource;
use App\Models\Category;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of active categories (public).
     */
    public function index(Request $request): JsonResponse
    {
        $query = Category::active()->ordered()->withCount('articles');

        // Include articles if requested
        if ($request->boolean('with_articles')) {
            $query->with(['articles' => function ($q) {
                $q->published()->latest('published_at')->limit(5);
            }]);
        }

        $categories = $query->get();

        return response()->json([
            'success' => true,
            'data' => CategoryResource::collection($categories),
        ]);
    }

    /**
     * Display the specified category with its articles (public).
     */
    public function show(Request $request, string $slug): JsonResponse
    {
        $category = Category::active()->where('slug', $slug)->firstOrFail();

        // Get articles for this category
        $articles = Article::with(['author', 'categoryRelation', 'tags'])
            ->published()
            ->where('category_id', $category->id)
            ->withCount(['likes', 'comments'])
            ->orderByDesc('published_at')
            ->paginate(min($request->get('per_page', 15), 50));

        return response()->json([
            'success' => true,
            'data' => [
                'category' => new CategoryResource($category),
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
     * Store a newly created category (authenticated - admin only).
     */
    public function store(Request $request): JsonResponse
    {
        if (!$request->user()->canManageCategories()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk membuat kategori',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:categories,name',
            'slug' => 'nullable|string|max:100|unique:categories,slug',
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:20',
            'icon' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
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
        
        if (empty($data['slug'])) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['name']);
        }

        $category = Category::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dibuat',
            'data' => new CategoryResource($category),
        ], 201);
    }

    /**
     * Update the specified category (authenticated - admin only).
     */
    public function update(Request $request, int $id): JsonResponse
    {
        if (!$request->user()->canManageCategories()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengedit kategori',
            ], 403);
        }

        $category = Category::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:100|unique:categories,name,' . $id,
            'slug' => 'nullable|string|max:100|unique:categories,slug,' . $id,
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:20',
            'icon' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $category->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil diperbarui',
            'data' => new CategoryResource($category),
        ]);
    }

    /**
     * Remove the specified category (authenticated - admin only).
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        if (!$request->user()->canManageCategories()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk menghapus kategori',
            ], 403);
        }

        $category = Category::findOrFail($id);

        // Check if category has articles
        if ($category->articles()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak dapat dihapus karena masih memiliki artikel',
            ], 422);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus',
        ]);
    }
}
