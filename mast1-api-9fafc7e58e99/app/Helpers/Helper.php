<?php
namespace App\Helpers;
use Illuminate\Support\Facades\Auth; 
use PeterPetrus\Auth\PassportToken;
use Mail;
use Illuminate\Support\Facades\DB;
use SpacesConnect;
use App\Cart;
use App\AdminSetting;
use App\Transaction;
use App\Vendor_product;
use App\Questionnaire;
use Twilio\Rest\Client;


class Helper{
  

    public static function encode_token($token) 
    {
        
        $token = new PassportToken($token);
             if ($token->valid) 
             {
                if ($token->existsValid()) 
                {
                    return $token->user_id;
                }
        }
    }
    
    public static function sendMessage($msg ,$no){
         $sid = env('TWILIO_ACCOUNT_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        try{
            $twilio = new Client($sid, $token);
            $message = $twilio->messages->create($no,["body" => $msg,"from" => env('TWILIO_NUMBER')]); 
            return true;
        } catch (\Exception $e) {
            return response()->json(['data'=>'','status'=>false,'message'=>$e->getMessage(),'token'=>'']); 
            
        }
       
     }

    public static function send_mail($tmplale,$data) {
        try{
            Mail::send($tmplale,$data, function ($message)  use($data) {
            $message->to($data['email'])->subject($data['subject']);
            }); 
        } catch (\Exception $e) {      
        }
    }


    public static function sendMailAttachData($tmplale,$data,$pdf) {
        try{
        Mail::send($tmplale,$data, function($message)use($data,$pdf) {
            $message->to($data["email"])->subject($data["subject"])
            ->attachData($pdf->output(), $data["pdfName"]);
            });
        } catch (\Exception $e) {      
        }
       
    }
   
    

    public static function imageUpload($image,$file,$folder){
        $key = env('AWS_ACCESS_KEY_ID');
        $secret = env('AWS_SECRET_ACCESS_KEY');
        $space_name = env('AWS_BUCKET');
        $region = env('AWS_DEFAULT_REGION');
        try {
            $space = new SpacesConnect($key, $secret, $space_name, $region);
            $path_to_file = $image;
            $path = $folder."/".$path_to_file;
            $space->UploadFile($file,"public",$path);  
     
         } catch (\SpacesAPIException $e) {
           $error = $e->GetError();
           return  $error;
         }
       
    
    }
  
  
     public static function getImageUrl($file){
        $key = env('AWS_ACCESS_KEY_ID');
        $secret = env('AWS_SECRET_ACCESS_KEY');
        $space_name = env('AWS_BUCKET');
        $region = env('AWS_DEFAULT_REGION');
       
        try {
            $space = new SpacesConnect($key, $secret, $space_name, $region);
            $link = $space->GetObject($file);
            return $link['@metadata']['effectiveUri'];
         } catch (\SpacesAPIException $e) {
           $error = $e->GetError();
           return asset('resources/assets/demo/images/thumbnail.png');
         }
    }

    
    public static function deleteImage($file){
        $key = env('AWS_ACCESS_KEY_ID');
        $secret = env('AWS_SECRET_ACCESS_KEY');
        $space_name = env('AWS_BUCKET');
        $region = env('AWS_DEFAULT_REGION');
      
       
        try {
            $space = new SpacesConnect($key, $secret, $space_name, $region);
            $space->DeleteObject($file);
            return true;
         } catch (\SpacesAPIException $e) {
           $error = $e->GetError();
           return $error;
         }
       
    }


    public static function getUrl(){
        $key = env('AWS_ACCESS_KEY_ID');
        $secret = env('AWS_SECRET_ACCESS_KEY');
        $space_name = env('AWS_BUCKET');
        $region = env('AWS_DEFAULT_REGION');
        $url = "https://$space_name.$region.digitaloceanspaces.com/";
        return  $url;
    }

    
    


    public static function removeNull($data){
        array_walk_recursive($data, function (&$item, $key) {
            $item = null === $item ? '' : $item;
        });
        return $data;
    }

    public static function  stripslashes_deep($value)
    {
        $value = is_array($value) ?
                    array_map('stripslashes_deep', $value) :
                    stripslashes($value);
    
        return $value;
    }


    public static function tax()
    {
        $AdminSetting = AdminSetting::first();
        //$AdminSetting = $AdminSetting->toArray();
     //   print_r($AdminSetting);
        $tax = !empty($AdminSetting['tax']) ?  $AdminSetting['tax']  : 0 ;
    
        return $tax;
    }
    
   
    public static function generateRandomString($id) {
        $characters = time().time().date('Ymdhsi').'0123456789ABCDE'.$id.'FGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 7; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
     
    }


    public static function generateRandomTransactionId() {
        $characters = time().time().date('Ymdhsi').'0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 8; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $TransactionData = Transaction::where('transactionId',$randomString)->get()->first();

        
        $str = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
        $randomString2 = substr(str_shuffle($str), 0, 8);
        $TransactionData2 = Transaction::where('transactionId',$randomString2)->get()->first();
        if(empty($TransactionData)){
            return 'TP-'.strtoupper($randomString);
        }else if(empty($TransactionData2)){
            return 'TP-'.strtoupper($randomString2);
        }else{
            return 'TP-'.strtoupper(substr(md5(microtime()), 0, 8)); 
        }  
       
     
    }



    public static function checkProductQuantity($productId,$eventId,$inputQuantity){
        $productData = Vendor_product::find($productId);
        $questionnairData = Questionnaire::where('id',$eventId)->get()->first();
        $AdminSetting = AdminSetting::first();
        $eventDay = !empty($AdminSetting->eventDay) ? $AdminSetting->eventDay : 0 ;
        if(!empty($questionnairData)){
            $eventDate = !empty($questionnairData->farEventDate) ? $questionnairData->farEventDate : '';
            $farEvent = !empty($questionnairData->farEvent) ? $questionnairData->farEvent : '';
            if($farEvent == 'exact'){
            $eventDateStart  = date('Y-m-d 00:00:01',strtotime($eventDate."-2 day"));
            $eventDateEnd  = date('Y-m-d 23:59:59',strtotime($eventDate."+2 day"));
            if(!empty($productData)){
                 $quantity = !empty($productData->quantity) ? $productData->quantity : 0 ;
                 $productName = !empty($productData->name) ? $productData->name :'' ;
                 if($inputQuantity <= $quantity){
                      $Transaction = Cart::join('questionnaires','questionnaires.id','carts.eventId')->where('productId',$productId)->whereBetween('questionnaires.farEventDate',[$eventDateStart,$eventDateEnd])->where('carts.status',2)->sum('carts.quantity');
                      
                        if(!empty($Transaction)){
                            if($Transaction <= $inputQuantity){
                                 
                                return json_encode(['data'=>'','status'=>true,'message'=>'success','token'=>'']); 
                            }else{
                                return json_encode(['data'=>'','status'=>false,'message'=> $productName.' not available for this event date','token'=>'']); 
                            }
                        }else{
                            
                            return json_encode(['data'=>'','status'=>true,'message'=>'success','token'=>'']); 
                        }
                   }else{
                    return json_encode(['data'=>'','status'=>false,'message'=>$productName . ' not available for this event date','token'=>'']); 
                 }
            }else {
                return json_encode(['data'=>'','status'=>false,'message'=>'Invalid Product Id','token'=>'']); 
            }
            }else {
                return json_encode(['data'=>'','status'=>false,'message'=>'Event date is not fixed.','token'=>'']); 
            }
        }else {
            return json_encode(['data'=>'','status'=>false,'message'=>'Invalid Event Id','token'=>'']); 
        }
    } 


    public static function Event($eventId,$sellerId){
       
             $eventData = Questionnaire::where('id',$eventId)->get()->first();
             $sellerIds = !empty($eventData->sellerId) ? $eventData->sellerId : '' ;
             if(!empty($sellerIds)){
                  $comma = ','; 
             }else{
                $comma = ''; 
             }
           
          if(!empty($eventData)){
            $eventGet = Questionnaire::where('id',$eventId)->whereRaw("find_in_set($sellerId,sellerId)")->get()->first();

            if(!empty($eventGet)){
                
            }else{
                $sellerIds .=  $comma.$sellerId;
                $eventData->sellerId = $sellerIds;
                $eventData->save();   
            }
           
           
                     
                      
                    
        }
          
    }


    public static function eventType($typeEvent){

      
        $eventPlanning = [
            'personal'=> 'Personal',
            'corporate'=> 'Corporate',
            'social'=> 'Social',
            'child-birthday'=> 'Child Birthday Party',
            'adult-birthday'=> 'Adult Birthday Party',
            'wedding'=> 'Wedding',
            'graduation'=> 'Graduation Party',
            'baby-shower'=> 'Baby Shower',
            'bridal-shower'=> 'Bridal Shower',
            'bachelor-party'=> ',Bachelor(ette) Party',
            'conference'=> 'Conference',
            'product-lunch'=> 'Product Launch',
            'holiday'=> 'Holiday Party',
            'dinner'=> 'Dinner Party',
            'panel-discussion'=> 'Panel Discussion',
            'fundraiser'=> 'Fundraiser',
            'meet-greet'=> 'Meet & Greet',
            'forum'=> 'Forum',
   
   
            'eventPlanner'=> 'I am looking to hire an event planner',
            'eventMyself'=> ' I am planning the event myself ',
            'virtual'=> 'Virtual Planner (help in select areas)',
            'in-person'=> 'In-person Planner (start to finish help)',
            'book'=> 'Book a pro',
            'rent'=> 'Rent from vendors',
         //   'travel-planner'=> 'I travel to the party planner',
          //  'party-planner-me'=> 'The party planner travels to me',
           // 'phone-online-planner'=> 'Phone or online (no in-person meeting) ', 
            'did-research'=> 'Did research',
            'talked-friend'=> 'Talked to friends',
            'got-other'=> 'Got other estimates',
            'just-guessing'=> 'Just guessing',
            'allocated'=> 'What I have allocated',
            'other-budget'=> '',
            'low-min'=> 'Budget to Mid-range',
            'mid-high'=> 'Mid-range to high-end',
            'high-luxury'=> 'High-end to Luxury',
            'creative'=> 'Creative',
            'logistics'=> 'Logistics',
            'marketing'=> 'Marketing',
            'management'=> 'Management',
            'partnerships'=> 'Partnerships',
            'yes'=> 'Yes, I do',
            'no'=> 'No, I need one',
            'virtual1 '=> 'Virtual',
            'outdoor'=> 'Outdoor venue ',
            'indoor'=> 'Indoor venue ',
            'classic'=> 'Classic',
            'romantic'=> 'Romantic',
            'modern'=> 'Modern',
            'natural'=> 'Natural',
            'glamorous'=> 'Glamorous',
            'bohemian'=> 'Bohemian',
            'urban'=> 'Urban',
            'notheme'=> 'No specific theme',
            'theme-other'=> '',
         ];
         if(array_key_exists($typeEvent, $eventPlanning)){
          return   $eventPlanning[$typeEvent];
         }
 
      }

}