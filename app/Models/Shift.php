<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shift extends Model
{
    protected $fillable = [
        'user_id',
        'opened_at',
        'closed_at',
        'opening_balance',
        'closing_balance',
        'expected_total',
        'difference',
        'notes',
        'status',
    ];

    protected $casts = [
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
        'opening_balance' => 'decimal:2',
        'closing_balance' => 'decimal:2',
        'expected_total' => 'decimal:2',
        'difference' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function holdOrders(): HasMany
    {
        return $this->hasMany(HoldOrder::class);
    }

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    public function closeShift(float $closingBalance, ?string $notes = null): void
    {
        $this->closed_at = now();
        $this->closing_balance = $closingBalance;
        $this->expected_total = $this->transactions()
            ->where('paid', true)
            ->where('void', false)
            ->sum('total_price');
        $this->difference = $closingBalance - $this->expected_total;
        $this->notes = $notes;
        $this->status = 'closed';
        $this->save();
    }
}
