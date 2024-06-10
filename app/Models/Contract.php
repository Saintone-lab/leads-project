<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;
    protected $table = "contract";
    protected $date = [
        'date',
        'created_at',
        'updated_at'
    ];
    protected $fillable = [
        'id_quotation',
        'no_contract',
        'level',
        'type',
    ];
    
    public function quotation()
    {
        return $this->belongsTo('App\Models\Quotation', 'id_quotation', 'id');
    }
}
