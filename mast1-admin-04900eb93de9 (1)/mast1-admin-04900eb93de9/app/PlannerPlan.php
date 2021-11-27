<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlannerPlan extends Model
{
    protected $table = 'plannerplans';
    
    protected $fillable = [
        'sellerId','title','description','regularPrice','salePrice',
   ];
}
