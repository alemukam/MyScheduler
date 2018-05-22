<?php

namespace App\Http\Controllers;
use App;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function setLang(Request $request)
    {
        return redirect()->back() -> withCookie(cookie() -> forever('lang', $request -> input('lang')));
    }
}
