<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Session;
use Validator;
use DB;
use Hash;
use Helper;
use App\Admin;
use App\Cart;
use App\Notification;
use App\Transaction;
use App\Message;
use App\Questionnaire;
use App\Seller;
use App\Customer;
use App\AdminSetting;
use DateTime;
use DateInterval;

class AdminController extends Controller
{
  
   public function dashboard(){
     

    $comma = '';
    $eventChartMonth = array();
    $CustomerChartMonth = array();
    $vendorChartMonth = array();
    $plannerChartMonth = array();
    $eventChartWeek = array();
    $CustomerChartWeek =array(); 
    $plannerChartWeek = array(); 
    $vendorChartWeek = array();
      $loginId = Session::get('id');
      $imageUrl = Helper::getUrl();


      $custom_date = time(); 
      $week_start = date('d-m-Y', strtotime('this week last monday', $custom_date));
      $week_end = date('d-m-Y', strtotime('this week next sunday', $custom_date));
      $from = Carbon::parse($week_start);
      $to = Carbon::parse($week_end);

    
        
        
    $eventWeeek = Questionnaire::select(DB::raw("(COUNT(*)) as count"),DB::raw("DAYNAME(created_at) as dayname"))
                                            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                                            ->whereYear('created_at', date('Y'))
                                            ->groupBy('dayname')
                                            ->get()->toArray(); 

    $QuestionnaireDataweek = Questionnaire::select('*',DB::raw("COUNT(typeEvent) as typeEventtotal"))->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                                            ->whereYear('created_at', date('Y'));

    $QuestionnaireDatamonth  = Questionnaire::select('*',DB::raw("COUNT(typeEvent) as typeEventtotal"))->whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', Carbon::now()->month);
                                             
    $data['eventWeektypeEvent'] =  $QuestionnaireDataweek->groupBy('typeEvent')->get()->toArray();
    $data['eventmonthtypeEvent'] =  $QuestionnaireDatamonth->groupBy('typeEvent')->get()->toArray();

    $data['eventWeekIninPerson'] =  $QuestionnaireDataweek->where('levelOfService','in-person')->count(); 
    $data['eventWeekonline'] =  $QuestionnaireDataweek->where('levelOfService','online')->count(); 
    $data['eventmonthIninPerson'] =  $QuestionnaireDatamonth->where('levelOfService','in-person')->count(); 
    $data['eventmonthonline'] =  $QuestionnaireDatamonth->where('levelOfService','online')->count(); 
    
    
  
      $data['topPlannerThisWeek'] = Seller::select('sellers.*',DB::raw('count(transactions.id) as countTop'), DB::raw('(CASE WHEN sellers.profileImage = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",sellers.id,"/", sellers.profileImage) END) AS profileImage'), 'pr.ratting_cnt',DB::raw('round(pr.seller_rate/pr.ratting_cnt) as rating'))
                                    ->join('transactions','transactions.sellerId','sellers.id')
                                    ->join('questionnaires','transactions.questionnaireId','questionnaires.id')
                                    ->leftjoin(DB::raw('(select pr1.sellerId, sum(pr1.rating) as seller_rate, count(1) as ratting_cnt from reviews pr1 group by pr1.sellerId) as pr'), function($join){
                                        $join->on('pr.sellerId', '=', 'sellers.id');
                                        })
                                    ->where('sellers.type',2)
                                    ->where('transactions.status',1)
                                    ->whereBetween('transactions.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                                    ->orderBy('countTop','DESC')
                                    ->limit(10)
                                    ->get()->toArray();

    $data['topVendorThisWeek'] = Cart::select('sellers.*',DB::raw('count(carts.id) as countTop'), DB::raw('(CASE WHEN sellers.profileImage = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",sellers.id,"/", sellers.profileImage) END) AS profileImage'), 'pr.ratting_cnt',DB::raw('round(pr.seller_rate/pr.ratting_cnt) as rating'))
                                    ->join('vendor_products as p','p.id','carts.productId')
                                    ->join('sellers','sellers.id','p.sellerId')
                                    ->leftjoin(DB::raw('(select pr1.sellerId, sum(pr1.rating) as seller_rate, count(1) as ratting_cnt from reviews pr1 group by pr1.sellerId) as pr'), function($join){
                                        $join->on('pr.sellerId', '=', 'sellers.id');
                                        })
                                    ->where('sellers.type',1)
                                    ->where('carts.status',1)
                                    ->whereBetween('carts.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                                    ->orderBy('countTop','DESC')
                                    ->limit(10)
                                    ->get()->toArray();

     $data['topPlannerThisMonth'] = Seller::select('sellers.*',DB::raw('count(transactions.id) as countTop'), DB::raw('(CASE WHEN sellers.profileImage = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",sellers.id,"/", sellers.profileImage) END) AS profileImage'), 'pr.ratting_cnt',DB::raw('round(pr.seller_rate/pr.ratting_cnt) as rating'))
                           ->join('transactions','transactions.sellerId','sellers.id')
                           ->join('questionnaires','transactions.questionnaireId','questionnaires.id')
                           ->leftjoin(DB::raw('(select pr1.sellerId, sum(pr1.rating) as seller_rate, count(1) as ratting_cnt from reviews pr1 group by pr1.sellerId) as pr'), function($join){
                            $join->on('pr.sellerId', '=', 'sellers.id');
                            })
                          ->where('sellers.type',2)
                          ->where('transactions.status',1)
                          ->whereBetween('transactions.created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
                          ->whereYear('transactions.created_at', date('Y'))
                          ->orderBy('countTop','DESC')
                          ->limit(10)
                          ->get()->toArray();

    $data['topVendorThisMonth'] = Cart::select('sellers.*',DB::raw('count(carts.id) as countTop'), DB::raw('(CASE WHEN sellers.profileImage = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",sellers.id,"/", sellers.profileImage) END) AS profileImage'), 'pr.ratting_cnt',DB::raw('round(pr.seller_rate/pr.ratting_cnt) as rating'))
                          ->join('vendor_products as p','p.id','carts.productId')
                          ->join('sellers','sellers.id','p.sellerId')
                          ->leftjoin(DB::raw('(select pr1.sellerId, sum(pr1.rating) as seller_rate, count(1) as ratting_cnt from reviews pr1 group by pr1.sellerId) as pr'), function($join){
                            $join->on('pr.sellerId', '=', 'sellers.id');
                            })
                          ->where('sellers.type',1)
                          ->where('carts.status',1)
                          ->whereBetween('carts.created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
                          ->orderBy('countTop','DESC')
                          ->limit(10)
                          ->get()->toArray();

  
        
    
                  
        return view('dashboard',$data);
   }
    
   public function dashboardweek(Request $request){
        $comma = '';
        $eventChartMonth = array();
        $CustomerChartMonth = array();
        $vendorChartMonth = array();
        $plannerChartMonth = array();
        $eventChartWeek = array();
        $CustomerChartWeek =array(); 
        $plannerChartWeek = array(); 
        $vendorChartWeek = array();
        $loginId = Session::get('id');
        $imageUrl = Helper::getUrl();
        $custom_date = time(); 
        $week_start = date('d-m-Y', strtotime('this week last monday', $custom_date));
        $week_end = date('d-m-Y', strtotime('this week next sunday', $custom_date));
        $from = Carbon::parse($week_start);
        $to = Carbon::parse($week_end);
        $totalRevenueThisWeeek = Transaction::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->where('status',1)->sum('transactions.amount');      
        
        $data['totalRevenueThisWeeek'] = !empty($totalRevenueThisWeeek) ? "$"." ".number_format($totalRevenueThisWeeek,2) : "$ 0.00"  ;
        
        $eventWeeek = Questionnaire::select(DB::raw("(COUNT(*)) as count"),DB::raw("DAYNAME(created_at) as dayname"))
                                            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                                            ->whereYear('created_at', date('Y'))
                                            ->groupBy('dayname')
                                            ->get()->toArray(); 
        $QuestionnaireDataweek = Questionnaire::select('*',DB::raw("COUNT(typeEvent) as typeEventtotal"))->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                                            ->whereYear('created_at', date('Y'));                                   
        $data['eventWeektypeEvent'] =  $QuestionnaireDataweek->groupBy('typeEvent')->get()->toArray();
        $data['eventWeekIninPerson'] =  $QuestionnaireDataweek->where('levelOfService','in-person')->count(); 
        $data['eventWeekonline'] =  $QuestionnaireDataweek->where('levelOfService','online')->count(); 
        $data['topPlannerThisWeek'] = Seller::select('sellers.*',DB::raw('count(transactions.id) as countTop'), DB::raw('(CASE WHEN sellers.profileImage = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",sellers.id,"/", sellers.profileImage) END) AS profileImage'))
                                    ->join('transactions','transactions.sellerId','sellers.id')
                                    ->join('questionnaires','transactions.questionnaireId','questionnaires.id')
                                    ->where('sellers.type',2)
                                    ->where('transactions.status',1)
                                    ->whereBetween('transactions.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                                    ->orderBy('countTop','DESC')
                                    ->limit(10)
                                    ->get()->toArray();
        $data['plannerInPersonWeekCount'] =  Seller::select('*')
                                    ->leftjoin('planners','planners.plannerId','sellers.id')
                                    ->whereBetween('sellers.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                                    ->where('planners.willingWork','in-person')
                                    ->where('planners.willingWork','both')
                                   ->where('sellers.type',2)->get()->count();
        $data['plannerInnVirtualWeekCount'] =  Seller::select('*')
                                  ->leftjoin('planners','planners.plannerId','sellers.id')
                                  ->whereBetween('sellers.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                                  ->where('planners.willingWork','online')
                                  ->where('planners.willingWork','both')
                                 ->where('sellers.type',2)->get()->count();
        
        for($d = $from; $d->lte($to); $d->addDay()) {
                $st =  $d->format("Y-m-d 00:00:01");  
               $endDt =  $d->format("Y-m-d 23:59:59");
                $eventWeeek = Questionnaire::whereBetween('created_at', [$st, $endDt])->count(); 
                $CustomerWeeek = Customer::whereBetween('created_at', [$st, $endDt ])->count();
                $plannerWeeek = Seller::where('type',2)->whereBetween('created_at', [$st, $endDt ])->count();
                $vendorWeeek = Seller::where('type',1)->whereBetween('created_at', [$st, $endDt ])->count();
                //$eventChartWeek .= $comma.'{ label : "'.date("l",strtotime($d->format("Y-m-d 00:00:01"))) .'", y: '.$eventWeeek.'}'; 
				$eventChartWeek[] = $eventWeeek;
                //$CustomerChartWeek .= $comma.'{ label : "'.date("l",strtotime($d->format("Y-m-d 00:00:01"))) .'", y: '.$CustomerWeeek.'}';
				$CustomerChartWeek[] = $CustomerWeeek;				
                //$plannerChartWeek .= $comma.'{ label : "'.date("l",strtotime($d->format("Y-m-d 00:00:01"))) .'", y: '.$plannerWeeek.'}'; 
                $plannerChartWeek[] =  $plannerWeeek;
				//$vendorChartWeek .= $comma.'{ label : "'.date("l",strtotime($d->format("Y-m-d 00:00:01"))) .'", y: '.$vendorWeeek.'}'; 
				$vendorChartWeek[] =  $vendorWeeek ;
                $comma = ',';                       
            }
            $data['eventChartWeek'] = $eventChartWeek;
            $data['CustomerChartWeek'] = $CustomerChartWeek;
            $data['plannerChartWeek'] = $plannerChartWeek;
            $data['vendorChartWeek'] = $vendorChartWeek;
        
        echo json_encode($data);
   }


   public function dashboardmonth(Request $request){
    $comma = '';
    $eventChartMonth = array();
    $CustomerChartMonth = array();
    $vendorChartMonth = array();
    $plannerChartMonth = array();
    $eventChartWeek = array();
    $CustomerChartWeek =array(); 
    $plannerChartWeek = array(); 
    $vendorChartWeek = array();
    $loginId = Session::get('id');
    $imageUrl = Helper::getUrl();
    $custom_date = time(); 
    $week_start = date('d-m-Y', strtotime('this week last monday', $custom_date));
    $week_end = date('d-m-Y', strtotime('this week next sunday', $custom_date));
    $from = Carbon::parse($week_start);
    $to = Carbon::parse($week_end);


                      
    $totalRevenueThisMonth = Transaction::whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', Carbon::now()->month)->where('status',1)->sum('transactions.amount');      
        
    
    $data['totalRevenueThisMonth'] = !empty($totalRevenueThisMonth) ? "$"." ". number_format($totalRevenueThisMonth,2) : "$ 0.00"  ;
    

    $QuestionnaireDataweek = Questionnaire::select('*',DB::raw("COUNT(typeEvent) as typeEventtotal"))->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
                                            ->whereYear('created_at', date('Y'));

    $QuestionnaireDatamonth  = Questionnaire::select('*',DB::raw("COUNT(typeEvent) as typeEventtotal"))->whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', Carbon::now()->month);
                                             
     $data['eventmonthtypeEvent'] =  $QuestionnaireDatamonth->groupBy('typeEvent')->get()->toArray();
     $data['eventmonthIninPerson'] =  $QuestionnaireDatamonth->where('levelOfService','in-person')->count(); 
     $data['eventmonthonline'] =  $QuestionnaireDatamonth->where('levelOfService','online')->count(); 
    
     $data['topPlannerThisMonth'] = Seller::select('sellers.*',DB::raw('count(transactions.id) as countTop'), DB::raw('(CASE WHEN sellers.profileImage = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",sellers.id,"/", sellers.profileImage) END) AS profileImage'))
                           ->join('transactions','transactions.sellerId','sellers.id')
                           ->join('questionnaires','transactions.questionnaireId','questionnaires.id')
                          ->where('sellers.type',2)
                          ->where('transactions.status',1)
                          ->whereBetween('transactions.created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
                          ->whereYear('transactions.created_at', date('Y'))
                          ->orderBy('countTop','DESC')
                          ->limit(10)
                          ->get()->toArray();

    $data['plannerInPersonMonthCount'] =  Seller::select('*')
                                   ->leftjoin('planners','planners.plannerId','sellers.id')
                                   ->whereBetween('sellers.created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
                                    ->whereYear('sellers.created_at', date('Y'))
                                   ->where('planners.willingWork','in-person')
                                   ->where('planners.willingWork','both')
                                  ->where('sellers.type',2)->get()->count();


  $data['plannerInVirtualMonthCount'] =  Seller::select('*')
                                 ->leftjoin('planners','planners.plannerId','sellers.id')
                                 ->whereBetween('sellers.created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
                                  ->whereYear('sellers.created_at', date('Y'))
                                 ->where('planners.willingWork','online')
                                 ->where('planners.willingWork','both')
                                ->where('sellers.type',2)->get()->count();
                       
    
            for($i = 1; $i <= 12; $i++){
                    $j = $i;
                    if($i<=9){
                        $j = '0'.$i;
                    }
                    $start_month = date('Y').'-'.$j.'-'.'01 00:00:01';
                    $end_month = date('Y').'-'.$j.'-'.'31 23:59:59';
                    
                
                    $event = Questionnaire::select(DB::raw("(COUNT(*)) as count"))->whereBetween('created_at', [$start_month, $end_month ])->get()->first();	
                    $Customer = Customer::select(DB::raw("(COUNT(*)) as count"))->whereBetween('created_at', [$start_month, $end_month ])->get()->first();
                    $planner = Seller::select(DB::raw("(COUNT(*)) as count"))->where('type',2)->whereBetween('created_at', [$start_month, $end_month ])->get()->first();
                    $vendor = Seller::select(DB::raw("(COUNT(*)) as count"))->where('type',1)->whereBetween('created_at', [$start_month, $end_month ])->get()->first();
                   
                    //$eventChartMonth .= $comma.'{ label : "'.date("M",strtotime(date('d-'.$j.'-Y'))) .'", y: '.$event->count.'}';
				//	$eventChartMonth[] = array('label'=> date("M",strtotime(date('d-'.$j.'-Y'))), 'y' => $event->count);
					$eventChartMonth[] =  $event->count;
                    //$CustomerChartMonth .= $comma.'{ label : "'.date("M",strtotime(date('d-'.$j.'-Y'))) .'", y: '.$Customer->count.'}';
					$CustomerChartMonth[] = $Customer->count;
                    //$plannerChartMonth .= $comma.'{ label : "'.date("M",strtotime(date('d-'.$j.'-Y'))) .'", y: '.$planner->count.'}';
					$plannerChartMonth[] =  $planner->count;
                    //$vendorChartMonth .= $comma.'{ label : "'.date("M",strtotime(date('d-'.$j.'-Y'))) .'", y: '.$vendor->count.'}';
					$vendorChartMonth[] =  $vendor->count;
                    $comma = ',';
                   
            }    
            $data['eventChartMonth'] = $eventChartMonth;
            $data['CustomerChartMonth'] = $CustomerChartMonth;
            $data['plannerChartMonth'] = $plannerChartMonth;
            $data['vendorChartMonth'] = $vendorChartMonth;
    
    echo json_encode($data);
   }
   
    public function notifications(Request $request){
        $loginId = Session::get('id');
        $imageUrl = Helper::getUrl();
        $html = '';

        $data['notification'] = Notification::where('readStatus',0)->where('type','admin')->latest('id')->paginate(5);
        if ($request->ajax()) {
            if(!empty($data['notification'])){
            foreach($data['notification'] as $key => $value){
                if($value['urlType'] == 'customer'){
                    $link = url('customers');
                }elseif($value['urlType'] == 'event'){
                    $link = url('event-list');
                }elseif($value['urlType'] == 'vendor'){
                    $link = url('vendors');
                }elseif($value['urlType'] == 'planner'){
                    $link = url('planners');
                }elseif($value['urlType'] == 'offer'){
                    $link = url('event-list');
                }else{
                $link =  'javascript:void(0)';
                }
            $html  .= '<div class="notification_wrap notificationMove"><div class="notification_box"><div class="thumbnail" style="background: url('.asset("resources/assets/demo/images/mask.png").')">
                    </div><div class="notification_details"><h3>New request</h3><p>'.$value['notification'].'</p>
                    </div> <div class="notification_control"><a href="'.$link.'">reply request</a> <div class="trash_icon notificationDelete" data-id="'.$value['id'].'"><img src="'.asset('resources/assets//demo/images/delete.png') .'" alt=""/></div> </div></div> </div> ';
                }
            }
            return response()->json(['html'=>$html]);
        }
    
        $data['ongoing'] = Transaction::select('questionnaires.id','questionnaires.eventName','questionnaires.farEventDate',DB::raw('(CASE WHEN images.image = "" THEN "" ELSE CONCAT("'.$imageUrl .'","customer/customer",questionnaires.customerId,"/", images.image) END) AS image'))
                            ->join('questionnaires','questionnaires.id','transactions.questionnaireId')
                            ->leftjoin('images','images.questionnaireId','questionnaires.id')
                            ->groupBy('images.questionnaireId')
                            ->where('questionnaires.status',2)->where('transactions.status',1)
                            ->orderBy('questionnaires.id','DESC')->latest('id')->paginate(5);
        
        
        $data['messages'] = Message::select("messages.*")
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
                        ->where('messages.sentby','!=',$loginId)
                        ->where('messages.sendType','!=',1)
                        ->where('messages.receiveType','=',0)
                        ->orderBy('messages.id','DESC')
                    
                        ->groupBy('sentby')
                        ->latest('id')->paginate(10); 
        return view('notification',$data);
    }
        
    public function ongoingData(Request $request)
    {
        $ongoingHtml = '';
        $loginId = Session::get('id');
        $imageUrl = Helper::getUrl();
        $data['ongoing'] = Transaction::select('questionnaires.id','questionnaires.eventName','questionnaires.farEventDate',DB::raw('(CASE WHEN images.image = "" THEN "" ELSE CONCAT("'.$imageUrl .'","customer/customer",questionnaires.customerId,"/", images.image) END) AS image'))
                            ->join('questionnaires','questionnaires.id','transactions.questionnaireId')
                            ->leftjoin('images','images.questionnaireId','questionnaires.id')
                            ->groupBy('images.questionnaireId')
                            ->where('questionnaires.status',2)->where('transactions.status',1)
                            ->orderBy('questionnaires.id','DESC')->latest('id')->paginate(5);
        
            if ($request->ajax()) {
            foreach($data['ongoing']  as  $ongoingData){
                $ongoingHtml   .=   '<div class=ongoing_section>
                            <div class="ongoing_inner_box">
                                <img style="    width: 90px;height: 90px;border-radius: 50%;" src="'.(!empty($ongoingData['image']) ?   url('/resources/assets/demo/images/NoImage.png') : '').'" alt=""/>
                                    <h2>' .$ongoingData['eventName']. '</h2>
                                    <ul>
                                        <li style="display: inline-block; text-align: left;" ><a href="#">View invoice</a></li>
                                        
                                        <li style="display: inline-block;text-align: right;"><a href="#">Message administrator</a></li>
                                    </ul>
                                    <!--div class="delte_icon">
                                        <img src="'.asset('resources/assets/demo/images/delete.png').'" alt=""/>
                                    </div--->
                                </div>
                            </div>';
                }
            return response()->json(['ongoingHtml'=>$ongoingHtml]);
            }
    }
    public function MessageData(Request $request)
    {
        $messagesHtml = '';
        $loginId = Session::get('id');
        $imageUrl = Helper::getUrl();
        $data['messages'] = Message::select("messages.*")
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
                                            ->where('messages.sentby','!=',$loginId)
                                            ->where('messages.sendType','!=',1)
                                            ->where('messages.receiveType','=',0)
                                            ->orderBy('messages.id','DESC')
                                        
                                            ->groupBy('sentby')
                                            ->latest('id')->paginate(10); 
        //  print_r( $data['messages'] );
            if ($request->ajax()) {
                if(!empty($data['messages'])){
            foreach($data['messages']  as  $messages){
            
                $messagesHtml   .= '<div class="d_message_box"> <div class="message_head">
                                            <h2>'.(!empty($messages['name']) ? $messages['name'] : ' ').'</h2>
                                            <a href="'.url('message').'">reply message</a>
                                        </div>
                                        <div class="message_text">
                                            <p>'.(!empty($messages['message']) ? $messages['message'] : ' ').'</p>
                                        </div>
                                    </div>';
                    
            
                }
                }
            return response()->json(['messagesHtml'=>$messagesHtml]);
        }
            
    }

    public function profile(Request $req)
    {
        $id = Session::get('id');
        $admin = Admin::where('id',$id)->get()->first();
        return view('auth.profile',['data' => $admin ]);   
    }
    
    public function profile_insert(Request $req)
    {
        $id = Session::get('id');
        $req->validate([
            'name' => 'required',
            'phoneNo' => 'required|unique:admin,mobile,'.$id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048*5',
        ]);
        
        $admin = Admin::where('id',$id)->get()->first();
        $image = !empty($admin['image']) ? $admin['image'] : '' ;
        $admin->name =  $req->name;
        $admin->mobile =  $req->phoneNo;
        if ($req->has('image')){
            if(!empty($image)){
                Helper::deleteImage("admin/admin".$id."/".$image."");
            }
            $image = $req->file('image');
            $imageName = time().'.'.$req->image->extension();  
            $folder = Helper::imageUpload($imageName, $image,$folder="admin/admin".$id."");
            $admin->image = $imageName;
        }  
        $admin->save();
        $req->session()->put('name',$admin->name);
        $notification = array(
            'message' => 'Update Profile successfully!', 
            'alert-type' => 'success'
        ); 
        return redirect("profile")->with($notification);         
        
    }
    
    public function change_password(Request $req)
    {
        return view('auth.change_password');   
    }
    
    public function change_password_insert(Request $req)
    {
        $id = Session::get('id');
        $req->validate([
            'current_password' => 'required|min:6|max:20',
            'password' => 'required|min:6|max:20',
            'confirm_password' => 'required|same:password',
            
        ]);
        
        $admin = Admin::where('id',$id)->get()->first();
        $password = $admin->password;
        if(Hash::check($req->current_password,$password)){  
        $admin->password =  Hash::make($req->confirm_password);
        $admin->save();
        $notification = array(
            'message' => 'Psaaword Update successfully!', 
            'alert-type' => 'success'
        );

        return redirect("change-password")->with($notification);    
    }else{
        $notification = array(
            'message' => 'Oops Current Psaaword Incorrect', 
            'alert-type' => 'error'
        );

        return redirect("change-password")->with($notification);     
    }     
        
    }
        public function logout(){
            
            Session::flush();
        return redirect("login");
        }

        public function notificationStatus(Request $req){
            $id = $req->id;
            $notificationData = Notification::find($id);
            if(!empty($notificationData)){
                $notificationData->readStatus = 1;
                $notificationData->save();
            }
        }

        public function adminSetting(){
            $data['data'] = AdminSetting::first();
            return view('auth.admin_setting',$data);
        }

        public function adminSettingInsert(Request $req)
        {
           
            $req->validate([
                'tax' => 'nullable|numeric',
                'email' => 'nullable|email',
                'phone' => 'nullable',
                'fee' => 'nullable|numeric',
                'eventday' => 'required|numeric',
               
            ]);
            $data = AdminSetting::first();
            if(!empty($data )){
                $data->tax =  !empty($req->tax) ? $req->tax : 0;
                $data->email =  $req->email;
                $data->phoneNo =  $req->phone;
                $data->address =  $req->address;
                $data->eventDay =  $req->eventday;
                $data->fee =  $req->fee;
                $data->refund =  $req->refund;
                $data->save();
                $notification = array(
                    'message' => 'Update successfully!', 
                    'alert-type' => 'success'
                ); 
            }else{
                $data = new AdminSetting;
                $data->tax =  $req->tax;
                $data->email =  $req->email;
                $data->phoneNo =  $req->phone;
                $data->address =  $req->address;
                $data->eventDay =  $req->eventday;
                $data->fee =  $req->fee;
                $data->refund =  $req->refund;
                $data->save();
                $notification = array(
                    'message' => 'Add successfully!', 
                    'alert-type' => 'success'
                );  
            }
           
            
            return redirect("admin-setting")->with($notification);         
            
        }
}
