<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'image','questionnaireId',
   ];

    protected $hidden = [
    'updated_at','created_at'
    ]; 
}
