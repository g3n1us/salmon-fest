<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataPoint extends Model
{
	
	protected $casts = [
		'quantity' => 'integer',
		'price' => 'float',
	];
	
    public function getPurchaseAwardAttribute(){
	    return \App\PurchaseAward::where('id', $this->purchase->purchase_award_id)->first();
    }	
	
    public function purchase(){
	    return $this->belongsTo('App\Purchase');
    }
	
}
