@extends('layouts.master')
@section('title', 'OrderBTC')

@section('content')
<div class="container" style="width:60%">
        <h1>Order</h1>
        <table class="table">
            <tbody>
            <tr>
                <td><b>Name</b></td>
                <td><b>Amount</b></td>
                <td><b>Price</b></td>
                <td><b>Total</b></td>
                <td><b>Currency</b></td>
            </tr>
              @foreach($orderLines as $orderLine)
                <tr>
                    <td>{{$productNames[++$int]}}</td>
                    <td>{{$orderLine->amount}}</td>
                    <td>{{$orderLine->price}}</td>
                    <td>{{$orderLine->total}}</td>
                    <td>{{$orderLine->currency}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <a class="btn" href="/">Back to shopping</a>
    </div>
@stop
