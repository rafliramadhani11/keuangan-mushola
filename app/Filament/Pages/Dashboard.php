<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\AccountWidget;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        DatePicker::make('startDate')
                            ->label('Dari tanggal')
                            ->placeholder('YYYY-MM-DD')
                            ->displayFormat('Y-m-d')
                            ->closeOnDateSelection()
                            ->native(false)
                            ->live(onBlur: true)
                            ->maxDate(fn (Get $get) => $get('endDate') ?: now()),

                        DatePicker::make('endDate')
                            ->default(fn () => now()->endOfDay())
                            ->label('Sampai tanggal')
                            ->placeholder('YYYY-MM-DD')
                            ->displayFormat('Y-m-d')
                            ->closeOnDateSelection()
                            ->native(false)
                            ->live(onBlur: true)
                            ->minDate(fn (Get $get) => $get('startDate') ?: now())
                            ->maxDate(now()),
                    ])
                    ->columns(2)
                    ->columnSpan(3),
            ]);
    }

    public function getColumns(): int|array
    {
        return 2;
    }

    protected function getHeaderActions(): array
    {
        return [
            $this->createReport(),
        ];
    }

    public function createReport()
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
                $url = route('dashboard.report', [
                    'startDate' => $data['startDate'],
                    'endDate' => $data['endDate'],
                ]);

                $this->js("window.open('$url', '_blank')");
            });
    }

    public function startDateComponent()
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

    public function endDateComponent()
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

    protected function getHeaderWidgets(): array
    {
        return [
            AccountWidget::class,
        ];
    }
}
