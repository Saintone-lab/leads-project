<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pic extends Model
{
    use HasFactory;

    protected $table = "pic";
    protected $fillable = [
        'name',
        'position',
        'email',
        'phone'
    ];

    
    public function client()
    {
        return $this->hasMany('App\Model\Client', 'id_pic');
    }
    
}
