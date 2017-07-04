@extends('partials.app')

@section('style')
    <style>
        body
        {
            padding: 0;
            margin: 0;
            /*background: url('https://images.pexels.com/photos/5928/salad-healthy-diet-spinach.jpg?w=940&h=650&auto=compress&cs=tinysrgb');*/
            background-size: cover;
            background-position: center;
            padding-top: 160px;
        }

        .navbar{
            margin-bottom: 0px;
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
            font-size: 22px;
            font-weight: 600;
            text-align: center;
            margin-top: 5%;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #ff851b;
            text-shadow: 1px 1px 2px #ccc;
        }


        @media (min-width: 990px) {
            form
            {
                padding: 10px 50px;
                margin: 0 auto;
            }

            .mybox{
                margin-top: 35px;
                border:2px solid white;
                width: 620px;
                background-color: rgba(255, 255, 255, 0.95);
                -webkit-box-shadow: 0px 0px 49px 14px rgba(188,190,194,0.39);
                -moz-box-shadow: 0px 0px 49px 14px rgba(188,190,194,0.39);
                box-shadow: 0px 0px 49px 14px rgba(188,190,194,0.39);
                padding-bottom: 20px;
            }
        }

        @media (max-width: 992px) {
            .mybox{
                border:2px solid white;
                width: 100%;
                background-color: rgba(255, 255, 255, 0.95);
                -webkit-box-shadow: 0px 0px 49px 14px rgba(188,190,194,0.39);
                -moz-box-shadow: 0px 0px 49px 14px rgba(188,190,194,0.39);
                box-shadow: 0px 0px 49px 14px rgba(188,190,194,0.39);
                padding-bottom: 20px;
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
            /*border-bottom: 1px solid #FFFFFF;*/
            outline: 0;
            font-style: italic;
            font-size: 24px;
            font-weight: 400;
            letter-spacing: 1px;
            margin-bottom: 5px;
            color: #000;
            outline: 0;

            border: 1px solid #ff8553;
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

        .myBtnClr, .myBtnClr:hover, .myBtnClr:focus, .myBtnClr:active{
            color: white;
            background: #f85032; /* fallback for old browsers */
            background: -webkit-linear-gradient(to left, #f85032 , #e73827); /* Chrome 10-25, Safari 5.1-6 */
            background: linear-gradient(to left, #f85032 , #e73827); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
        }
    </style>
@endsection

{{--@section('content')
    <div class="jumbotron">
        <h2>Download Our app From Play store.</h2>
        <a href="https://play.google.com/store/apps/details?id=com.tromboy" class="btn btn-md btn-primary">Download From Play Store</a>
    </div>
@endsection--}}

@section('content')
    <div class="container" style="height: 400px;" >
        @include('partials.notify')
        <div class="row">
            <div class="col-md-12">
                <div class="wrap">
                    <center>
                        <div class="mybox">
                            <p class="form-title">
                                Select Area & Order Information</p>
                            <form class="login" id="search_frm" method="post" action="{{ route('restaurant.index') }}">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <select name="city" id="city">
                                            <option>City</option>
                                            @foreach(\App\City::all() as $city)
                                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <input disabled autocomplete="off" id="area" name="area" type="text" data-provide="typeahead" value="Area" placeholder="Area" onfocus="this.value=='Area'?this.value='':''" />
                                    </div>
                                </div>
                                <input id="area_id" name="area_id" type="hidden" />
                                {!! csrf_field() !!}
                                <div class="row text-primary text-left" style="padding: 5px 10px; font-size: 16px;">
                                    <div class="col-sm-6">
                                        <input id="delivery" name="type" type="radio" value="delivery" /> <label for="delivery"><b>Delivery</b></label>
                                    </div>
                                    <div class="col-sm-6">
                                        <input id="pickup" name="type" type="radio" value="pickup" /> <label for="pickup"><b>Takeaway</b></label>
                                    </div>
                                    {{--<div class="col-sm-4">--}}
                                        {{--<input id="dinein" name="type" type="radio" value="dinein" /> <label for="dinein"><b>Eat at Restaurant</b></label>--}}
                                    {{--</div>--}}
                                </div>
                                <input type="submit" value="Show Restaurants" id="sub_btn" class="btn myBtnClr btn-sm" />
                            </form>
                            {{--<p style="margin: 0;">Currently, We are live in Satna.</p>--}}
                        </div>
                    </center>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>
    <script>
        function createCookie(name,value,days) {
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                var expires = "; expires=" + date.toUTCString();
            }
            else var expires = "";
            document.cookie = name + "=" + value + expires + "; path=/";
        }

        function readCookie(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for(var i=0;i < ca.length;i++) {
                var c = ca[i];
                while (c.charAt(0)==' ') c = c.substring(1,c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
            }
            return null;
        }

        $(document).ready(function (){
            if(readCookie('city_id') != null) $('#city').val(readCookie('city_id'));
            if($("#city").val() != 'City') $("#area").removeAttr('disabled');

        });
        $("#city").on('change', function (){
            if(this.value != 'City'){
                createCookie('city_id', $(this).val());
                $("#area").removeAttr('disabled');
            }else{
                $("#area").attr('disabled', '');
            }
            $("#area").val("");
        });
        var $area = $('#area');
        $area.typeahead({
            highlight: true,
            name: 'area',
            display: 'name',
            afterSelect: function (item){ $("#area_id").val(item.id); },
            source: function (query, process) {
                var ajaxResponse, city_id= $("#city").val();
                $.ajax({
                    url: '{{ route('area.get') }}?q=' + query+'&c='+city_id,
                    type: "GET",
                    cache: false,
                    async: false,
                    success : function (response) {
                        ajaxResponse = JSON.parse(response);
                        console.log(ajaxResponse);
                        process(ajaxResponse);
                    },
                });
            },
            autoSelect: true
        })

        $("#sub_btn").click(function (e) {
            e.preventDefault();

            if($('[name="type"]:checked').val()!=null) {
                if($("#city").val()!=null) {
                    if($area.val()!="Area") $("#search_frm").submit();
                    else alert("Select Area");
                }else alert("Select City");
            }else alert("Order information not selected.");
        });
    </script>
@endsection