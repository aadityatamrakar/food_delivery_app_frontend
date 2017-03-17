<div class="navbar-fixed-top" id="header_top_nav">
  <div class="container-fluid" style="border-bottom: 1px solid #9d9d9d; background-color: rgb(240,240,240);">
    @include('partials.top_bar_above_nav')
  </div>
  <div class="container-fluid brand_nav_bar">
    <div class="container">
      <div class="row">
        <div class="col-md-3">
          <h1 style="line-height: 25px; margin: 0px; padding: 15px 0px;"><a style="text-decoration: none; color:white; font-weight: bold;" href="{{ route('home') }}">TromBoy</a></h1>
        </div>
        <div class="col-md-9 hidden-xs" style="padding: 5px 0px;">
          @yield('header_right')
        </div>
      </div>
    </div>
  </div>
</div>