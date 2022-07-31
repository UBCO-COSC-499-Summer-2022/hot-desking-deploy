<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsageStatisticsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'isAdmin', 'isSuspended']);
    }

    public function index()
    {

        return view('admin.usageStatisticsManagement.usageStatistics'); //return view for Manager Policies
    }
}