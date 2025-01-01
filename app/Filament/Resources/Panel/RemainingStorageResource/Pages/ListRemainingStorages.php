<?php

namespace App\Filament\Resources\Panel\RemainingStorageResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\RemainingStorageResource;
use Carbon\Carbon;
use Filament\Resources\Components\Tab;

class ListRemainingStorages extends ListRecords
{
    protected static string $resource = RemainingStorageResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()
            ->visible(fn () => $this->isWithinAllowedHours()),
        ];
    }

    protected function isWithinAllowedHours(): bool
    {
        $currentHour = Carbon::now()->hour; // Pastikan timezone Anda sudah disesuaikan
        return $currentHour >= 21 && $currentHour <= 8; // Jam 08:00 - 18:00
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('All'),
            'belum diperiksa' => Tab::make()->query(fn ($query) => $query->where('status', '1')),
            'valid' => Tab::make()->query(fn ($query) => $query->where('status', '2')),
            'perbaiki' => Tab::make()->query(fn ($query) => $query->where('status', '3')),
            'periksa ulang' => Tab::make()->query(fn ($query) => $query->where('status', '4')),
        ];
    }
}
