<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Log;
use Hash;

class AuthController extends Controller
{
    public function login()
    {
        return view("pages.login");
    }

    public function auth(Request $request){
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            Log::create([
                'id_user' => Auth::user()->id,
                'name' => "login"
            ]);
            return redirect()->route("tours");
        }
        return redirect()->route("login");
    }

    public function logout(){
        Log::create([
            'id_user' => Auth::user()->id,
            'name' => "logout"
        ]);
        Auth::logout();
        return redirect()->route("login");
    }
}
