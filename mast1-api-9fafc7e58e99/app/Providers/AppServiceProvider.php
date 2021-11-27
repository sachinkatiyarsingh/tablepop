<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
 
        Validator::extend('greater_than_field', function($attribute, $value, $parameters, $validator) {
            $min_field = $parameters[0];
            $data = $validator->getData();
            $min_value = $data[$min_field] != null ? $data[$min_field] : 0 ;
            $min_valuess =  ($min_value  == null ? 0 : $min_value) ;
            if($min_value == 'null'){
                $vss = 0;
            }else{
                $vss = $min_value;
            }
           
            return $value > $vss;
        });   

        Validator::replacer('greater_than_field', function($message, $attribute, $rule, $parameters) {
            //echo $attribute;
        return  ucwords(strtolower($attribute)).' must be greater than';
        });

    }
}
