<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ProductVariant extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'compare_at_price' => 'integer',
            'cost_price' => 'integer',
            'stock' => 'integer',
            'low_stock_threshold' => 'integer',
            'weight_grams' => 'integer',
            'is_default' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function optionValues(): BelongsToMany
    {
        return $this->belongsToMany(ProductOptionValue::class, 'option_value_variant');
    }

    public function movements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function isLowStock(): bool
    {
        return $this->stock <= $this->low_stock_threshold;
    }

    /**
     * Atomically adjust stock with lockForUpdate and record inventory ledger.
     */
    public function adjustStock(
        int $qty,
        string $type,
        ?int $userId = null,
        ?Model $reference = null,
        ?string $note = null
    ): self {
        return DB::transaction(function () use ($qty, $type, $userId, $reference, $note) {
            /** @var ProductVariant $lockedVariant */
            $lockedVariant = self::query()->where('id', $this->id)->lockForUpdate()->firstOrFail();

            $newStock = $lockedVariant->stock + $qty;

            if ($newStock < 0) {
                throw new Exception("Stok untuk SKU {$lockedVariant->sku} tidak mencukupi (stok saat ini: {$lockedVariant->stock}, diminta: {$qty}).");
            }

            $lockedVariant->stock = $newStock;
            $lockedVariant->save();

            $movementData = [
                'user_id' => $userId,
                'type' => $type,
                'quantity' => $qty,
                'stock_after' => $newStock,
                'note' => $note,
            ];

            if ($reference) {
                $movementData['reference_type'] = $reference->getMorphClass();
                $movementData['reference_id'] = $reference->getKey();
            }

            $lockedVariant->movements()->create($movementData);

            $this->stock = $newStock;

            return $lockedVariant;
        });
    }
}
