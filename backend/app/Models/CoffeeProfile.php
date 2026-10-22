<?php

namespace App\Models;

use App\Enums\RoastLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoffeeProfile extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'roast_level' => RoastLevel::class,
            'altitude_masl' => 'integer',
            'acidity' => 'integer',
            'body' => 'integer',
            'sweetness' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function origin(): BelongsTo
    {
        return $this->belongsTo(Origin::class);
    }
}
