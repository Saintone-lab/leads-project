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
        'brand',
        'pn',
        'image',
        'detail',
        'price',
        'rental',
        'second',
        'new',
    ];
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'id_product', 'id');
    }
    public function unit()
    {
        return $this->belongsTo('App\Models\Unit', 'id_product', 'id');
    }
    public function quotation()
    {
        return $this->hasMany('App\Models\Quotation', 'id_equivalent');
    }
    public function detail_return()
    {
        return $this->hasMany('App\Models\DetailReturn', 'id_pn');
    }
    public function detail_delivery()
    {
        return $this->hasMany('App\Models\DetailDelivery', 'id_pn');
    }
}
