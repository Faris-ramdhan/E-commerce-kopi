<?php

namespace App\Filament\Admin\Resources\WholesaleLeads\Pages;

use App\Filament\Admin\Resources\WholesaleLeads\WholesaleLeadResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWholesaleLead extends EditRecord
{
    protected static string $resource = WholesaleLeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
