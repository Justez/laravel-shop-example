<?php

namespace App\Http\Controllers;

use Session;
use App\Order;
use App\Product;
use App\OrderLine;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class PaymentsController extends Controller
{
    public function paymentVerify()
    {
        switch (Input::get('method')) {
            case "BTC":
                $this->createCoingateOrder();
                break;
            case "Paypal":
                $this->payPaypal();
                break;
            case "Cash":
                $this->payCash();
                break;
            case "Card":
                $this->payCard();
                break;
        }
    }

    public function createCoingateOrder()
    {
        if (!Auth::guest()) {
            $user_id = Auth::user()->id;
            $cart_products=Session::get('cart');
            if (!$cart_products) {
                return Redirect::route('/')->with('error','Your cart is empty');
            } else {
                $cart_total=array_sum(array_column($cart_products,'total'));
                $createdOrderId = $this->saveOrder("BTC");
                $currency = Order::where('id',$createdOrderId)->get()[0]['currency'];
                $description = Order::where('id',$createdOrderId)->get()[0]['description'];
                $post_params = array(
                    'order_id'          => (string)$createdOrderId,
                    'price'             => $cart_total,
                    'currency'          => $currency,
                    'receive_currency'  => 'BTC',
                    'title'             => (string)$createdOrderId,
                    'description'       => substr($description,0,(strlen($description) > 500) ? 500 : strlen($description)),
                    'callback_url'      => "http://localhost:8001/cgcallback",
                    'cancel_url'        => "http://localhost:8001/checkout",
                    'success_url'       => "http://localhost:8001/order"
                );
                $response = $this->api_request('https://api-sandbox.coingate.com/v1/orders', 'POST', $post_params);
                if ($response['status_code']==200) {
                    $redirect = substr($response['response_body'], strpos($response['response_body'], 'ent_url":"')+10,-2);
                    header("Location: ".$redirect);
                    exit();
                } elseif ($response['status_code']==401) {
                    Redirect::back()->with('error','Configuration error. Please contact to site administrator about this problem.');
                } elseif ($response['status_code']==404) {
                    Redirect::back()->with('error','Please try again.');
                } elseif ($response['status_code']==500) {
                    Redirect::rback()->with('error','Payment with bitcoins is temporary unavailable.');
                } elseif ($response['status_code']==429) {
                    Redirect::route('/')->with('error','Unknown error. Please try again a bit later or choose another payment type.');
                } else {
                    Redirect::route('/')->with('error','Unknown error. Please try again a bit later.');
                }
            }
        } else {
          return Redirect::route('/login')->with('error','You must login to purchase');
        }
    }

    public function saveOrder($payType)
    {
        $id = Order::insertGetId(
            array(
                'user_id'=>Auth::user()->id,
                'description'=>implode(array_column(Session::get('cart'),'title'),', '),
                'amount'=>array_sum(array_column(Session::get('cart'),'amount')),
                'total'=>array_sum(array_column(Session::get('cart'),'total')),
                'currency'=>array_column(Session::get('cart'),'currency')[0],
                'pay_type'=>$payType,
                'token'=>Session::get('_token'),
                'created_at'=>\Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ));
        foreach (Session::get('cart') as $item) {
            OrderLine::insert(
                array(
                    'order_id'=>$id,
                    'product_id'=>$item['product_id'],
                    'amount'=>$item['amount'],
                    'price'=>$item['price'],
                    'total'=>$item['total'],
                    'currency'=>$item['currency']
                ));
        }
        return $id;
    }

    private function api_request($url, $method = 'GET', $params = array())
    {
        define('APP_ID', 318);
        define('API_KEY', 'LihOJSxFN1fd9boWjcTtIa');
        define('API_SECRET', '3Hmn0RPcjQOrhSg7KIsvDF8LMtpaqeyx');

        $nonce      = time();
        $message    = $nonce . APP_ID . API_KEY;
        $signature  = hash_hmac('sha256', $message, API_SECRET);

        $headers = array();
        $headers[] = 'Access-Key: ' . API_KEY;
        $headers[] = 'Access-Nonce: ' . $nonce;
        $headers[] = 'Access-Signature: ' . $signature;

        $curl = curl_init();

        $curl_options = array(
            CURLOPT_RETURNTRANSFER  => 1,
            CURLOPT_URL             => $url
        );

        if ($method == 'POST') {
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';

            array_merge($curl_options, array(CURLOPT_POST => 1));
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
        }

        curl_setopt_array($curl, $curl_options);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response       = curl_exec($curl);
        $http_status    = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        return array('status_code' => $http_status, 'response_body' => $response);
    }

    public function callback()
    {

    }

    public function payPaypal()
    {
        $this->createCoingateOrder();
        return view('payments/payPaypal');
    }

    public function payCash()
    {
        $this->createCoingateOrder();
        return view('payments/payCash');
    }

    public function payCard()
    {
        $this->createCoingateOrder();
        return view('payments/payCard');
    }
}
