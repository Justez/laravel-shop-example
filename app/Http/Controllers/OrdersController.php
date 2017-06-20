<?php

namespace App\Http\Controllers;

use Auth;
use App\Order;
use App\OrderLine;
use Coingate\Coingate;
use Illuminate\Http\Request;

class OrdersController extends Controller
{

    public function getBTCOrders(Request $request)
    {
        $orders = Order::where('user_id','=',Auth::user()->id)->where('pay_type','=','BTC')->orderBy('id', 'DESC')->get();
        $amount = count($orders);
        $btcOrders = array();
        foreach ($orders as $order) {
            $response = $this->api_request('https://api-sandbox.coingate.com/v1/orders/'.$order->other);
            if ($response['status_code']==200) {
                $from = strpos($response['response_body'],'nt":"')+5;
                $to = strpos($response['response_body'],'","creat');
                $btcOrders[$order->id]=substr($response['response_body'],$from,$to-$from);
            }
        }
        return view('orders/ordersBTC',compact('orders','amount','$btcOrders'));
    }

    public function showBTC($id)
    {
      $order = Order::find($id);
      $productNames = explode(', ',$order->description);
      $orderLines = OrderLine::where('order_id',$order->id)->get();
      return view('orders/orderBTC',compact('order','orderLines','productNames'));
    }
    public function show($id)
    {
      $int = -1;
      $order = Order::find($id);
      $productNames = explode(', ',$order->description);
      $orderLines = OrderLine::where('order_id',$order->id)->get();
      return view('orders/order',compact('order','orderLines','productNames','int'));
    }

    public function getIndex()
    {
        $orders = Order::where('user_id','=',Auth::user()->id)->orderBy('id', 'DESC')->get();
        $amount = count($orders);
        return view('orders/orders',compact('orders','amount'));
    }

    function api_request($url, $method = 'GET', $params = array())
    {
        $nonce      = time();
        $message    = $nonce . '318' . 'LihOJSxFN1fd9boWjcTtIa';
        $signature  = hash_hmac('sha256', $message, '3Hmn0RPcjQOrhSg7KIsvDF8LMtpaqeyx');

        $headers = array();
        $headers[] = 'Access-Key: ' . 'LihOJSxFN1fd9boWjcTtIa';
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
}
