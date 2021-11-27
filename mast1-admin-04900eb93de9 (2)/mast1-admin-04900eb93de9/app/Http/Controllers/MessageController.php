<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SingleMessagingGroup;
use App\Questionnaire;
use App\MessageGroup;
use App\PlannerPlan;
use App\Transaction;
use App\Customer;
use App\Message;
use App\Seller;
use Validator;
use Helper;
use Session;
use DB;

class MessageController extends Controller
{
   public function index(){
         $customerData =  [];  
         $SellerData = [];
         $demoImage = asset('resources/assets/demo/images/thumbnail.png');
         $imageUrl = Helper::getUrl(); 
         $groupData  =  MessageGroup::select('message_groups.*','message_groups.id as groupId','q.eventName','m.message','m.date as messageDate')
                                       ->selectRaw(DB::raw('COALESCE(c.name,q.eventName,COALESCE(CONCAT(d.firstName, " ", d.lastName))) as eventName'))
                                
                                         ->leftjoin('customers as c', function($join){
                                                $join->on('message_groups.customerId', '=', 'c.id')
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
                                           
                                        ->where('message_groups.adminId',1)
                                        ->orderBy('message_groups.id','DESC')
                                        ->get()->toArray();
        
        $list =   SingleMessagingGroup::select('single_messaging_groups.id','single_messaging_groups.groupId','single_messaging_groups.createId as sellerId',DB::raw('CONCAT(v.firstName," ",v.lastName) as vendorName'),DB::raw('CONCAT(s.firstName," ",s.lastName) as name'),DB::raw('CONCAT(s.firstName," ",s.lastName) as eventName'),DB::raw('(0) as questionnaireId'),DB::raw('(0) as customerId'),DB::raw('(0) as adminId'),DB::raw('(0) as type'),DB::raw('(CASE WHEN s.profileImage = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",s.id,"/", s.profileImage) END) AS profileImage'),'m.message','m.date as messageDate')
                                        ->leftjoin('sellers AS s', function($leftjoin){
                                            $leftjoin->on('s.id', '=', 'single_messaging_groups.createId');
                                        })
                                        ->leftjoin('sellers AS v', function($leftjoin){
                                            $leftjoin->on('v.id', '=', 'single_messaging_groups.sameId');
                                        })
                                ->leftjoin('recent_message_view AS m','single_messaging_groups.groupId', '=', 'm.groupId')
                                ->where('single_messaging_groups.type',3)
                                ->get()->toArray();
                             
        $groupDataAll = (array_merge($groupData,$list));
      
       return view('message.index',['data'=>$groupDataAll,'sellerData'=>$SellerData,'customerData'=>$customerData,'transactionId'=>'admin_1']);
   }

   public function messsageListShow(Request $req){
        $html = '';
        $imageUrl = Helper::getUrl(); 
        $demoImage = asset('resources/assets/demo/images/thumbnail.png');
        $loginId = Session::get('id'); 
        $groupId = $req->id;
        $user = $req->user;
        $typeData = $req->typeData;
        $groupData = MessageGroup::where('id',$groupId)->get()->first();
        $groupType = !empty($groupData->type) ? $groupData->type : '' ;
      
            $messageData =  Message::select("messages.*",DB::raw('( CASE WHEN messages.sendType = 3  THEN (CASE WHEN d.profileImage = "" THEN "'.$demoImage.'" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",d.id,"/", d.profileImage) END)  END) AS sellerImage'),DB::raw('(CASE WHEN messages.sendType = 2  THEN (CASE WHEN c.profileImage IS NULL THEN  "'.$demoImage.'" WHEN c.profileImage = "" THEN "'.$demoImage.'" ELSE CONCAT("'.$imageUrl .'","customer/customer",c.id,"/", c.profileImage) END) END) AS customerImage'),DB::raw('(CASE WHEN messages.sendType = 1  THEN (CASE WHEN a.image IS NULL THEN  "'.$demoImage.'" WHEN a.image = "" THEN "'.$demoImage.'" ELSE CONCAT("'.$imageUrl .'","admin/admin",a.id,"/", a.image) END) END) AS adminImage'))
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
                    ->where('messages.groupId',$groupId)
                    ->orderBy('messages.id','asc')
                    ->get()
                    ->toArray();
        
       
               // print_r( $messageData);          
        if(!empty($messageData)){
            foreach($messageData as $msg){
                  
                 if($msg['sendType'] == 1){
                    $image =  '' ;
                 }else if($msg['sendType'] == 2){
                    $image =  !empty($msg['sellerImage']) ? $msg['sellerImage'] :  $demoImage ;
                    
                 }else{
                    $image =  !empty($msg['customerImage']) ? $msg['customerImage'] :  $demoImage ;
               
                 }
                  
                 if($msg['sentby'] == $loginId && $msg['sendType'] == 1){
                     $class = 'me';
                 }else{
                    $class = 'you';
                 }
                 if($msg['sentby'] == $loginId && $msg['sendType'] == 1){
                     $name = '';
                 }else{
                    $name = $msg['name'];
                 }

               
                 $html .=  '<div class="message-box '.$class.'">';
                               if($class == 'you'){
                                   $html .= ' <div class="thumbnail" style="background: url('.$image.');">
                                   </div>';
                               }
                            
                 $html .=  '   <span class="messageSendName" style="font-size:10px"> '.$name.' </sapn>      <div class="message_content">
                            <div class="message-text">
                                <p>' .$msg['message']. '</p>';
                                if(!empty($msg['msgFile']))  {
                                    $files = json_decode($msg['msgFile']);
                                    foreach($files as $value){
                                        $fileType = Helper::fileType($value);
                                        if($fileType == 'image'){
                                     $html .= '<a href="'.$imageUrl.'message/'.$value.'" download><img style="width: 100px;height: 100px;
                                     " src="'.$imageUrl.'message/'.$value.'"></a>';
                                        }else{
                                            $html .= '<a href="'.$imageUrl.'message/'.$value.'" download><img style="    width: 20px;height: 20px;
                                            " src="'.asset('resources/assets/demo/images/files.png').'"></a>';
                                              
                                        }
                                    }
                                    
                               }
                 $html .=            '</div>
                            <span class="date">'.date('l h:s a',strtotime($msg['updated_at'])).'</span>
                        </div>';
                        if($class == 'me'){
                            $html .= '  <div class="thumbnail" style="background: url('.$image.');">
                            </div>';
                        }
                    
                $html .=  '</div>';    
            }
           
          
        } 
        echo json_encode(array('html'=>$html));
   }


   public function messageSend(Request $req){
      $imageUrl = Helper::getUrl();  
       $loginId = Session::get('id'); 
       $loginUserImage = Session::get('image');
       $html = ''; 
       $demoImage = asset('resources/assets/demo/images/thumbnail.png');
       $image = !empty($loginUserImage) ? $loginUserImage : $demoImage ;
       $groupId =  $req->id;
       $text = !empty($req->text) ? $req->text : '';
       $files = $req->msgFile;
       $msgFile = json_encode(json_decode($files,true),JSON_UNESCAPED_SLASHES);
       $user = $req->user;
       $groupData = MessageGroup::where('id',$groupId)->get()->first();
       $groupType = !empty($groupData->type) ? $groupData->type : 0 ;
    //   if(!empty($groupData)){
        if(is_numeric($groupId)){
           if($groupType == 1){
              $sentto = !empty($groupData->customerId) ? $groupData->customerId : $groupData->sellerId ;
              $receiveType = !empty($groupData->customerId) ? 2 : 3 ;
          }else{ 
             $sentto = !empty($groupData->questionnaireId) ? $groupData->questionnaireId : 0 ; 
             $receiveType = 0 ;
          }
        }else{
            $groupIdArray = explode('_',$groupId);
            $sentto = $groupIdArray['2'];
            $receiveType = 3; 
        }   

          //if(!empty($customerData)){
                    $message = new Message;
                    $message->sentby = $loginId;
                    $message->sentto = $sentto;
                    $message->groupId = $groupId ;
                    $message->message = $text;
                 
                    if(!empty($files)){
                        $message->msgFile = $msgFile;
                    }
                    $message->sendType = 1;
                    if($receiveType){
                        $message->receiveType = $receiveType;
                    }
              
                   
                    $message->date = date('Y-m-d h:i:s a');
                    $message->readStatus = 0;
                    $message->save();
           
                    $html .=    '<div class="message-box me">
                                    <div class="message_content">
                                       <div class="message-text">
                                           <p>' .$text. '</p>';
                                          if(!empty($message->msgFile))  {
                                               $files = json_decode($message->msgFile,true);
                                               foreach($files as $value){
                                                $fileType = Helper::fileType($value);
                                                if($fileType == 'image'){
                                                    $html .= '<a href="'.$imageUrl.'message/'.$value.'" download><img style="    width: 100px;height: 100px;" src="'.$imageUrl.'message/'.$value.'"></a>';
                                                }else{
                                                    $html .= '<a href="'.$imageUrl.'message/'.$value.'" download><img style="    width: 20px;height: 20px;" src="'.asset('resources/assets/demo/images/files.png').'"></a>';
                                              
                                                }
                                                }
                                               
                                          }
                     $html .=    ' </div>
                                        <span class="date">'.date('l h:s a').'</span>
                                     </div> 
                                </div>'; 
                    return response()->json(['data'=>$html,'status'=>true,'message'=>'Message Send','messageId'=>$message->id]); 
           //}  
     //  }else{
      //  return response()->json(['data'=>'','status'=>false,'message'=>'In-valid Id','messageId'=>'']);   
      // }
   }


   public function imageUpload(Request $request){
   
        $validator = Validator::make($request->all(), [ 
            'file' => 'required|mimes:pdf,docx,doc,jpeg,jpg,png,gif|max:2048*5',
        ]);
        if ($validator->fails()) { 
            echo  json_encode(array('msg'=>$validator->errors()->all(),'status'=>false)) ;                           
        }else{    
                
                $image = $request->file;
                $imageName = time().$request->file('file')->getClientOriginalName();
                $imageName = str_replace( " ", "-", trim($imageName) );
                $folder = Helper::imageUpload($imageName, $image,$folder="message");

                $filenames[] = $imageName;
                   
                
                $html = '<div class="alert alert-success alert-rounded">'.$imageName.' Image Successfully Upload
                        <button type="button" data-value="'.$imageName.'" class="close remove_images" data-dismiss="alert" aria-label="Close"> <span class="text-danger">×</span> </button>
                        </div>';
                echo json_encode(['msg'=>'Updated Successfully', 'status'=>true, 'filename'=>$filenames,'html' => $html]);
                 
        }
   }

   public function removeImage(Request $request){ 
        Helper::deleteImage("message".$request->name);   
        echo json_encode(['msg'=>' Successfully', 'status'=>true]);       
   }
}
