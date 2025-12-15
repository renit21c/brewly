<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HoldOrder extends Model
{
    protected $fillable = ['shift_id', 'order_name', 'items', 'total', 'held_at', 'status'];

    protected $casts = [
        'items' => 'array',
        'total' => 'decimal:2',
        'held_at' => 'datetime',
    ];

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }
}
