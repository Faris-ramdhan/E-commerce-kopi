<?php

namespace App\Filament\Admin\Resources\Products\Tables;

use App\Enums\ProductType;
use App\Enums\RoastLevel;
use App\Models\Product;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->paginationPageOptions([10, 25, 50])
            ->emptyStateHeading('Belum Ada Produk')
            ->emptyStateDescription('Mulai katalog Anda dengan menambahkan biji kopi atau produk pertama.')
            ->emptyStateIcon('heroicon-o-shopping-bag')
            ->emptyStateActions([
                Action::make('create')
                    ->label('Tambah Produk Pertama')
                    ->url(fn (): string => route('filament.admin.resources.products.create'))
                    ->icon('heroicon-m-plus')
                    ->button(),
            ])
            ->columns([
                ImageColumn::make('images.path')
                    ->label('Foto')
                    ->disk(config('filesystems.default', 'public'))
                    ->square()
                    ->extraImgAttributes(['class' => 'rounded-lg border border-[#e8dfd5] dark:border-[#382f28] object-cover'])
                    ->defaultImageUrl(url('https://ui-avatars.com/api/?name=Kopi&background=b85d38&color=ffffff&size=100'))
                    ->limit(1),

                TextColumn::make('name')
                    ->label('Nama Produk')
                    ->searchable(['name', 'slug'])
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Product $record): string => $record->variants->pluck('sku')->filter()->take(2)->join(', ')),

                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge(),

                TextColumn::make('coffeeProfile.roast_level')
                    ->label('Roast Level')
                    ->badge()
                    ->placeholder('-'),

                TextColumn::make('price_from')
                    ->label('Mulai Dari')
                    ->formatStateUsing(fn ($state) => $state ? 'Rp ' . number_format($state, 0, ',', '.') : 'Belum diset')
                    ->sortable(),

                TextColumn::make('total_stock')
                    ->label('Total Stok')
                    ->state(fn (Product $record): int => (int) $record->variants->sum('stock'))
                    ->badge()
                    ->color(fn (int $state): string => $state <= 10 ? 'danger' : 'success')
                    ->suffix(' pcs')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'published' => 'Terbit',
                        'draft' => 'Draf',
                        'archived' => 'Arsip',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft' => 'warning',
                        'archived' => 'danger',
                        default => 'gray',
                    }),

                ToggleColumn::make('is_featured')
                    ->label('Unggulan')
                    ->afterStateUpdated(function ($record, $state) {
                        Notification::make()
                            ->title($state ? "Produk '{$record->name}' ditandai sebagai unggulan!" : "Status unggulan '{$record->name}' dinonaktifkan.")
                            ->success()
                            ->send();
                    }),

                TextColumn::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Filter Kategori')
                    ->relationship('category', 'name'),

                SelectFilter::make('type')
                    ->label('Filter Tipe Produk')
                    ->options(ProductType::class),

                SelectFilter::make('roast_level')
                    ->label('Filter Roast Level')
                    ->options(RoastLevel::class)
                    ->query(fn (Builder $query, array $data) => filled($data['value']) ? $query->whereHas('coffeeProfile', fn ($q) => $q->where('roast_level', $data['value'])) : $query),

                Filter::make('is_featured')
                    ->label('Hanya Produk Unggulan')
                    ->query(fn (Builder $query): Builder => $query->where('is_featured', true)),

                Filter::make('low_stock')
                    ->label('Peringatan Stok Menipis')
                    ->query(fn (Builder $query): Builder => $query->whereHas('variants', fn ($q) => $q->whereColumn('stock', '<=', 'low_stock_threshold'))),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Ubah'),
                DeleteAction::make()
                    ->label('Hapus')
                    ->modalHeading('Hapus Produk')
                    ->modalDescription('Apakah Anda yakin ingin menghapus produk ini?'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Hapus yang Dipilih'),
                ]),
            ]);
    }
}
