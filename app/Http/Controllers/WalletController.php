<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class WalletController extends Controller
{
    public function index()
    {
        return view('wallet.index');
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
}
