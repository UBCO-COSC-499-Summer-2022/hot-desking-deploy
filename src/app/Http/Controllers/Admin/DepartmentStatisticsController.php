<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buildings;
use App\Models\Campuses;
use App\Models\Faculty;
use App\Models\Floors;
use App\Models\Rooms;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DepartmentStatisticsController extends Controller
{

    public function index() {


        
        $campuses = Campuses::all();
        $buildings = Buildings::all();
        $floors = Floors::all();
        $rooms = Rooms::all();
        
        $room_id = Session::get('room_id');
        $floor_id = Session::get('floor_id');
        $building_id = Session::get('building_id');
        $campus_id = Session::get('campus_id');
        
        return view('admin.usageStatisticsManagement.departmentStatistics')->with('campuses', $campuses)->with('buildings', $buildings)->with('floors', $floors)->with('rooms', $rooms)->with('floor_id', $floor_id)->with('building_id', $building_id)->with('campus_id', $campus_id)->with('room_id', $room_id);  
    }

    public function getFilterDepartments(Request $request){

    {
        $dateRange = $request->dateRange;
        $array = explode(' - ', $dateRange);
        $dateStart = $array[0];
        $dateEnd = $array[1];
        $date = new DateTime($dateStart);
        $dateStart = $date->format('Y-m-d H:i:s');
        $date2 = new DateTime($dateEnd);
        $dateEnd = $date2->format('Y-m-d H:i:s');

        $roomId = $request->roomId;

        // $departmentData = User::select(DB::raw("COUNT(*) as count"))
        // ->groupBy(DB::raw("faculty_dept"))
        // ->pluck('count');

        // $departmentData = User::select(DB::raw("COUNT(*) as count"))
        // ->join('departments', 'users.department_id', '=', 'departments.department_id')
        // ->join('booking_history', 'users.id', '=', 'booking_history.user_id')
        // ->groupBy(DB::raw("users.department_id"))
        // ->pluck('count');

        $departmentData = DB::table('departments')
        ->select(DB::raw('COUNT(*) as count'))     
        ->join('users', 'departments.department_id', '=', 'users.department_id')
        ->join('booking_history', 'users.id', '=', 'booking_history.user_id')
        ->join('desks', 'desks.id', '=', 'booking_history.desk_id')
        ->join('rooms', 'rooms.id', '=', 'desks.room_id')
        ->where('rooms.id', '=', $roomId)
        ->whereBetween('booking_history.book_time_start', [$dateStart, $dateEnd])
        ->groupBy(DB::raw('departments.department'))
        ->pluck('count');   


        // $departmentCategories = DB::table('users')
        // ->join('departments', 'users.department_id', '=', 'departments.department_id')
        // ->join('booking_history', 'users.id', '=', 'booking_history.user_id')
        // ->groupBy(DB::raw('department'))
        // ->pluck('department');

        $departmentCategories = DB::table('departments')
        ->select(DB::raw('departments.department'))     
        ->join('users', 'departments.department_id', '=', 'users.department_id')
        ->join('booking_history', 'users.id', '=', 'booking_history.user_id')
        ->join('desks', 'desks.id', '=', 'booking_history.desk_id')
        ->join('rooms', 'rooms.id', '=', 'desks.room_id')
        ->where('rooms.id', '=', $roomId)
        ->whereBetween('booking_history.book_time_start', [$dateStart, $dateEnd])
        ->groupBy(DB::raw('departments.department'))
        ->pluck('departments.department'); 


        return response()->json([$departmentData, $departmentCategories],200);
    }
}
}