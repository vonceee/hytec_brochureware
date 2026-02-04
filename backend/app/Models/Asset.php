<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_category_id',
        'title',
        'internal_name',
        'file_path',
        'file_name',
        'file_type',
        'alt_text',
        'width',
        'height',
        'size_kb',
        'is_active',
        'uploaded_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(AssetCategory::class, 'asset_category_id');
    }

    public function pageSections(): BelongsToMany
    {
        return $this->belongsToMany(PageSection::class, 'section_assets')
            ->withPivot('sort_order')
            ->withTimestamps();
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
