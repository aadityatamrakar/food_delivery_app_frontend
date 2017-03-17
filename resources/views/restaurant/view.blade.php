@extends('partials.app')

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/css/bootstrap-dialog.min.css"  />
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
        <center><img style="box-shadow: 2px 2px 2px rgba(0,0,0,0.7);" src="//admin.tromboy.com/images/restaurant/logo/{{ $restaurant->logo }}" width="100px" height="100px" /></center>
        <center><h1 style="margin: 0px; padding: 0px 0px 15px 0px; color:white; text-shadow: 2px 2px 2px rgba(0, 0, 0, 0.6);">:: {{ $restaurant->name }} ::</h1></center>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title" style="text-transform: capitalize; font-weight: bold;">{{ $type }} Menu</div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3">
                            <ul class="nav nav-pills nav-stacked" role="tablist" style="font-weight: bold;">
                                @foreach($restaurant->categories as $index=>$category)
                                    <li role="presentation" class="{{ $index==0?"active":'' }}"><a href="#{{ str_replace(' ', '_', $category->title) }}" aria-controls="{{ str_replace(' ', '_', $category->title) }}" role="tab" data-toggle="tab">{{ $category->title }} <span class="badge">{{ count($category->products) }}</span></a></li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-md-9">
                            <div class="tab-content">
                                @foreach($restaurant->categories as $index=>$category)
                                    <div role="tabpanel" class="tab-pane {{ $index==0?"active":'' }}" id="{{ str_replace(' ', '_', $category->title) }}">
                                        <table class="table table-bordered" style="background: #fff; box-shadow: 2px 2px 2px #ccc;">
                                            <thead>
                                            <tr><th>Name</th><th width="30%">Price</th><th width="9%">Add?</th></tr>
                                            </thead>
                                            <tbody>
                                            @foreach($category->products as $product)
                                                <tr><th style="text-transform: capitalize;">{{ $product->title }}</th><th>Rs. {!! $product->mrp>$product->price?'<span class="text-danger" style="text-decoration: line-through;">'.$product->mrp.'</span>':'' !!} {{ $product->price }}</th><th><center><button class="btn btn-xs btn-success" onclick="addtocart('{{ $product->id }}', '{{ $product->title }}', 1, '{{ $product->price }}')" ><i class="glyphicon-plus glyphicon"></i> </button></center></th></tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="panel-title text-center" style="font-weight: bold; font-size: 20px;">Cart</div>
                </div>
                <table class="table table-bordered">
                    <thead>
                    <tr><th colspan="3" id="min_order_text" style="font-weight: bold"></th></tr>
                    <tr>
                        <th>Name</th>
                        <th>Qty.</th>
                        <th width="25%">Price</th>
                    </tr>
                    </thead>
                    <tbody id="cart_body" style="font-weight: bold;">
                    <tr><td colspan="3"><i class="fa fa-basket fa-3"></i> Wholla Empty Stomach? Add Items In Your Cart</td></tr>
                    </tbody>
                    <tfoot id="cart_footer" class="text-success" style="font-weight: bold;"></tfoot>
                </table>
                <div class="panel-body row hide" id="promo_row">
                    <div class="col-xs-12 hide" id="promocode_box" style="margin-bottom: 15px;">
                        <div class="input-group">
                            <input onkeyup="$(this).val($(this).val().toUpperCase());" type="text" name="promocode" id="promocode" class="form-control" placeholder="Enter Promocode Here.">
                            <span class="input-group-btn">
                            <button class="btn btn-default" onclick="check_coupon()" type="button">Apply!</button>
                        </span>
                        </div>
                        <span class="text-danger" id="check_error"></span>
                    </div>
                    <div class="col-xs-4">
                        <b>Have a Promocode ?</b>
                    </div>
                    <div class="col-xs-4">
                        <div class="onoffswitch">
                            <input type="checkbox" name="have_promocode" class="onoffswitch-checkbox" id="myonoffswitch">
                            <label class="onoffswitch-label" for="myonoffswitch">
                                <span class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>
                            </label>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <button class="pull-right btn btn-md btn-warning" id="checkout_btn" onclick="submit_cart()">FEED ME <i class="glyphicon glyphicon-arrow-right"></i></button>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <form method="post" action="{{ route('cart.checkout') }}" id="cart_form">
        {!! csrf_field() !!}
        <input type="hidden" id="restaurant_id" name="restaurant_id" value="{{ $restaurant->id }}" />
        <input type="hidden" id="cart" name="cart" />
        <input type="hidden" id="coupon" name="coupon" />
        <input type="hidden" id="type" name="type" value="{{ $type }}" />
        <input type="hidden" id="city" name="city" value="{{ \App\City::where('id', $restaurant->city_id)->first()->name }}" />
    </form>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/js/bootstrap-dialog.min.js"></script>
    <script>
        var coupon, c_applied, dis = 0, cart = [], amt_wo_dpf = 0, gtotal= 0, quantity = 0, min_order = parseFloat('{{ $type=='delivery'?$restaurant->min_delivery_amt:0 }}'), delivery_fee = parseFloat('{{ $restaurant->delivery_fee }}'), packing_fee = parseFloat('{{ $restaurant->packing_fee }}');

        $(document).ready(function (){
            if(window.localStorage.getItem('cart') != null && window.localStorage.getItem('cart') != '') {
                cart = JSON.parse(window.localStorage.getItem('cart'));
                check_cart();
            }else{
                check_switch();
            }
        });

        function check_cart()
        {
            var cart_data = JSON.stringify(cart);
            $.ajax({
                url : "{{ route('cart.check') }}",
                type: "POST",
                async: true,
                data: {"_token":"{{ csrf_token() }}", 'cart': cart_data, 'restaurant_id': '{{ $restaurant->id }}'}
            }).done(function (e){
                if(e['status'] == 'ok') {
                    console.log('+CART: ok');
                    update_cart();
                }else {
                    cart = [];
                    window.localStorage.setItem('cart', '[]');
                    update_cart();
                }
                check_switch();
            });
        }

        function check_coupon()
        {
            a = BootstrapDialog.show({
                title: 'Notification!',
                message: '<i class="fa fa-2x fa-spin fa-spinner spin"></i> Checking & Applying Coupon...',
                type: BootstrapDialog.TYPE_PRIMARY,
            });
            var code = $("#promocode").val();
            $.ajax({
                url : "{{ route('coupon.check') }}",
                type: "POST",
                data: {"_token":"{{ csrf_token() }}", 'code': code, 'gtotal': amt_wo_dpf}
            }).done(function (e){
                a.close();
                if(e['status'] == 'ok') {
                    $("#promocode").val('');
                    $("#check_error").html("");
                    coupon = e;
                    coupon['code'] = code;
                    update_cart();
                }else {
                    var error_box = $("#check_error");
                    switch(e['error'])
                    {
                        case 'invalid':
                            error_box.html("Code is invalid, Kindly check and retry.");
                            break;
                        case 'new_only':
                            error_box.html("This code is only applicable for new user.");
                            break;
                        case 'expired':
                            error_box.html("Sorry, This code is expired.");
                            break;
                        case 'min_amt':
                            error_box.html("Min. Cart Amount should be "+ e['min_amt']);
                            break;
                        case 'times_exceeded':
                            error_box.html("You have exceeded usage limit of this coupon.");
                            break;
                        default:
                            error_box.html("Error in applying coupon.");
                    }
                }
            });
        }

        $("#myonoffswitch").on('change', function (){
            check_switch();
        });

        function check_switch(){
            if ($('#myonoffswitch').is(':checked')) {
                $('#promocode_box')[0].classList.remove('hide');
            }else {
                $('#promocode_box')[0].classList.add('hide');
                $("#promocode").val('');
                $("#check_error").html('');
            }
            coupon = null;
            update_cart();
        }

        function submit_cart()
        {
            if(min_order <= (gtotal+dis))
            {
                $("#cart").val(JSON.stringify(cart));
                if(coupon != null) $("#coupon").val(coupon['code']);
                $("#cart_form").submit();
            }else{
                @if($type == 'delivery')
                    $("#min_order_text").html("<span class=\"text-danger\">You are Rs. "+parseFloat(min_order-gtotal)+" away from min order.</span>");
                @endif
            }
        }

        function removefromcart(i)
        {
            cart.splice(i, 1);
            window.localStorage.setItem('cart', JSON.stringify(cart));
            update_cart();
        }

        function addtocart(i, n, q, p)
        {
            if(parseFloat(quantity) < 50 && parseFloat(gtotal) <= 4200)
            {
                var flag = true;
                $.each(cart, function (index, v){
                    if(v['id'] == i)
                    {
                        v['quantity'] += q;
                        flag = false;
                    }
                });
                if(flag) cart.push({"id": i, "title":n, "price":p, "quantity":q});
                window.localStorage.setItem('cart', JSON.stringify(cart));
                update_cart();
            }else{
                alert("Maximum Order limit reached.");
            }
        }

        function apply_coupon(c)
        {
            if(gtotal > 0 && coupon != null)
            {
                if(gtotal >= parseFloat(coupon['min_amt'])) {
                    $("#promocode_box").addClass('hide');
                    dis = parseFloat(gtotal*(parseFloat(coupon['percent'])/100)).toFixed(0);
                    dis = (dis>=parseFloat(coupon['max_amount']))?parseFloat(coupon['max_amount']):dis;
                    if(c==1 && coupon['return_type'] == 'discount'){
                        gtotal -= dis;
//                        c_applied = true;
                        return '<tr class="text-warning"><td colspan="2">Discount ('+coupon['code']+')</td><td>- Rs. '+dis+'</td></tr>';
                    }else if(c==2 && coupon['return_type'] == 'cashback')
                    {
//                        c_applied = true;
                        return '<tr class="text-warning"><td colspan="3">Voila ('+coupon['code']+') has been applied. Cashback of '+dis+' will be credited in your wallet within 24hrs.</td></tr>';
                    }
                    a = BootstrapDialog.show({
                        title: 'Notification!',
                        message: '<i class="fa fa-2x fa-check"></i> Coupon applied',
                        type: BootstrapDialog.TYPE_SUCCESS, // <-- Default value is BootstrapDialog.TYPE_PRIMARY
                        closable: true,
                    });
                    setTimeout(function (){
                        a.close();
                    }, 1500);
                }else{
//                    c_applied = false;
                    document.getElementById('myonoffswitch').checked = false;
                    $("#check_error").html("Min. Sub Total should be "+coupon['min_amt']);
                    check_switch();
                }
            }else if((gtotal == 0 && coupon != null)){
//                c_applied = false;
                document.getElementById('myonoffswitch').checked = false;
                check_switch();
            }
            return '';
        }

        function update_cart()
        {
            var html='', footer='';
            quantity = gtotal = amt_wo_dpf = 0;
            for(var i=0;i<cart.length;i++)
            {
                html        += '<tr onmouseout="show_remove_btn(this, \'out\')" onmouseover="show_remove_btn(this, \'over\')"><td>'+'<button class="hide btn btn-xs btn-danger pull-right" onclick="removefromcart('+i+')"><i class="fa fa-trash"></i></button> '+cart[i]['title']+'</td><td width="20%"><input type="number" value="'+cart[i]['quantity']+'" style="width:50px" min="1" max="49" onchange="this.value = update_item('+i+', this.value)" /></td><td>Rs. '+parseFloat(cart[i]['quantity']*cart[i]['price'])+'</td></tr>';
//                        '<div class="input-group"><span class="input-group-btn"><button type="button" class="quantity-left-minus btn btn-danger btn-number"  data-type="minus" data-field=""><span class="glyphicon glyphicon-minus"></span></button></span><input type="text" id="" name="" class="form-control input-number" value="10" min="1" max="100">/<span class="input-group-btn"><button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus" data-field=""><span class="glyphicon glyphicon-plus"></span></button></span></div>'
                gtotal      += parseFloat(cart[i]['quantity']*cart[i]['price']);
                quantity    += cart[i]['quantity'];
            }

            amt_wo_dpf = gtotal; // For Coupon applying

            footer += apply_coupon(1);

            @if($type == 'delivery')
                if(min_order > (gtotal+dis)){
                    $("#min_order_text").html("<span class=\"text-danger\">You are Rs. "+parseFloat(min_order-gtotal).toFixed(1)+" away from minimum order.</span>");
                    if(! $("#promo_row").hasClass('hide')) {
                        $("#promo_row").addClass('hide');
                    }
                }else{
                    $("#min_order_text").html("<span class=\"text-success\">Yeah Minimum Order Amount Reached</span>");
                    if($("#promo_row").hasClass('hide')) $("#promo_row").removeClass('hide');
                }
            @endif

            if(gtotal > 0) {
                footer += '<tr><td>Sub Total</td><td>'+quantity+'</td><td>Rs. '+amt_wo_dpf+'</td></tr>';

                if($('#type').val() != 'dinein')
                {
                    if($('#type').val() == 'delivery'){
                        if(delivery_fee > 0) {
                            footer += '<tr><td colspan="2">Delivery Fee</td><td>+ Rs. '+delivery_fee+'</td></tr>';
                            gtotal += delivery_fee;
                        }else{
                            footer += '<tr class="text-warning"><td colspan="2">Delivery Fee</td><td>FREE</td></tr>';
                        }
                    }
                    if(packing_fee > 0) {
                        footer += '<tr><td colspan="2">Packing Fee</td><td>+ Rs. '+packing_fee+'</td></tr>';
                        gtotal += packing_fee;
                    }else{
                        footer += '<tr class="text-warning"><td colspan="2">Packing Fee</td><td>FREE</td></tr>';
                    }
                }
                footer += '<tr class="text-primary"><td colspan="2">Amount Payable</td><td>Rs. '+gtotal+'</td></tr>';
                if($("#promo_row").hasClass('hide')) {
                    //$("#checkout_btn").removeClass('hide');
                    $("#promo_row").removeClass('hide');
                }

            }else{
                html = '<tr><td class="text-success" colspan="3" style="font-weight: bold; font-size: 20px;"><center>Good Food<span class="fa-stack fa-2x"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-shopping-basket fa-stack-1x fa-inverse"></i></span>Good Mood</center></td></tr><tr><td colspan="3"><i class="fa fa-basket fa-3"></i> Add Items In Your Cart</td></tr>';
                if(! $("#promo_row").hasClass('hide')) {
                    //$("#checkout_btn").addClass('hide');
                    $("#promo_row").addClass('hide');
                }
            }

//            if(! c_applied) {
//                console.log('not applied');
            footer += apply_coupon(2);
//            }

            $("#cart_body").html(html);
            $("#cart_footer").html(footer);
        }

        function update_item(i, q)
        {
            if(q < 50)
            {
                cart[i]['quantity'] = parseFloat(q);
                window.localStorage.setItem('cart', JSON.stringify(cart));
                update_cart();
                return parseFloat(q);
            }else{
                alert('Maximum order limit reached.');
                return parseFloat(cart[i]['quantity']);
            }
        }

        function show_remove_btn(t, s)
        {
            if(s == 'over') t.children[0].children[0].classList.remove('hide');
            if(s == 'out') t.children[0].children[0].classList.add('hide');
        }
    </script>
    @yield('area_script')
@endsection