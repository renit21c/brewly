<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderType extends Model
{
    protected $fillable = ['name', 'code'];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
