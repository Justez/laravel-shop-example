@extends('layouts.master')
@section('title', 'OrdersBTC')

@section('content')
<div class="container" style="width:60%">
        <h1>BTC Orders: {{$amount}}</h1>
        <table class="table">
            <tbody>
            <tr>
                <td><b>Description</b></td>
                <td><b>Date</b></td>
                <td><b>Amount</b></td>
                <td><b>Total</b></td>
                <td><b>BTC</b></td>
                <td><b>Status</b></td>
            </tr>
            @foreach($orders as $order_item)
            <tr>
                <td><a href="/order/{{$order_item['id']}}">{{@substr($order_item['description'],0,45)}}...</a></td>
                <td>{{$order_item['created_at']}}</td>
                <td>{{$order_item['amount']}}</td>
                <td>{{$order_item['total']}} {{$order_item['currency']}}</td>
                <td>amount in btc</td>
                <td>{{$order_item['status']}}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
        <a class="btn" href="/">Back to shopping</a>
    </div>
@endsection
