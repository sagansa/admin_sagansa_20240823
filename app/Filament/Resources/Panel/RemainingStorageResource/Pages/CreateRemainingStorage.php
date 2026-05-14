<?php

namespace App\Filament\Resources\Panel\RemainingStorageResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Panel\RemainingStorageResource;
use Illuminate\Support\Facades\Auth;

class CreateRemainingStorage extends CreateRecord
{
    protected static string $resource = RemainingStorageResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['for'] = 'remaining_storage';
        $data['user_id'] = Auth::id();
        $data['status'] = '1';

        $hour = (int) now()->format('H');
        // Valid window: 22:00 to 11:00 (next day)
        // Invalid if: 11:00 <= hour < 22:00
        if ($hour >= 11 && $hour < 22) {
            abort(403, 'Laporan hanya bisa dibuat antara jam 22.00 hingga 11.00 keesokan harinya.');
        }

        // Check if report already exists for this store and date
        $exists = \App\Models\RemainingStorage::where('store_id', $data['store_id'])
            ->where('date', $data['date'])
            ->where('for', 'remaining_storage')
            ->exists();

        if ($exists) {
            abort(403, 'Laporan untuk toko ini pada tanggal tersebut sudah ada.');
        }

        return $data;
    }
}
