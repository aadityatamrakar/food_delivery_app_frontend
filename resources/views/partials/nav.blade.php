<div class="navbar-fixed-top" id="header_top_nav">
  <div class="container-fluid" style="border-bottom: 1px solid #9d9d9d; background-color: rgb(240,240,240);">
    <div class="container">
      <div class="row" style="padding-top:5px; ">
        <div class="col-md-6">
          Download Our App: <i class="fa fa-android fa-2" style="font-size: 16px; margin: 0px 5px;"></i>  <i style="font-size: 16px;"  class="fa fa-apple fa-2" aria-hidden="true"></i>
        </div>
        <div class="col-md-6 hidden-xs">
          <ul class="mylist pull-right" style="list-style: none;">
            @if(Auth::check())
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">My Account <span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="{{ route('profile') }}">Profile</a></li>
                  <li><a href="{{ route('wallet') }}">My Wallet (Rs. 0)</a></li>
                  <li><a href="#">My Orders</a></li>
                  <li><a href="{{ route('logout') }}">Logout</a></li>
                </ul>
              </li>
            @endif
            <li><a href="#">Contact Us</a></li>
            <li><a href="#">Help</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div class="container-fluid" style="background-color: white; border-bottom: 1px solid #d0e9c6; background-color: #d0e9c6;">
    <div class="container">
      <div class="row">
        <div class="col-md-3">
          <h1 style="line-height: 25px; margin: 0px; padding: 15px 0px;"><a style="text-decoration: none; color:black;" href="{{ route('home') }}">TromBoy</a></h1>
        </div>
        <div class="col-md-9 hidden-xs" style="padding: 5px 0px;">
          @yield('header_right')
        </div>
      </div>
    </div>
  </div>
</div>