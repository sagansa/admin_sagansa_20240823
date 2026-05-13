<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class HRD extends Cluster
{
    // protected static string|\UnitEnum|null $navigationGroup = 'Asset';

    protected static ?string $navigationLabel = 'HRD';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?int $navigationSort = 2;

    // protected static ?string $slug = 'asset/vehicles';
}
