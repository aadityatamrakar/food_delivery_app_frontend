@extends('partials.app')

@section('header')
    <div style="margin-top: 120px;"></div>
@endsection

@section('content')
    <div class="jumbotron">
        <center><img src="/img/check.gif" width="100px"/>
        <h1>Order Placed!</h1>
        <p>Thanks, Your order has been transferred to restaurant. <b>Order No: {{ $order->id }}</b></p>
            <p>Kindly contact <b>{{ $order->restaurant->name }}</b> at <b><i class="glyphicon glyphicon-phone"></i> {{ $order->restaurant->contact_no }}</b></p>
        </center>
    </div>
@endsection

@section('script')
<script>
    $(document).ready(function (){
        window.localStorage.removeItem('cart');
    });
</script>
@endsection