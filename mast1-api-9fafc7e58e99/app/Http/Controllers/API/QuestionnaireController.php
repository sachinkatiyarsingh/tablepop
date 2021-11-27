<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;
use App\Countries;
use App\Customer;
use App\State;
use App\Questionnaire;
use App\Notification;
use App\Eventplanner;
use App\Project_image;
use App\PlannerPlan;
use App\Milestone;
use App\Seller;
use App\Image;
use App\Blog;
use App\Event;
use App\Review;
use App\MessageGroup;
use Helper;
use Socket;
use DB;
use App\Vendor_product;
use App\ShareEvent;
use App\Admin;
use Config;

class QuestionnaireController extends Controller
{
   public $success = 200;
   public $error = 401;
    
    public function questionnaire(Request $req){
      $token = '';
      $NewcustomeData = [];
         $insert = array();
         $validator = Validator::make($req->all(), [ 
            'addPhotos' => 'nullable',
            'addPhotos.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
           // 'mobile' => 'nullable|numeric|digits:10|unique:customers',
         ]);
         if ($validator->fails()){ 
               return response()->json(['data'=>$validator->errors(),'status'=>false,'message'=>$validator->errors()->all(),'token'=>''], $this->success);            
         }else{
         $superAdmin = Admin::where('type',1)->where('adminType',1)->get()->first();
         $adminId = !empty($superAdmin->id) ? $superAdmin->id : 0 ;


         $customerId = !empty($req->customerId) ? $req->customerId : '';
         $levelOfService = !empty($req->levelOfService) ? $req->levelOfService : '';
         $levelOfServicePlanningType = !empty($req->levelOfServicePlanningType) ? $req->levelOfServicePlanningType : '';
         $premiumEvent = !empty($req->premiumEvent) ? $req->premiumEvent : '';
         $confirmationPartyPlanner = !empty($req->confirmationPartyPlanner) ? $req->confirmationPartyPlanner : '';
         $name = !empty($req->name) ? $req->name : '';
         $email = !empty($req->email) ? $req->email : '';
         $mobile = !empty($req->mobile) ? $req->mobile : '';
         $eventName = !empty($req->eventName) ? $req->eventName : '';
         $eventPlanning = !empty($req->eventPlanning) ? $req->eventPlanning : '';
         $eventPlanningOther = !empty($req->eventPlanningOther) ? $req->eventPlanningOther : '';
         $typeEvent = !empty($req->typeEvent) ? $req->typeEvent : '';
         $guestExpect = !empty($req->guestExpect) ? $req->guestExpect : '';
         $farEventDate = !empty($req->farEventDate) ? $req->farEventDate : '';
         $farEvent = !empty($req->farEvent) ? $req->farEvent : '';
         $partyPlaningServiceCatgeory = !empty($req->partyPlaningServiceCatgeory) ? $req->partyPlaningServiceCatgeory : '';
         $partyPlaningServiceSubCatgeory = !empty($req->partyPlaningServiceSubCatgeory) ? $req->partyPlaningServiceSubCatgeory : '';
         $themeEvent = !empty($req->themeEvent) ? $req->themeEvent : '';
         $themeEventOther = !empty($req->themeEventOther) ? $req->themeEventOther : '';
         $vennu = !empty($req->vennu) ? $req->vennu : '';
         $vennuValue  = !empty($req->vennuValue) ? $req->vennuValue : '';
   
         $weddindIdeas = !empty($req->weddindIdeas) ? $req->weddindIdeas : '';
         $anytningPartyPlanner = !empty($req->anytningPartyPlanner) ? $req->anytningPartyPlanner : '';
         $latitude = !empty($req->latitude) ? $req->latitude : '';
         $longitude = !empty($req->longitude) ? $req->longitude : '';
         $free = !empty($req->free) ? $req->free : '';
         $hearAbout = !empty($req->hearAbout) ? $req->hearAbout : '';
         $hearAboutOther = !empty($req->hearAboutOther) ? $req->hearAboutOther : '';
   
          if($guestExpect != '500'){
			
            $guestExpects = explode('-',$guestExpect);
            if(!empty($guestExpects[1])){
               $guestExpectStart = $guestExpects[0];
               $guestExpectEnd = $guestExpects[1];
            }else{
               $guestExpectStart =  $guestExpect;
               $guestExpectEnd =  '';
            }
          }else{
            $guestExpectStart =  '500+';
            $guestExpectEnd =  '';
          }
          

          

         $partyPlaningServiceCatgeory1 = '';
         if(!empty($partyPlaningServiceCatgeory)){
            $partyPlaningServiceCatgeory = json_decode($partyPlaningServiceCatgeory,true);
            if(!empty($partyPlaningServiceCatgeory) && is_array($partyPlaningServiceCatgeory)){
               foreach($partyPlaningServiceCatgeory as $key => $value){
                  $bank[] = $value;
               }
               $partyPlaningServiceCatgeory1 = implode(',',$bank);
         }
         }

         $partyPlaningServiceSubCatgeory1 = '';
         if(!empty($partyPlaningServiceSubCatgeory)){
            $partyPlaningServiceSubCatgeory = json_decode($partyPlaningServiceSubCatgeory,true);
            if(!empty($partyPlaningServiceSubCatgeory) && is_array($partyPlaningServiceSubCatgeory)){
               foreach($partyPlaningServiceSubCatgeory as $key1 => $value1){
                  $bank1[] = $value1;
               }
               $partyPlaningServiceSubCatgeory1 = implode(',',$bank1);
         }
         }


       
           

         $data = new Questionnaire;
         $data->customerId = $customerId;
         $data->levelOfService =  $levelOfService;
         $data->levelOfServicePlanningType = $levelOfServicePlanningType;
    //     $data->partyPlanner = $req->partyPlanner;
       //  $data->budgetRangeStart = $req->budgetRangeStart;
       //  $data->budgetRangeEnd = $req->budgetRangeEnd;
       //  $data->helpedBudget = $req->helpedBudget;
        // $data->helpedBudgetOther = $helpedBudgetOther;
         $data->hearAbout = $hearAbout;
         $data->hearAboutOther = $hearAboutOther;
         $data->premiumEvent = $premiumEvent;
         $data->premiumEvent = $premiumEvent;
         $data->confirmationPartyPlanner = $confirmationPartyPlanner;
         $data->name = $name;
         $data->email = $email;
         $data->mobile = $mobile;
         $data->eventName = $eventName;
         $data->typeEvent = $typeEvent;
         $data->eventPlanning = $eventPlanning;
         $data->eventPlanningOther = $eventPlanningOther;
         $data->guestExpectStart = $guestExpectStart;
         $data->guestExpectEnd = $guestExpectEnd;
         $data->farEvent = $farEvent;
         $data->farEventDate = $farEventDate;
         $data->partyPlaningServiceCatgeory = $partyPlaningServiceCatgeory1;
         $data->partyPlaningServiceSubCatgeory = $partyPlaningServiceSubCatgeory1;
         $data->vennu = $vennu;
         $data->vennuValue = $vennuValue;
         $data->themeEvent = $themeEvent;
         $data->themeEventOther = $themeEventOther;
       //  $data->addPhotos = '';
         $data->weddindIdeas = $weddindIdeas;
         $data->anytningPartyPlanner = $anytningPartyPlanner;
         $data->latitude = $latitude;
         $data->longitude = $longitude;
         $data->tokenId = '';
         $customeData = Customer::where('id',$data->customerId)->get()->first();
         if(!empty($customeData)){
         $data->save();

               $questionnaireupdate = Questionnaire::find($data->id);
               $questionnaireupdate->tokenId = 'EVT-'.Helper::generateRandomString($data->id);
               $questionnaireupdate->save();
               $notification = new Notification;
               $notification->notification = 'New event '.$data->eventName.' registered.';
               $notification->type = 'admin';
               $notification->urlType = 'event';
               $notification->questionnaireId = $data->id;
               $notification->fromId = $customerId;
               $notification->toId = $adminId;
               $notification->sendType = 'customer';
               $notification->save();
               Socket::notification($userId='',$userType='admin');
               if($req->hasfile('addPhotos')){

               foreach($req->file('addPhotos') as $image){
                  
                     $name= date('YmdHis') . "." . $image->getClientOriginalName();
                     $name = str_replace(" ", "-", trim($name));
                     $folder = Helper::imageUpload($name, $image,$folder="customer/customer".$customerId.""); 
                     $images = new Image;
                     $images->image = $name;
                     $images->questionnaireId = $data->id;
                     $images->save();
               }
            }
               

            if($free == 'free'){
               $sellerData = Seller::inRandomOrder()->where('type',2)->where('free',1)->get()->first();
               if(!empty($sellerData)){
                  $freeSellerId = !empty($sellerData->id) ? $sellerData->id : '' ;
                
                  $MessageGroupData = MessageGroup::where('questionnaireId',$data->id)->get()->first();
                  if(empty($MessageGroupData)){
                     $MessageGroup = new MessageGroup;
                     $MessageGroup->customerId = $customerId;
                     $MessageGroup->sellerId = $freeSellerId;
                     $MessageGroup->adminId = $superAdmin->id;
                     $MessageGroup->questionnaireId = $data->id;
                     $MessageGroup->type = 0;
                     $MessageGroup->save();
                  }
                  Helper::Event($data->id, $freeSellerId);
                  $questionnaireupdate = Questionnaire::find($data->id);
               //   $questionnaireupdate->tokenId = 'EVT-'.Helper::generateRandomString($data->id);
                  $questionnaireupdate->sataus = 2;
                  $questionnaireupdate->save();


                  $notification = new Notification;
                  $notification->notification = 'suggestions to free planners for your event.';
                  $notification->questionnaireId = $data->id;
                  $notification->customerId = $customerId;
                  $notification->type = 'customer';
                  $notification->urlType = 'eventPlanner';
                  $notification->fromId = $adminId;
                  $notification->toId = $customerId;
                  $notification->sendType = 'admin';
                  $notification->save();
                  Socket::notification($userId=$customerId,$userType='customer');
               }
               
            }
       
            $customerName = !empty($customeData->name) ? $customeData->name : '' ;
            $customerSurName = !empty($customeData->surname) ? $customeData->surname : '' ;
            $customerName = $customerName.' '.$customerSurName;
            $adminData  = Admin::where('type',1)->where('adminType',1)->get()->first();
            $adminMobile = !empty($adminData->mobile) ? $adminData->mobile : '' ;
            if(!empty($adminMobile)){
               $mobile = $adminMobile;
               $msg = str_replace("{customer}",$customerName,Config::get('msg.newEvent'));
               Helper::sendMessage($msg,$mobile);
            }
            $subject = "I posted an event, now what? ($data->eventName/$questionnaireupdate->tokenId)"; 
            $email_data = ['email' =>$customeData['email'],'name'=>$customerName,'eventName'=>$data->eventName,'user'=>'customer','subject' => $subject];  
            Helper::send_mail('emailTemplate.event',$email_data);
            return response()->json(['data'=>'','status'=>true,'isCustomerExist'=>true,'message'=>'Questionnaire Successfully Submited','token'=>''], $this->success);      
         }else{
            $customer_data = Customer::where('email',$email)->get()->first();
            if(!empty($customer_data)){
               return response()->json(['data'=>'','status'=>false,'isCustomerExist'=>true,'message'=>'Please login with your account','token'=>''], $this->success);      
            }else{
               return response()->json(['data'=>'','status'=>false,'isCustomerExist'=>false,'message'=>'Please create an account','token'=>''], $this->success);    
            }
                
         } 
         /* $customer_data = Customer::where('email',$email)->get()->first();
         if(!empty($mobile)){
            $customermobiledata = Customer::where('mobile',$mobile)->get()->first();
         }else{
            $customermobiledata = [];
         }
        
         $customerIdedata = Customer::where('id',$customerId)->get()->first();
         if(empty($customerIdedata)){
         if(!empty($customer_data)){
            return response()->json(['data'=>'','status'=>true,'isCustomerExist'=>true,'message'=>'Customer Already Registered','token'=>''], $this->success);      
         }else{
           if(empty($customermobiledata)){
            $token = Str::random(40);
            $invitationCode =  strtoupper(substr(md5(time()), 0, 8)); 
            $customer = new Customer;
            $customer->name = $name;
            $customer->surname = '';
            $customer->email = $email;
            $customer->mobile =  $mobile;
            $customer->country_id = 0;
            $customer->state_id = 0;
            $customer->notification = '';
            $customer->password = '';
            $customer->token = $token;
            $customer->invitationCode = $invitationCode;
            $customer->status = 1;
            $customer->save();
            $customerId = $customer->id;
            $data->customerId = $customerId;
            $data->save();

         

            if(!empty($mobile)){
               $mobile = $mobile;
               $msg = Config::get('msg.welcome');  
               Helper::sendMessage($msg,$mobile);
            }

           $ShareEventData = ShareEvent::where('email',$email)->get()->first();
           $ShareEventCustomerId = !empty($ShareEventData->customerId) ? $ShareEventData->customerId : '' ;
           if(empty($ShareEventCustomerId) && !empty($ShareEventData)){
               $ShareEventData->customerId = $customerId;
               $ShareEventData->save();
           }
          
        
            if($req->hasfile('addPhotos'))
            {
               foreach($req->file('addPhotos') as $image)
               {
                 
                  $name= $image->getClientOriginalName();
                  $name = str_replace( " ", "-", trim($name) );
                  $folder = Helper::imageUpload($name, $image,$folder="customer/customer".$customerId.""); 
                  $images = new Image;
                  $images->image = $name;
                  $images->questionnaireId = $data->id;
                  $images->save();
               }
            }
            
           $notification = new Notification;
            $notification->notification = 'New event '.$data->eventName.' registered.';
            $notification->type = 'admin';
            $notification->urlType = 'event';
            $notification->questionnaireId = $data->id;
            $notification->save();

            $customeData = Customer::where('id',$customerId)->get()->first();
            $Token =  $customeData->createToken('iaastha-api')->accessToken;
            $url = env('CUSTOMER_URL').'verify/'.$token;
            $subject = " Set Password"; 
            $email_data = ['name' => $customer['name'],'email' => $customer['email'],'user'=>'customer','show'=>1,'url'=>$url,'subject' => $subject];    
            Helper::send_mail('emailTemplate.welcome',$email_data);
            $NewcustomeData = Helper::removeNull($customeData);
           
           }else{
            return response()->json(['data'=>'','status'=>false,'isCustomerExist'=>true,'message'=>'The mobile has already been taken.','token'=>''], $this->success);       
           }
         }
       }else{
         $data->save();
       $questionnaireupdate = Questionnaire::find($data->id);
         $questionnaireupdate->tokenId = 'EVT-'.Helper::generateRandomString($data->id);
         $questionnaireupdate->save(); 
     
           
       
    
       
       } 
     
       $questionnaireupdate = Questionnaire::find($data->id);
       $questionnaireupdate->tokenId = 'EVT-'.Helper::generateRandomString($data->id);
       $questionnaireupdate->save();


    
       $customeData = Customer::where('id',$data->customerId)->get()->first();
       $customerName = !empty($customeData->name) ? $customeData->name : '' ;
       $customerSurName = !empty($customeData->surname) ? $customeData->surname : '' ;
       $customerName = $customerName.' '.$customerSurName;
       $adminData  = Admin::where('type',1)->where('adminType',1)->get()->first();
       $adminMobile = !empty($adminData->mobile) ? $adminData->mobile : '' ;
      if(!empty($adminMobile)){
         $mobile = $adminMobile;
         $msg = str_replace("{customer}",$customerName,Config::get('msg.newEvent'));
         Helper::sendMessage($msg,$mobile);
      }
     
       if(!empty($NewcustomeData) && !empty($Token)){
       return response()->json(['data'=> $NewcustomeData,'status'=>true,'isCustomerExist'=>true,'message'=>'Questionnaire Successfully Submited','token'=>$Token], $this->success);      
      }else{
         return response()->json(['data'=>'','status'=>true,'isCustomerExist'=>true,'message'=>'Questionnaire Successfully Submited','token'=>''], $this->success);      
      } */
   }
    }

   public function questionnaireList(Request $req){
    
       $token = $req->bearerToken();
       $sort_by = $req->sort_by;
       $status =  $req->type;
       $pageNo =  $req->pageNo;
       $customeId =  Helper::encode_token($token);
       $customeData = Customer::find($customeId);
        if(!empty($customeData)){
               $invitationId = !empty($customeData->invitationId) ? $customeData->invitationId : 0 ;
               $invitationEventId = ShareEvent::select(DB::raw('group_concat(eventId) as eventId'))->where('customerId',$customeId)->get()->first();
               $invitationEventId  = $invitationEventId['eventId'];
                if(!empty($invitationEventId)){
                  $invitationEventId = explode(',',$invitationEventId);
                }
               $limit = !empty(env('PR_PAGE_DATA')) ? env('PR_PAGE_DATA') : 10 ;
               $start = !empty($pageNo) ? ($pageNo - 1)*$limit : 0 ;
               $data = Questionnaire::select('questionnaires.*','customers.name as customers_name','customers.surname as customers_surname','customers.email as customers_email')
                                       ->leftjoin('customers','questionnaires.customerId','customers.id')
                                       //->leftjoin('themes','themes.id','questionnaires.themeEvent')
                                       ->where('questionnaires.customerId',$customeId);
               if(!empty($invitationEventId)){
                   $data = $data->orwhereIn('questionnaires.id',$invitationEventId); 
               }

              
               if($status == 1){
                  $count = $data->where('questionnaires.status',1)->count();
                  $statusData = $data->where('questionnaires.status',1)->offset($start)->limit($limit); 
                
               }else if($status == 2){
                  $count = $data->where('questionnaires.status',2)->count();
                  $statusData = $data->where('questionnaires.status',2)->offset($start)->limit($limit); 
                 
               }else if($status == 'pending'){
                  $count = $data->where('questionnaires.status',0)->count();
                  $statusData = $data->where('questionnaires.status',0)->offset($start)->limit($limit); 
                 
                }else if($status == 'all'){
                  $count = $data->count();
                  $statusData = $data->offset($start)->limit($limit); 
                 
               }else{
                  $count = $data->count();
                  $statusData = $data->offset($start)->limit($limit); 
                 
               }                       
                   
               if($sort_by == 'a-z') {
                     $filtterdata = $statusData->orderBy('questionnaires.eventName', 'asc')->get()->toArray();
               }else if($sort_by == 'recent'){
                     $filtterdata = $statusData->orderBy('questionnaires.id', 'DESC')->get()->toArray();
               }else{
                     $filtterdata = $statusData->orderBy('questionnaires.id', 'DESC')->get()->toArray();
               }
                
               if(!empty($data)){
                  $Alldata['eventList'] = $filtterdata;
                  $Alldata['totalPage'] = ceil($count/$limit);
                  $Alldata = Helper::removeNull($Alldata);
                  return response()->json(['data'=>$Alldata,'status'=>true,'message'=>'Data','token'=>''], $this->success);
               
               }else{
                return response()->json(['data'=>'','status'=>true,'message'=>'data empty','token'=>''], $this->success);
              }
         }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
         }
   }

    public function questionnaireDetails(Request $req){
       $imagePath = env('IMAGE_SHOW_PHTH');
       $imageUrl = Helper::getUrl();
       $questionnaireData = [];
        $image = array();
      
            $id = $req->questionnaireId;
         
            $questionnaireDataAll = Questionnaire::where('id',$id)->get()->first();
            if(!empty($questionnaireDataAll)){
            $customerId = !empty($questionnaireDataAll->customerId) ? $questionnaireDataAll->customerId: '' ;
            $guestExpectStart = !empty($questionnaireDataAll->guestExpectStart) ? $questionnaireDataAll->guestExpectStart : '' ;
            $partyPlaningServiceCatgeory = !empty($questionnaireDataAll->partyPlaningServiceCatgeory) ? $questionnaireDataAll->partyPlaningServiceCatgeory : '' ;
            $partyPlaningServiceSubCatgeory = !empty($questionnaireDataAll->partyPlaningServiceSubCatgeory) ? $questionnaireDataAll->partyPlaningServiceSubCatgeory : '' ;
            
            if($guestExpectStart == '500+'){
               $questionnaireDataAll['guestExpectStart'] = 500;
            }else{
               $questionnaireDataAll['guestExpectStart'] =  $guestExpectStart;
            }
            if(!empty($partyPlaningServiceCatgeory)){
               $questionnaireDataAll['partyPlaningServiceCatgeory'] =  array_map('intval', explode(',', $partyPlaningServiceCatgeory ));
            }

            if(!empty($partyPlaningServiceSubCatgeory)){
               $questionnaireDataAll['partyPlaningServiceSubCatgeory'] =  array_map('intval', explode(',', $partyPlaningServiceSubCatgeory ));
            }
            $image =  Image::select('id',DB::raw('(CASE WHEN image = "" THEN "" ELSE CONCAT("'.$imageUrl .'","customer/customer","'.$customerId.'","/", image) END) AS image'))->where('questionnaireId',$id)->get();
            $questionnaireData = $questionnaireDataAll;
           $questionnaireData['addPhotos'] =  $image;
            if(!empty($questionnaireDataAll)){
               $questionnaireDataAll = Helper::removeNull($questionnaireDataAll);
                 return response()->json(['data'=>$questionnaireData,'status'=>true,'message'=>'Data','token'=>''], $this->success);
               }else{
                return response()->json(['data'=>'','status'=>true,'message'=>'data empty','token'=>''], $this->success);
            }
        
         
      }else{
       return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Id','token'=>''], $this->error); 
     }
    }



    public function questionnaireUpdate(Request $req){ 
            $token = '';

            $insert = array();
            $response = array();
            $id = $req->questionnaireId;
            $questionnaireData = Questionnaire::find($id);
               if(!empty($questionnaireData)){
                  $customerId = !empty($questionnaireData->customerId)  ? $questionnaireData->customerId : '' ;
  
               $validator = Validator::make($req->all(), [ 
               'addPhotos' => 'nullable',
               'addPhotos.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
              
            ]);
            if ($validator->fails()){ 
                  return response()->json(['data'=>'','status'=>false,'message'=>$validator->errors()->all(),'token'=>''], $this->success);            
            }else{
            $data =  Questionnaire::find($id);
            if($req->levelOfService){
               $data->levelOfService = $req->levelOfService;
            }

            if($req->levelOfServicePlanningType){
               $data->levelOfServicePlanningType = $req->levelOfServicePlanningType;
            }
          /*   if($req->partyPlanner){
               $data->partyPlanner = $req->partyPlanner;
            } */
         /*    if($req->budgetRangeStart){
               $data->budgetRangeStart = $req->budgetRangeStart;
            } */
          /*   if($req->budgetRangeEnd){
               $data->budgetRangeEnd = $req->budgetRangeEnd;
            } */
         /*    if($req->helpedBudget){
               $data->helpedBudget = $req->helpedBudget;
            } */

         /*    if($req->helpedBudget){
               $data->helpedBudget = $req->helpedBudget;
            } */
           /*  if($req->helpedBudgetOther){
               $data->helpedBudgetOther = $req->helpedBudgetOther;
            } */
            if($req->hearAbout){
               $data->hearAbout = $req->hearAbout;
            }
            if($req->hearAboutOther){
               $data->hearAboutOther = $req->hearAboutOther;
            }
            if($req->premiumEvent){
               $data->premiumEvent = $req->premiumEvent;
            }
            if($req->confirmationPartyPlanner){
               $data->confirmationPartyPlanner = $req->confirmationPartyPlanner;
            }
            if($req->name){
               $data->name = $req->name;
            }
            if($req->email){
               $data->email = $req->email;
            }
            if($req->mobile){
               $data->mobile = $req->mobile;
            }
            if($req->eventName){
               $data->eventName = $req->eventName;
            }
            if($req->typeEvent){
               $data->typeEvent = $req->typeEvent;
            }
            if($req->eventPlanning){
               $data->eventPlanning = $req->eventPlanning;
            }
            if($req->eventPlanningOther){
               $data->eventPlanningOther = $req->eventPlanningOther;
            }
           $guestExpect = $req->guestExpect;
            if($req->guestExpect){
            if($guestExpect != '500'){
               $josn = json_decode($guestExpect,true);
               $guestExpects = explode('-',$req->guestExpect);
               if(!empty($guestExpects[1])){
                  $guestExpectStart = $guestExpects[0];
                  $guestExpectEnd = $guestExpects[1];
               }else{
                  $guestExpectStart =  $guestExpect;
                  $guestExpectEnd =  '';
               }
             }else{
               $guestExpectStart =  '500+';
               $guestExpectEnd =  '';
               
             }
             $data->guestExpectStart = $guestExpectStart;
               $data->guestExpectEnd = $guestExpectEnd;
            }
           if($req->farEvent){
               $data->farEvent = $req->farEvent;
            } 
            if($req->farEventDate){
               $data->farEventDate = $req->farEventDate;
            }
           
            if($req->partyPlaningServiceCatgeory){
                $partyPlaningServiceCatgeory1 = '';
                  $partyPlaningServiceCatgeory = json_decode($req->partyPlaningServiceCatgeory,true);
                  if(!empty($partyPlaningServiceCatgeory) && is_array($partyPlaningServiceCatgeory)){
                     foreach($partyPlaningServiceCatgeory as $key => $value){
                        $bank[] = $value;
                     }
                     $partyPlaningServiceCatgeory1 = implode(',',$bank);
                  }
                 
                  $data->partyPlaningServiceCatgeory = $partyPlaningServiceCatgeory1;
               
            }
            if($req->partyPlaningServiceSubCatgeory){
               $partyPlaningServiceSubCatgeory1 = '';
               $partyPlaningServiceSubCatgeory = json_decode($req->partyPlaningServiceSubCatgeory,true);
               if(!empty($partyPlaningServiceSubCatgeory) && is_array($partyPlaningServiceSubCatgeory)){
                  foreach($partyPlaningServiceSubCatgeory as $key => $value1){
                     $bank1[] = $value1;
                  }
                  $partyPlaningServiceSubCatgeory1 = implode(',',$bank1);
               }
              
               $data->partyPlaningServiceSubCatgeory = $partyPlaningServiceSubCatgeory1;
           
            }
            if($req->vennuValue){
               $data->vennuValue = $req->vennuValue;
            }

            if($req->vennu){
               $data->vennu = $req->vennu;
            }
            if($req->themeEvent){
               $data->themeEvent = $req->themeEvent;
            }

            if($req->themeEventOther){
               $data->themeEventOther = $req->themeEventOther;
            }
            if($req->weddindIdeas){
               $data->weddindIdeas = $req->weddindIdeas;
            }
            if($req->anytningPartyPlanner){
               $data->anytningPartyPlanner = $req->anytningPartyPlanner;
            }
            if($req->latitude){
               $data->latitude = $req->latitude;
            }
            if($req->longitude){
               $data->longitude = $req->longitude;
            }
            
            if($req->hasfile('addPhotos'))
            {
     
               foreach($req->file('addPhotos') as $image)
               {
                   $name= date('YmdHis').$image->getClientOriginalName();
                   $name = str_replace( " ", "-", trim($name) );
                   $folder = Helper::imageUpload($name, $image,$folder="customer/customer".$customerId."");
                   $images = new Image;
                   $images->image = $name;
                   $images->questionnaireId = $id;
                   $images->save();
               }
            }

            $data->save();
         return response()->json(['data'=>'','status'=>true,'message'=>'Questionnaire update Successfully','token'=>''], $this->success); 
         }
      }else{
         return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Questionnaire Id','token'=>''], $this->success); 
       }
       
    }

   public function imageDelete(Request $req){
      $id = $req->questionnaireId;
      $imageId = $req->imageId;
      $questionnaireData = Questionnaire::find($id);
      if(!empty($questionnaireData)){
            $imageData = Image::where('id',$imageId)->where('questionnaireId',$id)->get()->first();
            if(!empty($imageData)){
                $customerId = !empty($questionnaireData->customerId) ? $questionnaireData->customerId : '' ;
                $image = !empty($imageData->image) ? $imageData->image : '' ;
                $path  = "customer/customer".$customerId.'/'.$image;
                Helper::deleteImage($path);
                $imageData->delete();
                return response()->json(['data'=>'','status'=>true,'message'=>'Image Delete Successfully','token'=>''], $this->success); 
            }else{
               return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Image Id','token'=>''], $this->success); 
            }
      }else{
         return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Questionnaire Id','token'=>''], $this->success); 
      }
   }





  public function questionnairePlanner(Request $request){
          $imageUrl = Helper::getUrl();
         $questionnaireId = $request->questionnaireId;
         $questionnaireData = Questionnaire::find($questionnaireId); 
         $expertise = $request->expertise;
         $experience = $request->experience;
         $skill = $request->skill;
         $location = $request->location;
         if(!empty($questionnaireData)){
               $eventplanner = Eventplanner::select('sellers.id','sellers.firstName','sellers.profileName','sellers.lastName','sellers.email','sellers.mobileNo','sellers.countryId','sellers.gender','sellers.dob','sellers.location','sellers.addressTwo','sellers.latitude','sellers.longitude','sellers.userName','sellers.type','sellers.created_at',DB::raw('(CASE WHEN profileImage = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",sellers.id,"/", profileImage) END) AS profileImage'),'pr.ratting_cnt',DB::raw('round(pr.seller_rate/pr.ratting_cnt) as rating'))
                                    ->join('sellers','eventplanners.plannerId','sellers.id')
                                    ->leftjoin('planners','sellers.id','planners.plannerId')
                                    ->leftjoin(DB::raw('(select pr1.sellerId, sum(pr1.rating) as seller_rate, count(1) as ratting_cnt from reviews pr1 group by pr1.sellerId) as pr'), function($join){
                                       $join->on('pr.sellerId', '=', 'sellers.id');
                                       })
                                    ->where('eventplanners.questionnaireId',$questionnaireId)
                                    ->where('eventplanners.status',0);
               if(!empty($expertise)){
                  $plannerData = $eventplanner->whereRaw("find_in_set($expertise,planners.experiencePlanning)")->get()->toArray();
            
               }else if(!empty($location)){
                  $plannerData = $eventplanner->where('sellers.location', 'LIKE', '%' . $location . '%')->get()->toArray();
               }else if(!empty($experience)){
                  $plannerData = $eventplanner->where('planners.workingindustry',$experience)->get()->toArray();
               }else if(!empty($skill)){
                  $plannerData = $eventplanner->where('planners.personalityPlanners', 'LIKE', '%' . $skill . '%')->get()->toArray();
               }else{
                  $plannerData = $eventplanner->get()->toArray();
               } 
              
               if(!empty($plannerData)){
                   $plannerData = Helper::removeNull($plannerData);
                   return response()->json(['data'=>$plannerData,'status'=>true,'message'=>'Data','token'=>''], $this->success);
              }else{
               return response()->json(['data'=>'','status'=>true,'message'=>'Planner Not Available','token'=>''], $this->success);
              }
          }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Questionnaire Id','token'=>''], $this->success); 
         }
   }


   public function questionnairePlannerDetails(Request $request){
         $sellerId = $request->sellerId;
         $checkSeller = Seller::find($sellerId);
         $imageUrl = Helper::getUrl();
         if(!empty($checkSeller)){
    
             $sellerData  = Seller::select('sellers.*', DB::raw('(CASE WHEN sellers.profileImage = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",sellers.id,"/", sellers.profileImage) END) AS profileImage'),'countries.name as country','planners.experiencePlanning','planners.otherExperiencePlanning')
                                    ->leftjoin('countries','countries.id','sellers.countryId')
                                    ->leftjoin('planners','planners.plannerId','sellers.id')
                             //      ->leftjoin('events','events.id','planners.experiencePlanning')
                                  //  ->leftjoin('themes as th','th.id','questionnaires.themeEvent')
                                    ->where('sellers.id',$sellerId)->get()->first(); 
           
           
            $experiencePlanning = $sellerData['experiencePlanning'];
            $experiencePlanning = explode(',',$experiencePlanning);
            $event = Event::select(DB::raw('group_concat(name) as names'))->whereIn('id',$experiencePlanning)->get()->first();
            $sellerData['experiencePlanning'] =  $event['names'] ;



            $sellerData['projectImage'] = Project_image::select("id","event","numberAttendees","locationEvent",DB::raw('(CASE WHEN image = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor","'.$sellerId.'","/", image) END) AS image'))->where('sellerId',$sellerId)->get()->toArray();
            $sellerData['plans']  =   PlannerPlan::select('*',DB::raw('(CASE WHEN image = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",sellerId,"/",image) END) AS image'))->where('sellerId',$sellerId)->get()->toArray();
            $sellerData['blogs'] = Blog::select('blogs.*',DB::raw('(CASE WHEN blog_images.file = "" THEN "" ELSE CONCAT("'.$imageUrl .'","admin/blogs/", blog_images.file) END) AS file'))
                                    ->leftjoin('blog_images','blog_images.blogId','blogs.id')
                                    ->where('blog_images.type','image')
                                    ->whereRaw("find_in_set($sellerId,blogs.sellerId)")
                                   
                                    ->groupBy('blog_images.blogId')
                                    ->get()->toArray();
         $rating = Review::where('sellerId',$sellerId)->avg('rating');
         $review = Review::select('reviews.*',DB::raw('CONCAT(c.name," ",c.surname) as name'),DB::raw('(CASE WHEN c.profileImage = "" THEN "" ELSE CONCAT("'.$imageUrl .'","customer/customer",c.id,"/", c.profileImage) END) AS profileImage'))
                                    ->join('customers as c','c.id','reviews.customerId')
                                   ->where('sellerId',$sellerId)->get()->toArray();
         $sellerData['rating'] = round($rating);
         $sellerData['review'] = $review;
         if(!empty($rating)){
            $reviewAlready = true;
         }else{
            $reviewAlready = false;
         }
        
            $sellerData = Helper::removeNull($sellerData);
            return response()->json(['data'=>$sellerData,'status'=>true,'reviewAlready'=>$reviewAlready,'message'=>'Data','token'=>''], $this->success);
         }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Id','token'=>''], $this->success); 
         }
   }


   public function eventDetails(Request $req){
      $id = $req->eventId;
      $productId = $req->productId;
      $quantity  = !empty($req->quantity) ? $req->quantity : 0;
      $amount = 0;
      $questionnaireData = Questionnaire::find($id);
      if(!empty($questionnaireData)){
            $productData =   Vendor_product::find($productId);
            if(!empty($productData)){
               $regularPrice = !empty($productData->regularPrice) ? $productData->regularPrice : 0;
               $salePrice = !empty($productData->salePrice) ? $productData->salePrice : $regularPrice;
               $amount = $salePrice*$quantity;
            }
            $questionnaireData['totalAmount'] = $amount;
            return response()->json(['data'=>$questionnaireData,'status'=>true,'message'=>'','token'=>''], $this->success); 
          
      }else{
         return response()->json(['data'=>'','status'=>false,'message'=>'Invalid  Id','token'=>''], $this->success); 
      }
   }

   public function interactionDate(Request $req){
      $token = $req->bearerToken();
      $sellerId =  Helper::encode_token($token);
      $sellerData =  Seller::where('id',$sellerId)->where('type',2)->get()->first();
      if(!empty($sellerData)){
         $id = $req->eventId;
         $date = $req->date;
         $questionnaireData = Questionnaire::find($id);
            if(!empty($questionnaireData)){
                  $questionnaireData->interactionDate =  $date ;
                  $questionnaireData->save();
                  return response()->json(['data'=>'','status'=>true,'message'=>'success','token'=>''], $this->success); 
            }else{
               return response()->json(['data'=>'','status'=>false,'message'=>'Invalid  Id','token'=>''], $this->success); 
            }                      
      }else{
          return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
      }
  }


     public function plannarCompleteEvent(Request $req){
      $token = $req->bearerToken();
      $sellerId =  Helper::encode_token($token);
      $sellerData =  Seller::where('id',$sellerId)->where('type',2)->get()->first();
      if(!empty($sellerData)){
         $eventId = $req->eventId;
         $status = $req->status;
         $questionnaireData = Questionnaire::find($eventId);
            if(!empty($questionnaireData)){
                   $eventName = !empty($questionnaireData->eventName) ? $questionnaireData->eventName : '' ;
                   $tokenId = !empty($questionnaireData->tokenId) ? $questionnaireData->tokenId : '' ;
                   $customeId = !empty($questionnaireData->customerId) ? $questionnaireData->customerId : '' ;
                  
                   $customeData = Customer::find($customeId);
                 
                      $customeMobile = !empty($customeData->mobile) ? $customeData->mobile : '' ;
                   $customerEmail = !empty($customeData->email) ? $customeData->email : '' ;
                   $customerName = !empty($customeData->name) ? $customeData->name : '' ;
                   $customerSurname = !empty($customeData->surname) ? $customeData->surname : '' ;
                 
                   $Milestone = Milestone::where('questionnaireId',$eventId)->where('sellerId',$sellerId)->get()->first();
                   if(!empty($Milestone)){
                       $Milestone = Milestone::where('questionnaireId',$eventId)->where('sellerId',$sellerId)->where('status','!=',3)->get()->first();
                          
                       if(empty($Milestone)){
                           if(!empty($status)){

                              $questionnaireData->status = 1;
                              $questionnaireData->save();
                             
                           
                             if(!empty($customeMobile)){
                                $mobile = $customeMobile;
                                $msg = Config::get('msg.eventCompleted');  
                                Helper::sendMessage($msg,$mobile);

                             }

                             $subject = " Yippee! Your event is marked completed! ($eventName/$tokenId)"; 
                           $email_data = ['email' =>$customerEmail,'name'=>$customerName.' '. $customerSurname,'user'=>'customer','subject' => $subject];  
                           Helper::send_mail('emailTemplate.eventCompleted',$email_data);
                              return response()->json(['data'=>'','status'=>true,'message'=>'success','token'=>''], $this->success); 
                      
                           }else{
                              return response()->json(['data'=>'','status'=>false,'message'=>'','token'=>''], $this->success); 
                           }
                       }else{
                         return response()->json(['data'=>'','status'=>false,'message'=>'Payment Due','token'=>''], $this->success); 
                       }
                   
                     }else{
                     return response()->json(['data'=>'','status'=>false,'message'=>'Create A Milestone','token'=>''], $this->success); 
                   }
                  
            }else{
               return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Event Id','token'=>''], $this->success); 
            }                      
      }else{
          return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
      }
  }

}
