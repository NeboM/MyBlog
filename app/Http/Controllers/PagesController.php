<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\DB;

class PagesController extends Controller
{
    public function home(){
        return view('pages.home');
    }

    public function profile($id){
        $user = User::find($id, ['id','name', 'email','avatar']);
        return view('pages.profile')->with('user',$user);
    }

    public function login(){
        $users = User::all('email')->take(3);
        return view('auth.login')->with('users',$users);
    }
}
