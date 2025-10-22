<?php

namespace App\Filament\Resources\Incomes\Pages;

use App\Filament\Resources\Incomes\IncomeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;

class EditIncome extends EditRecord
{
    protected static string $resource = IncomeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    public function getHeading(): string|Htmlable
    {
        return 'Pemasukan';
    }

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.app.resources.expenses.index') => 'Pemasukan',
            'Ubah',
        ];
    }
}
