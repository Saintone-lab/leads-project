<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SerialProduct extends Model
{
    use HasFactory;
    protected $table = "serial_product";
    protected $date = [
        'created_at',
        'updated_at'
    ];
    protected $fillable = [
        'commodity',
        'description',
        'hpp',
        'stock',
    ];
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'id_product', 'id');
    }
}
