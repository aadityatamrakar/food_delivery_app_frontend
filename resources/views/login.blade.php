@extends('partials.home_app')

@section('style')
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css" />
    <style>
        @import url(https://fonts.googleapis.com/css?family=Roboto:300,500);

        body {
            padding-top: 120px;
            background:#F7F7F7;
            color:#666666;
            font-family: 'Roboto', sans-serif;
            font-weight:300;
        }

        body{
            width: 100%;
            background: -webkit-linear-gradient(left, #22d686, #24d3d3, #22d686, #24d3d3);
            background: linear-gradient(to right, #22d686, #24d3d3, #22d686, #24d3d3);
            background-size: 600% 100%;
            -webkit-animation: HeroBG 20s ease infinite;
            animation: HeroBG 5s ease infinite;
        }

        @-webkit-keyframes HeroBG {
            0% {
                background-position: 0 0;
            }
            50% {
                background-position: 100% 0;
            }
            100% {
                background-position: 0 0;
            }
        }

        @keyframes HeroBG {
            0% {
                background-position: 0 0;
            }
            50% {
                background-position: 100% 0;
            }
            100% {
                background-position: 0 0;
            }
        }


        .panel {
            border-radius: 5px;
        }
        label {
            font-weight: 300;
        }
        .panel-login {
            border: none;
            -webkit-box-shadow: 0px 0px 49px 14px rgba(188,190,194,0.39);
            -moz-box-shadow: 0px 0px 49px 14px rgba(188,190,194,0.39);
            box-shadow: 0px 0px 49px 14px rgba(188,190,194,0.39);
        }
        .panel-login .checkbox input[type=checkbox]{
            margin-left: 0px;
        }
        .panel-login .checkbox label {
            padding-left: 25px;
            font-weight: 300;
            display: inline-block;
            position: relative;
        }
        .panel-login .checkbox {
            padding-left: 20px;
        }
        .panel-login .checkbox label::before {
            content: "";
            display: inline-block;
            position: absolute;
            width: 17px;
            height: 17px;
            left: 0;
            margin-left: 0px;
            border: 1px solid #cccccc;
            border-radius: 3px;
            background-color: #fff;
            -webkit-transition: border 0.15s ease-in-out, color 0.15s ease-in-out;
            -o-transition: border 0.15s ease-in-out, color 0.15s ease-in-out;
            transition: border 0.15s ease-in-out, color 0.15s ease-in-out;
        }
        .panel-login .checkbox label::after {
            display: inline-block;
            position: absolute;
            width: 16px;
            height: 16px;
            left: 0;
            top: 0;
            margin-left: 0px;
            padding-left: 3px;
            padding-top: 1px;
            font-size: 11px;
            color: #555555;
        }
        .panel-login .checkbox input[type="checkbox"] {
            opacity: 0;
        }
        .panel-login .checkbox input[type="checkbox"]:focus + label::before {
            outline: thin dotted;
            outline: 5px auto -webkit-focus-ring-color;
            outline-offset: -2px;
        }
        .panel-login .checkbox input[type="checkbox"]:checked + label::after {
            font-family: 'FontAwesome';
            content: "\f00c";
        }
        .panel-login>.panel-heading .tabs{
            padding: 0;
        }
        .panel-login h2{
            font-size: 20px;
            font-weight: 300;
            margin: 30px;
        }
        .panel-login>.panel-heading {
            color: #848c9d;
            background-color: #e8e9ec;
            border-color: #fff;
            text-align:center;
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
            border-top-left-radius: 0px;
            border-top-right-radius: 0px;
            border-bottom: 0px;
            padding: 0px 15px;
        }
        .panel-login .form-group {
            padding: 0 30px;
        }
        .panel-login>.panel-heading .login {
            padding: 10px 10px;
            border-bottom-leftt-radius: 5px;
        }
        .panel-login>.panel-heading .register {
            padding: 10px 10px;
            background: #FF5722;
            border-bottom-right-radius: 5px;
        }
        .panel-login>.panel-heading a{
            text-decoration: none;
            color: #666;
            font-weight: 300;
            font-size: 16px;
            -webkit-transition: all 0.1s linear;
            -moz-transition: all 0.1s linear;
            transition: all 0.1s linear;
        }
        .panel-login>.panel-heading a#register-form-link {
            color: #fff;
            width: 100%;
            text-align: right;
        }
        .panel-login>.panel-heading a#login_form-link {
            width: 100%;
            text-align: left;
        }

        .panel-login select, .panel-login input[type="text"],.panel-login input[type="number"], .panel-login input[type="email"],.panel-login input[type="password"] {
            height: 45px;
            border: 0;
            font-size: 12px;
            -webkit-transition: all 0.1s linear;
            -moz-transition: all 0.1s linear;
            transition: all 0.1s linear;
            -webkit-box-shadow: none;
            box-shadow: none;
            border: 1px solid #999;
            border-radius: 0px;
            padding: 6px 6px;
            -webkit-border-radius: 6px;
            -moz-border-radius: 6px;
            border-radius: 6px;
        }
        .panel-login input:hover,
        .panel-login input:focus {
            outline:none;
            -webkit-box-shadow: none;
            -moz-box-shadow: none;
            box-shadow: none;
            border-color: #FF5722;
        }
        .btn-login {
            background-color: #E8E9EC;
            outline: none;
            color: #2D3B55;
            font-size: 14px;
            height: auto;
            font-weight: normal;
            padding: 14px 0;
            text-transform: uppercase;
            border: none;
            border-radius: 0px;
            box-shadow: none;
        }
        .btn-login:hover,
        .btn-login:focus {
            color: #fff;
            background-color: #2D3B55;
        }
        .forgot-password {
            text-decoration: underline;
            color: #888;
        }
        .forgot-password:hover,
        .forgot-password:focus {
            text-decoration: underline;
            color: #666;
        }

        .btn-register {
            background-color: #E8E9EC;
            outline: none;
            color: #2D3B55;
            font-size: 14px;
            height: auto;
            font-weight: normal;
            padding: 14px 0;
            text-transform: uppercase;
            border: none;
            border-radius: 0px;
            box-shadow: none;
        }
        .btn-register:hover,
        .btn-register:focus {
            color: #fff;
            background-color: #FF5722;
        }

    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-login">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <form id="login_form" action="{{ route('login') }}" method="post" role="form" style="display: block;">
                                    <h2 class="text-warning">Already a Member ? Please Login with your details</h2>
                                    <div class="form-group">
                                        <input type="number" name="mobile" id="mobile" data-mobile="yes" class="form-control" placeholder="Mobile No.">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="pin" id="pin" class="form-control" placeholder="PIN">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                    {!! csrf_field() !!}
                                </form>
                                <form id="register-form" onsubmit="return false;" method="post" role="form" style="display: none;">
                                    {!! csrf_field() !!}
                                    <input type="hidden" name="otp_reg" id="otp_reg" class="form-control">
                                    <h2 class="text-warning">Please provide the following details</h2>
                                    <div class="form-group">
                                        <input type="text" name="name" id="name" class="form-control" placeholder="Full Name">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                    <div class="form-group">
                                        <input type="number" name="mobile_reg" id="mobile_reg" data-mobile="yes" class="form-control" placeholder="Mobile No.">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                    <div class="form-group">
                                        <input type="number" onkeyup="if(this.value.length == 4) $('#address').focus();" name="pin_reg" id="pin_reg" data-toggle="tooltip" data-placement="top" title="4 digit PIN" class="form-control" maxlength="4" placeholder="Set 4 Digit Login PIN">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="address" id="address" class="form-control" placeholder="Address ">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="email" name="email" id="email" class="form-control" placeholder="Email">
                                                <span class="help-block text-danger"></span>
                                            </div>
                                            <div class="col-md-6">
                                                <select name="city" id="city" class="form-control">
                                                    <option>CITY</option>
                                                    @foreach(\App\City::all() as $city)
                                                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xs-9">
                                                <input type="checkbox" checked id="tc_check">
                                                I agree to the <a href="{{ route('termsconditions') }}">T&C's</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                Your phone number will be verified using an OTP
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-sm-6 col-sm-offset-3">
                                                <input type="submit" name="register-submit" id="register-submit" class="form-control btn btn-register" value="Register Now">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-6 tabs">
                                <a href="#" class="active" id="login_form-link"><div class="login">Already a Member? Login </div></a>
                            </div>
                            <div class="col-xs-6 tabs">
                                <a href="#" id="register-form-link"><div class="register">Not a member yet? Register Now</div></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="otpmodal" class="modal fade" data-backdrop="static" data-keyboard=false tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Verify Mobile No.</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" name="otp_c" id="otp_c" class="form-control">
                        <span class="help-block text-danger"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="resent_otp">Resend OTP</button>
                    <button type="button" class="btn btn-success" id="verify_button">Verify</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

@endsection

@section('script')
    <script>
        $.fn.extend({
            animateCss: function (animationName) {
                var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
                this.addClass('animated ' + animationName).one(animationEnd, function() {
                    $(this).removeClass('animated ' + animationName);
                });
            }
        });

        $(document).ready(function (){
            $('#mobile').focus();
        });

        $("#tc_check").click(function (e){
            e.preventDefault();
        });

        $(function() {
            $('#login_form-link').click(function(e) {
                $("#login_form").delay(100).fadeIn(100);
                $("#register-form").fadeOut(100);
                $('#register-form-link').removeClass('active');
                $(this).addClass('active');
                e.preventDefault();
            });
            $('#register-form-link').click(function(e) {
                $("#register-form").delay(100).fadeIn(100);
                $("#login_form").fadeOut(100);
                $('#login_form-link').removeClass('active');
                $(this).addClass('active');
                e.preventDefault();
            });
            $('[data-toggle="tooltip"]').tooltip();
        });

        $('input[type="number"][data-mobile="yes"]').on('blur', function (){
            if( $(this).val() != 0 && $(this).val().length != 10) {
                show_error(this, 'Please enter valid 10 digit mobile no.');
            }else{
                $(this).parent().children()[1].innerHTML = '';
            }
        });

        $("#pin").on('keyup', function (){
            if( $('#mobile').val().length==10 && $(this).val().length == 4) {
                $('#login_form').submit();
            }
        });
        $("#mobile").on('keyup', function (){
            if($(this).val().length == 10)
            {
                $("#pin").focus();
            }
        });

        $("#mobile_reg").on('keyup', function (){
            if( $(this).val().length == 10 ) {
                $('#pin_reg').focus();
                var m = $(this).val();
                var t = this;
                $.ajax({
                    url:'{{ route('checkmobile') }}',
                    type: "POST",
                    data: {"_token":"{{ csrf_token() }}", 'mobile': m}
                }).done(function (e) {
                    if (e.status == 'duplicate') show_error(t, "Mobile no. already Registered.");;
                });
            }
        });

        $("#resent_otp").on('click', function (){
            request_otp($("#mobile_reg").val(), function (){
                $('#otp_c').attr('placeholder', 'Resent! Enter OTP Recieved in '+$("#mobile_reg").val());
            });
        })

        function request_otp(m, callback){
            waitingDialog.show('Sending OTP...', {dialogSize: 'sm', progressType: 'primary'});
            $.ajax({
                url: "{{ route('login.get_otp') }}",
                type: "POST",
                data: {"_token":"{{ csrf_token() }}", 'mobile': m}
            }).done(function (e) {
                if (e.status == 'ok') waitingDialog.hide(callback);
            });
        }

        $("#mobile_reg").on('keyup', function(){
            if(this.value.length == 10){
                $("#otp").removeAttr('disabled');
                $("#otp").focus();
            }
        });

        $("#verify_button").click(function (){
            if($("#otp_c").val().length == 4) {
                $("#otp_reg").val($('#otp_c').val());
                $.ajax({
                    url: '{{ route('register') }}',
                    type: 'POST',
                    data: {
                        name: $("#name").val(),
                        address: $("#address").val(),
                        pin: $("#pin_reg").val(),
                        email: $("#email").val(),
                        city: $("#city").val(),
                        mobile: $("#mobile_reg").val(),
                        otp: $("#otp_reg").val(),
                        _token: '{{ csrf_token() }}'
                    }
                }).done(function (e){
                    if(e.status == 'ok') window.location = '/';
                    else if(e.status == 'error' && e.error == 'invalid_otp') show_error($("#otp_c")[0], 'Invalid OTP');
                    else if(e.status == 'error' && e.error== 'duplicate') show_error($("#mobile_reg")[0], 'Mobile no already registered.');
                    else {
                        alert("Something is not working. Kindly retry again.");
                        console.log(JSON.stringify(e.error));
                    }
                });
            }
        });

        $("#register-submit").click(function (e){
            e.preventDefault();
            if($("#name").val().length != 0) {
                if($("#address").val().length != 0) {
                    if($("#mobile_reg").val().length == 10) {
                        if($("#pin_reg").val().length != 0) {
                            if($("#city").val() != 'CITY'){
                                request_otp($("#mobile_reg").val(), function (){
                                    $('#otpmodal').modal('show');
                                    $('#otp_c').attr('placeholder', 'Enter OTP Recieved in '+$("#mobile_reg").val());
                                });
                            }else show_error($("#city")[0], 'Select City');
                        }else show_error($("#pin_reg")[0], 'PIN is required');
                    }else show_error($("#mobile_reg")[0], 'Enter a valid 10 digit mobile no.');
                }else show_error($("#address")[0], 'Address is required.');
            }else show_error($("#name")[0], 'Name is required.');
        });

        function show_error(t, e, a)
        {
            if(a == null) a='shake';
            $(t).animateCss(a);
            $(t).val('');
            $(t).focus();
            $(t).parent().children()[1].innerHTML = e;
        }
    </script>
@endsection