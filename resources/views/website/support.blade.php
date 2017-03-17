@extends('partials.app')

@section('style')
    <style>
        .box{
            padding: 10px 0px;
            border:1px dashed #999;
            border-left: 0px; border-right: 0px;
            border-top: 0;
        }
    </style>
@endsection

@section('content')
    <div style="margin-top: 91px;">
        <div class="box">
            <center><h1>Help Center!</h1></center>
        </div>

        <div class="row" style="margin-top: 20px;">
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel-title"><h3>Frequently Asked Questions</h3></div>
                    </div>
                    <div class="panel-body">
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            @include('website.faqs')
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel-title"><h3>Contact Us</h3></div>
                    </div>
                    <div class="panel-body">
                        <a href="#" onclick="alert('Click the right corner chat icon.')" style="font-weight: bold;"><i class="glyphicon glyphicon-comment"></i> Live Chat</a>
                        <p style="font-weight: bold;"><i class="glyphicon glyphicon-envelope"></i> Email: <a href="mailto:support@tromboy.com" >support@tromboy.com</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection