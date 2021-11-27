<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Validator;
use DB;
use App\Event;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('permissions');
    }
    
    public function index(){
        $data = Event::latest()->paginate(10);
        return view('event.index',['data'=>$data]);
   }
   
   public function add(Request $req){
    if($req->isMethod('post')){
        $req->validate([
            'event' => 'required|regex:/^[\pL\s\-]+$/u',
        ]);
        $data =  new Event;
        $data->name = $req->event;
        $data->save();  
        $notification = array(
            'message' => 'Event Type Add successfully!', 
            'alert-type' => 'success'
        ); 
        return redirect("event-types")->with($notification);  
    }
    return view('event.create');
   }
   
 
   public function edit(Request $req,$id=''){
        $data =  Event::find($id);
        if(!empty($data)){
            if($req->isMethod('post')){
                $req->validate([
                    'event' => 'required|regex:/^[\pL\s\-]+$/u',
                ]);
                
                $data->name = $req->event;
                $data->save();  
                $notification = array(
                    'message' => 'Event Type Update successfully!', 
                    'alert-type' => 'success'
                ); 
                return redirect("event-types")->with($notification);  
            }
        return view('event.edit',['data' => $data]);
        }else{
            return redirect("event-types");   
        }
   }

  
   public function delete($id){
       $update = Event::find($id);
       $update->delete();
   }
}
