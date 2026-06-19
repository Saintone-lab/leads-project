<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FixedAsset extends Model
{
    protected $table = "fixed_asset";
    protected $date = [
        'date',
        'pakai',
        'bayar',
        'created_at',
        'updated_at'
    ];
    protected $fillable = [
        'type',
        'code',
        'no_invoice',
        'metode',
        'desc',
        'qty',
        'status',
        'total',
    ];
    public function aktiva()
    {
        return $this->belongsTo('App\Models\Account', 'id_aktiva', 'id');
    }
    public function penyusutan()
    {
        return $this->belongsTo('App\Models\Account', 'id_penyusutan', 'id');
    }
    public function beban()
    {
        return $this->belongsTo('App\Models\Account', 'id_beban', 'id');
    }
    public function pengeluaran()
    {
        return $this->belongsTo('App\Models\Account', 'id_pengeluaran', 'id');
    }
    public function supplier()
    {
        return $this->belongsTo('App\Models\Supplier', 'id_supplier', 'id');
    }
}
