<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Validator;
use DB;
use Hash;
use Helper;
use Config;
use App\Contactu;
use App\Customer;
use App\Countries;
use App\Notification;
use App\State;
use App\Admin;
use App\MessageGroup;
use App\Transaction;
use App\Questionnaire;
use App\ShareEvent;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function index(){
       
        $id = Session::get('id');
        $type = Session::get('type');
        $data = Customer::latest()->paginate(10);
        return view('customer.index',['data'=>$data]);
   }
   
   public function add(Request $req){
  
    if($req->isMethod('post')){
        $id = Session::get('id'); 
        $req->validate([
            'name' => 'required|string',
            'surname' => 'required|string',
            'country' => 'required',
            'state' => 'required',
            'email' => 'required|string|email|unique:customers',
            'phoneNo' => 'nullable|unique:customers,mobile',
        ]);
 
        $mobile = !empty($req->phoneNo) ? $req->phoneNo : '';
        $invitationCode =  strtoupper(substr(md5(time()), 0, 8)); 
        $token = Str::random(60);
        $customer = new Customer;
        $customer->name = $req->name;
        $customer->staffId = $id;
        $customer->surname = $req->surname;
        $customer->email = $req->email;
        $customer->mobile = $mobile;
        $customer->country_id = $req->country;
        $customer->state_id = $req->state;
        $customer->notification = "";
        $customer->token = $token;
        $customer->invitationId = $invitationId;
        $customer->status = 1;
        $customer->password = '';
        $customer->save();
        
        $ShareEventData = ShareEvent::where('email',$customer->email)->get()->first();
        $ShareEventCustomerId = !empty($ShareEventData->customerId) ? $ShareEventData->customerId : '' ;
        if(empty($ShareEventCustomerId) && !empty($ShareEventData)){
            $ShareEventData->customerId = $customer->id;
            $ShareEventData->save();
        }
        $adminData = Admin::where('type',1)->get()->first();
        $adminId = !empty($adminData->id) ? $adminData->id : 0 ;
        $MessageGroupData = MessageGroup::where('customerId',$customer->id)->where('type',1)->get()->first();
        if(empty($MessageGroupData)){
            $MessageGroup = new MessageGroup;
            $MessageGroup->customerId = $customer->id;
            $MessageGroup->adminId = $adminId;
            $MessageGroup->type = 1;
            $MessageGroup->save();
        }

        $name = $customer->name.' '.$customer->surname;
        $url = env('CUSTOMER_PANEL_LINK').'verify/'.$token;
        $subject = "Welcome Email"; 
        $email_data = ['name' => $name,'email' => $customer['email'],'user'=>'customer','url'=>$url,'subject' => $subject];
        //$CountryData = Countries::find($customer->country_id);
     //   $phonecode = !empty($CountryData->phonecode) ? $CountryData->phonecode: '';
        if(!empty($mobile)) {
            $mobile = $mobile;
            $msg = Config::get('msg.invitation');  
            Helper::sendMessage($msg,$mobile);
        }
       
        Helper::send_mail('emailTemplate.customerInvite',$email_data);
        
        $notification = array(
            'message' => 'Customer Add successfully!', 
            'alert-type' => 'success'
        ); 
        return redirect("customers")->with($notification);    
       }
    $Countries = Countries::all();  
    return view('customer.create',['countries'=>$Countries]);
       
      
   }
 

   public function edit(Request $req,$id=''){
    $data = Customer::where('id',$id)->get()->first();
    if($req->isMethod('post')){
        if(!empty($data)){
            $req->validate([
              'name' => 'required|string',
              'surname' => 'required|string',
              'country' => 'required',
              'state' => 'required',
              'email' => 'required|email|unique:customers,email,'.$id,
              'phoneNo' => 'nullable|unique:customers,mobile,'.$id,
   
          ]);
         
            $update = Customer::find($id);
            $update->name = $req->name;
            $update->surname = $req->surname;
            $update->email = $req->email;
            $update->mobile = $req->phoneNo;
            $update->country_id = $req->country;
            $update->state_id = $req->state;
            $update->save();  
            $notification = array(
                'message' => 'Customer Update successfully!', 
                'alert-type' => 'success'
            ); 
           return redirect("customers")->with($notification);  
        }else{
         return redirect("customers");    
        }  
    }else{
        $country_id = !empty($data->country_id) ? $data->country_id : '';
        $Countries = Countries::all();  
        $state = State::where('country_id',$country_id)->get();
       
        if($data){
            return view('customer.edit',['data'=>$data,'countries'=>$Countries,'states'=>$state]);
        }else{
            return redirect("customers");  
        }
    }
      
   }


   public function update(Request $req,$id){
       $data =  Customer::find($id);
      
   }


   public function delete(Request $req){
       $id = $req->id;
       $status = $req->status;
       $response = [];
       $customerData = Customer::find($id);
       if(!empty($customerData)){
           if($status == 'approved'){
              $customerData->status = 1;
              $response = array(
                  'status' => 'approved',
              );
              $msg  = 'approved';
           }else if($status == 'decline'){
            $customerData->status = 2;
            $response = array(
                'status' => 'decline',
            );
            $msg  = 'deactivated';
           }
           $Notification = new Notification;
           $Notification->customerId = $id;
           $Notification->type = 'customer';
           $Notification->notification = 'Your account is '.$msg.' by admin';
           $Notification->save() ;
          
          $customerData->save();
          echo  json_encode($response);
       }
      
   }
  
   public function states($id){
       $html = array();
       $action = '';
       $data = State::where('country_id',$id)->get(); 
       foreach($data as $State){
           $html[] = '<option value="'.$State->id.'">'.$State->name.'</option>';
       }
       echo json_encode($html);

   }


   public function customers(Request $request){
    
        $columns = array( 
                            0 =>'id', 
                            1 =>'title',
                            2=> 'body',
                            3=> 'created_at',
                            4=> 'id',
                        );
  
        $totalData = Customer::count();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
            
        if(empty($request->input('search.value')))
        {            
            $posts = Customer::offset($start)
                         ->limit($limit)
                         ->orderBy($order,$dir)
                         ->get();
        }
        else {
            $search = $request->input('search.value'); 

            $posts =  Customer::where('email','LIKE',"%{$search}%")
                            ->orWhere('name', 'LIKE',"%{$search}%")
                            ->orWhere('surname', 'LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = Customer::where('email','LIKE',"%{$search}%")
                             ->orWhere('name', 'LIKE',"%{$search}%")
                             ->orWhere('surname', 'LIKE',"%{$search}%")
                             ->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                if(Session::get('type') == 1){
                   $action = '<a href="'.url('customers-edit/'.$post->id.'').'" class="btn btn-theme ">Edit </a>   <a href="javascript:void(0);" data-id="'.$post->id.'"  class="delete">Delete</a>' ;
                }else{
                    $action = '<a href="'.url('customers-edit/'.$post->id.'').'" class="btn btn-theme ">Edit </a>   <a href="javascript:void(0);" data-id="'.$post->id.'"  class="delete">Delete</a>' ;
                }
                $nestedData['image'] = '<img src='.asset("resources/assets/demo/images/message.png").' alt="">';
                $nestedData['name'] = $post->name.' '.$post->surname;
                $nestedData['email'] = $post->email;
                $nestedData['mobile'] = $post->mobile;
                $nestedData['project'] = '<a href="#">View Project</a>';
                $nestedData['invoices'] = '<a href="#">View invoices</a>';
                $nestedData['action'] = $action;
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

  
    public function contactus(){
        $Contactus = Contactu::latest()->paginate(10);
        return view('contactus.index',['data'=>$Contactus]);
    }
   

}
