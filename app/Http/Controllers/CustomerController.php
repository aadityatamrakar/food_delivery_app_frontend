<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index()
    {
        return view('customer.profile');
    }

    public function postUpdate(Request $request)
    {
        $this->validate($request,[
            "name"  =>  "required",
            "email" =>  "email",
            "city"  =>  "required",
            "pincode"   =>  "required|numeric|digits:6",
            "address"   =>  "required",
        ]);

        $customer = Customer::where('mobile', Auth::user()->mobile)->first();
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->city = $request->city;
        $customer->pincode = $request->pincode;
        $customer->address = $request->address;
        $customer->save();

        return redirect()->route('home')->with(['info'=>'Details Updated.', 'type'=>"success"]);
    }

}
