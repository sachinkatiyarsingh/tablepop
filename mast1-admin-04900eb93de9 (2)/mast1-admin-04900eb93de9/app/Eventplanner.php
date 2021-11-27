<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Eventplanner extends Model
{
    protected $fillable = [
        'plannerId','questionnaireId','status',
   ];
}
