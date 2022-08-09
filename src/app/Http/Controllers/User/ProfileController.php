<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ProfileController extends Controller
{
    //
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = User::find(Auth::id());
        $roles = Roles::all();
        return view('user.profile')->with('user',$user)->with('roles', $roles);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|string|min:1|max:255',
            'last_name' => 'required|string|min:1|max:255',
            'role_id' => 'required|integer',
        ]);
        $user = User::find(Auth::id());
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->role_id = $request->input('role_id');
        if($request->input('role_id')==4|$request->input('role_id')==5){
            $user->supervisor = $request->input('supervisor');
        }else{
            $user->supervisor = null;
        }
        if($user->save()){
            //if save is successful
            Session::flash('message', 'Successfully updated profile'); 
            Session::flash('alert-class', 'alert-success');
            return redirect()->route('profile');
        }
        //if save failed
        Session::flash('message', 'Failed to update profile'); 
        Session::flash('alert-class', 'alert-danger');
        return redirect()->route('profile');
    }
}
