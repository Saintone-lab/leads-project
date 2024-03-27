<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOut extends Model
{
    use HasFactory;
    protected $table = "product_out";
    protected $date = [
        'created_at',
        'updated_at',
        'date'
    ];
    protected $fillable = [
        'detail_client',
        'invoice',
        'note',
        'total',
    ];
    public function detail()
    {
        return $this->hasMany('App\Models\DetailProductIn', 'id_product_in');
    }
}
