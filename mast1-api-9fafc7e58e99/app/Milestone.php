<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    protected $fillable = [
        'sellerId','questionnaireId','description','isCompleted','amount','status'
   ];
}
