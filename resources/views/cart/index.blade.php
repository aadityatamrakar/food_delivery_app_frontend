@extends('partials.app')

@section('content')
    <div style="margin-top: 110px;"></div>
    <h2>Final Destination</h2><hr/>

    <div class="row">
        <div class="col-md-8">
            <h4 class="visible-sm visible-xs">Scroll down to place order.</h4>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th colspan="2">Contact Details</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th width="30%">Name</th>
                    <td><input type="text" value="{{ Auth::user()->name }}" class="form-control" required name="customer_name" id="customer_name"></td>
                </tr>
                <tr>
                    <th>Mobile No</th>
                    <td>{{ Auth::user()->mobile }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><input type="text" value="{{ Auth::user()->email }}" class="form-control" required name="customer_email" id="customer_email"></td>
                </tr>
                </tbody>
            </table>
            @if($type == 'delivery')
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th colspan="2">Your Delivery Address</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th width="30%">Delivery Address</th>
                    <td><textarea name="address_text" id="address_text" class="form-control">{{ Auth::user()->address }}</textarea></td>
                </tr>
                <tr>
                    <th>City</th>
                    <td>{{ Request::get('city') }}</td>
                </tr>
                </tbody>
            </table>
            @else
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th width="30%">Restaurant Address</th>
                        <td><b>{{ \App\Restaurant::where('id', Request::get('restaurant_id'))->first()->address }}</b></td>
                    </tr>
                    <tr>
                        <th>City</th>
                        <td>{{ Request::get('city') }}</td>
                    </tr>
                    </tbody>
                </table>
            @endif
        </div>
        <div class="col-md-4">
            {{--<div class="panel panel-default">--}}
                {{--<div class="panel-heading">--}}
                    {{--<h4>How you want your Order ?</h4>--}}
                {{--</div>--}}
                {{--<div class="panel-body">--}}
                    {{--<div class="form-group">--}}
                        {{--<div class="col-md-12">--}}
                            {{--<div class="radio">--}}
                                {{--<label for="delivery_pickup-0">--}}
                                    {{--<input type="radio" name="delivery_pickup" id="delivery_pickup-0" value="delivery" checked>--}}
                                    {{--<b>Deliver at your doorstep</b><br>--}}
                                    {{--<span id="delivery-text">(You will get your order in approx {{ \App\Restaurant::where('id', Request::get('restaurant_id'))->first()->delivery_time }} minutes.)</span>--}}
                                {{--</label>--}}
                            {{--</div>--}}
                            {{--<div class="radio">--}}
                                {{--<label for="delivery_pickup-1">--}}
                                    {{--<input type="radio" name="delivery_pickup" id="delivery_pickup-1" value="pickup">--}}
                                    {{--<b>Pickup from Restaurant</b><br/>--}}
                                    {{--<span class="hide" id="pickup-text">(Pickup from: <b>{{ \App\Restaurant::where('id', Request::get('restaurant_id'))->first()->address }}</b>)</span>--}}
                                {{--</label>--}}
                            {{--</div>--}}
                            {{--<div class="radio">--}}
                                {{--<label for="delivery_pickup-2">--}}
                                    {{--<input type="radio" name="delivery_pickup" id="delivery_pickup-2" value="dinein">--}}
                                    {{--<b>Dine in</b><br/>--}}
                                {{--</label>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Order Information</h4>
                </div>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                    </thead>
                    <tbody style="font-weight: bold;" id="order_info_tbl"></tbody>
                </table>
            </div>
        </div>
        <div class="col-md-12"><button class="pull-right btn btn-lg btn-warning" onclick="submit_form()">Feed Me</button></div>
    </div>

    <form method="post" action="{{ route('cart.confirm') }}" id="cart_form">
        {!! csrf_field() !!}
        <input type="hidden" id="restaurant_id" name="restaurant_id" value="{{ Request::get('restaurant_id') }}" />
        <input type="hidden" id="c_name" name="c_name" />
        <input type="hidden" id="c_email" name="c_email" />
        <input type="hidden" id="cart" name="cart" />
        <input type="hidden" id="wallet_amt" name="wallet_amt" value="{{ \App\Http\Controllers\WalletController::balance() }}" />
        <input type="hidden" id="deliver" name="deliver" />
        <input type="hidden" id="address" name="address" />
        <input type="hidden" id="otp_c" name="otp_c" />
        <input type="hidden" id="type" name="type" value="{{ $type }}" />
        <input type="hidden" id="coupon" name="coupon" value="{{ $coupon_data!='[]'?(json_decode($coupon_data, true)['code']):'' }}" />
        <input type="hidden" id="payment_id" name="payment_id" />
        <input type="hidden" id="block_back" name="block_back" />
    </form>

    {{--<div class="modal fade" data-keyboard="false" id="OTPModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">--}}
        {{--<div class="modal-dialog" role="document">--}}
            {{--<div class="modal-content">--}}
                {{--<div class="modal-header">--}}
                    {{--<h4 class="modal-title">Confirm Your Order ?</h4>--}}
                {{--</div>--}}
                {{--<div class="modal-body">--}}
                    {{--<form onsubmit="return false;" class="form-horizontal" method="post">--}}
                        {{--<fieldset>--}}
                            {{--<div class="form-group">--}}
                                {{--<label class="col-md-4 control-label" for="mobile">Mobile</label>--}}
                                {{--<div class="col-md-6">--}}
                                    {{--<input id="mobile" name="mobile" type="text" readonly value="{{ Auth::user()->mobile }}" class="form-control input-md" >--}}
                                    {{--<span class="help-block" id="otp_status">Sending OTP...</span>--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            {{--<!-- Text input-->--}}
                            {{--<div class="form-group">--}}
                                {{--<label class="col-md-4 control-label" for="otp">OTP</label>--}}
                                {{--<div class="col-md-6">--}}
                                    {{--<input id="otp" name="otp" type="text" class="form-control input-md" required="">--}}
                                    {{--<span class="help-block">Enter the OTP recieved in above mobile number to confirm order.</span>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</fieldset>--}}
                    {{--</form>--}}
                {{--</div>--}}
                {{--<div class="modal-footer">--}}
                    {{--<button type="button" class="btn btn-primary" onclick="save_confirm()">Confirm Order</button>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
@endsection

@section('script')
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        $(document).ready(function (){
            if($('#block_back').val() == 'block')
            {
                window.location.href = '/';
            }
        })
        var html='', gtotal=0, sub_total=0, gqty= 0, cart = JSON.parse('{!! $cart_data !!}'), coupon = JSON.parse('{!! $coupon_data !!}'), delivery_fee=parseFloat('{{ \App\Restaurant::where('id', Request::get('restaurant_id'))->first()->delivery_fee }}'), packing_fee=parseFloat('{{ \App\Restaurant::where('id', Request::get('restaurant_id'))->first()->packing_fee }}'), type='{{ Request::get('type') }}', wallet_amt = 0;
        function save_confirm()
        {
            $("#block_back").val('block');
            @if($type=='delivery')
                if($("#customer_name").val() != '' && $("#customer_email").val() != '' && $("#address_text").val() != '') {
                    $("#c_name").val($("#customer_name").val());
                    $("#c_email").val($("#customer_email").val());
                    $("#address").val($("#address_text").val());
                    $("#cart").val(JSON.stringify(cart));
                    $("#cart_form").submit();
                }else {
                    alert("Enter your Details.");
                    $("#address_text").focus();
                }
            @else
                $("#cart").val(JSON.stringify(cart));
                //$("#otp_c").val($("#otp").val());
                $("#cart_form").submit();
            @endif
        }

        function submit_form()
        {
            var options = {
                "key": "rzp_test_FMKzS7xs08EwP5",
                "amount": gtotal*100,
                "name": "TromBoy",
                "description": "Purchase From {{ \App\Restaurant::find(Request::get('restaurant_id'))->name }}",
                "handler": function (response){
                    $("#payment_id").val(response.razorpay_payment_id);
                    save_confirm();
                },
                "prefill": {
                    "name": "{{ \Illuminate\Support\Facades\Auth::user()->name }}",
                    "contact": "{{ \Illuminate\Support\Facades\Auth::user()->mobile }}",
                    "email": "{{ \Illuminate\Support\Facades\Auth::user()->email }}"
                },
                "notes": {
                    "address": "{{ \Illuminate\Support\Facades\Auth::user()->address }}"
                },
                "theme": {
                    "color": "#F37254"
                }
            };

            if(gtotal > 0){
                var rzp1 = new Razorpay(options);
                rzp1.open();
                e.preventDefault();
            }else{
                $("#payment_id").val('wallet');
                save_confirm();
            }
            {{--$.ajax({url: "{{ route('cart.requestotp') }}", type: "GET"}).done(function (e){ if(e=='sent') $("#otp_status").html('OTP Sent!'); });--}}
            {{--$("#OTPModal").modal('show');--}}
        }

        function apply_coupon(c)
        {
            if(coupon != null && coupon['status'] == 'ok'){
                dis = parseFloat(sub_total*(parseFloat(coupon['percent'])/100)).toFixed(1);
                dis = (dis>=parseFloat(coupon['max_amount']))?coupon['max_amount']:dis;
                dis = dis.toFixed(0);
                if(c==1 && coupon['return_type'] == 'discount'){
                    sub_total -= dis;
                    return '<tr class="text-warning"><td colspan="2">Discount ('+coupon['code']+')</td><td>- Rs. '+dis+'</td></tr>';
                }else if(c==2 && coupon['return_type'] == 'cashback'){
                    return '<tr class="text-warning"><td colspan="3">Voila ('+coupon['code']+') has been applied. Cashback of '+dis+' will be credited in your wallet within 24hrs.</td></tr>';
                }
            }
            return '';
        }

        $(document).ready(function (){
            update_cart();
        });

        function update_cart()
        {
            html = '', gtotal=0, gqty= 0, wallet_amt = $("#wallet_amt").val(), sub_total=0;
            $.each(cart, function (i, v){
                html += '<tr>'+'<td>'+ v['title']+'</td>'+'<td>'+ v['quantity']+'</td>'+'<td>'+ parseFloat(v['quantity']*v['price'])+'</td>'+'</tr>';
                gtotal += parseFloat(v['quantity']*v['price']);
                gqty += parseFloat(v['quantity']);
            });
            html += "<tr><td>Sub Total</td><td>"+gqty+"</td><td>Rs. "+(sub_total=gtotal)+"</td></tr>";

            html += apply_coupon(1);

            if(type == 'delivery')
            {
                if(delivery_fee > 0) {
                    html += "<tr><td colspan='2'>Delivery Fee</td><td>Rs. "+delivery_fee+"</td></tr>";
                    gtotal += delivery_fee;
                }else{
                    html += '<tr class="text-warning"><td colspan="2">Delivery Fee</td><td>FREE</td></tr>';
                }
            }

            if(type != 'dinein')
            {
                if(packing_fee> 0) {
                    html += "<tr><td colspan='2'>Packing Fee</td><td>Rs. "+packing_fee+"</td></tr>";
                    gtotal += packing_fee;
                }else{
                    html += '<tr class="text-warning"><td colspan="2">Packing Fee</td><td>FREE</td></tr>';
                }
            }

            if(gtotal >= wallet_amt) gtotal -= wallet_amt;
            else if(gtotal < wallet_amt) wallet_amt = gtotal, gtotal = 0;

            html += "<tr><td colspan=\"2\">Wallet Balance</td><td>(-) Rs. "+wallet_amt+"</td></tr>";
            html += "<tr><td colspan=\"2\">Amount Payable</td><td>Rs. "+(gtotal)+"</td></tr>";
            html += apply_coupon(2);
            $("#order_info_tbl").html(html);
        }

        $('[name="delivery_pickup"]').click(function (){
            if($(this).val() == 'delivery')
            {
                if($("#delivery-text").hasClass('hide')) $("#delivery-text").removeClass('hide');
                if(! $("#pickup-text").hasClass('hide')) $("#pickup-text").addClass('hide');
            }
            else if($(this).val() == 'pickup')
            {
                if(! $("#delivery-text").hasClass('hide')) $("#delivery-text").addClass('hide');
                if($("#pickup-text").hasClass('hide')) $("#pickup-text").removeClass('hide');
            }else{
                if(! $("#delivery-text").hasClass('hide')) $("#delivery-text").addClass('hide');
                if(! $("#pickup-text").hasClass('hide')) $("#pickup-text").addClass('hide');
            }
            update_cart();
        });
    </script>
@endsection