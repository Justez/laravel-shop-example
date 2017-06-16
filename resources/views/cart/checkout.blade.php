@extends('layouts.header')

@section('title', 'Checkout')
@section('content')
<div class="container" style="width:60%">
        <h1>Your Cart</h1>
        <table class="table">
            <tbody>
            <tr>
                <td><b>Title</b></td>
                <td><b>Amount</b></td>
                <td><b>Price</b></td>
                <td><b>Total</b></td>
                <td><b>Currency</b></td>
                <td><b>Delete</b></td>
            </tr>
            @foreach(Session::get('cart') as $cart_item)
            <tr>
                <td>{{$cart_item['title']}}</td>
                <td>{{$cart_item['amount']}}</td>
                <td>{{$cart_item['price']}}</td>
                <td>{{$cart_item['total']}}</td>
                <td>{{$cart_item['currency']}}</td>
                <td>
                  <form action="/cart/remove" name="remove_from_cart" method="post" accept-charset="UTF-8">
                      <input type="hidden" name="product" value="{{$cart_item['product_id']}}" />
                      <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                      <b align="right"><button class="btn">x</button></b>
                  </form>
                </td>
            </tr>
            @endforeach
            <tr>
                <td>
                </td>
                <td>
                </td>
                <td>
                    <b>Total</b>
                </td>
                <td>
                    <b>{{$cart_total}}</b>
                </td>
                <td>
                </td>
            </tr>
            </tbody>
        </table>
        <h1>Payment method</h1>
        <form action="/pay" method="post" accept-charset="UTF-8">
            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
            <select name="method" style="width: 100%;">
                <option value="BTC">BTC</option>
                <option value="Cash">Cash</option>
                <option value="Card">Card</option>
                <option value="Paypal">Paypal</option>
            </select>
            <button class="btn btn-block btn-primary btn-large">Place order</button>
        </form>
        <a class="btn" href="/">Back to shopping</a>
    </div>
@endsection
