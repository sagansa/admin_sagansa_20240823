<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Auth;

class Notes extends RichEditor
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->hiddenLabel()
            ->placeholder('notes')
            ->nullable()
            ->string()
            ->fileAttachmentsVisibility('public');
    }
}
