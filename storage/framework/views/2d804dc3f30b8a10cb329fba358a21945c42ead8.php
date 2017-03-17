<div class="navbar-fixed-top container-fluid" style="border-bottom: 1px solid #9d9d9d; background-color: rgb(240,240,240);">
  <div class="container">
    <div class="row" style="padding-top:5px; ">
      <div class="col-xs-6">
        Download Our App: <i class="fa fa-android fa-2" style="font-size: 16px; margin: 0px 5px;"></i>  <i style="font-size: 16px;"  class="fa fa-apple fa-2" aria-hidden="true"></i>
      </div>
      <div class="col-xs-6">
        <ul class="mylist pull-right" style="list-style: none;">
          <?php if(Auth::check()): ?>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">My Account <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="<?php echo e(route('profile')); ?>">Profile</a></li>
                <li><a href="<?php echo e(route('wallet')); ?>">My Wallet (Rs. 0)</a></li>
                <li><a href="#">My Orders</a></li>
                <li><a href="<?php echo e(route('logout')); ?>">Logout</a></li>
              </ul>
            </li>
          <?php endif; ?>
          <li><a href="#">Contact Us</a></li>
          <li><a href="#">Help</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>