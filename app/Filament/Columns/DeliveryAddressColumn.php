<?php

namespace App\Filament\Columns;

use Filament\Tables\Columns\TextColumn;

class DeliveryAddressColumn extends TextColumn
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->formatStateusing(
                fn($record): string => '<ul>' . implode('', [
                    '<li>' . ($record->deliveryAddress->name ?? '') . '</li>',
                    '<li>' . ($record->deliveryAddress->recipient_name ?? '') . ' - ' . ($record->deliveryAddress->recipient_telp_no ?? '') . '</li>',
                    '<li>' . ($record->deliveryAddress->address ?? '') . '</li>',
                    '<li>' . ($record->deliveryAddress->subdistrict->name ?? '') . ', ' . ($record->deliveryAddress->district->name ?? '') . '</li>',
                    '<li>' . ($record->deliveryAddress->city->name  ?? ''). ', ' . ($record->deliveryAddress->province->name ?? '') . '</li>',
                    '<li>' . ($record->deliveryAddress->postalCode->postal_code ?? '') . '</li>',
                ]) . '</ul>'
            )
            ->html()
            ->toggleable(isToggledHiddenByDefault: true);
    }
}
