<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Purchases extends Cluster
{
    protected static ?string $navigationGroup = 'Transaction';

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'transaction/purchases';
}