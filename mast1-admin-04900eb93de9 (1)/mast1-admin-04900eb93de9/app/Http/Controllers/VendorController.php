<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Validator;
use DB;
use Hash;
use Helper;
use Storage;
use Stripe\StripeClient;
use Stripe;
use Str;
use Config;
use SpacesConnect;
use App\Vendor;
use App\Seller;
use App\Project_image;
use App\Notification;
use Aws\S3\S3Client;
use Stripe\Charge;
use Stripe\Account;
use App\StripeAccount;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem;
use App\MessageGroup;
use App\Admin;
use App\Event;
use App\Review;
use App\VendorProduct;
 

class VendorController extends Controller
{
    public function __construct()
    {
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
    }
    
    public function index(request $req){
        $id = Session::get('id');
        $expertise = $req->expertise;
        $experience = $req->experience;
        $skill = $req->skill;
        $location = $req->location;

        $id = Session::get('id');
       $vendor = Seller::select('sellers.*','vendors.experiencePlanning', 'pr.ratting_cnt',DB::raw('round(pr.seller_rate/pr.ratting_cnt) as rating'))
                                ->leftjoin('vendors','sellers.id','vendors.sellerId')
                                ->leftjoin(DB::raw('(select pr1.sellerId, sum(pr1.rating) as seller_rate, count(1) as ratting_cnt from reviews pr1 group by pr1.sellerId) as pr'), function($join){
                                    $join->on('pr.sellerId', '=', 'sellers.id');
                                    })
                                ->where('sellers.type',1);
        if(!empty($expertise)){
            $data = $vendor->whereRaw("find_in_set($expertise,vendors.experiencePlanning)")
                            ->latest()->paginate(10);
        }else if(!empty($location)){
            $data = $vendor->where('sellers.location', 'LIKE', '%' . $location . '%')
                                ->latest()->paginate(10);
        }else if(!empty($experience)){
            $data = $vendor->where('vendors.workingindustry',$experience)
                                ->latest()->paginate(10);
        }else if(!empty($skill)){
            $data =$vendor->where('vendors.personalityPlanners', 'LIKE', '%' . $skill . '%')
                        ->latest()->paginate(10);
        }else{
            $data = $vendor->latest()->paginate(10);
        }
       
        $event = Event::all();
        return view('vendor.index',['data'=>$data,'event' =>$event]);
     }
   
   public function add(Request $req){
       
    if($req->isMethod('post')){
        $id = Session::get('id'); 
        $req->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'userName' => 'required|string|alpha_num|unique:sellers',
            'email' => 'required|string|email|unique:sellers',
            'phoneNo' => 'nullable|unique:sellers,mobileNo',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048*5',
           
        ]);
 
        $mobile = !empty($req->phoneNo) ? $req->phoneNo : '';
      //  $password = '123456';//Str::random(6);
         $token = Str::random(60);
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
        $data->profileImage = '';
        $data->remember_token = $token;
        $data->type =  1;
        $data->status =  1;
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
      /*   $response =    $this->stripe->accounts->create(['type' => 'custom','country' => 'US','email' => $data->email,'requested_capabilities' => ['card_payments','transfers',],]);
        $StripeAccount = new StripeAccount;
        $StripeAccount->sellerId = $data->id;
        $StripeAccount->accountId = $response->id;
        $StripeAccount->json = $response;
        $StripeAccount->status = $response->capabilities->card_payments;
        $StripeAccount->save(); */

        if(!empty($mobile)) {
            $mobile = $mobile;
            $msg = Config::get('msg.invitation');  
            Helper::sendMessage($msg,$mobile);
        }
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
        $url = env('SELLER_PANEL_LINK').'verify/'.$token;
        $subject = "Welcome Email"; 
        $email_data = ['name' => $data['name'],'email' => $data['email'],'user'=>'seller','url'=>$url,'subject' => $subject];   
        Helper::send_mail('emailTemplate.sellerInvite',$email_data);
 
        $notification = array(
            'message' => 'vendor Add successfully!', 
            'alert-type' => 'success'
        ); 
        return redirect("vendors")->with($notification);    
       }
    return view('vendor.create');
       
      
   }
 

   public function edit(Request $req,$id=''){
    $data = Seller::where('sellers.id',$id)->where('sellers.type',1)->get()->first();
    if($req->isMethod('post')){
        if(!empty($data)){
            $profileImage = !empty($data['profileImage']) ? $data['profileImage'] : '';
            $req->validate([
              'firstname' => 'required|string',
              'lastname' => 'required|string',
              'userName' => 'required|alpha_num|unique:sellers,userName,'.$id,
             // 'email' => 'required|email|unique:sellers,email,'.$id,
              'phoneNo' => 'nullable|unique:sellers,mobileNo,'.$id,
              'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048*5',
             
          ]);
          $mobile = !empty($req->phoneNo) ? $req->phoneNo : '';
          $data->firstName = $req->firstname;
          $data->userName = $req->userName;
          $data->lastName = $req->lastname;
         // $data->email = $req->email;
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
                'message' => 'Vendor Update successfully!', 
                'alert-type' => 'success'
            ); 
            return redirect("vendors")->with($notification);  
        }else{
            return redirect("vendors");    
        }  
    }else{
      
        if($data){
            return view('vendor.edit',['data'=>$data]);
        }else{
            return redirect("vendors");  
        }
    }
      
   }
   
   public function profile(Request $req,$sellerId){
            $imageUrl = Helper::getUrl();
            $experiencePlanning = [
                'corporate'=> 'Corporate',
                'weddings'=> 'Weddings',
                'birthday'=> 'Birthdays',
                'launches'=> 'Launches',
                'virtual'=> 'Virtual',
                'kid'=> 'Kids',
                'travel'=> 'Travel',
                'gatherings'=> 'Small gatherings',
                'all-above'=> 'All of the above',
                'other'=> '',
               ];
            $sellerData  = Seller::select('sellers.*', DB::raw('(CASE WHEN sellers.profileImage = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",sellers.id,"/", sellers.profileImage) END) AS profileImage'),'countries.name as country','vendors.experiencePlanning','vendors.otherExperiencePlanning')
                                    ->leftjoin('countries','countries.id','sellers.countryId')
                                    ->leftjoin('vendors','vendors.sellerId','sellers.id')
                                    ->where('sellers.id',$sellerId)->where('sellers.type',1)->get()->first()->toArray(); 
        
        if(!empty($sellerData)){
            $experiencePlanning = $sellerData['experiencePlanning'];
            $experiencePlanning = explode(',',$experiencePlanning);
            $event = Event::select(DB::raw('group_concat(name) as names'))->whereIn('id',$experiencePlanning)->get()->first();
            $experiencePlanning =  $event['names'] ;

            $sellerData['projectImage'] = Project_image::select("id","event","numberAttendees","locationEvent",DB::raw('(CASE WHEN image = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor","'.$sellerId.'","/", image) END) AS image'))->where('sellerId',$sellerId)->get()->toArray();
            $review = Review::select('reviews.*',DB::raw('CONCAT(c.name," ",c.surname) as name'),DB::raw('(CASE WHEN c.profileImage = "" THEN "" ELSE CONCAT("'.$imageUrl .'","customer/customer",c.id,"/", c.profileImage) END) AS profileImage'))
                                ->join('customers as c','c.id','reviews.customerId')
                            ->where('sellerId',$sellerId)->get()->toArray();
            $product = VendorProduct::select('vendor_products.*',DB::raw('(CASE WHEN i.image = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor","'.$sellerId.'","/", i.image) END) AS image'))
                            ->leftjoin('product_images as i','i.productId','vendor_products.id')
                           ->where('vendor_products.sellerId',$sellerId)
                           ->groupBy('i.productId')
                           ->get()->toArray();
            return view('vendor.profile',['data'=>$sellerData,'experience'=>$experiencePlanning,'review'=>$review,'product'=>$product]);
        }else{
            return redirect("vendors"); 
        }
   }

   public function delete($id){
       $data = Seller::find($id);
       if(!empty($data)){
        $data->delete();
       }
       
       $Vendor = Vendor::where('sellerId',$id)->get()->first();
       if(!empty($Vendor)){
        $Vendor->delete();
       }
   }

   public function status(Request $req){
      $sellerId = $req->id;
      $status = $req->status;
      $data = Seller::find($sellerId);
      $json = [];
      if(!empty($data)){
        $sellermobileno = !empty($data->mobileNo) ? $data->mobileNo : '' ;
        if($status == 'approved'){
            $data->status = 1;
            $json = ['status' => 'approved'];
            $msg = 'approved';

           
          //  $StripeAccount = StripeAccount::where('sellerId',$sellerId)->get()->first();
            //$accountId = !empty($StripeAccount->accountId) ? $StripeAccount->accountId : '' ;
          
       /*      if(empty($accountId) && !empty($StripeAccount)){
                if($StripeAccountStatus != 'active'){
                    $StripeAccount = $StripeAccount;
                    $response =    $this->stripe->accounts->create(['type' => 'custom','country' => 'US','email' => $data->email,'requested_capabilities' => ['card_payments','transfers',],]);
                    $StripeAccount->sellerId = $data->id;
                    $StripeAccount->accountId = $response->id;
                    $StripeAccount->json = $response;
                    $StripeAccount->status = $response->capabilities->card_payments;
                    $StripeAccount->save();
                }
               
            }else if(empty($StripeAccount)){
                $StripeAccount = new StripeAccount;
                $response =    $this->stripe->accounts->create(['type' => 'custom','country' => 'US','email' => $data->email,'requested_capabilities' => ['card_payments','transfers',],]);
                $StripeAccount->sellerId = $data->id;
                $StripeAccount->accountId = $response->id;
                $StripeAccount->json = $response;
                $StripeAccount->status = $response->capabilities->card_payments;
                $StripeAccount->save();
            } */
               
            if(!empty($sellermobileno)) {
                $mobile = $sellermobileno;
                $msg = Config::get('msg.sellerActive');  
                Helper::sendMessage($msg,$mobile);
            }
            
        }else{
            $data->status = 2;
            $json = ['status' => 'decline'];
            $msg = 'deactivated';


            if(!empty($sellermobileno)) {
                $mobile = $sellermobileno;
                $msg = Config::get('msg.sellerInactive');  
                Helper::sendMessage($msg,$mobile);
            }
        }    
        $data->save();
        $Notification = new Notification;
        $Notification->sellerId = $sellerId;
        $Notification->type = 'seller';
        $Notification->notification = 'Your account is '.$msg.' by admin';
        $Notification->save() ;
      }
     echo json_encode($json);
   
}
  
}
