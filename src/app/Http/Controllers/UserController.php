<?php

namespace App\Http\Controllers;

use App\Events\UserIsSuspended;
use App\Models\Users;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'first_name' => 'required|max:255', 
            'last_name' => 'required|max:255', 
            'email' => 'required|string|max:255|email|unique:users',
            'password' => 'required|min:8|max:255',
            'role_id' => 'required|integer|exists:roles,role_id',
            'faculty_id' => 'required|integer|exists:faculties,faculty_id',
        ]);
        $user = new Users;
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        $user->role_id = $request->input('role_id');
        $user->password = Hash::make($request->input('password'));
        $user->faculty_id = $request->input('faculty_id');

        if($request->has('is_admin') && ($request->input('is_admin') == TRUE)) {
            $user->is_admin=TRUE;
        }else {
            $user->is_admin=FALSE;
        }

        if($request->has('is_suspended') && ($request->input('is_suspended') == TRUE)) {
            $user->is_suspended=TRUE;
        }else {
            $user->is_suspended=FALSE;
        }

        if($user->save()) {
            Session::flash('message', 'Successfully created user: ' .$user->first_name); 
            Session::flash('alert-class', 'alert-success');
            return redirect()->route('userManager');
        }else {
            Session::flash('message', 'Failed to create user'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('userManager');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'first_name' => 'required|max:255', 
            'last_name' => 'required|max:255', 
            'email' => 'required|string|max:255',
            'role_id' => 'required|integer|exists:roles,role_id',
            'faculty_id' => 'required|integer|exists:faculties,faculty_id',
        ]);
        $user = Users::find($id);
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        $user->role_id = $request->input('role_id');
        $user->faculty_id = $request->input('faculty_id');

        if($request->has('is_admin') && ($request->input('is_admin') == TRUE)) {
            $user->is_admin=TRUE;
        }else {
            $user->is_admin=FALSE;
        }

        if($request->has('is_suspended') && ($request->input('is_suspended') == TRUE)) {
            $user->is_suspended=TRUE;
        }else {
            $user->is_suspended=FALSE;
        }


        if($user->save()) {
            if ($user->is_suspended) {
                event(new UserIsSuspended($user));
            }
            Session::flash('message', 'Successfully updated user: ' .$user->first_name); 
            Session::flash('alert-class', 'alert-success');
            return redirect()->route('userManager');
        }else {
            Session::flash('message', 'Failed to update user'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('userManager');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Users::find($id)->exists()) {
            $user = Users::find($id);
            $user->deleted_at = Carbon::now('GMT-7');
            if ($user->save()) {
                Session::flash('message', 'Successfully deleted user: ' .$user->first_name); 
                Session::flash('alert-class', 'alert-success'); 
                return redirect()->route('userManager');
            }
        }
        Session::flash('message', 'Failed to delete user'); 
        Session::flash('alert-class', 'alert-danger'); 
        return redirect()->route('userManager');
    }
}