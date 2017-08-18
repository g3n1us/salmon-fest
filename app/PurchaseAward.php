<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseAward extends Model
{
    protected $guarded = ['id'];
    
    
    protected $dataFormat = 'Y-m-d';
    
    public function purchases(){
	    return $this->hasMany('App\Purchase');
    }
    
    public function data_points()
    {
        return $this->hasManyThrough('App\DataPoint', 'App\Purchase');
    }   
    
    public function getDateAttribute($value){
	    return $this->attributes['date'] = date('F d, Y', strtotime($value));
    } 
    
    public function getTotalAmountAttribute($value){
	    $items = $this->data_points;
		$changed = $items->map(function ($item, $key) {
			return $item->price * $item->quantity;
		});	  
		return $changed->sum();  
    }
    
    
    public function getTotalCasesAttribute($value){
	    $items = $this->data_points;
		$changed = $items->map(function ($item, $key) {
			return $item->quantity;
		});	  
		return $changed->sum();  
    }
    
}
