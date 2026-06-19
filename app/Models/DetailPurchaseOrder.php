<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPurchaseOrder extends Model
{
    use HasFactory;
    protected $table = "detail_purchase_order";
    protected $date = [
        'created_at',
        'updated_at'
    ];
    protected $fillable = [
        'product',
        'qty',
        'info_qty',
        'amount',
    ];
    public function purchase()
    {
        return $this->belongsTo('App\Models\PurchaseOrder', 'id_purchase_order', 'id');
    }
}
