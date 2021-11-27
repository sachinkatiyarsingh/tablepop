<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use DB;
use Str;
use Validator;
use Hash;
use Helper;
use Config;
use Socket;
use App\Service;
use App\Customer;
use App\Notification;
use App\PlannerPlan;
use App\Message;
use App\Transaction;
use App\Project_image;
use App\Questionnaire;
use App\Offer;
use App\MessageGroup;
use App\Admin;
use App\Cart;
use App\AdminSetting;
use App\Milestone;
use App\EventVendor;
use App\Vendor_product;
use App\Moodboard;
use App\MoodboardImage;
use App\Seller;
use App\CustomerVendor;
use App\Favorite;
use App\Review;
use App\ShareEvent;
use App\SingleMessagingGroup;
use App\MessageNotification;


class CustomerController extends Controller
{
    public $success = 200;
    public $error = 401;     
    public function socialRegister(Request $req){
        $validator = Validator::make($req->all(), [
            'email' => 'required|string|email',
            'type' => 'required',
            'socialId' => 'required|string',
        ]);

        if ($validator->fails()) { 
            return response()->json(['data'=>'','status'=>false,'message'=>$validator->errors()->all(),'token'=>''], $this->success);            
        }else{
             $cusromerData = Customer::where('email',$req->input('email'))->get()->first();
             if(!empty($cusromerData)){
                $Token =  $cusromerData->createToken('iaastha-api')->accessToken;
                $cusromerData = Helper::removeNull($cusromerData);
               return response()->json(['data'=>$cusromerData,'status'=>true,'message'=>'Login Successfully','token'=>$Token], $this->success); 
             }else{
                $name_last ='';
                $invitationCode =  strtoupper(substr(md5(time()), 0, 8)); 
                $parts = explode(' ',$req->name); 
                $name_first = array_shift($parts);
                $name_last = array_pop($parts);
                $name_last = !empty($name_last) ? $name_last : '';
                $customer = new Customer;
                $customer->name = $name_first;
                $customer->surname = $name_last;
                $customer->email = $req->email;
                $customer->mobile =  '';
                $customer->country_id = '';
                $customer->state_id = '';
                $customer->notification = '';
                $customer->invitationCode = $invitationCode;
                $customer->invitationId =0;
                $customer->status = 1;
                $customer->password = '';
                $customer->save();
                $Token =  $customer->createToken('iaastha-api')->accessToken;
                $customer = Helper::removeNull($customer);
                return response()->json(['data'=> $customer,'status'=>true,'message'=>'Successfully','token'=>$Token], $this->success);     
             }

        }
    }

     public function register(Request $req){
        $error = '';
        $invitationId = '';
        $inputCode = $req->invitationCode;
         if(!empty($inputCode)){
            $invitationCustomerData = Customer::where('invitationCode',$inputCode)->get()->first();
            if(!empty($invitationCustomerData)){
                $invitationId = $invitationCustomerData->id;
                $error = '';
            }else{
                $error = 'Invalid Invitation Code';
            }
         }
       
      

        
        $validator = Validator::make($req->all(), [
            'name' => 'required|string',
            'surname' => 'required|string',
            'country' => 'required',
            'state' => 'required',
            'email' => 'required|string|email|unique:customers',
            'mobile' => 'nullable|unique:customers',
            'password' => 'required|string|min:6',

        ]);

        if ($validator->fails()) { 
            return response()->json(['data'=>'','status'=>false,'message'=>$validator->errors()->all(),'token'=>''], $this->success);            
        }else{
            $invitationCode =  strtoupper(substr(md5(time()), 0, 8)); 
            $mobile = !empty($req->mobile) ? $req->mobile : '';
            $customer = new Customer;
            $customer->name = $req->name;
            $customer->surname = $req->surname;
            $customer->email = $req->email;
            $customer->mobile =  $mobile;
            $customer->country_id = $req->country;
            $customer->state_id = $req->state;
            $customer->notification = '';
            $customer->invitationCode = $invitationCode;
            $customer->invitationId = $invitationId;
            $customer->status = 1;
            $customer->password = Hash::make($req->password);
            $customer->save();
            
            if($customer->save()){
                 
                $adminData = Admin::where('type',1)->where('adminType',1)->get()->first();
                $adminId = !empty($adminData->id) ? $adminData->id : 0 ;
                $MessageGroupData = MessageGroup::where('customerId',$customer->id)->where('type',1)->get()->first();
                if(empty($MessageGroupData)){
                    $MessageGroup = new MessageGroup;
                    $MessageGroup->customerId = $customer->id;
                    $MessageGroup->adminId = $adminId;
                    $MessageGroup->type = 1;
                    $MessageGroup->save();
                }
                
                $notification = new Notification;
                $notification->notification = 'New customer '.$customer->name.' '. $customer->surname.' registered.';
                $notification->type = 'admin';
                $notification->urlType = 'customer';
                $notification->customerId = $customer->id;
                $notification->fromId = $customer->id;
                $notification->toId = $adminId;
                $notification->sendType = 'customer';
                $notification->save();
                Socket::notification($adminId,$userType='admin');
                $ShareEventData = ShareEvent::where('email',$customer->email)->get()->first();
                $ShareEventCustomerId = !empty($ShareEventData->customerId) ? $ShareEventData->customerId : '' ;
                if(empty($ShareEventCustomerId) && !empty($ShareEventData)){
                    $ShareEventData->customerId = $customer->id;
                    $ShareEventData->save();
                }

                if(!empty($mobile)){
                    $mobile = $mobile;
                    $msg = Config::get('msg.welcome');  
                    Helper::sendMessage($msg,$mobile);
                }
                $customer_data = Customer::find( $customer->id);
                $Token =  $customer_data->createToken('iaastha-api')->accessToken;
                $subject = "Welcome to TablePop"; 
                $email_data = ['email' =>$customer_data['email'],'name'=>$customer->name.' '. $customer->surname,'user'=>'customer','subject' => $subject];  
                Helper::send_mail('emailTemplate.customerWelcome',$email_data);
                $customer_data = Helper::removeNull($customer_data);
               return response()->json(['data'=>$customer_data,'status'=>true,'message'=>'Register Successfully','token'=>$Token], $this->success); 
            }
        }
      
        
     }



     public function login(Request $req){ 
        
        $validator = Validator::make($req->all(), [ 
            'email' => 'required|string|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) { 
            return response()->json(['data'=>'','status'=>false,'message'=>$validator->errors()->all(),'token'=>''], $this->success);            
        }else{
            $email = $req->email;
            $Customer_data = Customer::where('email',$email)->get()->first();
            if(!empty($Customer_data)){
                 if($Customer_data->status == 1){
                 $data_password =  $Customer_data->password; 
                 if(Hash::check($req->password,$data_password)){
                    $Token =  $Customer_data->createToken('volumes')->accessToken;
                    $Customer_data = Helper::removeNull($Customer_data);
                    return response()->json(['data'=>$Customer_data,'status'=>true,'message'=>'Login Successfully','token'=>$Token], $this->success);            
                 }else{
                    return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Password','token'=>''], $this->success);  
                 }
                 }else{
                    return response()->json(['data'=>'','status'=>false,'message'=>'Your Account In-Active','token'=>''], $this->success);  
                 }
            }else{
               return response()->json(['data'=>'','status'=>false,'message'=>'Invalid email address','token'=>''], $this->success); 
            }
        }
    }

    public function profile(Request $req){
        $token = $req->bearerToken();
        $encode_token =  Helper::encode_token($token);
        $customerId = $encode_token; 
        $customerData = Customer::find($customerId);
        if(!empty($customerData)){
            $profileImage = !empty($customerData->profileImage) ? $customerData->profileImage : '' ;
            $validator = Validator::make($req->all(), [ 
              'mobile' => 'nullable|unique:customers,mobile,'.$customerId,
              'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048*5',
   
          ]);
          if ($validator->fails()) { 
              return response()->json(['data'=>'','status'=>false,'message'=>$validator->errors()->all(),'token'=>''], $this->success);                    
          }else{         
               if(!empty($req->name)){
                   $customerData->name = $req->name;
               }
               if(!empty($req->surname)){
                   $customerData->surname = $req->surname;
               }
             
               if(!empty($req->country)){
                   $customerData->country_id = $req->country;
               }
               if(!empty($req->state)){
                   $customerData->state_id = $req->state;
               }
              
               if(!empty($req->mobile)){
                   $customerData->mobile = $req->mobile;
               }
              
             
               if ($req->has('image')){
                if(!empty($profileImage)){
                    Helper::deleteImage("customer/customer".$customerId."/".$profileImage."");
                }
                $image = $req->file('image');
                $imageName = time().'.'.$req->image->extension(); 
                $imageName = str_replace( " ", "-", trim($imageName) ); 
                $folder = Helper::imageUpload($imageName, $image,$folder="customer/customer".$customerId."");
                $customerData->profileImage = $imageName;
               }  
               $customerData->save();  
               $Customer = Customer::where('id',$customerId)->get()->first();
               $profile = !empty($Customer['profileImage']) ? Helper::getUrl().'customer/customer'.$customerId.'/'.$Customer['profileImage'] : '' ;
               $Customer['profileImage'] = $profile;
               $Customer = Helper::removeNull($Customer);
              return response()->json(['data'=>$Customer,'status'=>true,'message'=>'Update Profile Successfully','token'=>''], $this->success);
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
            $Customer_data = Customer::where('email',$email)->get()->first();
            if(!empty($Customer_data)){
                
                $token = Str::random(32).time();
                $update = Customer::find($Customer_data['id']);
                $update->token = $token;
                $update->save();

                $url = env('CUSTOMER_URL').'verify/'.$token;
                $subject = "TablePop password reset"; 
                $email_data = ['name' => $Customer_data['name'],'email' => $Customer_data['email'],'url'=>$url,'subject' => $subject];    
                Helper::send_mail('emailTemplate.password',$email_data);
                return response()->json(['data'=>'','status'=>true,'message'=>'Reset Mail Send Successfully','token'=>''], $this->success);            
                
            }else{
               return response()->json(['data'=>'','status'=>false,'message'=>'Invalid email address','token'=>''], $this->success); 
            }
        }

    }


    public function reset_password(Request $req,$token) 
    {    
        $Customer_data =  Customer::where('token',$token)->get()->first();
        if(!empty($Customer_data)){
            $validator = Validator::make($req->all(), [ 
                'password' => 'required|min:6|max:20', 
                'c_password' => 'required|same:password', 
            ]);
            if ($validator->fails()) { 
                return response()->json(['data'=>'','status'=>false,'message'=>$validator->errors()->all(),'token'=>''], $this->success);                           
            }else{         
                    $password =  Hash::make($req->input('password'));
                    Customer::where('token',$token)->update(['password' => $password,'token'=>'']);
                    return response()->json(['data'=>'','status'=>true,'message'=>'success','token'=>''], $this->success); 
            }
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
          }
        }
    
        public function change_password(Request $req) 
        {    
            $token = $req->bearerToken();
            $encode_token =  Helper::encode_token($token);
            $customerId = $encode_token;
            $customerData =  Customer::where('id',$customerId)->get()->first();   
            if(!empty($customerData)){
             
                $validator = Validator::make($req->all(), [ 
                    'currentPassword' => 'required|min:6|max:20|',
                    'newPassword' => 'required|min:6|max:20', 
                    'confirmPassword' => 'required|same:newPassword', 
                ]);
            if ($validator->fails()) { 
                return response()->json(['data'=>'','status'=>false,'message'=>$validator->errors()->all(),'token'=>''], $this->success);                           
            }else{         
               
                $customerPassword = !empty($customerData['password']) ? $customerData['password'] : '';
                $password  = Hash::make($req->confirmPassword);
                if(Hash::check($req->currentPassword,$customerPassword)){ 
                     $updatePassword = Customer::find($customerId);
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
        }


      public function customerNotification(Request $req){
        $token = $req->bearerToken();
        $imageUrl = Helper::getUrl(); 
        $demoImage = asset('resources/images/demoImage.png');
        $encode_token =  Helper::encode_token($token);
        if(!empty($encode_token)){
            $customerId =  $encode_token;
            $notificationData = Notification::select('notifications.*',DB::raw('( CASE WHEN notifications.sendType = "seller"  THEN  CONCAT("'.$imageUrl .'","vendor/vendor",d.id,"/", d.profileImage) WHEN notifications.sendType = "customer"  THEN  CONCAT("'.$imageUrl .'","customer/customer",c.id,"/", c.profileImage) WHEN notifications.sendType = "admin"  THEN  CONCAT("'.$imageUrl .'","admin/admin",a.id,"/", a.image) ELSE "'.$demoImage.'"  END)   AS image'))
                                ->leftjoin('customers as c', function($join){
                                    $join->on('notifications.fromId', '=', 'c.id')
                                    ->where('notifications.sendType', '=', 'customer');
                                })
                                ->leftjoin('sellers AS d', function($leftjoin){
                                    $leftjoin->on('notifications.fromId', '=', 'd.id')
                                    ->where('notifications.sendType', '=', 'seller');
                                })
                                ->leftjoin('admin AS a', function($leftjoin){
                                    $leftjoin->on('notifications.fromId', '=', 'a.id')
                                    ->where('notifications.sendType', '=', 'admin');
                                })
                
                             ->where('notifications.toId',$customerId)->where('notifications.readStatus',0)->where('notifications.type','eventPlanner')->orwhere('notifications.type','customer')->limit(5)->orderBy('notifications.id','DESC')->get()->toArray();
           
            if(!empty($notificationData)){
                return response()->json(['data'=>$notificationData,'status'=>true,'message'=>'Notifications','token'=>''], $this->success); 
            }else{
                return response()->json(['data'=>[],'status'=>true,'message'=>'Data Empty','token'=>''], $this->success); 
            }
            
       }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
       }
      }


    public function customerDashboardNotification(Request $req){
        $imageUrl = Helper::getUrl(); 
        $demoImage = asset('resources/images/demoImage.png');
        $token = $req->bearerToken();
        $encode_token =  Helper::encode_token($token);
        $customerId = $encode_token;
        $customerData =  Customer::where('id',$customerId)->get()->first(); 
        if(!empty($customerData)){
            $pageNo = $req->pageNo;
            $limit = !empty(env('PR_PAGE_DATA')) ? env('PR_PAGE_DATA') : 10 ;
            $start = !empty($pageNo) ? ($pageNo - 1)*$limit : 0 ;
            $notification = Notification::select('notifications.*',DB::raw('( CASE WHEN notifications.sendType = "seller"  THEN  CONCAT("'.$imageUrl .'","vendor/vendor",d.id,"/", d.profileImage) WHEN notifications.sendType = "customer"  THEN  CONCAT("'.$imageUrl .'","customer/customer",c.id,"/", c.profileImage) WHEN notifications.sendType = "admin"  THEN  CONCAT("'.$imageUrl .'","admin/admin",a.id,"/", a.image) ELSE "'.$demoImage.'"  END)   AS image'))
                                    ->leftjoin('customers as c', function($join){
                                        $join->on('notifications.fromId', '=', 'c.id')
                                        ->where('notifications.sendType', '=', 'customer');
                                    })
                                    ->leftjoin('sellers AS d', function($leftjoin){
                                        $leftjoin->on('notifications.fromId', '=', 'd.id')
                                        ->where('notifications.sendType', '=', 'seller');
                                    })
                                    ->leftjoin('admin AS a', function($leftjoin){
                                        $leftjoin->on('notifications.fromId', '=', 'a.id')
                                        ->where('notifications.sendType', '=', 'admin');
                                    })
                               ->where('notifications.toId',$customerId)->where('notifications.readStatus',0)->where('notifications.type','customer')->orwhere('notifications.type','eventPlanner');
            $count = $notification->count();
            $notificationData = $notification->offset($start)->limit($limit)->orderBy('id','DESC')->get()->toArray();
            // print_r($notificationData);
            $data['notification'] = $notificationData;
            $data['totalPage'] = ceil($count/$limit);
            if(!empty($notificationData)){
                $data = Helper::removeNull($data);
                return response()->json(['data'=>$data,'status'=>true,'message'=>'Dashboard','token'=>''], $this->success); 
            }else{
                return response()->json(['data'=>[],'status'=>true,'message'=>'Data Empty','token'=>''], $this->success); 
            }
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
        }
    }


    public function customerDashboardOngoing(Request $req){
        $imageUrl = Helper::getUrl();
        $token = $req->bearerToken();
        $encode_token =  Helper::encode_token($token);
        $customerId = $encode_token;
        $customerData =  Customer::where('id',$customerId)->get()->first(); 
        if(!empty($customerData)){
            $pageNo = $req->pageNo;
            $limit = !empty(env('PR_PAGE_DATA')) ? env('PR_PAGE_DATA') : 10 ;
            $start = !empty($pageNo) ? ($pageNo - 1)*$limit : 0 ;
            $questionnairData = Transaction::select('questionnaires.id','questionnaires.tokenId','questionnaires.eventName','questionnaires.farEventDate',DB::raw('(CASE WHEN i.image = "" THEN "" ELSE CONCAT("'.$imageUrl .'","customer/customer",questionnaires.customerId,"/", i.image) END) AS image'))
                                ->leftjoin('questionnaires','questionnaires.id','transactions.questionnaireId')
                                ->leftjoin('images AS i', function($leftjoin){
                                    $leftjoin->on('questionnaires.id', '=', 'i.questionnaireId');
                                })
                               
                                ->groupBy('i.questionnaireId')
                                ->where('transactions.customerId',$customerId)
                                ->where('questionnaires.status',2)->where('transactions.status',1);
            $count = $questionnairData->count();
            $Ongoing = $questionnairData->offset($start)->limit($limit)->orderBy('questionnaires.id','DESC')->get()->toArray();
           // print_r($Ongoing);
           
            $data['ongoing'] = $Ongoing;
            $data['totalPage'] = ceil($count/$limit);
            if(!empty($questionnairData)){
                $data = Helper::removeNull($data);
                return response()->json(['data'=>$data,'status'=>true,'message'=>'Dashboard','token'=>''], $this->success); 
            }else{
                return response()->json(['data'=>[],'status'=>true,'message'=>'Data Empty','token'=>''], $this->success); 
            }
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
        }
    }


    public function customerDashboardMessage(Request $req){
        $imageUrl = Helper::getUrl();
        $token = $req->bearerToken();
        $demoImage = asset('resources/images/demoImage.png');
        $encode_token =  Helper::encode_token($token);
        $customerId = $encode_token;
        $customerData =  Customer::where('id',$customerId)->get()->first(); 
        if(!empty($customerData)){
            $pageNo = $req->pageNo;
            $limit = !empty(env('PR_PAGE_DATA')) ? env('PR_PAGE_DATA') : 10 ;
            $start = !empty($pageNo) ? ($pageNo - 1)*$limit : 0 ;
            $messageGroup = MessageGroup::select(DB::raw('group_concat(id) as groupId'))->where('customerId',$customerId)->get()->first();
            $SingleMessagingGroup = SingleMessagingGroup::select(DB::raw('group_concat(groupId) as groupId'))->where('createId',$customerId)->where('type',2)->get()->first();
            if(!empty($SingleMessagingGroup)){
                $SingleMessagingGroupid = explode(',',$SingleMessagingGroup->groupId);
            }
            if(!empty($messageGroup)){
              //  $messageGroupId = $messageGroup->groupId;
                $messageGroupId = explode(',',$messageGroup->groupId);
            }
            $messagesData = MessageNotification::select("message_notifications.*",DB::raw('( CASE WHEN message_notifications.type = 3  THEN  CONCAT("'.$imageUrl .'","vendor/vendor",d.id,"/", d.profileImage) WHEN  message_notifications.type = 2  THEN  CONCAT("'.$imageUrl .'","customer/customer",c.id,"/", c.profileImage) WHEN  message_notifications.type = 1  THEN  CONCAT("'.$imageUrl .'","admin/admin",a.id,"/", a.image) ELSE "'.$demoImage.'"  END)   AS image'))
                                        ->selectRaw(DB::raw('COALESCE(c.name,a.name,CONCAT(d.firstName, " ", d.lastName)) as name'))
                                        ->leftjoin('customers as c', function($join){
                                            $join->on('message_notifications.userId', '=', 'c.id')
                                            ->where('message_notifications.type', '=', 2);
                                        })
                                        ->leftjoin('sellers AS d', function($leftjoin){
                                            $leftjoin->on('message_notifications.userId', '=', 'd.id')
                                            ->where('message_notifications.type', '=', 3);
                                        })
                                        ->leftjoin('admin AS a', function($leftjoin){
                                            $leftjoin->on('message_notifications.userId', '=', 'a.id')
                                            ->where('message_notifications.type', '=', 1);
                                        })
                                ->where('message_notifications.userId',$customerId)
                                ->where('message_notifications.type', 2)
                               
                                ->whereIn('message_notifications.groupId',$messageGroupId)
                                ->orwhereIn('message_notifications.groupId',$SingleMessagingGroupid)
                               
                               ;
                               
            $count = $messagesData->count();
            $messagesData = $messagesData->offset($start)->limit($limit)->orderBy('message_notifications.id','DESC')->get()->toArray();
           // print_r($Ongoing);
           
            $data['message'] = $messagesData;
            $data['totalPage'] = ceil($count/$limit);
            if(!empty($messagesData)){
                $data = Helper::removeNull($data);
                return response()->json(['data'=>$data,'status'=>true,'message'=>'Dashboard','token'=>''], $this->success); 
            }else{
                return response()->json(['data'=>[],'status'=>true,'message'=>'Data Empty','token'=>''], $this->success); 
            }
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
        }
    }


    public function notificationCount(Request $req){
        $pageNo = $req->pageNo;
        $token = $req->bearerToken();
        $customerId =  Helper::encode_token($token);
      
        $customerData =  Customer::where('id',$customerId)->get()->first(); 
        if(!empty($customerData)){ 
            $messageGroup = MessageGroup::select(DB::raw('group_concat(id) as groupId'))->where('customerId',$customerId)->get()->first();
            $SingleMessagingGroup = SingleMessagingGroup::select(DB::raw('group_concat(groupId) as groupId'))->where('createId',$customerId)->where('type',2)->get()->first();
            if(!empty($SingleMessagingGroup)){
                $SingleMessagingGroupid = explode(',',$SingleMessagingGroup->groupId);
            }
            if(!empty($messageGroup)){
              //  $messageGroupId = $messageGroup->groupId;
                $messageGroupId = explode(',',$messageGroup->groupId);
            }
            $data['notification'] = Notification::where('toId',$customerId)->where('readStatus',0)->where('type','customer')->orwhere('type','eventPlanner')->count();
            $data['message'] = MessageNotification::where('message_notifications.userId',$customerId)
            ->where('message_notifications.type', 2)
            ->whereIn('message_notifications.groupId',$messageGroupId)
            ->orwhereIn('message_notifications.groupId',$SingleMessagingGroupid)
            ->count();
            if(!empty($data)){
                return response()->json(['data'=>$data,'status'=>true,'message'=>'success','token'=>''], $this->success); 
            }else{
                return response()->json(['data'=>'','status'=>true,'message'=>'success','token'=>''], $this->success);
            }
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
        }
    }
    public function dashboard(Request $req){
        $imageUrl = Helper::getUrl();
        $token = $req->bearerToken();
        $encode_token =  Helper::encode_token($token);
        $customerId = $encode_token;
        $customerData =  Customer::where('id',$customerId)->get()->first(); 
        if(!empty($customerData)){
            $notificationData = Notification::where('customerId',$customerId)->where('readStatus',0)->where('type','customer')->orwhere('type','eventPlanner')->limit(5)->orderBy('id','DESC')->get()->toArray();
            $questionnairData = Transaction::select('questionnaires.id','questionnaires.eventName','questionnaires.farEventDate',DB::raw('(CASE WHEN images.image = "" THEN "" ELSE CONCAT("'.$imageUrl .'","customer/customer",questionnaires.customerId,"/", images.image) END) AS image'))
                                ->join('questionnaires','questionnaires.id','transactions.questionnaireId')
                                ->leftjoin('images','images.questionnaireId','questionnaires.id')
                                ->groupBy('images.questionnaireId')
                                ->where('transactions.customerId',$customerId)
                                ->where('questionnaires.status',2)->where('transactions.status',1)->orderBy('questionnaires.id','DESC')->limit(5)->get()->toArray();
            
            $messagesData = Message::select("messages.*")
                                ->selectRaw(DB::raw('COALESCE(c.name,a.name,CONCAT(d.firstName, " ", d.lastName)) as name'))
                                   ->leftjoin('customers as c', function($join){
                                       $join->on('messages.sentby', '=', 'c.id')
                                       ->where('messages.sendType', '=', 2);
                                   })
                                   ->leftjoin('sellers AS d', function($leftjoin){
                                       $leftjoin->on('messages.sentby', '=', 'd.id')
                                       ->where('messages.sendType', '=', 3);
                                   })
                                   ->leftjoin('admin AS a', function($leftjoin){
                                       $leftjoin->on('messages.sentby', '=', 'a.id')
                                       ->where('messages.sendType', '=', 1);
                                   })
                         ->where('messages.sentby',$customerId)
                         ->where('messages.sendType','!=',2)
                         ->orderBy('messages.id','DESC')
                         ->limit(5)
                        ->get()
                        ->toArray();
            $data['notification'] = $notificationData;
            $data['ongoing'] = $questionnairData;
            $data['messages'] = $messagesData;
            if(!empty($data)){
                $data = Helper::removeNull($data);
                return response()->json(['data'=>$data,'status'=>true,'message'=>'Dashboard','token'=>''], $this->success); 
            }else{
                return response()->json(['data'=>[],'status'=>true,'message'=>'Data Empty','token'=>''], $this->success); 
            }
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
        }
    }
    
    public function offerCustomer(Request $req){
       
        $imageUrl = Helper::getUrl();
        $token = $req->bearerToken();
        $customerId =  Helper::encode_token($token);
        $customerData =  Customer::where('id',$customerId)->get()->first(); 
        if(!empty($customerData)){
            $invitationId = !empty($customerData->invitationId) ? $customerData->invitationId : 0 ;
            $eventId = $req->questionnaireId;
            $questionnairData = Questionnaire::where('id',$eventId)->where('customerId',$customerId)->orwhere('customerId',$invitationId)->get()->first();
            if($questionnairData){
                  $offerData = Offer::select('offers.*','q.eventName','s.firstName','s.lastName',DB::raw('(CASE WHEN s.profileImage = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",s.id,"/", s.profileImage) END) AS profileImage'))
                                      ->join('sellers as s','s.id','offers.sellerId')
                                      ->join('questionnaires as q','q.id','offers.questionnaireId')
                                      ->where('questionnaireId',$eventId)->get()->toArray();
                  if(!empty($offerData)){
                      return response()->json(['data'=>$offerData,'status'=>true,'message'=>'Offers','token'=>''], $this->success); 
             
                  }else{
                     return response()->json(['data'=>[],'status'=>true,'message'=>'Data Empty','token'=>''], $this->success); 
             
                  }
                 
             }else{
                return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Questionnaire Id','token'=>''], $this->success);   
             }
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
        }
    }   



    public function cusromerEventsDetails(Request $req){
        $html = '';
        $partyServiceCatgeory = [];
        $partyServiceSubCatgeory = [];
        $Catgeory =[];
        $Catgeory1 = '';
        $imageUrl = Helper::getUrl();
        $token = $req->bearerToken();
        $customerId =  Helper::encode_token($token);
        $customerData =  Customer::where('id',$customerId)->get()->first();
        if(!empty($customerData)){
                 $eventId = $req->eventId;
                 $questionnairData = Questionnaire::find($eventId);
                 if($questionnairData){
                        $questionnairData = Questionnaire::select('questionnaires.*','v.name as vennuName','th.name as themeName','questionnaires.customerId','questionnaires.eventName','questionnaires.confirmationPartyPlanner as address',DB::raw('(CASE WHEN questionnaires.status = 1 THEN "Finish" WHEN questionnaires.status = 2 THEN "Ongoing"  ELSE "Pending " END) AS status'),'questionnaires.interactionDate',DB::raw('(CASE WHEN questionnaires.guestExpectEnd = "" THEN questionnaires.guestExpectStart ELSE CONCAT(questionnaires.guestExpectStart,"-",questionnaires.guestExpectEnd) END) AS guest'),'t.created_at as projectStartDate','c.name',DB::raw('(CASE WHEN c.profileImage = "" THEN "" ELSE CONCAT("'.$imageUrl .'","customer/customer",c.id,"/", c.profileImage) END) AS profileImage'),DB::raw("SUM(t.amount) as amount"),DB::raw("SUM(t.vat) as vat"),DB::raw("((SUM(t.amount)*SUM(t.vat))/100) as totalvatAmount"),DB::raw("SUM(t.totalAmount) as totalAmount"),'e.name as typeEvent')
                                                ->leftjoin('customers as c','c.id','questionnaires.customerId')
                                                ->leftjoin('themes as th','th.id','questionnaires.themeEvent')
                                                ->leftjoin('events as e','e.id','questionnaires.typeEvent')
                                                ->leftjoin('venues as v','v.id','questionnaires.vennuValue')
                                                ->leftjoin('transactions as t', function($join){
                                                    $join->on('questionnaires.id', '=', 't.questionnaireId')
                                                    ->where('t.status', '=', 1);
                                                })
                                                ->where('questionnaires.id',$eventId)
                                                ->get()->first();
                        $partyPlaningServiceCatgeory = !empty($questionnairData->partyPlaningServiceCatgeory) ? $questionnairData->partyPlaningServiceCatgeory : '' ;
                        $partyPlaningServiceSubCatgeory = !empty($questionnairData->partyPlaningServiceSubCatgeory) ? $questionnairData->partyPlaningServiceSubCatgeory : '' ;
                        
                        if(!empty($partyPlaningServiceCatgeory)){
                            $partyPlaningServiceCatgeory = explode(',',$partyPlaningServiceCatgeory);
                            $ServiceCatgeory   = Service::whereIn('id',$partyPlaningServiceCatgeory)->get()->toArray();
                            
                        /*     if(!empty($ServiceCatgeory)){
                                foreach($ServiceCatgeory as  $partyCatgeory){
                                  $Catgeory[] =  $partyCatgeory['category'];
                                }
                                $Catgeory1 = implode(',', $Catgeory);
                          } */
                      
                        }
            
                        if(!empty($partyPlaningServiceSubCatgeory)){
                            $partyPlaningServiceSubCatgeory = explode(',',$partyPlaningServiceSubCatgeory);
                            $ServiceSubCatgeory   = Service::whereIn('id',$partyPlaningServiceSubCatgeory)->get()->toArray();
                            $partyServiceSubCatgeory =  $ServiceSubCatgeory;
                            if(!empty($partyServiceSubCatgeory)){
                                foreach($partyServiceSubCatgeory as  $partyCatgeory){
                                $Catgeory[]  =   $partyCatgeory['category'];
                                }
                                $Catgeory1 = implode(',', $Catgeory);
                        }
                        }  
                        $html .= 'Level of Service : '.Helper::eventType($questionnairData['levelOfService']).' / '.Helper::eventType($questionnairData['levelOfServicePlanningType']).' <br>';

                     //   $html  .= '   Budget :  '.$questionnairData['budgetRangeStart'].'  - '.$questionnairData['budgetRangeEnd'].'<br>';
                     $html  .=    'Event Planning :  '.Helper::eventType($questionnairData['eventPlanning']).'<br>';
                     if($questionnairData['themeEvent'] == 'other'){
                        $html  .=   'Theme Event : '.$questionnairData['themeEventOther'].'<br>';
                    }else if($questionnairData['themeEvent'] == 'notheme'){
                        $html  .=   'Theme Event : '.Helper::eventType($questionnairData['themeEvent']).'<br>';
                    }else{
                        $html  .=   'Theme Event : '.$questionnairData['themeName'].'<br>'; 
                    }
                     //   $html  .=    'Party Planner :  '.Helper::eventType($questionnairData['partyPlanner']).'<br>';
                       
                    
                               
                        $html  .=    'Party Planing Service :  '.$Catgeory1.'<br>';
                        $html  .=    'Venue :  ';
                          if($questionnairData['vennu'] == 'yes'){               
                            $html  .=  Helper::eventType($questionnairData['vennuValue']);
                          }else if($questionnairData['vennu'] == 'no'){
                            $html  .=   $questionnairData['vennuName'];
                          }else{
                          
                            $html  .=  'Virtual';
                          }
    
                        $questionnairData['description'] = $html;
                        $milestones = Milestone::where('questionnaireId',$eventId)->get()->toArray();

                        $cartData =   $cartData = Cart::select('p.id',  DB::raw('(SELECT(CASE WHEN i.image Is Null THEN "" WHEN i.image = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",p.sellerId,"/", i.image) END) FROM product_images as i WHERE i.productId=carts.productId LIMIT 1) AS image'),'.p.name','carts.quantity','p.regularPrice','p.salePrice','q.eventName',DB::raw('CONCAT(s.firstName," ",s.lastName) as vendorName'))
                                        ->join('vendor_products as p','p.id','carts.productId')
                                        ->join('sellers as s','s.id','p.sellerId')
                                        ->join('questionnaires as q','q.id','carts.eventId')
                                     //   ->leftjoin('product_images as i','i.productId','carts.productId')
                                        ->where('carts.eventId',$eventId)
                                        ->where('carts.status',2)
                                        //->groupBy('i.productId')
                                        ->orderBy('carts.id')->get()->toArray();
                    //    $tax = AdminSetting::find(1);
                        if(!empty($questionnairData)) {
                             $questionnairData['milestones'] = $milestones;
                             $questionnairData['products'] = $cartData;
                            // $questionnairData['tax'] = $tax->tax;
                            $questionnairData = Helper::removeNull($questionnairData);
                            return response()->json(['data'=>$questionnairData,'status'=>true,'message'=>'Event Details','token'=>''], $this->success); 
                        }else{
                            return response()->json(['data'=>$data,'status'=>true,'message'=>'Data Empty','token'=>''], $this->success); 
                        }  
                 }else{
                    return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Id','token'=>''], $this->success);   
                 }

        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
        }
    }


    public function customerVendorList(Request $req){
      
            $distance = env('MIX_Distance_LIMIT');
            
            $experience = $req->experience;
            $category = $req->category;
            $subcategory = $req->subcategory;
            $location = $req->location;
            $pageNo = $req->pageNo;
            $limit = !empty(env('PR_PAGE_DATA')) ? env('PR_PAGE_DATA') : 10 ;
            $start = !empty($pageNo) ? ($pageNo - 1)*$limit : 0 ;
            $imageUrl = Helper::getUrl();
            $token = $req->bearerToken();
            $customerId =  Helper::encode_token($token);
            $customerData =  Customer::where('id',$customerId)->get()->first();
            if(!empty($customerData)){
                $invitationId = !empty($customerData->invitationId) ? $customerData->invitationId : 0 ;
                $eventId = $req->eventId;
                $questionnairData = Questionnaire::where('id',$eventId)->where('customerId',$customerId)->orwhere('customerId',$invitationId)->get()->first();
                $levelOfService = !empty($questionnairData->levelOfService) ? $questionnairData->levelOfService : '' ;
                if($questionnairData){
                    $vendorData  =  EventVendor::select('sellers.*',DB::raw('(CASE WHEN sellers.profileImage = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",sellers.id,"/", sellers.profileImage) END) AS profileImage'),'pr.ratting_cnt',DB::raw('round(pr.seller_rate/pr.ratting_cnt) as rating'))
                                            ->join('sellers','sellers.id','event_vendors.vendorId')
                                            ->leftjoin('vendors','vendors.sellerId','sellers.id')
                                            ->leftjoin(DB::raw('(select pr1.sellerId, sum(pr1.rating) as seller_rate, count(1) as ratting_cnt from reviews pr1 group by pr1.sellerId) as pr'), function($join){
                                                $join->on('pr.sellerId', '=', 'sellers.id');
                                            })
                                            ->where('sellers.type',1)
                                            ->where('event_vendors.questionnaireId',$eventId)
                                            ->where('sellers.status',1);
                   
                    
                    if(!empty($category)){
                        $data = $vendorData->whereRaw("FIND_IN_SET($category,vendors.servicesCategory)");
                    }
                    if(!empty($subcategory)){
                        $data = $vendorData->whereRaw("FIND_IN_SET($subcategory,vendors.serviceSubCategory)");
                    }

                     if(!empty($location)){
                        $data = $vendorData->where('sellers.location', 'LIKE', '%' . $location . '%');
                    }
                     if(!empty($experience)){
    
                        $data = $vendorData->where('vendors.workingindustry',$experience);
                       
                    }
                     if(!empty($skill)){
                        $data = $vendorData->where('vendors.personalityPlanners', 'LIKE', '%' . $skill . '%');
                    }
                    $data = $vendorData;
                    $count = $data->count();
                    $data = $data->offset($start)->limit($limit)->get()->makeHidden(['password','remember_token','status'])->toArray();
                   /*  if(!empty($data)){
                        $data = $data;
                    }else{
                     
                        $vendorData =  Seller::select('sellers.*',DB::raw('(CASE WHEN sellers.profileImage = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",sellers.id,"/", sellers.profileImage) END) AS profileImage'))
                                            ->leftjoin('vendors','vendors.sellerId','sellers.id')
                                            ->where('sellers.type',1)
                                            ->where('sellers.status',1)
                                            ->orwhere('vendors.willingWork',$levelOfService)
                                            ->orwhere('vendors.willingWork','both');
                            $count = $vendorData->count();
                            $data = $vendorData->offset($start)->limit($limit)->get()->makeHidden(['password','remember_token','status'])->toArray();
                                           
                    }
                    */
                   
                    if(!empty($data)){
                        $vendors['vendors'] = Helper::removeNull($data);
                        $vendors['totalPage'] =  ceil($count/$limit);
                      return response()->json(['data'=>$vendors,'status'=>true,'message'=>'vendor List','token'=>''], $this->success); 
                    }else{
                      return response()->json(['data'=>[],'status'=>true,'message'=>'Data List Empty','token'=>''], $this->success); 
                    }
                }else{
                    return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Id','token'=>''], $this->success);   
                 }
            }else{
                return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
            }
    }


    public function checkProductQuantity(Request $req){
        $imageUrl = Helper::getUrl();
        $token = $req->bearerToken();
        $customerId =  Helper::encode_token($token);
        $customerData =  Customer::where('id',$customerId)->get()->first();
        if(!empty($customerData)){
                $productId = $req->productId;
                $eventId = $req->eventId;
                $inputQuantity = !empty($req->quantity) ? $req->quantity : 1;
                $productData = Vendor_product::find($productId);
                $questionnairData = Questionnaire::where('id',$eventId)->where('customerId',$customerId)->get()->first();
                $AdminSetting = AdminSetting::all()->first()->toArray();
                $eventDay = !empty($AdminSetting->eventDay) ? $AdminSetting->eventDay : 0 ;
                if(!empty($questionnairData)){
                    $eventDate = !empty($questionnairData->farEventDate) ? $questionnairData->farEventDate : '';
                    $eventDateStart  = date('Y-m-d 00:00:01',strtotime($eventDate."-2 day"));
                    $eventDateEnd  = date('Y-m-d 23:59:59',strtotime($eventDate."+2 day"));
                    if(!empty($productData)){
                         $quantity = !empty($productData->quantity) ? $productData->quantity : 0 ;
                         if($inputQuantity <= $quantity){
                              $Transaction = Transaction::where('productId',$productId)->whereBetween('created_at',[$eventDateStart,$eventDateEnd])->where('status',1)->sum('transactions.quantity');
                              
                                if(!empty($Transaction)){
                                    if($Transaction <= $inputQuantity){
                                        return response()->json(['data'=>'','status'=>true,'message'=>'success','token'=>''], $this->success); 
                                    }else{
                                        return response()->json(['data'=>'','status'=>false,'message'=>'Quantity Not Sufficient','token'=>''], $this->success); 
                                    }
                                }else{
                                    return response()->json(['data'=>'','status'=>true,'message'=>'success','token'=>''], $this->success); 
                                }
                           }else{
                            return response()->json(['data'=>'','status'=>false,'message'=>'Quantity Not Sufficient','token'=>''], $this->success); 
                         }
                    }else {
                        return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Product Id','token'=>''], $this->success); 
                    }
                }else {
                    return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Event Id','token'=>''], $this->success); 
                }
       }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Customer Token','token'=>''], $this->error); 
       } 
    }


    public function cusromerEvents(Request $req){
        $imageUrl = Helper::getUrl();
        $token = $req->bearerToken();
        $customerId =  Helper::encode_token($token);
        $customerData =  Customer::where('id',$customerId)->get()->first();
        if(!empty($customerData)){
            $invitationId = !empty($customerData->invitationId) ? $customerData->invitationId : 0 ;
            $pageNo = $req->pageNo;
            $limit = !empty(env('PR_PAGE_DATA')) ? env('PR_PAGE_DATA') : 10 ;
            $start = !empty($pageNo) ? ($pageNo - 1)*$limit : 0 ;
                $questionnair = Questionnaire::select('questionnaires.id','questionnaires.tokenId','questionnaires.customerId','questionnaires.eventName','questionnaires.confirmationPartyPlanner as address','questionnaires.farEventDate','c.name',DB::raw('(CASE WHEN c.profileImage = "" THEN "" ELSE CONCAT("'.$imageUrl .'","customer/customer",c.id,"/", c.profileImage) END) AS profileImage'),DB::raw('SUM(t.totalAmount) as totalAmount'))
                                            ->leftjoin('customers as c','c.id','questionnaires.customerId')
                                            ->leftjoin('transactions as t', 'questionnaires.id', '=', 't.questionnaireId')
                                            ->groupBy('t.questionnaireId')
                                            ->where('t.status', '=', 1)
                                            ->where('t.customerId', '=', $customerId)
                                            ->orwhere('t.customerId', '=', $invitationId);
                $questionnairData = $questionnair->offset($start)->limit($limit)->get()->toArray(); 
                $data['event'] = $questionnairData; 
                $data['totalPage'] = ceil($questionnair->count()/$limit); 
                if(!empty($questionnairData)){
                    $data = Helper::removeNull($data);
                           return response()->json(['data'=>$data,'status'=>true,'message'=>'Event List','token'=>''], $this->success); 
                }else{
                    return response()->json(['data'=>[],'status'=>true,'message'=>'Data List Empty','token'=>''], $this->success); 
                }
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
        }
    }


    public function cusromerTransactionEventDatils(Request $req){
        $imageUrl = Helper::getUrl();
        $token = $req->bearerToken();
        $customerId =  Helper::encode_token($token);
        $customerData =  Customer::where('id',$customerId)->get()->first();
        if(!empty($customerData)){
            $pageNo = $req->pageNo;
            $eventId = $req->eventId;
            $limit = !empty(env('PR_PAGE_DATA')) ? env('PR_PAGE_DATA') : 10 ;
            $start = !empty($pageNo) ? ($pageNo - 1)*$limit : 0 ;
                $questionnair = Transaction::select('transactions.*',DB::raw('(CASE WHEN transactions.invoice = "" THEN "" ELSE CONCAT("'.$imageUrl .'","customer/customer",c.id,"/", transactions.invoice) END) AS invoice'),'p.title as planeName','vp.name as productName','questionnaires.tokenId','questionnaires.customerId','questionnaires.eventName','questionnaires.confirmationPartyPlanner as address','questionnaires.farEventDate','c.name',DB::raw('(CASE WHEN c.profileImage = "" THEN "" ELSE CONCAT("'.$imageUrl .'","customer/customer",c.id,"/", c.profileImage) END) AS profileImage'),DB::raw('(CASE WHEN transactions.status = 1 THEN "Success" WHEN transactions.status = 3 THEN "Refunded" ELSE "Fail" END) as status'))
                                            ->leftjoin('customers as c','c.id','transactions.customerId')
                                            ->leftjoin('plannerplans as p','p.id','transactions.planId')
                                            ->leftjoin('vendor_products as vp','vp.id','transactions.productId')
                                           // ->leftjoin('offers as of','of.id','transactions.offerId')
                                            ->leftjoin('questionnaires', 'questionnaires.id', '=', 'transactions.questionnaireId')
                                            ->where('transactions.status', '=', 1)
                                            ->where('transactions.customerId', '=',$customerId)
                                            ->where('transactions.questionnaireId', '=',$eventId);
                $questionnairData = $questionnair->offset($start)->limit($limit)->get()->toArray(); 
                $data['transactions'] = $questionnairData; 
                $data['totalPage'] = ceil($questionnair->count()/$limit); 
                if(!empty($questionnairData)){
                    $data = Helper::removeNull($data);
                           return response()->json(['data'=>$data,'status'=>true,'message'=>'Event List','token'=>''], $this->success); 
                }else{
                    return response()->json(['data'=>[],'status'=>true,'message'=>'Data List Empty','token'=>''], $this->success); 
                }
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
        }
    }


    public function customermoodboardsList(Request $req){
        $imageUrl = Helper::getUrl();
        $token = $req->bearerToken();
        $customerId =  Helper::encode_token($token);
        $customerData =  Customer::where('id',$customerId)->get()->first();
        if(!empty($customerData)){
            $pageNo = $req->pageNo;
            $eventId = $req->eventId;
            $limit = !empty(env('PR_PAGE_DATA')) ? env('PR_PAGE_DATA') : 10 ;
            $start = !empty($pageNo) ? ($pageNo - 1)*$limit : 0 ;
            $data =  Moodboard::select("*",DB::raw('(CASE WHEN previewImage = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",sellerId,"/", previewImage) END) AS previewImage'))->where('eventId',$eventId);
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
    
    public function moodboardsAlbum(Request $req){
        $imageUrl = Helper::getUrl();
        $token = $req->bearerToken();
        $customerId =  Helper::encode_token($token);
        $customerData =  Customer::where('id',$customerId)->get()->first();
        if(!empty($customerData)){
                $moodboardId = $req->moodboardId;
                $moodboardData = Moodboard::select("*",DB::raw('(CASE WHEN previewImage = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",sellerId,"/", previewImage) END) AS previewImage'))->where('id',$moodboardId)->get()->first();
                if(!empty($moodboardData)){
                    $sellerId = !empty($moodboardData->sellerId) ? $moodboardData->sellerId : 0;
                    $moodboardData['moodboardimage'] = MoodboardImage::select("*",DB::raw('(CASE WHEN image = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor","'. $sellerId.'","/", image) END) AS image'))->where('moodboardId',$moodboardId)->get()->toArray();
                  return response()->json(['data'=>$moodboardData,'status'=>true,'message'=>'success','token'=>''], $this->success); 
              }else{
                  return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Id','token'=>''], $this->success); 
              }
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
        }
      }

      public function selectAlbumImage(Request $req){
        $imageUrl = Helper::getUrl();
        $token = $req->bearerToken();
        $customerId =  Helper::encode_token($token);
        $customerData =  Customer::where('id',$customerId)->get()->first();
        if(!empty($customerData)){
                $imageId = $req->imageId;
                $status = $req->status;
                $moodboardimage = MoodboardImage::where('id',$imageId)->get()->first();
                if(!empty($moodboardimage)){
                    $moodboardimage->customerId  = $customerId;
                    $moodboardimage->status  = $status;
                    $moodboardimage->save();
                  return response()->json(['data'=>'','status'=>true,'message'=>'success','token'=>''], $this->success); 
              }else{
                  return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Id','token'=>''], $this->success); 
              }
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
        }
      }

    
    public function customerSelectVendor(Request $req){
        $imageUrl = Helper::getUrl();
        $token = $req->bearerToken();
        $customerId =  Helper::encode_token($token);
        $customerData =  Customer::where('id',$customerId)->get()->first();
        if(!empty($customerData)){
            $invitationId = !empty($customerData->invitationId) ? $customerData->invitationId : 0 ;
            $eventId = $req->eventId;
            $questionnairData = Questionnaire::where('id',$eventId)->where('customerId',$customerId)->get()->first();
            if(!empty($questionnairData)){
                $vendorId = $req->vendorId;
                $vendorData = Seller::where('id',$vendorId)->where('type',1)->get()->first();
                if(!empty($vendorData)){
                        $CustomerVendor = CustomerVendor::where('eventId',$eventId)->where('sellerId',$vendorId)->where('customerId',$customerId)->orwhere('customerId',$invitationId)->get()->first();
                        if(empty($CustomerVendor)){
                            $data = new CustomerVendor;
                            $data->eventId = $eventId;
                            $data->sellerId = $vendorId;
                            $data->customerId = $customerId;
                            $data->save() ;
                        }
                     
                    return response()->json(['data'=>'','status'=>true,'message'=>'success','token'=>''], $this->success); 
                }else{
                    return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Vendor Id','token'=>''], $this->success); 
                }
            }else{
                return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Event Id','token'=>''], $this->success);
            } 
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
        }
      }

    public function eventSellers(Request $req){
        $imageUrl = Helper::getUrl();
        $eventId = $req->eventId;
        $questionnairData = Questionnaire::where('id',$eventId)->get()->first();
        if(!empty($questionnairData)){
            $sellerId = !empty($questionnairData->sellerId) ? $questionnairData->sellerId : '' ;
            $customerId = !empty($questionnairData->customerId) ? $questionnairData->customerId : '' ;
            $sellerId = explode(',',$sellerId);
            $sellerData = Seller::select('sellers.*',DB::raw('(CASE WHEN sellers.type = "1" THEN "Vendor"  ELSE "Planner"  END) AS type'),DB::raw('(CASE WHEN sellers.profileImage = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",sellers.id,"/", sellers.profileImage) END) AS profileImage'),'pr.ratting_cnt',DB::raw('round(pr.seller_rate/pr.ratting_cnt) as rating'),DB::raw('(select f.status  from favorites f where sellers.id = f.sellerId AND f.customerId = '.$customerId.') as favorite'))
           
              ->leftjoin(DB::raw('(select pr1.sellerId, sum(pr1.rating) as seller_rate, count(1) as ratting_cnt from reviews pr1 group by pr1.sellerId) as pr'), function($join){
                $join->on('pr.sellerId', '=', 'sellers.id');
                })

                ->whereIn('sellers.id',$sellerId)
           
                ->get()->toArray();
            $sellerData = Helper::removeNull($sellerData);
            return response()->json(['data'=> $sellerData,'status'=>true,'message'=>'success','token'=>''], $this->success); 
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Event Id','token'=>''], $this->success);
        }
    }
    
    public function markFavorite(Request $req){
        $imageUrl = Helper::getUrl();
        $token = $req->bearerToken();
        $customerId =  Helper::encode_token($token);
        $customerData =  Customer::where('id',$customerId)->get()->first();
        if(!empty($customerData)){
                $sellerId = $req->sellerId;
                $sellerData = Seller::where('id',$sellerId)->get()->first();
                if(!empty($sellerData)){
                        $favoriteData = Favorite::where('customerId',$customerId)->where('sellerId',$sellerId)->get()->first();
                        if(empty($favoriteData)){
                            $data =  new Favorite;
                            $data->customerId = $customerId;
                            $data->sellerId = $sellerId;
                            $data->status = 1;
                            $data->save();
                        }
                        
                    return response()->json(['data'=>'','status'=>true,'message'=>'success','token'=>''], $this->success); 
                }else{
                    return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Seller Id','token'=>''], $this->success); 
                }
            
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
        }
    }

    public function unmarkFavorite(Request $req){
        $imageUrl = Helper::getUrl();
        $token = $req->bearerToken();
        $customerId =  Helper::encode_token($token);
        $customerData =  Customer::where('id',$customerId)->get()->first();
        if(!empty($customerData)){
                $sellerId = $req->sellerId;
                $sellerData = Seller::where('id',$sellerId)->get()->first();
                if(!empty($sellerData)){
                      $favoriteData = Favorite::where('customerId',$customerId)->where('sellerId',$sellerId)->get()->first();
                       if(!empty($favoriteData)){
                          $favoriteData->delete();
                       }
                    return response()->json(['data'=>'','status'=>true,'message'=>'success','token'=>''], $this->success); 
                }else{
                    return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Seller Id','token'=>''], $this->success); 
                }
            
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
        }
    }


    public function favoriteSellers(Request $req){
        $imageUrl = Helper::getUrl();
        $token = $req->bearerToken();
        $customerId =  Helper::encode_token($token);
        $customerData =  Customer::where('id',$customerId)->get()->first();
        if(!empty($customerData)){
                 $favoriteData = Favorite::select('s.*',DB::raw('(CASE WHEN s.type = "1" THEN "Vendor"  ELSE "Planner"  END) AS type'),DB::raw('(CASE WHEN s.profileImage = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",s.id,"/", s.profileImage) END) AS profileImage'),'pr.ratting_cnt',DB::raw('round(pr.seller_rate/pr.ratting_cnt) as rating'),'favorites.status as favorite')
                                   ->join('sellers as s','s.id','favorites.sellerId')
                                   ->leftjoin(DB::raw('(select pr1.sellerId, sum(pr1.rating) as seller_rate, count(1) as ratting_cnt from reviews pr1 group by pr1.sellerId) as pr'), function($join){
                                    $join->on('pr.sellerId', '=', 's.id');
                                    })
                                   ->where('favorites.customerId',$customerId)->get()->makeHidden(['password','status','remember_token'])->toArray();
                if(!empty($favoriteData))  {
                    $favoriteData = Helper::removeNull($favoriteData);
                    return response()->json(['data'=>$favoriteData,'status'=>true,'message'=>'success','token'=>''], $this->success); 
               
                } else{
                    return response()->json(['data'=>'','status'=>true,'message'=>'Data Is Empty','token'=>''], $this->success); 
                }
                   
            
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
        }
    }


    public function submitReview(Request $req){
        $imageUrl = Helper::getUrl();
        $token = $req->bearerToken();
        $customerId =  Helper::encode_token($token);
        $customerData =  Customer::where('id',$customerId)->get()->first();
        if(!empty($customerData)){
                $sellerId = $req->sellerId;
                $sellerData = Seller::where('id',$sellerId)->get()->first();
                if(!empty($sellerData)){
                    $validator = Validator::make($req->all(),[ 
                        'rating' => 'nullable|numeric|max:5',
                    ]);
                
                 if ($validator->fails()){ 
                       return response()->json(['data'=>'','status'=>false,'message'=>$validator->errors()->all(),'token'=>''], $this->success);            
                 }else{ 
                        $data =  new Review;
                        $data->customerId = $customerId;
                        $data->sellerId = $sellerId;
                        $data->rating = $req->rating;
                        $data->comment  = $req->comment; 
                        $data->save();
                        return response()->json(['data'=>'','status'=>true,'message'=>'success','token'=>''], $this->success); 
                 }   
                 }else{
                    return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Seller Id','token'=>''], $this->success); 
                }
            
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
        }
    }

    public function reviewEdit(Request $req){
        $id = $req->id;
        $data =   Review::find($id);
        if(!empty($data)){
               
                    $validator = Validator::make($req->all(),[ 
                        'rating' => 'nullable|numeric|max:5',
                    ]);
                
                 if ($validator->fails()){ 
                       return response()->json(['data'=>'','status'=>false,'message'=>$validator->errors()->all(),'token'=>''], $this->success);            
                 }else{ 
                       
                        
                       if(!empty($req->rating)){
                        $data->rating = $req->rating;
                       }
                       if(!empty($req->comment)){
                        $data->comment = $req->comment;
                       }
                        
                      
                        $data->save();
                        return response()->json(['data'=>'','status'=>true,'message'=>'success','token'=>''], $this->success); 
                 }   
        }else{
                    return response()->json(['data'=>'','status'=>false,'message'=>'Invalid  Id','token'=>''], $this->success); 
        }
            
      
    }

    public function shareEvent(Request $req){
        $imageUrl = Helper::getUrl();
        $token = $req->bearerToken();
        $customerId =  Helper::encode_token($token);
        $customerData =  Customer::where('id',$customerId)->get()->first();
        if(!empty($customerData)){
                $customerEmail = !empty($customerData->email) ? $customerData->email : '' ;
                $customerName = !empty($customerData->name) ? $customerData->name : '' ;
                $customerSurName = !empty($customerData->surname) ? $customerData->surname : '' ;
                $customerName = $customerName.' '.$customerSurName;
                $eventId = $req->eventId;
                $questionnairData = Questionnaire::where('id',$eventId)->where('customerId',$customerId)->get()->first();
                $tokenId = !empty($questionnairData->tokenId) ? $questionnairData->tokenId : '' ;
                $eventName = !empty($questionnairData->eventName) ? $questionnairData->eventName : '' ;
                if(!empty($questionnairData)){
                    $validator = Validator::make($req->all(),[ 
                        'email' => 'required|string|email',
                    ]);
                
                 if ($validator->fails()){ 
                       return response()->json(['data'=>'','status'=>false,'message'=>$validator->errors()->all(),'token'=>''], $this->success);            
                 }else{ 
                       $email = $req->email;
                       if($email != $customerEmail){
                        $invitecustomerData = Customer::where('email',$email)->get()->first();
                        $invitecustomermobile = !empty($invitecustomerData->mobile) ? $invitecustomerData->mobile : '';
                        $ShareEventData = ShareEvent::where('email',$email)->where('eventId',$eventId)->get()->first();
                        $invitecustomerId = !empty($invitecustomerData->id) ? $invitecustomerData->id : '' ;
                        if(empty($ShareEventData)){
                            $data =  new ShareEvent;
                            $data->customerId = $invitecustomerId;
                            $data->eventId = $eventId;
                            $data->email = $email;
                            $data->save();
                            $subject = 'Event Invited';
                            $email_data = ['email' =>$email,'user'=>'customer','subject' => $subject];    
                             Helper::send_mail('emailTemplate.welcome',$email_data);


                            if(!empty($invitecustomermobile)){
                                $mobile = $invitecustomermobile;
                                $msg1 = str_replace("{customer}",$customerName,Config::get('msg.eventShare'));
                                $msg2 = str_replace("{eventId}",$tokenId,$msg1); 
                                $msg = str_replace("{eventName}",$tokenId,$msg2); 
                               
                                Helper::sendMessage($msg,$mobile);
                            }
                            return response()->json(['data'=>'','status'=>true,'message'=>'success','token'=>''], $this->success); 
                           
                        }else{
                            return response()->json(['data'=>'','status'=>false,'message'=>'Already Invited','token'=>''], $this->success); 
                        }
                    }else{
                        return response()->json(['data'=>'','status'=>false,'message'=>'Same Customer Not Invited','token'=>''], $this->success); 
                    }
                   }   
                 }else{
                    return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Event Id','token'=>''], $this->success); 
                }
            
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
        }
    }
   
    
}
