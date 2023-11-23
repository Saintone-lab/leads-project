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
        'qty',
        'price',
        'amount',
    ];

    
    public function quotation()
    {
        return $this->belongsTo('App\Model\Quotation', 'id_quotation', 'id');
    }
}
