<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use Illuminate\Http\Request;

class RolesManagerController extends Controller
{
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
        $roles = Roles::paginate(10);
        return view('admin.roles.rolesManager')->with('roles',$roles);
    }

    public function viewRole($id)
    {
        $role = Roles::find($id);
        return view('admin.roles.viewRole')->with('role',$role);

    }

    public function editRole($id)
    {   
        $role = Roles::find($id);
        return view('admin.roles.editRole')->with('role',$role);
    }


    public function createRole()
    {
        return view('admin.roles.createRole');
    }
}
