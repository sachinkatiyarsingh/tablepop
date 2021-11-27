<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use App\Questionnaire;
use App\Seller;
use App\Planner;
use App\Event;
use App\Notification;
use App\Eventplanner;
use App\Transaction;
use App\Milestone;
use App\AdminSetting;
use App\StripeAccount;
use Stripe\StripeClient;
use Stripe;
use Config;
use DB;
use Helper;
use Session;
use Str;
use PDF;



class QuestionnaireController extends Controller
{
    

    public function __construct()
    {
      /*  
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET')); */
    }
    
    public function index(){
        $data = Questionnaire::select('questionnaires.*','customers.name as customers_name','customers.surname as customers_surname','customers.email as customers_email')
                                ->leftjoin('customers','questionnaires.customerId','customers.id')->get();
    //  print_r( $data );
        return view('questionnaire.index',['data'=>$data]);
    }


    public function details($id){
        $data = Questionnaire::select('questionnaires.*','themes.name as themeName')
                                ->leftjoin('themes','themes.id','questionnaires.themeEvent')
                                ->where('questionnaires.tokenId',$id)->get()->first();
                            //    print_r( $data );
        return view('questionnaire.details',['data'=>$data]);
    }

   public function plannerList(Request $req,$id){
       $distance = env('MIX_Distance_LIMIT');

       $expertise = $req->expertise;
       $experience = $req->experience;
       $skill = $req->skill;
       $location = $req->location;

       $data = Questionnaire::where('tokenId',$id)->get()->first();
       $levelOfService = !empty($data->levelOfService) ? $data->levelOfService : ' ' ;
       $latitude = !empty($data->latitude) ? $data->latitude : 0 ;
       $longitude = !empty($data->longitude) ? $data->longitude : 0 ;
       
       $planners = Seller::select(DB::raw('sellers.*, (6367 * acos(cos(radians('.$latitude.')) * cos(radians(latitude)) * cos(radians(longitude) - radians('.$longitude.')) + sin(radians('.$latitude.')) * sin(radians(latitude))) ) AS distance'),'pr.ratting_cnt',DB::raw('round(pr.seller_rate/pr.ratting_cnt) as rating'))
                        ->leftjoin('planners','planners.plannerId','sellers.id')
                         ->leftjoin(DB::raw('(select pr1.sellerId, sum(pr1.rating) as seller_rate, count(1) as ratting_cnt from reviews pr1 group by pr1.sellerId) as pr'), function($join){
                                    $join->on('pr.sellerId', '=', 'sellers.id');
                                    })
                        ->where('sellers.type',2)
                        ->where('sellers.status',1)
                        //->where('planners.willingWork',$levelOfService)
                       // ->orwhere('planners.willingWork','both')
                      //  ->having('distance', '<', $distance)
                        ;

        if(!empty($expertise)){
            $plannerData = $planners->whereRaw("find_in_set($expertise,planners.experiencePlanning)")
                                ->limit(10)
                                ->orderBy('distance')
                                ->get()->toArray();
    
        }else if(!empty($location)){
            $plannerData = $planners->where('sellers.location', 'LIKE', '%' . $location . '%')
                                ->limit(10)->orderBy('distance')->get()->toArray();
        }else if(!empty($experience)){
            $plannerData = $planners->where('planners.workingindustry',$experience)
                                ->limit(10)->orderBy('distance')->get()->toArray();
        }else if(!empty($skill)){
            $plannerData = $planners->where('planners.personalityPlanners', 'LIKE', '%' . $skill . '%')
                              ->limit(10)->orderBy('distance')->get()->toArray();
        }else{
            $plannerData = $planners->limit(10)->orderBy('distance')->get()->toArray();
        }
        $event = Event::all();
        return view('questionnaire.planner',['data'=>$plannerData,'id'=>$id,'event'=>$event]);
   
    }



    public function sendMail(Request $request){
        $sellerEmailData = array();
        $jsonPlannerId =    $request->plannerToken;
        $plannerId = json_decode($jsonPlannerId,true);
        
        $tokenId =  $request->questionnaireId;
        $questionnaireData =  Questionnaire::where('tokenId',$tokenId)->get()->first();
        $questionnaireId = $questionnaireData->id;
        $eventName = $questionnaireData->eventName;
        if(!empty($questionnaireData)){
            $customerId = !empty($questionnaireData->customerId) ? $questionnaireData->customerId  : "" ;
            $questionnaireEmail = !empty($questionnaireData->email) ? $questionnaireData->email  : "" ;
            $customerData = Customer::find($customerId);
            $customerEmail = !empty($customerData->email) ? $customerData->email : '' ;
            $customerMobile = !empty($customerData->mobile) ? $customerData->mobile : '' ;

           
             foreach($plannerId as $plnId){
                $EventplannerData =  Eventplanner::where('id',$plnId)->where('questionnaireId',$questionnaireId)->get()->first();
                if(empty($EventplannerData)){
                    $Eventplanner = new Eventplanner;
                    $Eventplanner->questionnaireId = $questionnaireId;
                    $Eventplanner->plannerId = $plnId;
                    $Eventplanner->save();
                }
                $sellerData = Seller::find($plnId);
                if(!empty($sellerData)){
                    $firstName = !empty($sellerData->firstName) ? $sellerData->firstName : '' ;
                    $lastName = !empty($sellerData->lastName) ? $sellerData->lastName : '' ;
                    $name = $firstName." ".$lastName;
                    $email = !empty($sellerData->email) ? $sellerData->email : '' ;
                    $mobile = !empty($sellerData->mobile) ? $sellerData->mobile : '' ;
                    $location = !empty($sellerData->location) ? $sellerData->location : '' ;
                    $profileImage = !empty($sellerData->profileImage) ? Helper::getUrl()."vendor/vendor".$sellerData->id."/".$sellerData->profileImage :  asset('resources/assets/demo/images/thumbnail.png') ;
                    $sellerEmailData[] = array(
                        'name' => $name,
                        'email' => $email,
                        'mobile' => $mobile,
                        'location' => $location,
                        'profileImage' => $profileImage,
                    );
                }
              
                
             }
            
             if(!empty($customerMobile)) {
                $mobile = $customerMobile;
                $msg = str_replace("{eventName}",$eventName,Config::get('msg.suggestedPlanner'));
                Helper::sendMessage($msg,$mobile);
            }

            $notification = new Notification;
            $notification->notification = 'Admin sent suggestions to choose planners for your event.';
            $notification->questionnaireId = $questionnaireId;
            $notification->customerId = $customerId;
            $notification->type = 'customer';
            $notification->urlType = 'eventPlanner';
            $notification->save();
            $subject = "Planner Details"; 
            if($questionnaireEmail == $customerEmail){
              
                $email_data = ['plannerData'=> $sellerEmailData,'email' => $customerEmail,'subject' => $subject];   
                Helper::send_mail('emailTemplate.eventPlanner',$email_data);
            }else{
               
                $email_data = ['plannerData'=> $sellerEmailData,'email' => $customerEmail,'emailTwo' => $customerEmail,'subject' => $subject];   
                
                 Helper::sendMailCC('emailTemplate.eventPlanner',$email_data);
            }
         
           echo json_encode(['status'=>true]);
         }else{
            echo json_encode(['status'=>false]);
         }
    }

    
    public function questionnaireData(Request $request){
        $status =  $request->status;
        $columns = [0 =>'id', 1 =>'eventName',2=> 'confirmationPartyPlanner',3=> 'created_at',4=> 'mobile',5=> '',6=> ''];
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        if(!empty($order)){
            $order = $columns[$request->input('order.0.column')];
        }else{
            $order = 'id';
        }
        if(!empty($dir)){
            $dir = $request->input('order.0.dir');
        }else{
            $dir = 'DESC';
        }
      
        $search = $request->input('search.value');
        $access = Helper::permission();
        $eventList = !empty($access['eventList']) ? $access['eventList'] : array() ;
        
        //Table Column Name
      
            
        //pendding data 
        if($status == 'pendding'){
            $totalData = Questionnaire::where('status',0)->count();
            $totalFiltered = $totalData; 
            if(empty($request->input('search.value'))){            
                $posts = Questionnaire::where('status',0)->offset($start)->limit($limit)->orderBy($order,$dir)->get();
            }else{ 
                $posts =  Questionnaire::where('status',0)->where('id','LIKE',"%{$search}%")->orWhere('eventName', 'LIKE',"%{$search}%")
                                        ->offset($start)->limit($limit)->orderBy($order,$dir)->get();
                $totalFiltered = Questionnaire::where('status',0)->where('id','LIKE',"%{$search}%")->orWhere('eventName', 'LIKE',"%{$search}%")->count();
            }
             
        }elseif($status == 'ongoing'){
            $totalData = Questionnaire::where('status',2)->count();
            $totalFiltered = $totalData; 
            if(empty($request->input('search.value'))){            
                $posts = Questionnaire::where('status',2)->offset($start)->limit($limit)->orderBy($order,$dir)->get();
            }else{ 
                $posts =  Questionnaire::where('status',2)->where('id','LIKE',"%{$search}%")->orWhere('eventName', 'LIKE',"%{$search}%")
                                        ->offset($start)->limit($limit)->orderBy($order,$dir)->get();
                $totalFiltered = Questionnaire::where('status',2)->where('id','LIKE',"%{$search}%")->orWhere('eventName', 'LIKE',"%{$search}%")->count();
            }
        }elseif($status == 'finish'){
            $totalData = Questionnaire::where('status',1)->count();
            $totalFiltered = $totalData; 
            if(empty($request->input('search.value'))){            
                $posts = Questionnaire::where('status',1)->offset($start)->limit($limit)->orderBy($order,$dir)->get();
            }else{ 
                $posts =  Questionnaire::where('status',1)->where('id','LIKE',"%{$search}%")->orWhere('eventName', 'LIKE',"%{$search}%")
                                        ->offset($start)->limit($limit)->orderBy($order,$dir)->get();
                $totalFiltered = Questionnaire::where('status',1)->where('id','LIKE',"%{$search}%")->orWhere('eventName', 'LIKE',"%{$search}%")->count();
            }
        }else{
            $totalData = Questionnaire::count();
            $totalFiltered = $totalData; 
            if(empty($request->input('search.value'))){            
                $posts = Questionnaire::offset($start)->limit($limit)->orderBy($order,$dir)->get();
            }else{ 
                $posts =  Questionnaire::where('id','LIKE',"%{$search}%")->orWhere('eventName', 'LIKE',"%{$search}%")
                                        ->offset($start)->limit($limit) ->orderBy($order,$dir)->get();
                $totalFiltered = Questionnaire::where('id','LIKE',"%{$search}%")->orWhere('eventName', 'LIKE',"%{$search}%")->count();
            }
        }
       
            
            $data = array();
            $html = '';
            $htmlplaner = '';
            if(!empty($posts)){
                $i = 1 ;
            foreach ($posts as $post){
                if(Session::get('type') == 1){
                  $html = '<td> <a href="'.url("event-list/$post->tokenId").'" class="btn btn-theme ">All Details</a></td>';
                  if($post->status != 2 && $post->status != 1){
                      $htmlplaner = '<td> <a href=" '.url("planner-list/$post->tokenId").'" class="btn btn-theme ">Planners</a></td>';
                  }else{
                    $htmlplaner = '';
                  }
                }
                if(in_array('details',$eventList)){
                    
                    $html =  '<td> <a href="'.url("event-list/$post->tokenId").'" class="btn btn-theme ">All Details</a></td>';
                    if($post->status != 2 && $post->status != 1){
                    $htmlplaner .= '<td> <a href=" url("planner-list/'.$post->id.'")" class="btn btn-theme ">Planners</a></td>' ;
                }else{
                    $htmlplaner = '';
                  }
                }

                if($post->status == 0){
                    $dot = '<span class="penddingdot"></span>';
                }elseif($post->status == 2){
                    $dot = '<span class="ongoingdot"></span>';
                }elseif($post->status == 1){
                    $dot = '<span class="finishdot"></span>';
                }else{
                    $dot = '<span class="penddingdot"></span>';
                }

                if($post->farEvent == 'exact'){
                    $date =  date('M d,Y, h:i:s A',strtotime($post->farEventDate));
                }else{
                    $date =  $post->farEventDate;
                }
                $nestedData['id'] = '<a href="'.url("event-details/$post->tokenId").'" class="btn btn-theme ">'.$post->tokenId.'</a>';
                $nestedData['eventName'] = $post->eventName;
                $nestedData['location'] =  $post->confirmationPartyPlanner;
                $nestedData['date'] = $date ;
                $nestedData['mobileNo'] = $post->mobile;
                $nestedData['action'] = $html.$htmlplaner;
                $nestedData['dots'] = $dot;
                $data[] = $nestedData;

            }
            }

            $json_data = array(
                "draw"            => intval($request->input('draw')),  
                "recordsTotal"    => intval($totalData),  
                "recordsFiltered" => intval($totalFiltered), 
                "data"            => $data   
                );

            echo json_encode($json_data); 
    }
    
    
    public function eventsDetails(Request $req,$eventId){
        $imageUrl = Helper::getUrl();
        $demoImage = asset('resources/assets/demo/images/thumbnail.png');
        $questionnairData = Questionnaire::select('questionnaires.*','questionnaires.customerId','questionnaires.eventName','questionnaires.confirmationPartyPlanner as address','th.name as themeEvent',DB::raw('CONCAT(questionnaires.guestExpectStart,"-",questionnaires.guestExpectEnd) as guest'),'c.name',DB::raw('(CASE WHEN i.image = "" THEN "'.$demoImage.'"  ELSE CONCAT("'.$imageUrl .'","customer/customer",c.id,"/", i.image) END) AS image'),'t.created_at as projectStartDate',DB::raw("SUM(t.amount) as amount"),DB::raw("CONCAT(SUM(t.vat),'%') as vat"),DB::raw("SUM(t.totalAmount) as totalAmount"))
                                ->join('customers as c','c.id','questionnaires.customerId')
                                ->leftjoin('images as i','i.questionnaireId','questionnaires.id')
                                ->leftjoin('themes as th','th.id','questionnaires.themeEvent')
                                ->leftjoin('transactions as t', function($join){
                                    $join->on('questionnaires.id', '=', 't.questionnaireId')
                                        ->where('t.status', '=', 1);
                                    })
                                ->groupBy('i.questionnaireId')
                                    ->where('questionnaires.tokenId',$eventId)
                                    ->get()->first();
               
        $milestones = Milestone::where('questionnaireId',$questionnairData['id'])->get()->toArray();
        $questionnairData['milestones'] = $milestones;
        $TransactionData = Transaction::select("transactions.*","c.name","c.surname")
                                    ->join('customers as c','c.id','transactions.customerId')
                                    ->where('questionnaireId',$questionnairData['id'])->get()->toArray();
        return view('questionnaire.alldetails',['eventData'=>$questionnairData,'TransactionData'=>$TransactionData]);
    }



    public function milestonePayment(Request $req){
         $milestonesId = $req->id;
         $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
         $milestoneData = Milestone::where('id',$milestonesId)->get()->first();
         if(!empty($milestoneData)){
            $milestonetype = !empty($milestoneData->type) ? $milestoneData->type : '' ;
            if($milestonetype == 1){
                $sellerId = !empty($milestoneData->vendorId) ? $milestoneData->vendorId : '' ;
            }else{
                $sellerId = !empty($milestoneData->sellerId) ? $milestoneData->sellerId : '' ;  
                
            }
            $questionnaireId = !empty($milestoneData->questionnaireId) ? $milestoneData->questionnaireId : '' ;
            $questionnaireData =  Questionnaire::where('id',$questionnaireId)->get()->first();
       
            $eventName = !empty($questionnaireData->eventName) ? $questionnaireData->eventName : '';  
            $tokenId = !empty($questionnaireData->tokenId) ? $questionnaireData->tokenId : ''; 
            
           
             $sellerData = Seller::find($sellerId);
             if(!empty($sellerData)){
                    $status = !empty($sellerData->status) ? $sellerData->status : '' ;
                    $sellerMobileNo = !empty($sellerData->mobile) ? $sellerData->mobile : '' ;
                    if($status == 1){
                          $StripeAccount = StripeAccount::where('sellerId',$sellerId)->get()->first();
                          if(!empty($StripeAccount)){
                                  $accountId = !empty($StripeAccount->accountId) ? $StripeAccount->accountId : '' ;
                                  if(!empty($accountId)){
                                       $resAccount =   $this->stripe->accounts->retrieve($accountId);
                                       $stripeStatus =  $resAccount->capabilities->transfers ;   
                                       if($stripeStatus == 'active'){
                                           $AdminSetting = AdminSetting::first();
                                           $fee = !empty($AdminSetting->fee) ?  $AdminSetting->fee : 0 ;
                                           $milestoneAmount = !empty($milestoneData->amount) ? $milestoneData->amount : 0 ;
                                           $adminAmount = $milestoneAmount*($fee/100);
                                           $totalAmount  = $milestoneAmount  - $adminAmount;
                                            $charges = $this->stripe->charges->create([
                                                "amount" => $totalAmount*100,
                                                "currency" => "usd",
                                                "source" => "tok_visa",
                                                //"application_fee_amount" => 5,
                                                "transfer_data" => [
                                                "destination" => $accountId,
                                                ],
                                            ]);
                                             $invoice = strtoupper(substr(md5(microtime()), 0, 8));
                                             $pdfName =  $invoice.'.pdf';
                                             $milestoneData->status = 3; 
                                             $milestoneData->adminCommission = $fee; 
                                             $milestoneData->totalPayment = $totalAmount; 
                                             $milestoneData->invoice =  $pdfName; 
                                             $milestoneData->invoiceId =  $invoice; 
                                             $milestoneData->save();
                                              
                                             if(!empty($sellerMobileNo)) {
                                                $mobile = $sellerMobileNo;
                                                $msg1 = str_replace("{eventName}",$eventName,Config::get('msg.approvalMilestone'));
                                                $msg = str_replace("{eventId}",$tokenId,$msg1);
                                                Helper::sendMessage($msg,$mobile);
                                            }

                                             $invoiceData =  ['company'=>$AdminSetting,'user'=>$sellerData,'milestoneData'=>$milestoneData,'email' =>$sellerData['email'],'subject' => 'Milestones Details','pdfName'=> $pdfName]; 
                                             $pdf = PDF::loadView('pdfTemplate.invoice',$invoiceData);
                                             Helper::imageUpload($pdfName,$pdf->output(),$folder="vendor/vendor".$sellerId);
                                             Helper::sendMailAttachData('pdfTemplate.blank',$invoiceData,$pdf,$pdfName);
                                            $response = json_encode(['status'=>true,'message'=>'Success']);
                                       }else{
                                        $response = json_encode(['status'=>false,'message'=>'Stripe Account  Not Active']); 
                                       }
                                  }else{
                                     $response = json_encode(['status'=>false,'message'=>'Seller Not Add Bank Account']);
                                  }
                          }else{
                            $response = json_encode(['status'=>false,'message'=>'Seller Not Add Bank Account']); 
                          }
                    }else{
                        $response = json_encode(['status'=>false,'message'=>'Seller Account Not Active']);
                    }
             }else{
                $response = json_encode(['status'=>false,'message'=>'Invalid Seller Id']);
             }
         }else{
             $response = json_encode(['status'=>false,'message'=>'Invalid Id']);
         }
      echo $response;
    }
}
