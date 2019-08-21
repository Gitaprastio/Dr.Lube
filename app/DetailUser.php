<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailUser extends Model
{
    protected $table = 'detail_users';

    public function user(){
        return $this->belongsTo('App\User');
    }
}
