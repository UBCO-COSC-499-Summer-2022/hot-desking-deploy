<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class RoleController extends Controller
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
        // validate request input
        $this->validate($request, [
            'role' => 'required|max:30',
            'num_monthly_bookings' => 'required|integer',
            'frequency' => 'required|integer',
        ]);

        $role = new Roles;
        $role->role = $request->input('role');
        $role->num_monthly_bookings = $request->input('num_monthly_bookings');
        $role->frequency = $request->input('frequency');

        if ($role->save()) {
            Session::flash('message', 'Successfully created role: ' . $role->role);
            Session::flash('alert-class', 'alert-success');
            return redirect()->route('rolesManager');
        } else {
            Session::flash('message', 'Failed to create role');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('rolesManager');
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
        //
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
        // validate request input
        $this->validate($request, [
            'role' => 'required|max:30',
            'num_monthly_bookings' => 'required|integer',
            'frequency' => 'required|integer',
        ]);

        $role = Roles::find($id);
        $role->role = $request->input('role');
        $role->num_monthly_bookings = $request->input('num_monthly_bookings');
        $role->frequency = $request->input('frequency');

        if ($role->save()) {
            Session::flash('message', 'Successfully updated role: ' . $role->role);
            Session::flash('alert-class', 'alert-success');
            return redirect()->route('rolesManager');
        } else {
            Session::flash('message', 'Failed to updated role');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('rolesManager');
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
        // check if role exists in database
        if (Roles::find($id)->exists()) {
            // Get Role
            $role = Roles::find($id);
            if ($role->delete()) {
                Session::flash('message', 'Successfully deleted role: ' . $role->role);
                Session::flash('alert-class', 'alert-success');
                return redirect()->route('rolesManager');
            }
        }
        Session::flash('message', 'Failed to delete role');
        Session::flash('alert-class', 'alert-danger');
        return redirect()->route('rolesManager');
    }
}
