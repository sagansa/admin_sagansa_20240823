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
            ->openable()
            // ->maxSize(5120)
            ->optimize('webp')
            ->imageResizeMode('cover')
            // ->imageCropAspectRatio('1:1')
            // ->imageResizeTargetWidth('1920')
            // ->imageResizeTargetHeight('1080')
            ->image()
            ->imageEditor()
            ->disk('public')
            ->columnSpan([
                'full'
            ])
            ->imageEditorAspectRatios([null, '16:9', '4:3', '1:1'])
        ;
    }
}
