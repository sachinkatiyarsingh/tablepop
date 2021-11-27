<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Validator;
use DB;
use Hash;
use App\Admin;
use Str;
use Helper;


class AuthController extends Controller
{
    public function login(){
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {        
        $this->validate($request, [
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);
         $email = $request->email;
         $password = $request->password;      
         $data = Admin::where('email',$email)->get()->first();
        
         if(!empty($data)){
               if(Hash::check($password,$data->password)){  
                    $notification = array(
                        'message' => 'Login successfully!', 
                        'alert-type' => 'success'
                    ); 
                    Session::put('id',$data['id']);
                    Session::put('email',$data['email']);
                    Session::put('name',$data['name']);
                    Session::put('mobile',$data['mobile']);
                    Session::put('type',$data['type']);
                    Session::put('image',$data['image']);
                    Session::put('permission',$data['permission']);
                 
                return redirect("/")->with($notification);            
               }else{
                $notification = array(
                    'message' => 'Oops Psaaword Incorrect ', 
                    'alert-type' => 'error'
                ); 
                return redirect("login")->with($notification);
               }
             
         }else{
            $notification = array(
                'message' => 'Oops Invalid Email', 
                'alert-type' => 'error'
            ); 
            return redirect("login")->with($notification);
    
         }

    }


    public function forget(){
        return view('auth.forget');
    }


    public function forget_insert(Request $request)
    {        
        $this->validate($request, [
            'email'    => 'required|email',
        ]);
         $email = $request->email;
         $data = Admin::where('email',$email)->get()->first();
        
         if(!empty($data)){
              
            $token = Str::random(32).time();
            $update = Admin::find($data['id']);
            $update->token = $token;
            $update->save();

            $url = url('/').'/reset-password/'.$token;
            $subject = "Reset Password"; 
            $email_data = ['name' => $data['name'],'email' => $data['email'],'url'=>$url,'subject' => $subject];    
            Helper::send_mail('emailTemplate.resetPassword',$email_data);

            $notification = array(
                'message' => 'Reset-Password Mail Send Successfully', 
                'alert-type' => 'success'
            ); 
         return redirect("forget")->with($notification);
             
         }else{
            $notification = array(
                'message' => 'Oops Invalid Email', 
                'alert-type' => 'error'
            ); 
            return redirect("forget")->with($notification);
    
         }

    }


    public function reset($token){
          
        $data = Admin::where('token',$token)->get()->first();
        if($data){
        return view('auth.reset_password',['token'=>$token]);
        }else{
            $notification = array(
                'message' => 'Oops Invalid Token', 
                'alert-type' => 'error'
            ); 
            return redirect("forget")->with($notification);
        }
    }

    public function reset_password_insert(Request $req,$id)
    {
    
      $req->validate([
            'password' => 'required|min:6|max:20',
            'confirm_password' => 'required|same:password',
        ]);
         
        $admin = Admin::where('token',$id)->get()->first();
        $admin->password =  Hash::make($req->confirm_password);
        $admin->token = '';
        $admin->save();
        $notification = array(
            'message' => 'Psaaword Reset successfully!', 
            'alert-type' => 'success'
        );
  
        return redirect("login")->with($notification);    
      
        
    }
}
