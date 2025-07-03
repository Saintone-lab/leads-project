<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPendingPO extends Model
{
    use HasFactory;
    protected $table = "detail_pending_po";
    protected $fillable = [
        'id_pending',
        'id_replacement',
        'desc',
        'qty',
        'status',
        'note',
    ];
    public function pending()
    {
        return $this->belongsTo('App\Models\PendingPO', 'id_pending', 'id');
    }
    public function replacement()
    {
        return $this->belongsTo('App\Models\DetailProduct', 'id_replacement', 'id');
    }
    public function expense()
    {
        return $this->hasMany('App\Model\Expense', 'id_invoice');
    }
}
