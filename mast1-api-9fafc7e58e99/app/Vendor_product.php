<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor_product extends Model
{
    protected $fillable = [
        'sellerId', 'name', 'quantity','description','regularPrice','salePrice',
    ];

}
