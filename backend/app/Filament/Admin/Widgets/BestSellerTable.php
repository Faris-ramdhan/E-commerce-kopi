<?php

namespace App\Filament\Admin\Widgets;

use App\Models\OrderItem;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\DB;

class BestSellerTable extends TableWidget
{
    protected static ?int $sort = 3;

    protected static ?string $heading = '5 Produk & Varian Terlaris (Best Seller)';

    public function getTableRecordKey(\Illuminate\Database\Eloquent\Model|array $record): string
    {
        if (is_array($record)) {
            return (string) ($record['sku'] ?? $record['product_name'] ?? uniqid());
        }

        return (string) ($record->sku ?? $record->product_name ?? $record->id ?? uniqid());
    }

    public function table(Table $table): Table
    {
        return $table
            ->records(function () {
                return OrderItem::query()
                    ->select(
                        DB::raw('MIN(id) as id'),
                        'product_name',
                        'variant_name',
                        'sku',
                        DB::raw('SUM(quantity) as total_sold'),
                        DB::raw('SUM(subtotal) as total_revenue')
                    )
                    ->groupBy('product_name', 'variant_name', 'sku')
                    ->orderByDesc('total_sold')
                    ->limit(5)
                    ->get();
            })
            ->columns([
                TextColumn::make('product_name')
                    ->label('Nama Produk')
                    ->weight('bold')
                    ->description(fn (OrderItem $record): string => $record->variant_name ? "Varian: {$record->variant_name}" : ''),

                TextColumn::make('sku')
                    ->label('SKU')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('total_sold')
                    ->label('Terjual')
                    ->badge()
                    ->color('success')
                    ->suffix(' pcs'),

                TextColumn::make('total_revenue')
                    ->label('Total Pendapatan')
                    ->money('IDR', locale: 'id'),
            ])
            ->paginated(false);
    }
}
