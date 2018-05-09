<?php

namespace App\Http\Controllers;
use App;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function setLang(Request $request)
    {
        session(['lang' => $request -> input('lang')]);
        return redirect()->back(); 
    }
}
