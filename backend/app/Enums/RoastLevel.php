<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum RoastLevel: string implements HasLabel, HasColor, HasIcon
{
    case Light = 'light';
    case Medium = 'medium';
    case Dark = 'dark';

    public function getLabel(): string
    {
        return match ($this) {
            self::Light => 'Light Roast',
            self::Medium => 'Medium Roast',
            self::Dark => 'Dark Roast',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Light => 'warning',
            self::Medium => 'primary',
            self::Dark => 'gray',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Light => 'heroicon-m-sun',
            self::Medium => 'heroicon-m-fire',
            self::Dark => 'heroicon-m-moon',
        };
    }
}
