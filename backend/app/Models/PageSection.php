<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PageSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'section_key',
        'title',
        'description',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeSorted(Builder $query): Builder
    {
        return $query->orderBy('sort_order', 'asc');
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function assets(): BelongsToMany
    {
        return $this->belongsToMany(Asset::class, 'section_assets')
            ->withPivot('sort_order')
            ->withTimestamps();
    }
}
