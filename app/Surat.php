<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    public function jenis_surat(){
    	return $this->belongsTo('App\JenisSurat');
    }
}
