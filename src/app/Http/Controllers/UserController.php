<?php

namespace App\Http\Controllers;

use App\Models\Users;
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
            'name' => 'required|max:255', 
            'email' => 'required|max:255',
            'password' => 'required|min:8|max:255'
        ]);
        $user = new Users;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));

        if($request->has('is_admin')) {
            $user->is_admin=TRUE;
        }else {
            $user->is_admin=FALSE;
        }

        if($user->save()) {
            Session::flash('message', 'Successfully created user: ' .$user->name); 
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
            'name' => 'required|max:255', 
            'email' => 'required|max:255',
        ]);
        $user = Users::find($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');

        if($request->has('is_admin')) {
            $user->is_admin=TRUE;
        }else {
            $user->is_admin=FALSE;
        }

        if($user->save()) {
            Session::flash('message', 'Successfully updated user: ' .$user->name); 
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
            if ($user->delete()) {
                Session::flash('message', 'Successfully deleted user: ' .$user->name); 
                Session::flash('alert-class', 'alert-success'); 
                return redirect()->route('userManager');
            }
        }
        Session::flash('message', 'Failed to delete user'); 
        Session::flash('alert-class', 'alert-danger'); 
        return redirect()->route('userManager');
    }
}