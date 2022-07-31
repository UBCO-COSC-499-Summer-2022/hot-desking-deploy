<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacultyStatisticsController extends Controller
{

    public function index()
    {
        // $departmentData = User::select(DB::raw("COUNT(*) as count"))
        // ->groupBy(DB::raw("faculty_dept"))
        // ->pluck('count');

        $facultyData = User::select(DB::raw("COUNT(*) as count"))
        ->join('faculties', 'users.faculty_id', '=', 'faculties.faculty_id')
        ->join('booking_history', 'users.id', '=', 'booking_history.user_id')
        ->groupBy(DB::raw("users.faculty_id"))
        ->pluck('count');

        $facultyCategories = DB::table('users')
        ->join('faculties', 'users.faculty_id', '=', 'faculties.faculty_id')
        ->join('booking_history', 'users.id', '=', 'booking_history.user_id')
        ->groupBy(DB::raw('faculty'))
        ->pluck('faculty');
        


        return view('admin.usageStatisticsManagement.facultyStatistics')->with('facultyData',$facultyData)->with('facultyCategories', $facultyCategories); 
    }
}