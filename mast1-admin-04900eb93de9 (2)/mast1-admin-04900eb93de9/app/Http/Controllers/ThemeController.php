<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Validator;
use DB;
use App\Theme;

class ThemeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permissions');
    }
    
    public function index(){
        $data = Theme::latest()->paginate(10);
        return view('theme.index',['data'=>$data]);
   }
   
   public function add(Request $req){
    if($req->isMethod('post')){
        $req->validate([
            'theme' => 'required|regex:/^[\pL\s\-]+$/u',
        ]);
        $data =  new Theme;
        $data->name = $req->theme;
        $data->save();  
        $notification = array(
            'message' => 'Theme Add successfully!', 
            'alert-type' => 'success'
        ); 
        return redirect("themes")->with($notification);  
    }
    return view('theme.create');
   }
   
 
   public function edit(Request $req,$id=''){
        $data =  Theme::find($id);
        if(!empty($data)){
            if($req->isMethod('post')){
                $req->validate([
                    'theme' => 'required|regex:/^[\pL\s\-]+$/u',
                ]);
                
                $data->name = $req->theme;
                $data->save();  
                $notification = array(
                    'message' => 'Theme Update successfully!', 
                    'alert-type' => 'success'
                ); 
                return redirect("themes")->with($notification);  
            }
        return view('theme.edit',['data' => $data]);
        }else{
            return redirect("themes");   
        }
   }

  
   public function delete($id){
       $update = Theme::find($id);
       $update->delete();
   }
}
