<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Str;
use Validator;
use Hash;
use DB;
use Helper;
use Socket;
use App\Cart;
use App\AdminSetting;
use App\Service;
use App\Offer;
use App\Seller;
use App\Planner;
use App\Vendor;
use App\Notification;
use App\PlannerPlan;
use App\Message;
use App\Transaction;
use App\Project_image;
use App\Account_information;
use App\Questionnaire;
use App\Milestone;
use App\MessageGroup;
use App\SingleMessagingGroup;
use App\MessageNotification;
use App\EventVendor;



class PlannerController extends Controller
{
    public $success = 200;
    public $error = 401;
     

    public function planList(Request $req){
        $imageUrl = Helper::getUrl();
        $token = $req->bearerToken();
        $encode_token =  Helper::encode_token($token);
            
        if(!empty($encode_token)){
            $sellerId = $encode_token;
            $sellerData =  Seller::where('id',$sellerId)->where('type',2)->get()->first();  
            
                if(!empty($sellerData)){ 
                     
                    $PlannerPlans  = PlannerPlan::select('*',DB::raw('(CASE WHEN image = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",sellerId,"/",image) END) AS image'))->where('sellerId',$sellerId)->get()->toArray();
                    if(!empty($PlannerPlans)){
                        $PlannerPlans = Helper::removeNull($PlannerPlans);
                        return response()->json(['data'=>$PlannerPlans,'status'=>true,'message'=>'Plans List','token'=>''], $this->success); 
                    }else{
                        return response()->json(['data'=>'','status'=>true,'message'=>'Data Empty','token'=>''], $this->success);  
                    }
                    
                }else{
                return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
              }
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
        }
    }

    public function planAdd(Request $req){
        
        $token = $req->bearerToken();
        $encode_token =  Helper::encode_token($token);
            
        if(!empty($encode_token)){
            $sellerId = $encode_token;
            $sellerData =  Seller::where('id',$sellerId)->where('type',2)->get()->first();  
            
                if(!empty($sellerData)){
                    if($req->isCustom == 1){
                        $validator = Validator::make($req->all(), [ 
                            'title' => 'required',
                            'description' => 'required', 
                     
                        ]);
                    }else{
                        $validator = Validator::make($req->all(), [ 
                            'title' => 'required',
                            'description' => 'required', 
                            'salePrice' => 'nullable',
                            'regularPrice' => 'required|numeric|greater_than_field:salePrice',  
                        ]);
                    }
                   
                        if ($validator->fails()) { 
                            return response()->json(['data'=>'','status'=>false,'message'=>$validator->errors()->all(),'token'=>''], $this->success);                           
                        }else{         
                              $PlannerPlanData = PlannerPlan::where('sellerId',$sellerId)->where('isCustom',1)->get()->first();
                              $PlannerPlan = new PlannerPlan;
                              $PlannerPlan->title = $req->title ;
                              $PlannerPlan->description = $req->description;
                             
                              if($req->isCustom == 1){
                                $PlannerPlan->salePrice = 0;
                                $PlannerPlan->regularPrice = 0;
                                $PlannerPlan->isCustom = $req->isCustom;
                              }else{
                                $PlannerPlan->isCustom = 0;
                                $PlannerPlan->salePrice = !empty($req->salePrice) ? $req->salePrice : 0;
                                $PlannerPlan->regularPrice = $req->regularPrice;
                              }
                              if ($req->hasFile('image')){
                                $image = $req->file('image');
                                $imageName = time().'.'.$req->image->extension();  
                                $imageName = str_replace( " ", "-", trim($imageName) ); 
                                $folder = Helper::imageUpload($imageName, $image,$folder="vendor/vendor".$sellerId."");
                                $PlannerPlan->image = $imageName;
                            }
                              $PlannerPlan->sellerId = $sellerId;
                              if($req->isCustom == 1){
                                   if(!empty($PlannerPlanData)){
                                    return response()->json(['data'=>'','status'=>false,'message'=>'Custom Plane Already Exists','token'=>''], $this->success); 
                                   }else{
                                    $PlannerPlan->save();
                                    return response()->json(['data'=>'','status'=>true,'message'=>'Plan Add Successfully','token'=>''], $this->success); 
                                   }
                                
                                 
                              }else{
                                $PlannerPlan->save();
                                return response()->json(['data'=>'','status'=>true,'message'=>'Plan Add Successfully','token'=>''], $this->success); 
                              }

                             
                              
                        }

            }else{
                return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
            }
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
        }
    }

    public function planEditData(Request $req){
        $imageUrl = Helper::getUrl();
        $planId = $req->input('planId');
        $PlannerPlan =  PlannerPlan::select('*',DB::raw('(CASE WHEN image = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",sellerId,"/",image) END) AS image'))->where('id',$planId)->get()->first();    
        if(!empty($PlannerPlan)){  
            $PlannerPlan = Helper::removeNull($PlannerPlan);
            return response()->json(['data'=>$PlannerPlan,'status'=>true,'message'=>'Plans List','token'=>''], $this->success); 
               
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid id','token'=>''], $this->success); 
        }
    }

    public function planEdit(Request $req){
        
        $planId = $req->input('planId');
        $PlannerPlan =  PlannerPlan::where('id',$planId)->get()->first();    
        $image = !empty($PlannerPlan->image) ? $PlannerPlan->image : '' ;
        $sellerId = !empty($PlannerPlan->sellerId) ? $PlannerPlan->sellerId : '' ;
        if(!empty($PlannerPlan)){ 
                
            if($req->isCustom == 1){
                $validator = Validator::make($req->all(), [ 
                    'title' => 'required',
                    'description' => 'required', 
             
                ]);
            }else{
                $validator = Validator::make($req->all(), [ 
                    'title' => 'required',
                    'description' => 'required', 
                    'salePrice' => 'nullable|numeric',
                    'regularPrice' => 'required|numeric|greater_than_field:salePrice',  
                ]);
            }
            if ($validator->fails()) { 
                return response()->json(['data'=>'','status'=>false,'message'=>$validator->errors()->all(),'token'=>''], $this->success);                           
            }else{  
                if(!empty($req->title)){
                    $PlannerPlan->title = $req->title ;
                }
                if(!empty($req->description)){
                    $PlannerPlan->description = $req->description ;
                }
                if($req->isCustom == 1){
                   $PlannerPlan->regularPrice = 0;
                   $PlannerPlan->salePrice = 0;
                   $PlannerPlan->isCustom = $req->isCustom ;
                
            }else{
                if(!empty($req->regularPrice)){
                    $PlannerPlan->regularPrice = $req->regularPrice ;
                }
                if(!empty($req->salePrice)){
                    $PlannerPlan->salePrice = $req->salePrice ;
                }
                if(!empty($req->isCustom)){
                    $PlannerPlan->isCustom = 0;
                }
            }

            if ($req->hasFile('image')){
                if(!empty($image)){
                    Helper::deleteImage("vendor/vendor".$sellerId."/".$image."");
                }
                $image = $req->file('image');
                $imageName = time().'.'.$req->image->extension();  
                $imageName = str_replace( " ", "-", trim($imageName) ); 
                $folder = Helper::imageUpload($imageName, $image,$folder="vendor/vendor".$sellerId."");
                $PlannerPlan->image = $imageName;
            }
                $PlannerPlan->save();
                return response()->json(['data'=>'','status'=>true,'message'=>'Plan Update Successfully','token'=>''], $this->success); 
            }
  
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Id','token'=>''], $this->success); 
        }
    }


    public function planDelete(Request $req){
        
        $planId = $req->input('planId');
        $PlannerPlan =  PlannerPlan::where('id',$planId)->get()->first();    
        if(!empty($PlannerPlan)){ 
            $PlannerPlan->delete();
            return response()->json(['data'=>'','status'=>true,'message'=>'Plan Delete Successfully','token'=>''], $this->success);     
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Id','token'=>''], $this->success); 
        }
    }

    
    public function plannerDashboardNotification(Request $req){
        $imageUrl = Helper::getUrl();
        $demoImage = asset('resources/images/demoImage.png');
        $pageNo = $req->pageNo;
        $token = $req->bearerToken();
        $sellerId =  Helper::encode_token($token);
        $sellerData =  Seller::where('id',$sellerId)->get()->first(); 
        if(!empty($sellerData)){
            
            $limit = !empty(env('PR_PAGE_DATA')) ? env('PR_PAGE_DATA') : 10 ;
            $start = !empty($pageNo) ? ($pageNo - 1)*$limit : 0 ;
            $count = Notification::where('toId',$sellerId)->where('readStatus',0)->where('type','seller')->count();
            $data['notificationData'] = Notification::select('notifications.*',DB::raw('( CASE WHEN notifications.sendType = "seller"  THEN  CONCAT("'.$imageUrl .'","vendor/vendor",d.id,"/", d.profileImage) WHEN notifications.sendType = "customer"  THEN  CONCAT("'.$imageUrl .'","customer/customer",c.id,"/", c.profileImage) WHEN notifications.sendType = "admin"  THEN  CONCAT("'.$imageUrl .'","admin/admin",a.id,"/", a.image) ELSE "'.$demoImage.'"  END)   AS image'))
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
                                        ->where('notifications.toId',$sellerId)->where('notifications.readStatus',0)->where('notifications.type','seller')->offset($start)->limit($limit)->orderBy('notifications.id','DESC')->get()->toArray();
            if(!empty($data['notificationData'])){
                $data = Helper::removeNull($data);
                $data['totalPage'] = ceil($count/$limit);
                return response()->json(['data'=>$data,'status'=>true,'message'=>'Dashboard Notification','token'=>''], $this->success); 
            }else{
                return response()->json(['data'=>'','status'=>true,'message'=>'Data Empty','token'=>''], $this->success); 
            }
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
        }

    }

    

    public function plannerDashboardOngoing(Request $req){
        $imageUrl = Helper::getUrl();
        $pageNo = $req->pageNo;
        $token = $req->bearerToken();
        $sellerId =  Helper::encode_token($token);
        $sellerData =  Seller::where('id',$sellerId)->get()->first(); 
        if(!empty($sellerData)){
            $limit = !empty(env('PR_PAGE_DATA')) ? env('PR_PAGE_DATA') : 10 ;
            $start = !empty($pageNo) ? ($pageNo - 1)*$limit : 0 ;
            $ongoing =   Questionnaire::select('questionnaires.*','questionnaires.eventName',DB::raw('(CASE WHEN images.image = "" THEN "" ELSE CONCAT("'.$imageUrl .'","customer/customer",questionnaires.customerId,"/", images.image) END) AS image'))
                                                            ->leftjoin('images','images.questionnaireId','questionnaires.id')
                                                            ->groupBy('images.questionnaireId')
                                                            ->whereRaw("find_in_set($sellerId,questionnaires.sellerId)")
                                                            ->where('questionnaires.status',2)->orderBy('questionnaires.created_at','DESC');
           $count = $ongoing->count();
            $data['ongoing'] = $ongoing->offset($start)->limit($limit)->get()->toArray();
            if(!empty($data['ongoing'])){
                $data = Helper::removeNull($data);
                $data['totalPage'] = ceil($count/$limit);
                return response()->json(['data'=>$data,'status'=>true,'message'=>'Dashboard Ongoing','token'=>''], $this->success); 
            }else{
                return response()->json(['data'=>'','status'=>true,'message'=>'Data Empty','token'=>''], $this->success); 
            }
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
        }

    }



    public function plannerDashboardMessgage(Request $req){
        $imageUrl = Helper::getUrl();
        $pageNo = $req->pageNo;
        $token = $req->bearerToken();
        $sellerId =  Helper::encode_token($token);
        $sellerData =  Seller::where('id',$sellerId)->get()->first(); 
        $demoImage = asset('resources/images/demoImage.png');
        if(!empty($sellerData)){
            $limit = !empty(env('PR_PAGE_DATA')) ? env('PR_PAGE_DATA') : 10 ;
            $start = !empty($pageNo) ? ($pageNo - 1)*$limit : 0 ;
            $messageGroup = MessageGroup::select(DB::raw('group_concat(id) as groupId'))->where('sellerId',$sellerId)->get()->first();
            $SingleMessagingGroup = SingleMessagingGroup::select(DB::raw('group_concat(groupId) as groupId'))->where('createId',$sellerId)->where('type',3)->get()->first();
            if(!empty($SingleMessagingGroup)){
                $SingleMessagingGroupid = explode(',',$SingleMessagingGroup->groupId);
            }
            if(!empty($messageGroup)){
              //  $messageGroupId = $messageGroup->groupId;
                $messageGroupId = explode(',',$messageGroup->groupId);
            }
         //   print_r([1,2]);
            $count = MessageNotification::where('message_notifications.userId','=',$sellerId)
            ->where('message_notifications.type', 3)
            ->whereIn('message_notifications.groupId',$messageGroupId)
            ->orwhereIn('message_notifications.groupId',$SingleMessagingGroupid)
            ->count();
            $data['message'] = MessageNotification::select("message_notifications.*",DB::raw('( CASE WHEN message_notifications.type = 3  THEN  CONCAT("'.$imageUrl .'","vendor/vendor",d.id,"/", d.profileImage) WHEN  message_notifications.type = 2  THEN  CONCAT("'.$imageUrl .'","customer/customer",c.id,"/", c.profileImage) WHEN  message_notifications.type = 1  THEN  CONCAT("'.$imageUrl .'","admin/admin",a.id,"/", a.image) ELSE "'.$demoImage.'"  END)   AS image'))
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
                                    ->where('message_notifications.userId','=',$sellerId)
                                            ->where('message_notifications.type', 3)
                                   
                                  //  ->where('message_notifications.userId','=',$sellerId)
                                    ->whereIn('message_notifications.groupId',$messageGroupId)
                                    ->orwhereIn('message_notifications.groupId',$SingleMessagingGroupid)
                                    ->orderBy('message_notifications.id','DESC')
                                    ->offset($start)->limit($limit)
                                    ->get()
                                    ->toArray();
            if(!empty($data['message'])){
                $data = Helper::removeNull($data);
                $data['totalPage'] = ceil($count/$limit);
                return response()->json(['data'=>$data,'status'=>true,'message'=>'Dashboard Notification','token'=>''], $this->success); 
            }else{
                return response()->json(['data'=>'','status'=>true,'message'=>'Data Empty','token'=>''], $this->success); 
            }
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
        }

    }

    public function notificationCount(Request $req){
        $pageNo = $req->pageNo;
        $token = $req->bearerToken();
        $sellerId =  Helper::encode_token($token);
        $sellerData =  Seller::where('id',$sellerId)->get()->first(); 
        if(!empty($sellerData)){ 
            $messageGroup = MessageGroup::select(DB::raw('group_concat(id) as groupId'))->where('sellerId',$sellerId)->get()->first();
            $SingleMessagingGroup = SingleMessagingGroup::select(DB::raw('group_concat(groupId) as groupId'))->where('createId',$sellerId)->where('type',3)->get()->first();
            if(!empty($SingleMessagingGroup)){
                $SingleMessagingGroupid = explode(',',$SingleMessagingGroup->groupId);
            }
            if(!empty($messageGroup)){
                $messageGroupId = explode(',',$messageGroup->groupId);
            }
            $data['notification'] = Notification::where('toId',$sellerId)->where('readStatus',0)->where('type','seller')->count();
            $data['message'] = MessageNotification::where('message_notifications.userId','=',$sellerId)
                                ->where('message_notifications.type', 3)
                                ->whereIn('message_notifications.groupId',$messageGroupId)
                                ->orwhereIn('message_notifications.groupId',$SingleMessagingGroupid)
                                ->count();

            $data['cart'] =  Cart::where('sellerId',$sellerId)->where('carts.status',0)->orderBy('carts.id')->count();
            if(!empty($data)){
                return response()->json(['data'=>$data,'status'=>true,'message'=>'success','token'=>''], $this->success); 
            }else{
                return response()->json(['data'=>'','status'=>true,'message'=>'success','token'=>''], $this->success);
            }
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
        }
    }

    public function plannerDashboard(Request $req){
        $imageUrl = Helper::getUrl();
        $token = $req->bearerToken();
        $sellerId =  Helper::encode_token($token);
        $sellerData =  Seller::where('id',$sellerId)->get()->first(); 
        if(!empty($sellerData)){
            $notificationData = Notification::where('sellerId',$sellerId)->where('readStatus',0)->where('type','seller')->limit(5)->orderBy('id','DESC')->get()->toArray();
            $questionnairData = Transaction::select('questionnaires.id','questionnaires.eventName',DB::raw('(CASE WHEN images.image = "" THEN "" ELSE CONCAT("'.$imageUrl .'","customer/customer",questionnaires.customerId,"/", images.image) END) AS image'))
                                ->join('questionnaires','questionnaires.id','transactions.questionnaireId')
                                ->leftjoin('images','images.questionnaireId','questionnaires.id')
                                ->groupBy('images.questionnaireId')
                                ->where('transactions.sellerId',$sellerId)
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
                         ->where('messages.sentby',$sellerId)
                         ->where('messages.sendType','!=',3)
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
                return response()->json(['data'=>'','status'=>true,'message'=>'Data Empty','token'=>''], $this->success); 
            }
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
        }
    }
    
  
     
    public function plannerEventsList(Request $req){
        $data = [];
        $pageNo =  $req->pageNo;
        $type =  $req->type;
        $imageUrl = Helper::getUrl();
        $token = $req->bearerToken();
        $sellerId =  Helper::encode_token($token);
        $sellerData =  Seller::where('id',$sellerId)->get()->first();
        if(!empty($sellerData)){
            $limit = !empty(env('PR_PAGE_DATA')) ? env('PR_PAGE_DATA') : 10 ;
            $start = !empty($pageNo) ? ($pageNo - 1)*$limit : 0 ;
          
            $questionnairData = Questionnaire::select('questionnaires.*','questionnaires.eventName',DB::raw('(CASE WHEN images.image = "" THEN "" ELSE CONCAT("'.$imageUrl .'","customer/customer",questionnaires.customerId,"/", images.image) END) AS image'))
                                ->leftjoin('images','images.questionnaireId','questionnaires.id')
                                ->groupBy('images.questionnaireId')
                                ->whereRaw("find_in_set($sellerId,questionnaires.sellerId)");

                if($type == 1){
                    $count = $questionnairData->where('questionnaires.status',1)->count();
                    $questionnairData = $questionnairData->where('questionnaires.status',1)->orderBy('questionnaires.id','DESC')->offset($start)->limit($limit)->get()->toArray();
                }else{
                    $count = $questionnairData->where('questionnaires.status',2)->count();
                    $questionnairData = $questionnairData->where('questionnaires.status',2)->orderBy('questionnaires.id','DESC')->offset($start)->limit($limit)->get()->toArray(); 
                }
                if(!empty($questionnairData)) {
                    $data['eventList'] = Helper::removeNull($questionnairData);
                    $data['totalPage'] = ceil($count/$limit);
                    return response()->json(['data'=>$data,'status'=>true,'message'=>'Event List','token'=>''], $this->success); 
                }else{
                    return response()->json(['data'=>$data,'status'=>true,'message'=>'Data Empty','token'=>''], $this->success); 
                }  
            }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
        }
    }


    public function plannerEventsDetails(Request $req){
        $imageUrl = Helper::getUrl();
        $token = $req->bearerToken();
        $html = '';
        $Catgeory = [];
        $Catgeory1 = '';
        $sellerId =  Helper::encode_token($token);
        $sellerData =  Seller::where('id',$sellerId)->get()->first();
        if(!empty($sellerData)){
                 $SellerType = !empty($sellerData->type)  ? $sellerData->type : ' ' ;
                 $eventId = $req->questionnaireId;
                 $questionnairData = Questionnaire::find($eventId);
                 if($questionnairData){
                    $questionnairData = Questionnaire::select('questionnaires.*','v.name as vennuName','th.name as themeName','questionnaires.customerId','questionnaires.eventName','questionnaires.confirmationPartyPlanner as address',DB::raw('(CASE WHEN questionnaires.status = 1 THEN "Finish" WHEN questionnaires.status = 2 THEN "Ongoing" ELSE "Pending" END) AS status'),'questionnaires.interactionDate',DB::raw('(CASE WHEN questionnaires.guestExpectEnd = "" THEN questionnaires.guestExpectStart ELSE CONCAT(questionnaires.guestExpectStart,"-",questionnaires.guestExpectEnd) END) AS guest'),'c.name',DB::raw('(CASE WHEN c.profileImage = "" THEN "" ELSE CONCAT("'.$imageUrl .'","customer/customer",c.id,"/", c.profileImage) END) AS profileImage'),'t.created_at as projectStartDate',DB::raw("SUM(t.amount) as amount"),DB::raw("SUM(t.vat) as vat"),DB::raw("((SUM(t.amount)*SUM(t.vat))/100) as totalvatAmount"),DB::raw("SUM(t.totalAmount) as totalAmount"),'e.name as typeEvent')
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
                    if($SellerType == 1){
                        $AdminSetting = AdminSetting::first();
                        $tax = !empty($AdminSetting->tax) ? $AdminSetting->tax : 0;
                        $cartData = Cart::select('carts.*','.p.name','p.regularPrice','p.salePrice','q.eventName',DB::raw('(CASE WHEN p.salePrice = "" THEN (SUM(p.regularPrice*carts.quantity)) WHEN p.salePrice = "0"  THEN (SUM(p.regularPrice*carts.quantity)) ELSE  (SUM(p.salePrice*carts.quantity)) END) AS amount'))
                                            ->join('vendor_products as p','p.id','carts.productId')
                                            ->join('questionnaires as q','q.id','carts.eventId')
                                            ->where('carts.eventId',$eventId)
                                            ->where('carts.status',2)->get()->first();;
                        $questionnairData['amount'] = $cartData['amount'];
                        $totalvatAmount  =   ($cartData['amount']*($tax/100));
                        $questionnairData['totalvatAmount']  =   ($cartData['amount']*($tax/100));
                        $questionnairData['totalAmount'] =      $cartData['amount'] +   $totalvatAmount;    
                   }

                    $partyPlaningServiceCatgeory = !empty($questionnairData->partyPlaningServiceCatgeory) ? $questionnairData->partyPlaningServiceCatgeory : '' ;
                    $partyPlaningServiceSubCatgeory = !empty($questionnairData->partyPlaningServiceSubCatgeory) ? $questionnairData->partyPlaningServiceSubCatgeory : '' ;
                    
                    if(!empty($partyPlaningServiceCatgeory)){
                        $partyPlaningServiceCatgeory = explode(',',$partyPlaningServiceCatgeory);
                        $ServiceCatgeory   = Service::whereIn('id',$partyPlaningServiceCatgeory)->get()->toArray();
                   
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
                        $html  .=  $questionnairData['vennuName'];
                      }else{
                      
                        $html  .=  'Virtual';
                      }
                    $questionnairData['description'] = $html;
                        if($SellerType == 2){
                            $milestones = Milestone::where('questionnaireId',$eventId)->where('sellerId',$sellerId)->get()->toArray();
                        }else{
                            $milestones = Milestone::where('questionnaireId',$eventId)->where('vendorId',$sellerId)->get()->toArray(); 
                        }

                        
                        $cartData =   $cartData = Cart::select('p.id','.p.name','carts.quantity','p.regularPrice','p.salePrice','q.eventName',   DB::raw('(SELECT(CASE WHEN i.image Is Null THEN "" WHEN i.image = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",p.sellerId,"/", i.image) END) FROM product_images as i WHERE i.productId=carts.productId LIMIT 1) AS image'),DB::raw('CONCAT(s.firstName," ",s.lastName) as vendorName'),'co.name as country','ca.phoneNumber','ca.street')
                                        ->join('vendor_products as p','p.id','carts.productId')
                                        ->join('sellers as s','s.id','p.sellerId')
                                        ->join('questionnaires as q','q.id','carts.eventId')
                                        ->leftjoin('customer_address as ca','ca.id','carts.addressId')
                                        ->leftjoin('countries as co','co.id','ca.country')
                                        ->where('carts.eventId',$eventId)
                                        ->where('carts.status',2)
                                      
                                        ->orderBy('carts.id')->get()->toArray();
                        if($SellerType == 2){
                            $questionnairData['offers'] =  Offer::where('questionnaireId',$eventId)->where('type',0)->get()->toArray();
                        }
                        if(!empty($questionnairData)) {
                             $questionnairData['milestones'] = $milestones;
                             $questionnairData['products'] = $cartData;
                        
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

    public function offers(Request $req){
        $imageUrl = Helper::getUrl();
        $token = $req->bearerToken();
        $sellerId =  Helper::encode_token($token);
        $sellerData =  Seller::where('id',$sellerId)->get()->first();
        if(!empty($sellerData)){
            $eventId = $req->questionnaireId;
            $questionnairData = Questionnaire::find($eventId);
            if($questionnairData){
                  $offerData = Offer::where('sellerId',$sellerId)->where('questionnaireId',$eventId)->get()->toArray();
                  if(!empty($offerData)){
                      return response()->json(['data'=>$offerData,'status'=>true,'message'=>'Offers','token'=>''], $this->success); 
             
                  }else{
                     return response()->json(['data'=>'','status'=>true,'message'=>'Data Empty','token'=>''], $this->success); 
             
                  }
                 
             }else{
                return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Questionnaire Id','token'=>''], $this->success);   
             }
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
        }
    }



    public function offerCustomerList(Request $req){
        $imageUrl = Helper::getUrl();
        $demoImage = asset('resources/images/demoImage.png');
        $token = $req->bearerToken();
        $sellerId =  Helper::encode_token($token);
        $sellerData =  Seller::where('id',$sellerId)->get()->first();
        if(!empty($sellerData)){
             $TransactionData = Questionnaire::select('questionnaires.eventName','m.id as groupId','c.id as customerId',DB::raw('(CONCAT(c.name, " ", c.surname)) as name'),DB::raw('(CASE  WHEN c.profileImage IS NULL THEN "'.$demoImage.'"  WHEN c.profileImage = "" THEN "'.$demoImage.'" ELSE CONCAT("'.$imageUrl .'","customer/customer",c.id,"/", c.profileImage) END) AS profileImage'))
                                         ->join('message_groups as m','m.questionnaireId','questionnaires.id')
                                         ->join('customers as c','c.id','questionnaires.customerId')
                                         ->whereRaw("find_in_set($sellerId,questionnaires.sellerId)")->get()->toArray();
            if(!empty($TransactionData)) {
                return response()->json(['data'=>$TransactionData,'status'=>true,'message'=>'','token'=>''], $this->success); 
            }else{
                return response()->json(['data'=>'','status'=>true,'message'=>'Data Empty','token'=>''], $this->success); 
            }                        
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
        }
    }

    public function offerCreate(Request $req){
        $imageUrl = Helper::getUrl();
        $token = $req->bearerToken();
        $sellerId =  Helper::encode_token($token);
        $sellerData =  Seller::where('id',$sellerId)->get()->first();
        if(!empty($sellerData)){
            $groupId = $req->groupId;
            $groupData = MessageGroup::find($groupId);
            $eventId =  !empty($groupData->questionnaireId) ?  $groupData->questionnaireId : 0 ;
            $customerId =  !empty($groupData->customerId) ?  $groupData->customerId : 0 ;
            if($groupData){
                $validator = Validator::make($req->all(), [ 
                    'description' => 'required', 
                    'amount' => 'required|numeric', 
                     
                ]);
            
            if ($validator->fails()) { 
                return response()->json(['data'=>'','status'=>false,'message'=>$validator->errors()->all(),'token'=>''], $this->success);                           
            }else{ 
                  $offer = new Offer;
                  $offer->groupId = $groupId;
                  $offer->sellerId = $sellerId;
                  $offer->questionnaireId = $eventId;
                  $offer->description = $req->description;
                  $offer->amount = $req->amount;
                  $offer->save() ;

                  $Notification = new Notification;
                  $Notification->customerId = $customerId;
                  $Notification->type = 'customer';
                  $Notification->urltype = 'offer';
                  $Notification->offerId = $offer->id;
                  $Notification->questionnaireId = $eventId;
                  $Notification->fromId = $sellerId;
                  $Notification->toId = $customerId;
                  $Notification->sendType = 'seller';
                  $Notification->notification = 'New Offer Create';
                  $Notification->save() ;
                  Socket::notification($userId=$customerId,$userType='customer');

                  $message = new Message;
                  $message->sentby = $sellerId;
                  $message->sentto =  $eventId ;
                  $message->groupId = $groupId ;
                  $message->message = env('DEMO_PDF');                                                                           
                  $message->sendType = 3;
                  $message->receiveType = 0;
                  $message->date = date('Y-m-d h:i:s a');
                  $message->readStatus = 0;
                  $message->save();
                  return response()->json(['data'=>'','status'=>true,'message'=>'Offer Add Successfully','token'=>''], $this->success); 
             }
             }else{
                return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Id','token'=>''], $this->success);   
             }
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
        }
    }

    public function offerDelete(Request $req){
         $offerId = $req->id;
         $offerData = Offer::find($offerId);
         if(!empty($offerData)){
            $offerData->delete();  
            return response()->json(['data'=>'','status'=>true,'message'=>'Delete Successfully','token'=>''], $this->success); 
         }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Id','token'=>''], $this->success); 
         }
    }


    public function offerDetails(Request $req){
        $offerId = $req->id;
        $offerData = Offer::find($offerId);
        if(!empty($offerData)){
           return response()->json(['data'=>$offerData,'status'=>true,'message'=>'Offer Details','token'=>''], $this->success); 
        }else{
           return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Id','token'=>''], $this->success); 
        }
   }
 
    public function vendorLists(Request $req){
        $distance = env('MIX_Distance_LIMIT');
        $pageNo = $req->pageNo;
        $category = $req->category;
        $subcategory = $req->subcategory;
        $experience = $req->experience;
        $skill = $req->skill;
        $location = $req->location;
        $limit = !empty(env('PR_PAGE_DATA')) ? env('PR_PAGE_DATA') : 10 ;
        $start = !empty($pageNo) ? ($pageNo - 1)*$limit : 0 ;
        $imageUrl = Helper::getUrl();
        $token = $req->bearerToken();
        $sellerId =  Helper::encode_token($token);
        $sellerData =  Seller::where('id',$sellerId)->get()->first();
        if(!empty($sellerData)){
            $eventId = $req->eventId;
            $questionnairData = Questionnaire::find($eventId);
            if($questionnairData){
                $levelOfService = !empty($questionnairData->levelOfService) ? $questionnairData->levelOfService : ' ' ;
                $latitude = !empty($questionnairData->latitude) ? $questionnairData->latitude : 0 ;
                $longitude = !empty($questionnairData->longitude) ? $questionnairData->longitude : 0 ;
                
                $vendorData  = Seller::select(DB::raw('sellers.*, (6367 * acos(cos(radians('.$latitude.')) * cos(radians(latitude)) * cos(radians(longitude) - radians('.$longitude.')) + sin(radians('.$latitude.')) * sin(radians(latitude))) ) AS distance'),DB::raw('(CASE WHEN sellers.profileImage = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",sellers.id,"/", sellers.profileImage) END) AS profileImage'),'pr.ratting_cnt',DB::raw('round(pr.seller_rate/pr.ratting_cnt) as rating'))
                                ->leftjoin('vendors','vendors.sellerId','sellers.id')
                                ->leftjoin(DB::raw('(select pr1.sellerId, sum(pr1.rating) as seller_rate, count(1) as ratting_cnt from reviews pr1 group by pr1.sellerId) as pr'), function($join){
                                    $join->on('pr.sellerId', '=', 'sellers.id');
                                    })
                                ->where('type',1)
                                ->where('status',1)
                             //   ->where('vendors.willingWork',$levelOfService)
                              // ->orwhere('vendors.willingWork','both')
                            //  ->having('distance', '<', $distance)
                               ;
                if(!empty($category) && ($category != 'other')){
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
                $data = $data->offset($start)->limit($limit)->orderBy('distance')->get()->toArray();


               if(!empty($data)){
                        $data = $data;
                    }else{
                        $vendorData =  Seller::select('sellers.*',DB::raw('(CASE WHEN sellers.profileImage = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",sellers.id,"/", sellers.profileImage) END) AS profileImage'))
                                            ->leftjoin('vendors','vendors.sellerId','sellers.id')
                                            ->where('sellers.type',1)
                                            ->where('sellers.status',1)
                                            ->where('vendors.willingWork',$levelOfService)
                                            ->orwhere('vendors.willingWork','both');
                            $count = $vendorData->count();
                            $data = $vendorData->offset($start)->limit($limit)->get()->makeHidden(['password','remember_token','status'])->toArray();
                                           
                    }
                   
                if(!empty($data)){
                    $vendors['vendors'] = Helper::removeNull($data);
                    $vendors['totalPage'] =  ceil($count/$limit);
                  return response()->json(['data'=>$vendors,'status'=>true,'message'=>'vendor List','token'=>''], $this->success); 
                }else{
                  return response()->json(['data'=>[],'status'=>true,'message'=>'Data Empty','token'=>''], $this->success); 
                }
            }else{
                return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Event Id','token'=>''], $this->success);   
            }
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
        }
    }


    
    public function selectVendors(Request $req){
        $imageUrl = Helper::getUrl();
        $token = $req->bearerToken();
        $sellerId =  Helper::encode_token($token);
        $sellerData =  Seller::where('id',$sellerId)->where('type',2)->get()->first();
        if(!empty($sellerData)){
            $eventId = '';
            $content = trim(file_get_contents("php://input"));
            $json_decode = json_decode($content,true);
             if(!empty($json_decode)){
             foreach($json_decode as $data) {
               

                  $EventVendor = EventVendor::where('questionnaireId',$data['eventId'])->where('vendorId',$data['vendorId'])->get()->first();
                  if(empty($EventVendor)){
                       $eventId = $data['eventId'];
                        $EventVendor = new EventVendor;
                        $EventVendor->plannerId = $sellerId;
                        $EventVendor->vendorId = $data['vendorId'];
                        $EventVendor->questionnaireId = $data['eventId'];
                        $EventVendor->save(); 
                  }
                
             }
             if(!empty($eventId)){
                $questionnaireupdate = Questionnaire::find($eventId);
                $customerId = $questionnaireupdate->customerId;
                $notification = new Notification;
                $notification->notification = ' Planner sent suggestions to choose Vendor for your event.';
                $notification->type = 'customer';
                $notification->urlType = 'vendor';
                $notification->customerId = $customerId;
              
                $notification->fromId = $sellerId;
                $notification->toId = $customerId;
                $notification->sendType = 'seller';
                $notification->save();
                Socket::notification($userId=$questionnaireupdate->customerId,$userType='customer');
             } 
         
             return response()->json(['data'=>'','status'=>true,'message'=>'Success','token'=>''], $this->success);  
                    
            }else{
               return response()->json(['data'=>'','status'=>false,'message'=>'Please Fill Required Fields','token'=>''], $this->success);  
           }
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
        }
    }

} 
