<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VariantOption extends Model
{
    protected $fillable = ['menu_variant_id', 'name', 'price_modifier'];

    protected $casts = [
        'price_modifier' => 'decimal:2',
    ];

    public function menuVariant(): BelongsTo
    {
        return $this->belongsTo(MenuVariant::class);
    }
}
