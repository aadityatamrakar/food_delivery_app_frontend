<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WalletController extends Controller
{
    public function index()
    {
        $customer = Customer::find(Auth::user()->id);
        $bal = WalletController::balance($customer);
        return view('wallet.index', compact(['customer', 'bal']));
    }

    public function getStatus()
    {
        return 0;
    }
    
    public function getCoin($number)
    {
        $im = imagecreatefromstring(Storage::get('coin.png'));
        $black = imagecolorallocate($im, 0,0,0);
        imagettftext($im, 25, 0, 100, 100, $black, '', '245');
        header("content-type: image/png");
        imagepng($im);
    }

    public static function balance()
    {
        $customer = Customer::find(Auth::user()->id);

        $total_added = $customer->transactions->where('type', 'added')->sum('amount');
        $total_cashback = $customer->transactions->where('type', 'cashback_recieved')->sum('amount');
        $total_paid = $customer->transactions->where('type', 'paid_for_order')->sum('amount');
        $total_removed = $customer->transactions->where('type', 'removed')->sum('amount');

        return ($total_added+$total_cashback)-($total_paid+$total_removed);
    }
}
