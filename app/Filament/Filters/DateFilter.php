<?php

namespace App\Filament\Filters;

use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class DateFilter extends Filter
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->form([
                DatePicker::make('date_from'),
                DatePicker::make('date_until'),
            ])
            ->query(function (Builder $query, array $data): Builder {
                return $query
                    ->when(
                        $data['date_from'],
                        fn (Builder $query, $date): Builder => $query->whereDate('delivery_date', '>=', $date),
                    )
                    ->when(
                        $data['date_until'],
                        fn (Builder $query, $date): Builder => $query->whereDate('delivery_date', '<=', $date),
                    );
            });
    }
}
