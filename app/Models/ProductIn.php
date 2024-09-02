<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductIn extends Model
{
    use HasFactory;
    protected $table = "product_in";
    protected $date = [
        'created_at',
        'updated_at',
        'date_invoice',
        'date'
    ];
    protected $fillable = [
        'no_do',
        'invoice',
        'supplier',
        'note',
        'subtotal',
        'total_no_tax',
        'shipping',
        'tax',
        'price',
    ];
    public function detail()
    {
        return $this->hasMany('App\Models\DetailProductIn', 'id_product_in');
    }
}
