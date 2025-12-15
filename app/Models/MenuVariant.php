<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MenuVariant extends Model
{
    protected $fillable = ['name', 'type'];

    public function options(): HasMany
    {
        return $this->hasMany(VariantOption::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_menu_variants')
            ->withPivot('required')
            ->withTimestamps();
    }
}
