<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Register;

class RegisterController extends Controller
{
    public function main(Request $request)
    {
        $register = new Register();
        $resp = $register->main(strtoupper($request->input('username')), $request->input('password'), $request->input('cpassword'), $request->input('email'));
        $request->session()->flash($resp[0], $resp[1]);
        return redirect("/");

    }
}