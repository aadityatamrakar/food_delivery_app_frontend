@extends('partials.home_app')

@section('style')
    <style>
        body
        {
            padding: 0;
            margin: 0;
            background: url('https://d2z8zvwx6itreb.cloudfront.net/proyectop_article_d48b2e19fcd9b3f347bdee5ea212297e_jpg_1000x665_100_5043.jpg') fixed;
            background-size: cover;
        }


        .wrap
        {
            width: 100%;
            height: 100%;
            min-height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 99;
        }

        p.form-title
        {
            font-family: 'Open Sans' , sans-serif;
            font-size: 32px;
            font-weight: 600;
            text-align: center;
            margin-top: 5%;
            text-transform: uppercase;
            letter-spacing: 4px;
            color: #fd1100;
            text-shadow: 2px 2px 2px #999;
        }

        @media (min-width: 990px) {
            form
            {
                width: 350px;
                margin: 0 auto;
            }

            .mybox{

                border:2px solid black;
                width: 520px;
                background-color: rgba(255, 255, 255, 0.77);
                box-shadow: -5px -5px 5px rgba(0,0,0,0.6);
                padding-bottom: 20px;"
            }
        }

        @media (max-width: 992px) {
            .mybox{

                border:2px solid black;
                width: 100%;
                background-color: rgba(255, 255, 255, 0.77);
                box-shadow: -5px -5px 5px rgba(0,0,0,0.6);
                padding-bottom: 20px;"
            }
            form
            {
                width: 90%;
                margin: 0 auto;
            }
        }


        form.login input[type="text"], form.login input[type="password"], form.login select
        {
            width: 100%;
            margin: 0;
            padding: 5px 10px;
            background: 0;
            border: 0;
            border-bottom: 1px solid #FFFFFF;
            outline: 0;
            font-style: italic;
            font-size: 24px;
            font-weight: 400;
            letter-spacing: 1px;
            margin-bottom: 5px;
            color: #000;
            outline: 0;
        }

        form.login input[type="submit"]
        {
            width: 100%;
            font-size: 14px;
            text-transform: uppercase;
            font-weight: 500;
            margin-top: 16px;
            outline: 0;
            cursor: pointer;
            letter-spacing: 1px;
        }

        form.login input[type="submit"]:hover
        {
            transition: background-color 0.5s ease;
        }

        form.login .remember-forgot
        {
            float: left;
            width: 100%;
            margin: 10px 0 0 0;
        }
        form.login .forgot-pass-content
        {
            min-height: 20px;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        /*form.login label, form.login a*/
        /*{*/
        /*font-size: 12px;*/
        /*font-weight: 400;*/
        /*color: #FFFFFF;*/
        /*}*/

        .dropdown-menu>li>a {
            color: black !important;
        }

        form.login a
        {
            transition: color 0.5s ease;
        }

        form.login a:hover
        {
            color: #2ecc71;
        }


        .pass-reset label
        {
            font-size: 12px;
            font-weight: 400;
            margin-bottom: 15px;
        }

        .pass-reset input[type="email"]
        {
            width: 100%;
            margin: 5px 0 0 0;
            padding: 5px 10px;
            background: 0;
            border: 0;
            border-bottom: 1px solid #000000;
            outline: 0;
            font-style: italic;
            font-size: 12px;
            font-weight: 400;
            letter-spacing: 1px;
            margin-bottom: 5px;
            color: #000000;
            outline: 0;
        }

        .pass-reset input[type="submit"]
        {
            width: 100%;
            border: 0;
            font-size: 14px;
            text-transform: uppercase;
            font-weight: 500;
            margin-top: 10px;
            outline: 0;
            cursor: pointer;
            letter-spacing: 1px;
        }

        .pass-reset input[type="submit"]:hover
        {
            transition: background-color 0.5s ease;
        }
    </style>
@endsection

@section('content')
    <div class="container" style="margin-top: 200px;" >
        <div class="row">
            <div class="col-md-12">
                <div class="wrap">
                    <center>
                        <div class="mybox">
                            <p class="form-title">
                                Login
                            </p>
                            <form class="login" action="{{ route('login.check_otp') }}" method="post">
                                {!! csrf_field() !!}
                                <input id="mobile" name="mobile" type="text" required="" value="Enter your Mobile" onfocus="this.value=='Enter your Mobile'?this.value='':''" />
                                <input id="otp" name="otp" type="text" required="" class="hide" value="Enter 6 digit OTP here" onfocus="this.value=='Enter 6 digit OTP here'?this.value='':''" />
                                <input type="submit" id="getotp" name="getotp" class="btn btn-primary" value="Get OTP">
                                <input type="submit" id="login" name="login" value="Login" class="btn btn-success btn-sm hide" />
                            </form>
                        </div>
                    </center>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $("#getotp").on('click', function (e){
            e.preventDefault();
            $("#getotp").attr('disabled', '');
            var mobile = $("#mobile").val();
            $.ajax({
                url: "{{ route('login.get_otp') }}",
                type: "POST",
                async: true,
                data: {mobile: mobile, "_token": "{{ csrf_token() }}"}
            }).done(function (e){
                if(e == 'sent')
                {
                    $("#mobile").attr('readonly', '');
                    $("#otp").removeClass('hide');
                    $("#getotp").addClass('hide');
                    $("#login").removeClass('hide');
                }
            })
        });
    </script>
@endsection