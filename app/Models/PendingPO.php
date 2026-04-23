<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingPO extends Model
{
    use HasFactory;
    protected $table = "pending_po";
    protected $fillable = [
        'id_quotation',
        'ekspidisi',
        'status',
    ];
    public function quote()
    {
        return $this->belongsTo('App\Models\Quotation', 'id_quotation', 'id');
    }
    public function product_out()
    {
        return $this->belongsTo('App\Models\ProductOut', 'id_product_out', 'id');
    }
    public function detail()
    {
        return $this->hasMany('App\Models\DetailPendingPO', 'id_pending');
    }
    public function service()
    {
        return $this->hasMany('App\Models\ServiceOrder', 'id_sales_order');
    }
    public function pr()
    {
        return $this->hasMany('App\Models\PurchaseRequest', 'id_pending');
    }
    public function return()
    {
        return $this->hasMany('App\Models\Retur', 'id_pending');
    }
    public function expanse()
    {
        return $this->belongsTo('App\Models\Expanse', 'id_pending', 'id');
    }
}
