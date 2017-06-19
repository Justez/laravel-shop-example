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
    private $app_id = 0;
    private $api_key = "";
    private $api_secret = "";
    private $callback_url = "";
    private $cancel_url = "";
    private $success_url = "";

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

    public function coingateSetter()
    {
        $this->setAppId(318);
        $this->setApiKey("LihOJSxFN1fd9boWjcTtIa");
        $this->setApiSecret("3Hmn0RPcjQOrhSg7KIsvDF8LMtpaqeyx");
        $this->setCallbackUrl("http://justina.cgtest.eu/laravelshop/coinGateCallback.php");
        $this->setCancelUrl("http://justina.cgtest.eu/laravelshop/checkout.php");
        $this->setSuccessUrl("http://justina.cgtest.eu/laravelshop/order.php");
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
                $this->coingateSetter();
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
                    'callback_url'      => $this->callback_url,
                    'cancel_url'        => $this->cancel_url,
                    'success_url'       => $this->success_url
                );

                $response = api_request('https://api-sandbox.coingate.com/v1/orders', 'POST', $post_params);
                dd($response);
                //check if the order is Valid
                //{
                //$this->saveOrder("BTC");

                return view('orders/order',compact('order'));
                //return view('orders/order',compact('order'));
                //} else {Redirect::route('/')->with('error','Unknown error. Please try again a bit later.');}
                //return view('cart/checkout',compact('cart_products','cart_total'));
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

    public function getAppId()
    {
        return $this->$app_id;
    }

    public function getApiKey()
    {
        return $this->$api_key;
    }

    public function getApiSecret()
    {
        return $this->$api_secret;
    }

    public function getCallbackUrl()
    {
        return $this->$callback_url;
    }

    public function getCancelUrl()
    {
        return $this->$cancel_url;
    }

    public function getSuccessUrl()
    {
        return $this->$success_url;
    }

    public function setAppId($i)
    {
        $this->app_id = $i;
    }

    public function setApiKey($i)
    {
        $this->api_key = $i;
    }

    public function setApiSecret($i)
    {
        $this->api_secret = $i;
    }

    public function setCallbackUrl($i)
    {
        $this->callback_url = $i;
    }

    public function setCancelUrl($i)
    {
        $this->cancel_url = $i;
    }

    public function setSuccessUrl($i)
    {
        $this->success_url = $i;
    }
}
