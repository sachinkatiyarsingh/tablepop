<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MoodboardImage extends Model
{
    protected $fillable = [
        'image','moodboardId'
   ];
}
