<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Session;
use Validator;
use DB;
use Hash;
use Helper;
use App\Admin;


class StaffController extends Controller
{
        
    public function __construct()
    {
        $this->middleware('adminAccess');
    }
    
    public function index(){
        $data = Admin::where('adminType',2)->orwhere('adminType',3)->latest()->paginate(10);
        return view('staff.index',['data'=>$data]);
   }
   
   public function add(){
       return view('staff.create');
   }
   public function create(Request $req){
       $option = array(); 

       $req->validate([
           'name' => 'required|string',
           'email' => 'required|string|email|unique:admin',
           'phoneNo' => 'nullable|unique:admin,mobile',
       ]);
       $option['customer'] = $req->customers;
       $option['vendor'] = $req->vendor;
       $option['eventtypes'] = $req->eventtypes;
       $option['themes'] = $req->themes;
       $option['eventList'] = $req->eventList;
       $option['calendar'] = $req->calendar;
       $option['planner'] = $req->planner;
       $option['blog'] = $req->blog;
       $option['faq'] = $req->faq;

       $mobile = !empty($req->phoneNo) ? $req->phoneNo : '';
       $token = Str::random(40);
       $data = new Admin;
       $data->name = $req->name;
       $data->email = $req->email;
       $data->mobile = $mobile;
       $data->permission = '';
       $data->token = $token;
       if($req->fullPermission){
         $data->type = 1;
         $data->permission = '';
         $data->adminType = 2;
       }else{
        $data->type = 2;
        $data->permission = json_encode($option);
        $data->adminType = 3;
       }
      
       $data->password = '';
   
       $data->save();
       

       $name = $data->name;
       $url = url('reset-password/'.$token.'');
       $subject = "Password Set"; 
       $email_data = ['name' => $name,'email' => $data['email'],'url'=>$url,'subject' => $subject];    
       Helper::send_mail('emailTemplate.staffInvite',$email_data);

       $notification = array(
           'message' => 'Staff Add successfully!', 
           'alert-type' => 'success'
       ); 
       return redirect("staff")->with($notification);    
   }

   public function edit($id){
       $data = Admin::where('id',$id)->get()->first();
       if($data){
           return view('staff.edit',['data'=>$data]);
       }else{
           return redirect("staff");  
       }
      
   }

   public function update(Request $req,$id){
       $data =  Admin::find($id);
       if(!empty($data)){
           $req->validate([
             'name' => 'required|string',
             'email' => 'required|email|unique:admin,email,'.$id,
             'phoneNo' => 'nullable|unique:admin,mobile,'.$id,
  
         ]);
        
          $mobile = !empty($req->phoneNo) ? $req->phoneNo : '';
           $update = Admin::find($id);
           $update->name = $req->name;
           $update->email = $req->email;
           $update->mobile = $mobile;
           $option['customer'] = $req->customers;
           $option['vendor'] = $req->vendor;
           $option['eventtypes'] = $req->eventtypes;
           $option['themes'] = $req->themes;
           $option['eventList'] = $req->eventList;
           $option['calendar'] = $req->calendar;
           $option['planner'] = $req->planner;
           $option['blog'] = $req->blog;
           $option['faq'] = $req->faq;
           if($req->fullPermission){
           
            $update->type = 1;
            $update->permission = '';
            $update->adminType = 2;
          }else{
            
           $update->type = 2;
           $update->permission = json_encode($option);
           $update->adminType = 3;
          }

           $update->save();  
           $notification = array(
               'message' => 'Staff Update successfully!', 
               'alert-type' => 'success'
           ); 
         
           return redirect("staff")->with($notification);  
       
       }else{
           return redirect("staff");    
       }  
   }
   public function delete($id){
       $update = Admin::find($id);
       $update->delete();
   }
}
