<?php

namespace App\Http\Controllers;

use DB;
use Session;
use App\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{

    public function getIndex()
    {
      $products = Product::where('deleted','=','0')->get();
      return view('products/products',compact('products'));
    }

    public function show($id){
      $product = Product::find($id);
      return Redirect::back();
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
