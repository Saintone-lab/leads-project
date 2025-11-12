<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $table = "supplier    ";
    protected $date = [
        'date',
        'created_at',
        'updated_at'
    ];
    protected $fillable = [
        'supplier',
        'email',
        'phone',
        'web',
        'address',
        'npwp',
        'info',
    ];
}
