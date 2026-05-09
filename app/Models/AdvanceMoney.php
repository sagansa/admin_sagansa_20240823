<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdvanceMoney extends Model
{

    protected $connection = 'mysql';
    use HasFactory;

    protected $table = 'advance_moneys';

    protected $guarded = [];
}
