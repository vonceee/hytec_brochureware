<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AssetResource;
use App\Models\Asset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $assets = Asset::orderBy('created_at', 'desc')->get();
            return response()->json(AssetResource::collection($assets));
        } catch (\Exception $e) {
            return response()->json(['message' => 'Server Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:jpeg,png,jpg,gif,svg,mp4,pdf,doc,docx|max:20480', // 20MB max
                'asset_category_id' => 'nullable|exists:asset_categories,id',
                'title' => 'nullable|string|max:255',
                'alt_text' => 'nullable|string|max:255',
            ]);

            if (!$request->hasFile('file')) {
                return response()->json(['message' => 'No file uploaded'], 400);
            }

            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $mimeType = $file->getMimeType();
            $sizeKb = round($file->getSize() / 1024, 2);

            // Store file
            $path = $file->store('assets', 'public');

            // Get dimensions if image
            $width = null;
            $height = null;
            if (str_starts_with($mimeType, 'image/')) {
                $dimensions = getimagesize($file->getRealPath());
                if ($dimensions) {
                    $width = $dimensions[0];
                    $height = $dimensions[1];
                }
            }

            $asset = Asset::create([
                'asset_category_id' => $request->asset_category_id,
                'title' => $request->title ?? pathinfo($originalName, PATHINFO_FILENAME),
                'internal_name' => $originalName,
                'file_path' => $path,
                'file_name' => basename($path),
                'file_type' => $mimeType,
                'alt_text' => $request->alt_text,
                'width' => $width,
                'height' => $height,
                'size_kb' => $sizeKb,
                'is_active' => true,
                // 'uploaded_by' => auth()->id(), // Assuming auth is handled or nullable for now
            ]);

            return response()->json(new AssetResource($asset), 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Server Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $asset = Asset::findOrFail($id);

            // Delete file from storage
            if (Storage::disk('public')->exists($asset->file_path)) {
                Storage::disk('public')->delete($asset->file_path);
            }

            $asset->delete();

            return response()->json(['message' => 'Asset deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting asset: ' . $e->getMessage()], 500);
        }
    }
}
