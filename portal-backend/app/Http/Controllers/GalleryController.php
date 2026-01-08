<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class GalleryController extends Controller
{
    /**
     * Display gallery management page.
     */
    public function index(Request $request)
    {
        // Get unique albums for filter dropdown
        $albums = Gallery::distinct()->whereNotNull('album')->pluck('album');
        
        return view('galleries.index', compact('albums'));
    }

    /**
     * Get filtered gallery data (AJAX).
     */
    public function getData(Request $request): JsonResponse
    {
        $query = Gallery::with('uploader');

        // Handle status filter (active vs trash)
        if ($request->status === 'trash') {
            $query->onlyTrashed();
        }

        // Apply sorting
        $sortField = $request->get('sort_field', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('album', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('media_type')) {
            $query->where('media_type', $request->media_type);
        }

        if ($request->filled('album')) {
            $query->where('album', $request->album);
        }

        if ($request->filled('is_published')) {
            $query->where('is_published', $request->is_published === 'true' || $request->is_published === '1');
        }

        if ($request->filled('is_featured')) {
            $query->where('is_featured', $request->is_featured === 'true' || $request->is_featured === '1');
        }

        // Paginate results
        $perPage = $request->get('per_page', 15);
        $galleries = $query->paginate($perPage);

        // Transform data for frontend
        $data = $galleries->getCollection()->map(function ($gallery) {
            return [
                'id' => $gallery->id,
                'title' => $gallery->title,
                'description' => $gallery->description,
                'image_path' => $gallery->image_path,
                'thumbnail_path' => $gallery->thumbnail_path,
                'image_url' => $gallery->image_url,
                'thumbnail_url' => $gallery->thumbnail_url,
                'media_type' => $gallery->media_type,
                'video_url' => $gallery->video_url,
                'album' => $gallery->album,
                'event_date' => $gallery->event_date?->format('d M Y'),
                'event_date_raw' => $gallery->event_date?->format('Y-m-d'),
                'location' => $gallery->location,
                'is_featured' => $gallery->is_featured,
                'is_published' => $gallery->is_published,
                'sort_order' => $gallery->sort_order,
                'uploader' => $gallery->uploader ? [
                    'id' => $gallery->uploader->id,
                    'name' => $gallery->uploader->name,
                ] : null,
                'created_at' => $gallery->created_at->format('d M Y H:i'),
                'created_at_human' => $gallery->created_at->diffForHumans(),
                'deleted_at' => $gallery->deleted_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'meta' => [
                'current_page' => $galleries->currentPage(),
                'last_page' => $galleries->lastPage(),
                'per_page' => $galleries->perPage(),
                'total' => $galleries->total(),
                'from' => $galleries->firstItem(),
                'to' => $galleries->lastItem(),
            ],
            'links' => [
                'first' => $galleries->url(1),
                'last' => $galleries->url($galleries->lastPage()),
                'prev' => $galleries->previousPageUrl(),
                'next' => $galleries->nextPageUrl(),
            ],
        ]);
    }

    /**
     * Get list of unique albums for autocomplete.
     */
    public function getAlbums(Request $request): JsonResponse
    {
        $search = $request->get('search', '');
        
        $query = Gallery::distinct()
            ->whereNotNull('album')
            ->where('album', '!=', '');
        
        if ($search) {
            $query->where('album', 'like', "%{$search}%");
        }
        
        $albums = $query->orderBy('album')->pluck('album');
        
        return response()->json([
            'success' => true,
            'data' => $albums,
        ]);
    }

    /**
     * Get grouped gallery data (albums grouped together).
     */
    public function getGroupedData(Request $request): JsonResponse
    {
        $status = $request->status;
        
        // Build base query
        $baseQuery = Gallery::with('uploader');
        
        if ($status === 'trash') {
            $baseQuery->onlyTrashed();
        }

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $baseQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('album', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('media_type')) {
            $baseQuery->where('media_type', $request->media_type);
        }

        if ($request->filled('album')) {
            $baseQuery->where('album', $request->album);
        }

        if ($request->filled('is_published')) {
            $baseQuery->where('is_published', $request->is_published === 'true' || $request->is_published === '1');
        }

        if ($request->filled('is_featured')) {
            $baseQuery->where('is_featured', $request->is_featured === 'true' || $request->is_featured === '1');
        }

        // Get all matching galleries
        $allGalleries = $baseQuery->orderBy('created_at', 'desc')->get();

        // Group by album + description + location (for images only)
        $grouped = [];
        $ungrouped = [];

        foreach ($allGalleries as $gallery) {
            // Videos and items without album are not grouped
            if ($gallery->media_type === 'video' || empty($gallery->album)) {
                $ungrouped[] = $gallery;
                continue;
            }

            // Create group key from album + description + location
            $groupKey = md5(($gallery->album ?? '') . '|' . ($gallery->description ?? '') . '|' . ($gallery->location ?? ''));
            
            if (!isset($grouped[$groupKey])) {
                $grouped[$groupKey] = [
                    'representative' => $gallery,
                    'items' => [],
                    'album' => $gallery->album,
                    'description' => $gallery->description,
                    'location' => $gallery->location,
                ];
            }
            
            $grouped[$groupKey]['items'][] = $gallery;
        }

        // Convert to array format
        $result = [];

        // Add grouped items
        foreach ($grouped as $groupKey => $group) {
            $representative = $group['representative'];
            $count = count($group['items']);
            
            // Get all item IDs in this group
            $itemIds = collect($group['items'])->pluck('id')->toArray();
            
            // Get thumbnails for group preview (up to 4)
            $previewThumbnails = collect($group['items'])
                ->take(4)
                ->map(fn($g) => $g->thumbnail_url ?? $g->image_url)
                ->toArray();

            $result[] = [
                'id' => $representative->id,
                'group_key' => $groupKey,
                'is_group' => $count > 1,
                'group_count' => $count,
                'group_item_ids' => $itemIds,
                'expanded_items' => collect($group['items'])->map(function($g) {
                    return [
                        'id' => $g->id,
                        'title' => $g->title,
                        'description' => $g->description,
                        'image_url' => $g->image_url,
                        'thumbnail_url' => $g->thumbnail_url,
                        'media_type' => $g->media_type,
                        'video_url' => $g->video_url,
                        'is_published' => $g->is_published,
                        'created_at' => $g->created_at->format('d M Y H:i'),
                        'uploader' => $g->uploader ? ['name' => $g->uploader->name] : null,
                        'album' => $g->album,
                        'location' => $g->location,
                        'event_date' => $g->event_date?->format('d M Y'),
                    ];
                })->toArray(),
                'preview_thumbnails' => $previewThumbnails,
                'title' => $this->getBaseTitle($representative->title),
                'description' => $representative->description,
                'image_path' => $representative->image_path,
                'thumbnail_path' => $representative->thumbnail_path,
                'image_url' => $representative->image_url,
                'thumbnail_url' => $representative->thumbnail_url,
                'media_type' => $representative->media_type,
                'video_url' => $representative->video_url,
                'album' => $representative->album,
                'event_date' => $representative->event_date?->format('d M Y'),
                'event_date_raw' => $representative->event_date?->format('Y-m-d'),
                'location' => $representative->location,
                'is_featured' => $representative->is_featured,
                'is_published' => $representative->is_published,
                'sort_order' => $representative->sort_order,
                'uploader' => $representative->uploader ? [
                    'id' => $representative->uploader->id,
                    'name' => $representative->uploader->name,
                ] : null,
                'created_at' => $representative->created_at->format('d M Y H:i'),
                'created_at_human' => $representative->created_at->diffForHumans(),
                'deleted_at' => $representative->deleted_at,
            ];
        }

        // Add ungrouped items
        foreach ($ungrouped as $gallery) {
            $result[] = [
                'id' => $gallery->id,
                'group_key' => null,
                'is_group' => false,
                'group_count' => 1,
                'group_item_ids' => [$gallery->id],
                'preview_thumbnails' => [$gallery->thumbnail_url ?? $gallery->image_url],
                'title' => $gallery->title,
                'description' => $gallery->description,
                'image_path' => $gallery->image_path,
                'thumbnail_path' => $gallery->thumbnail_path,
                'image_url' => $gallery->image_url,
                'thumbnail_url' => $gallery->thumbnail_url,
                'media_type' => $gallery->media_type,
                'video_url' => $gallery->video_url,
                'album' => $gallery->album,
                'event_date' => $gallery->event_date?->format('d M Y'),
                'event_date_raw' => $gallery->event_date?->format('Y-m-d'),
                'location' => $gallery->location,
                'is_featured' => $gallery->is_featured,
                'is_published' => $gallery->is_published,
                'sort_order' => $gallery->sort_order,
                'uploader' => $gallery->uploader ? [
                    'id' => $gallery->uploader->id,
                    'name' => $gallery->uploader->name,
                ] : null,
                'created_at' => $gallery->created_at->format('d M Y H:i'),
                'created_at_human' => $gallery->created_at->diffForHumans(),
                'deleted_at' => $gallery->deleted_at,
            ];
        }

        // Sort by created_at desc
        usort($result, fn($a, $b) => strtotime($b['created_at']) - strtotime($a['created_at']));

        // Manual pagination
        $perPage = $request->get('per_page', 15);
        $currentPage = $request->get('page', 1);
        $total = count($result);
        $lastPage = ceil($total / $perPage);
        
        $paginatedData = array_slice($result, ($currentPage - 1) * $perPage, $perPage);

        return response()->json([
            'success' => true,
            'data' => $paginatedData,
            'meta' => [
                'current_page' => (int) $currentPage,
                'last_page' => (int) $lastPage,
                'per_page' => (int) $perPage,
                'total' => $total,
                'from' => ($currentPage - 1) * $perPage + 1,
                'to' => min($currentPage * $perPage, $total),
            ],
        ]);
    }

    /**
     * Get all items in an album group.
     */
    public function getAlbumItems(Request $request): JsonResponse
    {
        $ids = $request->get('ids', []);
        
        if (empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No IDs provided',
            ], 400);
        }

        $galleries = Gallery::withTrashed()
            ->whereIn('id', $ids)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($gallery) {
                return [
                    'id' => $gallery->id,
                    'title' => $gallery->title,
                    'description' => $gallery->description,
                    'image_path' => $gallery->image_path,
                    'thumbnail_path' => $gallery->thumbnail_path,
                    'image_url' => $gallery->image_url,
                    'thumbnail_url' => $gallery->thumbnail_url,
                    'media_type' => $gallery->media_type,
                    'video_url' => $gallery->video_url,
                    'album' => $gallery->album,
                    'event_date' => $gallery->event_date?->format('d M Y'),
                    'event_date_raw' => $gallery->event_date?->format('Y-m-d'),
                    'location' => $gallery->location,
                    'is_featured' => $gallery->is_featured,
                    'is_published' => $gallery->is_published,
                    'created_at' => $gallery->created_at->format('d M Y H:i'),
                    'deleted_at' => $gallery->deleted_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $galleries,
        ]);
    }

    /**
     * Extract base title without numbering suffix like (1/5), (2/5), etc.
     */
    private function getBaseTitle(string $title): string
    {
        // Remove patterns like "(1/5)", " (2/10)", etc.
        return trim(preg_replace('/\s*\(\d+\/\d+\)\s*$/', '', $title));
    }

    /**
     * Store multiple gallery items (bulk upload).
     */
    public function bulkStore(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'images' => 'required|array|min:1|max:20',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'album' => 'nullable|string|max:255',
            'event_date' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ]);

        try {
            $createdCount = 0;
            $baseData = [
                'title' => $request->title,
                'description' => $request->description,
                'media_type' => 'image',
                'album' => $request->album,
                'event_date' => $request->event_date,
                'location' => $request->location,
                'is_featured' => $request->boolean('is_featured', false),
                'is_published' => $request->boolean('is_published', true),
                'uploaded_by' => Auth::id(),
            ];

            $images = $request->file('images');
            $totalImages = count($images);

            foreach ($images as $index => $image) {
                $data = $baseData;
                
                // Add numbering to title if multiple images
                if ($totalImages > 1) {
                    $data['title'] = $baseData['title'] . ' (' . ($index + 1) . '/' . $totalImages . ')';
                }

                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                
                // Store original image
                $path = $image->storeAs('galleries', $filename, 'public');
                $data['image_path'] = $path;

                // Create thumbnail
                $thumbnailFilename = 'thumb_' . $filename;
                $thumbnailPath = 'galleries/thumbnails/' . $thumbnailFilename;
                
                Storage::disk('public')->makeDirectory('galleries/thumbnails');
                
                $originalPath = Storage::disk('public')->path($path);
                $thumbFullPath = Storage::disk('public')->path($thumbnailPath);
                
                $this->createThumbnail($originalPath, $thumbFullPath, 400, 300);
                
                $data['thumbnail_path'] = $thumbnailPath;

                $gallery = Gallery::create($data);
                $createdCount++;

                ActivityLog::log(
                    ActivityLog::ACTION_CREATE,
                    "Menambahkan item galeri (bulk): {$gallery->title}",
                    $gallery,
                    null,
                    $gallery->toArray(),
                    ActivityLog::LEVEL_INFO
                );
            }

            return response()->json([
                'success' => true,
                'message' => "{$createdCount} item galeri berhasil ditambahkan.",
                'count' => $createdCount,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan item galeri: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created gallery item.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required_if:media_type,image|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'media_type' => 'required|in:image,video',
            'video_url' => 'required_if:media_type,video|nullable|url',
            'album' => 'nullable|string|max:255',
            'event_date' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ]);

        try {
            $data = $request->only([
                'title', 'description', 'media_type', 'video_url',
                'album', 'event_date', 'location'
            ]);
            
            $data['is_featured'] = $request->boolean('is_featured', false);
            $data['is_published'] = $request->boolean('is_published', true);
            $data['uploaded_by'] = Auth::id();

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                
                // Store original image
                $path = $image->storeAs('galleries', $filename, 'public');
                $data['image_path'] = $path;

                // Create thumbnail
                $thumbnailFilename = 'thumb_' . $filename;
                $thumbnailPath = 'galleries/thumbnails/' . $thumbnailFilename;
                
                // Ensure thumbnail directory exists
                Storage::disk('public')->makeDirectory('galleries/thumbnails');
                
                // Create and save thumbnail using simple resize
                $originalPath = Storage::disk('public')->path($path);
                $thumbFullPath = Storage::disk('public')->path($thumbnailPath);
                
                // Simple thumbnail creation without Intervention Image
                $this->createThumbnail($originalPath, $thumbFullPath, 400, 300);
                
                $data['thumbnail_path'] = $thumbnailPath;
            }

            // Handle video thumbnail from YouTube/Vimeo
            if ($request->media_type === 'video' && $request->video_url) {
                $thumbnail = $this->getVideoThumbnail($request->video_url);
                if ($thumbnail) {
                    $data['thumbnail_path'] = $thumbnail;
                }
            }

            $gallery = Gallery::create($data);

            ActivityLog::log(
                ActivityLog::ACTION_CREATE,
                "Menambahkan item galeri baru: {$gallery->title}",
                $gallery,
                null,
                $gallery->toArray(),
                ActivityLog::LEVEL_INFO
            );

            return response()->json([
                'success' => true,
                'message' => 'Item galeri berhasil ditambahkan.',
                'data' => $gallery,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan item galeri: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get single gallery detail.
     */
    public function show(Gallery $gallery): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $gallery->id,
                'title' => $gallery->title,
                'description' => $gallery->description,
                'image_path' => $gallery->image_path,
                'image_url' => $gallery->image_url,
                'thumbnail_url' => $gallery->thumbnail_url,
                'media_type' => $gallery->media_type,
                'video_url' => $gallery->video_url,
                'album' => $gallery->album,
                'event_date' => $gallery->event_date?->format('d M Y'),
                'event_date_raw' => $gallery->event_date?->format('Y-m-d'),
                'location' => $gallery->location,
                'is_featured' => $gallery->is_featured,
                'is_published' => $gallery->is_published,
                'sort_order' => $gallery->sort_order,
                'uploader' => $gallery->uploader ? $gallery->uploader->name : null,
                'created_at' => $gallery->created_at->format('d M Y H:i:s'),
                'updated_at' => $gallery->updated_at->format('d M Y H:i:s'),
            ],
        ]);
    }

    /**
     * Update the specified gallery item.
     */
    public function update(Request $request, Gallery $gallery): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'media_type' => 'required|in:image,video',
            'video_url' => 'required_if:media_type,video|nullable|url',
            'album' => 'nullable|string|max:255',
            'event_date' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ]);

        try {
            $data = $request->only([
                'title', 'description', 'media_type', 'video_url',
                'album', 'event_date', 'location'
            ]);
            
            $data['is_featured'] = $request->boolean('is_featured', false);
            $data['is_published'] = $request->boolean('is_published', true);

            // Handle new image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($gallery->image_path) {
                    Storage::disk('public')->delete($gallery->image_path);
                }
                if ($gallery->thumbnail_path) {
                    Storage::disk('public')->delete($gallery->thumbnail_path);
                }

                $image = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                
                // Store original image
                $path = $image->storeAs('galleries', $filename, 'public');
                $data['image_path'] = $path;

                // Create thumbnail
                $thumbnailFilename = 'thumb_' . $filename;
                $thumbnailPath = 'galleries/thumbnails/' . $thumbnailFilename;
                
                Storage::disk('public')->makeDirectory('galleries/thumbnails');
                
                $originalPath = Storage::disk('public')->path($path);
                $thumbFullPath = Storage::disk('public')->path($thumbnailPath);
                
                $this->createThumbnail($originalPath, $thumbFullPath, 400, 300);
                
                $data['thumbnail_path'] = $thumbnailPath;
            }

            $oldValues = $gallery->getOriginal();
            $gallery->update($data);
            $newValues = $gallery->getChanges();

            ActivityLog::log(
                ActivityLog::ACTION_UPDATE,
                "Mengubah item galeri: {$gallery->title}",
                $gallery,
                $oldValues,
                $newValues,
                ActivityLog::LEVEL_INFO
            );

            return response()->json([
                'success' => true,
                'message' => 'Item galeri berhasil diperbarui.',
                'data' => $gallery->fresh(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui item galeri: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a gallery item (soft delete).
     */
    public function destroy(Gallery $gallery): JsonResponse
    {
        try {
            $gallery->delete();

            ActivityLog::log(
                ActivityLog::ACTION_DELETE,
                "Menghapus item galeri (tong sampah): {$gallery->title}",
                $gallery,
                null,
                null,
                ActivityLog::LEVEL_WARNING
            );

            return response()->json([
                'success' => true,
                'message' => 'Item galeri berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus item galeri: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk delete gallery items.
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:galleries,id',
        ]);

        try {
            $galleries = Gallery::whereIn('id', $request->ids)->get();
            $count = 0;

            foreach ($galleries as $gallery) {
                $gallery->delete();
                $count++;
                
                ActivityLog::log(
                    ActivityLog::ACTION_DELETE,
                    "Menghapus item galeri (massal): {$gallery->title}",
                    $gallery,
                    null,
                    null,
                    ActivityLog::LEVEL_WARNING
                );
            }

            return response()->json([
                'success' => true,
                'message' => "{$count} item galeri berhasil dihapus.",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus item galeri: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Restore a soft-deleted gallery item.
     */
    public function restore($id): JsonResponse
    {
        try {
            $gallery = Gallery::onlyTrashed()->findOrFail($id);
            $gallery->restore();

            ActivityLog::log(
                ActivityLog::ACTION_RESTORE,
                "Memulihkan item galeri: {$gallery->title}",
                $gallery,
                null,
                null,
                ActivityLog::LEVEL_INFO
            );

            return response()->json([
                'success' => true,
                'message' => 'Item galeri berhasil dipulihkan.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memulihkan item galeri: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Force delete a gallery item.
     */
    public function forceDelete($id): JsonResponse
    {
        try {
            $gallery = Gallery::withTrashed()->findOrFail($id);
            $oldData = $gallery->toArray();

            // Delete files
            if ($gallery->image_path) {
                Storage::disk('public')->delete($gallery->image_path);
            }
            if ($gallery->thumbnail_path) {
                Storage::disk('public')->delete($gallery->thumbnail_path);
            }

            $gallery->forceDelete();

            ActivityLog::log(
                ActivityLog::ACTION_FORCE_DELETE,
                "Menghapus permanen item galeri: {$gallery->title}",
                $gallery,
                $oldData,
                null,
                ActivityLog::LEVEL_DANGER
            );

            return response()->json([
                'success' => true,
                'message' => 'Item galeri berhasil dihapus permanen.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus permanen item galeri: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk restore gallery items.
     */
    public function bulkRestore(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:galleries,id',
        ]);

        try {
            $galleries = Gallery::onlyTrashed()->whereIn('id', $request->ids)->get();
            
            foreach ($galleries as $gallery) {
                $gallery->restore();

                ActivityLog::log(
                    ActivityLog::ACTION_RESTORE,
                    "Memulihkan item galeri (massal): {$gallery->title}",
                    $gallery,
                    null,
                    null,
                    ActivityLog::LEVEL_INFO
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Item galeri terpilih berhasil dipulihkan.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memulihkan item galeri: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk force delete gallery items.
     */
    public function bulkForceDelete(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:galleries,id',
        ]);

        try {
            $galleries = Gallery::withTrashed()->whereIn('id', $request->ids)->get();
            
            foreach ($galleries as $gallery) {
                $oldData = $gallery->toArray();
                
                // Delete files
                if ($gallery->image_path) {
                    Storage::disk('public')->delete($gallery->image_path);
                }
                if ($gallery->thumbnail_path) {
                    Storage::disk('public')->delete($gallery->thumbnail_path);
                }
                
                $gallery->forceDelete();

                ActivityLog::log(
                    ActivityLog::ACTION_FORCE_DELETE,
                    "Menghapus permanen item galeri (massal): {$gallery->title}",
                    $gallery,
                    $oldData,
                    null,
                    ActivityLog::LEVEL_DANGER
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Item galeri terpilih berhasil dihapus permanen.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus permanen item galeri: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle published status.
     */
    public function togglePublished(Gallery $gallery): JsonResponse
    {
        try {
            $oldPublished = $gallery->is_published;
            $gallery->update(['is_published' => !$gallery->is_published]);

            ActivityLog::log(
                ActivityLog::ACTION_UPDATE,
                "Mengubah status publish galeri {$gallery->title} menjadi " . ($gallery->is_published ? 'Published' : 'Draft'),
                $gallery,
                ['is_published' => $oldPublished],
                ['is_published' => $gallery->is_published],
                ActivityLog::LEVEL_INFO
            );

            return response()->json([
                'success' => true,
                'message' => $gallery->is_published ? 'Item dipublikasikan.' : 'Item disembunyikan.',
                'is_published' => $gallery->is_published,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured(Gallery $gallery): JsonResponse
    {
        try {
            $oldFeatured = $gallery->is_featured;
            $gallery->update(['is_featured' => !$gallery->is_featured]);

            ActivityLog::log(
                ActivityLog::ACTION_UPDATE,
                "Mengubah status featured galeri {$gallery->title} menjadi " . ($gallery->is_featured ? 'Featured' : 'Normal'),
                $gallery,
                ['is_featured' => $oldFeatured],
                ['is_featured' => $gallery->is_featured],
                ActivityLog::LEVEL_INFO
            );

            return response()->json([
                'success' => true,
                'message' => $gallery->is_featured ? 'Item ditandai sebagai featured.' : 'Status featured dihapus.',
                'is_featured' => $gallery->is_featured,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create thumbnail from image.
     */
    private function createThumbnail($sourcePath, $destPath, $width, $height)
    {
        $info = getimagesize($sourcePath);
        $mime = $info['mime'];
        
        switch ($mime) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($sourcePath);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($sourcePath);
                break;
            case 'image/webp':
                $image = imagecreatefromwebp($sourcePath);
                break;
            default:
                return false;
        }
        
        $origWidth = imagesx($image);
        $origHeight = imagesy($image);
        
        // Calculate new dimensions maintaining aspect ratio
        $ratio = min($width / $origWidth, $height / $origHeight);
        $newWidth = round($origWidth * $ratio);
        $newHeight = round($origHeight * $ratio);
        
        $thumb = imagecreatetruecolor($newWidth, $newHeight);
        
        // Preserve transparency for PNG and GIF
        if ($mime == 'image/png' || $mime == 'image/gif') {
            imagealphablending($thumb, false);
            imagesavealpha($thumb, true);
            $transparent = imagecolorallocatealpha($thumb, 255, 255, 255, 127);
            imagefilledrectangle($thumb, 0, 0, $newWidth, $newHeight, $transparent);
        }
        
        imagecopyresampled($thumb, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
        
        switch ($mime) {
            case 'image/jpeg':
                imagejpeg($thumb, $destPath, 85);
                break;
            case 'image/png':
                imagepng($thumb, $destPath, 8);
                break;
            case 'image/gif':
                imagegif($thumb, $destPath);
                break;
            case 'image/webp':
                imagewebp($thumb, $destPath, 85);
                break;
        }
        
        imagedestroy($image);
        imagedestroy($thumb);
        
        return true;
    }

    /**
     * Get video thumbnail from URL.
     */
    private function getVideoThumbnail($url)
    {
        // Extract YouTube video ID
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return 'https://img.youtube.com/vi/' . $matches[1] . '/maxresdefault.jpg';
        }
        
        // Extract Vimeo video ID
        if (preg_match('/vimeo\.com\/(\d+)/', $url, $matches)) {
            return null; // Vimeo requires API call
        }
        
        return null;
    }
}
