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
                    <td>{{ Auth::user()->name }}</td>
                </tr>
                <tr>
                    <th>Mobile No</th>
                    <td>{{ Auth::user()->mobile }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ Auth::user()->email }}</td>
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
        <input type="hidden" id="cart" name="cart" />
        <input type="hidden" id="deliver" name="deliver" />
        <input type="hidden" id="address" name="address" />
        <input type="hidden" id="otp_c" name="otp_c" />
        <input type="hidden" id="type" name="type" value="{{ $type }}" />
        <input type="hidden" id="coupon" name="coupon" value="{{ $coupon_data!='[]'?(json_decode($coupon_data, true)['code']):'' }}" />
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
    <script>
        var html='', gtotal=0, gqty= 0, cart = JSON.parse('{!! $cart_data !!}'), coupon = JSON.parse('{!! $coupon_data !!}'), delivery_fee=parseFloat('{{ \App\Restaurant::where('id', Request::get('restaurant_id'))->first()->delivery_fee }}'), packing_fee=parseFloat('{{ \App\Restaurant::where('id', Request::get('restaurant_id'))->first()->packing_fee }}'), type='{{ Request::get('type') }}';
        function save_confirm()
        {
            @if($type=='delivery')
                if($("#address_text").val() != '') {
                    $("#address").val($("#address_text").val());
                    $("#cart").val(JSON.stringify(cart));
                    //$("#otp_c").val($("#otp").val());
                    $("#cart_form").submit();
                }else {
                    alert("Enter your delivery address");
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
            {{--$.ajax({url: "{{ route('cart.requestotp') }}", type: "GET"}).done(function (e){ if(e=='sent') $("#otp_status").html('OTP Sent!'); });--}}
            {{--$("#OTPModal").modal('show');--}}
            save_confirm();
        }

        function apply_coupon(c)
        {
            if(coupon != null && coupon['status'] == 'ok'){
                dis = parseFloat(gtotal*(parseFloat(coupon['percent'])/100)).toFixed(1);
                dis = (dis>=parseFloat(coupon['max_amount']))?coupon['max_amount']:dis;
                if(c==1 && coupon['return_type'] == 'discount'){
                    gtotal -= dis;
                    return '<tr class="text-warning"><td colspan="2">Discount ('+coupon['code']+')</td><td>- Rs. '+dis+'</td></tr>';
                }else if(c==2 && coupon['return_type'] == 'cashback'){
                    return '<tr class="text-warning"><td colspan="3">Voila ('+coupon['code']+') has been applied. Cashback of '+dis+' coins will be added in your wallet within 24hrs.</td></tr>';
                }
            }
            return '';
        }

        $(document).ready(function (){
            update_cart();
        });

        function update_cart()
        {
            html = '', gtotal=0, gqty= 0;
            $.each(cart, function (i, v){
                html += '<tr>'+'<td>'+ v['title']+'</td>'+'<td>'+ v['quantity']+'</td>'+'<td>'+ parseFloat(v['quantity']*v['price'])+'</td>'+'</tr>';
                gtotal += parseFloat(v['quantity']*v['price']);
                gqty += parseFloat(v['quantity']);
            });
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

            html += "<tr><td>Grand Total</td><td>"+gqty+"</td><td>Rs. "+gtotal+"</td></tr>";
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