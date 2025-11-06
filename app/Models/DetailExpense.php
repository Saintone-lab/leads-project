<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailExpense extends Model
{
    use HasFactory;
    protected $table = "detail_expense";
    protected $date = [
        'created_at',
        'updated_at'
    ];
    protected $fillable = [
        'desc',
        'sub_cat',
        'qty',
        'price',
        'amount',
    ];
    
    public function expense()
    {
        return $this->belongsTo('App\Models\Expense', 'id_Expense', 'id');
    }
}
