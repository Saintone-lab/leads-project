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
        'type',
        'flag',
        'no_po',
        'no_invoice',
        'date',
        'pph',
        'invoiceTo',
    ];

    
    public function quote()
    {
        return $this->belongsTo('App\Models\Quotation', 'id_quotation', 'id');
    }
    public function expense()
    {
        return $this->hasMany('App\Model\Expense', 'id_invoice');
    }
}
