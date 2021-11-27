<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Helper;
use Socket;
use DB;
use App\Customer;
use App\Questionnaire;
use App\PlannerPlan;
use App\Message;
use App\Seller;
use App\Admin;
use App\Transaction;
use App\MessageGroup;
use App\SingleMessagingGroup;
use App\ShareEvent;
use App\MessageNotification;


class MessageController extends Controller
{
    public $success = 200;
    public $error = 401;
     
   
    public function sellerList(Request $req){
    $token = $req->bearerToken();
    $encode_token =  Helper::encode_token($token);
      $customerId = $encode_token;
    $customerData = Customer::find($customerId) ; 
    $imageUrl = Helper::getUrl(); 
    $demoImage = asset('resources/images/demoImage.png');
        if(!empty($customerData)){
            $invitationId = !empty($customerData->invitationId) ? $customerData->invitationId : '' ;
            $invitationId = ShareEvent::select(DB::raw('group_concat(eventId) as eventId'))->where('customerId',$customerId)->get()->first();
               $invitationId  = $invitationId['eventId'];
                if(!empty($invitationId)){
                  $invitationId = explode(',',$invitationId);
                }
            $sellerList = MessageGroup::select('message_groups.*','message_groups.id as groupId','m.message','m.date as messageDate',DB::raw('(CASE WHEN d.profileImage = " " THEN "'.$demoImage.'" ELSE "'.$demoImage.'" END) AS profileImage'))
                                ->selectRaw(DB::raw('COALESCE(a.name,CONCAT(d.firstName, " ", d.lastName),q.eventName) as name'))
                                    ->leftjoin('admin as a', function($join){
                                            $join->on('message_groups.adminId', '=', 'a.id')
                                            ->where('message_groups.type', '=', 1);
                                        })
                                    ->leftjoin('sellers AS d', function($leftjoin){
                                        $leftjoin->on('message_groups.sellerId', '=', 'd.id')
                                        ->where('message_groups.type', '=', 1);
                                    })
                                    ->leftjoin('questionnaires AS q', function($leftjoin){
                                        $leftjoin->on('message_groups.questionnaireId', '=', 'q.id')
                                        ->where('message_groups.type', '=', 0);
                                    })
                                    ->leftjoin('recent_message_view AS m', function($leftjoin){
                                        $leftjoin->on('message_groups.id', '=', 'm.groupId');
                                    })
                                    
                                ->where('message_groups.customerId',$customerId);
            if(!empty($invitationId)){
              $sellerList =   $sellerList->orwhereIn('message_groups.questionnaireId',$invitationId); 
            } 
                $sellerList =   $sellerList->orderBy('message_groups.id','DESC')->get()->toArray();
                             
                                       
            if(!empty($sellerList)){
                $sellerList = Helper::removeNull($sellerList);
                return response()->json(['data'=>$sellerList,'status'=>true,'message'=>'Seller List','token'=>''], $this->success);  
            }else{
                return response()->json(['data'=>[],'status'=>true,'message'=>'Data Empty','token'=>''], $this->success);  
            }
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=> 'Invalid Customer Token','token'=>''], $this->error);  
        }
   }


   public function customerSellerChat(Request $req){
    $token = $req->bearerToken();
    $encode_token =  Helper::encode_token($token);
    $customerId = $encode_token;
    $customerData = Customer::find($customerId) ; 
    $imageUrl = Helper::getUrl();
    $demoImage = asset('resources/images/demoImage.png');
        if(!empty($customerData)){
           $groupId = $req->groupId;
            $groupData = MessageGroup::where('id',$groupId)->get()->first();
            $groupType  = !empty($groupData->type) ? $groupData->type : 0 ;
            $adminId  = !empty($groupData->adminId) ? $groupData->adminId : 0 ;
            $sellerId  = !empty($groupData->sellerId) ? $groupData->sellerId : 0 ;
            if(!empty($groupData)){  
                if($groupType == 1){
                    $sentto = !empty($groupData->sellerId) ? $groupData->sellerId : $groupData->adminId ;
                    $receiveType = !empty($groupData->sellerId) ? 3 : 1 ;
                }else{ 
                   $sentto = !empty($groupData->questionnaireId) ? $groupData->questionnaireId : 0 ; 
                   $receiveType = 0 ;
                }
               
              // if(!empty($sellerData)){   
                     if($req->hasfile('msgFile')){
                        $validator = Validator::make($req->all(), [ 
                            'message' => 'nullable|string',
                            'msgFile' => 'required',
                            'msgFile.*' => 'required|max:5000*2|mimes:pdf,docx,doc,jpeg,jpg,png',
                        ]);
                     }else{
                        $validator = Validator::make($req->all(), [ 
                            'message' => 'required|string',
                            'msgFile' => 'nullable',
                            'msgFile.*' => 'nullable|max:5000*2|mimes:pdf,docx,doc,jpeg,jpg,png',
                        ]);
                     }
                   

                        if($validator->fails()) { 
                            return response()->json(['data'=>'','status'=>false,'message'=>$validator->errors()->all(),'token'=>''], $this->success);                           
                        }else{         
                              
                              $msgText = !empty($req->message) ? $req->message : '' ;
                              $message = new Message;
                              $message->sentby = $customerId;
                              $message->sentto =  $sentto ;
                              $message->groupId = $groupId ;
                              $message->message = $msgText;
                              if($req->hasfile('msgFile')){
                                 foreach($req->file('msgFile') as $file){
                                    $fileName = time().date('dmYhis').'.'.$file->getClientOriginalName();
                                    $fileName = str_replace( " ", "-", trim($fileName) );
                                    $folder = Helper::imageUpload($fileName, $file,$folder="message"); 
                                    $images[] = $fileName;  
                                 }
                                 $message->msgFile = json_encode($images,JSON_UNESCAPED_SLASHES); 
                            }                                                                               
                              $message->sendType = 2;
                              $message->receiveType = $receiveType;
                              $message->date = date('Y-m-d h:i:s a');
                              $message->readStatus = 0;
                              $message->save();
                              $messageData = Message::select("messages.*",DB::raw('(CASE WHEN messages.sendType = 2  THEN (CASE WHEN c.profileImage IS NULL THEN  "'.$demoImage.'" WHEN c.profileImage = "" THEN "'.$demoImage.'" ELSE CONCAT("'.$imageUrl .'","customer/customer",c.id,"/", c.profileImage) END) END) AS customerImage'))
                                                ->selectRaw(DB::raw('COALESCE(c.name) as name'))
                                                    ->leftjoin('customers as c', function($join){
                                                        $join->on('messages.sentby', '=', 'c.id')
                                                        ->where('messages.sendType', '=', 2);
                                                    })   
                                        ->where('messages.id',$message->id)
                                        ->orderBy('messages.id','asc')
                                        ->get()
                                        ->first();

                             if(empty($msgText)) {
                                 $msg =  $message->msgFile;
                             }else{
                                $msg =  $msgText;
                             }  
                           if($groupType == 1){
                                $userid = !empty($groupData->sellerId) ? $groupData->sellerId : $groupData->adminId ;
                                $receiveType = !empty($groupData->sellerId) ? 3 : 1 ;
                                $MessageNotification = new MessageNotification;
                                $MessageNotification->groupId = $groupId;
                                $MessageNotification->userId = $userid;
                                $MessageNotification->message =  $msg;
                                $MessageNotification->type = $receiveType;
                                $MessageNotification->status = 0;
                                $MessageNotification->save();
                                Socket::message($userid,$receiveType);
                            }else{ 

                                $MessageNotification = new MessageNotification;
                                $MessageNotification->groupId = $groupId;
                                $MessageNotification->userId = $adminId;
                                $MessageNotification->message =  $msg;
                                $MessageNotification->type = 1;
                                $MessageNotification->status = 0;
                                $MessageNotification->save();
                                Socket::message($adminId,1);

                                $MessageNotification = new MessageNotification;
                                $MessageNotification->groupId = $groupId;
                                $MessageNotification->userId = $sellerId;
                                $MessageNotification->message =  $msg;
                                $MessageNotification->type = 3;
                                $MessageNotification->status = 0;
                                $MessageNotification->save();
                                Socket::message($sellerId,3);
                            } 
                              $messageData = Helper::removeNull($messageData);
                              $messageData['imageUrl'] = $imageUrl.'message/';
                              
                              return response()->json(['data'=>$messageData,'status'=>true,'message'=>'Message Send','token'=>''], $this->success,[], JSON_UNESCAPED_UNICODE); 
                       }   
              // }else{
                 //  return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Seller Id','token'=>''], $this->success);   
              //  }
             }else{
                return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Group Id ','token'=>''], $this->success);   
            }
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error);  
        }
   }



   public function customerList(Request $req){
    $list = [];
    $token = $req->bearerToken();
    $encode_token =  Helper::encode_token($token);
    $sellerId = $encode_token;
    $sellerData = Seller::where('id',$sellerId)->get()->first() ; 
    $imageUrl = Helper::getUrl(); 
    $demoImage = asset('resources/images/demoImage.png');
        if(!empty($sellerData)){
            $sellerType = !empty($sellerData->type) ? $sellerData->type : '' ;
            $customerList = MessageGroup::select('message_groups.*','message_groups.id as groupId','q.eventName','m.message','m.date as messageDate',DB::raw('(CASE WHEN d.profileImage = " " THEN "'.$demoImage.'" ELSE "'.$demoImage.'" END) AS profileImage'))
                                    ->selectRaw(DB::raw('COALESCE(a.name,CONCAT(d.name, " ", d.surname)) as name'))
                                        ->leftjoin('admin as a', function($join){
                                                $join->on('message_groups.adminId', '=', 'a.id')
                                                ->where('message_groups.type', '=', 1);
                                            })
                                        ->leftjoin('customers AS d', function($leftjoin){
                                            $leftjoin->on('message_groups.customerId', '=', 'd.id')
                                            ->where('message_groups.type', '=', 1);
                                        })
                                        ->leftjoin('questionnaires AS q', function($leftjoin){
                                            $leftjoin->on('message_groups.questionnaireId', '=', 'q.id')
                                            ->where('message_groups.type', '=', 0);
                                        })
                                        ->leftjoin('recent_message_view AS m', function($leftjoin){
                                            $leftjoin->on('message_groups.id', '=', 'm.groupId');
                                        })
                                        
                                    ->where('message_groups.sellerId',$sellerId)
                                    ->orderBy('message_groups.id','DESC')
                                    ->get()->toArray();
        if($sellerType == 2){
         /*    $list =   Seller::select('sellers.id',DB::raw('CONCAT(sellers.firstName," ",sellers.lastName) as name'),DB::raw('CONCAT(sellers.firstName," ",sellers.lastName) as eventName'),'sellers.id as sellerId','sellers.created_at','sellers.updated_at',DB::raw('CONCAT("seller","_","'.$sellerId .'","_",sellers.id) as groupId'),DB::raw('(0) as questionnaireId'),DB::raw('(0) as customerId'),DB::raw('(0) as adminId'),DB::raw('(0) as type'),DB::raw('(CASE WHEN sellers.profileImage = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",sellers.id,"/", sellers.profileImage) END) AS profileImage'),'m.message','m.date as messageDate')
                                    ->leftjoin('single_messaging_groups AS s', function($leftjoin){
                                        $leftjoin->on('sellers.id', '=', 's.createId')
                                        ->where('s.type', '=',3);
                                    })
                                    ->leftjoin('recent_message_view AS m','s.groupId', '=', 'm.groupId')
                                    ->where('sellers.type',1)->get()->toArray();  */
          $list =   DB::select('select tt.*, (SELECT m.message FROM recent_message_view AS m where m.groupId = tt.groupId LIMIT 1) as message  from (
                                select `sellers`.`id`,  CONCAT(sellers.firstName," ",sellers.lastName) as name, CONCAT(sellers.firstName," ",sellers.lastName) as eventName, 
                                `sellers`.`id` as `sellerId`,`sellers`.`created_at`,  `sellers`.`updated_at`,  CONCAT("seller","_","'.$sellerId .'","_",sellers.id) as groupId, (0) as questionnaireId,(0) as customerId, (0) as adminId, (0) as type, (CASE WHEN sellers.profileImage = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",sellers.id,"/", sellers.profileImage) END) AS profileImage 
                                from `sellers` where `sellers`.`type` = 1) as tt');
            
            $list = json_decode(json_encode($list),true);
            
        }elseif($sellerType == 1){
            $list =   SingleMessagingGroup::select('single_messaging_groups.id','single_messaging_groups.groupId','single_messaging_groups.createId as sellerId',DB::raw('CONCAT(s.firstName," ",s.lastName) as name'),DB::raw('CONCAT(s.firstName," ",s.lastName) as eventName'),DB::raw('(0) as questionnaireId'),DB::raw('(0) as customerId'),DB::raw('(0) as adminId'),DB::raw('(0) as type'),DB::raw('(CASE WHEN s.profileImage = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",s.id,"/", s.profileImage) END) AS profileImage'),'m.message','m.date as messageDate')
                                    ->leftjoin('sellers AS s', function($leftjoin){
                                            $leftjoin->on('s.id', '=', 'single_messaging_groups.createId');
                                    })
                                    ->leftjoin('recent_message_view AS m', function($leftjoin){
                                        $leftjoin->on('single_messaging_groups.groupId', '=', 'm.groupId');   
                                    })
                                ->where('single_messaging_groups.type',3)
                                ->where('s.type',2)
                                ->where('single_messaging_groups.sameId',$sellerId)
                                ->get()->toArray();
        
        }
        $dataAll = (array_merge($customerList,$list));
            if(!empty($dataAll)){
                $dataAll = Helper::removeNull($dataAll);
                return response()->json(['data'=>$dataAll,'status'=>true,'message'=>'Customer List','token'=>''], $this->success);  
            }else{
                return response()->json(['data'=>[],'status'=>true,'message'=>'Data Empty','token'=>''], $this->success);  
            }
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Seller Token','token'=>''], $this->error);  
        }
   }


   public function sellerCustomerChat(Request $req){
    $token = $req->bearerToken();
    $encode_token =  Helper::encode_token($token);
    $sellerId = $encode_token;
    $sellerData = Seller::where('id',$sellerId)->get()->first() ; 
    $imageUrl = Helper::getUrl(); 
    $msgType = $req->msgType; 
    $demoImage = asset('resources/images/demoImage.png');
    
        if(!empty($sellerData)){
            $groupId = $req->groupId;
            $sellerType = !empty($sellerData->type) ? $sellerData->type : '' ;
            if(is_numeric($groupId)){
                $groupData = MessageGroup::where('id',$groupId)->get()->first();
                $groupType  = !empty($groupData->type) ? $groupData->type : 0 ; 
                $adminId  = !empty($groupData->adminId) ? $groupData->adminId : 0 ;
                $customerId  = !empty($groupData->customerId) ? $groupData->customerId : 0 ;
                if($groupType == 1){
                    $sentto = !empty($groupData->customerId) ? $groupData->customerId : $groupData->adminId ;
                    $receiveType = !empty($groupData->customerId) ? 2 : 1 ;
                }else{ 
                   $sentto = !empty($groupData->questionnaireId) ? $groupData->questionnaireId : 0 ; 
                   $receiveType = 0 ;
                }
            }else{
                $groupData = SingleMessagingGroup::where('groupId',$groupId)->get()->first();
                $superAdmin = Admin::where('type',1)->where('adminType',1)->get()->first();
                $groupIdArray = explode('_',$groupId);
                if(empty($groupData)){
                    $singleGroup = new SingleMessagingGroup;
                    $singleGroup->groupId = $groupId;
                    $singleGroup->createId = $sellerId;
                    $singleGroup->sameId = $groupIdArray['2'];
                    $singleGroup->type = 3;
                    $singleGroup->adminId = $superAdmin->id;
                    $singleGroup->save();
                }
                 if($sellerType == 2){
                    $sentto = $groupIdArray['2'];
                 }else{
                    $sentto = $groupIdArray['1'];
                 }
               
                $receiveType = 3;  
            }   
              // if(!empty($customerData)){  
                   
                        if($req->hasfile('msgFile')){
                            $validator = Validator::make($req->all(), [ 
                                'message' => 'nullable|string',
                                'msgFile' => 'required',
                                'msgFile.*' => 'required|max:5000*2|mimes:pdf,docx,doc,jpeg,jpg,png',
                            ]);
                        }else{
                            $validator = Validator::make($req->all(), [ 
                                'message' => 'required|string',
                                'msgFile' => 'nullable',
                                'msgFile.*' => 'nullable|max:5000*2|mimes:pdf,docx,doc,jpeg,jpg,png',
                            ]);
                        }
                    
                        if ($validator->fails()) { 
                            return response()->json(['data'=>'','status'=>false,'message'=>$validator->errors()->all(),'token'=>''], $this->success);                           
                        }else{         
                              $msgText = !empty($req->message) ? $req->message : '' ;
                              $message = new Message;
                              $message->sentby = $sellerId;
                              $message->sentto = $sentto;
                              $message->groupId = $groupId ;
                              $message->message = $msgText;
                              if($req->hasfile('msgFile')){
                                 foreach($req->file('msgFile') as $file){
                                    $fileName = time().date('dmYhis').'.'.$file->getClientOriginalName();
                                    $fileName = str_replace( " ", "-", trim($fileName) );
                                    $folder = Helper::imageUpload($fileName, $file,$folder="message"); 
                                    $images[] = $fileName;  
                                 }
                                 $message->msgFile = json_encode($images,JSON_UNESCAPED_SLASHES); 
                              }
                              $message->sendType = 3;
                              
                              $message->receiveType = $receiveType;
                              $message->date = date('Y-m-d h:i:s a');
                              $message->readStatus = 0;
                              $message->save();
                              $messageData = Message::select("messages.*",DB::raw('( CASE WHEN messages.sendType = 3  THEN (CASE WHEN d.profileImage = "" THEN "'.$demoImage.'" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",d.id,"/", d.profileImage) END)  END) AS sellerImage'))
                                                        ->selectRaw(DB::raw('COALESCE(CONCAT(d.firstName, " ", d.lastName)) as name'))
                                                            ->leftjoin('sellers AS d', function($leftjoin){
                                                                $leftjoin->on('messages.sentby', '=', 'd.id')
                                                                ->where('messages.sendType', '=', 3);
                                                            })         
                                                ->where('messages.id',$message->id)
                                                ->orderBy('messages.id','asc')
                                                ->get()
                                                ->first();
                                if(empty($msgText)) {
                                    $msg =  $message->msgFile;
                                }else{
                                    $msg =  $msgText;
                                }  
                                if(is_numeric($groupId)){
                                if($groupType == 1){
                                    $userid = !empty($groupData->customerId) ? $groupData->customerId : $groupData->adminId ;
                                    $receiveType = !empty($groupData->sellerId) ? 2 : 1 ;
                                    $MessageNotification = new MessageNotification;
                                    $MessageNotification->groupId = $groupId;
                                    $MessageNotification->userId = $userid;
                                    $MessageNotification->message =  $msg;
                                    $MessageNotification->type = $receiveType;
                                    $MessageNotification->status = 0;
                                    $MessageNotification->save();
                                    Socket::message($userid,$receiveType);
                                }else{ 
    
                                    $MessageNotification = new MessageNotification;
                                    $MessageNotification->groupId = $groupId;
                                    $MessageNotification->userId = $adminId;
                                    $MessageNotification->message =  $msg;
                                    $MessageNotification->type = 1;
                                    $MessageNotification->status = 0;
                                    $MessageNotification->save();
                                    Socket::message($adminId,1);
    
                                    $MessageNotification = new MessageNotification;
                                    $MessageNotification->groupId = $groupId;
                                    $MessageNotification->userId = $customerId;
                                    $MessageNotification->message =  $msg;
                                    $MessageNotification->type = 2;
                                    $MessageNotification->status = 0;
                                    $MessageNotification->save();
                                    Socket::message($customerId,2);
                                }
                            }else{
                                $MessageNotification = new MessageNotification;
                                    $MessageNotification->groupId = $groupId;
                                    $MessageNotification->userId = $sentto;
                                    $MessageNotification->message =  $msg;
                                    $MessageNotification->type = 3;
                                    $MessageNotification->status = 0;
                                    $MessageNotification->save(); 
                                    Socket::message($sentto,3);
                            }
                            $messageData = Helper::removeNull($messageData);
                            $messageData['imageUrl'] = $imageUrl.'message/';
                            return response()->json(['data'=>$messageData,'status'=>true,'message'=>'Message Send','token'=>''], $this->success,[], JSON_UNESCAPED_UNICODE); 
                        }
            //    }else{
            //   return response()->json(['data'=>'','status'=>false,'message'=>'Invalid customer Id','token'=>''], $this->success);   
           //    }
           //  }else{
           //    return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Group Id','token'=>''], $this->success);   
            //}
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error);  
        }
   }


   public function customerSellerMessaging(Request $req){
    
        $pageNo = $req->pageNo;
        $token = $req->bearerToken();
        $encode_token =  Helper::encode_token($token);
        $customerId = $encode_token;
        $customerData = Customer::where('id',$customerId)->where('status',1)->get()->first() ; 
        if(!empty($customerData)){
            $groupId = $req->groupId;
            $groupData = MessageGroup::where('id',$groupId)->get()->first();
            
            $imageUrl = Helper::getUrl(); 
            $demoImage = asset('resources/images/demoImage.png');
            $limit = env('CHAT_PER_PAGE_NO');
            $offset = !empty($pageNo) ? ($pageNo - 1)*$limit : 0 ;
           
           //if(!empty($groupData)){
                $groupType  = !empty($groupData->type) ? $groupData->type : 0 ;
                MessageNotification::where('groupId',$groupId)->where('userId',$customerId)->where('type',2)->delete();
                Socket::message($customerId,2);
                /*  if($groupType == 1){
                $count = Message::where('messages.groupId',$groupId)->where('messages.sentby' , $customerId)
                                    ->where('messages.sendType',2)
                                    ->orwhere('messages.sentto' , $customerId)
                                    ->where('messages.receiveType',2)->count();
                              
                $data =  Message::select("messages.*",DB::raw('(CASE WHEN messages.sendType = 2  THEN (CASE WHEN c.profileImage IS NULL THEN  "'.$demoImage.'" WHEN c.profileImage = "" THEN "'.$demoImage.'" ELSE CONCAT("'.$imageUrl .'","customer/customer",c.id,"/", c.profileImage) END) END) AS profileImage'))
                                ->selectRaw(DB::raw('COALESCE(c.name) as name'))
                                ->leftjoin('customers as c', function($join){
                                    $join->on('messages.sentby', '=', 'c.id');
                                  
                                }) 
                                ->where('messages.groupId',$groupId)
                                ->where('messages.sentby' , $customerId)
                                ->where('messages.sendType',2)
                                ->orwhere('messages.sentto' , $customerId)
                                ->where('messages.receiveType',2)
                                ->orderBy('messages.id','DESC')
                                ->get()->toArray();
                               
                              
            }else{ */
            $count = Message::where('groupId','=',$groupId)->count();
            $data = Message::select("messages.*",DB::raw('( CASE WHEN messages.sendType = 3  THEN (CASE WHEN d.profileImage = "" THEN "'.$demoImage.'" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",d.id,"/", d.profileImage) END)  END) AS sellerImage'),DB::raw('(CASE WHEN messages.sendType = 2  THEN (CASE WHEN c.profileImage IS NULL THEN  "'.$demoImage.'" WHEN c.profileImage = "" THEN "'.$demoImage.'" ELSE CONCAT("'.$imageUrl .'","customer/customer",c.id,"/", c.profileImage) END) END) AS customerImage'),DB::raw('(CASE WHEN messages.sendType = 1  THEN (CASE WHEN a.image IS NULL THEN  "'.$demoImage.'" WHEN a.image = "" THEN "'.$demoImage.'" ELSE CONCAT("'.$imageUrl .'","admin/admin",a.id,"/", a.image) END) END) AS adminImage'))
                                 ->selectRaw(DB::raw('COALESCE(CONCAT(c.name, " ", c.surname),a.name,CONCAT(d.firstName, " ", d.lastName)) as name'))
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
                          ->where('messages.groupId',$groupId)
                          ->orderBy('messages.id','DESC')
                          ->take($limit)->skip($offset)
                         ->get()
                         ->toArray();
            //}                            
            $totalPage = ceil($count/$limit);  
            $messageList['chat'] = array_reverse(Helper::removeNull($data));
            $messageList['totalPage'] = $totalPage;
            $messageList['imageUrl'] = $imageUrl.'message/';        
            if(!empty($messageList)){
               
                return response()->json(['data'=>$messageList,'status'=>true,'message'=>'Message','token'=>''], $this->success);  
                }else{
                    return response()->json(['data'=>'','status'=>true,'message'=>'Data Empty','token'=>''], $this->success);  
                }
          //  }else{
         //       return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Group Id','token'=>''], $this->success);  
        //    }
        }else{
        return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error);  
        }
   }




   public function sellerMessaging(Request $req){
    $pageNo = $req->pageNo;
    $token = $req->bearerToken();
    $encode_token =  Helper::encode_token($token);
    $sellerId = $encode_token;
    $sellerData = Seller::where('id',$sellerId)->get()->first() ; 
    if(!empty($sellerData)){
        $groupId = $req->groupId;
        $groupData = MessageGroup::where('id',$groupId)->get()->first();
        
        $imageUrl = Helper::getUrl(); 
        $demoImage = asset('resources/images/demoImage.png');
        $limit = env('CHAT_PER_PAGE_NO');
        $offset = !empty($pageNo) ? ($pageNo - 1)*$limit : 0 ;
        MessageNotification::where('groupId',$groupId)->where('userId',$sellerId)->where('type',3)->delete();
        Socket::message($sellerId,3);
        //  if(!empty($groupData)){
            $groupType  = !empty($groupData->type) ? $groupData->type : 0 ;
/* 
            if($groupType == 1){
                $count = Message::where('messages.groupId',$groupId)->where('messages.sentby' , $sellerId)
                            ->where('messages.sendType',3)
                            ->orwhere('messages.sentto' , $sellerId)
                            ->where('messages.receiveType',3)->count();
                        
                $data =  Message::select("messages.*",DB::raw('(CASE WHEN messages.sendType = 2  THEN (CASE WHEN c.profileImage IS NULL THEN  "'.$demoImage.'" WHEN c.profileImage = "" THEN "'.$demoImage.'" ELSE CONCAT("'.$imageUrl .'","customer/customer",c.id,"/", c.profileImage) END) END) AS profileImage'))
                            ->selectRaw(DB::raw('COALESCE(c.name) as name'))
                            ->leftjoin('customers as c', function($join){
                                $join->on('messages.sentby', '=', 'c.id');
                            
                            }) 
                            ->where('messages.groupId',$groupId)
                            ->where('messages.sentby' , $sellerId)
                            ->where('messages.sendType',3)
                            ->orwhere('messages.sentto' , $sellerId)
                            ->where('messages.receiveType',3)
                            ->orderBy('messages.id','DESC')
                            ->get()->toArray();
            }else{ */
            $count = Message::where('groupId','=',$groupId)->count();
            $data = Message::select("messages.*",DB::raw('( CASE WHEN messages.sendType = 3  THEN (CASE WHEN d.profileImage = "" THEN "'.$demoImage.'" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",d.id,"/", d.profileImage) END)  END) AS sellerImage'),DB::raw('(CASE WHEN messages.sendType = 2  THEN (CASE WHEN c.profileImage IS NULL THEN  "'.$demoImage.'" WHEN c.profileImage = "" THEN "'.$demoImage.'" ELSE CONCAT("'.$imageUrl .'","customer/customer",c.id,"/", c.profileImage) END) END) AS customerImage'),DB::raw('(CASE WHEN messages.sendType = 1  THEN (CASE WHEN a.image IS NULL THEN  "'.$demoImage.'" WHEN a.image = "" THEN "'.$demoImage.'" ELSE CONCAT("'.$imageUrl .'","admin/admin",a.id,"/", a.image) END) END) AS adminImage'))
                                 ->selectRaw(DB::raw('COALESCE(CONCAT(c.name, " ", c.surname),a.name,CONCAT(d.firstName, " ", d.lastName)) as name'))
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
                          ->where('messages.groupId',$groupId)
                          ->orderBy('messages.id','DESC')
                          ->take($limit)->skip($offset)
                         ->get()
                         ->toArray();
            // }                            
            $totalPage = ceil($count/$limit); 
            $messageList['chat'] = array_reverse(Helper::removeNull($data));
            $messageList['totalPage'] = $totalPage;
            $messageList['imageUrl'] = $imageUrl.'message/';         
            if(!empty($messageList)){
              
                return response()->json(['data'=>$messageList,'status'=>true,'message'=>'Message','token'=>''], $this->success);  
            }else{
                return response()->json(['data'=>'','status'=>true,'message'=>'Data Empty','token'=>''], $this->success);  
            }
        //}else{
         //   return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Group Id','token'=>''], $this->success);  
         //   }
        }else{
        return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error);  
        }
   }

  
   public function messageSoket(Request $req){
    $userList= [];
    $messageId = $req->messageId;
    $messageData = Message::find($messageId);
    $imageUrl = Helper::getUrl(); 
    if(!empty($messageData)){
            $imageUrl = Helper::getUrl(); 
            $demoImage = asset('resources/images/demoImage.png');
            $MessageObject = Message::select("messages.*",DB::raw('( CASE WHEN messages.sendType = 3  THEN (CASE WHEN d.profileImage = "" THEN "'.$demoImage.'" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",d.id,"/", d.profileImage) END)  END) AS sellerImage'),DB::raw('(CASE WHEN messages.sendType = 2  THEN (CASE WHEN c.profileImage IS NULL THEN  "'.$demoImage.'" WHEN c.profileImage = "" THEN "'.$demoImage.'" ELSE CONCAT("'.$imageUrl .'","customer/customer",c.id,"/", c.profileImage) END) END) AS customerImage'),DB::raw('(CASE WHEN messages.sendType = 1  THEN (CASE WHEN a.image IS NULL THEN  "'.$demoImage.'" WHEN a.image = "" THEN "'.$demoImage.'" ELSE CONCAT("'.$imageUrl .'","admin/admin",a.id,"/", a.image) END) END) AS adminImage'))
                                ->selectRaw(DB::raw('COALESCE(CONCAT(c.name, " ", c.surname),a.name,CONCAT(d.firstName, " ", d.lastName)) as name'))
                                    ->leftjoin('customers as c', function($join){
                                        $join->on('messages.sentby', '=', 'c.id')
                                        ->where('messages.sendType', '=', 2);
                                    })->leftjoin('sellers AS d', function($leftjoin){
                                        $leftjoin->on('messages.sentby', '=', 'd.id')
                                        ->where('messages.sendType', '=', 3);
                                    })->leftjoin('admin AS a', function($leftjoin){
                                        $leftjoin->on('messages.sentby', '=', 'a.id')
                                        ->where('messages.sendType', '=', 1);
                                    })->where('messages.id',$messageId)
                                    ->get()->first();
    $groupId = $messageData->groupId;
    $sentby = $messageData->sentby;
    $sendType = $messageData->sendType;

    $groupData = MessageGroup::where('id',$groupId)->get()->first();
    $groupType = !empty($groupData->type) ? $groupData->type : 0 ;
    $customerId = !empty($groupData->customerId) ?  $groupData->customerId : '' ;
    $customerData = Customer::select('id as sentby',DB::raw('2 as type'))->where('invitationId',$customerId)->get()->toArray();
    
    if(is_numeric($groupId)){ 
    if($groupType == 1){
       
        if($sendType == 1){
            $sentto = !empty($groupData->sellerId) ? $groupData->sellerId : $groupData->customerId ;
            $type = !empty($groupData->sellerId) ? 3 : 2 ;
            $userList[] = array(
                'sentby'=> $sentto,
                'type'=>  $type,
            );

        }else if($sendType == 2){
            $sentto = !empty($groupData->sellerId) ? $groupData->sellerId : $groupData->adminId ;
            $type = !empty($groupData->sellerId) ? 3 : 1 ;
            $userList[] = array(
                'sentby'=> $sentto,
                'type'=>  $type,
            );

          

        }else{
            $sentto = !empty($groupData->customerId) ? $groupData->customerId : $groupData->adminId ;
            $type = !empty($groupData->customerId) ? 2 : 1 ;
            $userList[] = array(
                'sentby'=> $sentto,
                'type'=>  $type,
            );
         
        }
      
             
      
    }else{
        if($sendType == 1){
            $userList[] = array(
                'sentby'=> $groupData->sellerId,
                'type'=> 3,
            );
            
            $userList[] = array(
                'sentby'=> $groupData->customerId,
                'type'=> 2,
               
            );  

           
        }else if($sendType == 2){
            $userList[] = array(
                'sentby'=> $groupData->sellerId,
                'type'=> 3,
            );
            
            $userList[] = array(
                'sentby'=> $groupData->adminId,
                'type'=> 1,
               
            );  

         
        }else{
            $userList[] = array(
                'sentby'=> $groupData->customerId,
                'type'=> 2,
            );
            
            $userList[] = array(
                'sentby'=> $groupData->adminId,
                'type'=> 1,
               
            ); 
        }
    }
    if(!empty($customerData)){
        $userList =  array_merge($customerData,$userList);
      }
   }else{
      $groupData = SingleMessagingGroup::where('groupId',$groupId)->get()->first(); 
      $groupType = !empty($groupData->type) ? $groupData->type : 0 ;
      $createId = !empty($groupData->createId) ? $groupData->createId : 0 ;
      $sameId = !empty($groupData->sameId) ? $groupData->sameId : 0 ;
      if($groupType == 3){
            if($sendType == 1){
                $userList[] = array(
                    'sentby'=> $groupData->createId,
                    'type'=> 3,
                );
                
                $userList[] = array(
                    'sentby'=> $groupData->sameId,
                    'type'=> 3,
                
                );  
            }else{
                 
                if($sentby != $createId){
                    $userList[] = array(
                        'sentby'=> $groupData->createId,
                        'type'=> 3,
                    );
                }
               
                if($sentby != $sameId){
                    $userList[] = array(
                        'sentby'=> $groupData->sameId,
                        'type'=> 3,
                    );
                }
               
                
                $userList[] = array(
                    'sentby'=> $groupData->adminId,
                    'type'=> 1,
                
                ); 
            }
       }else if($groupType == 2){
        if($sendType == 1){
            $userList[] = array(
                'sentby'=> $groupData->createId,
                'type'=> 2,
            );
            
            $userList[] = array(
                'sentby'=> $groupData->sameId,
                'type'=> 2,
            
            );  
        }else{
             
            if($sentby != $createId){
                $userList[] = array(
                    'sentby'=> $groupData->createId,
                    'type'=> 2,
                );
            }
           
            if($sentby != $sameId){
                $userList[] = array(
                    'sentby'=> $groupData->sameId,
                    'type'=> 2,
                );
            }
           
            
            $userList[] = array(
                'sentby'=> $groupData->adminId,
                'type'=> 1,
            
            ); 
        }
       }
   }
        
      
    
  // if(!empty($transactionData)){
         
        $MessageObject = Helper::removeNull($MessageObject);
        $data['MessageObject'] = $MessageObject;
        $data['userList'] = $userList;
        $data['imageUrl'] = $imageUrl.'message/';
       
        return response()->json(['data'=>$data,'status'=>true,'message'=>'','token'=>''], $this->success);  
       
   // }else{
    //    return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Id','token'=>''], $this->success); 
   // }
    }else{
      return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Id','token'=>''], $this->success);    
   }

}
  
  
   public function groupSocket(Request $req){
     $groupId =  $req->groupId; 
     $userList = [];
     $groupData = [];
    $demoImage = asset('resources/images/demoImage.png');
    $imageUrl = Helper::getUrl(); 
    if(is_numeric($groupId)){
         
    $groupData  =  MessageGroup::select('message_groups.*','message_groups.id as groupId','m.message','m.date as messageDate',DB::raw('(CASE WHEN d.profileImage = " " THEN "'.$demoImage.'" ELSE "'.$demoImage.'" END) AS profileImage'))
                                        ->selectRaw(DB::raw('COALESCE(a.name,CONCAT(d.firstName, " ", d.lastName),q.eventName) as name'))
                                            ->leftjoin('admin as a', function($join){
                                                    $join->on('message_groups.adminId', '=', 'a.id')
                                                    ->where('message_groups.type', '=', 1);
                                                })
                                            ->leftjoin('sellers AS d', function($leftjoin){
                                                $leftjoin->on('message_groups.sellerId', '=', 'd.id')
                                                ->where('message_groups.type', '=', 1);
                                            })
                                            ->leftjoin('questionnaires AS q', function($leftjoin){
                                                $leftjoin->on('message_groups.questionnaireId', '=', 'q.id')
                                                ->where('message_groups.type', '=', 0);
                                            })
                                            ->leftjoin('recent_message_view AS m', function($leftjoin){
                                                $leftjoin->on('message_groups.id', '=', 'm.groupId');
                                            })
                                            
                                        ->where('message_groups.id',$groupId)
                                        ->orderBy('message_groups.id','DESC')
                                  ->get()->first();

        $customerId = !empty($groupData->customerId) ?  $groupData->customerId : '' ;
        $sellerId = !empty($groupData->sellerId) ?  $groupData->sellerId : '' ;
        $adminId = !empty($groupData->adminId) ?  $groupData->adminId : '' ;
        $customerData = Customer::select('id as sentby',DB::raw('2 as type'))->where('invitationId',$customerId)->get()->toArray();
    
                    
        if(!empty($groupData)){
            $userList[] = array(
                'sentby'=> $sellerId,
                'type'=> 3,
            );
            
            $userList[] = array(
                'sentby'=> $adminId,
                'type'=> 1,
               
            ); 
            
            if(!empty( $customerData)){
              $userList =  array_merge($customerData,$userList);
            }
            
             }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Id','token'=>''], $this->success);    
        }
        }else{
          
            $groupData =   SingleMessagingGroup::select('single_messaging_groups.id','single_messaging_groups.groupId','single_messaging_groups.adminId','single_messaging_groups.createId as sellerId',DB::raw('CONCAT(v.firstName," ",v.lastName) as vendorName'),DB::raw('CONCAT(s.firstName," ",s.lastName) as name'),DB::raw('(0) as questionnaireId'),DB::raw('(0) as customerId'),DB::raw('(0) as type'),DB::raw('(CASE WHEN s.profileImage = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",s.id,"/", s.profileImage) END) AS profileImage'),'m.message','m.date as messageDate')
                                    ->leftjoin('sellers AS s', function($leftjoin){
                                        $leftjoin->on('s.id', '=', 'single_messaging_groups.createId');
                                    })
                                    ->leftjoin('sellers AS v', function($leftjoin){
                                        $leftjoin->on('v.id', '=', 'single_messaging_groups.sameId');
                                    })
                            ->leftjoin('recent_message_view AS m','single_messaging_groups.groupId', '=', 'm.groupId')
                            ->where('single_messaging_groups.groupId',$groupId)
                            ->get()->first();
            if(!empty($groupData)){
            $userList[] = array(
                'sentby'=> $groupData->sellerId,
                'type'=> 3,
            );
            
            $userList[] = array(
                'sentby'=> $groupData->adminId,
                'type'=> 1,
                
            ); 
                
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Id','token'=>''], $this->success);    
        }
        }
            $data['groupData'] = $groupData;
            $data['userList'] = $userList;
            return response()->json(['data'=>$data,'status'=>true,'message'=>'','token'=>''], $this->success);  
      //   }else{
         //   return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Id','token'=>''], $this->success);    
       // }
   }


}
