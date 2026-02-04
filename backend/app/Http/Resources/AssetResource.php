<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AssetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'internal_name' => $this->internal_name,
            'url' => convertToFullUrl($this->file_path),
            'file_name' => $this->file_name,
            'file_type' => $this->file_type,
            'alt_text' => $this->alt_text,
            'width' => $this->width,
            'height' => $this->height,
            'size_kb' => $this->size_kb,
            'uploaded_at' => $this->created_at,
        ];
    }
}

function convertToFullUrl($path)
{
    if (!$path) return null;
    return Storage::url($path);
}
