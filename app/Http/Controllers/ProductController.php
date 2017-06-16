<?php

namespace App\Http\Controllers;

use Session;
use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function getIndex()
    {
      $products = Product::where('deleted','=','0')->get();
      $amount = 0;
      return view('products/products',compact('products','amount'));
    }

    public function show($id){
      $product = Product::find($id);
      dd($product);
    }

    public function delete($id)
    {
      DB::table('products')
            ->where('id', $id)
            ->update(['deleted' => 1]);
    }

    public function renew($id)
    {
      DB::table('products')
            ->where('id', $id)
            ->update(['deleted' => 0]);
    }

}
