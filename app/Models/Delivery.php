<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;
    protected $table = "delivery";
    protected $date = [
        'created_at',
        'updated_at',
        'date'
    ];
    protected $fillable = [
        'id_invoice',
        'destination',
    ];

    public function invoice()
    {
        return $this->belongsTo('App\Models\Invoice', 'id_invoice', 'id');
    }
    public function detail()
    {
        return $this->hasMany('App\Models\DetailDelivery', 'id_delivery');
    }
}
