<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Validator;
use App\Countries;
use App\Customer;
use App\State;
use App\Theme;
use App\Event;
use App\Seller;
use App\Blog;
use App\BlogImage;
use App\Questionnaire;
use App\Notification;
use App\MerchantCode;
use App\CustomerAddress;
use App\Faq;
use App\Contactu;
use App\Venue;
use App\Service;
use App\AdminSetting;
use Helper;
use Socket;
use DB;

class ApiController extends Controller
{
    
   public $success = 200;
   public $error = 401;
    
    public function venue(){
        $data = Venue::all();
         if(!empty($data)){
            return response()->json(['data'=>$data,'status'=>true,'message'=>'Venue List','token'=>''], $this->success);      
         }else{
            return response()->json(['data'=>'','status'=>true,'message'=>'Venue List Empty','token'=>''], $this->success);      
         }   
    }

    public function serviceCategory(){
        $data = Service::select('id','category')->where('subCategory',0)->get();
         if(!empty($data)){
            return response()->json(['data'=>$data,'status'=>true,'message'=>'Service Category List','token'=>''], $this->success);      
         }else{
            return response()->json(['data'=>'','status'=>true,'message'=>'Service Category List Empty','token'=>''], $this->success);      
         }   
    }

    public function serviceSubCategory(Request $req){
       $id = $req->categoryId;
      $data = Service::select('id','category as subCategory')->where('subCategory',$id)->get();
       if(!empty($data)){
          return response()->json(['data'=>$data,'status'=>true,'message'=>'Service Sub-Category List','token'=>''], $this->success);      
       }else{
          return response()->json(['data'=>'','status'=>true,'message'=>'Service Sub-Category List Empty','token'=>''], $this->success);      
       }   
  }
    public function country(){

        $country = Countries::all();
         if(!empty($country)){
            return response()->json(['data'=>$country,'status'=>true,'message'=>'Country List','token'=>''], $this->success);      
         }else{
            return response()->json(['data'=>'','status'=>true,'message'=>'Country List Empty','token'=>''], $this->success);      
         }
        
    }


    public function states(Request $request){
        $country_id =  $request->country_id;
        if(!empty($country_id)){
            $states = State::where('country_id',$country_id)->get();
         if(!empty($states)){
            return response()->json(['data'=>$states,'status'=>true,'message'=>'States List','token'=>''], $this->success);      
         }else{
            return response()->json(['data'=>'','status'=>true,'message'=>'States List Empty','token'=>''], $this->success);      
         }
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Country Id Is Required','token'=>''], $this->success);      
        }
    }


    public function isToken(Request $request){
        $token =  $request->token;
        if(!empty($token)){
        $customerData = Customer::where('token',$token)->get()->first();
        if(!empty($customerData)){

         $customerData = Helper::removeNull($customerData);
         return response()->json(['data'=>$customerData,'status'=>true,'message'=>'Token Is verify','token'=>''], $this->success);    
        }else{
         return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->success);    
        }
      }else{
         return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->success);    
        }
    }
   

    public function eventType(){

      $Event = Event::all();
       if(!empty($Event)){
          return response()->json(['data'=>$Event,'status'=>true,'message'=>'Event List','token'=>''], $this->success);      
       }else{
          return response()->json(['data'=>'','status'=>true,'message'=>'Event List Empty','token'=>''], $this->success);      
       }
      
    }

    public function questionnaireEventType(){

      $Event['personal'] = Event::where('eventCategory','personal')->get()->toArray();
      $Event['corporate'] = Event::where('eventCategory','corporate')->get()->toArray();
      $Event['social'] = Event::where('eventCategory','social')->get()->toArray();
       if(!empty($Event)){
          return response()->json(['data'=>$Event,'status'=>true,'message'=>'Event List','token'=>''], $this->success);      
       }else{
          return response()->json(['data'=>'','status'=>true,'message'=>'Event List Empty','token'=>''], $this->success);      
       }
      
    }

    public function themes(){

      $Theme = Theme::all();
       if(!empty($Theme)){
          return response()->json(['data'=>$Theme,'status'=>true,'message'=>'Theme List','token'=>''], $this->success);      
       }else{
          return response()->json(['data'=>'','status'=>true,'message'=>'Theme List Empty','token'=>''], $this->success);      
       }
      
    }
    
    public function MerchantCode(){

      $MerchantCode = MerchantCode::all();
       if(!empty($MerchantCode)){
          return response()->json(['data'=>$MerchantCode,'status'=>true,'message'=>'succss','token'=>''], $this->success);      
       }else{
          return response()->json(['data'=>'','status'=>true,'message'=>'Theme List Empty','token'=>''], $this->success);      
       }
      
    }


    public function notificationDelete(Request $request){
         $notificationId = $request->notificationId; 
         $NotificationData = Notification::find($notificationId);
         if(!empty($NotificationData)){
            $userType = !empty( $NotificationData->type) ? $NotificationData->type : '' ;
            $customerId = !empty( $NotificationData->customerId) ? $NotificationData->customerId : '' ;
            $sellerId = !empty( $NotificationData->sellerId) ? $NotificationData->sellerId : '' ;
            $NotificationData->readStatus = 1;
            $NotificationData->save();
            if($userType == 'admin'){
               $type = 1;
               Socket::notification($adminId='',$userType='admin');
            }elseif($userType == 'customer'){
                  $type = 2;
                  Socket::notification($customerId,$userType='customer');
            }elseif($userType == 'seller'){
                  $type = 3;
                  Socket::notification($sellerId,$userType='seller');
            }else{
                  $type = '';
            }
            return response()->json(['data'=>'','status'=>true,'message'=>'Delete Successfully','token'=>''], $this->success);      
         }else{
             return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Id','token'=>''], $this->success);    
        }
    }
     
    public function isSellerToken(Request $request){
      $token =  $request->token;
      if(!empty($token)){
         $sellerData = Seller::where('remember_token',$token)->get()->first();
         if(!empty($sellerData)){
            $sellerData = Helper::removeNull($sellerData);
          return response()->json(['data'=>$sellerData,'status'=>true,'message'=>'Token Is verify','token'=>''], $this->success);    
         }else{
          return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->success);    
         }
      }else{
         return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->success);    
        }    
  }

      public function blogs(Request $request){
         $pageNo = $request->pageNo;
         $limit = !empty(env('PR_PAGE_DATA')) ? env('PR_PAGE_DATA') : 10 ;
         $start = !empty($pageNo) ? ($pageNo - 1)*$limit : 0 ;
         $imageUrl = Helper::getUrl();
         $blogs = Blog::select('blogs.*',DB::raw('(CASE WHEN blog_images.file = "" THEN "" ELSE CONCAT("'.$imageUrl .'","admin/blogs/", blog_images.file) END) AS file'))
                        ->leftjoin('blog_images', function($leftjoin){
                           $leftjoin->on('blogs.id', '=', 'blog_images.blogId')
                           ->where('blog_images.type','image');   
                        })
                      ->groupBy('blog_images.blogId')
                      ->offset($start)->limit($limit)
                      ->get()->toArray();
         $count = Blog::count();
         if(!empty($blogs)){
             $AdminSetting = AdminSetting::first();
            $data['blog'] = $blogs;
            $data['totalPage'] = ceil($count/$limit);
            $data['title'] = !empty($AdminSetting->title) ? $AdminSetting->title : '' ;
            $data['description'] = !empty($AdminSetting->description) ? $AdminSetting->description : '' ;
            return response()->json(['data'=> $data,'status'=>true,'message'=>'Success','token'=>''], $this->success);      
         }else{
            return response()->json(['data'=>'','status'=>true,'message'=>'Data Empty','token'=>''], $this->success);    
         }
      }


      public function blogDetails(Request $request){
         $imageUrl = Helper::getUrl();
         $blogId =  $request->blogId;
         $blogs = Blog::where('id',$blogId)->get()->first();
        
         if(!empty($blogs)){
             
             $blogs['blogFile']  = BlogImage::select('*',DB::raw('(CASE WHEN file = "" THEN "" ELSE CONCAT("'.$imageUrl .'","admin/blogs/", file) END) AS file'))->where('blogId',$blogId)->get()->toArray();
            return response()->json(['data'=> $blogs,'status'=>true,'message'=>'Success','token'=>''], $this->success);      
         }else{
            return response()->json(['data'=>'','status'=>true,'message'=>'Invalid id','token'=>''], $this->success);    
         }
      }


      public function addressEdit(Request $request){
         $imageUrl = Helper::getUrl();
         $addressId =  $request->addressId;
         $addressData = CustomerAddress::where('id',$addressId)->where('status',0)->get()->first();
        
         if(!empty($addressData)){
              
            if(!empty($request->street)){
               $addressData->street = $request->street;
            }

            if(!empty($request->country)){
               $addressData->country = $request->country;
            }

            if(!empty($request->phoneNumber)){
               $addressData->phoneNumber = $request->phoneNumber;
            }
            $addressData->save() ;
            $address = CustomerAddress::select('customer_address.*','countries.name as countryName','countries.id as country')
                  ->join('countries','countries.id','customer_address.country')
                  ->where('customer_address.id',$addressId)->get()->first();
            return response()->json(['data'=>$address,'status'=>true,'message'=>'Success','token'=>''], $this->success);      
         }else{
            return response()->json(['data'=>'','status'=>true,'message'=>'Invalid id','token'=>''], $this->success);    
         }
      }


      public function addressDelete(Request $request){
         $addressId =  $request->addressId;
         $addressData = CustomerAddress::where('id',$addressId)->where('status',0)->get()->first();
        
         if(!empty($addressData)){
            $addressData->status = 1;
            $addressData->save() ;
            return response()->json(['data'=>'','status'=>true,'message'=>'Success','token'=>''], $this->success);      
         }else{
            return response()->json(['data'=>'','status'=>true,'message'=>'Invalid id','token'=>''], $this->success);    
         }
      }


      public function address(Request $req) 
      {    
         $address = [];
          $token = $req->bearerToken();
          $encode_token =  Helper::encode_token($token);
          $customerId = $encode_token;
          $customerData =  Customer::where('id',$customerId)->get()->first(); 
          $pageNo =  $req->pageNo;
          $limit = !empty(env('PR_PAGE_DATA')) ? env('PR_PAGE_DATA') : 10 ;
          $start = !empty($pageNo) ? ($pageNo - 1)*$limit : 0 ;
          
          if(!empty($customerData)){
               $addressData = CustomerAddress::select('customer_address.*','countries.name as countryName','countries.id as country')
                                          ->join('countries','countries.id','customer_address.country')
                                          ->where('customer_address.customerId',$customerId)->where('customer_address.status',0);
               $count = $addressData->count();
               $addressData = $addressData->offset($start)->limit($limit)->get()->toArray();
               if(!empty($addressData)){
               $address['address'] = $addressData;
               $address['totalPage'] = ceil($count/$limit);
               return response()->json(['data'=>$address,'status'=>true,'message'=>'Success','token'=>''], $this->success);      
            }else{
               return response()->json(['data'=>'','status'=>true,'message'=>'Data Empty','token'=>''], $this->success);    
            }
        }else{
          return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
        }
      }


      public function addressAdd(Request $req){
       
         $token = $req->bearerToken();
         $customerId =  Helper::encode_token($token);
         $customerData =  Customer::where('id',$customerId)->get()->first(); 
         if(!empty($customerData)){
            
            $validator = Validator::make($req->all(),[ 
               'street' => 'required',
               'country' => 'required',
               'phoneNumber' => 'required',
            ]);

            if ($validator->fails()){ 
               return response()->json(['data'=>'','status'=>false,'message'=>$validator->errors()->all(),'token'=>''], $this->success);            
            }else{ 

               $addressData = CustomerAddress::where('customerId',$customerId)
               ->where('street',$req->street)->where('country',$req->country)->where('phoneNumber',$req->phoneNumber)->where('status',0)->get()->first();
                   
               if(empty($addressData)){
                     $addressData = new CustomerAddress;
                     $addressData->customerId = $customerId;
                     $addressData->country = $req->country;
                     $addressData->phoneNumber = $req->phoneNumber;
                     $addressData->street = $req->street;
                     $addressData->save();

                  $address = CustomerAddress::select('customer_address.*','countries.name as countryName','countries.id as country')
                     ->join('countries','countries.id','customer_address.country')
                     ->where('customer_address.id',$addressData->id)->get()->first();
               return response()->json(['data'=>$address,'status'=>true,'message'=>'Success','token'=>''], $this->success);  
               }else{
                  return response()->json(['data'=>'','status'=>false,'message'=>'address already in use ','token'=>''], $this->success); 
               }
            }
                
         }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error);    
         }
      }



      public function faq(Request $request){
         //$pageNo = $request->pageNo;
       //  $limit = !empty(env('PR_PAGE_DATA')) ? env('PR_PAGE_DATA') : 10 ;
       //  $start = !empty($pageNo) ? ($pageNo - 1)*$limit : 0 ;
        // $imageUrl = Helper::getUrl();
         $faq = Faq::all();
        // $count = Faq::count();
         if(!empty($faq)){
            $data = $faq;
           // $data['totalPage'] = ceil($count/$limit);
            return response()->json(['data'=> $data,'status'=>true,'message'=>'Success','token'=>''], $this->success);      
         }else{
            return response()->json(['data'=>'','status'=>true,'message'=>'Data Empty','token'=>''], $this->success);    
         }
      }



      public function contactus(Request $req){
        
            $validator = Validator::make($req->all(),[ 
               'name' => 'required',
               'email' => 'required|email',
              
            ]);

            if ($validator->fails()){ 
               return response()->json(['data'=>'','status'=>false,'message'=>$validator->errors()->all(),'token'=>''], $this->success);            
            }else{ 
               $data = new Contactu;
               $data->name = $req->name;
               $data->email = $req->email;
               $data->message = $req->message;
               $data->save();
               return response()->json(['data'=>'','status'=>true,'message'=>'We have received your request.','token'=>''], $this->success);  
            }
                
        
      }


            public function subscription(Request $req){
        
            $validator = Validator::make($req->all(),[ 
               'email' => 'required|email',
            ]);

             

            if ($validator->fails()){ 
               
               return response()->json(['data'=>'','status'=>false,'message'=>$validator->errors()->all(),'token'=>''], $this->success);            
            }else{ 
               $list_ids = env('LIST_IDS');
               $curl = curl_init();
               curl_setopt_array($curl, array(
               CURLOPT_URL => "https://api.sendgrid.com/v3/marketing/contacts",
               CURLOPT_RETURNTRANSFER => true,
               CURLOPT_ENCODING => "",
               CURLOPT_MAXREDIRS => 10,
               CURLOPT_TIMEOUT => 30,
               CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
               CURLOPT_CUSTOMREQUEST => "PUT",
               CURLOPT_POSTFIELDS => "{\"list_ids\":[\"$list_ids\"],\"contacts\":[{\"email\":\"$req->email\",\"custom_fields\":{}}]}",
               CURLOPT_HTTPHEADER => array(
                  "authorization: Bearer ".env('SUBSCRIPTION_TOKEN'),
                  "content-type: application/json"
               ),
               ));

               $response = curl_exec($curl);
               $err = curl_error($curl);

               curl_close($curl);

               if ($err) {
                  return response()->json(['data'=>'','status'=>true,'message'=>'','token'=>''], $this->success);  
               } else {
                  return response()->json(['data'=>'','status'=>true,'message'=>'success','token'=>''], $this->success);  
               }
              
             
            } 
      }
	
	
	public function userSubscription(Request $req){
        
         $validator = Validator::make($req->all(),[ 
            'email' => 'required|email',
            'firstname' => 'required',
            'lastname' => 'required',
         ]);

          

         if ($validator->fails()){ 
            
            return response()->json(['data'=>'','status'=>false,'message'=>$validator->errors()->all(),'token'=>''], $this->success);            
         }else{ 
            $list_ids = env('LIST_IDS');
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.sendgrid.com/v3/marketing/contacts",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => "{\"list_ids\":[\"$list_ids\"],\"contacts\":[{\"email\":\"$req->email\",\"first_name\":\"$req->firstname\",\"last_name\":\"$req->lastname\",\"custom_fields\":{}}]}",
          
            CURLOPT_HTTPHEADER => array(
               "authorization: Bearer ".env('SUBSCRIPTION_TOKEN'),
               "content-type: application/json"
            ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
               return response()->json(['data'=>'','status'=>true,'message'=>'fail','token'=>''], $this->success);  
            } else {
               
               return response()->json(['data'=>'','status'=>true,'message'=>'success','token'=>''], $this->success);  
            }
           
          
         } 
   }
  
}
