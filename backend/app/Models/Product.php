<?php

namespace App\Models;

use App\Enums\ProductType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Product extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'type' => ProductType::class,
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'status', 'category_id'])
            ->logOnlyDirty();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function coffeeProfile(): HasOne
    {
        return $this->hasOne(CoffeeProfile::class);
    }

    public function flavorNotes(): BelongsToMany
    {
        return $this->belongsToMany(FlavorNote::class, 'flavor_note_product');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function options(): HasMany
    {
        return $this->hasMany(ProductOption::class)->orderBy('sort_order');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeFilterCoffee(Builder $query, array $filters): Builder
    {
        return $query->whereHas('coffeeProfile', function (Builder $q) use ($filters) {
            if (!empty($filters['roast_level'])) {
                $q->where('roast_level', $filters['roast_level']);
            }
            if (!empty($filters['process'])) {
                $q->where('process', $filters['process']);
            }
            if (!empty($filters['origin_id'])) {
                $q->where('origin_id', $filters['origin_id']);
            }
        })->when(!empty($filters['flavor_notes']), function (Builder $q) use ($filters) {
            $notes = (array) $filters['flavor_notes'];
            $q->whereHas('flavorNotes', function (Builder $fnQuery) use ($notes) {
                $fnQuery->whereIn('flavor_notes.id', $notes)
                    ->orWhereIn('flavor_notes.slug', $notes);
            });
        });
    }

    public function getPriceFromAttribute(): ?int
    {
        return $this->variants()
            ->where('is_active', true)
            ->min('price');
    }
}
