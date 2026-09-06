<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class SplashController extends Controller
{
    public function __invoke(): View
    {
        return view('splash');
    }
}
