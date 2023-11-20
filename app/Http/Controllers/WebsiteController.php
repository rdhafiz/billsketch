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
}
