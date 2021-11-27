<?php

namespace App\Http\Controllers;
use Session;
use Validator;
use DB;
use App\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(){
        $adminId = Session::get('id');
        $data = Faq::where('adminId',$adminId)->latest()->paginate(10);
        return view('faq.index',['data'=>$data]);
   }
   
   public function add(Request $req){
    $adminId = Session::get('id');
    if($req->isMethod('post')){
        $req->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);
        $data =  new Faq;
        $data->adminId = $adminId;
        $data->question = $req->question;
        $data->answer = $req->answer;
        $data->save();  
        $notification = array(
            'message' => 'Faq Add successfully!', 
            'alert-type' => 'success'
        ); 
        return redirect("faq")->with($notification);  
    }
    return view('faq.create');
   }
   
 
   public function edit(Request $req,$id=''){
        $data =  Faq::find($id);
        if(!empty($data)){
            if($req->isMethod('post')){
                $req->validate([
                    'question' => 'required',
                    'answer' => 'required',
                ]);
             
                $data->question = $req->question;
                $data->answer = $req->answer;
                $data->save();  
                $notification = array(
                    'message' => 'Faq Update successfully!', 
                    'alert-type' => 'success'
                ); 
                return redirect("faq")->with($notification);  
            }
        return view('faq.edit',['data' => $data]);
        }else{
            return redirect("faq");   
        }
   }

  
   public function delete($id){
       $update = Faq::find($id);
       $update->delete();
   }
}
