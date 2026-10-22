<?php

namespace App\Filament\Admin\Resources\WholesaleLeads\Pages;

use App\Filament\Admin\Resources\WholesaleLeads\WholesaleLeadResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWholesaleLeads extends ListRecords
{
    protected static string $resource = WholesaleLeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
