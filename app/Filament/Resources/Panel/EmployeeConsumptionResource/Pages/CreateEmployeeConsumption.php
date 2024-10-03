<?php

namespace App\Filament\Resources\Panel\EmployeeConsumptionResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Panel\EmployeeConsumptionResource;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

class CreateEmployeeConsumption extends CreateRecord
{
    protected static string $resource = EmployeeConsumptionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['for'] = 'employee_consumption';
        $data['user_id'] = Auth::id();
        $data['status'] = '1';

        return $data;
    }

    protected function afterCreate(): void
    {
        $employeeConsumption = $this->record;

        $user = Auth::user();

        Notification::make()
            ->title('New order')
            ->icon('heroicon-o-shopping-bag')
            // ->body("{$employeeConsumption->store?->nickname} - {$employeeConsumption->date}")
            ->body("{$employeeConsumption->date}")
            ->actions([
                Action::make('View')
                    ->url(EmployeeConsumptionResource::getUrl('view', ['record' => $employeeConsumption])),
            ])
            ->sendToDatabase($user);
    }
}
