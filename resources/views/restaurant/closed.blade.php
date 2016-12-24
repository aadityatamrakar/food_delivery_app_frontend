@extends('partials.app')

@section('style')
    @include('restaurant.style')
    <style>
        .onoffswitch {
            position: relative; width: 73px;
            -webkit-user-select:none; -moz-user-select:none; -ms-user-select: none;
        }
        .onoffswitch-checkbox {
            display: none;
        }
        .onoffswitch-label {
            display: block; overflow: hidden; cursor: pointer;
            border: 2px solid #FFFFFF; border-radius: 50px;
        }
        .onoffswitch-inner {
            display: block; width: 200%; margin-left: -100%;
            transition: margin 0.3s ease-in 0s;
        }
        .onoffswitch-inner:before, .onoffswitch-inner:after {
            display: block; float: left; width: 50%; height: 27px; padding: 0; line-height: 27px;
            font-size: 16px; color: white; font-family: Trebuchet, Arial, sans-serif; font-weight: bold;
            box-sizing: border-box;
        }
        .onoffswitch-inner:before {
            content: "Yes";
            padding-left: 10px;
            background-color: #1CBD2A; color: #FFFFFF;
        }
        .onoffswitch-inner:after {
            content: "No";
            padding-right: 10px;
            background-color: #FF0000; color: #FFFFFF;
            text-align: right;
        }
        .onoffswitch-switch {
            display: block; width: 15px; margin: 6px;
            background: #FFFFFF;
            position: absolute; top: 0; bottom: 0;
            right: 42px;
            border: 2px solid #FFFFFF; border-radius: 50px;
            transition: all 0.3s ease-in 0s;
        }
        .onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-inner {
            margin-left: 0;
        }
        .onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-switch {
            right: 0px;
        }
        .nav-pills>li.active>a, .nav-pills>li.active>a:hover, .nav-pills>li.active>a:focus{
            background-color: orangered;
        }
    </style>
@endsection

@section('header_right')
    @include('restaurant.header_right')
@endsection

@section('header')
    <div class="myheader" style="margin-top: 95px;">
        <center><img style="box-shadow: 2px 2px 2px rgba(0,0,0,0.7);" src="http://foodadmin.local/images/restaurant/logo/{{ $restaurant->logo }}" width="100px" height="100px" /></center>
        <center><h1 style="margin: 0px; margin-top: -15px; padding: 0px 0px 15px 0px; color:white; text-shadow: 2px 2px 2px red;">:: Welcome to {{ $restaurant->name }} ::</h1></center>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <h1 class="text-center text-warning">Currently We are closed. <br>Come again later. Thanks for visiting.</h1>
        </div>
    </div>
@endsection

@section('script')
@endsection