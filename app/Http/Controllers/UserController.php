<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function update_avatar(Request $request){

        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = Auth::user();

        $avatarName = $user->id.'_avatar'.time().'.'.request()->avatar->getClientOriginalExtension();

        $request->avatar->storeAs('avatars',$avatarName);

        $oldAvatar = $user->avatar;
        $user->avatar = $avatarName;
        $user->save();
        if($oldAvatar != 'user.png'){
            unlink(storage_path('app/public/avatars/'.$oldAvatar));
        }


        return back();

    }
}
