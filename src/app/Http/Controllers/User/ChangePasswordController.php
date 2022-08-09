<?php

#app\Http\Controllers\HomeController.php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;
use App\User;

class ChangePasswordController extends Controller
{
    public function showChangePasswordGet() {
        return view('user.changePassword');
    }

    public function changePasswordPost(Request $request) {
        // die dump for test
        //ddd(hash::make($request->get('new-password')));
        if (!(Hash::check($request->get('current-password'), auth()->user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error","Your current password does not matches with the password.");
        }

        if(strcmp($request->get('current-password'), $request->get('new-password')) == 0){
            // Current password and new password same
            return redirect()->back()->with("error","New Password cannot be same as your current password.");
        }

        $validatedData = $request->validate([
            'current-password' => 'required',
            'new-password' => 'required|string|min:8|confirmed',
        ]);

        //Change Password
        $user = auth()->user();
        $user->password = hash::make($request->get('new-password'));
        $user->save();

        #return redirect()->back()->with("success","Password successfully changed!");
        return view('user.profile');
    }
}