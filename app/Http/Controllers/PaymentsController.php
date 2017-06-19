<?php

namespace App\Http\Controllers;

use Session;
use App\Order;
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

    public function paymentVefify()
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
        $this->setSuccessUrl("http://justina.cgtest.eu/laravelshop/success.php");
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

                //dd($user_id);
                //check if the order is Valid
                //{
                //$this->saveOrder("BTC");
                return Redirect::route('/order/'.$this->saveOrder("BTC"));
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
                'amount'=>array_sum(array_column(Session::get('cart'),'amount')),
                'total'=>array_sum(array_column(Session::get('cart'),'total')),
                'currency'=>array_column(Session::get('cart'),'currency')[0],
                'pay_type'=>$payType,
                'token'=>Session::get('_token')
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
