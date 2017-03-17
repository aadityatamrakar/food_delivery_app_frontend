<div class="col-md-6">
    <div class="media" {!! $link?'onclick="window.location=\''. route('restaurant.view', ["type"=>$type, "id"=>$restaurant->id, "name"=>$restaurant->name]).'\'"':'' !!}>
        <div class="media-left">
            <a href="#">
                <img class="media-object" width="100px" height="100px" src="//admin.tromboy.com/images/restaurant/logo/{{ $restaurant->logo }}" alt="{{ $restaurant->name }}">
            </a>
        </div>
        <div class="media-body">
            <div class="media-heading">
                <h4>{{ strlen($restaurant->name)>25?substr($restaurant->name,0,24).'...':$restaurant->name }}</h4>
                <span class="help-block">{{ substr(implode(', ', json_decode($restaurant->cuisines, true)), 0, 44) }}</span>
            </div>
            <center><hr width="90%" style="margin: 8px 0px; border-color: #ccc;"></center>
            <div class="row" style=" font-weight: bold;">
                <div class="col-xs-2" data-toggle="popover" data-placement="top" data-trigger="hover" data-content="Food Type: {{ $restaurant->type }}">
                    <img src="/public/img/{!! $restaurant->type == 'Pure Veg'?'veg':'nveg' !!}.jpg" width="16px" height="16px"/>
                </div>
                @if($link)
                    <div class="col-xs-{{ $type=='dinein'?10:4 }} text-primary" data-toggle="popover" data-placement="top" data-trigger="hover" data-content="@if($type == 'delivery') {{ 'Delivers in '.$restaurant->delivery_time }} @elseif($type == 'pickup') {{ 'Pickup in '.$restaurant->pickup_time }} @elseif($type == 'dinein') {{ 'Dine in time '.$restaurant->dinein_time }} @endif Minutes">
                        <i class="fa fa-clock-o"></i> @if($type == 'delivery') {{ $restaurant->delivery_time }} @elseif($type == 'pickup') {{ $restaurant->pickup_time }} @elseif($type == 'dinein') {{ 'Dine in Time '.$restaurant->dinein_time }} @endif Mins
                    </div>
                    @if($type == 'delivery')
                        <div class="col-xs-6 text-warning" data-toggle="popover" data-placement="top" data-trigger="hover" data-content="Minimum Order Amount Rs. {{ $restaurant->min_delivery_amt }}, Delivery Fee: {{ $restaurant->delivery_fee?'Rs. '.$restaurant->delivery_fee:'FREE' }}, Packing Fee: {{ $restaurant->packing_fee?'Rs. '.$restaurant->packing_fee:'FREE' }}">
                            Min Order <i class="fa fa-inr"></i> {{ $restaurant->min_delivery_amt }}
                        </div>
                    @elseif($type == 'pickup')
                        <div class="col-xs-6 text-warning" data-toggle="popover" data-placement="top" data-trigger="hover" data-content="Container charges {{ $restaurant->packing_fee?'Rs. '.$restaurant->packing_fee:'FREE' }}">
                            Packing Fee <i class="fa fa-inr"></i> {{ $restaurant->packing_fee?:'FREE' }}
                        </div>
                    @endif
                @elseif(!$link)
                    <div class="col-xs-offset-4 col-xs-6">
                        <button class="btn btn-danger btn-xs pull-right" disabled>CURRENTLY CLOSED</button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>