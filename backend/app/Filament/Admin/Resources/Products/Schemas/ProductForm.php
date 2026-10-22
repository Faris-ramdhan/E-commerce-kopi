<?php

namespace App\Filament\Admin\Resources\Products\Schemas;

use App\Enums\ProductType;
use App\Enums\RoastLevel;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('ProductTabs')
                    ->tabs([
                        // TAB 1: INFORMASI
                        Tab::make('Informasi Dasar')
                            ->icon('heroicon-m-information-circle')
                            ->schema([
                                Section::make('Data Utama Produk')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('name')
                                                ->label('Nama Produk')
                                                ->placeholder('Contoh: Aceh Gayo Wine Process')
                                                ->required()
                                                ->maxLength(255)
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(function (string $operation, $state, callable $set) {
                                                    if ($operation === 'create') {
                                                        $set('slug', Str::slug($state));
                                                    }
                                                }),

                                            TextInput::make('slug')
                                                ->label('Slug URL')
                                                ->required()
                                                ->maxLength(255)
                                                ->unique(ignoreRecord: true)
                                                ->helperText('Otomatis dibuat dari nama atau sesuaikan manual.'),
                                        ]),

                                        Grid::make(3)->schema([
                                            Select::make('category_id')
                                                ->label('Kategori')
                                                ->relationship('category', 'name')
                                                ->searchable()
                                                ->preload()
                                                ->required(),

                                            Select::make('type')
                                                ->label('Tipe Produk')
                                                ->options(ProductType::class)
                                                ->required()
                                                ->live(),

                                            Select::make('status')
                                                ->label('Status Publikasi')
                                                ->options([
                                                    'draft' => 'Draf (Belum Terbit)',
                                                    'published' => 'Terbit (Aktif Dijual)',
                                                    'archived' => 'Arsip (Disembunyikan)',
                                                ])
                                                ->default('draft')
                                                ->required(),
                                        ]),

                                        Textarea::make('short_description')
                                            ->label('Deskripsi Singkat')
                                            ->placeholder('Ringkasan 1-2 kalimat untuk kartu produk di storefront.')
                                            ->maxLength(500)
                                            ->rows(3)
                                            ->columnSpanFull(),

                                        RichEditor::make('description')
                                            ->label('Deskripsi Lengkap (Rich Text)')
                                            ->columnSpanFull(),

                                        Grid::make(2)->schema([
                                            Toggle::make('is_featured')
                                                ->label('Tampilkan sebagai Produk Unggulan di Homepage')
                                                ->default(false),

                                            DateTimePicker::make('published_at')
                                                ->label('Waktu Publikasi')
                                                ->default(now()),
                                        ]),
                                    ]),
                            ]),

                        // TAB 2: MEDIA / GALERI
                        Tab::make('Media & Galeri')
                            ->icon('heroicon-m-photo')
                            ->schema([
                                Section::make('Foto Produk')
                                    ->description('Unggah foto produk. Gambar pertama yang ditandai utama akan menjadi cover katalog.')
                                    ->schema([
                                        Repeater::make('images')
                                            ->relationship('images')
                                            ->schema([
                                                FileUpload::make('path')
                                                    ->label('Berkas Foto')
                                                    ->disk(config('filesystems.default', 'public'))
                                                    ->directory('products')
                                                    ->image()
                                                    ->imageEditor()
                                                    ->required(),

                                                TextInput::make('alt')
                                                    ->label('Teks Alt (Aksesibilitas / SEO)')
                                                    ->placeholder('Deskripsi gambar untuk pembaca layar'),

                                                Toggle::make('is_primary')
                                                    ->label('Jadikan Foto Utama (Primary)')
                                                    ->default(false),
                                            ])
                                            ->columns(3)
                                            ->orderColumn('sort_order')
                                            ->defaultItems(1),
                                    ]),
                            ]),

                        // TAB 3: PROFIL KOPI (Khusus Coffee)
                        Tab::make('Profil Kopi')
                            ->icon('heroicon-m-sparkles')
                            ->visible(fn (callable $get) => $get('type') === ProductType::Coffee->value || $get('type') === ProductType::Coffee)
                            ->schema([
                                Section::make('Spesifikasi Kopi Single Origin')
                                    ->relationship('coffeeProfile')
                                    ->schema([
                                        Grid::make(3)->schema([
                                            Select::make('origin_id')
                                                ->label('Daerah Asal (Origin)')
                                                ->relationship('origin', 'name')
                                                ->searchable()
                                                ->preload()
                                                ->nullable(),

                                            TextInput::make('process')
                                                ->label('Proses Pascapanen')
                                                ->placeholder('Contoh: Natural, Washed, Wine Anaerobic'),

                                            Select::make('roast_level')
                                                ->label('Roast Level')
                                                ->options(RoastLevel::class)
                                                ->nullable(),
                                        ]),

                                        Grid::make(3)->schema([
                                            TextInput::make('variety')
                                                ->label('Varietas')
                                                ->placeholder('Contoh: Typica, Kartika, Timtim'),

                                            TextInput::make('producer')
                                                ->label('Petani / Produser')
                                                ->placeholder('Contoh: Koperasi Gayo'),

                                            TextInput::make('altitude_masl')
                                                ->label('Ketinggian (mdpl / masl)')
                                                ->numeric()
                                                ->placeholder('1500'),
                                        ]),

                                        Grid::make(3)->schema([
                                            Select::make('acidity')
                                                ->label('Acidity (Skala 1 - 5)')
                                                ->options([1 => '1 - Rendah', 2 => '2', 3 => '3 - Sedang', 4 => '4', 5 => '5 - Tinggi']),

                                            Select::make('body')
                                                ->label('Body (Skala 1 - 5)')
                                                ->options([1 => '1 - Ringan', 2 => '2', 3 => '3 - Medium', 4 => '4', 5 => '5 - Penuh/Heavy']),

                                            Select::make('sweetness')
                                                ->label('Sweetness (Skala 1 - 5)')
                                                ->options([1 => '1 - Rendah', 2 => '2', 3 => '3 - Sedang', 4 => '4', 5 => '5 - Manis Kuat']),
                                        ]),
                                    ]),

                                Section::make('Catatan Rasa (Flavor Notes)')
                                    ->schema([
                                        Select::make('flavorNotes')
                                            ->label('Pilihan Flavor Notes')
                                            ->relationship('flavorNotes', 'name')
                                            ->multiple()
                                            ->searchable()
                                            ->preload()
                                            ->helperText('Pilih aroma & cita rasa yang terdeteksi saat cupping.'),
                                    ]),
                            ]),

                        // TAB 4: SEO & META
                        Tab::make('SEO & Metadata')
                            ->icon('heroicon-m-globe-alt')
                            ->schema([
                                Section::make('Optimasi Mesin Pencari')
                                    ->schema([
                                        TextInput::make('meta_title')
                                            ->label('Meta Title')
                                            ->placeholder('Judul halaman untuk mesin pencari')
                                            ->maxLength(70),

                                        Textarea::make('meta_description')
                                            ->label('Meta Description')
                                            ->placeholder('Deskripsi ringkas yang tampil pada hasil pencarian Google')
                                            ->maxLength(160)
                                            ->rows(3),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
