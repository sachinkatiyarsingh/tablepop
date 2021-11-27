<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Str;
use Validator;
use Hash;
use DB;
use Helper;
use App\Seller;
use App\Planner;
use App\Vendor;
use App\Project_image;
use App\Product_image;
use App\Vendor_product;
use App\Account_information;
use App\Notification;
use App\Questionnaire;
use App\Milestone;
use App\Blog;
use App\Review;
use Socket;

class VendorController extends Controller
{
    public $success = 200;
    public $error = 401;
     
    
    public function products(Request $req){
        $token = $req->bearerToken();
        $encode_token =  Helper::encode_token($token);
        $sellerId = $encode_token;
        $pageNo = $req->PageNumber;
        $sellerData =  Seller::where('id',$sellerId)->where('type',1)->get()->first();  
        if(!empty($sellerData)){
            $imageUrl = Helper::getUrl();
            $limit = env('PR_PAGE_DATA');
            $offset = !empty($pageNo) ? ($pageNo - 1)*$limit : 0 ;
            $count = Vendor_product::where('vendor_products.sellerId',$sellerId)->count();
            $productData = Vendor_product::select('vendor_products.*', DB::raw('(SELECT(CASE WHEN i.image Is Null THEN "" WHEN i.image = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",vendor_products.sellerId,"/", i.image) END) FROM product_images as i WHERE i.productId=vendor_products.id LIMIT 1) AS image'))->where('vendor_products.sellerId',$sellerId)
                                           ->orderBy('vendor_products.id','DESC')
                                           ->take($limit)->skip($offset)
                                           ->get()->toArray();
            $totalPage = ceil($count/$limit);     
           if(!empty($productData)){
               $productData = Helper::removeNull($productData);
               $data['product'] = $productData;
               $data['totalPage'] = $totalPage;
              
            return response()->json(['data'=>$data,'status'=>true,'message'=>'Product List','token'=>''], $this->success);   
           }else{
            return response()->json(['data'=>'','status'=>true,'message'=>'Data Empty','token'=>''], $this->success);   
           }
                                         
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
        } 
    }

    public function productDetails(Request $req){
        $productId = $req->productId;
        $imageUrl = Helper::getUrl();
        $VendorProduct = Vendor_product::find($productId);
        if(!empty($VendorProduct)){
            $sellerId = !empty($VendorProduct->sellerId) ? $VendorProduct->sellerId : '' ;
             $productData = $VendorProduct;
            
             $images = Product_image::select('id',DB::raw('(CASE WHEN image = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor","'.$sellerId.'","/", image) END) AS image'))->where('productId',$productId)->get()->toArray();
             if($images){
                $productData['images'] = $images;
             }else{
                $productData['images'] = [];
             }
             if(!empty($productData)){
                return response()->json(['data'=>$productData,'status'=>true,'message'=>'Product List','token'=>''], $this->success);   
               }else{
                return response()->json(['data'=>'','status'=>true,'message'=>'Data Empty','token'=>''], $this->success);   
               }
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Id','token'=>''], $this->success); 
        } 
    }

    public function productAdd(Request $req){
        $token = $req->bearerToken();
        $encode_token =  Helper::encode_token($token);
        $sellerId = $encode_token;
        $sellerData =  Seller::where('id',$sellerId)->where('type',1)->get()->first();  
        if(!empty($encode_token)){
            $validator = Validator::make($req->all(), [ 
                'name' => 'required',
                'quantity' => 'required',
                'description' => 'required', 
                'regularPrice' => 'required|numeric', 
                'salePrice' => 'nullable|numeric',
                'image' => 'nullable',
                'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048*5' 
            ]);
        
           if ($validator->fails()) { 
                return response()->json(['data'=>'','status'=>false,'message'=>$validator->errors()->all(),'token'=>''], $this->success);            
            }else{
                 $salePrice = !empty($req->salePrice) ? $req->salePrice : 0 ;
                 $VendorProduct = new Vendor_product;
                 $VendorProduct->name = $req->name;
                 $VendorProduct->quantity = $req->quantity;
                 $VendorProduct->description = $req->description;
                 $VendorProduct->regularPrice = $req->regularPrice;
                 $VendorProduct->salePrice = $salePrice;
                 $VendorProduct->sellerId = $sellerId;
                 $VendorProduct->save();

                 if($VendorProduct->save()){
                  
                     if($req->hasfile('image'))
                     {  
                       foreach($req->file('image') as  $file)
                       {
                            $name= time().'.'.$file->getClientOriginalName();
                            $name = str_replace( " ", "-", trim($name) );
                            $folder = Helper::imageUpload($name, $file,$folder="vendor/vendor".$sellerId."");
                            $productImage = new Product_image;
                            $productImage->productId = $VendorProduct->id;
                            $productImage->sellerId =  $sellerId;
                            $productImage->image = $name;
                            $productImage->save();
                       }
                    }
                   
                 }
                 return response()->json(['data'=>'','status'=>true,'message'=>'Product Add Successfully','token'=>''], $this->success); 
            }  
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
        } 
    }


    public function productEdit(Request $req){
        $productId = $req->productId;
        $VendorProduct = Vendor_product::find($productId);
        if(!empty($VendorProduct)){
            $sellerId = !empty($VendorProduct->sellerId) ? $VendorProduct->sellerId : '' ;
            $validator = Validator::make($req->all(), [ 
                'name' => 'required',
                'quantity' => 'required',
                'description' => 'required', 
                'regularPrice' => 'required|numeric', 
                'salePrice' => 'nullable|numeric',
                'image' => 'nullable',
                'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048*5' 
            ]);
        
           if ($validator->fails()) { 
                return response()->json(['data'=>'','status'=>false,'message'=>$validator->errors()->all(),'token'=>''], $this->success);            
            }else{
                $salePrice = !empty($req->salePrice) ? $req->salePrice : 0 ;
                 $VendorProduct->name = $req->name;
                 $VendorProduct->quantity = $req->quantity;
                 $VendorProduct->description = $req->description;
                 $VendorProduct->regularPrice = $req->regularPrice;
                 $VendorProduct->salePrice = $salePrice;
                 $VendorProduct->sellerId = $sellerId;
                 $VendorProduct->save();

                 if($VendorProduct->save()){
                  
                     if($req->hasfile('image'))
                     {  
                       foreach($req->file('image') as  $file)
                       {
                            $name= time().'.'.$file->getClientOriginalName();
                            $name = str_replace( " ", "-", trim($name) );
                            $folder = Helper::imageUpload($name, $file,$folder="vendor/vendor".$sellerId."");
                            $productImage = new Product_image;
                            $productImage->productId = $productId;
                            $productImage->sellerId =  $sellerId;
                            $productImage->image = $name;
                            $productImage->save();
                       }
                    }
                 }
                 return response()->json(['data'=>'','status'=>true,'message'=>'Product Update Successfully','token'=>''], $this->success); 
            }  
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Id','token'=>''], $this->success); 
        } 
    }


    public function productDelete(Request $req){
        $productId = $req->productId;
        $VendorProduct = Vendor_product::find($productId);
        if(!empty($VendorProduct)){
            $VendorProduct->delete();
            DB::table('product_images')->where('productId', $productId)->delete();
            
            return response()->json(['data'=>'','status'=>true,'message'=>'Delete Successfully','token'=>''], $this->success); 
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Id','token'=>''], $this->success); 
        }     
    }

    public function productImageDelete(Request $req){
        $imageId = $req->imageId;
        $Product_image = Product_image::find($imageId);
        if(!empty($Product_image)){
            $image = !empty($Product_image->image) ? $Product_image->image : '' ;
            $sellerId = !empty($Product_image->sellerId) ? $Product_image->sellerId : '' ;
            $path = 'vendor/verdor'.$sellerId.'/'.$image;
            Helper::deleteImage($path);
            $Product_image->delete();
            return response()->json(['data'=>'','status'=>true,'message'=>'Delete Successfully','token'=>''], $this->success); 
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Id','token'=>''], $this->success); 
        }

    }


    public function vendorProfile(Request $req){
        $vendorId = $req->vendorId;
        $imageUrl = Helper::getUrl();
        $sellerData =  Seller::where('id',$vendorId)->where('type',1)->get()->first();
        if(!empty($sellerData)){
                $sellerData  = Seller::select('sellers.*','vendors.servicesCategory','vendors.serviceSubCategory','vendors.otherServices','vendors.experiencePlanning', DB::raw('(CASE WHEN sellers.profileImage = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",sellers.id,"/", sellers.profileImage) END) AS profileImage'),'countries.name as country','vendors.otherExperiencePlanning')
                                ->leftjoin('countries','countries.id','sellers.countryId')
                                ->leftjoin('vendors','vendors.sellerId','sellers.id')
                          //      ->leftjoin('events','vendors.experiencePlanning','events.id')
                                ->where('sellers.id',$vendorId)->where('sellers.type',1)->get()->first();   
            $servicesCategory  = !empty($sellerData->servicesCategory) ? $sellerData->servicesCategory : [] ;
            $serviceSubCategory  = !empty($sellerData->serviceSubCategory) ? $sellerData->serviceSubCategory : [] ;
            if(!empty($servicesCategory)){
                     $sellerData['servicesCategory'] = array_map('intval', explode(',', $servicesCategory));
                }else{
                    $sellerData['servicesCategory'] = [];
                }
            if(!empty($serviceSubCategory)){
                $sellerData['serviceSubCategory'] = array_map('intval', explode(',', $serviceSubCategory));
                }else{
                    $sellerData['serviceSubCategory'] = [];
                }
             if(!empty($sellerData)){

                $sellerData['accountInformation'] = Account_information::where('sellerId',$vendorId)->get()->first();
                $sellerData['projectImage'] = Project_image::select("id","event","numberAttendees","locationEvent",DB::raw('(CASE WHEN image = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor","'.$vendorId.'","/", image) END) AS image'))->where('sellerId',$vendorId)->get()->toArray();
                $sellerData['blogs'] = Blog::select('blogs.*',DB::raw('(CASE WHEN blog_images.file = "" THEN "" ELSE CONCAT("'.$imageUrl .'","admin/blogs/", blog_images.file) END) AS file'))
                                    ->leftjoin('blog_images','blog_images.blogId','blogs.id')
                                    ->where('blog_images.type','image')
                                    ->whereRaw("find_in_set($vendorId,blogs.sellerId)")
                                    ->groupBy('blog_images.blogId')
                                    ->get()->toArray();
                $rating = Review::where('sellerId',$vendorId)->avg('rating');
             $review = Review::select('reviews.*',DB::raw('CONCAT(c.name," ",c.surname) as name'),DB::raw('(CASE WHEN c.profileImage = "" THEN "" ELSE CONCAT("'.$imageUrl .'","customer/customer",c.id,"/", c.profileImage) END) AS profileImage'))
                ->join('customers as c','c.id','reviews.customerId')
                                   ->where('sellerId',$vendorId)->get()->toArray();
                $sellerData['rating'] = round($rating);
                $sellerData['review'] = $review;
                    $sellerData = Helper::removeNull($sellerData);
                  return response()->json(['data'=>$sellerData,'status'=>true,'message'=>'vendor Details','token'=>''], $this->success); 
                }else{
                  return response()->json(['data'=>'','status'=>false,'message'=>'Data List','token'=>''], $this->success); 
                }
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Id','token'=>''], $this->success); 
        }
    }



    public function vendorproducts(Request $req){
        $sellerId = $req->vendorId;
        $pageNo = $req->pageNo;
        $sellerData =  Seller::where('id',$sellerId)->where('type',1)->get()->first();  
        if(!empty($sellerData)){
            $imageUrl = Helper::getUrl();
            $limit = env('CHAT_PER_PAGE_NO');
            $offset = !empty($pageNo) ? ($pageNo - 1)*$limit : 0 ;
            $count = Vendor_product::where('vendor_products.sellerId',$sellerId)->count();
            $productData = Vendor_product::select('vendor_products.*', DB::raw('(SELECT(CASE WHEN i.image Is Null THEN "" WHEN i.image = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",vendor_products.sellerId,"/", i.image) END) FROM product_images as i WHERE i.productId=vendor_products.id LIMIT 1) AS image'))->where('vendor_products.sellerId',$sellerId)
            ->orderBy('vendor_products.id','DESC')
                                           ->take($limit)->skip($offset)
                                           ->get()->toArray();
            $totalPage = ceil($count/$limit);     
           if(!empty($productData)){
               $productData = Helper::removeNull($productData);
               $data['product'] = $productData;
               $data['totalPage'] = $totalPage;
              
            return response()->json(['data'=>$data,'status'=>true,'message'=>'Product List','token'=>''], $this->success);   
           }else{
            return response()->json(['data'=>'','status'=>true,'message'=>'Data Empty','token'=>''], $this->success);   
           }
                                         
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Id','token'=>''], $this->success); 
        } 
    }


    public function milestones(Request $req){
        $token = $req->bearerToken();
        $sellerId =  Helper::encode_token($token);
        $sellerData =  Seller::find($sellerId);  
        if(!empty($sellerData)){
            $eventId = $req->eventId;
            $questionnairData = Questionnaire::where('id',$eventId)->get()->first();
           // print_r( $questionnairData);
            if($questionnairData){
            $Milestones =  Milestone::where('vendorId',$sellerId)->where('questionnaireId',$eventId)->where('type',1)->get()->toArray();
            if(!empty($Milestones)){
                return response()->json(['data'=>$Milestones,'status'=>true,'message'=>'Milestones','token'=>''], $this->success); 
       
            }else{
               return response()->json(['data'=>'','status'=>true,'message'=>'Data Empty','token'=>''], $this->success); 
       
            }
            
           return response()->json(['data'=>'','status'=>true,'message'=>'Success','token'=>''], $this->success);  
            }else{
                return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Id','token'=>''], $this->success); 
            }
        }else{
           return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
        }
   }
}
