<?php
namespace App\Helpers;
use Illuminate\Support\Facades\Auth; 
use PeterPetrus\Auth\PassportToken;
use Mail;
use Illuminate\Support\Facades\DB;
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;
use App\MessageNotification;
use App\Notification;
use App\Admin;
class Socket{
 
    public static function notification($userId,$userType){
        try{

            if(!empty($userId)){
                $userId = $userId;
            }else{
                $adminData = Admin::where('type',1)->where('adminType',1)->get()->first();
                $userId = !empty($adminData->id) ? $adminData->id : 0 ;
            }
            
            if($userType == 'admin'){
                $type = 1;
                $notificationCount = Notification::where('type',$userType)->where('readStatus',0)->count();
            }elseif($userType == 'customer'){
                $type = 2;
                $notificationCount = Notification::where('customerId',$userId)->where('type',$userType)->where('readStatus',0)->count();
            }elseif($userType == 'seller'){
                $type = 3;
                $notificationCount = Notification::where('sellerId',$userId)->where('type',$userType)->where('readStatus',0)->count();
            }else{
                $type = '';
            }
            
          
            $notificationArray = ['userId'=>$userId,'type'=>$type,'count'=>$notificationCount];
            $version = new Version2X(env('SOCKET_URL'));
            $client = new Client($version);
            $client->initialize();
            $client->emit('notification_count',$notificationArray);
            $client->close();

        } catch (\Exception $e) {
            return response()->json(['data'=>'','status'=>false,'message'=>$e->getMessage(),'token'=>'']); 
            
        }
    }


    public static function message($userId,$userType){
        try{
           
            if(!empty($userId)){
                $userId = $userId;
            }else{
                $adminData = Admin::where('type',1)->where('adminType',1)->get()->first();
                $userId = !empty($adminData->id) ? $adminData->id : 0 ;
            }
            $notificationCount = MessageNotification::where('userId',$userId)->where('type',$userType)->count();
            $notificationArray = ['userId'=>$userId,'type'=>$userType,'count'=>$notificationCount];
            $version = new Version2X(env('SOCKET_URL'));
            $client = new Client($version);
            $client->initialize();
            $client->emit('message_count',$notificationArray);
            $client->close();

        } catch (\Exception $e) {
            return response()->json(['data'=>'','status'=>false,'message'=>$e->getMessage(),'token'=>'']); 
            
        }
    }


    
    
}