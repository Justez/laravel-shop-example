<?php

namespace App\Http\Controllers;

use Auth;

use App\Order;
use Coingate\Coingate;
use Illuminate\Http\Request;

class OrdersController extends Controller
{

    public function show()
    {
      //coingate sends order details via post method
      dd($request->isMethod('post'));
      $order = Order::find();
      Session::forget('cart');
      dd("show order");
    }

    public function getIndex()
    {
        $orders = Order::where('id','=',Auth::user()->id)->get();
        $amount = count($orders);
        dd($orders);
        return view('orders/my_orders',compact('orders','amount'));
    }
}
