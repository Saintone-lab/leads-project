<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailReturn extends Model
{
    protected $table = "detail_return";
    protected $fillable = [
        'id_return',
        'id_pn',
        'qty',
        'price',
        'amount',
        'note',
    ];

    
    public function return()
    {
        return $this->belongsTo('App\Models\ReturnQ', 'id_return', 'id');
    }
    public function equivalent()
    {
        return $this->belongsTo('App\Models\SerialProduct', 'id_pn', 'id');
    }
}
