<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ProductType: string implements HasLabel, HasColor, HasIcon
{
    case Coffee = 'coffee';
    case Tool = 'tool';
    case Apparel = 'apparel';
    case Merchandise = 'merchandise';
    case Book = 'book';

    public function getLabel(): string
    {
        return match ($this) {
            self::Coffee => 'Kopi (Beans/Ground)',
            self::Tool => 'Alat Seduh (Tools)',
            self::Apparel => 'Apparel & Pakaian',
            self::Merchandise => 'Merchandise & Aksesoris',
            self::Book => 'Buku & Panduan',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Coffee => 'primary',
            self::Tool => 'info',
            self::Apparel => 'gray',
            self::Merchandise => 'success',
            self::Book => 'warning',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Coffee => 'heroicon-m-sparkles',
            self::Tool => 'heroicon-m-wrench-screwdriver',
            self::Apparel => 'heroicon-m-tag',
            self::Merchandise => 'heroicon-m-gift',
            self::Book => 'heroicon-m-book-open',
        };
    }
}
