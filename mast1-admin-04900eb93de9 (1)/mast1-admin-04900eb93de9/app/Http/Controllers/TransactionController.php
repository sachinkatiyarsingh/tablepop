<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Validator;
use DB;
use Hash;
use Helper;
use Config;
use Str;
use Stripe;
use App\State;
use App\Admin;
use App\Customer;
use App\Countries;
use App\Notification;
use App\MessageGroup;
use App\Transaction;
use App\Questionnaire;
use App\AdminSetting;
use App\StripeAccount;
use Stripe\StripeClient;


class TransactionController extends Controller
{
    public function history(Request $request){
        $start  = $request->start;
        $end =  $request->end;
       $eventId = $request->event;
        $Transaction = Transaction::select('transactions.*',DB::raw('CONCAT(customers.name," ",customers.surname) as name'),'questionnaires.eventName')
                                     ->join('questionnaires','transactions.questionnaireId','questionnaires.id')
                                     ->join('customers','customers.id','transactions.customerId')
                                     ->where(function($q) {
                                      $q->where('transactions.status',1)
                                        ->orWhere('transactions.status',3);
                                  })
                                   ; 
 
         if(!empty($start) && !empty($end) && !empty($eventId)){
             $start = date('Y-m-d 00:00:01',strtotime($start));
             $end = date('Y-m-d 23:59:59',strtotime($end));
      
             $Transactions =   $Transaction->whereBetween('transactions.created_at',[$start,$end])->where('questionnaires.tokenId',$eventId)->latest('id')->paginate(10);
         }else if(!empty($start) && !empty($end)){
             $start = date('Y-m-d 00:00:01',strtotime($start));
             $end = date('Y-m-d 23:59:59',strtotime($end));
             $Transactions =   $Transaction->whereBetween('transactions.created_at',[$start,$end])->latest('id')->paginate(10);
         }else if(!empty($eventId)){
            //  $eventData = Questionnaire::where('tokenId',$eventId)->get()->first();
             // $eventId = !empty($eventData->id) ? $eventData->id : '' ;
             $Transactions =   $Transaction->where('questionnaires.tokenId',$eventId)->latest('id')->paginate(10);
         }else{
         
             $Transactions =   $Transaction->latest('id')->paginate(10);
         }
         $event = Questionnaire::all();
         return view('history.index',['data'=>$Transactions,'event'=>$event]);
    }

    public function paymentRefunded(Request $request){
        $response = '';
        $id = $request->id;
        $TransactionData = Transaction::where('id',$id)->where('status',1)->get()->first();
        if(!empty($TransactionData)){
                $stripeTransactionId =  !empty($TransactionData->stripeTransactionId) ? $TransactionData->stripeTransactionId : '';
                $transactionId =  !empty($TransactionData->transactionId) ? $TransactionData->transactionId : '';
                $transactionId =  !empty($TransactionData->transactionId) ? $TransactionData->transactionId : '';
                $amount =  !empty($TransactionData->amount) ? $TransactionData->amount : 0;
                $amount =  !empty($TransactionData->amount) ? $TransactionData->amount : 0;
                $vat =  !empty($TransactionData->vat) ? $TransactionData->vat : 0;
                $totalAmount =  !empty($TransactionData->totalAmount) ? $TransactionData->totalAmount : 0;
                $adminsetting = AdminSetting::first();
                $refundFee = !empty($adminsetting->refund) ? $adminsetting->refund : 0 ;
                $refundamount = $totalAmount - ($totalAmount)*($refundFee/100);
                $refundamount = $refundamount - ($refundamount)*($vat/100);
                
                try{

                   $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
                    $refunds =    $stripe->refunds->create([
                    'amount' => round($refundamount),
                    'charge' => $stripeTransactionId,
                  ]);
                  $TransactionData->status = 3;
                  $TransactionData->save();
                  $response  = json_encode(['status'=>true,'msg'=>'Payment successfuly refunded']);
                } catch(\Stripe\Exception\CardException $e) {
                    // Since it's a decline, \Stripe\Exception\CardException will be caught
                    $response  = json_encode(['data'=>'','status'=>false,'msg'=> $e->getError()->message]); 
                  } catch (\Stripe\Exception\RateLimitException $e) {
                    $response  = json_encode(['data'=>'','status'=>false,'msg'=> $e->getError()->message]); 
                  } catch (\Stripe\Exception\InvalidRequestException $e) {
                    $response  = json_encode(['data'=>'','status'=>false,'msg'=> $e->getError()->message]); 
                  } catch (\Stripe\Exception\AuthenticationException $e) {
                    $response  = json_encode(['data'=>'','status'=>false,'msg'=> $e->getError()->message]); 
                  } catch (\Stripe\Exception\ApiConnectionException $e) {
                    $response  = json_encode(['data'=>'','status'=>false,'msg'=> $e->getError()->message]); 
                  } catch (\Stripe\Exception\ApiErrorException $e) {
                    $response  = json_encode(['data'=>'','status'=>false,'msg'=> $e->getError()->message]); 
                  } catch (Exception $e) {
                   $response = response()->json(['data'=>'','status'=>false,'msg'=> $e->getError()->message]); 
                  }
        }else{
            $response  = json_encode(['status'=>false,'msg'=>'In-Valid Transaction Id']);
        }
        echo $response;
    }

    public function getEventList(Request $req){
       $eventData = array();
       $eventName = $req->search;
       $eventData = Questionnaire::select('id','tokenId','eventName')->where('eventName','like', "%$eventName%")->get()->toArray();
        
       return response()->json($eventData);
    }
}
