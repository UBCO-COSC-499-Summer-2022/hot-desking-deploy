<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Roles;
use App\Models\User;

class UserManagerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'isAdmin', 'isSuspended']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $users = User::paginate(10);
        return view('admin.userManagement.userManager')->with('users', $users);
    }

    public function viewUser($user_id)
    {
        $user = User::find($user_id);
        return view('admin.userManagement.viewUser')->with('user',$user);
    }

    public function editUser($user_id)
    {
        $user = User::find($user_id);
        $roles = Roles::all();
        $faculties = Faculty::all();
        $departments = Department::all();
        return view('admin.userManagement.editUser')->with('user',$user)->with('roles',$roles)->with('faculties', $faculties)->with('departments', $departments);
    }

    public function addUser() 
    {
        $roles = Roles::all();
        $faculties = Faculty::all();
        $departments = Department::all();
        return view('admin.userManagement.addUser')->with('roles',$roles)->with('faculties',$faculties)->with('departments', $departments);
    }

}