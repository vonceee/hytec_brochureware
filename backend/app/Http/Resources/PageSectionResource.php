<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageSectionResource extends JsonResource
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
            'section_key' => $this->section_key,
            'title' => $this->title,
            'description' => $this->description,
            'sort_order' => $this->sort_order,
            'assets' => AssetResource::collection($this->whenLoaded('assets')),
        ];
    }
}
