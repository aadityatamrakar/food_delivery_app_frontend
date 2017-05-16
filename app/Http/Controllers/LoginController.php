<?php

namespace App\Http\Controllers;

use App\Customer;
use App\otp;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return redirect()->route('home');
        //return view('home');
    }

    public function login(Request $request)
    {
        $customer = Customer::where('mobile', $request->mobile)->first();

        if($customer != null){
            Auth::login($customer);
            return redirect()->route('home');
//            if($request->pin == $customer->pin){
//                Auth::login($customer);
//                return redirect()->route('home');
//            }else{
//                return redirect()->route('login')->with(['info'=>"Invalid PIN", 'type'=>"warning"]);
//            }
        }else{
            return redirect()->route('login')->with(['info'=>"User not registered with this mobile no.", 'type'=>"warning"]);
        }
    }

    public function postOtp(Request $request)
    {

        $mobile = urlencode($request->mobile);
        $otp = rand(100000, 999999);
        $message = urlencode("Verification Code: $otp , TromBoy.com");
        //$this->SendSMS($mobile, $message);
        otp::create(['mobile'=>$request->mobile,'otp'=>$otp, 'res'=>'1']);
        //header('otp: '.$otp);

        return ['status'=>'ok'];
    }

    public function postRegister(Request $request)
    {
        $mobile = urlencode($request->mobile);
        $otp = otp::where('mobile', $mobile)->orderBy('created_at', 'desc')->first()->otp;

        if ($otp != $request->otp) {
            return ['status' => 'error', 'error' => 'invalid_otp'];
        }

        $validator = Validator::make($request->all(), [
            "name"          =>  'required',
            "mobile"        =>  'required|numeric|digits:10|unique:customer,mobile',
            "pin"           =>  'required|numeric|digits:4',
            "address"       =>  'required',
            "city"          =>  'required',
            "email"         =>  'email'
        ]);

        if ($validator->fails()) {
            return ['status' => 'error', 'error'=>$validator->errors()];
        }

        if(($customer = Customer::where('mobile', $mobile)->first()) == null){
            $customer = Customer::create([
                "name"      =>$request->name,
                "address"   =>$request->address,
                "city"      =>$request->city,
                "email"     =>$request->email?:'',
                'mobile'    =>$mobile,
                'pin'       =>$request->pin
            ]);
            Auth::login($customer);
            return ['status'=>'ok'];
        }else{
            return ['status'=>'error', 'error'=>'duplicate'];
        }
    }

    public function check_otp(Request $request)
    {
        $mobile = urlencode($request->mobile);
        $otp = otp::where('mobile', $mobile)->orderBy('created_at', 'desc')->first()->otp;

        if(($customer = Customer::where('mobile', $mobile)->first()) == null){
            $customer = Customer::create(['mobile'=>$mobile]);
        }

        if ($otp == $request->otp){
            Auth::login($customer);
            return ['status' => 'ok'];
        }else
            return ['status' => 'error', 'error'=>'invalid_otp'];
    }

    public function checkMobile(Request $request)
    {
        if(Customer::where('mobile', $request->mobile)->first() == null){
            return ['status'=>'ok'];
        }else{
            return ['status'=>'duplicate'];
        }
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('login')->with(["info"=>"Logged out successfully.", "type"=>"success"]);
    }
}
