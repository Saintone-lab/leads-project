<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Target extends Model
{
    use HasFactory;
    protected $table = "target";
    protected $date = [
        'created_at',
        'updated_at'
    ];
    protected $fillable = [
        'id_sales',
        'dc',
        'intro',
        'quote',
        'po'
    ];

    public function sales()
    {
        return $this->belongsTo('App\Models\User', 'id_sales', 'id');
    }
}
