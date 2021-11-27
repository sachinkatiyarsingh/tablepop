<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Validator;
use DB;
use Hash;
use Helper;
use Str;
use SpacesConnect;
use Storage;
use Stripe;
use Config;
use App\Planner;
use App\Seller;
use App\Project_image;
use Stripe\Charge;
use Stripe\Account;
use App\StripeAccount;
use Aws\S3\S3Client;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem;
use App\MessageGroup;
use App\Admin;
use App\Event;
use App\Review;
use App\PlannerPlan;
 
class PlannerController extends Controller
{
    public function __construct()
    {
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
    }
    
    public function index(Request $req){
        $expertise = $req->expertise;
        $experience = $req->experience;
        $skill = $req->skill;
        $location = $req->location;

        $id = Session::get('id');
        /* $planner = Seller::select('sellers.*','planners.experiencePlanning',DB::raw('round(AVG(r.rating),0) as rating'))
                        ->leftjoin('planners','sellers.id','planners.plannerId')
                        ->leftjoin('reviews as r','sellers.id','r.sellerId')
                        ->where('sellers.type',2); */

         $planner = Seller::select('sellers.*','planners.experiencePlanning', 'pr.ratting_cnt',DB::raw('round(pr.seller_rate/pr.ratting_cnt) as rating'))
                        ->leftjoin('planners','sellers.id','planners.plannerId')
                         ->leftjoin(DB::raw('(select pr1.sellerId, sum(pr1.rating) as seller_rate, count(1) as ratting_cnt from reviews pr1 group by pr1.sellerId) as pr'), function($join){
                            $join->on('pr.sellerId', '=', 'sellers.id');
                            })
                        ->where('sellers.type',2); 
                       
        if(!empty($expertise)){
            $data = $planner->whereRaw("find_in_set($expertise,planners.experiencePlanning)")
                            ->latest()->paginate(10);
   
        }else if(!empty($location)){
            $data = $planner->where('sellers.location', 'LIKE', '%' . $location . '%')
                                ->latest()->paginate(10);
        }else if(!empty($experience)){
            $data = $planner->where('planners.workingindustry',$experience)
                                ->latest()->paginate(10);
        }else if(!empty($skill)){
            $data = $planner->where('planners.personalityPlanners', 'LIKE', '%' . $skill . '%')
                       ->latest()->paginate(10);
        }else{
          
            $data = $planner ->latest()->paginate(10);
        }
     
         $event = Event::all();
        return view('planner.index',['data'=>$data,'event'=>$event]);
   }
   
   public function add(Request $req){
       
    if($req->isMethod('post')){
        $id = Session::get('id'); 
        $req->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'userName' => 'required|string|unique:sellers|alpha_num',
            'email' => 'required|string|email|unique:sellers',
            'phoneNo' => 'nullable|unique:sellers,mobileNo',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048*5',
           
        ]);
 
        $mobile = !empty($req->phoneNo) ? $req->phoneNo : '';
      //  $password = '123456';//Str::random(6);
        $token = Str::random(60);
        
        if(!empty($req->free)){
            $free = 1;
        }else{
            $free = 0;
        }
       
        $data = new Seller;
        $data->staffId = $id;
        $data->firstName = $req->firstname;
        $data->lastName = $req->lastname;
        $data->userName = $req->userName;
        $data->email = $req->email;
        $data->mobileNo =  $mobile;
        $data->location =  '';
        $data->latitude =  '';
        $data->longitude =  '';
        $data->remember_token = $token;
        $data->profileImage = '';
        $data->type =  2;
        $data->status = 1;
        $data->free = $free;
        $data->password = '';
        $data->save();
        $update = Seller::find($data->id);
        if ($req->hasFile('image')) {
            if($req->file('image')->isValid()) {  
                $image = $req->file('image');
                $imageName = time().'.'.$image->getClientOriginalExtension();
                $folder = Helper::imageUpload($imageName, $image,$folder="vendor/vendor".$data->id."");
                $update->profileImage = $imageName;
            }
        }
        $update->save();
       /*  $response =    $this->stripe->accounts->create(['type' => 'custom','country' => 'US','email' => $data->email,'requested_capabilities' => ['card_payments','transfers',],]);
        $StripeAccount = new StripeAccount;
        $StripeAccount->sellerId = $data->id;
        $StripeAccount->accountId = $response->id;
        $StripeAccount->json = $response;
        $StripeAccount->status = $response->capabilities->card_payments;
        $StripeAccount->save(); */


        $adminData = Admin::where('type',1)->get()->first();
        $adminId = !empty($adminData->id) ? $adminData->id : 0 ;
        $MessageGroupData = MessageGroup::where('sellerId',$data->id)->where('type',1)->get()->first();
        if(empty($MessageGroupData)){
            $MessageGroup = new MessageGroup;
            $MessageGroup->sellerId = $data->id;
            $MessageGroup->adminId = $adminId;
            $MessageGroup->type = 1;
            $MessageGroup->save();
        }

        if($free == 1){
            $PlannerPlan = new PlannerPlan;
            $PlannerPlan->title = 'Custome Plane' ;
            $PlannerPlan->description = $data->firstName.' '.$data->lastName.' Custome Plane' ; 
            $PlannerPlan->salePrice = 0;
            $PlannerPlan->regularPrice = 0;
            $PlannerPlan->isCustom = 1;
            $PlannerPlan->save();
        }
        

        if(!empty($mobile)) {
            $mobile = $mobile;
            $msg = Config::get('msg.invitation');  
            Helper::sendMessage($msg,$mobile);
        }
        $url = env('SELLER_PANEL_LINK').'verify/'.$token;
        $subject = "Welcome Email"; 
        $email_data = ['name' => $data['name'],'email' => $data['email'],'user'=>'seller','url'=>$url,'subject' => $subject];   
         Helper::send_mail('emailTemplate.sellerInvite',$email_data);
    
        $notification = array(
            'message' => 'Planner Add successfully!', 
            'alert-type' => 'success'
        ); 
        return redirect("planners")->with($notification);    
       }
    return view('planner.create');
       
      
   }
 

   public function edit(Request $req,$id=''){
    $data = Seller::where('sellers.id',$id)->where('sellers.type',2)->get()->first();
    if($req->isMethod('post')){
        if(!empty($data)){
            $profileImage = !empty($data['profileImage']) ? $data['profileImage'] : '';
            $req->validate([
              'firstname' => 'required|string',
              'lastname' => 'required|string',
              'userName' => 'required|alpha_num|unique:sellers,userName,'.$id,
           //   'email' => 'required|email|unique:sellers,email,'.$id,
              'phoneNo' => 'nullable|unique:sellers,mobileNo,'.$id,
              'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048*5',
             
          ]);
          if(!empty($req->free)){
            $free = 1;
        }else{
            $free = 0;
        }
          $mobile = !empty($req->phoneNo) ? $req->phoneNo : '';
          $data->firstName = $req->firstname;
          $data->userName = $req->userName;
          $data->lastName = $req->lastname;
          $data->free = $free;
       //   $data->email = $req->email;
          $data->mobileNo =  $mobile;
    
         if ($req->has('image')) {
              if(!empty($profileImage)){
                  Helper::deleteImage("vendor/vendor".$id."/".$profileImage."");
              }
              $image = $req->file('image');
              $folder = env('IMAGE_UPLOAD_PATH');
              $imageName = time().'.'.$req->image->extension();  
              $folder = Helper::imageUpload($imageName, $image,$folder="vendor/vendor".$id."");
              $data->profileImage = $imageName;
          }
          $data->save();
        
          
           
            $notification = array(
                'message' => 'Planner Update successfully!', 
                'alert-type' => 'success'
            ); 
            return redirect("planners")->with($notification);  
        }else{
            return redirect("planners");    
        }  
    }else{
      
        if($data){
            return view('planner.edit',['data'=>$data]);
        }else{
            return redirect("planners");  
        }
    }
      
   }
   
   public function profile(Request $req,$sellerId){
    $imageUrl = Helper::getUrl();
   
    $sellerData  = Seller::select('sellers.*', DB::raw('(CASE WHEN sellers.profileImage = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",sellers.id,"/", sellers.profileImage) END) AS profileImage'),'countries.name as country','planners.experiencePlanning','planners.otherExperiencePlanning')
                            ->leftjoin('countries','countries.id','sellers.countryId')
                            ->leftjoin('planners','planners.plannerId','sellers.id')
                            ->where('sellers.id',$sellerId)->where('sellers.type',2)->get()->first()->toArray(); 

if(!empty($sellerData)){
    $experiencePlanning = !empty($sellerData['experiencePlanning']) ? $sellerData['experiencePlanning'] : '';
    $experiencePlanning = explode(',',$experiencePlanning);
    $event = Event::select(DB::raw('group_concat(name) as names'))->whereIn('id',$experiencePlanning)->get()->first();
    $experiencePlanning =  $event['names'] ;
    $sellerData['projectImage'] = Project_image::select("id","event","numberAttendees","locationEvent",DB::raw('(CASE WHEN image = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor","'.$sellerId.'","/", image) END) AS image'))->where('sellerId',$sellerId)->get()->toArray();
    $review = Review::select('reviews.*',DB::raw('CONCAT(c.name," ",c.surname) as name'),DB::raw('(CASE WHEN c.profileImage = "" THEN "" ELSE CONCAT("'.$imageUrl .'","customer/customer",c.id,"/", c.profileImage) END) AS profileImage'))
                    ->join('customers as c','c.id','reviews.customerId')
                ->where('sellerId',$sellerId)->get()->toArray();
    $plans = PlannerPlan::where('sellerId',$sellerId)->get()->toArray();           
    return view('planner.profile',['data'=>$sellerData,'experience'=>$experiencePlanning,'review'=>$review,'plans'=>$plans]);
}else{
    return redirect("planners"); 
}
}

   public function delete($id){
    $data = Seller::find($id);
    if(!empty($data)){
     $data->delete();
    }
       
       $Vendor = Planner::where('plannerId',$id)->get()->first();
       if(!empty($Vendor)){
        $Vendor->delete();
       }
      

   }
}
