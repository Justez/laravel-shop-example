@extends('layouts.header')
@section('title', 'Orders')

@section('content')
<div class="container" style="width:60%">
        <h1>Your Orders</h1>
        <table class="table">
            <tbody>
            <tr>
                <td><b>Date</b></td>
                <td><b>Amount</b></td>
                <td><b>Total</b></td>
                <td><b>Currency</b></td>
                <td><b>Pay type</b></td>
            </tr>
            @foreach(Session::get('order') as $order_item)
            <li>
            <tr>
                <a href="/order/{{$order_item['id']}}">
                    <td>{{$order_item['created_at']}}</td>
                </a>
                <td>{{$order_item['amount']}}</td>
                <td>{{$order_item['total']}}</td>
                <td>{{$order_item['currency']}}</td>
                <td>{{$order_item['pay_type']}}</td>
            </tr>
          </li>
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
        <a class="btn" href="/">Back to shopping</a>
    </div>
@endsection
