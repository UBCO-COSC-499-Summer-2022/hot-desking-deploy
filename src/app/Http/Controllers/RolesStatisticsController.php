<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RolesStatisticsController extends Controller
{

    public function index()
    {


        $roleData = DB::table('roles')
        ->select(DB::raw('COUNT(*) as count'))     
        ->join('users', 'roles.role_id', '=', 'users.role_id')
        ->join('booking_history', 'users.id', '=', 'booking_history.user_id')
        ->groupBy(DB::raw('roles.role'))
        ->pluck('count');       

        $roleCategories = DB::table('roles')
        ->select(DB::raw('roles.role'))
        ->pluck('roles.role');

          
        return view('admin.usageStatisticsManagement.rolesStatistics')->with('roleData',$roleData)->with('roleCategories', $roleCategories); 
    }
}