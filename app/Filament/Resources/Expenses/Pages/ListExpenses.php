<?php

namespace App\Filament\Resources\Expenses\Pages;

use App\Filament\Resources\Expenses\ExpenseResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class ListExpenses extends ListRecords
{
    protected static string $resource = ExpenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Buat Pengeluaran'),
            $this->createReport()
                ->modalWidth(Width::Large),
        ];
    }

    public function createReport(): Action
    {
        return Action::make('create_report')
            ->label('Buat Report')
            ->modal()
            ->modalWidth(Width::ExtraLarge)
            ->modalHeading('Buat Report')
            ->icon(Heroicon::DocumentArrowDown)
            ->iconPosition(IconPosition::After)
            ->color('pink')
            ->modalSubmitActionLabel('View PDF')
            ->schema(
                fn (Schema $schema) => $schema
                    ->components([
                        $this->startDateComponent(),
                        $this->endDateComponent(),
                    ])->columns(2)
            )
            ->modalFooterActionsAlignment(Alignment::End)
            ->action(function (array $data) {
                $url = route('expense.report', [
                    'startDate' => $data['startDate'],
                    'endDate' => $data['endDate'],
                ]);

                $this->js("window.open('$url', '_blank')");
            });
    }

    public function startDateComponent(): DatePicker
    {
        return DatePicker::make('startDate')
            ->label('Dari tanggal')
            ->default(fn () => now()->startOfMonth())
            ->placeholder('YYYY-MM-DD')
            ->displayFormat('Y-m-d')
            ->closeOnDateSelection()
            ->native(false)
            ->live(onBlur: true)
            ->maxDate(fn (Get $get) => $get('endDate') ?: now());
    }

    public function endDateComponent(): DatePicker
    {
        return DatePicker::make('endDate')
            ->default(now())
            ->label('Sampai tanggal')
            ->placeholder('YYYY-MM-DD')
            ->displayFormat('Y-m-d')
            ->closeOnDateSelection()
            ->native(false)
            ->live(onBlur: true)
            ->minDate(fn (Get $get) => $get('startDate') ?: now());
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
