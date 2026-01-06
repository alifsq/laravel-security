<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $response = Auth::attempt([
            'email' => $request->get('email', 'wrong'),
            'password' => $request->get('password', 'wrong')
        ], true);

        Session::regenerate();
        if ($response) {
            return redirect("/users/current");
        }else{
            return "Wrong Crisidentials";
        }
    }

    public function current(){
        $user = Auth::user();
        if($user){
            return "Hello $user->name";
        }else{
            return redirect('/users/login');
        }
    }
}
