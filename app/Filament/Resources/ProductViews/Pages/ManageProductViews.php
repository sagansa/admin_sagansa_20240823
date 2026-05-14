<?php

namespace App\Filament\Resources\ProductViews\Pages;

use App\Filament\Resources\ProductViews\ProductViewResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageProductViews extends ManageRecords
{
    protected static string $resource = ProductViewResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
