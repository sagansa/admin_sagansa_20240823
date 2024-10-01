<?php

namespace App\Filament\Bulks;

use Filament\Tables\Actions\BulkAction;

class ValidBulkAction extends BulkAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label('Set Status to Valid')
            ->icon('heroicon-o-check')
            ->requiresConfirmation()
            ->color('success');
    }
}
