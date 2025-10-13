<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingPO extends Model
{
    use HasFactory;
    protected $table = "pending_po";
    protected $fillable = [
        'id_quotation',
        'ekspidisi',
        'status',
    ];
    public function quote()
    {
        return $this->belongsTo('App\Models\Quotation', 'id_quotation', 'id');
    }
    public function detail()
    {
        return $this->hasMany('App\Models\DetailPendingPO', 'id_pending');
    }
    public function expanse()
    {
        return $this->belongsTo('App\Models\Expanse', 'id_pending', 'id');
    }
}
