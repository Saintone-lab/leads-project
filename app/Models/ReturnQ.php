<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnQ extends Model
{
    use HasFactory;
    
    protected $table = "return";
    protected $fillable = [
        'id_quotation',
        'no_return',
        'note',
        'subtotal',
        'tax',
        'total',
        'date',
    ];

    
    public function quote()
    {
        return $this->belongsTo('App\Models\Quotation', 'id_quotation', 'id');
    }
}
