<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class 
DetailProduct extends Model
{
    use HasFactory;
    protected $table = "detail_product";
    protected $date = [
        'created_at',
        'updated_at'
    ];
    protected $fillable = [
        'replacement',
        'hpp',
        'stock',
        'warehouse_stock',
        'pending_stock',
    ];
    
    public function unit()
    {
        return $this->belongsTo('App\Models\Unit', 'id_product', 'id');
    }
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'id_product', 'id');
    }
    public function detailProductIn()
    {
        return $this->hasMany('App\Models\DetailProductIn', 'id_detail_product');
    }
    public function sparepart()
    {
        return $this->hasMany('App\Models\Sparepart', 'id_equivalent');
    }
}
