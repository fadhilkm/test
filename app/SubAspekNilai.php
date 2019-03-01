<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubAspekNilai extends Model
{
    public function penilaians(){
    	return $this->hasMany('App\Penilaian');
    }
    public function penilaian(){
    	return $this->hasOne('App\Penilaian');
    }
}
