<?php

#app\Http\Controllers\HomeController.php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use Illuminate\Support\Facades\Session;

class ChangeEmailController extends Controller
{
    public function showChangeEmailGet() {
        return view('user.changeEmail');
    }

    public function changeEmailPost(Request $request) {

        $this->validate($request,[
            'new-email' => 'required|string|min:8',
            'new-email-confirm' =>'required|string|min:8|same:new-email',
        ]);

        if (auth()->user()->email != $request->input("new-email")){
            auth()->user()->newEmail($request->input("new-email"));
            Session::flash('message', 'An confirmation email has been sent to your new email'); 
            Session::flash('alert-class', 'alert-warning');
            return redirect()->route('changeEmailGet');
        }else{
            Session::flash('message', 'The new email must be different from the old email'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('changeEmailGet');
        }

        Session::flash('message', 'Failed to change email'); 
        Session::flash('alert-class', 'alert-danger');
        return redirect()->route('changeEmailGet');
    }
}