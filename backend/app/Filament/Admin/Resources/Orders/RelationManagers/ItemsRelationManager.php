<?php

namespace App\Filament\Admin\Resources\Orders\RelationManagers;

use App\Models\OrderItem;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'Item Produk yang Dipesan';

    public function form(Schema $schema): Schema
    {
        return $schema;
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('product_name')
            ->columns([
                TextColumn::make('product_name')
                    ->label('Nama Produk')
                    ->weight('bold')
                    ->description(fn (OrderItem $record): string => $record->variant_name ? "Varian: {$record->variant_name}" : ''),

                TextColumn::make('sku')
                    ->label('SKU')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('price')
                    ->label('Harga Satuan')
                    ->money('IDR', locale: 'id'),

                TextColumn::make('quantity')
                    ->label('Jumlah (Qty)')
                    ->badge()
                    ->color('success')
                    ->suffix(' pcs'),

                TextColumn::make('weight_grams')
                    ->label('Berat')
                    ->suffix(' g'),

                TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money('IDR', locale: 'id')
                    ->weight('bold'),
            ])
            ->paginated(false);
    }
}
