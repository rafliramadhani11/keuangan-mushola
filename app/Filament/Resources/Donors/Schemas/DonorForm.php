<?php

namespace App\Filament\Resources\Donors\Schemas;

use App\Models\Donor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DonorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->hint('(optional)')
                    ->label('Email address')
                    ->email(),
                TextInput::make('phone')
                    ->hint('(optional)')
                    ->belowContent('ex: 898 1332 4231')
                    ->prefix('+62')
                    ->mask('999 9999 9999 9999'),
                Select::make('type')
                    ->options([
                        Donor::INDIVIDUAL => 'Per Orangan',
                        Donor::ORGANIZATION => 'Organisasi',
                    ])
                    ->native(false)
                    ->required(),
                Toggle::make('is_anonymous')
                    ->required(),
            ]);
    }
}
