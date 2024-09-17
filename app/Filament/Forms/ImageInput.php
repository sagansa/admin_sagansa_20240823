<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\FileUpload;

class ImageInput extends FileUpload
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->rules(['image'])
            // ->hiddenLabel()
            ->nullable()
            ->maxSize(1024)
            ->image()
            ->imageEditor()
            ->columnSpan([
                'full'
            ])
            ->imageEditorAspectRatios([null, '16:9', '4:3', '1:1']);
    }
}
