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

    public function terms()
    {
        return view('website.termsconditions');
    }

    public function about()
    {
        return view('website.about');
    }

    public function contact()
    {
        return view('website.contact');
    }
}
