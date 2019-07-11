<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CommonController extends Controller
{
    public function chooseLanguage(Request $request)
    {
        Session::put('locale', $request->lang);
        return $request->lang;
    }
}
