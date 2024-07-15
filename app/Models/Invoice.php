<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = "invoice";
    protected $fillable = [
        'id_quotation',
        'term',
        'sign',
        'no_po',
        'no_invoice',
    ];

    
    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'id_client', 'id');
    }
}
