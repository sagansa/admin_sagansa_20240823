<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Stock extends Cluster
{
    // protected static string|\UnitEnum|null $navigationGroup = 'Asset';

    protected static ?string $navigationLabel = 'Stock';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static ?int $navigationSort = 3;

    // protected static ?string $slug = 'asset/vehicles';
}
