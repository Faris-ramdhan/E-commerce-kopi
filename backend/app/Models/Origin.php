<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Origin extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function coffeeProfiles(): HasMany
    {
        return $this->hasMany(CoffeeProfile::class);
    }
}
