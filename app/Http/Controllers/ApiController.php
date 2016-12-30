<?php

namespace App\Http\Controllers;

use App\Area;
use App\City;
use App\Customer;
use App\otp;
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
}
