<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Helper;
use DB;
use Stripe;
use Stripe_Error;
use Str;
use PDF;
use App;
use Config;
use Socket;
use App\Admin;
use App\Offer;
use App\Cart;
use App\Milestone;
use App\Seller;
use App\Transaction;
use App\Customer;
use App\Questionnaire;
use App\PlannerPlan;
use App\Notification;
use App\MessageGroup;
use App\AdminSetting;
use App\Vendor_product;
use App\CustomerAddress;



class PaymentController extends Controller
{
  public $success = 200;
  public $error = 401;
   

 /*   public function pdf(){
    $list =  DB::raw("tt.*")
    ->selectSub("(SELECT m.message FROM recent_message_view AS m where m.groupId = tt.groupId LIMIT 1) as message")
    ->from('(select sellers.id from sellers where sellers.type = 1) as tt')
    ->toSql();
      /*   $AdminSetting = AdminSetting::first();
        $cu = Customer::first();
        $transaction = Transaction::select('transactions.*','countries.name as country','plannerplans.title')
                                 ->leftjoin('countries','countries.id','transactions.country')
                                 ->leftjoin('plannerplans','plannerplans.id','transactions.planId')
                                  ->get()->first();
           $pdf = PDF::loadView('pdfTemplate.invoice', ['company'=>$AdminSetting,'user'=>$cu,'transaction'=>$transaction]); 
                            
           
     print_r($list);
//return view('pdfTemplate.invoice');
    }  */
       
    public function paymentDetails(Request $req){
      $imageUrl = Helper::getUrl();
        $cartData = [];
        $amount = 0;
        $grangTotal = 0;
        $taxAmount = 0;
        $productId = $req->productId;
        $planId = $req->planId;
        $offerId = $req->offerId;

        $productData = Vendor_product::find($productId); 
        $planData = PlannerPlan::find($planId); 
        $offerData = Offer::find($offerId); 
        $AdminSetting = AdminSetting::first();
        $tax = !empty($AdminSetting->tax) ? $AdminSetting->tax : 0;
        if(!empty($productData)){
            $regularPrice = !empty($productData->regularPrice) ? $productData->regularPrice : 0 ;
            $salePrice = !empty($productData->salePrice) ? $productData->salePrice : 0 ;
            $amount = !empty($salePrice) ? $salePrice : $regularPrice ;
            $taxAmount =    $amount*($tax/100);
            $grangTotal = $amount + $taxAmount ;

        }elseif(!empty($planData)){
            $planAmount = !empty($planData->regularPrice) ? $planData->regularPrice : 0 ;
            $amount = !empty($planData->salePrice) ? $planData->salePrice : $planAmount ;
            $taxAmount =    $amount*($tax/100);
            $grangTotal = $amount + $taxAmount ;
        }elseif(!empty($offerData)){
            $amount = !empty($offerData->amount)  ? $offerData->amount : '';
            $taxAmount =    $amount*($tax/100);
            $grangTotal = $amount + $taxAmount ;

            $offerType = !empty($offerData->type) ? $offerData->type : '' ;
            $cartId = !empty( $offerData->cartId) ? $offerData->cartId : '' ;
            $cartId = explode(',',$cartId);
            if($offerType  == 2){
               $cartData =   $cartData = Cart::select('carts.*','.p.name','p.regularPrice','p.salePrice','q.eventName',DB::raw('(CASE WHEN i.image Is Null THEN "" WHEN i.image = "" THEN "" ELSE CONCAT("'.$imageUrl .'","vendor/vendor",p.sellerId,"/", i.image) END) AS image'))
                                  ->join('vendor_products as p','p.id','carts.productId')
                                  ->join('questionnaires as q','q.id','carts.eventId')
                                  ->leftjoin('product_images as i','i.productId','carts.productId')
                                  ->whereIn('carts.id',$cartId)
                                  ->groupBy('i.productId')
                  
                                  ->orderBy('carts.id')->get()->toArray();
            }
        }

        $array = array(
           'price' => $amount,
           'vat' => $tax.'%',
           'vatAmount' => $taxAmount,
           'grandtotal' => $grangTotal,
        );
        if(!empty($productData) || !empty($planData) || !empty($offerData)){
            $data  = $array;
            $data['cartData']  = $cartData;
            return response()->json(['data'=>$data,'status'=>true,'message'=>'','token'=>''], $this->success); 
        }else {
            return response()->json(['data'=>'','status'=>false,'message'=>'','token'=>''], $this->success); 
        }
    }


    public function customerPayment(Request $req){
        $token = $req->bearerToken();
        $paymentToken = $req->paymentToken;
        $questionnaireId = $req->questionnaireId;
        $planId = $req->planId;
        $addressId = $req->addressId;
        $CustomerAddress = CustomerAddress::where('id',$addressId)->get()->first();
        $encode_token =  Helper::encode_token($token);
        $CustomerId = $encode_token;
        $CustomerData = Customer::find($CustomerId);
        $array = '';
        if(!empty($CustomerData)){
               $name = !empty($CustomerData->name) ? $CustomerData->name : ''; 
               $surname = !empty($CustomerData->surname) ? $CustomerData->surname : ''; 
               $customerEmail = !empty($CustomerData->email) ? $CustomerData->email : ''; 
               $customerMobile = !empty($CustomerData->mobile) ? $CustomerData->mobile : ''; 
               $customerStripeId = !empty($CustomerData->stripeId) ? $CustomerData->stripeId : ''; 

              $fullName = $name.' '.$surname;
              $questionnaireData = Questionnaire::find($questionnaireId);
              if(!empty($questionnaireData)){
                       $eventName = !empty($questionnaireData->eventName) ? $questionnaireData->eventName : '' ;
                       $tokenId = !empty($questionnaireData->tokenId) ? $questionnaireData->tokenId : '' ;
                       $planData = PlannerPlan::find($planId);
                      if(!empty($planData)){
                           $planAmount = !empty($planData->regularPrice) ? $planData->regularPrice : 0 ;
                           $planSalePrice = !empty($planData->salePrice) ? $planData->salePrice : $planAmount ;
                           $sellerId = !empty($planData->sellerId) ? $planData->sellerId : 0 ;
                           $isCustom = !empty($planData->isCustom) ? $planData->isCustom : 0 ;
                           $superAdmin = Admin::where('type',1)->where('adminType',1)->get()->first();
                           $adminMobile = !empty($superAdmin->mobile) ? $superAdmin->mobile : '' ;
                           $admiId = !empty($superAdmin->id) ? $superAdmin->id : '' ;
                           $AdminSetting = AdminSetting::first();
                           $tax = !empty($AdminSetting->tax) ? $AdminSetting->tax : 0;
                           $companyEmail  = !empty($AdminSetting->email) ? $AdminSetting->email : 0;
                           $companyphoneNo  = !empty($AdminSetting->phoneNo) ? $AdminSetting->phoneNo : 0;
                           $companyaddress  = !empty($AdminSetting->address) ? $AdminSetting->address : 0;
                        
                           $MessageGroupData = MessageGroup::where('questionnaireId',$questionnaireId)->get()->first();
                           $TransactionId = Helper::generateRandomTransactionId();
                           if($isCustom == 1){
                                /* $transaction = new Transaction;
                                $transaction->street = '';
                                $transaction->country = '';
                                $transaction->phoneNumber = '';
                                $transaction->customerId = $CustomerId;
                                $transaction->questionnaireId = $questionnaireId;
                                $transaction->planId = $planId;
                                $transaction->sellerId = $sellerId;
                                $transaction->transactionId = $TransactionId;
                                $transaction->token = '';
                                $transaction->amount = 0;
                                $transaction->status = 3;
                                $transaction->save(); */
                                
                                if(empty($MessageGroupData)){
                                    $MessageGroup = new MessageGroup;
                                    $MessageGroup->customerId = $CustomerId;
                                    $MessageGroup->sellerId = $sellerId;
                                    $MessageGroup->adminId = $superAdmin->id;
                                    $MessageGroup->questionnaireId = $questionnaireId;
                                    $MessageGroup->type = 0;
                                    $MessageGroup->save();
 
                                }

                                Helper::Event($questionnaireId, $sellerId);
                                $questionnaireData->status = 2;
                                $questionnaireData->save();
                                $array = array(
                                    'groupId' => !empty($MessageGroup->id) ? $MessageGroup->id : $MessageGroupData->id,
                                 );
                            return response()->json(['data'=>$array,'status'=>true,'message'=>'Custom plan selected','token'=>''], $this->success); 
                          
                         }else{
                        if(!empty($paymentToken)){

                          if(empty($CustomerAddress)){
                            
                            $validator = Validator::make($req->all(),[ 
                                'street' => 'required',
                                'country' => 'required',
                                'phoneNumber' => 'required',
                            ]);
                        
                         if ($validator->fails()){ 
                               return response()->json(['data'=>'','status'=>false,'message'=>$validator->errors()->all(),'token'=>''], $this->success);            
                         }else{ 
                             $validator = '';
                             $addressData = CustomerAddress::where('id',$CustomerId)
                             ->where('street',$req->street)->where('country',$req->country)->where('phoneNumber',$req->phoneNumber)->where('status',0)->get()->first();
                          
                            if(empty($addressData)){
                                $address =  new CustomerAddress;
                                $address->customerId = $CustomerId;
                                $address->street = $req->street;
                                $address->country = $req->country;
                                $address->phoneNumber = $req->phoneNumber;
                                $address->save() ;
                                $addressId = $address->id;
                            }else{
                              $addressId = $addressData->id;
                            }
                            
                
                         }
                        }
                          if(empty($validator)){
                           $stripe = Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                           try{
                           
                             $token = $paymentToken;
                             $taxAmount = $planSalePrice*($tax/100);
                             $price = $planSalePrice;
                             $totalPrice = $planSalePrice +  $taxAmount;
                             
                            if (!isset($token)) {
                               
                                   //return redirect()->route('addmoney.paymentstripe');
                               }
                               $charge = \Stripe\Charge::create([
                                   'card' => $token,
                                   'currency' => env('CURRENCY_TYPE'),
                                   'amount' =>  $totalPrice*100,
                                   'description' => 'card',
                               ]);
                    
                               if($charge['status'] == 'succeeded') {
                                   $transaction = new Transaction;
                                   $transaction->addressId = $addressId;
                                   $transaction->customerId = $CustomerId;
                                   $transaction->questionnaireId = $questionnaireId;
                                   $transaction->planId = $planId;
                                   $transaction->sellerId = $sellerId;
                                   $transaction->transactionId = $TransactionId;
                                   $transaction->stripeTransactionId = $charge['id'];
                                   $transaction->token = $token;
                                   $transaction->amount = $planSalePrice;
                                   $transaction->vat = $tax;
                                   $transaction->totalAmount = $totalPrice;
                                   $transaction->json =  $charge;
                                   $transaction->status = 1;
                                   $transaction->paymentMethod = 'stripe';
                                   $transaction->save();


                                  Helper::Event($questionnaireId, $sellerId);
                                  $questionnaireData->status = 2;
                                  $questionnaireData->save();

                                  if(empty($customerStripeId)){
                                     $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
                                     $stripeIdData =  $stripe->customers->create([
                                        'name' => $fullName,
                                       
                                      'email' =>  $customerEmail,
                                    ]); 
                                      $CustomerData->stripeId = $stripeIdData['id'];
                                      $CustomerData->save();
                                  }
                                  

                              /*      $CustomeNotification = new Notification;
                                   $CustomeNotification->customerId = $CustomerId;
                                   $CustomeNotification->notification = 'Payment Success';
                                   $CustomeNotification->type = 'customer';
                                   $CustomeNotification->save(); */


                                   $sellerNotification = new Notification;
                                   $sellerNotification->sellerId = $sellerId;
                                   $sellerNotification->notification = 'Customer '.$fullName.' made a payment of $'.$totalPrice.' for '.$eventName.'. You can start event.';
                                   $sellerNotification->questionnaireId = $questionnaireId;
                                   $sellerNotification->type = 'seller';
                                   $sellerNotification->urlType = 'event';
                                   $sellerNotification->fromId = $CustomerId;
                                   $sellerNotification->toId = $sellerId;
                                   $sellerNotification->sendType = 'customer';
                                   $sellerNotification->save();
                                   Socket::notification($sellerId,$userType='seller');
                                   $adminNotification = new Notification;
                                   $adminNotification->customerId = $CustomerId;
                                   $adminNotification->questionnaireId = $questionnaireId;
                                   $adminNotification->notification = 'Customer '.$fullName.' made a payment of $'.$totalPrice.' for '.$eventName.'';
                                   $adminNotification->type = 'admin';
                                   $adminNotification->urlType = 'event';
                                   $adminNotification->fromId = $CustomerId;
                                   $adminNotification->toId = $admiId;
                                   $adminNotification->sendType = 'customer';
                                   $adminNotification->save();
                                   Socket::notification($userId=$admiId,$userType='admin');
                                    if(!empty($adminMobile)){
                                      $mobile = $adminMobile;

                                      $msg1 = str_replace("{eventId}",$tokenId,Config::get('msg.payment'));
                                      $msg2 = str_replace("{amount}",$totalPrice,$msg1);
                                      $msg = str_replace("{transactinId}",$TransactionId,$msg2);
                                      Helper::sendMessage($msg,$mobile);
                                    
                                    }


                                    if(!empty($customerMobile)){
                                      $mobile = $customerMobile;
                                      $msg1 = str_replace("{eventId}",$tokenId,Config::get('msg.payment'));
                                      $msg2 = str_replace("{amount}",$totalPrice,$msg1);
                                      $msg = str_replace("{transactinId}",$TransactionId,$msg2);  

                                      Helper::sendMessage($msg,$mobile);
                                    }
                                   
                                   if(empty($MessageGroupData)){
                                            $MessageGroup = new MessageGroup;
                                            $MessageGroup->customerId = $CustomerId;
                                            $MessageGroup->sellerId = $sellerId;
                                            $MessageGroup->adminId = $superAdmin->id;
                                            $MessageGroup->questionnaireId = $questionnaireId;
                                            $MessageGroup->type = 0;
                                            $MessageGroup->save();
                                   }
                                    
                                   $array = array(
                                        'transactionId' =>  $TransactionId,
                                        'groupId' => !empty($MessageGroup->id) ? $MessageGroup->id : $MessageGroupData->id,
                                   );

                                $transaction = Transaction::select('transactions.*','countries.name as country','plannerplans.title')
                                                            ->leftjoin('customer_address','customer_address.id','transactions.addressId')
                                                            ->leftjoin('countries','countries.id','customer_address.country')
                                                            ->leftjoin('plannerplans','plannerplans.id','transactions.planId')
                                                            ->where('transactions.id',$transaction->id)
                                                            ->get()->first();
                                $pdfName =  $TransactionId.'.pdf';
                           //     try{
                                 $invoiceData =  ['company'=>$AdminSetting,'eventName'=>$eventName,'user'=>$CustomerData,'transaction'=>$transaction,'email' =>$CustomerData['email'],'subject' => 'PayMent Details','pdfName'=> $pdfName]; 
                                 $pdf = PDF::loadView('pdfTemplate.invoice',$invoiceData);
                                 file_put_contents(public_path().'/'.$pdfName, $pdf->output());
                                 $pdfFolder =  file_get_contents(public_path().'/'.$pdfName);    
                                 Helper::imageUpload($pdfName,$pdfFolder,$folder="customer/customer".$CustomerId);
                                 Helper::sendMailAttachData('pdfTemplate.blank',$invoiceData,$pdf,$pdfName);
                                 $transactionUpdate = Transaction::find($transaction->id);
                                 $transactionUpdate->invoice = $pdfName;                  
                                 $transactionUpdate->save();     
                                 unlink(public_path().'/'.$pdfName)  ;    
                               // } catch(\Exception $e) {
                             
                               // }                     
                                return response()->json(['data'=>$array,'status'=>true,'message'=>'Payment Success','token'=>''], $this->success);  
                               }else{
                                $transaction = new Transaction;
                                $transaction->addressId = $addressId;
                                $transaction->customerId = $CustomerId;
                                $transaction->questionnaireId = $questionnaireId;
                                $transaction->planId = $planId;
                                $transaction->sellerId = $sellerId;
                                $transaction->transactionId = $TransactionId;
                                $transaction->token = $token;
                                $transaction->amount = $planSalePrice;
                                $transaction->vat = $tax;
                                $transaction->totalAmount = $totalPrice;
                                $transaction->status = 2;
                                $transaction->paymentMethod = 'stripe';
                                $transaction->save();


                              /*   $CustomeNotification = new Notification;
                                $CustomeNotification->customerId = $CustomerId;
                                $CustomeNotification->notification = 'Payment Fail';
                                $CustomeNotification->type = 'customer';
                                $CustomeNotification->questionnaireId = $questionnaireId;
                                $CustomeNotification->urlType = 'event';
                                
                                $CustomeNotification->save(); */
                            /*     Socket::notification($userId=$CustomerId,$userType='customer'); */
                                return response()->json(['data'=>'','status'=>false,'message'=>'something went to wrong.','token'=>''], $this->success); 
                                
                               }
                    
                            } catch(\Stripe\Exception\CardException $e) {
                                // Since it's a decline, \Stripe\Exception\CardException will be caught
                                return response()->json(['data'=>'','status'=>false,'message'=> $e->getError()->message,'token'=>''], $this->success); 
                               
                              } catch (\Stripe\Exception\RateLimitException $e) {
                                return response()->json(['data'=>'','status'=>false,'message'=> $e->getError()->message,'token'=>''], $this->success); 
                              } catch (\Stripe\Exception\InvalidRequestException $e) {
                                return response()->json(['data'=>'','status'=>false,'message'=> $e->getError()->message,'token'=>''], $this->success); 
                              } catch (\Stripe\Exception\AuthenticationException $e) {
                                return response()->json(['data'=>'','status'=>false,'message'=> $e->getError()->message,'token'=>''], $this->success); 
                              } catch (\Stripe\Exception\ApiConnectionException $e) {
                                return response()->json(['data'=>'','status'=>false,'message'=> $e->getError()->message,'token'=>''], $this->success); 
                              } catch (\Stripe\Exception\ApiErrorException $e) {
                                return response()->json(['data'=>'','status'=>false,'message'=> $e->getError()->message,'token'=>''], $this->success); 
                              } catch (Exception $e) {
                              return response()->json(['data'=>'','status'=>false,'message'=> $e->getError()->message,'token'=>''], $this->success); 
                              }
                        }
                            }else{
                                return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Payment Token','token'=>''], $this->success);  
                            }
                        }
                      }else{
                        return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Plan Id','token'=>''], $this->success);  
                      }

              }else{
                return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Questionnaire Id','token'=>''], $this->success);  
              }
           
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Customer Token','token'=>''], $this->error);  
        }
    }


    public function offerPayment(Request $req){
        $token = $req->bearerToken();
        $paymentToken = $req->paymentToken;
        $encode_token =  Helper::encode_token($token);
        $CustomerId = $encode_token;
        $addressId = $req->addressId;
        $CustomerAddress = CustomerAddress::where('id',$addressId)->get()->first();
        $CustomerData = Customer::find($CustomerId);
        $name = !empty($CustomerData->name) ? $CustomerData->name : ''; 
               $surname = !empty($CustomerData->surname) ? $CustomerData->surname : ''; 
               $fullName = $name.' '.$surname;
               $customerEmail = !empty($CustomerData->email) ? $CustomerData->email : ''; 
               $customerMobile = !empty($CustomerData->mobile) ? $CustomerData->mobile : ''; 
               $customerStripeId = !empty($CustomerData->stripeId) ? $CustomerData->stripeId : ''; 
        if(!empty($CustomerData)){
               $offerId = $req->offerId;
               $offerData = Offer::where('id',$offerId)->where('status',0)->get()->first();
               $offerType = !empty( $offerData->type) ? $offerData->type : '' ;
               $cartId = !empty( $offerData->cartId) ? $offerData->cartId : '' ;
               $milestoneId = !empty( $offerData->milestoneId) ? $offerData->milestoneId : '' ;
               $TransactionId = Helper::generateRandomTransactionId();
               if(!empty($offerData)){
                         $questionnaireId = !empty($offerData->questionnaireId)  ? $offerData->questionnaireId : '';
                         $questionnaireData = Questionnaire::find($questionnaireId);
                         $eventName = !empty($questionnaireData->eventName) ? $questionnaireData->eventName : '' ;
                         $tokenId = !empty($questionnaireData->tokenId) ? $questionnaireData->tokenId : '' ;
                         $amount = !empty($offerData->amount)  ? $offerData->amount : 0;
                         $sellerId = !empty($offerData->sellerId)  ? $offerData->sellerId : '';
                         $vendorId = !empty($offerData->vendorId)  ? $offerData->vendorId : 0;
                         $superAdmin = Admin::where('type',1)->where('adminType',1)->get()->first();
                         $adminMobile = !empty($superAdmin->mobile) ? $superAdmin->mobile : '' ;
                         $adminId= !empty($superAdmin->id) ? $superAdmin->id : '' ;
                        
                        // $transaction = Transaction::where('questionnaireId',$questionnaireId)->where('status',3)->get()->first();
                         $AdminSetting = AdminSetting::first();
                         $tax = !empty($AdminSetting->tax) ? $AdminSetting->tax : 0;            
                       // if(!empty($transaction)){
                        if(!empty($paymentToken)){
                          if(empty($CustomerAddress)){
                            
                            $validator = Validator::make($req->all(),[ 
                                'street' => 'required',
                                'country' => 'required',
                                'phoneNumber' => 'required',
                            ]);
                        
                         if ($validator->fails()){ 
                               return response()->json(['data'=>'','status'=>false,'message'=>$validator->errors()->all(),'token'=>''], $this->success);            
                         }else{ 
                             $validator = '';
                          $addressData = CustomerAddress::where('customerId',$CustomerId)
                             ->where('street',$req->street)->where('country',$req->country)->where('phoneNumber',$req->phoneNumber)->where('status',0)->get()->first();
                            
                             
                              if(empty($addressData)){
                                  $address =  new CustomerAddress;
                                  $address->customerId = $CustomerId;
                                  $address->street = $req->street;
                                  $address->country = $req->country;
                                  $address->phoneNumber = $req->phoneNumber;
                                  $address->save() ;
                                  $addressId = $address->id;
                              }else{
                                $addressId = $addressData->id;
                              }
                             
                
                         }
                        }
                          if(empty($validator)){ 
                           $stripe = Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                           try{
                             
                            
                            $token = $paymentToken;
                           
                            $price = $amount;
                            $taxAmount =  $price*($tax/100);
                            $totalPrice = $price + $taxAmount;
                             
                             
                            if (!isset($token)) {
                               
                                   //return redirect()->route('addmoney.paymentstripe');
                               }
                               $charge = \Stripe\Charge::create([
                                   'card' => $token,
                                   'currency' => env('CURRENCY_TYPE'),
                                   'amount' =>  $totalPrice*100,
                                   'description' => 'card',
                               ]);
                    
                               if($charge['status'] == 'succeeded') {
                                  

                                $transaction = new Transaction;
                                    $transaction->addressId = $addressId;
                                   $transaction->customerId = $CustomerId;
                                   $transaction->questionnaireId = $questionnaireId;
                                   $transaction->planId = 0;
                                   $transaction->sellerId = $sellerId;
                                   $transaction->offerId = $offerId;
                                   $transaction->json = $charge;
                                   $transaction->transactionId = $TransactionId;
                                   $transaction->stripeTransactionId = $charge['id'];
                                   $transaction->token = $token;
                                   $transaction->amount = $price;
                                   $transaction->vat = $tax;
                                   $transaction->totalAmount = $totalPrice;
                                   $transaction->status = 1;
                                   $transaction->paymentMethod = 'stripe';
                                   $transaction->save();

                                   $questionnaireData = Questionnaire::find($questionnaireId);
                                   Helper::Event($questionnaireId, $sellerId);
                                   Helper::Event($questionnaireId, $vendorId);
                                   $questionnaireData->status = 2;
                                   $questionnaireData->save(); 

                                 /*   $CustomeNotification = new Notification;
                                   $CustomeNotification->customerId = $CustomerId;
                                   $CustomeNotification->notification = 'Payment Success';
                                   $CustomeNotification->type = 'customer';
                                   $CustomeNotification->save(); */
                                   if(empty($customerStripeId)){
                                    $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
                                    $stripeIdData =  $stripe->customers->create([
                                      'name' => $fullName,
                                     'email' =>  $customerEmail,
                                   ]); 
                                     $CustomerData->stripeId = $stripeIdData['id'];
                                     $CustomerData->save();
                                 }

                                   if(!empty($milestoneId)){
                                    $Milestones =  Milestone::find($milestoneId);
                                    $Milestones->status = 0;
                                    $Milestones->save();
                                   }

                                   $sellerNotification = new Notification;
                                   $sellerNotification->sellerId = $sellerId;
                                   $sellerNotification->notification = 'Customer '.$fullName.' made a payment of $'.$totalPrice.' for '.$eventName.'. You can start event.';
                                   $sellerNotification->type = 'seller';
                                   $sellerNotification->urlType = 'event';
                                   $sellerNotification->questionnaireId = $questionnaireId;
                                   $sellerNotification->fromId = $CustomerId;
                                   $sellerNotification->toId = $sellerId;
                                   $sellerNotification->sendType = 'customer';
                                   $sellerNotification->save();
                                   Socket::notification($userId=$sellerId,$userType='seller');
                                   if(!empty($adminMobile)){
                                    $mobile = $adminMobile;
                                    $msg1 = str_replace("{eventId}",$tokenId,Config::get('msg.payment'));
                                      $msg2 = str_replace("{amount}",$totalPrice,$msg1);
                                      $msg = str_replace("{transactinId}",$TransactionId,$msg2);  
                                    Helper::sendMessage($msg,$mobile);
                                  }


                                  if(!empty($customerMobile)){
                                    $mobile = $customerMobile;
                                    $msg1 = str_replace("{eventId}",$tokenId,Config::get('msg.payment'));
                                      $msg2 = str_replace("{amount}",$totalPrice,$msg1);
                                      $msg = str_replace("{transactinId}",$TransactionId,$msg2);
                                    Helper::sendMessage($msg,$mobile);
                                  }
                                   if(!empty($vendorId)){
                                      $vendorNotification = new Notification;
                                      $vendorNotification->sellerId = $vendorId;
                                      $vendorNotification->notification = 'Customer '.$fullName.' made a payment of $'.$totalPrice.' for '.$eventName.'. You can start event.';
                                      $vendorNotification->type = 'seller';
                                      $vendorNotification->urlType = 'event';
                                      $vendorNotification->questionnaireId = $questionnaireId;
                                      $vendorNotification->fromId = $CustomerId;
                                      $vendorNotification->toId = $vendorId;
                                      $vendorNotification->sendType = 'customer';
                                      $vendorNotification->save();
                                      Socket::notification($userId=$vendorId,$userType='seller');
                                   }

                                   $adminNotification = new Notification;
                                   $adminNotification->customerId = $CustomerId;
                                   $adminNotification->notification = 'Customer '.$fullName.' made a payment of $'.$totalPrice.' for '.$eventName.'';
                                   $adminNotification->type = 'admin';
                                   $adminNotification->urlType = 'event';
                                   $adminNotification->questionnaireId = $questionnaireId;
                                 
                                   $adminNotification->fromId = $CustomerId;
                                   $adminNotification->toId = $adminId;
                                   $adminNotification->sendType = 'customer';
                                   $adminNotification->save();
                                   Socket::notification($userId=$adminId,$userType='admin');

                                   $offerData->status = 1;
                                   $offerData->save();
                                   
                                   $array = array(
                                     'transactionId' =>  $transaction->transactionId,
                                    );
                                  
                                    if($offerType == 2){
                                       $ids = explode(',',$cartId);
                                       \DB::table('carts')->whereIn('id', $ids)->update(['status' => 2,'addressId'=>$addressId]);
                                    }

                                  

                                $transaction = Transaction::select('transactions.*','countries.name as country')
                                ->leftjoin('customer_address','customer_address.id','transactions.addressId')
                                ->leftjoin('countries','countries.id','customer_address.country')
                                                           // ->leftjoin('plannerplans','plannerplans.id','transactions.planId')
                                                            ->where('transactions.id',$transaction->id)
                                                            ->get()->first();

                                                            
                                 $pdfName =  $transaction->transactionId.'.pdf';
                                 try{
                             
                                 $invoiceData =  ['company'=>$AdminSetting,'eventName'=>$eventName,'user'=>$CustomerData,'transaction'=>$transaction,'email' =>$CustomerData['email'],'subject' => 'PayMent Details','pdfName'=> $pdfName,'offer'=>'offer']; 
                                 $pdf = PDF::loadView('pdfTemplate.invoice',$invoiceData);
                                 file_put_contents(public_path().'/'.$pdfName, $pdf->output());
                                 $pdfFolder =  file_get_contents(public_path().'/'.$pdfName);    
                                 Helper::imageUpload($pdfName,$pdfFolder,$folder="customer/customer".$CustomerId);
                                 Helper::sendMailAttachData('pdfTemplate.blank',$invoiceData,$pdf,$pdfName);
                                 $transactionUpdate = Transaction::find($transaction->id);
                                 $transactionUpdate->invoice = $pdfName;
                                 $transactionUpdate->save();
                                 unlink(public_path().'/'.$pdfName)  ;   
                                } catch(\Exception $e) {
                                  
                                }          
                                return response()->json(['data'=>$array,'status'=>true,'message'=>'Payment Success','token'=>''], $this->success);  
                               
                               
                               }else{
                                $transaction = new Transaction;
                                $transaction->addressId = $addressId;
                                $transaction->customerId = $CustomerId;
                                $transaction->questionnaireId = $questionnaireId;
                                $transaction->planId = 0;
                                $transaction->transactionId = $TransactionId;
                                $transaction->sellerId = $sellerId;
                                $transaction->offerId = $offerId;
                                $transaction->token = $token;
                                $transaction->amount = $price;
                                $transaction->vat = $tax;
                                $transaction->totalAmount = $totalPrice;
                                $transaction->status = 2;
                                $transaction->paymentMethod = 'stripe';
                                $transaction->save();


                            /*     $CustomeNotification = new Notification;
                                $CustomeNotification->customerId = $CustomerId;
                                $CustomeNotification->notification = 'Payment Fail';
                                $CustomeNotification->type = 'customer';
                                $CustomeNotification->urlType = 'event';
                                $CustomeNotification->questionnaireId = $questionnaireId;
                                $CustomeNotification->save();
                                Socket::notification($userId=$CustomerId,$userType='customer'); */

                               
                                return response()->json(['data'=>'','status'=>false,'message'=>'something went to wrong.','token'=>''], $this->success); 
                                
                               }
                    
                            } catch(\Stripe\Exception\CardException $e) {
                                // Since it's a decline, \Stripe\Exception\CardException will be caught
                                return response()->json(['data'=>'','status'=>false,'message'=> $e->getError()->message,'token'=>''], $this->success); 
                               
                              } catch (\Stripe\Exception\RateLimitException $e) {
                                return response()->json(['data'=>'','status'=>false,'message'=> $e->getError()->message,'token'=>''], $this->success); 
                              } catch (\Stripe\Exception\InvalidRequestException $e) {
                                return response()->json(['data'=>'','status'=>false,'message'=> $e->getError()->message,'token'=>''], $this->success); 
                              } catch (\Stripe\Exception\AuthenticationException $e) {
                                return response()->json(['data'=>'','status'=>false,'message'=> $e->getError()->message,'token'=>''], $this->success); 
                              } catch (\Stripe\Exception\ApiConnectionException $e) {
                                return response()->json(['data'=>'','status'=>false,'message'=> $e->getError()->message,'token'=>''], $this->success); 
                              } catch (\Stripe\Exception\ApiErrorException $e) {
                                return response()->json(['data'=>'','status'=>false,'message'=> $e->getError()->message,'token'=>''], $this->success); 
                              } catch (Exception $e) {
                              return response()->json(['data'=>'','status'=>false,'message'=> $e->getError()->message,'token'=>''], $this->success); 
                              }
                        }
                            }else{
                                return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Payment Token','token'=>''], $this->success);  
                            }
                        
                     
                       // }else{
                         //   return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Transaction Id','token'=>''], $this->success);  
                         //  }
              }else{
                return response()->json(['data'=>'','status'=>false,'message'=>'Invalid  Id','token'=>''], $this->success);  
              }
           
        }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Customer Token','token'=>''], $this->error);  
        }
    }



   /*  public function productPayment(Request $req){
        $imageUrl = Helper::getUrl();
        $token = $req->bearerToken();
        $customerId =  Helper::encode_token($token);
        $customerData =  Customer::where('id',$customerId)->get()->first();
        $name = !empty($CustomerData->name) ? $CustomerData->name : ''; 
        $surname = !empty($CustomerData->surname) ? $CustomerData->surname : ''; 
        $fullName = $name.' '.$surname;
        $paymentToken = $req->paymentToken;
        if(!empty($customerData)){
                $productId = $req->productId;
                $eventId = $req->eventId;
                $inputQuantity = !empty($req->quantity) ? $req->quantity : 1;
                $superAdmin = Admin::where('type',1)->where('adminType',1)->get()->first();
                $productData = Vendor_product::find($productId);
                $questionnairData = Questionnaire::where('id',$eventId)->where('customerId',$customerId)->get()->first();
                $AdminSetting = AdminSetting::all()->first();
                $eventDay = !empty($AdminSetting->eventDay) ? $AdminSetting->eventDay : 0 ;
                $MessageGroupData = MessageGroup::where('questionnaireId',$eventId)->get()->first();
                if(!empty($questionnairData)){
                    $eventDate = !empty($questionnairData->farEventDate) ? $questionnairData->farEventDate : '';
                    $eventName = !empty($questionnairData->eventName) ? $questionnairData->eventName : '';
                    $eventDateStart  = date('Y-m-d 00:00:01',strtotime($eventDate."-2 day"));
                    $eventDateEnd  = date('Y-m-d 23:59:59',strtotime($eventDate."+2 day"));
                    if(!empty($productData)){
                         $sellerId = !empty($productData->sellerId) ? $productData->sellerId : 0 ;
                         $quantity = !empty($productData->quantity) ? $productData->quantity : 0 ;
                         $regularPrice = !empty($productData->regularPrice) ? $productData->regularPrice : 0 ;
                         $salePrice = !empty($productData->salePrice) ? $productData->salePrice : $regularPrice ;
                         if($inputQuantity <= $quantity){
                              $Transaction = Transaction::where('productId',$productId)->whereBetween('created_at',[$eventDateStart,$eventDateEnd])->where('status',1)->sum('transactions.quantity');
                              
                                if(!empty($Transaction)){
                                    if($Transaction <= $inputQuantity){
                                        if(!empty($paymentToken)){
                                        $validator = Validator::make($req->all(), [ 
                                          'street' => 'required',
                                          'country' => 'required',
                                          'phoneNumber' => 'required',
                                         
                                       ]);
                                       if ($validator->fails()){ 
                                             return response()->json(['data'=>$validator->errors(),'status'=>false,'message'=>$validator->errors()->all(),'token'=>''], $this->success);            
                                       }else{ 
                                         $stripe = Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                                         try{
                                         
                                           $tax = Helper::tax();
                                           $token = $paymentToken;
              
                                           $price = $salePrice;
                                           $totalPrice = $salePrice + $tax;
                                           
                                          if (!isset($token)) {
                                             
                                                 //return redirect()->route('addmoney.paymentstripe');
                                             }
                                             $charge = \Stripe\Charge::create([
                                                 'card' => $token,
                                                 'currency' => env('CURRENCY_TYPE'),
                                                 'amount' =>  $totalPrice*100,
                                                 'description' => 'card',
                                             ]);
                                  
                                             if($charge['status'] == 'succeeded') {
                                                 $transaction = new Transaction;
                                                 $transaction->street = $req->street;
                                                 $transaction->country = $req->country;
                                                 $transaction->phoneNumber = $req->phoneNumber;
                                                 $transaction->customerId = $customerId;
                                                 $transaction->questionnaireId = $eventId;
                                                 $transaction->productId = $productId;
                                                 $transaction->sellerId = $sellerId;
                                                 $transaction->quantity = $quantity;
                                                 $transaction->transactionId = "TP".rand().Str::random(6);
                                                 $transaction->stripeTransactionId = $charge['balance_transaction'];
                                                 $transaction->token = $token;
                                                 $transaction->amount = $price;
                                                 $transaction->vat = $tax;
                                                 $transaction->totalAmount = $totalPrice;
                                                 $transaction->json =  $charge;
                                                 $transaction->status = 1;
                                                 $transaction->paymentMethod = 'stripe';
                                                 $transaction->save();
                                            
              
              
                                                 $sellerNotification = new Notification;
                                                 $sellerNotification->sellerId = $sellerId;
                                                 $sellerNotification->notification = 'Customer '.$fullName.' made a payment of $'.$totalPrice.' for '.$eventName.'. You can start event.';
                                                 $sellerNotification->type = 'seller';
                                                 $sellerNotification->urlType = 'event';
                                                 $sellerNotification->questionnaireId = $eventId;
                                                 $sellerNotification->save();
              
                                                 $adminNotification = new Notification;
                                                 $adminNotification->customerId = $CustomerId;
                                                 $adminNotification->notification = 'Customer '.$fullName.' made a payment of $'.$totalPrice.' for '.$eventName.'';
                                                 $adminNotification->type = 'admin';
                                                 $adminNotification->urlType = 'event';
                                                 $adminNotification->questionnaireId = $eventId;
                                                 $adminNotification->save();
                                                
              
                                                 
                                                 if(empty($MessageGroupData)){
                                                          $MessageGroup = new MessageGroup;
                                                          $MessageGroup->customerId = $customerId;
                                                          $MessageGroup->sellerId = $sellerId;
                                                          $MessageGroup->adminId = $superAdmin->id;
                                                          $MessageGroup->questionnaireId = $eventId;
                                                          $MessageGroup->type = 1;
                                                          $MessageGroup->save();
                                                   }
                                                 
                                                 $array = array(
                                                  'transactionId' =>  $transaction->transactionId,
                                                  'groupId' => !empty($MessageGroup->id) ? $MessageGroup->id : $MessageGroupData->id,
                                               );
                                              return response()->json(['data'=>$array,'status'=>true,'message'=>'Payment Success','token'=>''], $this->success);  
                        
                                             
                                             }else{
                                                $transaction = new Transaction;
                                                $transaction->street = $req->street;
                                                $transaction->country = $req->country;
                                                $transaction->phoneNumber = $req->phoneNumber;
                                                $transaction->customerId = $customerId;
                                                $transaction->questionnaireId = $eventId;
                                                $transaction->productId = $productId;
                                                $transaction->quantity = $quantity;
                                                $transaction->sellerId = $sellerId;
                                                $transaction->transactionId = "TP".rand().Str::random(6);
                                             //   $transaction->stripeTransactionId = $charge['balance_transaction'];
                                                $transaction->token = $token;
                                                $transaction->amount = $price;
                                                $transaction->vat = $tax;
                                                $transaction->totalAmount = $totalPrice;
                                                $transaction->paymentMethod = 'stripe';
                                              //  $transaction->json =  $charge;
                                                $transaction->status = 2;
                                                $transaction->save();
              
              
                                              $CustomeNotification = new Notification;
                                              $CustomeNotification->customerId = $customerId;
                                              $CustomeNotification->notification = 'Payment Fail';
                                              $CustomeNotification->type = 'customer';
                                              $CustomeNotification->urlType = 'event';
                                              $CustomeNotification->save();
                                              return response()->json(['data'=>'','status'=>false,'message'=>'something went to wrong.','token'=>''], $this->success); 
                                              
                                             }
                                  
                                         }catch (RateLimit $e) {
                                          $body = $e->getJsonBody();
                                          return ['status' => 'failed', 'res' => $body['error']];
                                          } catch (InvalidRequest $e) {
                                              $body = $e->getJsonBody();
                                              return ['status' => 'failed', 'res' => $body['error']];
                                          } catch (Authentication $e) {
                                              $body = $e->getJsonBody();
                                              return ['status' => 'failed', 'res' => $body['error']];
                                          } catch (ApiConnection $e) {
                                              $body = $e->getJsonBody();
                                              return ['status' => 'failed', 'res' => $body['error']];
                                          } catch (Base $e) {
                                              $body = $e->getJsonBody();
                                              return ['status' => 'failed', 'res' => $body['error']];
                                          } catch (Exception $e) {
                                              return ['status' => 'failed', 'res' => json_encode($e->getMessage())];
                                          }
                                      }
                                          }else{
                                              return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Payment Token','token'=>''], $this->success);  
                                          }
                                        return response()->json(['data'=>'','status'=>true,'message'=>'success','token'=>''], $this->success); 
                                    }else{
                                        return response()->json(['data'=>'','status'=>false,'message'=>'Quantity Not Sufficient','token'=>''], $this->success); 
                                    }
                                }else{
                                    if(!empty($paymentToken)){
                                        $validator = Validator::make($req->all(), [ 
                                          'street' => 'required',
                                          'country' => 'required',
                                          'phoneNumber' => 'required',
                                         
                                       ]);
                                       if ($validator->fails()){ 
                                             return response()->json(['data'=>$validator->errors(),'status'=>false,'message'=>$validator->errors()->all(),'token'=>''], $this->success);            
                                       }else{ 
                                         $stripe = Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                                         try{
                                         
                                           $tax = Helper::tax();
                                           $token = $paymentToken;
              
                                           $price = $salePrice;
                                           $totalPrice = $salePrice + $tax;
                                           
                                          if (!isset($token)) {
                                             
                                                 //return redirect()->route('addmoney.paymentstripe');
                                             }
                                             $charge = \Stripe\Charge::create([
                                                 'card' => $token,
                                                 'currency' => env('CURRENCY_TYPE'),
                                                 'amount' =>  $totalPrice*100,
                                                 'description' => 'card',
                                             ]);
                                  
                                             if($charge['status'] == 'succeeded') {
                                                 $transaction = new Transaction;
                                                 $transaction->street = $req->street;
                                                 $transaction->country = $req->country;
                                                 $transaction->phoneNumber = $req->phoneNumber;
                                                 $transaction->customerId = $customerId;
                                                 $transaction->questionnaireId = $eventId;
                                                 $transaction->productId = $productId;
                                                 $transaction->sellerId = $sellerId;
                                                 $transaction->quantity = $quantity;
                                                 $transaction->transactionId = "TP".rand().Str::random(6);
                                                 $transaction->stripeTransactionId = $charge['balance_transaction'];
                                                 $transaction->token = $token;
                                                 $transaction->amount = $price;
                                                 $transaction->vat = $tax;
                                                 $transaction->totalAmount = $totalPrice;
                                                 $transaction->json =  $charge;
                                                 $transaction->status = 1;
                                                 $transaction->save();
                                            
              
              
                                                 $sellerNotification = new Notification;
                                                 $sellerNotification->sellerId = $sellerId;
                                                 $sellerNotification->notification = 'Customer '.$fullName.' made a payment of $'.$totalPrice.' for '.$eventName.'. You can start event.';
                                                 $sellerNotification->type = 'seller';
                                                 $sellerNotification->urlType = 'event';
                                                 $sellerNotification->questionnaireId = $eventId;
                                                 $sellerNotification->save();
              
                                                 $adminNotification = new Notification;
                                                 $adminNotification->customerId = $customerId;
                                                 $adminNotification->notification = 'Customer '.$fullName.' made a payment of $'.$totalPrice.' for '.$eventName.'';
                                                 $adminNotification->type = 'admin';
                                                 $adminNotification->urlType = 'event';
                                                 $adminNotification->questionnaireId = $eventId;
                                                 $adminNotification->save();
                                                
              
                                                 
                                                 if(empty($MessageGroupData)){
                                                          $MessageGroup = new MessageGroup;
                                                          $MessageGroup->customerId = $customerId;
                                                          $MessageGroup->sellerId = $sellerId;
                                                          $MessageGroup->adminId = $superAdmin->id;
                                                          $MessageGroup->questionnaireId = $eventId;
                                                          $MessageGroup->type = 1;
                                                          $MessageGroup->save();
                                                 }
                                                 
                                                 $array = array(
                                                  'transactionId' =>  $transaction->transactionId,
                                                  'groupId' => !empty($MessageGroup->id) ? $MessageGroup->id : $MessageGroupData->id,
                                               );
                                              return response()->json(['data'=>$array,'status'=>true,'message'=>'Payment Success','token'=>''], $this->success);  
                        
                                             
                                             }else{
                                                $transaction = new Transaction;
                                                $transaction->street = $req->street;
                                                $transaction->country = $req->country;
                                                $transaction->phoneNumber = $req->phoneNumber;
                                                $transaction->customerId = $customerId;
                                                $transaction->questionnaireId = $eventId;
                                                $transaction->productId = $productId;
                                                $transaction->quantity = $quantity;
                                                $transaction->sellerId = $sellerId;
                                                $transaction->transactionId = "TP".rand().Str::random(6);
                                             //   $transaction->stripeTransactionId = $charge['balance_transaction'];
                                                $transaction->token = $token;
                                                $transaction->amount = $price;
                                                $transaction->vat = $tax;
                                                $transaction->totalAmount = $totalPrice;
                                              //  $transaction->json =  $charge;
                                                $transaction->status = 2;
                                                $transaction->save();
              
              
                                              $CustomeNotification = new Notification;
                                              $CustomeNotification->customerId = $customerId;
                                              $CustomeNotification->notification = 'Payment Fail';
                                              $CustomeNotification->type = 'customer';
                                              $CustomeNotification->urlType = 'event';
                                              $CustomeNotification->questionnaireId = $eventId;
                                              $CustomeNotification->save();
                                              return response()->json(['data'=>'','status'=>false,'message'=>'something went to wrong.','token'=>''], $this->success); 
                                              
                                             }
                                  
                                         }catch (RateLimit $e) {
                                          $body = $e->getJsonBody();
                                          return ['status' => 'failed', 'res' => $body['error']];
                                          } catch (InvalidRequest $e) {
                                              $body = $e->getJsonBody();
                                              return ['status' => 'failed', 'res' => $body['error']];
                                          } catch (Authentication $e) {
                                              $body = $e->getJsonBody();
                                              return ['status' => 'failed', 'res' => $body['error']];
                                          } catch (ApiConnection $e) {
                                              $body = $e->getJsonBody();
                                              return ['status' => 'failed', 'res' => $body['error']];
                                          } catch (Base $e) {
                                              $body = $e->getJsonBody();
                                              return ['status' => 'failed', 'res' => $body['error']];
                                          } catch (Exception $e) {
                                              return ['status' => 'failed', 'res' => json_encode($e->getMessage())];
                                          }
                                      }
                                          }else{
                                              return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Payment Token','token'=>''], $this->success);  
                                          }
                                      
                                 
                                    return response()->json(['data'=>'','status'=>true,'message'=>'success','token'=>''], $this->success); 
                                }
                           }else{
                            return response()->json(['data'=>'','status'=>false,'message'=>'Quantity Not Sufficient','token'=>''], $this->success); 
                         }
                    }else {
                        return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Product Id','token'=>''], $this->success); 
                    }
                }else {
                    return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Event Id','token'=>''], $this->success); 
                }
       }else{
            return response()->json(['data'=>'','status'=>false,'message'=>'Invalid Customer Token','token'=>''], $this->success); 
       } 
    } */
}
