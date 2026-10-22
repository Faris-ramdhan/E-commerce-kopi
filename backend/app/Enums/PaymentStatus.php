<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum PaymentStatus: string implements HasLabel, HasColor, HasIcon
{
    case Pending = 'pending';
    case Paid = 'paid';
    case Failed = 'failed';
    case Expired = 'expired';
    case Refunded = 'refunded';

    public function getLabel(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu Pembayaran',
            self::Paid => 'Lunas',
            self::Failed => 'Pembayaran Gagal',
            self::Expired => 'Kadaluarsa',
            self::Refunded => 'Dana Dikembalikan',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Paid => 'success',
            self::Failed => 'danger',
            self::Expired => 'gray',
            self::Refunded => 'info',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Pending => 'heroicon-m-clock',
            self::Paid => 'heroicon-m-check-badge',
            self::Failed => 'heroicon-m-x-circle',
            self::Expired => 'heroicon-m-no-symbol',
            self::Refunded => 'heroicon-m-arrow-path',
        };
    }
}
