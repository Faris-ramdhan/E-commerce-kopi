<?php

namespace App\Filament\Admin\Widgets;

use App\Models\ProductVariant;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LowStockTable extends TableWidget
{
    protected static ?int $sort = 4;

    protected static ?string $heading = 'Peringatan Stok Menipis (Perlu Roasting / Restock)';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ProductVariant::query()
                    ->with('product')
                    ->where('is_active', true)
                    ->whereColumn('stock', '<=', 'low_stock_threshold')
                    ->orderBy('stock', 'asc')
            )
            ->columns([
                TextColumn::make('product.name')
                    ->label('Produk')
                    ->weight('bold'),

                TextColumn::make('sku')
                    ->label('SKU')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('name')
                    ->label('Varian')
                    ->placeholder('-'),

                TextColumn::make('stock')
                    ->label('Sisa Stok')
                    ->badge()
                    ->color('danger')
                    ->suffix(' pcs'),

                TextColumn::make('low_stock_threshold')
                    ->label('Batas Minimum')
                    ->suffix(' pcs'),
            ])
            ->recordActions([
                Action::make('add_stock')
                    ->label('Tambah Stok')
                    ->icon('heroicon-o-plus-circle')
                    ->color('warning')
                    ->form([
                        TextInput::make('quantity')
                            ->label('Jumlah Masuk (+)')
                            ->numeric()
                            ->required()
                            ->minValue(1),
                        Select::make('type')
                            ->label('Tipe Mutasi')
                            ->options([
                                'purchase' => 'Hasil Roasting / Restock Baru',
                                'adjustment' => 'Koreksi Stok Fisik Gudang',
                            ])
                            ->default('purchase')
                            ->required(),
                        TextInput::make('note')
                            ->label('Catatan Batch Sangrai / Faktur')
                            ->placeholder('Contoh: Batch Roasting #42'),
                    ])
                    ->action(function (ProductVariant $record, array $data) {
                        try {
                            $record->adjustStock(
                                (int) $data['quantity'],
                                $data['type'],
                                auth('web')->id(),
                                null,
                                $data['note'] ?? null
                            );
                            Notification::make()
                                ->title("Stok {$record->sku} berhasil ditambahkan!")
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Gagal menambah stok: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->paginated(false);
    }
}
