<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function getSubtotalAttribute(): int
    {
        return $this->items->sum(function (CartItem $item) {
            return ($item->variant->price ?? 0) * $item->quantity;
        });
    }

    public function getTotalWeightGramsAttribute(): int
    {
        return $this->items->sum(function (CartItem $item) {
            return ($item->variant->weight_grams ?? 0) * $item->quantity;
        });
    }
}
