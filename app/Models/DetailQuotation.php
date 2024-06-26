<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailQuotation extends Model
{
    use HasFactory;
    protected $table = "detail_quotation";
    protected $fillable = [
        'id_quotation',
        'product',
        'detail_product',
        'qty',
        'disc',
        'price',
        'amount',
        'fee',
    ];

    
    public function quotation()
    {
        return $this->belongsTo('App\Models\Quotation', 'id_quotation', 'id');
    }
}
