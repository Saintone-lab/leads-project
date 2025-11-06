<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $table = "expense";
    protected $date = [
        'date',
        'created_at',
        'updated_at'
    ];
    protected $fillable = [
        'no_expense',
        'divisi',
        'desc',
        'category',
        'method',
        'account',
        'total',
    ];
    public function detail()
    {
        return $this->hasMany('App\Models\DetailExpense', 'id_expsense');
    }
}
