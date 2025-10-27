<?php

namespace App\Filament\Resources\Incomes\Pages;

use App\Filament\Resources\Incomes\IncomeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;

class ListIncomes extends ListRecords
{
    protected static string $resource = IncomeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Buat Pemasukan'),
        ];
    }

    public function getHeading(): string|Htmlable
    {
        return 'Pemasukan';
    }

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.app.resources.incomes.index') => 'Pemasukan',
            'Daftar',
        ];
    }
}
