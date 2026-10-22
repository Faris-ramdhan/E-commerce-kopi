<?php

namespace App\Enums;

enum RoastLevel: string
{
    case Light = 'light';
    case Medium = 'medium';
    case Dark = 'dark';

    public function label(): string
    {
        return match ($this) {
            self::Light => 'Light Roast',
            self::Medium => 'Medium Roast',
            self::Dark => 'Dark Roast',
        };
    }
}
