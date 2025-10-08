<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->required(),
                        TextInput::make('password')
                            ->password()
                            ->required()
                            ->hidden(fn ($operation) => $operation === 'edit'),
                        Select::make('roles')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable(),
                    ])
                    ->columns(2)
                    ->columnSpan(2),

                Section::make()
                    ->schema([
                        TextEntry::make('created_at'),
                        TextEntry::make('updated_at'),
                    ])
                    ->columns(1)
                    ->columnSpan(1)
                    ->hidden(fn ($operation) => $operation === 'create'),
            ])->columns(3);
    }
}
