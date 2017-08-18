<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $guarded = ['id'];
    
    protected $casts = [
    ];
    
//     protected $dateFormat = 'Y-m-d';
    
    protected $dates = ['created_at', 'updated_at', 'solicitation_date', 'date'];
    
    public function data_points(){
	    return $this->hasMany('App\DataPoint');
    }
    
    public function purchase_award(){
	    return $this->belongsTo('App\PurchaseAward');
    }
    
    public function vendor(){
	    return $this->belongsTo('App\Vendor');
    }
    
}
