<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Closings extends Cluster
{
    // protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationGroup = 'Transaction';

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'transaction/closings';
}
