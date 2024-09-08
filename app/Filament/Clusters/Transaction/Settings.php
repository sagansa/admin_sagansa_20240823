<?php

namespace App\Filament\Clusters\Transaction;

use Filament\Clusters\Cluster;

class Settings extends Cluster
{
    protected static ?string $navigationGroup = 'Transaction';

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'transaction/settings';
}
