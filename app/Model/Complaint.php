<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $table='complaints';

    public function user(){
        return $this->belongsTo('App\Model\User');
    }
}
