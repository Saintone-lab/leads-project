<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = "product";
    protected $date = [
        'date',
        'created_at',
        'updated_at'
    ];
    protected $fillable = [
        'commodity',
        'description',
        'go',
        'dimension',
        'frist_stock',
        'stock',
        'unit',
        'note',
    ];
    
    public function detail()
    {
        return $this->hasMany('App\Models\DetailProduct', 'id_product');
    }
    public function serial()
    {
        return $this->hasMany('App\Models\SerialProduct', 'id_product');
    }
}
