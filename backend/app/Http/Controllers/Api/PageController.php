<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PageResource;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PageController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(string $slug): JsonResponse
    {
        try {
            $page = Page::with(['sections' => function ($query) {
                $query->orderBy('sort_order', 'asc');
            }, 'sections.assets' => function ($query) {
                $query->orderBy('section_assets.sort_order', 'asc');
            }])->where('slug', $slug)->firstOrFail();

            return response()->json(new PageResource($page));
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Page not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Server Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * List all pages (Optional, but good for navigation)
     */
    public function index(): JsonResponse
    {
        try {
            $pages = Page::all();
            return response()->json(PageResource::collection($pages));
        } catch (\Exception $e) {
            return response()->json(['message' => 'Server Error'], 500);
        }
    }
}
