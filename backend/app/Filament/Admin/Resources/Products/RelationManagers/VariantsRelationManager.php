<?php

namespace App\Filament\Admin\Resources\Products\RelationManagers;

use App\Models\Product;
use App\Models\ProductVariant;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class VariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'variants';

    protected static ?string $title = 'Varian Produk (SKU, Harga, Stok & Berat)';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)->schema([
                    TextInput::make('sku')
                        ->label('SKU (Kode Unik)')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),

                    TextInput::make('name')
                        ->label('Nama Varian')
                        ->placeholder('Contoh: 200g Biji Utuh / Size L')
                        ->maxLength(255),
                ]),

                Grid::make(3)->schema([
                    TextInput::make('price')
                        ->label('Harga Jual (IDR)')
                        ->numeric()
                        ->prefix('Rp')
                        ->required(),

                    TextInput::make('compare_at_price')
                        ->label('Harga Coret / Asli (IDR)')
                        ->numeric()
                        ->prefix('Rp'),

                    TextInput::make('cost_price')
                        ->label('Harga Modal (HPP)')
                        ->numeric()
                        ->prefix('Rp'),
                ]),

                Grid::make(3)->schema([
                    TextInput::make('stock')
                        ->label('Stok Saat Ini')
                        ->numeric()
                        ->default(0)
                        ->required(),

                    TextInput::make('low_stock_threshold')
                        ->label('Ambang Batas Stok Menipis')
                        ->numeric()
                        ->default(5)
                        ->required(),

                    TextInput::make('weight_grams')
                        ->label('Berat Pengiriman (Gram)')
                        ->numeric()
                        ->suffix('gram')
                        ->default(0)
                        ->required(),
                ]),

                Grid::make(2)->schema([
                    Toggle::make('is_default')
                        ->label('Varian Utama (Default)')
                        ->default(false),

                    Toggle::make('is_active')
                        ->label('Varian Aktif Dijual')
                        ->default(true),
                ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('sku')
            ->columns([
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('name')
                    ->label('Nama Varian')
                    ->searchable(),

                TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                TextColumn::make('stock')
                    ->label('Stok')
                    ->sortable()
                    ->badge()
                    ->color(fn(ProductVariant $record): string => $record->isLowStock() ? 'danger' : 'success'),

                TextColumn::make('weight_grams')
                    ->label('Berat')
                    ->suffix(' g'),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Varian Manual'),

                Action::make('generate_variants')
                    ->label('Generator Varian Otomatis')
                    ->icon('heroicon-m-bolt')
                    ->color('primary')
                    ->form([
                        CheckboxList::make('weights')
                            ->label('Pilihan Kemasan (Berat)')
                            ->options([
                                '200g' => '200 Gram (Berat: 220g)',
                                '500g' => '500 Gram (Berat: 530g)',
                                '1000g' => '1000 Gram (1 Kg) (Berat: 1050g)',
                            ])
                            ->default(['200g', '500g'])
                            ->required(),

                        CheckboxList::make('grind_sizes')
                            ->label('Pilihan Grind Size (Gilingan)')
                            ->options([
                                'whole_bean' => 'Biji Utuh (Whole Bean)',
                                'coarse' => 'Kasar (Cold Brew / French Press)',
                                'medium' => 'Sedang (V60 / Filter / Kalita)',
                                'fine' => 'Halus (Espresso / Tubruk / Mokapot)',
                            ])
                            ->default(['whole_bean', 'medium'])
                            ->required(),

                        TextInput::make('base_price_200g')
                            ->label('Harga Dasar 200g (IDR)')
                            ->numeric()
                            ->default(110000)
                            ->prefix('Rp')
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        /** @var Product $ownerRecord */
                        $ownerRecord = $this->getOwnerRecord();
                        $slugPrefix = strtoupper(Str::slug(substr($ownerRecord->name, 0, 15)));

                        $weightsMap = [
                            '200g' => ['name' => '200g', 'weight' => 220, 'mult' => 1.0],
                            '500g' => ['name' => '500g', 'weight' => 530, 'mult' => 2.3],
                            '1000g' => ['name' => '1000g', 'weight' => 1050, 'mult' => 4.2],
                        ];

                        $grindsMap = [
                            'whole_bean' => ['label' => 'Biji Utuh', 'code' => 'BEAN'],
                            'coarse' => ['label' => 'Kasar (Cold Brew)', 'code' => 'COARSE'],
                            'medium' => ['label' => 'Sedang (Filter/V60)', 'code' => 'MED'],
                            'fine' => ['label' => 'Halus (Espresso/Tubruk)', 'code' => 'FINE'],
                        ];

                        $count = 0;
                        $basePrice = (int) $data['base_price_200g'];

                        foreach ($data['weights'] as $wKey) {
                            $wInfo = $weightsMap[$wKey] ?? null;
                            if (!$wInfo) continue;

                            $price = (int) round($basePrice * $wInfo['mult'], -3);

                            foreach ($data['grind_sizes'] as $gKey) {
                                $gInfo = $grindsMap[$gKey] ?? null;
                                if (!$gInfo) continue;

                                $sku = "{$slugPrefix}-{$wInfo['name']}-{$gInfo['code']}";
                                $name = "{$wInfo['name']} - {$gInfo['label']}";

                                ProductVariant::firstOrCreate(
                                    [
                                        'product_id' => $ownerRecord->id,
                                        'sku' => $sku,
                                    ],
                                    [
                                        'name' => $name,
                                        'price' => $price,
                                        'cost_price' => (int) round($price * 0.55),
                                        'stock' => 20,
                                        'low_stock_threshold' => 5,
                                        'weight_grams' => $wInfo['weight'],
                                        'is_default' => ($count === 0),
                                        'is_active' => true,
                                    ]
                                );
                                $count++;
                            }
                        }

                        Notification::make()
                            ->title("Berhasil membuat {$count} kombinasi varian!")
                            ->success()
                            ->send();
                    }),
            ])
            ->recordActions([
                Action::make('adjust_stock')
                    ->label('Ubah Stok')
                    ->icon('heroicon-o-arrows-up-down')
                    ->color('warning')
                    ->form([
                        TextInput::make('quantity')
                            ->label('Jumlah Perubahan (+ masuk, - keluar)')
                            ->numeric()
                            ->required(),
                        Select::make('type')
                            ->label('Tipe Penyesuaian')
                            ->options([
                                'purchase' => 'Barang Masuk (Purchase/Restock)',
                                'sale' => 'Penjualan (Sale)',
                                'adjustment' => 'Penyesuaian Manual (Adjustment)',
                                'return' => 'Retur Pelanggan (Return)',
                            ])
                            ->default('adjustment')
                            ->required(),
                        TextInput::make('note')
                            ->label('Catatan Ledger')
                            ->placeholder('Alasan penyesuaian stok'),
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
                                ->title('Stok berhasil diperbarui!')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Gagal mengubah stok: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
