<?php

namespace App\Filament\Admin\Resources\WholesaleLeads;

use App\Filament\Admin\Resources\WholesaleLeads\Pages\CreateWholesaleLead;
use App\Filament\Admin\Resources\WholesaleLeads\Pages\EditWholesaleLead;
use App\Filament\Admin\Resources\WholesaleLeads\Pages\ListWholesaleLeads;
use App\Filament\Admin\Resources\WholesaleLeads\Schemas\WholesaleLeadForm;
use App\Filament\Admin\Resources\WholesaleLeads\Tables\WholesaleLeadsTable;
use App\Models\WholesaleLead;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class WholesaleLeadResource extends Resource
{
    protected static ?string $model = WholesaleLead::class;

    protected static ?string $modelLabel = 'Prospek B2B';
    protected static ?string $pluralModelLabel = 'Kemitraan Wholesale';

    protected static string | UnitEnum | null $navigationGroup = 'Wholesale';
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-briefcase';
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        $count = WholesaleLead::where('status', 'new')->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'primary';
    }

    public static function form(Schema $schema): Schema
    {
        return WholesaleLeadForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WholesaleLeadsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWholesaleLeads::route('/'),
            'create' => CreateWholesaleLead::route('/create'),
            'edit' => EditWholesaleLead::route('/{record}/edit'),
        ];
    }
}
