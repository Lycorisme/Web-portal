<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\GalleryResource;
use App\Models\Gallery;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    /**
     * Display a listing of published gallery items (public).
     */
    public function index(Request $request): JsonResponse
    {
        $query = Gallery::with('uploader')->published();

        // Filter by media type
        if ($request->filled('type')) {
            if ($request->type === 'image') {
                $query->images();
            } elseif ($request->type === 'video') {
                $query->videos();
            }
        }

        // Filter by album
        if ($request->filled('album')) {
            $query->inAlbum($request->album);
        }

        // Filter featured only
        if ($request->boolean('featured')) {
            $query->featured();
        }

        // Sorting
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'oldest':
                $query->orderBy('published_at', 'asc');
                break;
            case 'event_date':
                $query->orderByDesc('event_date');
                break;
            default:
                $query->orderByDesc('published_at');
        }

        // Pagination
        $perPage = min($request->get('per_page', 20), 50);
        $gallery = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => GalleryResource::collection($gallery),
            'meta' => [
                'current_page' => $gallery->currentPage(),
                'last_page' => $gallery->lastPage(),
                'per_page' => $gallery->perPage(),
                'total' => $gallery->total(),
            ],
        ]);
    }

    /**
     * Get list of albums (public).
     */
    public function albums(): JsonResponse
    {
        $albums = Gallery::published()
            ->whereNotNull('album')
            ->select('album')
            ->distinct()
            ->orderBy('album')
            ->pluck('album');

        return response()->json([
            'success' => true,
            'data' => $albums,
        ]);
    }

    /**
     * Display the specified gallery item (public).
     */
    public function show(int $id): JsonResponse
    {
        $item = Gallery::with('uploader')->published()->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => new GalleryResource($item),
        ]);
    }

    /**
     * Store a newly created gallery item (authenticated).
     */
    public function store(Request $request): JsonResponse
    {
        if (!$request->user()->canManageContent()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengunggah ke galeri',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image' => 'required_without:video_url|file|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'video_url' => 'required_without:image|nullable|url',
            'album' => 'nullable|string|max:100',
            'event_date' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $data['uploaded_by'] = $request->user()->id;

        // Handle file upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('gallery', 'public');
            $data['image_path'] = $path;
            $data['media_type'] = 'image';

            // Generate thumbnail (simplified - in production use intervention/image)
            $data['thumbnail_path'] = $path;
        } elseif ($request->filled('video_url')) {
            $data['media_type'] = 'video';
        }

        if ($data['is_published'] ?? false) {
            $data['published_at'] = now();
        }

        unset($data['image']);
        $gallery = Gallery::create($data);

        // Log activity
        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'create_gallery',
            'description' => "Mengunggah item galeri via API: {$gallery->title}",
            'subject_type' => Gallery::class,
            'subject_id' => $gallery->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Item galeri berhasil diunggah',
            'data' => new GalleryResource($gallery),
        ], 201);
    }

    /**
     * Update the specified gallery item (authenticated).
     */
    public function update(Request $request, int $id): JsonResponse
    {
        if (!$request->user()->canManageContent()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengedit galeri',
            ], 403);
        }

        $gallery = Gallery::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'video_url' => 'nullable|url',
            'album' => 'nullable|string|max:100',
            'event_date' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        // Handle file upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($gallery->image_path) {
                Storage::disk('public')->delete($gallery->image_path);
            }

            $file = $request->file('image');
            $path = $file->store('gallery', 'public');
            $data['image_path'] = $path;
            $data['thumbnail_path'] = $path;
            $data['media_type'] = 'image';
        }

        // Handle publish
        if (isset($data['is_published']) && $data['is_published'] && !$gallery->published_at) {
            $data['published_at'] = now();
        }

        unset($data['image']);
        $gallery->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Item galeri berhasil diperbarui',
            'data' => new GalleryResource($gallery),
        ]);
    }

    /**
     * Remove the specified gallery item (authenticated).
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        if (!$request->user()->canManageContent()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk menghapus galeri',
            ], 403);
        }

        $gallery = Gallery::findOrFail($id);
        $title = $gallery->title;

        // Delete files
        if ($gallery->image_path) {
            Storage::disk('public')->delete($gallery->image_path);
        }
        if ($gallery->thumbnail_path && $gallery->thumbnail_path !== $gallery->image_path) {
            Storage::disk('public')->delete($gallery->thumbnail_path);
        }

        $gallery->delete();

        // Log activity
        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'delete_gallery',
            'description' => "Menghapus item galeri via API: {$title}",
            'subject_type' => Gallery::class,
            'subject_id' => $id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Item galeri berhasil dihapus',
        ]);
    }
}
