<?php
$faqs = [
    ["id"=>1, "question"=>"Can I cancel my order?", "answer"=>"You may cancel the order before it is placed with restaurant by contacting the support team via chat or call.Once an order is placed and restaurant starts preparing your food, we cannot cancel it."],
    ["id"=>2, "question"=>"Can I edit my order? ", "answer"=>"You may cancel the order before it is placed with restaurant by contacting the support team via chat or call. Once an order is placed and restaurant starts preparing your food, we cannot cancel it."],
    ["id"=>3, "question"=>"Will Tromboy be accountable for quality/quantity?", "answer"=>"The restaurant is responsible for the quality and quantity of the food. However if there's an issue for the same, kindly mention your complaint at support@tromboy.com  and we will pass it on to the restaurant."],
    ["id"=>4, "question"=>"Where is my order?", "answer"=>"After you place your order, we send it directly to the restaurant, which starts preparing it immediately. Our restaurants do everything they can to get your food delivered as quickly as possible. However, sometimes restaurants receive very large amount of orders, or drivers get stuck in heavy traffic - this unfortunately might cause delays.If the amount of time you’ve waited has exceeded the estimated delivery time, you can contact us and we’ll look into what’s going on."],
    ["id"=>5, "question"=>"How to order", "answer"=>"It is as easy as you will understand it in a one go: <br>1. Tell us your directions: Enter your appropriate location so that we can show you which restaurants deliver to you. <br/>2. Choose what you like to: Pick a restaurant and select the items you'd like to order. You may search by restaurant name, cuisine type, dish name or by keyword.<br/>3.Checkout: Mention your exact delivery address, payment method and your cell number. Always make sure that you enter the correct cell number to help us contact you regarding your order, if needed. Now sit back, relax, and we’ll get your food delivered to your doorstep."],
    ["id"=>6, "question"=>"Why am I getting a message that a restaurant does not deliver to me?", "answer"=>"The delivery area depends on individual restaurants and tromboy.com has no influence on this. When you enter your delivery address on the homepage, you can browse through all the restaurants that can deliver to your doorstep."],
];
?>
@foreach($faqs as $faq)
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="{{ 'heading_'.$faq['id'] }}">
            <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#{{ 'collapse_'.$faq['id'] }}" aria-expanded="true" aria-controls="{{ 'collapse_'.$faq['id'] }}">
                    {{ $faq['question']}}
                </a>
            </h4>
        </div>
        <div id="{{ 'collapse_'.$faq['id'] }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="{{ 'heading_'.$faq['id'] }}">
            <div class="panel-body">
                {!! $faq['answer'] !!}
            </div>
        </div>
    </div>
@endforeach
