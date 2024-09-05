<?php

namespace App\Filament\Resources\Panel\ClosingCourierResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\ClosingCourierResource;

class ListClosingCouriers extends ListRecords
{
    protected static string $resource = ClosingCourierResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
