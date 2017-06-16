@extends('layouts.header')
@section('title', 'Cart')
@section('sidebar')
    @parent
    <!--show cart components-->
@endsection
@section('content')
<div class="container" style="width:60%">
        <h1>Cart</h1>
        <table class="table">
            <tbody>
            <tr>
                <td><b>Product</b></td>
                <td><b>Amount</b></td>
                <td><b>Price</b></td>
                <td><b>Total</b></td>
                <td><b>Currency</b></td>
                <td><b>Delete</b></td>
            </tr>
            @$cart_total=0
            @$cart_currency='EUR'
            @foreach($products as $cart_item)
                @$cart_total+={{$cart_item->total}}
                <tr>
                    <td>{{$cart_item->Products->title}}</td>
                    <td>{{$cart_item->amount}}</td>
                    <td>{{$cart_item->Products->salesprice}}</td>
                    <td>{{$cart_item->total}}</td>
                    <td>{{$cart_item->currency}}</td>
                    <td>
                    <a href="{{URL::route('delete_product_from_cart',array($cart_item->id))}}">Delete</a>
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
                    <b>@$cart_total @$cart_currency</b>
                </td>
                <td>
                </td>
            </tr>
            </tbody>
        </table>
        <h1>Shipping</h1>
        <form action="/order" method="post" accept-charset="UTF-8">
            <label>Address</label>
            <textarea class="span4" name="address" rows="5"></textarea>
            <button class="btn btn-block btn-primary btn-large">Place order</button>
        </form>
    </div>
@stop
