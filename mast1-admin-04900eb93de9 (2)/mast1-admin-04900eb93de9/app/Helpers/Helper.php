<?php
namespace App\Helpers;
use Illuminate\Support\Facades\Auth; 
use PeterPetrus\Auth\PassportToken;
use DB;
use Mail;
use Session;
use App\Admin;
use App\Event;
use SpacesConnect;
use Twilio\Rest\Client;

class Helper{
    
   public static function sendMessage($msg ,$no){
      $sid = env('TWILIO_ACCOUNT_SID');
      $token = env('TWILIO_AUTH_TOKEN');
      $twilio = new Client($sid, $token);
      $message = $twilio->messages->create($no,["body" => $msg,"from" => env('TWILIO_NUMBER')]); 
      return true;
     
   }



    public static function send_mail($tmplale,$data) {
   
      Mail::send($tmplale, $data, function($message) use ($data) {
         $message->to($data['email'])
              ->subject($data['subject']);
         $message->from(getenv('MAIL_FROM_ADDRESS'));
      });
      
     }

     public static function sendMailCC($tmplale,$data) {
     
      Mail::send($tmplale, $data, function($message) use ($data) {
         $message->to($data['email'], $to_name=$data['subject'])
              ->subject($data['subject']);
         $message->cc($data['emailTwo'],$data['subject']);
         $message->from(env('MAIL_FROM_ADDRESS'),$data['subject']);
      });
    }

    public static function sendMailAttachData($tmplale,$data,$pdf) {
      Mail::send($tmplale,$data, function($message)use($data,$pdf) {
          $message->to($data["email"])->subject($data["subject"])
          ->attachData($pdf->output(), $data["pdfName"]);
          });
     
  }


     public static function permission(){
         $json = array();
         $loginId = Session::get('id');
         $adminData = Admin::find($loginId);
         $permission = !empty($adminData['permission']) ? $adminData['permission'] : '';
         $type =  !empty($adminData['type']) ? $adminData['type'] : '';;
         if($type == 2){
         $json = json_decode($permission,true);
         if(!empty($json)){
         
            return $json;
       }
      }
      }


      public static function imageUpload($image,$file,$folder){
         $key = env('AWS_ACCESS_KEY_ID');
         $secret = env('AWS_SECRET_ACCESS_KEY');
         $space_name = env('AWS_BUCKET');
         $region = env('AWS_DEFAULT_REGION');
         $space = new SpacesConnect($key, $secret, $space_name, $region);
         $path_to_file = $image;
         $path = $folder."/".$path_to_file;
         try {
           
             $space->UploadFile($file, "public",$path);  
      
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
         $space = new SpacesConnect($key, $secret, $space_name, $region);
         $link = $space->GetObject($file);
         try {
           
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
         $space = new SpacesConnect($key, $secret, $space_name, $region);
        
         try {
           
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
         'in-person'=> 'In-person Planning',
         'online'=> 'Online Planning',
         'full-service'=> 'Full service planning (start to finish help)',
         'partial-service'=> 'Partial planning (help in select areas)',
         'travel-planner'=> 'I travel to the party planner',
         'party-planner-me'=> 'The party planner travels to me',
         'phone-online-planner'=> 'Phone or online (no in-person meeting) ', 
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
         'outdoor-venue'=> 'Outdoor vennu',
         'indoor'=> 'Indoor vennu',
         'classic'=> 'Classic',
         'romantic'=> 'Romantic',
         'modern'=> 'Modern',
         'natural'=> 'Natural',
         'glamorous'=> 'Glamorous',
         'bohemian'=> 'Bohemian',
         'urban'=> 'Urban',
         'theme-other'=> '',
        ];
        if(array_key_exists($typeEvent, $eventPlanning)){
         return   $eventPlanning[$typeEvent];
        }

     }



     public static function fileType($file){
		$image_type = array('gif','jpg','jpeg','png');
		$video_type = array('avi','flv','wmv','mp3','mp4');
		$src_file_name = $file;
		$ext = strtolower(pathinfo($src_file_name, PATHINFO_EXTENSION));
		if(in_array($ext, $image_type)){
			return 'image';
		}else if(in_array($ext, $video_type)){
	       return 'video';
		 }else{
			return false;
		 }
    }
    

   public static function expertise($experiencePlanning){

    
      $experiencePlanning = explode(',',$experiencePlanning);
      $event = Event::select(DB::raw('group_concat(name) as names'))->whereIn('id',$experiencePlanning)->get()->first();
      if(!empty($event['names'])){
         return $experiencePlanning =  $event['names'] ;
      }
     
   }

}