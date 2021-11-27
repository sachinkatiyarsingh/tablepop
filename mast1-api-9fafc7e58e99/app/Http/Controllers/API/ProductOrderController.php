<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Str;
use Validator;
use Hash;
use DB;
use Helper;
use Socket;
use App\Cart;
use App\Seller;
use App\Planner;
use App\Vendor;
use App\Offer;
use App\Message;
use App\Transaction;
use App\Notification;
use App\Questionnaire;
use App\Project_image;
use App\Account_information;
use App\PlannerPlan;
use App\Milestone;
use App\MessageGroup;
use App\Admin;
use App\Customer;
use App\Moodboard;
use App\MoodboardImage;
use App\StripeAccount;
use App\Vendor_product;
use Stripe\StripeClient;
use Stripe;

class ProductOrderController extends Controller
{
   public $success = 200;
   public $error = 401;
    

    public function addToCart(Request $req){
        $imageUrl = Helper::getUrl();
        $token = $req->bearerToken();
        $sellerId =  Helper::encode_token($token);
        $sellerData =  Seller::where('id',$sellerId)->where('type',2)->get()->first();
        if(!empty($sellerData)){
                $productId = $req->productId;
                $eventId = $req->eventId;
                $questionnairData = Questionnaire::where('id',$eventId)->get()->first();
                 if(!empty($questionnairData)){
                $inputQuantity = !empty($req->quantity) ? $req->quantity : 1;
                $checkProductQuantity =  Helper::checkProductQuantity($productId,$eventId,$inputQuantity);
                $response = json_decode($checkProductQuantity,true);
                if($response['status']){
                     $cartData = Cart::where('sellerId',$sellerId)->where('eventId',$eventId)->where('productId',$productId)->where('status',0)->get()->first();
                     $cartQuantity  = !empty($cartData->quantity) ? $cartData->quantity : 0 ;
                     if(!empty($cartData)){
                        $data = $cartData;
                       
                     }else{
                        $data = new Cart;
                     }
                     $data->eventId = $eventId;
                     $data->productId = $productId;
                     $data->quantity = $cartQuantity + $inputQuantity;
                     $data->sellerId = $sellerId;
                     $data->save();
                     return response()->json(['data'=>'','status'=>true,'message'=>'Add To Cart Success','token'=>''], $this->success);   
                }else{
                    return response()->json(['data'=>'','status'=>false,'message'=>$response['message'],'token'=>''], $this->success);   
                }
            }else{
                return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Event Id','token'=>''], $this->success); 
            }  
       }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
       }      
    }


    public function cartList(Request $req){
        $imageUrl = Helper::getUrl();
        $pageNo = $req->pageNo;
        $limit = env('PR_PAGE_DATA');
        $offset = !empty($pageNo) ? ($pageNo - 1)*$limit : 0 ;
        $token = $req->bearerToken();
        $sellerId =  Helper::encode_token($token);
        $sellerData =  Seller::where('id',$sellerId)->where('type',2)->get()->first();
        if(!empty($sellerData)){
               //  $eventId = $req->eventId;
               //  $questionnairData = Questionnaire::where('id',$eventId)->get()->first();
                // if(!empty($questionnairData)){
                   $cartData = Cart::select('carts.*','.p.name','p.regularPrice','p.salePrice','q.eventName',DB::raw('(CASE WHEN i.image Is Null THEN "" WHEN i.image = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",p.sellerId,"/", i.image) END) AS image'))
                                       ->join('vendor_products as p','p.id','carts.productId')
                                       ->join('questionnaires as q','q.id','carts.eventId')
                                       ->leftjoin('product_images as i','i.productId','carts.productId')
                                       ->where('carts.sellerId',$sellerId)
                                       ->groupBy('i.productId')
                                       ->where('carts.status',0)
                                       ->orderBy('carts.id');
                   $count = $cartData->count();
                   $cartData = $cartData->take($limit)->skip($offset)->get()->toArray();
                   if($cartData){
                       $data['list'] = $cartData;
                       $data['totalPage'] = ceil($count/$limit);
                    return response()->json(['data'=>$data,'status'=>true,'message'=>'Cart List','token'=>''], $this->success);   
                   }else{
                    return response()->json(['data'=>'','status'=>true,'message'=>'Cart Empty','token'=>''], $this->success);   
                   }
          //  }else{
             //   return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Event Id','token'=>''], $this->success); 
           // }   
       }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
       }      
    }

    public function catrRemoveProdct(Request $req){
    
        $token = $req->bearerToken();
        $sellerId =  Helper::encode_token($token);
        $sellerData =  Seller::where('id',$sellerId)->where('type',2)->get()->first();
        if(!empty($sellerData)){
                 $cartId = $req->id;
              //   $questionnairData = Questionnaire::where('id',$eventId)->get()->first();
              //   if(!empty($questionnairData)){
                   //  $productId =  $req->productId;
                   $cartData = Cart::where('id',$cartId)->get()->first();
                   if($cartData){
                       $cartData->delete();
                    return response()->json(['data'=>'','status'=>true,'message'=>'Product removed from cart','token'=>''], $this->success);   
                   }else{
                    return response()->json(['data'=>'','status'=>false,'message'=>'Product Not Exist This Cart List','token'=>''], $this->success);   
                   }
           // }else{
            //    return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Event Id','token'=>''], $this->success); 
           // }   
       }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
       }      
    }


   public function productBooking(Request $req){
        $error = '';
        $vendorIdArray = [];
        $eventArray = [];
        $ids = [];
        $token = $req->bearerToken();
        $sellerId =  Helper::encode_token($token);
        $sellerData =  Seller::where('id',$sellerId)->where('type',2)->get()->first();
        if(!empty($sellerData)){
                 $cartData = Cart::where('carts.sellerId',$sellerId)->where('status',0)->get()->toArray();
                 if(!empty($cartData)){
                      foreach($cartData as $cart){
                        $ids[] = $cart['id'];
                        $productId = !empty($cart['productId']) ? $cart['productId'] : 0;
                        $eventId = !empty($cart['eventId']) ? $cart['eventId'] : 0;
                        $quantity = !empty($cart['quantity']) ? $cart['quantity'] : 0;

                        $checkProductQuantity =  Helper::checkProductQuantity($productId,$eventId,$quantity);
                        $response = json_decode($checkProductQuantity,true);
                        if($response['status']){
                                 
                              $productData = Vendor_product::where('id',$productId)->get()->first();
                              $vendorId = !empty($productData->sellerId) ? $productData->sellerId : '';
                              $name = !empty($productData->name) ? $productData->name : '';
                              $regularPrice = !empty($productData->regularPrice) ? $productData->regularPrice : '';
                              $salePrice = !empty($productData->salePrice) ? $productData->salePrice : $regularPrice;
                              $vendorData = Seller::find($vendorId);
                              $vendorfirstName = !empty($vendorData->firstName) ? $vendorData->firstName : '' ;
                              $vendorlastName = !empty($vendorData->lastName) ? $vendorData->lastName : '' ; 
                             
                             if(array_key_exists($eventId,$eventArray)){
								 if($eventArray[$eventId][$vendorId]){
									$eventArray[$eventId][$vendorId]['description'] = $eventArray[$eventId][$vendorId]['description'].' <br>'.$name;
									$eventArray[$eventId][$vendorId]['amount'] = $eventArray[$eventId][$vendorId]['amount']+ ($salePrice*$quantity);
								}else{
									$eventArray[$eventId][$vendorId] = array(
									   'name' => $vendorfirstName." ".$vendorlastName." Milestone",
									   'description' => $name,
									   'amount' => $salePrice*$quantity
									);
								 }
							 }else{
								 $eventArray[$eventId][$vendorId] = array(
								   'name' => $vendorfirstName." ".$vendorlastName." Milestone",
								   'description' => $name,
								   'amount' => $salePrice*$quantity
								);
							 }
                              
                             
                              

                        }else{
                             $error .=  $response['message'].' please remove this product from your cart'."\n";    
                        } 
                      }
                     
                      if(empty($error)){
                            
                            foreach($eventArray as $key=>$itme){
                                
                              $eventMilestoneAmount = Milestone::where('questionnaireId',$key)->sum('amount');
                              $eventTransactionAmount = Transaction::where('questionnaireId',$key)->where('status',1)->sum('totalAmount');
                              
                              $questionnairData = Questionnaire::where('id',$key)->get()->first();
                              $eventName = !empty($questionnairData->eventName) ? $questionnairData->eventName : '' ;
                              $tokenId = !empty($questionnairData->tokenId) ? $questionnairData->tokenId : '' ;
                              $CustomerId = !empty($questionnairData->customerId) ? $questionnairData->customerId : '' ;
                              $customeData = Customer::find($CustomerId);
                              $customeMobile = !empty($customeData->mobile) ? $customeData->mobile : '' ;
                              $customerEmail = !empty($customeData->email) ? $customeData->email : '' ;
                              $customerName = !empty($customeData->name) ? $customeData->name : '' ;
                              $customerSurname = !empty($customeData->surname) ? $customeData->surname : '' ;
                                 foreach($itme as $keys=>$values){


                                    Helper::Event($key, $keys);

                                   $Milestones = new Milestone;
                                   $Milestones->sellerId = $sellerId;
                                   $Milestones->questionnaireId = $key;
                                   $Milestones->name = $values['name'];
                                   $Milestones->vendorId = $keys;
                                   $Milestones->type = 1;
                                   $Milestones->status = -1;
                                   $Milestones->isCompleted = 0;
                                   $Milestones->description = $values['description'];
                                   $Milestones->amount = $values['amount']; 
                                   $Milestones->save(); 

                                   

                                // if($eventMilestoneAmount > $eventTransactionAmount){
                                        $amount = $eventMilestoneAmount - $eventTransactionAmount;
                                        $offerData = Offer::where('questionnaireId',$key)->where('status',0)->where('type',1)->get()->first();
                                        $offerAmount = !empty($offerData->amount) ? $offerData->amount : 0 ;
                                        if(!empty($offerData)){
                                            $offer = $offerData;
                                        }else{
                                            $offer = new Offer;
                                        }
                                        $cartId = $ids;
                                        $cartId = implode(',', $cartId);
                                        $totalAmount = $values['amount'] + $offerAmount;
                                        $offer->groupId = 0;
                                        $offer->sellerId = $sellerId;
                                        $offer->questionnaireId = $key;
                                        $offer->description = 'Custom Offer';
                                        $offer->amount = $totalAmount;
                                        $offer->type = 2;
                                        $offer->vendorId = $keys;
                                        $offer->cartId = $cartId;
                                        $offer->milestoneId = $Milestones->id;
                                        $offer->save() ;
                                        
                                        $NotificationData = Notification::where('offerId',$offer->id)->get()->first();
                                        if(!empty($NotificationData)){
                                            $Notification = $NotificationData;
                                        }else{
                                            $Notification = new Notification;
                                        }
                                        
                                        $Notification->customerId = $CustomerId;
                                        $Notification->type = 'customer';
                                        $Notification->urltype = 'offer';
                                        $Notification->questionnaireId = $key;
                                        $Notification->offerId = $offer->id;
                                        $Notification->readStatus = 0;
                                        $Notification->fromId = $sellerId;
                                        $Notification->toId = $CustomerId;
                                        $Notification->sendType = 'seller';
                                        $Notification->notification = 'Make the payment of $'.$totalAmount.' for vendor products';
                                        $Notification->save() ;
                                        Socket::notification($userId=$CustomerId,$userType='customer');
                      
                                     }
                             // }
                              $subject = " [ACTION REQUIRED] Payment has been requested for an event milestone ($eventName/$tokenId)"; 
                              $email_data = ['email' =>$customerEmail,'name'=>$customerName.' '. $customerSurname,'user'=>'customer','subject' => $subject];  
                              Helper::send_mail('emailTemplate.paymentRequest',$email_data);
                            }
                        
                           \DB::table('carts')->whereIn('id', $ids)->update(['status' => 1]);
                            return response()->json(['data'=>'','status'=>true,'message'=>'Booked your products','token'=>''], $this->success,[],JSON_PRETTY_PRINT);      
                      }else{
                        
                         return response()->json(['data'=>'','status'=>false,'message'=>$error,'token'=>''], $this->success,[],JSON_PRETTY_PRINT);    
                      }
                      
                 }else{
                    return response()->json(['data'=>'','status'=>true,'message'=>'Cart Empty','token'=>''], $this->success);      
                 } 
       }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Token','token'=>''], $this->error); 
       } 
   }
}
