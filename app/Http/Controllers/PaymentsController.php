<?php

namespace App\Http\Controllers;

use DB;
use Session;
use App\Order;
use App\Product;
use App\OrderLine;
use Illuminate\Http\Request;
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
                    'callback_url'      => "http://demo2.coingate.com/cgcallback",
                    'cancel_url'        => "http://demo2.coingate.com/checkout",
                    'success_url'       => "http://demo2.coingate.com/BTCorders?emptyCart=true"
                );
                $response = \CoinGate\Merchant\Order::create($post_params, array(),array(
                    'environment' => 'sandbox',
                    'app_id' => 318,
                    'api_key' => 'LihOJSxFN1fd9boWjcTtIa',
                    'api_secret' => '3Hmn0RPcjQOrhSg7KIsvDF8LMtpaqeyx'));
                if ($response->status=="pending") {
                    Order::where('id',$createdOrderId)->update(['other' => $response->id]);
                    Session::forget('cart');
                    header("Location: ".$response->payment_url);
                    exit();
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

    public function callback(Request $request)
    {
        if( $request->isMethod('post') ) {
            $data = $request->all();
            $order = Order::find($request->input('order_id'));
            if ($request->input('token') == $order->token) {
                $status = NULL;
                if ($request->input('status') == 'paid' &&
                    $request->input('price') >= $order->total) {
                    $status = 'paid';
                }
                else {
                    $status = $request->input('status');
                }
                if (!is_null($status)) {
                    DB::table('orders')->where('id', $order->id)->update(['status' => $status]);
                }
            }
        } else {
            abort(403, 'You cannot access this page directly.');
        }
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
