<?php

namespace App\Http\Controllers;

use App\Category;
use App\City;
use App\Coupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Order;
use App\otp;
use App\Product;
use App\Restaurant;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{

    public function cart_check(Request $request)
    {
        $cart = json_decode($request->cart, true);

        foreach($cart as $product){
            $p = Product::where('id', $product['id'])->first();
            if($p != null) $c = Category::where('id', $p->category_id)->first();
            if($c != null) $r = Restaurant::where('id', $c->restaurant_id)->first();
            if((isset($r) && $r != null) && $r->id != $request->restaurant_id) return ['status'=>'error'];
            break;
        }

        return ['status'=>'ok'];
    }

    public function checkout_first(Request $request)
    {
        $coupon_data = '[]';
        $user = Auth::user();
        $c = new CouponController();
        $gtotal = 0;
        $restaurant = Restaurant::where('id', $request->restaurant_id)->first();
        $cart = json_decode($request->cart, true);
        $type = $request->type;

        for($i=0; $i<count($cart); $i++)
        {
            $gtotal += $cart[$i]['quantity']*Product::where('id', $cart[$i]['id'])->first()->price;
            $cart[$i]['price'] = Product::where('id', $cart[$i]['id'])->first()->price;
        }

        if($request->type == 'delivery' && $gtotal < $restaurant->min_delivery_amt) return back()->withInput()->with(['type'=>'danger', 'info'=>"You are Rs. ".($restaurant->min_delivery_amt-$gtotal)." away from min order."]);
        if($request->coupon != null) $coupon_data = json_encode($c->check($request->coupon, $user, $gtotal));
        $cart_data = json_encode($cart);
        return view('cart.index', compact(['cart_data', 'coupon_data', 'type']));
    }

    public function checkout_otp()
    {
        $mobile = urlencode(Auth::user()->mobile);
        $otp = rand(10000, 99999);
        $message = urlencode("OTP: $otp , Kindly use this for Order Confirmation. TromBoy.com");
        //$res = file_get_contents("http://sms.hostingfever.in/sendSMS?username=spantech&message=$message&sendername=ONLINE&smstype=TRANS&numbers=$mobile&apikey=4d360261-78da-4d98-826c-d02a6771545c");
        otp::create(['mobile'=>$mobile,'otp'=>$otp, 'res'=>'1']);
        header('otp: '.$otp);

        return 'sent';
    }

    public function checkout_confirm(Request $request)
    {
        $type = $request->type;
        $mobile = Auth::user()->mobile;
//        $otp = otp::where('mobile', $mobile)->orderBy('created_at', 'desc')->first()->otp;
        $gtotal = 0;
        $c = new CouponController();
        $restaurant = Restaurant::where('id', $request->restaurant_id)->first();
        $cart = json_decode($request->cart, true);
        $packing_fee = $type!='dinein'?$restaurant->packing_fee:0;
        $delivery_fee = $type=='delivery'?$restaurant->delivery_fee:0;
        $coupon_applied = false;

//        if ($otp != $request->otp_c)
//            return redirect()->route('restaurant.view', ["id"=>$restaurant->id, 'name'=>$restaurant->title])->with(['info'=>'Invalid OTP', 'type'=>'danger']);

        for($i=0; $i<count($cart); $i++)
        {
            $gtotal += $cart[$i]['quantity']*Product::where('id', $cart[$i]['id'])->first()->price;
            $cart[$i]['price'] = Product::where('id', $cart[$i]['id'])->first()->price;
        }
        if($type =='delivery' && $gtotal < $restaurant->min_delivery_amt) return redirect()->route('restaurant.view', ["id"=>$restaurant->id, 'name'=>$restaurant->title])->with(['type'=>'danger', 'info'=>"You are Rs. ".($restaurant->min_delivery_amt-$gtotal)." away from min order."]);
        if($request->coupon != null) {
            $coupon = $c->check($request->coupon, Auth::user(), $gtotal);
            if($coupon->status == 'ok'){
                $coupon_applied = true;
                if($coupon->return_type == 'discount')
                {
                    $dis = $gtotal*($coupon->percent/100);
                    if($dis>$coupon->max_amount)
                        $gtotal -= $coupon->max_amount;
                    else
                        $gtotal -= $dis;
                }
            }
        }
        $gtotal+=$delivery_fee+$packing_fee;

        // +TODO CASHBACK TO WALLET

        $cart_data = json_encode($cart);

        $order = Order::create([
            "user_id"       =>  Auth::user()->id,
            "restaurant_id" =>  $request->restaurant_id,
            "address"       =>  $request->address?:'',
            "deliver"       =>  $type,
            "cart"          =>  $cart_data,
            "city"          =>  City::where('id', $restaurant->city_id)->first()->name,
            "gtotal"        =>  $gtotal,
            "coupon"        =>  $coupon_applied?$coupon->id:null,
            "delivery_fee"  =>  $delivery_fee,
            "packing_fee"   =>  $packing_fee,
        ]);

        $user_message = urlencode("Dear ".Auth::user()->name.", Your Order has been recieved, Order No: ".$order->id.". TromBoy.com");
        $restaurant_message = urlencode("NEW ORDER: ".$order->id.", Name:".Auth::user()->name." Mob:".Auth::user()->mobile." Add:".$order->address." Cart: [");
        for($i=0; $i<count($cart); $i++)
            $restaurant_message .= urlencode('{'.$cart[$i]['title'].' ('.$cart[$i]['quantity'].')},');
        $restaurant_message .= urlencode("] Amt: ".$gtotal." TromBoy.com");

        //$res = file_get_contents("http://sms.hostingfever.in/sendSMS?username=spantech&message=$user_message&sendername=ONLINE&smstype=TRANS&numbers=$mobile&apikey=4d360261-78da-4d98-826c-d02a6771545c");
        //$res = file_get_contents("http://sms.hostingfever.in/sendSMS?username=spantech&message=$restaurant_message&sendername=ONLINE&smstype=TRANS&numbers=".urlencode($restaurant->contact_no)."&apikey=4d360261-78da-4d98-826c-d02a6771545c");

        return view('cart.confirm', compact('order'));
    }
}
