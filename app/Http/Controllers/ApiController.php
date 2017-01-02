<?php

namespace App\Http\Controllers;

use App\Area;
use App\City;
use App\Customer;
use App\otp;
use App\Restaurant;
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
        //$res = file_get_contents("http://sms.hostingfever.in/sendSMS?username=spantech&message=$message&sendername=ONLINE&smstype=TRANS&numbers=$mobile&apikey=4d360261-78da-4d98-826c-d02a6771545c");
        otp::create(['mobile'=>$request['mobile'],'otp'=>$otp, 'res'=>'1']);
        header("X-xotp: $otp");

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

    public function remove_hours($tmp)
    {
        unset($tmp->delivery_hours);
        unset($tmp->pickup_hours);
        unset($tmp->dinein_hours);
        $tmp->cuisines = json_decode($tmp->cuisines, true);
        return $tmp;
    }
}
