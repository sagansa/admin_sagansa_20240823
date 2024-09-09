<?php

namespace App\Filament\Columns;

use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

class ImageOpenUrlColumn extends ImageColumn
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->openUrlInNewTab() // Membuka URL di tab baru
            ->tooltip('Klik untuk membuka gambar di tab baru') // Tooltip untuk pengguna
            ->toggleable(isToggledHiddenByDefault: false);
    }
}
