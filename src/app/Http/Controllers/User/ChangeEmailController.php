<?php

namespace App\Http\Controllers\User;

use App\Models\Users;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ChangeEmailController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }

    public function showChangeEmailGet() {
        return view('user.changeEmail');
    }

    public function changeEmailPost(Request $request) {
        $this->validate($request,[
            'new-email' => 'required|string|min:8',
            'new-email-confirm' =>'required|string|min:8|same:new-email',
        ]);

        $user = User::find($request->input("user_id"));

        // Check if the new-email already exists in the user table in our database
        if (Users::where('email', $request->input("new-email"))->count() >= 1) {
            Session::flash('message', 'This email is already in use.'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('changeEmailGet');
        }

        if ($user->email != $request->input("new-email")) {
            $user->newEmail($request->input("new-email"));
            Session::flash('message', 'An confirmation email has been sent to your new email.'); 
            Session::flash('alert-class', 'alert-warning');
            return redirect()->route('changeEmailGet');
        } else {
            Session::flash('message', 'The new email must be different from the old email.'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('changeEmailGet');
        }

        Session::flash('message', 'Failed to change email.'); 
        Session::flash('alert-class', 'alert-danger');
        return redirect()->route('changeEmailGet');
    }    
}