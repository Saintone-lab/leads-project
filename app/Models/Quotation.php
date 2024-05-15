<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;
    protected $table = "quotation";
    protected $date = [
        'expired_date',
        'estimated_date',
        'po_date',
        'created_at',
        'updated_at'
    ];
    protected $fillable = [
        'id_pic',
        'id_sales',
        'id_service',
        'no_pr',
        'status',
        'note',
        'flag',
        'tax',
        'diskon',
        'shipping',
        'no_quote',
        'termcon',
        'subtotal',
        'total_no_tax',
        'harga_total'
    ];

    public function pic()
    {
        return $this->belongsTo('App\Models\Pic', 'id_pic', 'id');
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
    public function termncon()
    {
        return $this->hasMany('App\Models\Termncon', 'id_quotation');
    }
    public function revisi()
    {
        return $this->hasMany('App\Models\RevQuote', 'id_quotation');
    }
    
}
