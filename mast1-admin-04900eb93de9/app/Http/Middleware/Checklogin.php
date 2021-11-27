<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Admin;
class Checklogin
{
   
    public function handle($request, Closure $next)
    {
        $Show = array();
        if (session()->get('id') != ''){
           /*  $loginId = session()->get('id');
            $adminData = Admin::find($loginId);
            $permission = !empty($adminData['permission']) ? $adminData['permission'] : '';
            $type =  !empty($adminData['type']) ? $adminData['type'] : '';;
            $json = json_decode($permission,true);
            if(!empty($json)){
            $customerArray = !empty($json['customer']) ? $json['customer'] : array();
            $vendorArray = !empty($json['vendor']) ? $json['vendor'] : array();
            $currentController = class_basename(Route::current()->controller);
            $currentMethod = $request->route()->getActionMethod();
            
              
               if($currentController == 'CustomerController'){
                   if(in_array($currentMethod,$customerArray)){
                       $Show =   $customerArray; 
                       view()->share('customer', $Show);
                    }else{
                    return redirect()->intended('/');
                }
               }else if($currentController == 'VendorController'){
                if(in_array($currentMethod,$vendorArray)){
                     $Show =  $vendorArray; 
                     view()->share('vendor', $Show);
                }else{
                 return redirect()->intended('/');
                }
               }
             
            } */
        }else{
            return redirect()->intended('login');
        }
        
        return $next($request);
    }
}
