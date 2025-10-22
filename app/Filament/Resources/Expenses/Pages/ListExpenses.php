<?php

namespace App\Filament\Resources\Expenses\Pages;

use App\Filament\Resources\Expenses\ExpenseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;

class ListExpenses extends ListRecords
{
    protected static string $resource = ExpenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getHeading(): string|Htmlable
    {
        return 'Pengeluaran';
    }

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.app.resources.expenses.index') => 'Pengeluaran',
            'Daftar',
        ];
    }
}
