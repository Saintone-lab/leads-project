<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PowerServicePrice extends Model
{
    use HasFactory;

    protected $table = 'power_service_prices';

    protected $fillable = [
        'power',
        'price_pm1',
        'price_pm2',
        'price_pm3',
        'price_pm4',
    ];
}
