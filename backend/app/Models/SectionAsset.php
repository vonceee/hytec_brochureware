<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class SectionAsset extends Pivot
{
    protected $table = 'section_assets';

    protected $fillable = [
        'page_section_id',
        'asset_id',
        'sort_order',
    ];
}
