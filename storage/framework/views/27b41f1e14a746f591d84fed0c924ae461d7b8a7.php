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
</style>
<footer class="container-fluid" style="margin-top: 20px; border-top: 1px solid #ddd; padding: 20px 0px;">
    <div class="container">
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
                        <li><a href="#">About Us</a></li> |
                        <li><a href="#">Partner with Us</a></li> |
                        <li><a href="#">Help & Support</a></li> |
                        <li><a href="#">Refunds & Cancellation Policy</a></li> |
                        <li><a href="#">Terms & Condition</a></li>
                    </ul>
                </center>
            </div>
        </div>
        <div class="row" style="margin-top: 25px;">
            <div class="col-xs-12 social-icons" style="font-size: 16px;">
                <center>
                    <p class="footer-title">Copyright &copy; <?php echo e(\Carbon\Carbon::now()->format('Y')); ?>, <a href="#">TromBoy.com</a></p>
                </center>
            </div>
        </div>
    </div>
</footer>