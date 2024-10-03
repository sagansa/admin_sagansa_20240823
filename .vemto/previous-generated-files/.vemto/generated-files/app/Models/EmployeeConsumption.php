<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeConsumption extends Model
{
    use HasFactory;

    protected $table = 'stock_cards';

    protected $guarded = [];
}
