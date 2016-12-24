@extends('partials.app')

@section('header')
    <div style="margin-top: 120px;"></div>
@endsection

@section('content')
    <h2>Thanks, Your order has been transferred to restaurant. Order No: {{ $order->id }}</h2>
@endsection