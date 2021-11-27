<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Validator;
use Hash;
use DB;
use Socket;
use Helper;
use Config;
use App\Cart;
use App\Offer;
use App\Seller;
use App\Planner;
use App\Vendor;
use App\Message;
use App\Transaction;
use App\Notification;
use App\Questionnaire;
use App\Project_image;
use App\Account_information;
use App\PlannerPlan;
use App\Milestone;
use App\MessageGroup;
use App\Admin;
use App\Review;
use App\Customer;
use App\Moodboard;
use App\MoodboardImage;
use App\StripeAccount;
use Stripe\StripeClient;
use Stripe;

class SellerController extends Controller
{
    public $success = 200;
    public $error = 401;
     

    public function __construct()
    {
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function planners(Request $req){
        $bank = [];
        $validator = Validator::make($req->all(), [
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'profileName' => 'required|string',
            'userName' => 'required|string|unique:sellers|alpha_num',
            'email' => 'required|string|email|unique:sellers',
            'mobileNo' => 'nullable|unique:sellers',
            'password' => 'required|string|min:6',
            'location' => 'required',
            'portfolio' => 'required|url',
            'resume'  => 'nullable|url',
            'resumeFile' => 'nullable|file|max:5000*2|mimes:pdf,docx,doc',
            'coverLetter'  => 'nullable|url',
            'coverLetterFile' => 'nullable|file|max:5000*2|mimes:pdf,docx,doc',
        ]);

   

    if ($validator->fails()) { 
        return response()->json(['data'=>'','status'=>false,'message'=>$validator->errors()->all(),'token'=>''], $this->success);            
    }else{
		
        $mobile = !empty($req->mobileNo) ? $req->mobileNo : '';
        $latitude  = !empty($req->latitude ) ? $req->latitude  : '';
        $longitude  = !empty($req->longitude) ? $req->longitude : '';
        $location  = !empty($req->location) ? $req->location : '';
        $addressOne  = !empty($req->addressOne) ? $req->addressOne : '';
        $data = new Seller;
        $data->firstName = $req->firstName;
        $data->lastName = $req->lastName;
        $data->userName = $req->userName;
        $data->profileName = $req->profileName;
        $data->email = $req->email;
        $data->mobileNo =  $mobile;
        $data->profileImage = '';
        $data->location =  $location;
        $data->addressOne = $addressOne;
        $data->latitude =  $latitude;
        $data->longitude =  $longitude ;
        $data->type =  2;
        $data->status =  0;
        $data->password = Hash::make($req->password);
        $data->save();
        
        if($data->save()){
            $resume = !empty($req->resume) ? $req->resume : '' ;
            $resumeType = !empty($req->resumeType) ? $req->resumeType : '' ;
            $coverLetterType = !empty($req->coverLetterType) ? $req->coverLetterType : '' ;
            $coverLetter = !empty($req->coverLetter) ? $req->coverLetter : '' ;
            $portfolio = !empty($req->portfolio) ? $req->portfolio : '' ;
            $hearTablepop = !empty($req->hearTablepop) ? $req->hearTablepop : '' ;
            $referredBy = !empty($req->referredBy) ? $req->referredBy : '' ;
            $experience  = !empty($req->experience ) ? $req->experience  : '' ;
            $dedicateTablePopClient = !empty($req->dedicateTablePopClient) ? $req->dedicateTablePopClient : '' ;
            $willingWork = !empty($req->willingWork) ? $req->willingWork : '' ;
            $software  = !empty($req->software) ? $req->software : '' ;
            $otherSoftware  = !empty($req->otherSoftware) ? $req->otherSoftware : '' ;
            $employmentSituation  = !empty($req->employmentSituation) ? $req->employmentSituation : '' ;
            $workingindustry = !empty($req->workingindustry) ? $req->workingindustry : '' ;
            $degrees  = !empty($req->degrees) ? $req->degrees : '' ;
            $otherDegrees  = !empty($req->otherDegrees) ? $req->otherDegrees : '' ;
            $experiencePlanning  = !empty($req->experiencePlanning) ? $req->experiencePlanning : '' ;
            $otherExperiencePlanning  = !empty($req->otherExperiencePlanning) ? $req->otherExperiencePlanning : '' ;
            $pricingModel = !empty($req->pricingModel) ? $req->pricingModel : '' ;
            $plannedSimultaneously = !empty($req->plannedSimultaneously) ? $req->plannedSimultaneously : '' ;
            $teamOfVendors  = !empty($req->teamOfVendors) ? $req->teamOfVendors : '' ;
            $creditToExecute = !empty($req->creditToExecute) ? $req->creditToExecute : '' ;
            $eventClient = !empty($req->eventClient) ? $req->eventClient : '' ;
            $references  = !empty($req->references) ? $req->references : '' ;
            $interestedTablepop  = !empty($req->interestedTablepop) ? $req->interestedTablepop : '' ;
            $personalityPlanners   = !empty($req->personalityPlanners) ? $req->personalityPlanners : '' ;
            $planEventsCompany   = !empty($req->planEventsCompany) ? $req->planEventsCompany : '' ;
            $eventSoftware   = !empty($req->eventSoftware) ? $req->eventSoftware : '' ;
            $promotionSocialMedia = !empty($req->promotionSocialMedia) ? $req->promotionSocialMedia : '' ;
            $eventSuccessful = !empty($req->eventSuccessful) ? $req->eventSuccessful : '' ;
            $gamePlan = !empty($req->gamePlan) ? $req->gamePlan : '' ;
            $successfulPlanningExperience = !empty($req->successfulPlanningExperience) ? $req->successfulPlanningExperience : '' ;
            $experienceNegotiating = !empty($req->experienceNegotiating) ? $req->experienceNegotiating : '' ;
            $stressPlanning  = !empty($req->stressPlanning) ? $req->stressPlanning : '' ;
            $kickoffMeeting  = !empty($req->kickoffMeeting) ? $req->kickoffMeeting : '' ;
            $plannedMoreOneEvent  = !empty($req->plannedMoreOneEvent) ? $req->plannedMoreOneEvent : '' ;
            $prioritizeDeadlines  = !empty($req->prioritizeDeadlines) ? $req->prioritizeDeadlines : '' ;
            $difficultClient  = !empty($req->difficultClient) ? $req->difficultClient : '' ;
            $serviceConsider  = !empty($req->serviceConsider) ? $req->serviceConsider : '' ;
              
            $experiencePlanning1 = '';
 		 	if(!empty($req->experiencePlanning)){
				$experiencePlanning = json_decode($req->experiencePlanning,true);
				if(!empty($experiencePlanning) && is_array($experiencePlanning)){
					foreach($experiencePlanning as $key => $value){
						$bank[] = $value;
					}
					$experiencePlanning1 = implode(',',$bank);
				}
	    	}else{
                $experiencePlanning1 = '';
            }  
            $seller = new Planner;
            $seller->plannerId = $data->id;
            $seller->resumeFile = '';
            $seller->resume = $resume;
            $seller->coverLetterFile = '';
            $seller->coverLetter = $coverLetter;
            $seller->portfolio = $portfolio;
            $seller->hearTablepop = $hearTablepop;
            $seller->referredBy = $referredBy;
            $seller->experience = $experience;
            $seller->dedicateTablePopClient = $dedicateTablePopClient;
            $seller->willingWork = $willingWork;
            $seller->references = $references;
            $seller->software = $software;
            $seller->otherSoftware = $otherSoftware;
            $seller->employmentSituation = $employmentSituation;
            $seller->workingindustry = $workingindustry;
            $seller->degrees = $degrees;
            $seller->otherDegrees = $otherDegrees;
            $seller->experiencePlanning = $experiencePlanning1;
            $seller->otherExperiencePlanning = $otherExperiencePlanning;
            $seller->pricingModel = $pricingModel;
            $seller->plannedSimultaneously = $plannedSimultaneously;
            $seller->teamOfVendors = $teamOfVendors;
            $seller->creditToExecute = $creditToExecute;
            $seller->eventClient = $eventClient;
            $seller->interestedTablepop = $interestedTablepop;
            $seller->personalityPlanners = $personalityPlanners;
            $seller->planEventsCompany = $planEventsCompany;
            $seller->eventSoftware = $eventSoftware;
            $seller->promotionSocialMedia = $promotionSocialMedia;
            $seller->eventSuccessful = $eventSuccessful;
            $seller->gamePlan = $gamePlan;
            $seller->successfulPlanningExperience = $successfulPlanningExperience;
            $seller->experienceNegotiating = $experienceNegotiating;
            $seller->stressPlanning = $stressPlanning;
            $seller->kickoffMeeting = $kickoffMeeting;
            $seller->plannedMoreOneEvent = $plannedMoreOneEvent;
            $seller->prioritizeDeadlines = $prioritizeDeadlines;
            $seller->difficultClient = $difficultClient;
            $seller->serviceConsider = $serviceConsider;
            if($req->hasFile('resumeFile')){
                $file = $req->file('resumeFile');
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $fileName = str_replace( " ", "-", trim($fileName) );
                $seller->resumeFile = $fileName;
                $folder = Helper::imageUpload($fileName, $file,$folder="vendor/vendor".$data->id."");
            }
            if($req->hasFile('coverLetterFile')){
                $file = $req->file('coverLetterFile');
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $fileName = str_replace( " ", "-", trim($fileName) );
                $seller->coverLetterFile = $fileName;
                $folder = Helper::imageUpload($fileName, $file,$folder="vendor/vendor".$data->id."");
            }
            $seller->save();
             $sellerData = Seller::select('sellers.*','planners.resume','planners.coverLetter','planners.portfolio')
                                 ->leftjoin('planners','planners.plannerID','sellers.id')->where('sellers.id',$data->id)->get()->first();
            $subject = "WelCome"; 
            $email_data = ['email' =>$sellerData['email'],'user'=>'seller','subject' => $subject];
            
            

           Helper::send_mail('emailTemplate.welcome',$email_data);


           if(!empty($mobile)){
            $mobile = $mobile;
            $msg = Config::get('msg.welcome');  
            Helper::sendMessage($msg,$mobile);
        }
            $adminData = Admin::where('type',1)->where('adminType',1)->get()->first();
            $adminId = !empty($adminData->id) ? $adminData->id : 0 ;
           $notification = new Notification;
           $notification->notification = 'New planner '.$data->firstName.' '.$data->lastName.' registered';
           $notification->type = 'admin';
           $notification->urlType = 'planner';
           $notification->fromId = $data->id;
           $notification->toId = $adminId;
           $notification->sendType = 'seller';
           $notification->sellerId = $data->id;
           $notification->save();
           Socket::notification($userId=$adminId,$userType='admin');

         
           $MessageGroupData = MessageGroup::where('sellerId',$data->id)->where('type',1)->get()->first();
           if(empty($MessageGroupData)){
               $MessageGroup = new MessageGroup;
               $MessageGroup->sellerId = $data->id;
               $MessageGroup->adminId = $adminId;
               $MessageGroup->type = 1;
               $MessageGroup->save();
           }

           $Token =  $sellerData->createToken('iaastha-api')->accessToken;
           $sellerData = Helper::removeNull($sellerData);
           return response()->json(['data'=>$sellerData,'status'=>true,'message'=>'Register Successfully','token'=>$Token], $this->success); 
        }
    }       
   }
  

   public function vendors(Request $req){
    $bank = [];
    $validator = Validator::make($req->all(), [
        'firstName' => 'required|string',
        'lastName' => 'required|string',
        'profileName' => 'required|string',
        'userName' => 'required|string|unique:sellers|alpha_num',
        'email' => 'required|string|email|unique:sellers',
        'mobileNo' => 'nullable|unique:sellers',
        'password' => 'required|string|min:6',
        'location' => 'required',
        'portfolio' => 'required|url',
        'resume'  => 'nullable|url',
        'resumeFile' => 'nullable|file|max:5000*2|mimes:pdf,docx,doc',
        'coverLetter'  => 'nullable|url',
        'coverLetterFile' => 'nullable|file|max:5000*2|mimes:pdf,docx,doc',
    ]);

   

    if ($validator->fails()) { 
        return response()->json(['data'=>'','status'=>false,'message'=>$validator->errors()->all(),'token'=>''], $this->success);            
    }else{
        $mobile = !empty($req->mobileNo) ? $req->mobileNo : '';
        $latitude  = !empty($req->latitude ) ? $req->latitude  : '';
        $longitude  = !empty($req->longitude) ? $req->longitude : '';
        $longitude  = !empty($req->longitude) ? $req->longitude : '';
        $data = new Seller;
        $data->firstName = $req->firstName;
        $data->lastName = $req->lastName;
        $data->profileName = $req->profileName;
        $data->userName = $req->userName;
        $data->email = $req->email;
        $data->mobileNo =  $mobile;
        $data->profileImage =  '';
        $data->location =  $req->location;
        $data->latitude =  $latitude;
        $data->longitude =  $longitude ;
        $data->addressOne =  $req->addressOne;
        $data->type = 1;
        $data->status =  0;
        $data->password = Hash::make($req->password);
        $data->save();
        
        if($data->save()){
            $resume = !empty($req->resume) ? $req->resume : '' ;
            $resumeType = !empty($req->resumeType) ? $req->resumeType : '' ;
            $coverLetterType = !empty($req->coverLetterType) ? $req->coverLetterType : '' ;
            $coverLetter = !empty($req->coverLetter) ? $req->coverLetter : '' ;
            $portfolio = !empty($req->portfolio) ? $req->portfolio : '' ;
            $hearTablepop = !empty($req->hearTablepop) ? $req->hearTablepop : '' ;
            $referredBy = !empty($req->referredBy) ? $req->referredBy : '' ;
            $experience  = !empty($req->experience ) ? $req->experience  : '' ;
            $dedicateTablePopClient = !empty($req->dedicateTablePopClient) ? $req->dedicateTablePopClient : '' ;
            $willingWork = !empty($req->willingWork) ? $req->willingWork : '' ;
            $software  = !empty($req->software) ? $req->software : '' ;
            $otherSoftware  = !empty($req->otherSoftware) ? $req->otherSoftware : '' ;
            $employmentSituation  = !empty($req->employmentSituation) ? $req->employmentSituation : '' ;
            $workingindustry = !empty($req->workingindustry) ? $req->workingindustry : '' ;
            $degrees  = !empty($req->degrees) ? $req->degrees : '' ;
            $otherDegrees  = !empty($req->otherDegrees) ? $req->otherDegrees : '' ;
            $experiencePlanning  = !empty($req->experiencePlanning) ? $req->experiencePlanning : '' ;
            $otherExperiencePlanning  = !empty($req->otherExperiencePlanning) ? $req->otherExperiencePlanning : '' ;
            $pricingModel = !empty($req->pricingModel) ? $req->pricingModel : '' ;
            $plannedSimultaneously = !empty($req->plannedSimultaneously) ? $req->plannedSimultaneously : '' ;
            $teamOfVendors  = !empty($req->teamOfVendors) ? $req->teamOfVendors : '' ;
            $creditToExecute = !empty($req->creditToExecute) ? $req->creditToExecute : '' ;
            $eventClient = !empty($req->eventClient) ? $req->eventClient : '' ;
            $references  = !empty($req->references) ? $req->references : '' ;
            $interestedTablepop  = !empty($req->interestedTablepop) ? $req->interestedTablepop : '' ;
            $personalityPlanners   = !empty($req->personalityPlanners) ? $req->personalityPlanners : '' ;
            $planEventsCompany   = !empty($req->planEventsCompany) ? $req->planEventsCompany : '' ;
            $eventSoftware   = !empty($req->eventSoftware) ? $req->eventSoftware : '' ;
            $promotionSocialMedia = !empty($req->promotionSocialMedia) ? $req->promotionSocialMedia : '' ;
            $eventSuccessful = !empty($req->eventSuccessful) ? $req->eventSuccessful : '' ;
            $gamePlan = !empty($req->gamePlan) ? $req->gamePlan : '' ;
            $successfulPlanningExperience = !empty($req->successfulPlanningExperience) ? $req->successfulPlanningExperience : '' ;
            $experienceNegotiating = !empty($req->experienceNegotiating) ? $req->experienceNegotiating : '' ;
            $stressPlanning  = !empty($req->stressPlanning) ? $req->stressPlanning : '' ;
            $kickoffMeeting  = !empty($req->kickoffMeeting) ? $req->kickoffMeeting : '' ;
            $plannedMoreOneEvent  = !empty($req->plannedMoreOneEvent) ? $req->plannedMoreOneEvent : '' ;
            $prioritizeDeadlines  = !empty($req->prioritizeDeadlines) ? $req->prioritizeDeadlines : '' ;
            $difficultClient  = !empty($req->difficultClient) ? $req->difficultClient : '' ;
            $servicesCategory  = !empty($req->servicesCategory) ? $req->servicesCategory : '' ;
            $serviceSubCategory  = !empty($req->serviceSubCategory) ? $req->serviceSubCategory : '' ;
            $otherServices  = !empty($req->otherServices) ? $req->otherServices : '' ;
            $serviceConsider  = !empty($req->serviceConsider) ? $req->serviceConsider : '' ;
			$experiencePlanning1 = '';
          /*   if(!empty($experiencePlanning)){
				$experiencePlanning = json_decode($experiencePlanning,true);
				if(!empty($experiencePlanning) && is_array($experiencePlanning)){
					foreach($experiencePlanning as $key => $value){
						$bank[] = $value;
					}
					$experiencePlanning1 = implode(',',$bank);
				}
	    	}else{
                $experiencePlanning1 = '';
            } */

            $servicesCategory1 = '';
            if($servicesCategory){
                  $servicesCategory = json_decode($servicesCategory,true);
                  if(!empty($servicesCategory) && is_array($servicesCategory)){
                     foreach($servicesCategory as $key => $value){
                        $bank[] = $value;
                     }
                     $servicesCategory1 = implode(',',$bank);
                  }
            }
            $serviceSubCategory1 = '';
            if($serviceSubCategory){
               $serviceSubCategory = json_decode($serviceSubCategory,true);
               if(!empty($serviceSubCategory) && is_array($serviceSubCategory)){
                  foreach($serviceSubCategory as $key => $value1){
                     $bank1[] = $value1;
                  }
                  $serviceSubCategory1 = implode(',',$bank1);
               }
            }
         
            $seller = new Vendor;
            $seller->sellerId = $data->id;
            $seller->resumeFile = '';
            $seller->contract = '';
            $seller->resume = $resume;
            $seller->coverLetterFile = '';
            $seller->coverLetter = $coverLetter;
            $seller->portfolio = $portfolio;
            $seller->hearTablepop = $hearTablepop;
            $seller->referredBy = $referredBy;
            $seller->experience = $experience;
            $seller->dedicateTablePopClient = $dedicateTablePopClient;
            $seller->willingWork = $willingWork;
            $seller->references = $references;
            $seller->software = $software;
            $seller->otherSoftware = $otherSoftware;
            $seller->employmentSituation = $employmentSituation;
            $seller->workingindustry = $workingindustry;
            $seller->degrees = $degrees;
            $seller->otherDegrees = $otherDegrees;
            $seller->servicesCategory = $servicesCategory1;
            $seller->serviceSubCategory = $serviceSubCategory1;
            $seller->experiencePlanning = $experiencePlanning1;
            $seller->otherExperiencePlanning = $otherExperiencePlanning;
            $seller->pricingModel = $pricingModel;
            $seller->plannedSimultaneously = $plannedSimultaneously;
            $seller->teamOfVendors = $teamOfVendors;
            $seller->creditToExecute = $creditToExecute;
            $seller->eventClient = $eventClient;
            $seller->interestedTablepop = $interestedTablepop;
            $seller->personalityPlanners = $personalityPlanners;
            $seller->planEventsCompany = $planEventsCompany;
            $seller->eventSoftware = $eventSoftware;
            $seller->promotionSocialMedia = $promotionSocialMedia;
            $seller->eventSuccessful = $eventSuccessful;
            $seller->gamePlan = $gamePlan;
            $seller->successfulPlanningExperience = $successfulPlanningExperience;
            $seller->experienceNegotiating = $experienceNegotiating;
            $seller->stressPlanning = $stressPlanning;
            $seller->kickoffMeeting = $kickoffMeeting;
            $seller->plannedMoreOneEvent = $plannedMoreOneEvent;
            $seller->prioritizeDeadlines = $prioritizeDeadlines;
            $seller->difficultClient = $difficultClient;
            $seller->serviceConsider = $serviceConsider;
            if($req->hasfile('resumeFile')){
                $file = $req->file('resumeFile');
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $fileName = str_replace( " ", "-", trim($fileName) );
                $seller->resumeFile = $fileName;
                $folder = Helper::imageUpload($fileName, $file,$folder="vendor/vendor".$data->id."");
            }
            if($req->hasfile('coverLetterFile')){
                $file = $req->file('coverLetterFile');
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $fileName = str_replace( " ", "-", trim($fileName) );
                $seller->coverLetterFile = $fileName;
                $folder = Helper::imageUpload($fileName, $file,$folder="vendor/vendor".$data->id."");
            }
            $seller->save();

            if(!empty($mobile)){
                $mobile = $mobile;
                $msg = Config::get('msg.welcome');  
                Helper::sendMessage($msg,$mobile);
            }
             $sellerData = Seller::select('sellers.*','planners.resume','planners.coverLetter','planners.portfolio')
                                 ->leftjoin('planners','planners.plannerID','sellers.id')->where('sellers.id',$data->id)->get()->first();
            $subject = "WelCome"; 
            $email_data = ['email' =>$sellerData['email'],'user'=>'seller','subject' => $subject];  
            Helper::send_mail('emailTemplate.welcome',$email_data);
            
            $adminData = Admin::where('type',1)->where('adminType',1)->get()->first();
            $adminId = !empty($adminData->id) ? $adminData->id : 0 ;
           $notification = new Notification;
           $notification->notification = 'New vendor '.$data->firstName.' '.$data->lastName.' registered';
           $notification->type = 'admin';
           $notification->urlType = 'vendor';
           $notification->fromId = $data->id;
           $notification->toId = $adminId;
           $notification->sendType = 'seller';
           $notification->sellerId = $data->id;
           $notification->save();
           Socket::notification($userId=$adminId,$userType='admin');

          
           $MessageGroupData = MessageGroup::where('sellerId',$data->id)->where('type',1)->get()->first();
           if(empty($MessageGroupData)){
               $MessageGroup = new MessageGroup;
               $MessageGroup->sellerId = $data->id;
               $MessageGroup->adminId = $adminId;
               $MessageGroup->type = 1;
               $MessageGroup->save();
           }

           $Token =  $sellerData->createToken('iaastha-api')->accessToken;
           $sellerData = Helper::removeNull($sellerData);
           return response()->json(['data'=>$sellerData,'status'=>true,'message'=>'Register Successfully','token'=>$Token], $this->success); 
        }
    } 
   }

   public function sellerLogin(Request $request){
    $validator = Validator::make($request->all(), [
        'username' => 'required|string',
        'password' => 'required', 
    ]);
    if ($validator->fails()) { 
        return response()->json(['data'=>'','status'=>false,'message'=>$validator->errors()->all(),'token'=>''], $this->success);            
    }else{
        $username = $request->username;
            $data = Seller::where('email',$username)->orwhere('userName',$username)->get()->first();
            if(!empty($data)){
                $dataPassword =  $data->password; 
                if(Hash::check($request->password,$dataPassword)){
                   $sellerImage = !empty($data->profileImage) ? Helper::getUrl().'vendor/vendor'.$data->id.'/'.$data->profileImage : '';
                   $sellerType = $data->type == 1 ? 'vendor' : 'planner';
                   $data['profileImage'] = $sellerImage;
                   
                   $Token =  $data->createToken('iaastha-api')->accessToken;
                   $data = Helper::removeNull($data);
                   return response()->json(['data'=>$data,'status'=>true,'message'=>'Login Successfully','token'=>$Token], $this->success);            
                }else{
                   return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Password','token'=>''], $this->success);  
                }
           }else{
              return response()->json(['data'=>'','status'=>false,'message'=>'Invalid UserName','token'=>''], $this->success); 
           }
    }
   }

   public function profileDetails(Request $req){
  //  $sellerData['otherDetails']['experiencePlanning'] = [];
  $otherDetails = [];
    $token = $req->bearerToken();
    $encode_token =  Helper::encode_token($token);
    if(!empty($encode_token)){
    $sellerId = $encode_token;
    $imageUrl = Helper::getUrl();
    $demoPdf = env('DEMO_PDF');
    $sellerData  = Seller::select('sellers.*', DB::raw('(CASE WHEN sellers.profileImage = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",sellers.id,"/", sellers.profileImage) END) AS profileImage'),'countries.name as country', DB::raw('(CASE  WHEN sellers.contract IS NULL THEN "'.$demoPdf.'" WHEN sellers.contract = "" THEN "'.$demoPdf.'" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",sellers.id,"/", sellers.contract) END) AS contract'))
                           ->leftjoin('countries','countries.id','sellers.countryId')
                          // ->leftjoin('stripe_accounts','stripe_accounts.sellerId','sellers.id')
                           ->where('sellers.id',$sellerId)->get()->first();
    if(!empty($sellerData)){
            $sellerType = !empty($sellerData->type) ? $sellerData->type : '' ;
            if($sellerType == 1){
                $otherDetails = vendor::select("*", DB::raw('(CASE  WHEN resumeFile IS NULL THEN "" WHEN resumeFile = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",sellerId,"/", resumeFile) END) AS resumeFile'),DB::raw('(CASE  WHEN coverLetterFile IS NULL THEN "" WHEN coverLetterFile = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",sellerId,"/", coverLetterFile) END) AS coverLetterFile'))->where('sellerId',$sellerId)->get()->first();
              
                $servicesCategory  = !empty($otherDetails->servicesCategory) ? $otherDetails->servicesCategory : [] ;
                $serviceSubCategory  = !empty($otherDetails->serviceSubCategory) ? $otherDetails->serviceSubCategory : [] ;
                if(!empty($servicesCategory)){
                    $otherDetails['servicesCategory'] = array_map('intval', explode(',', $servicesCategory));
                 }else{
                    $otherDetails['servicesCategory'] = [];
                 }
                if(!empty($serviceSubCategory)){
                    $otherDetails['serviceSubCategory'] = array_map('intval', explode(',', $serviceSubCategory));
                 }else{
                    $otherDetails['serviceSubCategory'] = [];
                 }
               
            }else if($sellerType == 2){
                $otherDetails = Planner::select("*", DB::raw('(CASE  WHEN resumeFile IS NULL THEN "" WHEN resumeFile = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",plannerId,"/", resumeFile) END) AS resumeFile'),DB::raw('(CASE  WHEN coverLetterFile IS NULL THEN "" WHEN coverLetterFile = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",plannerId,"/", coverLetterFile) END) AS coverLetterFile'))->where('plannerId',$sellerId)->get()->first();
                $experiencePlanning  = !empty($otherDetails->experiencePlanning) ? $otherDetails->experiencePlanning : [] ;
                if(!empty($experiencePlanning)){
                    $otherDetails['experiencePlanning'] = array_map('intval', explode(',', $experiencePlanning));
                 }else{
                    $otherDetails['experiencePlanning'] = [];
                 }

            }
            
        
            $sellerData['otherDetails'] = $otherDetails;
            $sellerData['accountInformation'] = Account_information::where('sellerId',$sellerId)->get()->first();
            $sellerData['projectImage'] = Project_image::select("id","event","numberAttendees","locationEvent",DB::raw('(CASE WHEN image = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor","'.$sellerId.'","/", image) END) AS image'))->where('sellerId',$sellerId)->get()->toArray();
            $StripeAccount  = StripeAccount::where('sellerId',$sellerId)->get()->first();
            if(!empty($StripeAccount)){
                $resAccount =  $this->stripe->accounts->retrieve($StripeAccount->accountId);
                $status =  $resAccount->capabilities->transfers ;   
            }else{
                $status = 'inactive';
            }
           
            $sellerData['stripeAccountStatus'] = $status;
            $sellerData = Helper::removeNull($sellerData);
            return response()->json(['data'=>$sellerData,'status'=>true,'message'=>'Profile Data','token'=>''], $this->success);
    }else{
        return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Seller Token','token'=>''], $this->success); 
    }
}else{
    return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Seller Token','token'=>''], $this->success); 
}
  }



   public function profileUpdate(Request $req){
    $experiencePlanning1 = '';
    $imageUrl = Helper::getUrl();
       $StripeAccountStatus = 'inactive';
        $token = $req->bearerToken();
        $encode_token =  Helper::encode_token($token);
        if(!empty($encode_token)){
        $sellerId = $encode_token;
        $sellerData  = Seller::find($sellerId);
        if(!empty($sellerData)){
            $sellerType = !empty($sellerData->type) ? $sellerData->type : '' ;
            $validator = Validator::make($req->all(), [
               /*  'firstName' => 'required|string',
                'lastName' => 'required|string', */
                //'userName' => 'required|alpha_num|unique:sellers,userName,'.$id,
               // 'email' => 'required|email|unique:sellers,email,'.$sellerId,
                'mobileNo' => 'nullable|unique:sellers,mobileNo,'.$sellerId,
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048*5',
                 'dob' =>   'nullable',
                 'postalCode' =>   'nullable|numeric',
                 'resume'  => 'nullable|url',
                'resumeFile' => 'nullable|max:5000*2|mimes:pdf,docx,doc',
                'coverLetter'  => 'nullable|url',
                'facebook'  => 'nullable|url',
                'twitter'  => 'nullable|url',
                'pinterest'  => 'nullable|url',
                'website'  => 'nullable|url',
                'coverLetterFile' => 'nullable|max:5000*2|mimes:pdf,docx,doc', 
            ]);
            if ($validator->fails()) { 
                return response()->json(['data'=>'','status'=>false,'message'=>$validator->errors()->all(),'token'=>''], $this->success);            
            }else{
                $profileImage = !empty($sellerData->profileImage) ? $sellerData->profileImage : '' ;
              
                if(!empty($req->firstName)){
                    $sellerData->firstName =  $req->firstName;
                }
                if(!empty($req->profileName)){
                    $sellerData->profileName =  $req->profileName;
                  
                }
                if(!empty($req->lastName)){
                    $sellerData->lastName =  $req->lastName;
                }
                if(!empty($req->dob)){
                    $sellerData->dob =  $req->dob;
                }
                if(!empty($req->mobileNo)){
                    $sellerData->mobileNo =  $req->mobileNo;
                }
                if(!empty($req->gender)){
                    $sellerData->gender =  $req->gender;
                }
                if(!empty($req->country)){
                    $sellerData->countryId =  $req->country;
                }
                if(!empty($req->state)){
                    $sellerData->state =  $req->state;
                }
                if(!empty($req->city)){
                    $sellerData->city =  $req->city;
                }
               
                if(!empty($req->location)){
                    $sellerData->location =  $req->location;
                }
              
                if(!empty($req->addressOne)){
                    $sellerData->addressOne =  $req->addressOne;
                }
              
                if(!empty($req->addressTwo)){
                    $sellerData->addressTwo =  $req->addressTwo;
                }
                if(!empty($req->postalCode)){
                    $sellerData->postalCode =  $req->postalCode;
                }
                if(!empty($req->website)){
                    $sellerData->website =  $req->website;
                }
                if(!empty($req->facebook)){
                    $sellerData->facebook =  $req->facebook;
                }
                if(!empty($req->pinterest)){
                    $sellerData->pinterest =  $req->pinterest;
                }
                if(!empty($req->twitter)){
                    $sellerData->twitter =  $req->twitter;
                }
               
               if ($req->hasFile('image')){
                    if(!empty($profileImage)){
                        Helper::deleteImage("vendor/vendor".$sellerId."/".$profileImage."");
                    }
                    $image = $req->file('image');
                    $imageName = time().'.'.$req->image->extension();  
                    $imageName = str_replace( " ", "-", trim($imageName) ); 
                    $folder = Helper::imageUpload($imageName, $image,$folder="vendor/vendor".$sellerId."");
                    $sellerData->profileImage = $imageName;
                }
                $sellerData->save();


            if($sellerType == 1){
                $seller =  Vendor::where('sellerId',$sellerId)->get()->first();
                if(!empty($seller)){
                    $seller = $seller;
                }else{
                    $seller = new Vendor;
                    $seller->sellerId = $sellerId;
                }
             }else if($sellerType == 2){
                $seller =  Planner::where('plannerId',$sellerId)->get()->first();
                if(!empty($seller)){
                    $seller = $seller;
                   
                }else{
                    $seller = new Planner;
                    $seller->plannerId = $sellerId;
                }
             }
           
            if(!empty($req->resume)){
                  $seller->resume = $req->resume;
            }
            if(!empty($req->coverLetter)){
                $seller->coverLetter = $req->coverLetter;
            }

            if(!empty($req->portfolio)){
                $seller->portfolio = $req->portfolio;
            }

            if(!empty($req->hearTablepop)){
                $seller->hearTablepop = $req->hearTablepop;
            }

            if(!empty($req->referredBy)){
                $seller->referredBy = $req->referredBy;
            }
            
            if(!empty($req->experience)){
                $seller->experience = $req->experience;
            }

            if(!empty($req->dedicateTablePopClient)){
                $seller->dedicateTablePopClient = $req->dedicateTablePopClient;
            }

            if(!empty($req->willingWork)){
                $seller->willingWork = $req->willingWork;
            }

            if(!empty($req->references)){
                $seller->references = $req->references;
            }

            if(!empty($req->software)){
                $seller->software = $req->software;
            }
            
            if(!empty($req->software)){
                $seller->software = $req->software;
            }
            if(!empty($req->otherSoftware)){
                $seller->otherSoftware = $req->otherSoftware;
            }

            if(!empty($req->employmentSituation)){
                $seller->employmentSituation = $req->employmentSituation;
            }

            if(!empty($req->workingindustry)){
                $seller->workingindustry = $req->workingindustry;
            }

            if(!empty($req->degrees)){
                $seller->degrees = $req->degrees;
            }
            if(!empty($req->otherDegrees)){
                $seller->otherDegrees = $req->otherDegrees;
            }
            if(!empty($req->serviceConsider)){
                $seller->serviceConsider = $req->serviceConsider;
            }
            if( $sellerType == 2){
                if(!empty($req->experiencePlanning)){
                    $experiencePlanning = '';
                    $experiencePlanning = json_decode($req->experiencePlanning,true);
                    if(!empty($experiencePlanning) && is_array($experiencePlanning)){
                        foreach($experiencePlanning as $key => $value){
                            $bank[] = $value;
                        }
                        $experiencePlanning1 = implode(',',$bank);
                    }else{
                        $experiencePlanning1 = '';
                    }
                    $seller->experiencePlanning = $experiencePlanning1;
                }
            }
            if( $sellerType == 1){
                $servicesCategory1 = '';
                if($req->servicesCategory){
                      $servicesCategory = json_decode($req->servicesCategory,true);
                      if(!empty($servicesCategory) && is_array($servicesCategory)){
                         foreach($servicesCategory as $key => $value){
                            $bank[] = $value;
                         }
                         $servicesCategory1 = implode(',',$bank);
                      }
                      $seller->servicesCategory = $servicesCategory1;
                }
                $serviceSubCategory1 = '';
                if($req->serviceSubCategory){
                   $serviceSubCategory = json_decode($req->serviceSubCategory,true);
                   if(!empty($serviceSubCategory) && is_array($serviceSubCategory)){
                      foreach($serviceSubCategory as $key => $value1){
                         $bank1[] = $value1;
                      }
                      $serviceSubCategory1 = implode(',',$bank1);
                   }
                   $seller->serviceSubCategory = $serviceSubCategory1;
                }
            }
           
           

            if(!empty($req->otherExperiencePlanning)){
                $seller->otherExperiencePlanning = $req->otherExperiencePlanning;
            }
            if(!empty($req->pricingModel)){
                $seller->pricingModel = $req->pricingModel;
            }
           
            if(!empty($req->plannedSimultaneously)){
                $seller->plannedSimultaneously = $req->plannedSimultaneously;
            }
            
            if(!empty($req->teamOfVendors)){
                $seller->teamOfVendors = $req->teamOfVendors;
            }

            if(!empty($req->creditToExecute)){
                $seller->creditToExecute = $req->creditToExecute;
            }
            if(!empty($req->eventClient)){
                $seller->eventClient = $req->eventClient;
            }

            if(!empty($req->interestedTablepop)){
                $seller->interestedTablepop = $req->interestedTablepop;
            }

            if(!empty($req->personalityPlanners)){
                $seller->personalityPlanners = $req->personalityPlanners;
            }

            if(!empty($req->planEventsCompany)){
                $seller->planEventsCompany = $req->planEventsCompany;
            }

            if(!empty($req->eventSoftware)){
                $seller->eventSoftware = $req->eventSoftware;
            }

            
            if(!empty($req->promotionSocialMedia)){
                $seller->promotionSocialMedia = $req->promotionSocialMedia;
            }

             
            if(!empty($req->eventSuccessful)){
                $seller->eventSuccessful = $req->eventSuccessful;
            }

            if(!empty($req->gamePlan)){
                $seller->gamePlan = $req->gamePlan;
            }
            
            if(!empty($req->successfulPlanningExperience)){
                $seller->successfulPlanningExperience = $req->successfulPlanningExperience;
            }

            if(!empty($req->experienceNegotiating)){
                $seller->experienceNegotiating = $req->experienceNegotiating;
            }

            if(!empty($req->stressPlanning)){
                $seller->stressPlanning = $req->stressPlanning;
            }

            if(!empty($req->kickoffMeeting)){
                $seller->kickoffMeeting = $req->kickoffMeeting;
            }

            if(!empty($req->plannedMoreOneEvent)){
                $seller->plannedMoreOneEvent = $req->plannedMoreOneEvent;
            }

            if(!empty($req->prioritizeDeadlines)){
                $seller->prioritizeDeadlines = $req->prioritizeDeadlines;
            }

            if(!empty($req->difficultClient)){
                $seller->difficultClient = $req->difficultClient;
            }
           
       
            if($req->file('resumeFile')){
                $file = $req->file('resumeFile');
                $fileName = time().'.'.$file->getClientOriginalName();
                $fileName = str_replace( " ", "-", trim($fileName) );
                $seller->resumeFile = $fileName;
                $folder = Helper::imageUpload($fileName, $file,$folder="vendor/vendor".$sellerId);
            }
            if($req->file('coverLetterFile')){
                $file = $req->file('coverLetterFile');
                $fileName = time().'.'.$file->getClientOriginalName();
                $fileName = str_replace( " ", "-", trim($fileName) );
                $seller->coverLetterFile = $fileName;
                $folder = Helper::imageUpload($fileName, $file,$folder="vendor/vendor".$sellerId);
            }
          
            $seller->save();
            
            
        
            /*     $sellerAccount = Account_information::where('sellerId',$sellerId)->get()->first();
                if(!empty($sellerAccount)){
                    $accountData =  $sellerAccount;
                }else{
                    $accountData = new Account_information;
                }
                $accountData->sellerId  = $sellerId;
                $accountData->bankName  = $req->bankName;
                $accountData->routingNumber  = $req->routingNumber;
                $accountData->accountNo  = $req->accountNo;
                $accountData->accountHolderName  = $req->accountHolderName;
                if(!empty($req->bankName) || !empty($req->routingNumber) || !empty($req->accountNo) || !empty($req->accountHolderName)){
                    $accountData->save();
                } */
                
                $StripeAccount = StripeAccount::where('sellerId',$sellerId)->get()->first();
                $accountId = !empty($StripeAccount->accountId) ? $StripeAccount->accountId : '' ;
                if(!empty($accountId)){
                    $resAccount =   $this->stripe->accounts->retrieve($accountId);
                    $StripeAccountStatus =  $resAccount->capabilities->transfers;
                }
                
                $sellerData['StripeAccountStatus'] =  $StripeAccountStatus;
                $sellerData = Helper::removeNull($sellerData);
                if($sellerType == 1){
                    $otherDetails = vendor::select("*", DB::raw('(CASE  WHEN resumeFile IS NULL THEN "" WHEN resumeFile = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",sellerId,"/", resumeFile) END) AS resumeFile'),DB::raw('(CASE  WHEN coverLetterFile IS NULL THEN "" WHEN coverLetterFile = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",sellerId,"/", coverLetterFile) END) AS coverLetterFile'))->where('sellerId',$sellerId)->get()->first();
                    $otherDetails = Helper::removeNull($otherDetails);
                    $servicesCategory  = !empty($otherDetails->servicesCategory) ? $otherDetails->servicesCategory : [] ;
                    $serviceSubCategory  = !empty($otherDetails->serviceSubCategory) ? $otherDetails->serviceSubCategory : [] ;
                  //  $otherServices  = !empty($otherDetails->otherServices) ? $otherDetails->otherServices : '' ;
                   // $otherDetails['otherServices'] = $otherServices
                    if(!empty($servicesCategory)){
                        $otherDetails['servicesCategory'] = array_map('intval', explode(',', $servicesCategory));
                     }else{
                        $otherDetails['servicesCategory'] = [];
                     }
                    if(!empty($serviceSubCategory)){
                        $otherDetails['serviceSubCategory'] = array_map('intval', explode(',', $serviceSubCategory));
                     } else{
                        $otherDetails['serviceSubCategory'] = [];
                     }
                   
                }else if($sellerType == 2){
                    $otherDetails = Planner::select("*", DB::raw('(CASE  WHEN resumeFile IS NULL THEN "" WHEN resumeFile = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",plannerId,"/", resumeFile) END) AS resumeFile'),DB::raw('(CASE  WHEN coverLetterFile IS NULL THEN "" WHEN coverLetterFile = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",plannerId,"/", coverLetterFile) END) AS coverLetterFile'))->where('plannerId',$sellerId)->get()->first();
                    $otherDetails = Helper::removeNull($otherDetails);
                    $experiencePlanning  = !empty($otherDetails->experiencePlanning) ? $otherDetails->experiencePlanning : [] ;
                    if(!empty($experiencePlanning)){
                        $otherDetails['experiencePlanning'] = array_map('intval', explode(',', $experiencePlanning));
                     }else{
                        $otherDetails['experiencePlanning'] = [];
                     }
    
                }
               
                $sellerData['otherDetails'] = $otherDetails;
                $sellerData = Helper::removeNull($sellerData);
                return response()->json(['data'=>$sellerData,'status'=>true,'message'=>'profile Update success but admin this account not approved','token'=>''], $this->success);   
            
               /*   
               if ($req->hasFile('photoIdFront')){
                    $image = $req->file('photoIdFront');
                    $imageName = time().'.'.$req->photoIdFront->extension();  
                    $imageName = str_replace( " ", "-", trim($imageName) ); 
                    $fp = fopen($image, 'r');
                    $photo =  \Stripe\File::create([
                       'file' => $fp,
                       'purpose' => 'identity_document',
                     ]);
                     $photoIdFront = $photo->id; 
                }else{
                    $photoIdFront =''; 
                }

                if ($req->hasFile('photoIdBack')){
                    $image = $req->file('photoIdBack');
                    $imageName = time().'.'.$req->photoIdBack->extension();  
                    $imageName = str_replace( " ", "-", trim($imageName) ); 
                    $fp = fopen($image, 'r');
                    $photo2 =  \Stripe\File::create([
                       'file' => $fp,
                       'purpose' => 'identity_document',
                     ]);
                     $photoIdBack = $photo2->id; 
                }else{
                    $photoIdBack =''; 
                }
                
                $StripeAccount = StripeAccount::where('sellerId',$sellerId)->get()->first();
                $accountId = !empty($StripeAccount->accountId) ? $StripeAccount->accountId : '' ;
                $resAccount =   $this->stripe->accounts->retrieve($accountId);
                $StripeAccountStatus =  $resAccount->capabilities->transfers;
                $businessWebsite = !empty($req->businessWebsite) ? $req->businessWebsite : 'https://help.sharetribe.com/' ;
                
                if(!empty($StripeAccount)){
                    $StripeAccount = $StripeAccount;
                    $StripeAccount->businessType  =  $req->businessType ; 
                    $StripeAccount->ssn  =  $req->ssn ; 
                    $StripeAccount->industry  =  $req->industry ; 
                    $StripeAccount->businessWebsite  =  $businessWebsite ; 
                    if($req->businessType == 'company'){
                        $StripeAccount->jobTitle  =  $req->jobTitle ; 
                        $StripeAccount->tax_id  =  $req->tax_id ;
                        $StripeAccount->company  =  $req->company ;
                    }
                    $StripeAccount->mcc  =  $req->mcc ; 
                    $StripeAccount->photoIdFront  =   $photoIdFront; 
                    $StripeAccount->photoIdBack  = $photoIdBack; 
                    $StripeAccount->save();
                }else{
                    $StripeAccount = new StripeAccount;
                    $StripeAccount->businessType  =  $req->businessType ; 
                    $StripeAccount->ssn  =  $req->ssn ; 
                    $StripeAccount->industry  =  $req->industry ; 
                    $StripeAccount->businessWebsite  =  $businessWebsite ; 
                    if($req->businessType == 'company'){
                        $StripeAccount->jobTitle  =  $req->jobTitle ; 
                        $StripeAccount->tax_id  =  $req->tax_id ;
                        $StripeAccount->company  =  $req->company ;
                    }
                    $StripeAccount->mcc  =  $req->mcc ; 
                    $StripeAccount->photoIdFront  =   $photoIdFront; 
                    $StripeAccount->photoIdBack  = $photoIdBack; 
                    $StripeAccount->save();
                }    
                   
                
                

                $sellerData = Helper::removeNull($sellerData);
                
                $sellerDataAll  = Seller::select('sellers.*','countries.name as country','states.code as state')
                                        ->leftjoin('countries','countries.id','sellers.countryId') 
                                        ->leftjoin('states','states.id','sellers.state')
                                        ->where('sellers.id',$sellerId)->get()->first();
                $sellerData['profileImage'] = !empty($sellerData->profileImage) ?  Helper::getUrl().'vendor/vendor'.$sellerId.'/'.$sellerData->profileImage : '';
                $firstName = !empty($sellerDataAll->firstName) ? $sellerDataAll->firstName : '' ;
                $lastName = !empty($sellerDataAll->lastName) ? $sellerDataAll->lastName : '' ;
                $email = !empty($sellerDataAll->email) ? $sellerDataAll->email : '' ;
                $mobileNo = !empty($sellerDataAll->mobileNo) ? $sellerDataAll->mobileNo : '' ;
                $state = !empty($sellerDataAll->state) ? $sellerDataAll->state : '' ;
                $city = !empty($sellerDataAll->city) ? $sellerDataAll->city : '' ;
                $dob = !empty($sellerDataAll->dob) ? $sellerDataAll->dob : '' ;
                $location = !empty($sellerDataAll->location) ? $sellerDataAll->location : '' ;
                $postalCode = !empty($sellerDataAll->postalCode) ? $sellerDataAll->postalCode : '' ;
                $StripeAccount  = StripeAccount::where('sellerId',$sellerId)->get()->first();
                $bankAccount  = Account_information::where('sellerId',$sellerId)->get()->first();
                $businessType = !empty($StripeAccount->businessType) ? $StripeAccount->businessType : '' ;
                $accountId = !empty($StripeAccount->accountId) ? $StripeAccount->accountId : '' ;
                $personId = !empty($StripeAccount->personId) ? $StripeAccount->personId : '' ;
                $ssn = !empty($StripeAccount->ssn) ? $StripeAccount->ssn : '' ;
                $businessWebsite = !empty($StripeAccount->businessWebsite) ? $StripeAccount->businessWebsite : '' ;
                $industry = !empty($StripeAccount->industry) ? $StripeAccount->industry : '' ;
                $jobTitle = !empty($StripeAccount->jobTitle) ? $StripeAccount->jobTitle : '' ;
                $tax_id = !empty($StripeAccount->tax_id) ? $StripeAccount->tax_id : '' ;
                $company = !empty($StripeAccount->company) ? $StripeAccount->company : '' ;
                $photoIdFront = !empty($StripeAccount->photoIdFront) ? $StripeAccount->photoIdFront : '' ;
                $photoIdBack = !empty($StripeAccount->photoIdBack) ? $StripeAccount->photoIdBack : '' ;
                $accountNo = !empty($bankAccount->accountNo) ? $bankAccount->accountNo : '' ;
                $bankName = !empty($bankAccount->bankName) ? $bankAccount->bankName : '' ;
                $routingNumber = !empty($bankAccount->routingNumber) ? $bankAccount->routingNumber : '' ;
                $accountHolderName = !empty($bankAccount->accountHolderName) ? $bankAccount->accountHolderName : '' ;
            
              
                 
                if(!empty($StripeAccount)){
                    try {
                        // Use Stripe's library to make requests...
                    $resAccount =   $this->stripe->accounts->retrieve($accountId);
                    $StripeAccountStatus =  $resAccount->capabilities->transfers;
                    if($businessType == 'individual'){
                        if($StripeAccountStatus != 'active'){
                        $this->stripe->accounts->update( $accountId, [ 
                              "business_type" => $businessType,
                              [ "individual" => [
                                "first_name" => $firstName,
                                "last_name" => $lastName,
                                "phone" => $mobileNo,
                                "email" => $email,
                                "ssn_last_4" => $ssn,
                                "dob" => ["day" => date('d',strtotime($dob)),"month" => date('m',strtotime($dob)),"year" =>  date('Y',strtotime($dob))],
                                "address" => [ "city" =>  $city,"line1" => $location,"postal_code" => $postalCode,"state" =>  $state],
                                'verification' => ['document' => ['front' => $photoIdFront,'back' => $photoIdBack]],
                               
                            ]]]
                          );
                        }

                        if(empty($resAccount->individual->verification->document->front)){
                            $response =   $this->stripe->accounts->update( $accountId,
                             [ [ "individual" => ['verification' => ['document' => [ 'front' =>  $photoIdFront] ]]]]
                             );
                          } 
                        if(empty($resAccount->individual->verification->document->back)){
                            $response =   $this->stripe->accounts->update($accountId,
                             [ [ "individual" => ['verification' => ['document' => [ 'back' => $photoIdBack] ]]]]
                             );
                          } 
                          $response =   $this->stripe->accounts->update($accountId,[
                             'tos_acceptance' => ['date' => time(),'ip' => $_SERVER['REMOTE_ADDR']],
                             'external_account' => [
                                'object' => 'bank_account',
                                'country' => "US",
                                'currency' => "usd",
                                'account_holder_name' => $accountHolderName,
                                'account_holder_type' => "individual",
                                'routing_number' => $routingNumber,
                                'account_number' =>  $accountNo,
                            ],
                            "business_profile" => [ "mcc"=>  $industry,"url"=> $businessWebsite]
                             ]
                           ); 
                         

                          $StripeAccount->json = $response;
                          $StripeAccount->status = $response->capabilities->card_payments;;
                          $StripeAccount->save();
                          $sellerData['StripeAccountStatus'] = $response->capabilities->card_payments;
                          return response()->json(['data'=>$sellerData,'status'=>true,'message'=>'Update Successfully','token'=>''], $this->success);
          
                    }else if($businessType == 'company'){

                        if($StripeAccountStatus != 'active'){
                            $response =   $this->stripe->accounts->update($accountId,
                                    ["business_type" => "company",[
                                        "company" => ["name" => $company,"phone" => $mobileNo,"tax_id" =>  $tax_id,
                                        "address" => ["city" =>  $city,"line1" => $location,"postal_code" => $postalCode, "state" =>  $state, ],
                                    ]
                                    ], 
                                    ]
                                );
                        }
                          $response =   $this->stripe->accounts->update($accountId,[
                            'tos_acceptance' => ['date' => time(),'ip' => $_SERVER['REMOTE_ADDR']],
                            'external_account' => [
                               'object' => 'bank_account',
                               'country' => "US",
                               'currency' => "usd",
                               'account_holder_name' => $accountHolderName,
                               'account_holder_type' => "company",
                               'routing_number' => $routingNumber,
                               'account_number' =>  $accountNo,
                           ],
                           "business_profile" => [ "mcc"=>  $industry,"url"=> $businessWebsite]
                            ]
                          );
                          if(empty($personId)){
                          $person  =  $this->stripe->accounts->createPerson($accountId,
                            [ "first_name" => $firstName,"last_name" => $lastName,"phone" => $mobileNo,"email" => $email, "ssn_last_4" => $ssn,
                            "dob" => ["day" => date('d',strtotime($dob)), "month" => date('m',strtotime($dob)), "year" =>  date('Y',strtotime($dob)) ],
                            "address" => ["city" =>  $city,"line1" => $location, "postal_code" => $postalCode,"state" =>  $state,],
                            'relationship' => [ 'title' =>$jobTitle,"director"=> false,"executive"=> false,"owner"=> true, "percent_ownership"=> 100,"representative"=>true,]
                            ]
                            ); 
                           $StripeAccount->personId = $person->id;
                          }else{
                            if($StripeAccountStatus != 'active'){
                           $person =  $this->stripe->accounts->updatePerson($accountId,$personId,
                            ["first_name" => $firstName,"last_name" => $lastName,"phone" => $mobileNo,"email" => $email,"ssn_last_4" => $ssn,
                            "dob" => ["day" => date('d',strtotime($dob)),"month" => date('m',strtotime($dob)),"year" =>  date('Y',strtotime($dob))],
                            "address" => ["city" =>  $city,"line1" => $location,"postal_code" => $postalCode,"state" =>  $state,],
                            'relationship' => ['title' =>$jobTitle,"director"=> false,"executive"=> false,"owner"=> true,"percent_ownership"=> 100, "representative"=>true,
                                   ]
                               ]
                             ); 
                             if($person->verification->document->front){
                                $this->stripe->accounts->updatePerson($accountId,$personId,
                                [ "verification" =>  ["document"=>  ["front"=> $photoIdFront] ] ]
                               ); 
                             }
                            
                             if($person->verification->document->back){
                                $this->stripe->accounts->updatePerson($accountId,$personId,
                                [ "verification" =>  ["document"=>  [ "back"=> $photoIdBack] ] ]
                               ); 
                             }
                          
                            }
                          }
                         
                          $StripeAccount->json = $response;
                          $StripeAccount->save();
                          $sellerData['StripeAccountStatus'] = $response->capabilities->card_payments;
                          return response()->json(['data'=>$sellerData,'status'=>true,'message'=>'success','token'=>''], $this->success); 
                    }else{
                        $sellerData['StripeAccountStatus'] =  $StripeAccountStatus;
                        return response()->json(['data'=>$sellerData,'status'=>true,'message'=>'success','token'=>''], $this->success); 
                    }
                } catch(\Stripe\Exception\CardException $e) {
                    // Since it's a decline, \Stripe\Exception\CardException will be caught
                    return response()->json(['data'=>'','status'=>false,'message'=> $e->getError()->message,'token'=>''], $this->success); 
                   
                  } catch (\Stripe\Exception\RateLimitException $e) {
                    return response()->json(['data'=>'','status'=>false,'message'=> $e->getError()->message,'token'=>''], $this->success); 
                  } catch (\Stripe\Exception\InvalidRequestException $e) {
                    return response()->json(['data'=>'','status'=>false,'message'=> $e->getError()->message,'token'=>''], $this->success); 
                  } catch (\Stripe\Exception\AuthenticationException $e) {
                    return response()->json(['data'=>'','status'=>false,'message'=> $e->getError()->message,'token'=>''], $this->success); 
                  } catch (\Stripe\Exception\ApiConnectionException $e) {
                    return response()->json(['data'=>'','status'=>false,'message'=> $e->getError()->message,'token'=>''], $this->success); 
                  } catch (\Stripe\Exception\ApiErrorException $e) {
                    return response()->json(['data'=>'','status'=>false,'message'=> $e->getError()->message,'token'=>''], $this->success); 
                  } catch (Exception $e) {
                  return response()->json(['data'=>'','status'=>false,'message'=> $e->getError()->message,'token'=>''], $this->success); 
                  } */
             
                
               
               
               
            }
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Seller Token','token'=>''], $this->error); 
        }
            }else{
                return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Seller Token','token'=>''], $this->error); 
            }
    }

    public function projectImage(Request $req){
           
        $token = $req->bearerToken();
        $encode_token =  Helper::encode_token($token);
        if(!empty($encode_token)){
        $sellerId = $encode_token;
        $sellerData =  Seller::find($sellerId);  
        
        if(!empty($sellerData)){

            $validator = Validator::make($req->all(), [
                'projectImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048*5'
            ]);
            if ($validator->fails()) { 
                return response()->json(['data'=>'','status'=>false,'message'=>$validator->errors()->all(),'token'=>''], $this->success);            
            }else{
            $event = $req->event;
            $NumberAttendees = $req->numberAttendees;
            $LocationEvent = $req->locationEvent;
            $projectImage = new  Project_image;
            $projectImage->event = $event;
            $projectImage->numberAttendees = $NumberAttendees;
            $projectImage->locationEvent = $LocationEvent;
            $projectImage->image = '';

            if($req->file('projectImage'))
            {  
               $file = $req->file('projectImage');
               $name= time().'.'.$file->getClientOriginalName();
               $name = str_replace( " ", "-", trim($name) );
               $folder = Helper::imageUpload($name, $file,$folder="vendor/vendor".$sellerId."");
               $projectImage->sellerId = $sellerId;
               $projectImage->image = $name;
              
               
            }
            if(!empty($event) || !empty($NumberAttendees) || !empty($LocationEvent) || !empty($req->file('projectImage'))){
                $projectImage->save() ;
                return response()->json(['data'=>'','status'=>true,'message'=>'upload Successfully','token'=>''], $this->success);
            }else{
                return response()->json(['data'=>'','status'=>true,'message'=>'Please Fill One Field','token'=>''], $this->success);  
            }
           
            
          }
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
        }
    }else{
        return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
    }
    }


    public function projectImageEdit(Request $req){
           
        $token = $req->bearerToken();
        $id = $req->id;
        $sellerId =  Helper::encode_token($token);
        $sellerData =  Seller::find($sellerId);  
        if(!empty($sellerData)){
           $projectImage = Project_image::where('id',$id)->where('sellerId',$sellerId)->get()->first();
           $image  = !empty($projectImage->image) ? $projectImage->image : '';   
        if(!empty($projectImage)){

            $validator = Validator::make($req->all(), [
                'projectImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048*5'
            ]);
            if ($validator->fails()) { 
                return response()->json(['data'=>'','status'=>false,'message'=>$validator->errors()->all(),'token'=>''], $this->success);            
            }else{
            $event = $req->event;
            $numberAttendees = $req->numberAttendees;
            $locationEvent = $req->locationEvent;
        
            if(!empty( $event )){
                $projectImage->event = $event;
            }
            if(!empty( $NumberAttendees )){
                $projectImage->numberAttendees = $numberAttendees;
            }
            if(!empty( $locationEvent )){
                $projectImage->locationEvent = $locationEvent;
            }
    
            $projectImage->image = '';

            if($req->file('projectImage'))
            {  
                if(!empty($image)){
                    Helper::deleteImage("vendor/vendor".$sellerId."/".$image."");   
                }
               $file = $req->file('projectImage');
               $name= time().'.'.$file->getClientOriginalName();
               $name = str_replace( " ", "-", trim($name) );
               $folder = Helper::imageUpload($name, $file,$folder="vendor/vendor".$sellerId."");
               $projectImage->sellerId = $sellerId;
               $projectImage->image = $name;
              
               
            }
            $projectImage->save() ;
            return response()->json(['data'=>'','status'=>true,'message'=>'upload Successfully','token'=>''], $this->success); 
          }
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid id','token'=>''], $this->error); 
        }
    }else{
        return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
    }
    }

    public function projectImageDelete(Request $req){
          $projectImageId = $req->id;
          $projectImageData = Project_image::find($projectImageId);
          if(!empty($projectImageData)){
               $image = !empty($projectImageData->image) ? $projectImageData->image : '' ;
               $sellerId = !empty($projectImageData->sellerId) ? $projectImageData->sellerId : '' ;
               $path  = 'vendor/vendor'.$sellerId.'/'.$image;
               Helper::deleteImage($path );
               $projectImageData->delete();
               return response()->json(['data'=>'','status'=>true,'message'=>'Delete Successfully','token'=>''], $this->success);
          }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Id','token'=>''], $this->success); 
          }
    }


    public function change_password(Request $req) 
    {    
        $token = $req->bearerToken();
        $encode_token =  Helper::encode_token($token);
        if(!empty($encode_token)){
        $sellerId = $encode_token;
        $sellerData =  Seller::find($sellerId);  
        
        if(!empty($sellerData)){
           
            $validator = Validator::make($req->all(), [ 
                'currentPassword' => 'required|min:6|max:20',
                'newPassword' => 'required|min:6|max:20', 
                'confirmPassword' => 'required|same:newPassword', 
            ]);
        if ($validator->fails()) { 
            return response()->json(['data'=>'','status'=>false,'message'=>$validator->errors()->all(),'token'=>''], $this->success);                           
        }else{         
           
            $sellerPassword = !empty($sellerData['password']) ? $sellerData['password'] : '';
            $password  = Hash::make($req->confirmPassword);
            if(Hash::check($req->currentPassword,$sellerPassword)){ 
                 $updatePassword = Seller::find($sellerId);
                 $updatePassword->password = $password;
                 $updatePassword->save();
                return response()->json(['data'=>'','status'=>true,'message'=>'Password Successfully Update','token'=>''], $this->success); 
                
            }else{
                return response()->json(['data'=>'','status'=>false,'message'=>'Current Password Does Not Match','token'=>''], $this->success); 

            }
               
        }
    }else{
        return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
      }
    }else{
        return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
      }
    }



    public function sellerContract(Request $req){
           
        $token = $req->bearerToken();
        $encode_token =  Helper::encode_token($token);
        if(!empty($encode_token)){
        $sellerId = $encode_token;
        $sellerData =  Seller::find($sellerId);  
        
        if(!empty($sellerData)){

            $validator = Validator::make($req->all(), [
                'contract' => 'required|file|mimes:doc,pdf,docx|max:2048*20'
            ]);
            if ($validator->fails()) { 
                return response()->json(['data'=>'','status'=>false,'message'=>$validator->errors()->all(),'token'=>''], $this->success);            
            }else{
                $sellerContract = !empty($sellerData->contract) ? $sellerData->contract : '' ;

            if($req->file('contract'))
            {  
                if(!empty($sellerContract)){
                    $path = "vendor/vendor".$sellerId.'/'.$sellerContract;
                    Helper::deleteImage($path);
                }
               $file = $req->file('contract');
               $name= time().rand(999,1000).$file->getClientOriginalName();
               $name = str_replace( " ", "-", trim($name) );
               $folder = Helper::imageUpload($name, $file,$folder="vendor/vendor".$sellerId);
               $sellerData->contract = $name;
               $sellerData->save() ;
            }
            return response()->json(['data'=>'','status'=>true,'message'=>'upload Successfully','token'=>''], $this->success);
           }
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->success); 
        }
    }else{
        return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
    }
    }


    public function forget(Request $req){
        
        $validator = Validator::make($req->all(), [ 
            'email' => 'required|string|email',
        ]);
        if ($validator->fails()) { 
            return response()->json(['data'=>'','status'=>false,'message'=>$validator->errors()->all(),'token'=>''], $this->success);            
        }else{
            $email = $req->email;
            $sellerData = seller::where('email',$email)->get()->first();
            if(!empty($sellerData)){
                
                $token = Str::random(60).time();
                $sellerData->remember_token = $token;
                $sellerData->save();

                $url = env('SELLER_PANEL_LINK').'verify/'.$token;
                $subject = "TablePop password reset"; 
                $email_data = ['name' => $sellerData['firstName'].' '. $sellerData['lastName'],'user'=>'seller','email' => $sellerData['email'],'url'=>$url,'subject' => $subject];    
                Helper::send_mail('emailTemplate.password',$email_data);
                return response()->json(['data'=>'','status'=>true,'message'=>'Reset Mail Send Successfully','token'=>''], $this->success);            
                
            }else{
               return response()->json(['data'=>'','status'=>false,'message'=>'Invalid email address','token'=>''], $this->success); 
            }
        }

    }


    public function resetPassword(Request $req,$token) 
    {    
        $sellerData =  seller::where('remember_token',$token)->get()->first();
        if(!empty($sellerData)){
            $validator = Validator::make($req->all(), [ 
                'password' => 'required|min:6|max:20', 
                'c_password' => 'required|same:password', 
            ]);
            if ($validator->fails()) { 
                return response()->json(['data'=>'','status'=>false,'message'=>$validator->errors()->all(),'token'=>''], $this->success);                           
            }else{         
                    $password =  Hash::make($req->input('password'));
                    seller::where('remember_token',$token)->update(['password' => $password,'remember_token'=>'']);
                    return response()->json(['data'=>'','status'=>true,'message'=>'Psaaword Update Successfully','token'=>''], $this->success); 
            }
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
          }
        }


   
    public function createMilestones(Request $req,$questionnaireId){
        $token = $req->bearerToken();
        $sellerId =  Helper::encode_token($token);
        $sellerData =  Seller::where('id',$sellerId)->where('type',2)->get()->first();  
        if(!empty($sellerData)){
            $firstName = !empty($sellerData->firstName) ? $sellerData->firstName : '';
            $lastName = !empty($sellerData->lastName) ? $sellerData->lastName : '';

            $name = $firstName.' '.$lastName ;
            // $questionnaireId = $req->questionnaireId;
            $questionnaireData =  Questionnaire::find($questionnaireId);
            $CustomerId = $questionnaireData->customerId;
          
            if(!empty($questionnaireData))
            {
                     $TransactionData = Transaction::select(DB::raw('CASE  WHEN plannerplans.salePrice IS NULL THEN  SUM(plannerplans.regularPrice)  WHEN plannerplans.salePrice = ""  THEN  SUM(plannerplans.regularPrice) WHEN plannerplans.salePrice = 0  THEN  SUM(plannerplans.regularPrice) ELSE  SUM(plannerplans.salePrice)  END AS totalAmount'))
                                                     ->join('plannerplans','plannerplans.id','transactions.planId')
                                                     ->where('transactions.questionnaireId',$questionnaireId)
                                                     ->where('transactions.status',1)
                                                     ->get()->first();
                      $totalAmount =  $TransactionData['totalAmount'];
                      
                      $content = trim(file_get_contents("php://input"));
                      $json_decode = json_decode($content,true);
                      
                      $sum = 0;
                      $allintests = [];
                       if(!empty($json_decode)){
                       foreach($json_decode as $data) {
                          
                            $sum +=   $data['amount'];
                            $Milestones = new Milestone;
                            $Milestones->sellerId = $sellerId;
                            $Milestones->questionnaireId = $questionnaireId;
                            if($data['isCompleted'] == 1){
                                $Milestones->isCompleted = 1;
                                $Milestones->status = 1;
                            }else{
                                $Milestones->isCompleted = $data['isCompleted'];
                            }
                            
                            $Milestones->name = $data['name'];
                         //   $Milestones->vendorId = $data['vendorId'];
                         //   $Milestones->type = $data['type'];
                            $Milestones->description = $data['description'];
                            $Milestones->amount = $data['amount']; 
                           $Milestones->save(); 
                       }
                       if($data['isCompleted'] == 1){
                            $CustomeNotification = new Notification;
                            $CustomeNotification->customerId = $CustomerId;
                            $CustomeNotification->notification = ''.$name.' marked milestone '.$Milestones->name.' as completed.';
                            $CustomeNotification->type = 'customer';
                            $CustomeNotification->urlType = 'event';
                            $CustomeNotification->fromId = $sellerId;
                            $CustomeNotification->toId = $CustomerId;
                            $CustomeNotification->sendType = 'seller';
                            $CustomeNotification->questionnaireId = $questionnaireId;
                            $CustomeNotification->save(); 
                            Socket::notification($userId=$CustomerId,$userType='customer');
                       }
                      
                         $eventMilestoneAmount = Milestone::where('questionnaireId',$questionnaireId)->sum('amount');
                         $eventTransactionAmount = Transaction::where('questionnaireId',$questionnaireId)->where('status',1)->sum('totalAmount');
                         
                        /*  if($eventMilestoneAmount > $eventTransactionAmount){
                            $amount = $eventMilestoneAmount - $eventTransactionAmount;
                            $offerData = Offer::where('questionnaireId',$questionnaireId)->where('status',0)->where('type',1)->get()->first();
                            
                            if(!empty($offerData)){
                                $offer = $offerData;
                            }else{
                                $offer = new Offer;
                            }
                            
                            $offer->groupId = 0;
                            $offer->sellerId = $sellerId;
                            $offer->questionnaireId = $questionnaireId;
                            $offer->description = 'Custom Offer';
                            $offer->amount = $amount;
                            $offer->type = 1;
                            $offer->save() ;
                            
                            $NotificationData = Notification::where('offerId',$offer->id)->get()->first();
                            if(!empty($NotificationData)){
                                $Notification = $NotificationData;
                            }else{
                                $Notification = new Notification;
                            }
                            
                            $Notification->customerId = $CustomerId;
                            $Notification->type = 'customer';
                            $Notification->urltype = 'offer';
                            $Notification->questionnaireId = $questionnaireId;
                            $Notification->offerId = $offer->id;
                            $Notification->readStatus = 0;
                            $Notification->notification = 'New offer is create with amount of '.$amount.'';
                            $Notification->save() ;
          
          
                         } */

       
                          return response()->json(['data'=>'','status'=>true,'message'=>'Success','token'=>''], $this->success);  
                    
                     }else{
                        return response()->json(['data'=>'','status'=>false,'message'=>'Please Fill Required Fields','token'=>''], $this->success);  
                    }
                 }else{
                return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Questionnaire Id','token'=>''], $this->success); 
            }
       
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
        }
    }

   
    public function milestoneStatus(Request $req){
         $id = $req->id;
         $status = $req->status;
         $Milestones =  Milestone::find($id);
         if(!empty($Milestones)){
             $sellerId = !empty($Milestones->sellerId) ? $Milestones->sellerId : '' ;
             $MilestonesName = !empty($Milestones->name) ? $Milestones->name : '' ;
             $sellerData = Seller::find($sellerId);
             $questionnaireData =  Questionnaire::find($Milestones->questionnaireId);
             $tokenId = !empty($questionnaireData->tokenId) ? $questionnaireData->tokenId : '';
             $eventName = !empty($questionnaireData->eventName) ? $questionnaireData->eventName : '';
             $CustomerId = $questionnaireData->customerId;
             $customerData = Customer::find($CustomerId);
            
             $plannerfirstName = !empty($sellerData->firstName) ? $sellerData->firstName : '';
             $plannerlastName = !empty($sellerData->lastName) ? $sellerData->lastName : '';
             $plannerEmail = !empty($sellerData->email) ? $sellerData->email : '';
             $plannerName = $plannerfirstName.' '.$plannerlastName ;
              
             $customerfirstName = !empty($customerData->name) ? $customerData->name : '';
             $customerlastName = !empty($customerData->surname) ? $customerData->surname : '';
             $customermobile = !empty($customerData->mobile) ? $customerData->mobile : '';
             $customerName = $customerfirstName.' '.$customerlastName ;
             $Milestones->status = $status;
             $Milestones->save();

             $adminData  = Admin::where('type',1)->where('adminType',1)->get()->first();
             $adminName = !empty($adminData->name) ? $adminData->name : '' ;
             $adminMobile = !empty($adminData->mobile) ? $adminData->mobile : '' ;
             $adminEmail = !empty($adminData->email) ? $adminData->email : '' ;
             $adminId = !empty($adminData->id) ? $adminData->id : '' ;
         
            if($status == 1){
                if(!empty($customermobile)){
                    $mobile = $customermobile;
                    $msg1 = str_replace("{milestoneName}",$MilestonesName,Config::get('msg.markMilestone'));
                    $msg = str_replace("{eventId}",$tokenId,$msg1); 
                    Helper::sendMessage($msg,$mobile);
                 }
                $CustomeNotification = new Notification;
                $CustomeNotification->customerId = $CustomerId;
                $CustomeNotification->notification = ''.$plannerName.' marked milestone '.$MilestonesName.' as completed.';
                $CustomeNotification->type = 'customer';
                $CustomeNotification->questionnaireId = $Milestones->questionnaireId;
                $CustomeNotification->urlType = 'event';
                $CustomeNotification->fromId = $sellerId;
                $CustomeNotification->toId = $CustomerId;
                $CustomeNotification->sendType = 'seller';
                $CustomeNotification->save();
                Socket::notification($userId=$CustomerId,$userType='customer');

                $subject = " [ACTION REQUIRED] An event milestone is marked completed  ($eventName/$tokenId)"; 
                $email_data = ['email' =>$plannerEmail,'name'=>$plannerName,'user'=>'customer','subject' => $subject];  
                Helper::send_mail('emailTemplate.sellerMilestone',$email_data);
            }else if($status == 2){

                if(!empty($adminMobile)){
                    $mobile = $adminMobile;
                    $msg = Config::get('msg.markMilestone');  
                    Helper::sendMessage($msg,$mobile);
                 }

                $CustomeNotification = new Notification;
                $CustomeNotification->customerId = $CustomerId;
                $CustomeNotification->notification = ''.$customerName.' marked milestone '.$MilestonesName.' as completed. You can make payment to '.$plannerName.'';
                $CustomeNotification->urlType = 'event';
                $CustomeNotification->questionnaireId = $Milestones->questionnaireId;
                $CustomeNotification->type = 'admin';
                $CustomeNotification->urlType = 'event';
                $CustomeNotification->fromId = $adminId;
                $CustomeNotification->toId = $CustomerId;
                $CustomeNotification->sendType = 'seller';
                $CustomeNotification->save();
                Socket::notification($userId='',$userType='admin');

                $subject = " [ACTION REQUIRED] An event milestone is marked completed  ($eventName/$tokenId)"; 
                $email_data = ['email' =>$adminEmail,'name'=>$adminName,'user'=>'customer','subject' => $subject];  
                Helper::send_mail('emailTemplate.adminMilestone',$email_data);
            }
            return response()->json(['data'=>'','status'=>true,'message'=>'Success','token'=>''], $this->success);  
         }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Id','token'=>''], $this->success); 
         }
    }


    public function milestones(Request $req){
        $token = $req->bearerToken();
        $sellerId =  Helper::encode_token($token);
        $sellerData =  Seller::find($sellerId);  
        if(!empty($sellerData)){
            $eventId = $req->questionnaireId;
            $questionnairData = Questionnaire::where('id',$eventId)->get()->first();
            if($questionnairData){
            $Milestones =  Milestone::where('sellerId',$sellerId)->where('questionnaireId',$eventId)->get()->toArray();
            if(!empty($Milestones)){
                return response()->json(['data'=>$Milestones,'status'=>true,'message'=>'Milestones','token'=>''], $this->success); 
       
            }else{
               return response()->json(['data'=>'','status'=>true,'message'=>'Data Empty','token'=>''], $this->success); 
       
            }
            
           return response()->json(['data'=>'','status'=>true,'message'=>'Success','token'=>''], $this->success);  
            }else{
                return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Id','token'=>''], $this->success); 
            }
        }else{
           return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
        }
   }




   public function sellerTransactionEvents(Request $req){
    $imageUrl = Helper::getUrl();
    $token = $req->bearerToken();
    $sellerId =  Helper::encode_token($token);
    $sellerData =  Seller::find($sellerId);  
    if(!empty($sellerData)){
        $sellerType = !empty($sellerData->type) ? $sellerData->type : '' ;
        $pageNo = $req->pageNo;
        $limit = !empty(env('PR_PAGE_DATA')) ? env('PR_PAGE_DATA') : 10 ;
        $start = !empty($pageNo) ? ($pageNo - 1)*$limit : 0 ;
            $questionnair = Questionnaire::select('questionnaires.id','questionnaires.tokenId','questionnaires.eventName','questionnaires.confirmationPartyPlanner as address','questionnaires.farEventDate',DB::raw('CONCAT(c.firstName," ",c.lastName) as name'),DB::raw('(CASE WHEN c.profileImage = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",c.id,"/", c.profileImage) END) AS profileImage'),DB::raw('SUM(t.amount) as totalAmount'))
                                        ->leftjoin('milestones as t', 'questionnaires.id', '=', 't.questionnaireId')
                                        ->leftjoin('sellers as c','c.id','t.sellerId')
                                        ->groupBy('t.questionnaireId')
                                        ->where('t.status', '=', 3);
           if($sellerType == 1){
               $dataEvent = $questionnair->where('t.vendorId', '=', $sellerId)->where('t.type',1);
           }else{
              $dataEvent = $questionnair->where('t.sellerId', '=', $sellerId)->where('t.type',0)->where('t.vendorId',0);
           }
            $data['totalPage'] = ceil($dataEvent->count()/$limit); 
            $questionnairData = $dataEvent->offset($start)->limit($limit)->get()->toArray(); 
            $data['event'] = $questionnairData; 
  
            if(!empty($questionnairData)){
                $data = Helper::removeNull($data);
                       return response()->json(['data'=>$data,'status'=>true,'message'=>'Event List','token'=>''], $this->success); 
            }else{
                return response()->json(['data'=>'','status'=>true,'message'=>'Data List Empty','token'=>''], $this->success); 
            }
    }else{
        return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
    }
}


public function sellerIdTransactionEventDatils(Request $req){
    $imageUrl = Helper::getUrl();
    $token = $req->bearerToken();
    $sellerId =  Helper::encode_token($token);
    $sellerData =  Seller::find($sellerId);  
    if(!empty($sellerData)){
        $pageNo = $req->pageNo;
        $eventId = $req->eventId;
        $questionnairData = Questionnaire::where('id',$eventId)->get()->first();
        if(!empty($questionnairData)){
        $limit = !empty(env('PR_PAGE_DATA')) ? env('PR_PAGE_DATA') : 10 ;
        $start = !empty($pageNo) ? ($pageNo - 1)*$limit : 0 ;
            $questionnair = Milestone::select('milestones.*',DB::raw('(CASE WHEN milestones.invoice = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",c.id,"/", milestones.invoice) END) AS invoice'),'questionnaires.tokenId','questionnaires.customerId','questionnaires.eventName','questionnaires.confirmationPartyPlanner as address','questionnaires.farEventDate',DB::raw('CONCAT(c.firstName," ",c.lastName) as sellerName'),DB::raw('(CASE WHEN c.profileImage = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",c.id,"/", c.profileImage) END) AS profileImage'))
                                        ->leftjoin('sellers as c','c.id','milestones.sellerId')
                                      //  ->leftjoin('plannerplans as p','p.id','transactions.planId')
                                      //  ->leftjoin('vendor_products as vp','vp.id','transactions.productId')
                                       // ->leftjoin('offers as of','of.id','transactions.offerId')
                                        ->leftjoin('questionnaires', 'questionnaires.id', '=', 'milestones.questionnaireId')
                                        ->where('milestones.status', '=', 3)
                                        ->where('milestones.questionnaireId', '=',$eventId)
                                        ->where('milestones.sellerId', '=',$sellerId);
            $questionnairData = $questionnair->offset($start)->limit($limit)->get()->toArray(); 
            $data['event'] = $questionnairData; 
            $data['totalPage'] = ceil($questionnair->count()/$limit); 
            if(!empty($questionnairData)){
                $data = Helper::removeNull($data);
                       return response()->json(['data'=>$data,'status'=>true,'message'=>'Event List','token'=>''], $this->success); 
            }else{
                return response()->json(['data'=>'','status'=>true,'message'=>'Data List Empty','token'=>''], $this->success); 
            }
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Event Id','token'=>''], $this->success);
        }
    }else{
        return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
    }
}
    
public function moodboards(Request $req){
    $imageUrl = Helper::getUrl();
    $token = $req->bearerToken();
    $sellerId =  Helper::encode_token($token);
    $sellerData =  Seller::find($sellerId);  
    if(!empty($sellerData)){
        $pageNo = $req->pageNo;
        $eventId = $req->eventId;
        $limit = !empty(env('PR_PAGE_DATA')) ? env('PR_PAGE_DATA') : 10 ;
        $start = !empty($pageNo) ? ($pageNo - 1)*$limit : 0 ;
        $data =  Moodboard::select("*",DB::raw('(CASE WHEN previewImage = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor","'.$sellerId.'","/", previewImage) END) AS previewImage'))->where('sellerId',$sellerId)->where('eventId',$eventId);
        $dataall = $data->offset($start)->limit($limit)->get()->toArray(); 
        $count = $data->count();

        $response['moodboards'] = $dataall;
        $response['totalPage'] = ceil($count/$limit);
       if(!empty($dataall)){
        return response()->json(['data'=>$response,'status'=>true,'message'=>'success','token'=>''], $this->success); 
    } else{
        return response()->json(['data'=>'','status'=>true,'message'=>'Data Empty','token'=>''], $this->success); 
    }      
       
    }else{
        return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
    }
  }


  public function moodboardsDetails(Request $req){
    $imageUrl = Helper::getUrl();
    $token = $req->bearerToken();
    $sellerId =  Helper::encode_token($token);
    $sellerData =  Seller::find($sellerId);  
    if(!empty($sellerData)){
            $moodboardId = $req->moodboardId;
            $moodboardData = Moodboard::select("*",DB::raw('(CASE WHEN previewImage = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor","'.$sellerId.'","/", previewImage) END) AS previewImage'))->where('id',$moodboardId)->where('sellerId',$sellerId)->get()->first();
            if(!empty($moodboardData )){
            $moodboardData['moodboardimage'] = MoodboardImage::select("*",DB::raw('(CASE WHEN image = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor","'.$sellerId.'","/", image) END) AS image'))->where('moodboardId',$moodboardId)->get()->toArray();
              return response()->json(['data'=>$moodboardData,'status'=>true,'message'=>'success','token'=>''], $this->success); 
          }else{
              return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Id','token'=>''], $this->success); 
          }
    }else{
        return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
    }
  }

  public function moodboardsAdd(Request $req){
    $token = $req->bearerToken();
    $sellerId =  Helper::encode_token($token);
    $sellerData =  Seller::find($sellerId);  
    if(!empty($sellerData)){
          $eventId = $req->eventId;
          $eventData = Questionnaire::find($eventId);

          if(!empty($eventData)){
            $validator = Validator::make($req->all(), [
                'name' => 'required|string',
                'description' => 'required|string',
                'previewImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048*10',
            ]);
    
            if ($validator->fails()) { 
                return response()->json(['data'=>'','status'=>false,'message'=>$validator->errors()->all(),'token'=>''], $this->success);            
            }else{
               $data = new Moodboard;
               $data->sellerId =  $sellerId;
               $data->eventId =  $eventId;
               $data->description =  $req->description;
               $data->name =  $req->name;
               $data->previewImage = '';
               if ($req->hasFile('previewImage')){
                    $image = $req->file('previewImage');
                    $imageName = time().'.'.$req->previewImage->extension(); 
                    $imageName = str_replace( " ", "-", trim($imageName) ); 
                    $folder = Helper::imageUpload($imageName, $image,$folder="vendor/vendor".$sellerId."");
                    $data->previewImage = $imageName;
               }  
               $data->save();
               return response()->json(['data'=>'','status'=>true,'message'=>'success','token'=>''], $this->success); 
            }
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Event Id','token'=>''], $this->success); 
        }
    }else{
        return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
    }
  }


  public function moodboardsEdit(Request $req){
    $token = $req->bearerToken();
    $sellerId =  Helper::encode_token($token);
    $sellerData =  Seller::find($sellerId);  
    if(!empty($sellerData)){
          $moodboardId = $req->moodboardId;
          $moodboardData = Moodboard::where('id',$moodboardId)->where('sellerId',$sellerId)->get()->first();
          $previewImage = !empty($moodboardData->previewImage) ? $moodboardData->previewImage : '' ;
          if(!empty($moodboardData)){
            $validator = Validator::make($req->all(), [
                'name' => 'required|string',
                'description' => 'required|string',
                'previewImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048*10',
            ]);
    
            if ($validator->fails()) { 
                return response()->json(['data'=>'','status'=>false,'message'=>$validator->errors()->all(),'token'=>''], $this->success);            
            }else{
               
               $moodboardData->description =  $req->description;
               $moodboardData->name =  $req->name;
               $moodboardData->previewImage = '';
               if ($req->hasFile('previewImage')){
                if(!empty($previewImage)){
                    Helper::deleteImage("vendor/vendor".$sellerId."/".$previewImage."");
                 }
                    $image = $req->file('previewImage');
                    $imageName = time().'.'.$req->previewImage->extension(); 
                    $imageName = str_replace( " ", "-", trim($imageName) ); 
                    $folder = Helper::imageUpload($imageName, $image,$folder="vendor/vendor".$sellerId."");
                    $moodboardData->previewImage = $imageName;
               }  
               $moodboardData->save();
               return response()->json(['data'=>'','status'=>true,'message'=>'success','token'=>''], $this->success); 
            }
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Id','token'=>''], $this->success); 
        }
    }else{
        return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
    }
  }


  public function moodboardsDelete(Request $req){
    $token = $req->bearerToken();
    $sellerId =  Helper::encode_token($token);
    $sellerData =  Seller::find($sellerId);  
    if(!empty($sellerData)){
          $moodboardId = $req->moodboardId;
          $moodboardData = Moodboard::where('id',$moodboardId)->where('sellerId',$sellerId)->get()->first();
          $previewImage = !empty($moodboardData->previewImage) ? $moodboardData->previewImage : '' ;
          if(!empty($moodboardData)){
            if(!empty($previewImage)){
                Helper::deleteImage("vendor/vendor".$sellerId."/".$previewImage."");

             }
             $moodboardData->delete();
            return response()->json(['data'=>'','status'=>true,'message'=>'success','token'=>''], $this->success); 
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Id','token'=>''], $this->success); 
        }
    }else{
        return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
    }
  }
  
  public function moodboardsImageAdd(Request $req){
    $token = $req->bearerToken();
    $sellerId =  Helper::encode_token($token);
    $sellerData =  Seller::find($sellerId);  
    if(!empty($sellerData)){
          $moodboardId = $req->moodboardId;
          $moodboardData = Moodboard::where('id',$moodboardId)->where('sellerId',$sellerId)->get()->first();
          $previewImage = !empty($moodboardData->previewImage) ? $moodboardData->previewImage : '' ;
          if(!empty($moodboardData)){
           
                $validator = Validator::make($req->all(), [
                    'image' => 'required',
                    'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048*10',
                ]);
        
                if ($validator->fails()) { 
                    return response()->json(['data'=>'','status'=>false,'message'=>$validator->errors()->all(),'token'=>''], $this->success);            
                }else{
                    if($req->hasfile('image'))
                    {
                       foreach($req->file('image') as $image)
                       {
                         
                          $name= $image->getClientOriginalName();
                          $name = str_replace( " ", "-", trim($name) );
                          $folder = Helper::imageUpload($name, $image,$folder="vendor/vendor".$sellerId.""); 
                          $images = new MoodboardImage;
                          $images->image = $name;
                          $images->moodboardId = $moodboardId;
                          $images->save();
                       }
                    }
                }
            return response()->json(['data'=>'','status'=>true,'message'=>'success','token'=>''], $this->success); 
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Id','token'=>''], $this->success); 
        }
    }else{
        return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
    }
  }


  public function moodboardsImageDelete(Request $req){
    $token = $req->bearerToken();
    $sellerId =  Helper::encode_token($token);
    $sellerData =  Seller::find($sellerId);  
    if(!empty($sellerData)){
          $imageId = $req->imageId;
          $moodboardData = MoodboardImage::find($imageId);
          $image = !empty($moodboardData->image) ? $moodboardData->image : '' ;
          if(!empty($moodboardData)){
            if(!empty($image)){
                Helper::deleteImage("vendor/vendor".$sellerId."/".$image."");
             }
             $moodboardData->delete();
               
            return response()->json(['data'=>'','status'=>true,'message'=>'success','token'=>''], $this->success); 
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Id','token'=>''], $this->success); 
        }
    }else{
        return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
    }
  }


/*   public function stripeAccount(Request $req){
    $token = $req->bearerToken();
    $sellerId =  Helper::encode_token($token);
    $sellerData =  Seller::select('sellers.*','countries.name as country','states.name as state')
                           ->leftjoin('countries','countries.id','sellers.countryId') 
                           ->leftjoin('states','states.id','sellers.state')
                           ->where('sellers.id',$sellerId)->get()->first();  
    if(!empty($sellerData)){
         $firstName = !empty($sellerData->firstName) ? $sellerData->firstName : '' ;
         $lastName = !empty($sellerData->lastName) ? $sellerData->lastName : '' ;
         $email = !empty($sellerData->email) ? $sellerData->email : '' ;
         $mobileNo = !empty($sellerData->mobileNo) ? $sellerData->mobileNo : '' ;
         $state = !empty($sellerData->state) ? $sellerData->state : '' ;
         $city = !empty($sellerData->city) ? $sellerData->city : '' ;
         $dob = !empty($sellerData->dob) ? $sellerData->dob : '' ;
         $location = !empty($sellerData->location) ? $sellerData->location : '' ;
         $postalCode = !empty($sellerData->postalCode) ? $sellerData->postalCode : '' ;
         $StripeAccount  = StripeAccount::where('sellerId',$sellerId)->get()->first();
         $bankAccount  = Account_information::where('sellerId',$sellerId)->get()->first();
         $businessType = !empty($StripeAccount->businessType) ? $StripeAccount->businessType : '' ;
         $accountId = !empty($StripeAccount->accountId) ? $StripeAccount->accountId : '' ;
         $personId = !empty($StripeAccount->personId) ? $StripeAccount->personId : '' ;
         $ssn = !empty($StripeAccount->ssn) ? $StripeAccount->ssn : '' ;
         $businessWebsite = !empty($StripeAccount->businessWebsite) ? $StripeAccount->businessWebsite : '' ;
         $industry = !empty($StripeAccount->industry) ? $StripeAccount->industry : '' ;
         $jobTitle = !empty($StripeAccount->jobTitle) ? $StripeAccount->jobTitle : '' ;
         $tax_id = !empty($StripeAccount->tax_id) ? $StripeAccount->tax_id : '' ;
         $company = !empty($StripeAccount->company) ? $StripeAccount->company : '' ;
         $photoIdFront = !empty($StripeAccount->photoIdFront) ? $StripeAccount->photoIdFront : '' ;
         $photoIdBack = !empty($StripeAccount->photoIdBack) ? $StripeAccount->photoIdBack : '' ;
         $accountNo = !empty($bankAccount->accountNo) ? $bankAccount->accountNo : '' ;
         $bankName = !empty($bankAccount->bankName) ? $bankAccount->bankName : '' ;
         $routingNumber = !empty($bankAccount->routingNumber) ? $bankAccount->routingNumber : '' ;
         $accountHolderName = !empty($bankAccount->accountHolderName) ? $bankAccount->accountHolderName : '' ;
         $state = 'AL';
         try {
            // Use Stripe's library to make requests...
        
         if(!empty($StripeAccount)){
             if($businessType == 'individual'){
                $response =   $this->stripe->accounts->update(
                    $accountId,
                   [ "business_type" => $businessType,
                      'tos_acceptance' => [
                         'date' => time(),
                         'ip' => $_SERVER['REMOTE_ADDR'], // Assumes you're not using a proxy
                       ],
                       [ "individual" => [
                         "first_name" => $firstName,
                         "last_name" => $lastName,
                         "phone" => $mobileNo,
                         "email" => $email,
                         "ssn_last_4" => $ssn,
                         "dob" => [
                             "day" => date('d',strtotime($dob)),
                             "month" => date('m',strtotime($dob)),
                             "year" =>  date('Y',strtotime($dob))
                         ],
                         "address" => [
                             "city" =>  $city,
                             "line1" => $location,
                             "postal_code" => $postalCode,
                             "state" =>  $state,
                         ],
                         'verification' => [
                            'document' => [
                              'front' => $photoIdFront,
                              'back' => $photoIdBack,
                            ],
                          ],
                        
                     ]
                       ],'external_account' => [
                         'object' => 'bank_account',
                         'country' => "US",
                         'currency' => "usd",
                         'account_holder_name' => $accountHolderName,
                         'account_holder_type' => 'company',
                         'routing_number' => $routingNumber,
                         'account_number' => $accountHolderName,
                     ],
                     "business_profile" => [
                         "mcc"=>  $industry,
                         "url"=> $businessWebsite
                     ],
                   ]
                   );
                   $StripeAccount->json = $response;
                   $StripeAccount->save();
                   return response()->json(['data'=>'','status'=>true,'message'=>'success','token'=>''], $this->success);  
             }else if($businessType == 'company'){
                $response =   $stripe->accounts->update(
                    $accountId,
                     [
                     "business_type" => "company",
                     'tos_acceptance' => [
                         'date' => time(),
                         'ip' => $_SERVER['REMOTE_ADDR'], // Assumes you're not using a proxy
                       ],
                       [
                         "company" => [
                         "name" => $company,
                        
                         "phone" => $mobileNo,
                         "tax_id" =>  $tax_id,
                       
                         "address" => [
                            "city" =>  $city,
                            "line1" => $location,
                            "postal_code" => $postalCode,
                            "state" =>  $state,
                         ],
                        ]
                       ], 
                       'external_account' => [
                        'object' => 'bank_account',
                         'country' => "US",
                         'currency' => "usd",
                         'account_holder_name' => $accountHolderName,
                         'account_holder_type' => $businessType,
                         'routing_number' => $routingNumber,
                         'account_number' => $accountHolderName,
                     ],
                     "business_profile" => [
                        "mcc"=>  $industry,
                        "url"=> $businessWebsite
                     ],
                   ]
                   ); 
                   if(!empty($personId)){
                    $stripe->accounts->createPerson(
                        $accountId,
                        $personId,
                        [   
                        "first_name" => $firstName,
                        "last_name" => $lastName,
                        "phone" => $mobileNo,
                        "email" => $email,
                        "ssn_last_4" => $ssn,
                        "dob" => [
                            "day" => date('d',strtotime($dob)),
                            "month" => date('m',strtotime($dob)),
                            "year" =>  date('Y',strtotime($dob))
                        ],
                        "address" => [
                            "city" =>  $city,
                            "line1" => $location,
                            "postal_code" => $postalCode,
                            "state" =>  $state,
                        ],'relationship' => [
                            'title' =>'ss',
                            "director"=> false,
                            "executive"=> false,
                            "owner"=> true,
                            "percent_ownership"=> 100,
                            "representative"=>true,
                        ]
            
                        ]
                      ); 

                      $person = '';
                   }else{
                    $person =  $stripe->accounts->createPerson(
                        $accountId,
                        [   
                            "first_name" => $firstName,
                            "last_name" => $lastName,
                            "phone" => $mobileNo,
                            "email" => $email,
                            "ssn_last_4" => $ssn,
                            "dob" => [
                                "day" => date('d',strtotime($dob)),
                                "month" => date('m',strtotime($dob)),
                                "year" =>  date('Y',strtotime($dob))
                            ],
                            "address" => [
                                "city" =>  $city,
                                "line1" => $location,
                                "postal_code" => $postalCode,
                                "state" =>  $state,
                            ],'relationship' => [
                                'title' =>'ss',
                                "director"=> false,
                                "executive"=> false,
                                "owner"=> true,
                                "percent_ownership"=> 100,
                                "representative"=>true,
                            ]
                        ]
                      ); 


                   }
                  
                  $StripeAccount->json = $response;
                  if(!empty($person)){
                    $StripeAccount->personId = $person->id;
                  }
                   $StripeAccount->save();
                   return response()->json(['data'=>'','status'=>true,'message'=>'success','token'=>''], $this->success); 
             }
         }
        } catch(\Stripe\Exception\CardException $e) {
            // Since it's a decline, \Stripe\Exception\CardException will be caught
            return response()->json(['data'=>'','status'=>false,'message'=> $e->getError()->message,'token'=>''], $this->success); 
           
          } catch (\Stripe\Exception\RateLimitException $e) {
            return response()->json(['data'=>'','status'=>false,'message'=> $e->getError()->message,'token'=>''], $this->success); 
          } catch (\Stripe\Exception\InvalidRequestException $e) {
            return response()->json(['data'=>'','status'=>false,'message'=> $e->getError()->message,'token'=>''], $this->success); 
          } catch (\Stripe\Exception\AuthenticationException $e) {
            return response()->json(['data'=>'','status'=>false,'message'=> $e->getError()->message,'token'=>''], $this->success); 
          } catch (\Stripe\Exception\ApiConnectionException $e) {
            return response()->json(['data'=>'','status'=>false,'message'=> $e->getError()->message,'token'=>''], $this->success); 
          } catch (\Stripe\Exception\ApiErrorException $e) {
            return response()->json(['data'=>'','status'=>false,'message'=> $e->getError()->message,'token'=>''], $this->success); 
          } catch (Exception $e) {
          return response()->json(['data'=>'','status'=>false,'message'=> $e->getError()->message,'token'=>''], $this->success); 
          }

         
     }else{
        return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
    }
  } */

  public function calendar(Request $req){
    $token = $req->bearerToken();
    $sellerId =  Helper::encode_token($token);
    $sellerData =  Seller::find($sellerId);  
    if(!empty($sellerData)){
        $sellerType = !empty($sellerData->type) ? $sellerData->type : '' ;
        if($sellerType == 1){
               $calendar = Cart::select('q.id','carts.quantity','p.name','q.eventName','q.farEventDate','q.tokenId as eventId')
                                    ->join('vendor_products as p','p.id','carts.productId')
                                    ->join('questionnaires as q','q.id','carts.eventId')
                                    ->where('carts.status',1)
                                    ->orwhere('carts.status',2)
                                    ->where('q.farEvent','exact')
                                    ->get()->toArray();
        }else if($sellerType == 2){
            $calendar = Questionnaire::select('id','eventName','farEventDate','tokenId as eventId','status')
                                       ->where('farEvent','exact')
                                        ->whereRaw("find_in_set($sellerId,sellerId)")
                                        ->get()->toArray();
        }
         if(!empty($calendar)){
            return response()->json(['data'=>$calendar,'status'=>true,'message'=>'success','token'=>''], $this->success); 
         }else{
            return response()->json(['data'=>'','status'=>true,'message'=>'Data Empty','token'=>''], $this->success); 
         }
    }else{
        return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
    }
  }
  

  public function stripeAccount(Request $req){
    $token = $req->bearerToken();
    $sellerId =  Helper::encode_token($token);
    $sellerData =  Seller::find($sellerId);  
    if(!empty($sellerData)){

        $authToken = $req->authToken;
        try {
        if(!empty($authToken)){
            $response = \Stripe\OAuth::token([
                'grant_type' => 'authorization_code',
                'code' => $authToken,
              ]);
            $connected_account_id = $response->stripe_user_id;
            $resAccount =   $this->stripe->accounts->retrieve($connected_account_id);
            $StripeAccountStatus =  $resAccount->capabilities->transfers;
            $StripeAccount  = StripeAccount::where('sellerId',$sellerId)->where('accountId',$connected_account_id)->get()->first();
            if(empty($StripeAccount)){
                $account = new StripeAccount;
                $account->accountId = $connected_account_id;
                $account->sellerId = $sellerId;
                $account->status = $StripeAccountStatus;
                $account->save();
            }
            return response()->json(['data'=>'','status'=>true,'message'=>'Success','token'=>''], $this->success); 
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Auth Token','token'=>''], $this->success); 
        }
        }catch(\Stripe\Exception\CardException $e) {
            return response()->json(['data'=>'','status'=>false,'message'=> $e->getJsonBody()['error_description'],'token'=>''], $this->success); 
          } catch (\Stripe\Exception\RateLimitException $e) {
            return response()->json(['data'=>'','status'=>false,'message'=> $e->getJsonBody()['error_description'],'token'=>''], $this->success); 
          } catch (\Stripe\Exception\InvalidRequestException $e) {
            return response()->json(['data'=>'','status'=>false,'message'=> $e->getJsonBody()['error_description'],'token'=>''], $this->success); 
          } catch (\Stripe\Exception\AuthenticationException $e) {
              return response()->json(['data'=>'','status'=>false,'message'=> $e->getJsonBody()['error_description'],'token'=>''], $this->success); 
          } catch (\Stripe\Exception\ApiConnectionException $e) {
            return response()->json(['data'=>'','status'=>false,'message'=> $e->getJsonBody()['error_description'],'token'=>''], $this->success); 
          } catch (\Stripe\Exception\ApiErrorException $e) {
            return response()->json(['data'=>'','status'=>false,'message'=> $e->getJsonBody()['error_description'],'token'=>''], $this->success); 
          } catch (Exception $e) {
            return response()->json(['data'=>'','status'=>false,'message'=> $e->getJsonBody()['error_description'],'token'=>''], $this->success); 
          }
    }else{
        return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
    }
  }

}
