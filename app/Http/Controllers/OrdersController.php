<?php

namespace App\Http\Controllers;

use Session;
use App\Order;
use Coingate\Coingate;
use Illuminate\Http\Request;

class OrdersController extends Controller
{

    public function show($id)
    {
      $order = Order::find($id);
      dd($order);
    }

    public function getIndex()
    {
        $orders = Order::where('id','=',Auth::user()->id)->get();
        $amount = count($orders);
        return view('products/products',compact('products','amount'));
    }


}
