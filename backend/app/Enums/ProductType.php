<?php

namespace App\Enums;

enum ProductType: string
{
    case Coffee = 'coffee';
    case Tool = 'tool';
    case Apparel = 'apparel';
    case Merchandise = 'merchandise';
    case Book = 'book';

    public function label(): string
    {
        return match ($this) {
            self::Coffee => 'Coffee',
            self::Tool => 'Brewing Tool',
            self::Apparel => 'Apparel',
            self::Merchandise => 'Merchandise',
            self::Book => 'Book',
        };
    }
}
