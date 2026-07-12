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
        'id_unit',
        'serial_number',
        'kondisi',
        'qc_status',
        'status_unit',
        'harga_jual',
        'id_machine',
        'confirmed_by',
        'confirmed_at',
        'mulai_penyusutan',
        'jenis_kendaraan',
        'merk_model',
        'bahan_bakar',
        'plat_nomor',
        'atas_nama',
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
    public function unit()
    {
        return $this->belongsTo('App\Models\Unit', 'id_unit', 'id');
    }
    public function confirmedBy()
    {
        return $this->belongsTo('App\Models\User', 'confirmed_by', 'id');
    }
    public function services()
    {
        return $this->hasMany('App\Models\FixedAssetService', 'id_fixed_asset');
    }
    public function machine()
    {
        return $this->belongsTo('App\Models\Machine', 'id_machine', 'id');
    }
    public function maintenanceLogs()
    {
        return $this->hasMany('App\Models\VehicleMaintenanceLog', 'id_fixed_asset')->orderByDesc('tanggal');
    }
}
