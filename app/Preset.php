<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Preset extends Model
{
    protected $table = "presets";
    
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];
}
