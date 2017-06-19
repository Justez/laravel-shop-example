<?php

namespace App\Http\Controllers;

use Auth;
use Session;
use App\Order;
use Coingate\Coingate;
use Illuminate\Http\Request;

class OrdersController extends Controller
{

    public function show($id)
    {
      $order = Order::find($id);
      dd("");
    }

    public function getIndex()
    {
        $orders = Order::where('id','=',Auth::user()->id)->get();
        dd($orders);
        $amount = count($orders);
        dd($orders);
        return view('orders/my_orders',compact('orders','amount'));
    }
}
