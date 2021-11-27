<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contactu extends Model
{
    protected $fillable = [
        'name','email','message',
   ];
}
