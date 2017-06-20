@extends('layouts.master')
@section('title', 'Orders')

@section('content')
<div class="container" style="width:60%">
        <h1>Your Orders: {{$amount}}</h1>
        <table class="table">
            <tbody>
            <tr>
                <td><b>Date</b></td>
                <td><b>Amount</b></td>
                <td><b>Total</b></td>
                <td><b>Currency</b></td>
                <td><b>Pay type</b></td>
            </tr>
            @foreach($orders as $order_item)
            <tr>
                <td><a href="/order/{{$order_item['id']}}">{{$order_item['created_at']}}</a></td>
                <td>{{$order_item['amount']}}</td>
                <td>{{$order_item['total']}}</td>
                <td>{{$order_item['currency']}}</td>
                <td>{{$order_item['pay_type']}}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
        <a class="btn" href="/">Back to shopping</a>
    </div>
@endsection
