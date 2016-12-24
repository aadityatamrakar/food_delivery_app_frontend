<?php

namespace App\Http\Controllers;

use App\Coupon;
use Carbon\Carbon;

class CouponController extends Controller
{
    public function check($code, $customer, $gtotal)
    {
        $coupon = Coupon::where('code', $code)->first();
        if (Carbon::now()->between(Carbon::parse($coupon->valid_from), Carbon::parse($coupon->valid_till))) {
            if (($coupon->new_only == 'yes' && count($customer->orders) == 0) || $coupon->new_only == 'no') {
                if(count($customer->orders->where('coupon', (string)$coupon->id)) < $coupon->times){
                    if($gtotal >= $coupon->min_amt){
                        $coupon->status = 'ok';
                        return $coupon;
                    }else{
                        return ["status" => 'error', 'error' => 'min_amt', 'min_amt'=>$coupon->min_amt];
                    }
                }else{
                    return ["status" => 'error', 'error' => 'times_exceeded'];
                }
            }else{
                return ["status" => 'error', 'error' => 'new_only'];
            }
        }else {
            return ["status" => 'error', 'error' => 'expired'];
        }
    }
}
