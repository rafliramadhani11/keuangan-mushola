<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;

class ManageCategories extends ManageRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->modalWidth(Width::Large),

            $this->createReport()
                ->modalWidth(Width::Large),
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
                $url = route('category.report', [
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
}
