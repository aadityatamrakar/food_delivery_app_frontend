<?php

namespace App\Http\Controllers;

use App\Area;
use App\Banner;
use App\City;
use App\Coupon;
use App\Customer;
use App\Leads;
use App\Stations;
use App\Train;
use App\wallet;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use App\Order;
use App\otp;
use App\Product;
use App\Restaurant;
use App\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Razorpay\Api\Api;

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
        $res = $this->SendSMS($mobile, $message);
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
//            "city"      =>"required",
            "address"   =>"required"
        ]);

        if($validator->fails())
            return ['status'=>'error', 'error'=>$validator->errors()];

        $customer = Customer::where('mobile', $data['mobile'])->first();
        $customer->name = $data['name'];
        $customer->email = $data['email'];
        $customer->address = $data['address'];
        $customer->city = 3;
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
        return Area::select(['id', 'name'])->whereNotNull('restaurant_id')->where([['city_id', $city], ['restaurant_id', '!=', '[]']])->get();
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
                $tmp = Restaurant::select('id', 'logo', 'name', 'address', 'speciality', 'cuisines', 'type', 'train_time', 'delivery_time', 'pickup_time', 'dinein_time', 'delivery_fee', 'min_delivery_amt', 'packing_fee', 'payment_modes', 'delivery_hours', 'pickup_hours', 'dinein_hours')->where('id', $res)->first();

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

        //return compact(['restaurants', 'closed']);
        return compact(['restaurants']);
    }
    public function get_restaurant_train($city)
    {
        $home = new HomeController();
        $city = City::where('name', $city)->first();
        $restaurant = Restaurant::select('id', 'logo', 'name', 'address', 'speciality', 'cuisines', 'type', 'train_time', 'delivery_fee', 'min_delivery_amt', 'packing_fee', 'payment_modes', 'train_hours')->where([['city_id', $city->id], ['train_time', '!=', 'null']])->get();
        $restaurants = [];
        $closed = [];
        if($restaurant != null) {
            foreach ($restaurant as $tmp) {
                if($home->check_hours($tmp->train_hours, $tmp)) $restaurants[] = $this->remove_hours($tmp);
                else $closed[] = $this->remove_hours($tmp);
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
        unset($tmp->train_hours);
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
        $mobile2 = isset($request['mobile2'])?$request['mobile2']:'';
        $remarks = isset($request['remarks'])?$request['remarks']:'';
        $customer = Customer::where('mobile', $mobile)->first();
        $gtotal = 0;
        $cashback = 0;
        $discount = 0;
        $c = new CouponController();
        $restaurant = Restaurant::where('id', $request['restaurant_id'])->first();
        $cart = $request['cart'];
        $payment_id = $request['payment_id'];
        $payment_amount = $request['payment_amount'];
        $wallet_amount = $request['wallet_amount'];
        settype($wallet_amount, 'integer');
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
                    $discount = $gtotal;
                    if($dis>$coupon->max_amount)
                        $gtotal -= $coupon->max_amount;
                    else
                        $gtotal -= $dis;
                    $discount -= $gtotal;
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
            "remarks"       =>  $remarks,
            "mobile2"       =>  $mobile2,
            "discount"      =>  $discount,
            "cashback"      =>  $cashback,
        ]);

        $amt_to_take = $gtotal;

        if($payment_id == 'wallet' || $wallet_amount != 0) {
            wallet::create([
                "type"          =>  "paid_for_order",
                'capture'       =>  "success",
                'mode'          =>  "wallet",
                'amount'        =>  $wallet_amount,
                'order_id'      =>  $order->id,
                'user_id'       =>  $customer->id,
                'restaurant_id' =>  $restaurant->id,
            ]);
            $amt_to_take = $gtotal-$wallet_amount;
        }else if($payment_id == 'COD') {
//            wallet::create([
//                "type"          =>  "paid_for_order",
//                'capture'       =>  "success",
//                'mode'          =>  "cash",
//                'amount'        =>  $wallet_amount,
//                'order_id'      =>  $order->id,
//                'user_id'       =>  $customer->id,
//                'restaurant_id' =>  $restaurant->id,
//            ]);
//            $amt_to_take = $gtotal;
        }else if($payment_id != 'wallet' && $payment_id != 'COD') {
            $api = new Api($this->razorpay['key'], $this->razorpay['secret']);
            $payment = $api->payment->fetch($payment_id);
            $payment->capture(array('amount' => ($payment_amount)*100));

            wallet::create([
                "type"          => "added",
                'reason'        => "APP",
                'capture'       => "success",
                'mode'          => $payment->method.' - '.($payment->wallet),
                'amount'        => $payment_amount,
                'order_id'      => $order->id,
                'user_id'       => $customer->id,
                'restaurant_id' => $restaurant->id,
            ]);

            wallet::create([
                "type"          => "paid_for_order",
                'reason'        => "APP",
                'capture'       => "success",
                'mode'          => "wallet",
                'amount'        => $gtotal-$wallet_amount,
                'order_id'      => $order->id,
                'user_id'       => $customer->id,
                'restaurant_id' => $restaurant->id,
            ]);
            $amt_to_take = 0;
        }

        $confirm_link = $this->confirm_order_link($order->id);
        $restaurant_message = $order->id.", Type: ".$order->deliver.", Name:".$customer->name."(".$customer->mobile.', '.$order->mobile2."), ".$order->address.", Cart: [";
        for($i=0; $i<count($cart); $i++)
            $restaurant_message .= $cart[$i]['title'].'-'.$cart[$i]['quantity'].', ';
        $restaurant_message .= "]. Click ".$confirm_link." to confirm. Amount to take: ".$amt_to_take.", Remarks: ".$remarks;
        $this->SendSMS($restaurant->contact_no, $restaurant_message);
        $this->callr($restaurant->contact_no, "You have got a new order, TromBoy");
        Mail::send('emails.restaurant.new_order', ['order' => $order, 'confirm_link'=>$confirm_link, 'cart'=>$cart], function ($m) use ($order) {
            $m->to($order->restaurant->email, $order->restaurant->name)->subject("New Order Details");
        });
        return $order;
    }
    public function order_status($id)
    {
        $order = Order::find($id);
        return ['status'=>$order->status];
    }
    public function all_orders()
    {
        $request = $this->get_parameters();
        $customer = Customer::where('mobile', $request['mobile'])->first();
        $hc = new HomeController();
        foreach($customer->orders as $index=>$order)
        {
            $order->restaurant->isDelivery = $hc->check_hours($order->restaurant->delivery_hours, $order->restaurant);
            $order->restaurant->isPickup = $hc->check_hours($order->restaurant->pickup_hours, $order->restaurant);
            $order->restaurant->isDinein = $hc->check_hours($order->restaurant->dinein_hours, $order->restaurant);
        }
        return $customer->orders;
    }
    public function wallet_summary()
    {
        $request = $this->get_parameters();
        $customer = Customer::where('mobile', $request['mobile'])->first();
        $bal = WalletController::balance($customer);
        $transaction = Collection::make($customer->transactions);
        return compact(['transaction', 'bal']);
    }
    public function wallet_balance()
    {
        $request = $this->get_parameters();
        $customer = Customer::where('mobile', $request['mobile'])->first();
        $bal = WalletController::balance($customer);
        return $bal;
    }
    public function test()
    {

    }
    public function test_call($m)
    {
        $login = 'spantechnologies_1';
        $password = 'wVAZcLFbFZ';

        $api = new \CALLR\API\Client();
        $api->setAuthCredentials($login, $password);

        $options = (object) [
            'url' => 'https://tromboy.com/api/v2/callback_url'
        ];

        $app = $api->call('apps.create', ['REALTIME10', 'tromboy', $options]);

        $target = (object) [
            'number' => '+91'.$m,
            'timeout' => 30
        ];

        $options = (object) [
            'cdr_field' => 'userData',
            'cli' => 'BLOCKED'
        ];

        return $result = $api->call('calls.realtime', [$app->hash, $target, $options]);
    }

    public function callback_url()
    {
        $request = $this->get_parameters();
        Storage::disk('local')->append('callr.txt', json_encode($request));

        if($request['call_status'] == 'UP' && $request['command_id'] == '0')
        {
            $data = [
                'command'=>"read",
                "command_id"=>1,
                "params"=>[
                    "media_id"=>"TTS|TTS_EN-GB_SERENA|Do you want to know about G S T, press one to get a callback",
                    "max_digits"=>1,
                    "attempts"=>1,
                    "timeout_ms"=>3000
                ],
            ];
        }else if($request['command_id'] == '1'){
            if($request['command_result'] == '1'){
                $data = [
                    'command'=>"play",
                    "command_id"=>2,
                    "params"=>[
                        "media_id"=>"TTS|TTS_EN-GB_SERENA|Thanks Confirmed",
                    ],
                ];
            }else{
                $data = [
                    'command'=>"hangup",
                    "command_id"=>3,
                    "params"=>(object)[],
                ];
            }
        }else{
            $data = [
                'command'=>"hangup",
                "command_id"=>3,
                "params"=>(object)[],
            ];
        }

        Storage::disk('local')->append('callr.txt', json_encode($data));

        return ($data);
    }
    public function confirm_order_link($id)
    {
        $data = ["conf", $id];
        $encryptedValue = Crypt::encrypt(json_encode($data));
        $longUrl = 'http://tromboy.com/api/v2/order_confirmation/'.$encryptedValue;
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
            return $this->confirm_by_restaurant($data[1], false);
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
                $this->SendSMS($order->user->mobile, $message);
                Mail::send('emails.order_details', ['order' => $order], function ($m) use ($order) {
                    $m->to($order->user->email, $order->user->name)->subject("Your Order Details");
                });
                $message = "You have just confirmed the order ID:".$id.'. Please Try to fullfil the order on time. TromBoy!';
                $this->SendSMS($restaurant->contact_no, $message);
                return $message;
            }else{
                return 'Order '.$id.' has already been confirmed.';
            }
        }
    }
    public function regen_pin()
    {
        $request = $this->get_parameters();
        $customer = Customer::where('mobile', $request['mobile'])->first();
        if($customer == null) return ['status'=>'error', 'error'=>"Mobile no. not registered."];
        $this->SendSMS($customer->mobile, 'Use PIN: '.$customer->pin.' to Login, You can change your PIN from profile section in the app. TromBoy');
        return ['status'=>"sent"];
    }
    public function banner_get()
    {
        return Banner::select('url')->get();
    }
    public function railway_trains($query)
    {
        return Train::select('train_no', 'train_name')->where('train_name', 'LIKE', '%'.$query.'%')->skip(0)->take(15)->get();
    }
    public function railway_stations($train_no)
    {
        if (($station_data = Stations::where('train_no', $train_no)->first()) != null){
            $this->check_station($station_data->data);
        }else{
            $client = new Client();
            $request = new \GuzzleHttp\Psr7\Request('GET', 'http://api.railwayapi.com/route/train/'.$train_no.'/apikey/jiwlgbos/');
            $promise = $client->sendAsync($request)->then(function ($response) {
                $data = json_decode($response->getBody(), true);
                Stations::create(['train_no'=>$data['train']['number'], 'data'=>json_encode($data)]);
                $this->check_station($data);
            });
            $promise->wait();
        }
    }
    public function railway_pnr($pnr)
    {
        $client = new Client();
        $headers = [
            'Referer' => 'http://www.indianrail.gov.in/pnr_Enq.html',
            'Origin'=>'http://www.indianrail.gov.in',
            'Content-Type'=>'application/x-www-form-urlencoded',
            'User-Agent'=>'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36',
        ];
        $body = 'lccp_pnrno1='.$pnr.'&lccp_cap_value=24357&lccp_capinp_value=24357&submit=Please+Wait...';
        $request = new \GuzzleHttp\Psr7\Request('POST', 'http://www.indianrail.gov.in/cgi_bin/inet_pnstat_cgi_14350.cgi', $headers, $body);
        try{
            $promise = $client->sendAsync($request, ['connect_timeout' => 10])->then(function ($response) {
                if($response->getStatusCode() != 200){
                    return ['status'=>'error', 'error'=>"Server is Down"];
                }
                $html = $response->getBody();
//                if(strpos($html, 'Due to system overload, your request could not be processed. Please try sms.') != -1){
//                    return ['status'=>'error', 'error'=>"Server is Down"];
//                }
                $pkeys = ['sno', 'seat_no', 'status'];
                $keys= ['train_no', 'train_name', 'boarding_date', 'from', 'to', 'reserved_upto', 'boarding_point', 'class', 'passengers'];
                $data= []; $i=0; $tmp = $tmp2 = $pass = 0;
                while ($x = strpos($html, '<TD class="table_border_both">', $i))
                {
                    if($tmp != 8)
                        $data[$keys[$tmp]] = preg_replace('/\s+/', ' ', strip_tags(substr($html, $x, strpos($html, '</TD>', $x)-$x)));
                    else
                        $data[$keys[$tmp]][$pass][$pkeys[$tmp2]] = preg_replace('/\s+/', ' ', strip_tags(substr($html, $x, strpos($html, '</TD>', $x)-$x)));

                    $i = $x+1;
                    if($tmp<8) $tmp++;
                    elseif ($tmp2<2) $tmp2++;
                    elseif ($tmp2==2) {
                        $tmp2=0; $pass++;
                    }
                }

                if(isset($data['train_name']) && count($data) == count($keys)) $data['status'] = 'ok';
                else $data = ['status'=>'error', 'error'=>"Server Unavailable."];

                echo json_encode($data);
            });
            $promise->wait();
        }catch(\Exception $exception)
        {
            return ['status'=>'error', 'error'=>$exception->getMessage()];
        }
    }
    public function railway_live($train_no, $date)
    {
        $client = new Client();

        $request = new \GuzzleHttp\Psr7\Request('GET', 'http://api.railwayapi.com/live/train/'.$train_no.'/doj/'.$date.'/apikey/jiwlgbos/');
        $promise = $client->sendAsync($request)->then(function ($response) {
            $train_data = json_decode($response->getBody(), true);
            if($train_data['response_code'] != 200){
                return ['status'=>"error", 'error'=>"Train not running today."];
            }

            $stations = [];
            $home = new HomeController();
            $restaurants = [];
            $closed = [];

            $cs = $train_data['current_station']['no'];
            for($i=$cs; $i<count($train_data['route']); $i++)
            {
                if( ($city = City::where('name', $train_data['route'][$i]['station_']['name'])->first()) != null)
                {
                    if( count($tmp2 = Restaurant::where('city_id', $city->id)->whereNotNull('train_time')->get()) > 0)
                    {
                        foreach ($tmp2 as $tmp) {
                            if($home->check_hours($tmp->train_hours, $tmp, $train_data['route'][$i]['actarr_date'].' '.$train_data['route'][$i]['actdep']))
                            {

                                $check_time = Carbon::now();
                                $train_time = Carbon::parse($train_data['route'][$i]['actarr_date'].' '.$train_data['route'][$i]['actdep']);
                                $restaurant_ready = $check_time->addMinutes($tmp->train_time);
                                if($restaurant_ready->between(Carbon::now(), $train_time))
                                {
                                    $restaurants[$city->id][] = $this->remove_hours($tmp);
                                }
                            }
                        }
                        if(! count($restaurants)>0){
                            return ['status'=>"error", 'info'=>"Sorry, Currently no restaurant is delivering in this location."];
                        }else{
                            $train_data['route'][$i]['city_id'] = $city->id;
                            $stations[] = $train_data['route'][$i];
                        }
                    }
                }
            }
            echo json_encode(compact(['stations', 'restaurants']));
        });
        $promise->wait();
    }
    public function check_station($raw_data)
    {
        $data = is_array($raw_data)?$raw_data:json_decode($raw_data, true);
        if($data['train']['days'][date("w")]['runs'] == 'Y'){
            $stations = [];
            foreach ($data['route'] as $station){
                $stations[] = ['schdep'=>$station['schdep'], 'scharr'=>$station['scharr'],
                    'code'=>$station['code'], 'fullname'=>$station['fullname']];
            }
            echo json_encode($stations);
        }else{
            echo json_encode(['status'=>'error', 'error'=>"Train not running today."]);
        }
    }
    public function send_link_sms($mobile)
    {
        $message = "Click on this to download our app t3b.in/apk \n TromBoy";
        return $this->SendSMS($mobile, $message);
    }
    public function send_link_email($email)
    {
        Mail::send('emails.app_link', [], function ($m) use ($email) {
            $m->to($email, "")->subject("TromBoy App Download Link");
        });
        return 'sent!';
    }
    public function check() { return 'jai_mata_di'; }

    public function referral_page()
    {
        return view('referral.customer');
    }

    public function get_referral_otp(\Illuminate\Http\Request $request)
    {
        $v = Validator::make($request->all(), [
            "mobile"    =>  "required|unique:leads,mobile",
        ]);

        if($v->fails()){
            return ['status'=>"already_registered"];
        }else{
            $otp = rand(100, 999);
            $this->SendSMS($request->mobile, "Your OTP is ".$otp." by giving this otp to our promoter, you allow TromBoy Promotional messages.");
            return ['otp'=>$otp];
        }
    }

    public function referral_post(\Illuminate\Http\Request $request)
    {
        $this->validate($request, [
            "name"      =>  "required",
            "address"   =>  "required",
            "mobile"    =>  "required|unique:leads,mobile",
            "type"      =>  "required",
        ]);

        $leads = Leads::create($request->all());
        return ['status'=>"referral registered successfully."];
    }
}
