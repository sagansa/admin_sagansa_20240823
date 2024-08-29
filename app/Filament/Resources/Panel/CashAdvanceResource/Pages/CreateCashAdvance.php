<?php

namespace App\Filament\Resources\Panel\CashAdvanceResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Panel\CashAdvanceResource;

class CreateCashAdvance extends CreateRecord
{
    protected static string $resource = CashAdvanceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        // $data['created_by_id'] = Auth::id();
        // $data['approved_by_id'] = Auth::id();
        $data['status'] = '1';

        return $data;
    }
}
