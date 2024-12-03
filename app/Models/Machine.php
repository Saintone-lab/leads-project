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
        'brand',
        'type',
        'serial_number',
        'bar',
        'running',
    ];

    
    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'id_client', 'id');
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
