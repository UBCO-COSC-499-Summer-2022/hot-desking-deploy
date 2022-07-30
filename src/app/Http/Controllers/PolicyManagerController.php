<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PolicyManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'isAdmin']);
    }

    public function index()
    {

        return view('admin.policyManagement.policyManager'); //return view for Manager Policies
    }
    public function createPolicy()
    {
        return view('admin.policyManagement.createPolicy');
    }
    public function viewPolicy()
    {
        return view('admin.policyManagement.viewPolicy');
    }
    public function editPolicy()
    {

        return view('admin.policyManagement.editPolicy');
    }
}
