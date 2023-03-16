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
                 CURLOPT_URL => \config('paypal.sandbox.api_base').'/v1/oauth2/token',
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
        $rate = $this->currencyConvert();
        try{
            $access_token = $this->paypal_auth();
            $cart_for_paypal=[];
            $total_price=0;
            foreach($request as $item){
                $total_price+=intval($item['price']);
                $cart_for_paypal[]=[
                    'name'=>$item['title'],
                    'price'=>round($rate*$item['price'],1),
                    'quantity'=>'1',
                    'unit_amount'=>[
                        "currency_code"=> 'USD',
                        "value"=>round($rate*$item['price'],1),
                        ],
                ];
            }
            $total_price= round($rate *$total_price,1);
            $amount=[
                "currency_code"=> 'USD',
                "value"=> $total_price,
                "breakdown"=> [
                    "item_total"=> [
                        "currency_code"=> 'USD',
                        "value"=> $total_price
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
                CURLOPT_URL => \config('paypal.sandbox.api_base').'/v2/checkout/orders',
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
        $orders = UserArticle::where('order_id',$token)->get();
        $user=User::find($orders[0]->user_id);
        $access_token = $this->paypal_auth();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, \config('paypal.sandbox.api_base').'/v2/checkout/orders/'.$token.'/capture');
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
            foreach( $orders as  $order){
                $order->order_status = 'completed';
                $order->save();
                $user->articles()->where('order_id',$token)->first();
                event(new BuyArticle($order->article_id, $user));
            }
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
        $rate = $this->currencyConvert();
        try{
            $access_token = $this->paypal_auth();
            $cart_for_paypal=[];
            $price=($request->role=='author' ?  100 : 200);
            $price= round($rate * $price,1);
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
                CURLOPT_URL => \config('paypal.sandbox.api_base').'/v2/checkout/orders',
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
        curl_setopt($ch, CURLOPT_URL, \config('paypal.sandbox.api_base').'/v2/checkout/orders/'.$token.'/capture');
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

    public function currencyConvert()
    {
        $url = 'https://api.exchangerate-api.com/v4/latest/AED';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        $exp = json_decode($output,true);
        curl_close($curl);
        return $exp['rates']['USD'];
    }
}

?>
