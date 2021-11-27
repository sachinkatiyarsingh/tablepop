<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Questionnaire;
use Response;
class CalendarController extends Controller
{
  
    
    public function index(Request $req){
        $json = [];
        if(request()->ajax()) 
        {
            $data = Questionnaire::where('farEvent','exact')->get();
            foreach($data as $event){
                $id = !empty($event->tokenId) ? $event->tokenId : '' ;
                $eventName = !empty($event->eventName) ? $event->eventName : '' ;
                $created_at = !empty($event->created_at) ? $event->created_at : '' ;
                $farEvent = !empty($event->farEvent) ? $event->farEvent : '' ;
                $farEventDate = !empty($event->farEventDate) ? $event->farEventDate : '' ;
                
                if($farEvent == 'exact-date'){
                    $date = date("Y-m-d",strtotime($farEventDate));
                }else{
                    $date = date("Y-m-d",strtotime($created_at));
                }
                $json[] = array(
                    'id' => $id,
                    'title' => $eventName,
                    'start' => date("Y-m-d",strtotime($created_at)),
                    'color' => '#6D0202',
                );
            }
           return Response::json($json);
        }
         return view('calendar.index');
       }
    
    public function fullCalendar(Request $req,$id=''){
        $json = [];
        
        if(request()->ajax()) 
        {
            $data = Questionnaire::where('customerId',$id)->get()->toArray();
            foreach($data as $event){
                $id = !empty($event['id']) ? $event['id'] : '' ;
                $eventName = !empty($event['eventName']) ? $event['eventName'] : '' ;
                $created_at = !empty($event['created_at']) ? $event['created_at'] : '' ;
                $farEvent = !empty($event['farEvent']) ? $event['farEvent'] : '' ;
                $farEventDate = !empty($event['farEventDate']) ? $event['farEventDate'] : '' ;
                
                if($farEvent == 'exact-date'){
                    $date = date("Y-m-d",strtotime($farEventDate));
                }else{
                    $date = date("Y-m-d",strtotime($created_at));
                }
                $json[] = array(
                    'id' => $id,
                    'title' => $eventName,
                    'start' => date("Y-m-d",strtotime($created_at)),
                    'color' => '#6D0202',
                   
                );
            }
           return Response::json($json);
        }
         return view('calendar.customercalendar',['id'=>$id]);
       }
   
      
    
}
