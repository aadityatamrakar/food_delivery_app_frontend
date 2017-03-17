<div class="container">
    <div class="row" style="padding-top:5px; ">
        <div class="col-xs-8">
            Download Our App: <i class="fa fa-android fa-2" style="font-size: 16px; margin: 0px 5px;"></i>  <i style="font-size: 16px;"  class="fa fa-apple fa-2" aria-hidden="true"></i>
        </div>
        <div class="col-xs-4 hidden-xs hidden-sm">
            <ul class="mylist pull-right" style="list-style: none;">
                @if(Auth::check())
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">My Account <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('profile') }}">Profile</a></li>
                            <li><a href="{{ route('wallet') }}">My Wallet (Rs. {{ \App\Http\Controllers\WalletController::balance() }})</a></li>
                            <li><a href="#">My Orders</a></li>
                            <li><a href="{{ route('logout') }}">Logout</a></li>
                        </ul>
                    </li>
                @endif
                <li><a href="#">Contact Us</a></li>
                <li><a href="#">Help</a></li>
            </ul>
        </div>
        <div class="col-xs-4 hidden-lg hidden-md">
            <ul class="mylist pull-left" style="list-style: none;">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Menu <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        @if(Auth::check())
                            <li class="divider"><a href="#">My Account</a></li>
                            <li><a href="{{ route('profile') }}">Profile</a></li>
                            <li><a href="{{ route('wallet') }}">My Wallet (Rs. {{ \App\Http\Controllers\WalletController::balance() }})</a></li>
                            <li><a href="#">My Orders</a></li>
                            <li><a href="{{ route('logout') }}">Logout</a></li>
                        @endif
                        <li class="divider"><a href="#">Need Help ?</a></li>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">Help</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>