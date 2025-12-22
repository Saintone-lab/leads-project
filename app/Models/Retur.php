<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retur extends Model
{
    use HasFactory;
    
    protected $table = "return";
    protected $fillable = [
        "id_pending", 
        "id_product_in", 
        "id_replacement", 
        "qty", 
        "note",
        "status"
        ];

        public function pending(){
            return $this->belongsTo('App\Models\PendingPO', 'id_pending', 'id');
        }
        public function product(){
            return $this->belongsTo('App\Models\ProductIn', 'id_product_in', 'id');
        }
        public function replacement(){
            return $this->belongsTo('App\Models\DetailProduct', 'id_replacement', 'id');
        }
}
