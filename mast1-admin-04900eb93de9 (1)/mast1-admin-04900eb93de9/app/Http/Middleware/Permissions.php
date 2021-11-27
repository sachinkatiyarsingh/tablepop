<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use Closure;

use App\Admin;
class Permissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {  
        $Show = array();
        if (session()->get('id') != ''){
            $loginId = session()->get('id');
            $adminData = Admin::find($loginId);
            $permission = !empty($adminData['permission']) ? $adminData['permission'] : '';
            $type =  !empty($adminData['type']) ? $adminData['type'] : '';;
            $json = json_decode($permission,true);
            if(!empty($json)){
            $customerArray = !empty($json['customer']) ? $json['customer'] : array();
            $vendorArray = !empty($json['vendor']) ? $json['vendor'] : array();
            $eventtypes = !empty($json['eventtypes']) ? $json['eventtypes'] : array();
            $themes = !empty($json['themes']) ? $json['themes'] : array();
            $eventList = !empty($json['eventList']) ? $json['eventList'] : array();
            $calendar = !empty($json['calendar']) ? $json['calendar'] : array();
            $planner = !empty($json['planner']) ? $json['planner'] : array();
            $blog = !empty($json['blog']) ? $json['blog'] : array();
            $faq = !empty($json['faq']) ? $json['faq'] : array();
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
               }else if($currentController == 'PlannerController'){
                if(in_array($currentMethod,$planner)){
                     $Show =  $planner; 
                     view()->share('planner', $Show);
                }else{
                 return redirect()->intended('/');
                }
               }else if($currentController == 'EventController'){
                if(in_array($currentMethod,$eventtypes)){
                     $Show =  $eventtypes; 
                     view()->share('eventType', $Show);
                }else{
                 return redirect()->intended('/');
                }
               }else if($currentController == 'ThemeController'){
                if(in_array($currentMethod,$themes)){
                     $Show =  $themes; 
                     view()->share('themes', $Show);
                }else{
                 return redirect()->intended('/');
                }
               }else if($currentController == 'QuestionnaireController'){
                if(in_array($currentMethod,$eventList)){
                     $Show =  $eventList; 
                     view()->share('eventList', $Show);
                }else{
                 return redirect()->intended('/');
                }
               }else if($currentController == 'CalendarController'){
                if(in_array($currentMethod,$calendar)){
                     $Show =  $calendar; 
                     view()->share('calendar', $Show);
                }else{
                 return redirect()->intended('/');
                }
               }else if($currentController == 'BlogController'){
                    if(in_array($currentMethod,$blog)){
                         $Show =  $blog; 
                         view()->share('blog', $Show);
                    }else{
                     return redirect()->intended('/');
                    }
               }else if($currentController == 'FaqController'){
                    if(in_array($currentMethod,$faq)){
                         $Show =  $faq; 
                         view()->share('faq', $Show);
                    }else{
                     return redirect()->intended('/');
                    }
               }
             
            }
        }else{
            return redirect()->intended('login');
        }
        return $next($request);
    }
}
