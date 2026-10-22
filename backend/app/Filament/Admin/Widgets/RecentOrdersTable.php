<?php

namespace App\Filament\Admin\Widgets;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class RecentOrdersTable extends TableWidget
{
    protected static ?int $sort = 5;

    protected static ?string $heading = '5 Pesanan Terbaru';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('order_number')
                    ->label('Nomor Pesanan')
                    ->weight('bold')
                    ->searchable(),

                TextColumn::make('shipping_name')
                    ->label('Pelanggan')
                    ->description(fn (Order $record): string => $record->shipping_city ?? ''),

                TextColumn::make('grand_total')
                    ->label('Total Bayar')
                    ->money('IDR', locale: 'id'),

                TextColumn::make('status')
                    ->label('Status Pesanan')
                    ->badge(),

                TextColumn::make('payment_status')
                    ->label('Status Bayar')
                    ->badge(),

                TextColumn::make('created_at')
                    ->label('Waktu Masuk')
                    ->dateTime('d M Y, H:i'),
            ])
            ->paginated(false);
    }
}
