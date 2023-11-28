<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    
    protected $table = "client";
    protected $dates = [
        'created_date',
        'created_at',
        'updated_at'
    ];
    protected $fillable = [
        'id_sales',
        'id_pic',
        'id_issues',
        'id_service',
        'company',
        'email',
        'phone',
        'web',
        'image',
        'source',
        'role',
        'mobile',
        'address',
        'area'
    ];

    // Connection Table
    public function sales()
    {
        return $this->belongsTo('App\Models\User', 'id_sales', 'id');
    }
    public function issues()
    {
        return $this->belongsTo('App\Models\Issues', 'id_issues', 'id');
    }
    public function service()
    {
        return $this->belongsTo('App\Models\Service', 'id_service', 'id');
    }
    
    public function pic()
    {
        return $this->belongsTo('App\Models\Pic', 'id_pic', 'id');
    }
    
    // Extend Table
    
    public function activities()
    {
        return $this->hasMany('App\Models\Activities', 'id_client');
    }

    public function quotation()
    {
        return $this->hasMany('App\Models\quotation', 'id_client');
    }
    
    public function detail_client()
    {
        return $this->hasMany('App\Models\Detailclient', 'id_client');
    }
    
    
    public function visit()
    {
        return $this->hasMany('App\Models\Visit', 'id_client');
    }
    
}
