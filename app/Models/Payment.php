<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $table = "payment";
    protected $fillable = [
        'id_quotation',
        'file',
        'percent',
        'amount',
        'note',
    ];
    
    public function quotation()
    {
        return $this->belongsTo('App\Models\Quotation', 'id_quotation', 'id');
    }
}
