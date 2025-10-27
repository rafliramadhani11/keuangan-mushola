<?php

namespace App\Filament\Resources\Incomes\Pages;

use App\Filament\Resources\Incomes\IncomeResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;

class CreateIncome extends CreateRecord
{
    protected static string $resource = IncomeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();

        return $data;
    }

    public function getHeading(): string|Htmlable
    {
        return 'Pemasukan';
    }

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.app.resources.incomes.index') => 'Pemasukan',
            'Buat',
        ];
    }
}
