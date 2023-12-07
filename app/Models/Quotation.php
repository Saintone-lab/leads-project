<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;
    protected $table = "quotation";
    protected $date = [
        'date_expired',
        'folup_date',
        'created_at',
        'updated_at'
    ];
    protected $fillable = [
        'id_client',
        'id_sales',
        'id_service',
        'status',
        'tax',
        'shipping',
        'no_quote',
        'termcon',
        'subtotal',
        'harga_total'
    ];

    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'id_client', 'id');
    }
    public function sales()
    {
        return $this->belongsTo('App\Models\User', 'id_sales', 'id');
    }
    public function service()
    {
        return $this->belongsTo('App\Models\Service', 'id_service', 'id');
    }
    
    
    public function detail_quotation()
    {
        return $this->hasMany('App\Models\DetailQuotation', 'id_quotation');
    }
    
}
