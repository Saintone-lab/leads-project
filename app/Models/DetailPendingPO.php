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
        'id_detail_service',
        'id_replacement',
        'bdg',
        'bks',
        'status',
        'note',
    ];
    public function pending()
    {
        return $this->belongsTo('App\Models\PendingPO', 'id_pending', 'id');
    }
    public function service()
    {
        return $this->belongsTo('App\Models\DetailServiceQuotation', 'id_detail_service', 'id');
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
