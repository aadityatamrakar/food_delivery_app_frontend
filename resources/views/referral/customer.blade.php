<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Referral</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="container">
    <div class="starter-template">

        @include('partials.errors')

        <form class="form-horizontal" action="{{ route('referral_save') }}" method="post" id="form">
            <fieldset>
                <legend>Referral</legend>
                {!! csrf_field() !!}
                <div class="form-group">
                    <label class="col-md-4 control-label" for="name">Name</label>
                    <div class="col-md-6">
                        <input id="name" name="name" type="text" value="{{ old("name") }}" class="form-control input-md" required="">
                        <span class="help-block">{{ $errors->first('name') }}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="mobile">Mobile</label>
                    <div class="col-md-6">
                        <input id="mobile" name="mobile" maxlength="10" type="text" value="{{ old("mobile") }}" class="form-control input-md" required="">
                        <span id="mobile_help_block" class="help-block">{{ $errors->first('mobile') }}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="address">Address</label>
                    <div class="col-md-6">
                        <input id="address" name="address" type="text" value="{{ old("address") }}" class="form-control input-md" required="">
                        <span class="help-block">{{ $errors->first('address') }}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="type">Food Type</label>
                    <div class="col-md-4">
                        <label class="radio-inline" for="veg_noveg-0">
                            <input type="radio" name="type" id="type-0" value="veg" checked="checked">
                            Veg
                        </label>
                        <label class="radio-inline" for="veg_noveg-1">
                            <input type="radio" name="type" id="type-1" value="non-veg">
                            Non-veg
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="otp">OTP</label>
                    <div class="col-md-6">
                        <input id="otp" name="otp" type="text" maxlength="3" value="{{ old('otp') }}" class="form-control input-md" required="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="save"></label>
                    <div class="col-md-8">
                        <button id="save" name="save" class="btn btn-success">Save</button>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>
    var otp;
    $("#save").click(function (e){
        e.preventDefault();
        if(otp == parseInt($('#otp').val())) $("#form").submit();
        else alert('Invalid OTP, Try again.');
    });
    $("#mobile").on('blur', function (){
        var mobile = $(this).val();
        $.ajax({
            url: "{{ route('referral_otp') }}",
            type: "POST",
            data: {'_token': "{{ csrf_token() }}", 'mobile': mobile}
        }).done(function (res){
            if(res.hasOwnProperty('otp')){
                $("#mobile_help_block").html('OTP Sent!');
                $("#mobile").attr('readonly', '');
                otp = res['otp'];
            }else{
                alert('Already Registered.');
            }
        });

    })
</script>
</body>
</html>