<?php

namespace App\Filament\Resources\Expenses\Pages;

use App\Filament\Resources\Expenses\ExpenseResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;

class EditExpense extends EditRecord
{
    protected static string $resource = ExpenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
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
            'Ubah',
        ];
    }
}
