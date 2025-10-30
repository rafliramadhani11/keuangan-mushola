<?php

namespace App\Livewire\Category\Report;

use App\Models\Category;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class CategoryTable extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions, InteractsWithSchemas, InteractsWithTable;

    public string $startDate;

    public string $endDate;

    public function mount(string $startDate, string $endDate): void
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->categoriesQuery())
            ->defaultSort('created_at', 'desc')
            ->paginated(false)
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('type')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        Category::INCOME => 'Pemasukan',
                        Category::EXPENSE => 'Pengeluaran',
                    })
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        Category::INCOME => 'success',
                        Category::EXPENSE => 'danger',
                    }),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('desc'),
            ]);
    }

    public function categoriesQuery(): Builder
    {
        return Category::query()
            ->when($this->startDate, fn ($query) => $query->whereDate('created_at', '>=', $this->startDate))
            ->when($this->endDate, fn ($query) => $query->whereDate('created_at', '<=', $this->endDate));
    }

    public function render()
    {
        return view('livewire.category.report.category-table');
    }
}
