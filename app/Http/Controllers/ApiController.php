<?php

namespace App\Http\Controllers;

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
        $mobile = urlencode($request->mobile);
        $otp = rand(100000, 999999);
        $message = urlencode("OTP: $otp , Kindly use this for Login. TromBoy.com");
        //$res = file_get_contents("http://sms.hostingfever.in/sendSMS?username=spantech&message=$message&sendername=ONLINE&smstype=TRANS&numbers=$mobile&apikey=4d360261-78da-4d98-826c-d02a6771545c");
        otp::create(['mobile'=>$request->mobile,'otp'=>$otp, 'res'=>'1']);
        header("X-xotp: $otp");

        return 'sent';
    }

    public function api_check_otp()
    {
        $request = $this->get_parameters();
        $mobile = urlencode($request->mobile);
        $otp = otp::where('mobile', $mobile)->orderBy('created_at', 'desc')->first()->otp;
        $hash = md5($mobile.$otp.date('U'));

        if(Customer::where('mobile', $mobile)->first() == null)
            Customer::create(["mobile"=>$mobile, "hash"=>$hash]);
        else
            Customer::where('mobile', $mobile)->first()->update(["hash"=>$hash]);

        if ($otp == $request->otp)
            return json_encode(['status'=>"ok", 'hash'=>$hash]);
        else
            return 'invalid';
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

        $customer = Customer::create($data);

        return ['status'=>'ok'];
    }

}
