<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorProduct extends Model
{
    protected $fillable = [
        'sellerId', 'name', 'quantity','description','regularPrice','salePrice',
    ];
}
