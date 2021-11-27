<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Validator;
use DB;
use Hash;
use Helper;
use App\Blog;
use App\BlogImage;
use App\Seller;

class BlogController extends Controller
{
    public function index(){
        $adminId = Session::get('id');
         $data = Blog::where('adminId',$adminId)->latest()->paginate(10);
         return view('blog.index',['data'=>$data]);
    }


    public function add(Request $req){
        $sellerIds = '';
        $adminId = Session::get('id');
        if($req->isMethod('post')){
            

            $req->validate([
                'title' => 'required',
                'description' => 'required',  
            ]);
            $sellers = $req->input('seller');
            if($sellers){
                $sellerIds = implode(',',$sellers);  
            }
            $data =  new Blog;
            $data->adminId = $adminId;
            $data->title = $req->title;
            $data->description = $req->description;
            $data->sellerId = $sellerIds;
            $data->save(); 
            $blogs_files = $req->blogs_files;
            if(!empty($blogs_files)){
                $json = json_decode($blogs_files,true);
                foreach($json as $file){
                    $files = new BlogImage;
                     $files->blogId = $data->id;
                     $files->file = $file['name'];
                     $files->type = $file['type'];
                     $files->save();
                }
            }
           
            $notification = array(
                'message' => 'Blog Add successfully!', 
                'alert-type' => 'success'
            );  
           return redirect("blogs")->with($notification);  
        }
        $sellers  = Seller::all();
        return view('blog.add',['data'=>$sellers]);
    }

    public function edit(Request $req,$id){
        $sellerIds = '';
        $blog = Blog::find($id);
        if(!empty($blog)){
            $blogImage = BlogImage::where('blogId',$id)->get()->toArray();
        if($req->isMethod('post')){
            

            $req->validate([
                'title' => 'required',
                'description' => 'required',
              
            ]);
            $sellers = $req->input('seller');
            if($sellers){
                $sellerIds = implode(',',$sellers);  
            }
            $blog->title = $req->title;
            $blog->description = $req->description;
            $blog->sellerId = $sellerIds;
            $blog->save(); 
            $blogs_files = $req->blogs_files;
            if(!empty($blogs_files)){
                $json = json_decode($blogs_files,true);
                foreach($json as $file){
                    $files = new BlogImage;
                     $files->blogId = $id;
                     $files->file = $file['name'];
                     $files->type = $file['type'];
                     $files->save();
                }
            }
           
            $notification = array(
                'message' => 'Blog Update successfully!', 
                'alert-type' => 'success'
            );  
           return redirect("blogs")->with($notification);  
        }
    }else{
        return redirect("blogs");  
    } 
        $sellers  = Seller::all();
        return view('blog.edit',['data'=>$sellers,'blog'=>$blog,'blogImage'=>$blogImage]);
    }

    public function delete($id){
        $blog = Blog::find($id);
        if(!empty($blog)){
            $blog->delete();
            $blogImage = BlogImage::where('blogId',$id)->get()->toArray();
            if(!empty($blogImage)){
                foreach($blogImage as $file){
                    Helper::deleteImage("admin/blogs/".$file['file']."");
                }
            }
            DB::table('blog_images')->where('blogId', $id)->delete();
        }
    }


    public function imageUpload(Request $request){

        if(!empty($_FILES['file']['name']))
        {
           $file_type = Helper::fileType($_FILES['file']['name']);
                
           if($file_type == 'image')
           {   
              
                $image = $request->file('file');
                $path = $request->file('file')->getRealPath();      
                $name = time().''.$request->file('file')->getClientOriginalName();
                $name = str_replace( " ", "-", trim($name) );
                $folder = Helper::imageUpload($name, $image,$folder="admin/blogs");
                    $filenames[] = array(
                        'name' =>  $name,
                        'type' =>  $file_type,
                    );
                $img_name = env('IMAGE_SHOW_URL').'/temp'.'/'.$name;
                $html = '<li class="move" style="position: relative;">
                            <div>"'.$name.'" Updated Successfully</div>
                            <button type="button"  data-value="'.$name.'" class="btn btn-primary remove_images" style="position: absolute;right: -10px;top: -10px; padding: 1px 6px;">×</button>     
                        </li>';
                echo json_encode(['msg'=>'Updated Successfully', 'status'=>true, 'filename'=>$filenames,'html' => $html]);

                
                
            }else if($file_type == 'video'){
                $image = $request->file('file');
                $path = $request->file('file')->getRealPath();      
                $name = time().''.$request->file('file')->getClientOriginalName();
                $name = str_replace( " ", "-", trim($name) );
                $folder = Helper::imageUpload($name, $image,$folder="admin/blogs");
                    $filenames[] = array(
                        'name' =>  $name,
                        'type' =>  $file_type,
                    );
                $img_name = env('IMAGE_SHOW_URL').'/temp'.'/'.$name;
                $html = '<li class="move" style="position: relative;">
                             <div>'.$name.' Updated Successfully</div>
                             <button type="button"  data-value="'.$name.'" class="btn btn-primary remove_images" style="padding: 1px 6px;position: absolute;right: -10px;top: -10px;">×</button>
                         </li>';
                echo json_encode(['msg'=>'Updated Successfully', 'status'=>true, 'filename'=>$filenames,'html' => $html]);
            }else{
                echo json_encode(['msg'=>'Invalid File Type.', 'status'=>false, 'filename'=>'','html' => '']);
            }
        }
    }


    public function fileDelete(Request $req){
        Helper::deleteImage("admin/blogs/".$req->file."");
    }

    public function blogFileDelete(Request $req,$id){
        $blogImage = BlogImage::where('id',$id)->get()->first();
        $file = !empty($blogImage->file) ? $blogImage->file : '';
        Helper::deleteImage("admin/blogs/".$file."");
        $blogImage->delete();             
    }
}
