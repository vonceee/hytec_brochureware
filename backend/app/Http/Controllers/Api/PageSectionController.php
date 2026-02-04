<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PageSection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PageSectionController extends Controller
{
    /**
     * Update the assets for a specific section.
     * 
     * Expects payload:
     * {
     *   "assets": [
     *     { "id": 1, "sort_order": 0 },
     *     { "id": 5, "sort_order": 1 }
     *   ]
     * }
     */
    public function updateAssets(Request $request, string $id): JsonResponse
    {
        try {
            $section = PageSection::findOrFail($id);

            $request->validate([
                'assets' => 'required|array',
                'assets.*.id' => 'required|exists:assets,id',
                'assets.*.sort_order' => 'required|integer',
            ]);

            $syncData = [];
            foreach ($request->assets as $assetData) {
                $syncData[$assetData['id']] = ['sort_order' => $assetData['sort_order']];
            }

            // Sync assets with pivot data (detaching missing ones)
            $section->assets()->sync($syncData);

            return response()->json(['message' => 'Section assets updated successfully']);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating section assets: ' . $e->getMessage()], 500);
        }
    }
}
