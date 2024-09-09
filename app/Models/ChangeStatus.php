<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChangeStatus extends Model
{
    use HasFactory;
    protected $table = "change_status";
    protected $dates = [
        'date',
        'created_at',
        'updated_at'
    ];
    protected $fillable = [
        'id_quotation',
        'status',
        'note',
    ];
    
    public function quotation()
    {
        return $this->belongsTo('App\Models\Quotation', 'id_quotation', 'id');
    }
    public function comment()
    {
        return $this->hasMany('App\Models\Comment', 'id_status');
    }
}
