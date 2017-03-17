<?php

namespace App\Http\Controllers;

class WebsiteController extends Controller
{

    public function support()
    {
        return view('website.support');
    }

    public function refundcancel()
    {
        return view('website.refundcancel');
    }

    public function privacypolicy()
    {
        return view('website.privacypolicy');
    }
}
