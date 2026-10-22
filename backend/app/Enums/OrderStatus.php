<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum OrderStatus: string implements HasLabel, HasColor, HasIcon
{
    case PendingPayment = 'pending_payment';
    case Paid = 'paid';
    case Processing = 'processing';
    case Shipped = 'shipped';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case Refunded = 'refunded';

    public function getLabel(): string
    {
        return match ($this) {
            self::PendingPayment => 'Menunggu Pembayaran',
            self::Paid => 'Sudah Dibayar',
            self::Processing => 'Sedang Diproses',
            self::Shipped => 'Dalam Pengiriman',
            self::Completed => 'Pesanan Selesai',
            self::Cancelled => 'Dibatalkan',
            self::Refunded => 'Dana Dikembalikan',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::PendingPayment => 'warning',
            self::Paid => 'info',
            self::Processing => 'primary',
            self::Shipped => 'info',
            self::Completed => 'success',
            self::Cancelled => 'danger',
            self::Refunded => 'gray',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::PendingPayment => 'heroicon-m-clock',
            self::Paid => 'heroicon-m-check-circle',
            self::Processing => 'heroicon-m-arrow-path',
            self::Shipped => 'heroicon-m-truck',
            self::Completed => 'heroicon-m-check-badge',
            self::Cancelled => 'heroicon-m-x-circle',
            self::Refunded => 'heroicon-m-arrow-uturn-left',
        };
    }
}
