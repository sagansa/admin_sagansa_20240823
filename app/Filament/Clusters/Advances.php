<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Advances extends Cluster
{
    // protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationGroup = 'Purchase';

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'purchase/advances';
}
