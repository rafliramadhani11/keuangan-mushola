<?php

namespace App\Filament\Resources\Expenses\Pages;

use App\Filament\Resources\Expenses\ExpenseResource;
use App\Models\Transaction;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;

class CreateExpense extends CreateRecord
{
    protected static string $resource = ExpenseResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();

        $data['payment_method'] = Transaction::CASH_METHOD;

        return $data;
    }

    public function getHeading(): string|Htmlable
    {
        return 'Pengeluaran';
    }

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.app.resources.expenses.index') => 'Pengeluaran',
            'Buat',
        ];
    }
}
