<?php

namespace App\Filament\Columns;

use Filament\Tables\Columns\TextColumn;

class SupplierColumn extends TextColumn
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->copyable()
            ->searchable()
            ->formatStateUsing(
                fn($record): string => '<ul>' . implode('', [
                    '<li>Nama: ' . $record->supplier->name . '</li>',
                    '<li>Bank: ' . ($record->supplier->bank ? $record->supplier->bank->name : 'tidak tersedia') . '</li>',
                    '<li>Nama Rek.: ' . ($record->supplier->bank_account_name ? $record->supplier->bank_account_name : 'tidak tersedia') . '</li>',
                    '<li>No. Rek.: ' . ($record->supplier->bank_account_no ? $record->supplier->bank_account_no : 'tidak tersedia') . '</li>',
                ]) . '</ul>'
            )
            ->html()
            ->toggleable(isToggledHiddenByDefault: false);
    }
}
