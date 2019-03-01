<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
	protected $guarded = ['id'];


    public function sub_aspek_nilai(){
    	return $this->belongsTo('App\SubAspekNilai');
    }
    public function magang(){
    	return $this->belongsTo('App\Magang');
    }
}
