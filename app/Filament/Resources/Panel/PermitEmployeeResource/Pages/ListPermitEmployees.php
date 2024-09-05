<?php

namespace App\Filament\Resources\Panel\PermitEmployeeResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\PermitEmployeeResource;
use Filament\Resources\Components\Tab;

class ListPermitEmployees extends ListRecords
{
    protected static string $resource = PermitEmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('All'),
            'belum disetujui' => Tab::make()->query(fn ($query) => $query->where('status', '1')),
            'disetujui' => Tab::make()->query(fn ($query) => $query->where('status', '2')),
            'tidak disetujui' => Tab::make()->query(fn ($query) => $query->where('status', '3')),
            'pengajuan Ulang' => Tab::make()->query(fn ($query) => $query->where('status', '4')),
        ];
    }
}
