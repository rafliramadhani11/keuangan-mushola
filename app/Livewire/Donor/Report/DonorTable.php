<?php

namespace App\Livewire\Donor\Report;

use App\Models\Donor;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class DonorTable extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions, InteractsWithSchemas, InteractsWithTable;

    public string $startDate;

    public string $endDate;

    public function mount(string $startDate, string $endDate): void
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    protected function table(Table $table): Table
    {
        return $table
            ->query($this->donorsQuery())
            ->defaultSort('created_at', 'desc')
            ->paginated(false)
            ->columns([
                TextColumn::make('name')
                    ->badge(),
                TextColumn::make('email')
                    ->label('Email address'),
                TextColumn::make('phone')
                    ->prefix('+62 '),
                TextColumn::make('type')
                    ->badge(),
            ]);
    }

    protected function donorsQuery(): Builder
    {
        return Donor::query()
            ->when($this->startDate, fn ($query) => $query->whereDate('created_at', '>=', $this->startDate))
            ->when($this->endDate, fn ($query) => $query->whereDate('created_at', '<=', $this->endDate));
    }

    public function render()
    {
        return view('livewire.donor.report.donor-table');
    }
}
