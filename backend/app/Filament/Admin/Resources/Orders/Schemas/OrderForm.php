<?php

namespace App\Filament\Admin\Resources\Orders\Schemas;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Status & Pembayaran')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('order_number')
                                ->label('Nomor Pesanan')
                                ->disabled()
                                ->required(),

                            Select::make('status')
                                ->label('Status Alur Pesanan')
                                ->options(OrderStatus::class)
                                ->required(),

                            Select::make('payment_status')
                                ->label('Status Pembayaran')
                                ->options(PaymentStatus::class)
                                ->required(),
                        ]),
                    ]),

                Section::make('Alamat & Tujuan Pengiriman')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('shipping_name')
                                ->label('Nama Penerima')
                                ->required(),

                            TextInput::make('shipping_phone')
                                ->label('Telepon Penerima')
                                ->tel()
                                ->required(),

                            TextInput::make('shipping_city')
                                ->label('Kota / Kabupaten')
                                ->required(),
                        ]),

                        Grid::make(3)->schema([
                            TextInput::make('shipping_province')
                                ->label('Provinsi')
                                ->required(),

                            TextInput::make('shipping_district')
                                ->label('Kecamatan'),

                            TextInput::make('shipping_postal_code')
                                ->label('Kode Pos')
                                ->required(),
                        ]),

                        Textarea::make('shipping_address')
                            ->label('Alamat Lengkap Pengiriman')
                            ->rows(3)
                            ->columnSpanFull()
                            ->required(),
                    ]),

                Section::make('Logistik & Ekspedisi')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('courier')
                                ->label('Ekspedisi Kurir')
                                ->placeholder('JNE / SiCepat / J&T'),

                            TextInput::make('courier_service')
                                ->label('Layanan Kurir')
                                ->placeholder('REG / YES / Cargo'),

                            TextInput::make('tracking_number')
                                ->label('Nomor Resi Pengiriman')
                                ->placeholder('Nomor AWB resi resmi'),
                        ]),

                        Grid::make(3)->schema([
                            TextInput::make('total_weight_grams')
                                ->label('Total Berat')
                                ->suffix('gram')
                                ->numeric(),

                            DateTimePicker::make('shipped_at')
                                ->label('Waktu Dikirim'),

                            DateTimePicker::make('completed_at')
                                ->label('Waktu Selesai'),
                        ]),
                    ]),

                Section::make('Ringkasan Finansial')
                    ->schema([
                        Grid::make(4)->schema([
                            TextInput::make('subtotal')
                                ->label('Subtotal Produk')
                                ->prefix('Rp')
                                ->numeric()
                                ->disabled(),

                            TextInput::make('discount_total')
                                ->label('Total Diskon')
                                ->prefix('Rp')
                                ->numeric(),

                            TextInput::make('shipping_cost')
                                ->label('Ongkos Kirim')
                                ->prefix('Rp')
                                ->numeric(),

                            TextInput::make('grand_total')
                                ->label('Total Akhir')
                                ->prefix('Rp')
                                ->numeric()
                                ->required(),
                        ]),

                        Grid::make(2)->schema([
                            Textarea::make('customer_note')
                                ->label('Catatan dari Pelanggan (Gilingan/Kemasan)')
                                ->disabled(),

                            Textarea::make('internal_note')
                                ->label('Catatan Internal Staf / Roaster')
                                ->placeholder('Catatan khusus untuk operasional'),
                        ]),
                    ]),
            ]);
    }
}
