<style>
    .social-icons i
    {
        margin-left: 10px;
    }
    footer ul li{
        display: inline-block;
        margin-left: 5px;
    }
    footer ul li a{
        color: #cecece;
    }
    footer ul{
        list-style-type: circle;
    }
    footer{
        background-color: #363535;
        color: #cecece;
    }
    .footer-title{

    }
    .app_bg{
        background: #abbaab; /* fallback for old browsers */
        background: -webkit-linear-gradient(to left, #abbaab , #ffffff); /* Chrome 10-25, Safari 5.1-6 */
        background: linear-gradient(to left, #abbaab , #ffffff); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
    }
    .blood_red_gradient{
        color: #f85032; /* fallback for old browsers */
        color: -webkit-linear-gradient(to left, #f85032 , #e73827); /* Chrome 10-25, Safari 5.1-6 */
        color: linear-gradient(to left, #f85032 , #e73827); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
    }
</style>

<div class="container-fluid app_bg" style="margin-top: 20px; padding: 20px 0px;">
    <div class="container">
        <h2 class="text-center" style="color:#5e5e5e; font-weight: bold; font-size: 40px;">How it Works!</h2>
        <br>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <center>
                    <div class="col-md-3">
                        <i class="fa fa-map-marker fa-5x blood_red_gradient"></i><br>
                        <h3>Location</h3>
                        <p>Enter your location so that we can show you which restaurants deliver to you.</p>
                    </div>
                    <div class="col-md-3">
                        <i class="fa fa-cutlery fa-5x blood_red_gradient"></i><br>
                        <h3>Order</h3>
                        <p>Pick a restaurant and select items you’d like to order. You can search by restaurant and order.</p>
                    </div>
                    <div class="col-md-3">
                        <i class="fa fa-inr fa-5x blood_red_gradient"></i><br>
                        <h3>Payment</h3>
                        <p> Pay fast & secure online or on delivery!</p>
                    </div>
                    <div class="col-md-3">
                        <i class="fa fa-smile-o fa-5x blood_red_gradient"></i><br>
                        <h3>Enjoy</h3>
                        <p> Enjoy the food and rate us on Playstore/Appstore</p>
                    </div>
                </center>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid app_bg" style="padding: 20px 0px;">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h3>Avail exclusive offers only on TromBoy app. Get link to download the app NOW!</h3>
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="input-group">
                            <span class="input-group-addon" id="sizing-addon1">+91</span><input type="text" class="form-control" placeholder="Enter your Mobile number." id="mobile" name="mobile">
                            <span class="input-group-btn">
                        <button class="btn btn-warning" type="button" data-toggle="send_sms_link">Send SMS!</button>
                    </span>
                        </div><!-- /input-group -->
                    </div>
                </div>
                <br/>
                <center><a class="btn btn-primary btn-md"><i class="fa fa-android fa-2x"></i> Google Play</a></center>
            </div>
        </div>
    </div>
</div>

<footer class="container-fluid" style=" border-top: 1px solid #ddd; padding: 20px 0px;">
    <div class="container">
        {{--<div class="row">--}}
            {{--<div class="col-md-6">--}}
                {{--<h1 style="color: white;">Subscribe to our newsletter</h1>--}}
                {{--<form action="//Tromboy.us15.list-manage.com/subscribe/post?u=2b73e95cbe79f03d5079d62b3&amp;id=cbe7f66a37" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>--}}
                    {{--<div class="row">--}}
                        {{--<div class="col-xs-6">--}}
                            {{--<input type="email" name="EMAIL" class="form-control" placeholder="Email Address" required>--}}
                            {{--<input type="hidden" name="b_2b73e95cbe79f03d5079d62b3_cbe7f66a37" tabindex="-1" value="">--}}
                        {{--</div>--}}
                        {{--<div class="col-xs-6">--}}
                            {{--<input type="submit" value="Subscribe" name="subscribe" class="btn btn-primary">--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</form>--}}

            {{--</div>--}}
            {{--<div class="col-md-6 text-right pull-right">--}}
                {{--<h4 style="color: white;">Restaurant Owner ?</h4>--}}
                {{--<p>Join the TromBoy network <br/>and increase your sales</p>--}}
                {{--<a href="http://t3b.in/partnerwithus" target="_blank" class="btn btn-primary">Submit Request</a>--}}
            {{--</div>--}}
        {{--</div>--}}

        {{--<hr/>--}}

        <div class="row">

            <div class="col-xs-12 social-icons" style="font-size: 24px;">
                <center>
                    <p>Find us on</p>
                    <i class="fa fa-facebook-square" style="color: rgb(59,87,157);"></i>
                    <i class="fa fa-pinterest-square" style="color: red;"></i>
                    <i class="fa fa-linkedin-square" style="color: rgb(0, 123,182);"></i>
                    <i class="fa fa-instagram" style="color: orangered;"></i>
                    <i class="fa fa-twitter-square" style="color: rgb(44,170,225);"></i>
                    <i class="fa fa-youtube-square" style="color: rgb(200,23,1);"></i>
                </center>
            </div>
        </div>
        <div class="row" style="margin-top: 25px;">
            <div class="col-xs-12 social-icons" style="font-size: 16px;">
                <center>
                    <ul>
                        <li><a href="{{ route('about') }}">About Us</a></li> |
                        <li><a href="{{ route('careers') }}">Careers</a></li> |
                        <li><a target="_blank" href="http://t3b.in/partnerwithus">Partner with Us</a></li> |
                        <li><a href="{{ route('helpsupport') }}">Help & Support</a></li> |
                        <li><a href="{{ route('refundcancel') }}">Refunds & Cancellation Policy</a></li> |
                        <li><a href="{{ route('privacypolicy') }}">Privacy Policy</a></li> |
                        <li><a href="{{ route('termsconditions') }}">Terms & Condition</a></li> |
                        <li><a href="{{ route('contact') }}">Contact Us</a></li>
                    </ul>
                </center>
            </div>
        </div>
        <div class="row" style="margin-top: 25px;">
            <div class="col-xs-12 social-icons" style="font-size: 16px;">
                <center>
                    <p class="footer-title">Copyright &copy; {{ \Carbon\Carbon::now()->format('Y') }}, <a href="#">TromBoy.com</a></p>
                </center>
            </div>
        </div>
    </div>
</footer>
@push('scripts')
<script>
    $('[data-toggle="send_email_link"]').click(function (e){
        e.preventDefault();
        var btn = $(this);
        btn.attr('disabled', 'true');
        btn.html('Sending...');
        setTimeout(function (){
            btn.html('Sent!');
        }, 1500);
        var email = $("#email").val();
        $.ajax({
            url: "{{ route('send_link_email', ['email'=>'']) }}/"+encodeURIComponent(email),
        });
    });
    $('[data-toggle="send_sms_link"]').click(function (e){
        e.preventDefault();
        var btn = $(this);
        btn.attr('disabled', 'true');
        btn.html('Sending...');
        setTimeout(function (){
            btn.html('Sent!');
        }, 1500);
        var mobile = $("#mobile").val();
        $.ajax({
            url: "{{ route('send_link_sms', ['mobile'=>'']) }}/"+encodeURIComponent(mobile),
        });
    });
</script>
@endpush
