<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expanse extends Model
{
    use HasFactory;
    protected $table = "expanse";
    protected $date = [
        'date',
        'created_at',
        'updated_at'
    ];
    protected $fillable = [
        'kurir',
        'image',
        'charged',
        'cost',
        'no_track',
        'type'
    ];
    public function pending()
    {
        return $this->hasMany('App\Model\PendingPO', 'id_pending');
    }
}
