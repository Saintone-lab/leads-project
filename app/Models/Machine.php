<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    use HasFactory; 
    protected $table = "machine";
    protected $fillable = [
        'id_client',
        'id_unit',
        'desc',
        'serial',
        'tag',
        'location',
    ];

    
    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'id_client', 'id');
    }
    public function unit()
    {
        return $this->belongsTo('App\Models\SerialProduct', 'id_unit', 'id');
    }
    public function reports()
    {
        return $this->hasMany('App\Models\Reports', 'id_machine');
    }
    public function monitoring()
    {
        return $this->hasMany('App\Models\Monitoring', 'id_machine');
    }
}
