<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buildings;
use App\Models\Campuses;
use App\Models\Floors;
use App\Models\Roles;
use App\Models\Rooms;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

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
        ->join('users', 'roles.role_id', '=', 'users.role_id')
        ->join('booking_history', 'users.id', '=', 'booking_history.user_id')
        ->groupBy(DB::raw('roles.role'))
        ->pluck('roles.role');
        
        $campuses = Campuses::all();
        $buildings = Buildings::all();
        $floors = Floors::all();
        $rooms = Rooms::all();
        
        $room_id = Session::get('room_id');
        $floor_id = Session::get('floor_id');
        $building_id = Session::get('building_id');
        $campus_id = Session::get('campus_id');
        

        return view('admin.usageStatisticsManagement.rolesStatistics')->with('roleData',$roleData)->with('roleCategories', $roleCategories)->with('campuses', $campuses)->with('buildings', $buildings)->with('floors', $floors)->with('rooms', $rooms)->with('floor_id', $floor_id)->with('building_id', $building_id)->with('campus_id', $campus_id)->with('room_id', $room_id); 
    }

    public function getFilterRoles(Request $request){

        $dateRange = $request->dateRange;
        $array = explode(' - ', $dateRange);
        $dateStart = $array[0];
        $dateEnd = $array[1];
        $date = new DateTime($dateStart);
        $dateStart = $date->format('Y-m-d H:i:s');
        $date2 = new DateTime($dateEnd);
        $dateEnd = $date2->format('Y-m-d H:i:s');

        $roomId = $request->roomId;

        // $roleData = DB::table('roles')
        // ->select(DB::raw('COUNT(*) as count'))     
        // ->join('users', 'roles.role_id', '=', 'users.role_id')
        // ->join('booking_history', 'users.id', '=', 'booking_history.user_id')
        // ->whereBetween('booking_history.book_time_start', [$dateStart, $dateEnd])
        // ->groupBy(DB::raw('roles.role'))
        // ->pluck('count');    
        
        $roleData = DB::table('roles')
        ->select(DB::raw('COUNT(*) as count'))     
        ->join('users', 'roles.role_id', '=', 'users.role_id')
        ->join('booking_history', 'users.id', '=', 'booking_history.user_id')
        ->join('desks', 'desks.id', '=', 'booking_history.desk_id')
        ->join('rooms', 'rooms.id', '=', 'desks.room_id')
        ->where('rooms.id', '=', $roomId)
        ->whereBetween('booking_history.book_time_start', [$dateStart, $dateEnd])
        ->groupBy(DB::raw('roles.role'))
        ->pluck('count');    

        // // $roleCategories = DB::table('roles')
        // // ->select(DB::raw('roles.role'))
        // // ->pluck('roles.role');

        $roleCategories = DB::table('roles')
        ->select(DB::raw('roles.role'))     
        ->join('users', 'roles.role_id', '=', 'users.role_id')
        ->join('booking_history', 'users.id', '=', 'booking_history.user_id')
        ->join('desks', 'desks.id', '=', 'booking_history.desk_id')
        ->join('rooms', 'rooms.id', '=', 'desks.room_id')
        ->where('rooms.id', '=', $roomId)
        ->whereBetween('booking_history.book_time_start', [$dateStart, $dateEnd])
        ->groupBy(DB::raw('roles.role'))
        ->pluck('roles.role'); 

        // $totalRoles = 0;
        // foreach($roleData as $count) {
        //     $totalRoles += $count;
        // }

        // $dataRoles = [];

        // for($i = 0; $i < count($roleData); $i++) {
        //     $dataRoles[$i]['name'] = $roleData[$i];
        // }

        


        return response()->json([$roleData, $roleCategories],200);

    }
}