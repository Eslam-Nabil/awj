<?php

namespace App\Traits;

use Exception;
use App\Models\User;
use App\Events\BuyArticle;
use App\Models\PaypalOrder;
use App\Models\UserArticle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait Paypal {

    public function paypal_auth() {
        header('Content-type: application/json');
       try{
        $user=\config('paypal.sandbox.client_id');
        $password=\config('paypal.sandbox.secret');

           $setopt_content=array(
                 CURLOPT_URL => \config('paypal.sandbox.base').'/v1/oauth2/token',
                 CURLOPT_HEADER => false,
                 CURLOPT_SSL_VERIFYPEER =>false,
                 CURLOPT_MAXREDIRS => 10,
                 CURLOPT_RETURNTRANSFER => true,
                 CURLOPT_USERPWD => $user.":".$password,
                 CURLOPT_FOLLOWLOCATION => true,
                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                 CURLOPT_CUSTOMREQUEST => 'POST',
                 CURLOPT_POSTFIELDS=> "grant_type=client_credentials",
                 CURLOPT_HTTPHEADER => array(
                   'Content-Type: application/json',
                   ),
               );
           $ch = curl_init();
           curl_setopt_array($ch, $setopt_content);
           $first_result_data = json_decode(curl_exec($ch));
           curl_close($ch);
           return $first_result_data->access_token;
        }catch(Exception $e){
             return response()->json(['success' => false,'message'=>$e->getMessage()], 400);
        }

    }

    public function paypal_order_article($request) {
        header('Content-type: application/json');
        try{
            $access_token = $this->paypal_auth();
            $cart_for_paypal=[];
                $cart_for_paypal[]=[
                    'name'=>$request['title'],
                    'price'=>$request['price'],
                    'quantity'=>'1',
                    'unit_amount'=>[
                        "currency_code"=> 'USD',
                        "value"=>$request['price'],
                        ],
                ];
            $amount=[
                "currency_code"=> 'USD',
                "value"=> $request['price'],
                "breakdown"=> [
                    "item_total"=> [
                        "currency_code"=> 'USD',
                        "value"=> $request['price']
                    ]
                ]
            ];
            $body=json_encode(array(
                "intent"=>"CAPTURE",
                "purchase_units" =>  [
                        [
                        "items"=>$cart_for_paypal,
                        "amount"=> $amount
                        ],
                    ],
                "application_context"=> [
                    "return_url"=> route('paypal_capture'),
                    "cancel_url"=> route('paypal_cancel')
                    ]
                ));

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_HEADER => false,
                CURLOPT_URL => \config('paypal.sandbox.base').'/v2/checkout/orders',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS=> $body,
                CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer '.$access_token
                ),
            )
            );
            $result_data = json_decode(curl_exec($curl));
            curl_close($curl);
            return $result_data;
        }catch(Exception $e){
             return response()->json(['success' => false,'message'=>$e->getMessage()], 400);
        }
    }

    public function capture_payment_article(Request $request){
        $token = $request->token;
        $order = UserArticle::where('order_id',$token)->first();
        $user=User::find($order->user_id);
        $access_token = $this->paypal_auth();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, \config('paypal.sandbox.base').'/v2/checkout/orders/'.$token.'/capture');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer '.$access_token;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = json_decode(curl_exec($ch));
        if($result->status == 'COMPLETED'){
            $data=[
                'order_status'=>'completed',
            ];
            $user->articles()->updateExistingPivot($order->article_id,$data);
            event(new BuyArticle($order->article_id, $user));
            return redirect()->away(env('APP_URL').'#/bag/success')->with('success', 'order has captured successfully');
            return response()->json(['success' => true,'message'=>'order captured successfully'], 200);
        }else{
            return redirect()->away(env('APP_URL').'#/bag/canceled');
            return response()->json(['success' => true,'message'=>'order captured successfully'], 200);
        }
    }
    
    public function cancel_payment_article(){
        return redirect()->away(env('APP_URL').'/bag/canceled');
    }

    public function paypal_order_register($request) {
        header('Content-type: application/json');
        try{
            $access_token = $this->paypal_auth();
            $cart_for_paypal=[];
            $price=($request->role=='author' ?  100 : 200);
                $cart_for_paypal[]=[
                    'name'=>'register new '.$request->role,
                    'price'=>$price,
                    'quantity'=>'1',
                    'unit_amount'=>[
                        "currency_code"=> 'USD',
                        "value"=>$price,
                        ],
                ];
            $amount=[
                "currency_code"=> 'USD',
                "value"=> $price,
                "breakdown"=> [
                    "item_total"=> [
                        "currency_code"=> 'USD',
                        "value"=> $price
                    ]
                ]
            ];
            $body=json_encode(array(
                "intent"=>"CAPTURE",
                "purchase_units" =>  [
                        [
                        "items"=>$cart_for_paypal,
                        "amount"=> $amount
                        ],
                    ],
                "application_context"=> [
                    "return_url"=> route('paypal_register_capture'),
                    "cancel_url"=> route('paypal_cancel')
                    ]
                ));

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_HEADER => false,
                CURLOPT_URL => \config('paypal.sandbox.base').'/v2/checkout/orders',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS=> $body,
                CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer '.$access_token
                ),
            )
            );
            $result_data = json_decode(curl_exec($curl));
            curl_close($curl);
            return $result_data;
        }catch(Exception $e){
             return response()->json(['success' => false,'message'=>$e->getMessage()], 400);
        }
    }

    public function capture_payment_register(Request $request){
        $token = $request->token;
        $order = PaypalOrder::where('order_id',$token)->first();
        $access_token = $this->paypal_auth();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, \config('paypal.sandbox.base').'/v2/checkout/orders/'.$token.'/capture');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer '.$access_token;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = json_decode(curl_exec($ch));
        if($result->status == 'COMPLETED'){
        $order->order_status = 'completed';
        $order->save();
          
            return redirect()->away(env('APP_URL').'#/bag/success')->with('success', 'order has captured successfully');
            return response()->json(['success' => true,'message'=>'order captured successfully'], 200);
        }else{
            return redirect()->away(env('APP_URL').'#/bag/canceled');
            return response()->json(['success' => true,'message'=>'order captured successfully'], 200);
        }
    }
}
?>