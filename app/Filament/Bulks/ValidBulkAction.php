<?php

namespace App\Filament\Bulks;

use Filament\Actions\BulkAction;

class ValidBulkAction extends BulkAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->icon('heroicon-o-check')
            ->requiresConfirmation();
    }
}
