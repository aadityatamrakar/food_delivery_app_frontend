<?php

namespace App\Http\Controllers;

use App\Area;
use App\City;
use App\Coupon;
use App\Customer;
use App\wallet;
use Illuminate\Support\Facades\Request;
use App\Order;
use App\otp;
use App\Product;
use App\Restaurant;
use App\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function __construct()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: *");
    }
    public function get_parameters()
    {
        return json_decode(file_get_contents("php://input"), true);
    }
    public function api_postOtp()
    {
        $request = $this->get_parameters();

        $validator = Validator::make($request, [
            "name"      =>"required",
            "email"     =>"email",
            "mobile"    =>"required|numeric|digits:10|unique:customer,mobile",
            "pin"       =>"required|numeric|digits:4",
            "city"      =>"required",
            "address"   =>"required"
        ]);

        if($validator->fails())
            return ['status'=>'error', 'error'=>$validator->errors()];

        $mobile = urlencode($request['mobile']);
        $otp = rand(100000, 999999);
        $message = urlencode("Verification Code: $otp , TromBoy.com");
        $res = file_get_contents("http://sms.hostingfever.in/sendSMS?username=spantech&message=$message&sendername=ONLINE&smstype=TRANS&numbers=$mobile&apikey=4d360261-78da-4d98-826c-d02a6771545c");
        otp::create(['mobile'=>$request['mobile'],'otp'=>$otp, 'res'=>'1']);
        //header("X-xotp: $otp");

        return ['status'=>'sent'];
    }
    public function register()
    {
        $data = $this->get_parameters();

        $validator = Validator::make($data, [
            "name"      =>"required",
            "email"     =>"email",
            "mobile"    =>"required|numeric|digits:10|unique:customer,mobile",
            "pin"       =>"required|numeric|digits:4",
            "city"      =>"required",
            "address"   =>"required"
        ]);

        if($validator->fails())
            return ['status'=>'error', 'error'=>$validator->errors()];

        $mobile = urlencode($data['mobile']);
        $otp = otp::where('mobile', $mobile)->orderBy('created_at', 'desc')->first()->otp;

        if ($otp == $data['otp'])
            $customer = Customer::create($data);
        else
            return ['status'=>'invalid_otp'];

        return ['status'=>'ok'];
    }
    public function updateProfile()
    {
        $data = $this->get_parameters();

        $validator = Validator::make($data, [
            "name"      =>"required",
            "email"     =>"email",
            "mobile"    =>"required|numeric|digits:10",
            "city"      =>"required",
            "address"   =>"required"
        ]);

        if($validator->fails())
            return ['status'=>'error', 'error'=>$validator->errors()];

        $customer = Customer::where('mobile', $data['mobile'])->first();
        $customer->name = $data['name'];
        $customer->email = $data['email'];
        $customer->address = $data['address'];
        $customer->city = $data['city'];
        $customer->save();

        return ['status'=>'ok'];
    }
    public function updatePin()
    {
        $data = $this->get_parameters();

        $validator = Validator::make($data, [
            "pin"       =>"required|numeric|digits:4",
            "new_pin"   =>"required|numeric|digits:4",
            "mobile"    =>"required|numeric|digits:10",
        ]);

        if($validator->fails())
            return ['status'=>'error', 'error'=>$validator->errors()];

        $customer = Customer::where('mobile', $data['mobile'])->first();
        if($customer->pin == $data['pin']){
            $customer->pin = $data['new_pin'];
            $customer->save();
            return ['status'=>'ok'];
        }else{
            return ['status'=>'error', 'error'=>"old_pin_invalid"];
        }

    }
    public function check_coupon()
    {
        $data = $this->get_parameters();
        $gtotal = $data['gtotal'];
        $user = Customer::where('mobile', $data['m'])->first();
        $coupon = Coupon::select('min_amt', 'max_amount', 'percent', 'return_type', 'valid_from', 'valid_till', 'new_only')->where('code', $data['code'])->first();
        if($coupon != null){
            $c = new CouponController();
            return $c->check($data['code'], $user, $gtotal);
        }else {
            return ["status"=>'error', 'error'=>"invalid"];
        }
    }
    public function login()
    {
        $data = $this->get_parameters();
        $customer = Customer::where('mobile', $data['mobile'])->first();

        if($customer != null)
        {
            if($customer->pin == $data['pin']) return [
                'status'=>'ok',
                'name'  =>$customer->name,
                'city'  =>$customer->city,
                'email'  =>$customer->email,
                'address'  =>$customer->address,
                'token'  =>$customer->hash,
            ];
            else return ['status'=>'error', 'error'=>'pin_invalid'];
        }

        return ['status'=>'error', 'error'=>'no_mobile'];
    }
    public function get_city()
    {
        return City::select(['id', 'name'])->get();
    }
    public function get_area($city)
    {
        return Area::select(['id', 'name'])->where('city_id', $city)->get();
    }
    public function get_restaurant($area_id, $type)
    {
        $home = new HomeController();
        $area = Area::select('id', 'name', 'city_id', 'restaurant_id')->where('id', $area_id)->first();
        $restaurant = json_decode($area->restaurant_id, true);
        $restaurants = [];
        $closed = [];
        if($restaurant != null) {
            foreach ($restaurant as $res) {
                $tmp = Restaurant::select('id', 'logo', 'name', 'address', 'speciality', 'cuisines', 'type', 'delivery_time', 'pickup_time', 'dinein_time', 'delivery_fee', 'min_delivery_amt', 'packing_fee', 'payment_modes', 'delivery_hours', 'pickup_hours', 'dinein_hours')->where('id', $res)->first();

                if($type=='delivery' && $tmp->delivery_time!=null) {
                    if($home->check_hours($tmp->delivery_hours, $tmp)) $restaurants[] = $this->remove_hours($tmp);
                    else $closed[] = $this->remove_hours($tmp);
                }elseif($type=='pickup' && $tmp->pickup_time!=null) {
                    if($home->check_hours($tmp->pickup_hours, $tmp)) $restaurants[] = $this->remove_hours($tmp);
                    else $closed[] = $this->remove_hours($tmp);
                }elseif($type=='dinein' && $tmp->dinein_time!=null) {
                    if($home->check_hours($tmp->dinein_hours, $tmp)) $restaurants[] = $this->remove_hours($tmp);
                    else $closed[] = $this->remove_hours($tmp);
                }
            }
        }

        if(! count($restaurants)>0 && ! count($closed)>0){
            return ['status'=>"no_restaurant_open", 'info'=>"Sorry, Currently no restaurant is delivering in this location."];
        }

        return compact(['restaurants', 'closed']);
    }
    public function remove_hours($tmp)
    {
        unset($tmp->delivery_hours);
        unset($tmp->pickup_hours);
        unset($tmp->dinein_hours);
        $tmp->cuisines = json_decode($tmp->cuisines, true);
        return $tmp;
    }
    public function get_menu($restaurant_id)
    {
        $restaurant = Restaurant::where('id', $restaurant_id)->first();
        $menu = $restaurant->categories;
        foreach($menu as $index=>$category)
        {
            unset($menu[$index]['created_at']);
            unset($menu[$index]['updated_at']);
            unset($menu[$index]['restaurant_id']);
            $menu[$index]['products'] = $category->products;
            foreach($menu[$index]['products'] as $product){
                unset($product['created_at']);
                unset($product['updated_at']);
                unset($product['category_id']);
            }
        }
        return $menu;
    }
    public function place_order()
    {
        $request = $this->get_parameters();
        $type = $request['type'];
        $mobile = $request['mobile'];
        $address = $request['address'];
        $customer = Customer::where('mobile', $mobile)->first();
        $gtotal = 0;
        $cashback = 0;
        $c = new CouponController();
        $restaurant = Restaurant::where('id', $request['restaurant_id'])->first();
        $cart = $request['cart'];
        $packing_fee = $type!='dinein'?$restaurant->packing_fee:0;
        $delivery_fee = $type=='delivery'?$restaurant->delivery_fee:0;
        $coupon_applied = $request['coupon']!=null?true:false;
        for($i=0; $i<count($cart); $i++)
        {
            $gtotal += $cart[$i]['quantity']*Product::where('id', $cart[$i]['id'])->first()->price;
            $cart[$i]['price'] = Product::where('id', $cart[$i]['id'])->first()->price;
        }
        if($type =='delivery' && $gtotal < $restaurant->min_delivery_amt) return ['status'=>'error', 'error'=>'min_order_amt'];
        if($request['coupon'] != null) {
            $coupon = $c->check($request['coupon'], $customer, $gtotal);
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
                else if($coupon->return_type == 'cashback')
                {
                    if($dis > $coupon->max_amount)
                        $cashback = $coupon->max_amount;
                    else
                        $cashback = $dis;
                }
            }
        }
        $gtotal+=$delivery_fee+$packing_fee;
        $cart_data = json_encode($cart);

        $order = Order::create([
            "user_id"       =>  $customer->id,
            "restaurant_id" =>  $request['restaurant_id'],
            "address"       =>  $address?:'',
            "deliver"       =>  $type,
            "cart"          =>  $cart_data,
            "city"          =>  City::where('id', $restaurant->city_id)->first()->name,
            "gtotal"        =>  $gtotal,
            "coupon"        =>  $coupon_applied?$coupon->id:null,
            "delivery_fee"  =>  $delivery_fee,
            "packing_fee"   =>  $packing_fee,
            "status"        =>  "WFRA",
        ]);

        if($cashback > 0)
        {
            wallet::create([
                "type"      =>  "cashback_recieved",
                'capture'   =>  "success",
                'mode'      =>  "system",
                'amount'    =>  $cashback,
                'order_id'  =>  $order->id,
                'user_id'   =>  Auth::user()->id,
                'restaurant_id'=> $request->restaurant_id,
            ]);
        }

        $restaurant_message = "NEW:".$order->id.", Name:".substr($customer->name, strpos($customer->name, ' '))."(".$customer->mobile."), ".$order->address.", Cart: [";
        for($i=0; $i<count($cart); $i++)
            $restaurant_message .= $cart[$i]['title'].'-'.$cart[$i]['quantity'].', ';
        //$restaurant_message .= "] Amt: ".$gtotal.". To confirm order send '68J8D conf ".$order->id."' to 9220592205 or ".$this->confirm_order_link($order->id);
        //$this->SendSMS($restaurant->contact_no, $restaurant_message);
        $this->callr($restaurant->contact_no, "You have got a new order, TromBoy");
        return $order;
    }
    public function order_status($id)
    {
        $order = Order::find($id);
        return ['status'=>$order->status];
    }
    public function test()
    {
        $api = new \CALLR\API\Client();
        $api->setAuthCredentials('spantechnologies_1', 'wVAZcLFbFZ');
        $target = new \stdClass();
        $target->number = '+918989946073';
        $target->timeout = 30;

        $messages = [131, 132, 'TTS|TTS_EN-GB_SERENA|Hello world! how are you ? Tromboy.'];

        $options = new \stdClass();
        $options->cdr_field = 'userData';
        $options->cli = 'BLOCKED';
        $options->loop = 2;

        $result = $api->call('calls.broadcast_1', [$target, $messages, $options]);
        return $result;
    }
    public function confirm_order_link($id)
    {
        $data = ["conf", $id];
        $encryptedValue = Crypt::encrypt(json_encode($data));
        $longUrl = 'http://tromboy.com/api/order_confirmation/'.$encryptedValue;
        $url = $this->short_url($longUrl);
        return str_replace('https://', '', $url->id);
    }
    public function confirm_sms(){
        $parameters = explode(' ', strtolower($_POST['comments']));
        if($parameters[0] == 'conf'){
            $this->confirm_by_restaurant($parameters[1], $_POST['sender']);
        }
    }
    public function get_restaurant_confirm_link($hash)
    {
        $data = json_decode(Crypt::decrypt($hash));
        $order = Order::find($data[1]);
        return view('order.restaurant_confirmation', ["route"=>route('order_confirm_link', ['hash'=>$hash]), 'mobile'=>$order->restaurant->contact_no]);
    }
    public function restaurant_confirm_link($hash)
    {
        $data = json_decode(Crypt::decrypt($hash));
        if($data[0] == 'conf') {
            $order = Order::find($data[1]);
            if($_POST['digit'] == substr($order->restaurant->contact_no, 6, 4)){
                return $this->confirm_by_restaurant($data[1], false);
            }else{
                return 'Please check the entered mobile no.';
            }
        }
    }
    public function confirm_by_restaurant($id, $sender=true)
    {
        $order = Order::find($id);
        if($sender == false || ($order->restaurant->contact_no == substr($sender, 2)))
        {
            if($order->status == 'WFRA')
            {
                $order->status = 'PROC';
                $order->save();
                $restaurant = Restaurant::find($order->restaurant_id);
                if($order->deliver == 'delivery'){
                    $message = 'Hi, Your order will be delivered in approx. '.$order->restaurant->delivery_time .' mins. To follow up on your order '.$order->id.', call the restaurant directly at '.$order->restaurant->contact_no.' / '.$order->restaurant->contact_no_2;
                }else if($order->deliver == 'pickup'){
                    $message = "Hi, Your take-away time is approx. ".$order->restaurant->pickup_time .' mins. To follow up on your order '.$order->id.', call the restaurant directly at '.$order->restaurant->contact_no.' / '.$order->restaurant->contact_no_2;
                }else{
                    $message = "Hi, Your food will be ready in approx ".$order->restaurant->dinein_time .' mins. To follow up on your order '.$order->id.', call the restaurant directly at '.$order->restaurant->contact_no.' / '.$order->restaurant->contact_no_2;
                }
                //$this->SendSMS($order->restaurant->contact_no, $message);
                ////$this->SendSMS($order->user->mobile, $message);
                return $message = "You have just confirmed the order ID:".$id.'. Please Try to fullfil the order on time. TromBoy!';
            }else{
                return 'Order '.$id.' has already been confirmed.';
            }
        }
    }
}
