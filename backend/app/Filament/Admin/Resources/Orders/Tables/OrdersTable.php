<?php

namespace App\Filament\Admin\Resources\Orders\Tables;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('order_number')
                    ->label('Nomor Pesanan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable()
                    ->copyMessage('Nomor pesanan disalin!'),

                TextColumn::make('shipping_name')
                    ->label('Penerima')
                    ->searchable()
                    ->description(fn (Order $record): string => ($record->courier ? "{$record->courier} - {$record->courier_service}" : 'Kurir belum diset') . " • {$record->shipping_city}"),

                TextColumn::make('status')
                    ->label('Status Pesanan')
                    ->badge(),

                TextColumn::make('payment_status')
                    ->label('Status Bayar')
                    ->badge(),

                TextColumn::make('grand_total')
                    ->label('Total Bayar')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                TextColumn::make('tracking_number')
                    ->label('Nomor Resi')
                    ->searchable()
                    ->placeholder('Belum ada resi')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'gray'),

                TextColumn::make('total_weight_grams')
                    ->label('Berat')
                    ->suffix(' g')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Waktu Pesan')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Pesanan')
                    ->options(OrderStatus::class),

                SelectFilter::make('payment_status')
                    ->label('Status Pembayaran')
                    ->options(PaymentStatus::class),
            ])
            ->recordActions([
                // Aksi Update Resi Pengiriman
                Action::make('update_tracking')
                    ->label('Input Resi')
                    ->icon('heroicon-o-truck')
                    ->color('info')
                    ->form([
                        TextInput::make('tracking_number')
                            ->label('Nomor Resi Ekspedisi')
                            ->placeholder('Contoh: JNE88299100234')
                            ->required(),
                        TextInput::make('courier')
                            ->label('Nama Kurir')
                            ->default(fn (Order $record) => $record->courier ?? 'JNE')
                            ->required(),
                    ])
                    ->action(function (Order $record, array $data) {
                        $record->update([
                            'tracking_number' => $data['tracking_number'],
                            'courier' => $data['courier'],
                            'status' => OrderStatus::Shipped,
                            'shipped_at' => now(),
                        ]);

                        $record->histories()->create([
                            'user_id' => auth('web')->id(),
                            'from_status' => $record->getOriginal('status')?->value ?? null,
                            'to_status' => OrderStatus::Shipped->value,
                            'note' => "Resi {$data['tracking_number']} ({$data['courier']}) telah diinput. Pesanan dialihkan ke status Dikirim.",
                        ]);

                        Notification::make()
                            ->title("Resi pengiriman pesanan {$record->order_number} berhasil diperbarui!")
                            ->success()
                            ->send();
                    }),

                // Aksi Cetak Label Pengiriman / Invoice
                Action::make('print_label')
                    ->label('Cetak Label')
                    ->icon('heroicon-o-printer')
                    ->color('gray')
                    ->url(fn (Order $record): string => route('admin.orders.print-label', $record))
                    ->openUrlInNewTab(),

                EditAction::make()
                    ->label('Detail / Ubah'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // Bulk actions
                ]),
            ]);
    }
}
