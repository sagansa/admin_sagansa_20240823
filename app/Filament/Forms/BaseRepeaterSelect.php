<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\Select;

class BaseRepeaterSelect extends Select
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->hiddenLabel()
            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
            ->required()
            ->disabled();
    }
}
