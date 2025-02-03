<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Monitoring extends Model
{
    use HasFactory;
    protected $table = "monitoring";
    protected $date = [
        'created_at',
        'updated_at'
    ];
    protected $fillable = [
        'runing',
        // 'load',
        'pressure',
        'temp',
        'condition',
        'oil_level',
        'desc',
        'picture',
    ];

    public function machine()
    {
        return $this->belongsTo('App\Models\Machine', 'id_machine', 'id');
    }
    public function pic()
    {
        return $this->belongsTo('App\Models\User', 'id_pic', 'id');
    }
    public function reports()
    {
        return $this->hasMany('App\Models\Reports', 'id_monitoring');
    }
}
