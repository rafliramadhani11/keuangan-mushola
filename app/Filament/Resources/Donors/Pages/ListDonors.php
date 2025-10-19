<?php

namespace App\Filament\Resources\Donors\Pages;

use App\Filament\Resources\Donors\DonorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;

class ListDonors extends ListRecords
{
    protected static string $resource = DonorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Buat donatur'),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return 'Donatur';
    }

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.app.resources.donors.index') => 'Donatur',
            'Daftar',
        ];
    }
}
