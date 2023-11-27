<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pic extends Model
{
    use HasFactory;

    protected $table = "pic";
    protected $fillable = [
        'name_pic',
        'position',
        'email_pic',
        'phone_pic'
    ];

    
    public function client()
    {
        return $this->hasMany('App\Model\Client', 'id_pic');
    }
    
}
