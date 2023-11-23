<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actvities extends Model
{
    use HasFactory;
    protected $table = "activities";
    protected $date = [
        'created_date',
        'created_at',
        'updated_at'
    ];
    protected $fillable = [
        'id_client',
        'name',
        'status',
        'action'
    ];

    public function client(){
        return $this->belongsTo('App\Models\Client', 'id_client', 'id');
    }
}
