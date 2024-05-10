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
        'id_user',
        'detail_client',
        'invoice',
        'note',
        'vers',
        'total',
    ];
    public function detail()
    {
        return $this->hasMany('App\Models\DetailProductIn', 'id_product_out');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user', 'id');
    }
}
