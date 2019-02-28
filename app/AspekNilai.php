<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AspekNilai extends Model
{
    protected $guarded = ['id'];

    public function sub_aspek_nilai(){
    	return $this->hasMany('App\SubAspekNilai');
    }
}
