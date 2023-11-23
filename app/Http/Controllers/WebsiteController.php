<?php

namespace App\Http\Controllers;

class WebsiteController extends Controller
{
    /**
     * Main Website Controller
     */
    public function index()
    {
        return view('website.spa');
    }

    /**
     * Main Portal Controller
     */
    public function portal()
    {
        return view('portal.spa');
    }
}
