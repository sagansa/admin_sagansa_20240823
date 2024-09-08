<?php

namespace App\Filament\Resources\Panel\SalesOrderDirectsResource\Pages;

use App\Filament\Resources\Panel\SalesOrderDirectsResource;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewSalesOrderDirects extends ViewRecord
{
    protected static string $resource = SalesOrderDirectsResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make()
                    ->schema([

                    ])
            ]);
    }
}
