<?php

namespace App\Filament\Admin\Resources\WholesaleLeads\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class WholesaleLeadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('business_name')
                    ->required(),
                TextInput::make('business_type'),
                TextInput::make('contact_name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('phone')
                    ->tel()
                    ->required(),
                TextInput::make('city'),
                TextInput::make('estimated_monthly_kg')
                    ->numeric(),
                Textarea::make('message')
                    ->columnSpanFull(),
                TextInput::make('status')
                    ->required()
                    ->default('new'),
                TextInput::make('assigned_to')
                    ->numeric(),
                Textarea::make('internal_note')
                    ->columnSpanFull(),
            ]);
    }
}
