<?php

namespace App\Http\Controllers;

use App\Category;
use App\City;
use App\Coupon;
use App\Customer;
use App\wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Order;
use App\otp;
use App\Product;
use App\Restaurant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Razorpay\Api\Api;

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
        $this->SendSMS($mobile, $message);
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
        $cashback = 0;
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
                $dis = $gtotal*($coupon->percent/100);
                if($coupon->return_type == 'discount')
                {
                    if($dis>$coupon->max_amount)
                        $gtotal -= $coupon->max_amount;
                    else
                        $gtotal -= $dis;
                }
                elseif($coupon->return_type == 'cashback'){
                    if($dis > $coupon->max_amount)
                        $cashback = $coupon->max_amount;
                    else
                        $cashback = $dis;
                }
            }
        }
        $gtotal+=$delivery_fee+$packing_fee;

        $cart_data = json_encode($cart);

        $customer = Customer::find(Auth::user()->id);
        if(isset($request->c_name) && $request->c_name != '') $customer->name = $request->c_name;
        if(isset($request->c_email) && $request->c_email != '') $customer->email = $request->c_email;
        if(isset($request->address) && $request->address != '') $customer->address = $request->address;
        $customer->save();

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
            "status"        =>  "WFRA",
        ]);

        $api = new ApiController();
        $customer = Auth::user();

        /*
         * Notify Restaurant
         */
        $confirm_link = $api->confirm_order_link($order->id);
        $restaurant_message = "NEW:".$order->id.", Name:".substr($customer->name, strpos($customer->name, ' '))."(".$customer->mobile."), ".$order->address.", Cart: [";
        for($i=0; $i<count($cart); $i++)
            $restaurant_message .= $cart[$i]['title'].'-'.$cart[$i]['quantity'].', ';
        $restaurant_message .= "] Amt: ".$gtotal.". To confirm order send '68J8D conf ".$order->id."' to 9220592205 or ".$confirm_link;
        $this->SendSMS($restaurant->contact_no, $restaurant_message);
        // $this->callr($restaurant->contact_no, "You have got a new order, TromBoy");
        $api = new ApiController();
        //$this->callr($restaurant->contact_no, "You have got a new order, TromBoy");
        Mail::send('emails.restaurant.new_order', ['order' => $order, 'confirm_link'=>$confirm_link, 'cart'=>$cart], function ($m) use ($order) {
            $m->to($order->restaurant->email, $order->restaurant->name)->subject("New Order Details");
        });

        if($request->payment_id != 'wallet'){
            $wallet_amt = $request->wallet_amt;

            $api = new Api('rzp_test_FMKzS7xs08EwP5', 'MtWbDKF84Ak4DqrD6tcuaBHw');
            $payment = $api->payment->fetch($request->payment_id);
            $payment->capture(array('amount' => ($gtotal-$wallet_amt)*100));

            wallet::create([
                "type"      =>  "added",
                'capture'   =>  "success",
                'mode'      =>  $payment->method.' - '.($payment->wallet),
                'amount'    =>  ($gtotal-$wallet_amt),
                'order_id'  =>  $order->id,
                'user_id'   =>  Auth::user()->id,
                'restaurant_id'=> $request->restaurant_id,
            ]);

            wallet::create([
                "type"      =>  "paid_for_order",
                'capture'   =>  "success",
                'mode'      =>  "wallet",
                'amount'    =>  $gtotal,
                'order_id'  =>  $order->id,
                'user_id'   =>  Auth::user()->id,
                'restaurant_id'=> $request->restaurant_id,
            ]);
        }else{
            wallet::create([
                "type"      =>  "paid_for_order",
                'capture'   =>  "success",
                'mode'      =>  "wallet",
                'amount'    =>  $gtotal,
                'order_id'  =>  $order->id,
                'user_id'   =>  Auth::user()->id,
                'restaurant_id'=> $request->restaurant_id,
            ]);
        }

        if($cashback > 0)
        {
            wallet::create([
                "type"      =>  "cashback_recieved",
                'capture'   =>  "success",
                'mode'      =>  "system",
                'amount'    =>  round($cashback, 0),
                'order_id'  =>  $order->id,
                'user_id'   =>  Auth::user()->id,
                'restaurant_id'=> $request->restaurant_id,
            ]);
        }

        //$user_message = urlencode("Dear ".Auth::user()->name.", Your Order has been recieved, Order No: ".$order->id.". TromBoy.com");

        return redirect()->route('order.view', ['id'=>$order->id])->with(['orderplaced'=>'success']);
    }
}
