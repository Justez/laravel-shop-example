<?php

namespace App\Http\Controllers;

use Auth;
use Session;
use App\Product;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;

class CartController extends Controller
{
    public function postAddToCart()
    {
            $input = Input::all();
            $data['product_id'] = Input::get('product');
            $data['title'] = Input::get('title');
            $data['amount'] = Input::get('amount');
            $data['price']=Input::get('price');
            $data['total'] = $data['amount']*$data['price'];
            $data['currency'] = Input::get('currency');

            $array = Session::get('cart');
            if ($array==null) {
                Session::push('cart', $data);
            } elseif (array_search($data['product_id'], array_column($array, 'product_id'))!==false) { //the product is already in cart
                $key = array_search($data['product_id'], array_column($array, 'product_id'));
                $array[$key]['amount']+=$data['amount'];
                $array[$key]['total']+=$data['amount']*$data['price'];
                Session::forget('cart');
                Session::put('cart', $array);
            } elseif (array_search($data['product_id'], array_column($array, 'product_id'))==false) {
                Session::push('cart', $data);
            }
            return back();
    }

    public function checkout()
    {
        if (!Auth::guest()) {
            $user_id = Auth::user()->id;
            $cart_products=Session::get('cart');
            $cart_total=array_sum(array_column($cart_products,'total'));
            if (!$cart_products) {
                return Redirect::route('/')->with('error','Your cart is empty');
            }
            return view('cart/checkout',compact('cart_products','cart_total'));
        } else return redirect('/login');
    }

    public function removeItem()
    {
            $input = Input::all();
            $data['product_id'] = Input::get('product');
            $array = Session::get('cart');
            $key = array_search($data['product_id'], array_column($array, 'product_id'));
            if ($array[$key]['amount']==1){
                unset($array[$key]);
            } else {
                $array[$key]['amount']--;
                $array[$key]['total'] = $array[$key]['amount']*$array[$key]['price'];
            }
            Session::forget('cart');
            Session::put('cart', $array);
            return back();
    }

    public function emptyCart()
    {
        Session::forget('cart');
        return Redirect::back();
    }
}
