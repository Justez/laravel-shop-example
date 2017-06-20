@extends('layouts.master')
@section('title', 'Products')

@section('content')
    <div class="container pull-left">
        <div class="span12">
            <div class="row">
                <ul class="thumbnails">
                @foreach($products as $product)
                    <li class="span4">
                    <div class="thumbnail">
                        <div class="img-holder"><img class="img-container" src="{{$product->imgurl}}" alt="{{$product->description}}"></div>
                        <div class="caption">
                            <a href="/product/{{$product->id}}"><h4>{{$product->title}}</h4></a>
                            <p><i>{{$product->salesprice}} {{$product->currency}}</i></p>
                            <form action="/cart/add" name="add_to_cart" method="post" accept-charset="UTF-8">
                                <input type="hidden" name="product" value="{{$product->id}}" />
                                <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                                <input type="hidden" name="currency" value="{{$product->currency}}" />
                                <input type="hidden" name="title" value="{{$product->title}}" />
                                <input type="hidden" name="price" value="{{$product->salesprice}}" />
                                <select name="amount" style="width: 100%;">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                                <p align="center"><button class="btn btn-info btn-block">Add to Cart</button></p>
                            </form>
                        </div>
                    </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    </div>
    @if(count(Session::get('cart'),1)!==0)
        <span class="sidebar pull-right thumbnail ">
            <p>Cart: {{@count(Session::get('cart'))}} products </p>
            <div class="cart-sidebar caption thumbnail">
                <ul>
                @foreach(Session::get('cart') as $item)
                    <li>
                            {{$item['amount']}} <a href="/product/{{$item['product_id']}}">{{$item['title']}}</a>
                            <div class="pull-right">
                                <form action="/cart/remove" name="remove_from_cart" method="post" accept-charset="UTF-8">
                                    <input type="hidden" name="product" value="{{$item['product_id']}}" />
                                    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                                    <p align="right"><button class="btn">x</button></p>
                                </form>
                            </div>
                            <p><i>{{$item['total']}} {{$item['currency']}}</i></p>
                    </li>
                @endforeach
                @if(!Auth::guest())
                    <span class="btn btn-sm btn-success"><a style="color:white" href="/checkout">Checkout</a></span>
                @else
                    <span>Login to checkout</span>
                @endif
                    <span class="pull-right btn"><a style="color:black" href="/cart/delete">Empty cart</a></span>
            </div>

        </div>
        @else
        @endif
@endsection
