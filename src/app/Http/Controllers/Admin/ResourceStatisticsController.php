<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resources;
use App\Models\Resources_Desks;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResourceStatisticsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'isAdmin', 'isSuspended']);
    }

    public function index()
    {
        // $resourcesData = DB::table('resources_desks')
        // ->select(DB::raw("COUNT(*) as count"))
        // ->join('desks', 'resources_desks.desk_id', '=', 'desks.id')
        // ->join('booking_history', 'desks.id', '=', 'booking_history.desk_id')
        // ->groupBy(DB::raw('resources_desks.resource_id'))
        // ->pluck('count');

        $resourcesDesksData = DB::table('resources')
        ->select(DB::raw("COUNT(*) as count"))
        ->join('resources_desks', 'resources.resource_id', '=', 'resources_desks.resource_id')
        ->join('desks', 'resources_desks.desk_id', '=', 'desks.id')
        ->join('booking_history', 'desks.id', '=', 'booking_history.desk_id')
        ->groupBy(DB::raw('resources.resource_id'))
        ->pluck('count');

        $resourcesDesksCategories = DB::table('resources')
        ->select(DB::raw('resource_type'))
        ->join('resources_desks', 'resources.resource_id', '=', 'resources_desks.resource_id')
        ->join('desks', 'resources_desks.desk_id', '=', 'desks.id')
        ->join('booking_history', 'desks.id', '=', 'booking_history.desk_id')
        ->groupBy(DB::raw('resources.resource_id'))
        ->pluck('resource_type');

        $totalDesks = 0;
        foreach($resourcesDesksData as $count) {
            $totalDesks += $count;
        }

        $dataDesks = [];

        for($i = 0; $i < count($resourcesDesksData); $i++) {
            $dataDesks[$i]['name'] = $resourcesDesksCategories[$i];
            $dataDesks[$i]['y'] = round($resourcesDesksData[$i] / $totalDesks * 100, 1);
        }



        $resourcesRoomsData = DB::table('resources')
        ->select(DB::raw("COUNT(*) as count"))
        ->join('resources_rooms', 'resources.resource_id', '=', 'resources_rooms.resource_id')
        ->join('rooms', 'resources_rooms.room_id', '=', 'rooms.id')
        ->join('desks', 'rooms.id', '=', 'desks.room_id')
        ->join('booking_history', 'desks.id', '=', 'booking_history.desk_id')
        ->groupBy(DB::raw('resources.resource_id'))
        ->pluck('count');
 
        //dd($resourcesRoomsData);
        $resourcesRoomsCategories = DB::table('resources')
        ->select(DB::raw('resource_type'))
        ->join('resources_rooms', 'resources.resource_id', '=', 'resources_rooms.resource_id')
        ->join('rooms', 'resources_rooms.room_id', '=', 'rooms.id')
        ->join('desks', 'rooms.id', '=', 'desks.room_id')
        ->join('booking_history', 'desks.id', '=', 'booking_history.desk_id')
        ->groupBy(DB::raw('resources.resource_id'))
        ->pluck('resource_type');


        $totalRooms = 0;
        foreach($resourcesRoomsData as $count) {
            $totalRooms += $count;
        }

        $dataRooms = [];

        for($i = 0; $i < count($resourcesRoomsData); $i++) {
            $dataRooms[$i]['name'] = $resourcesRoomsCategories[$i];
            $dataRooms[$i]['y'] = round($resourcesRoomsData[$i] / $totalRooms * 100, 1);
        }
        
        

        //ddd($resourcesData);
        //ddd($resourcesData, $resourcesCategories, $data);

        return view('admin.usageStatisticsManagement.generalResourcesStatistics')->with('dataDesks', $dataDesks)->with('dataRooms', $dataRooms); //return view for Manager Policies
    }

    public function getFilterResources(Request $request) {
        $dateRange = $request->dateRange;
        $array = explode(' - ', $dateRange);
        $dateStart = $array[0];
        $dateEnd = $array[1];
        $date = new DateTime($dateStart);
        $dateStart = $date->format('Y-m-d H:i:s');
        $date2 = new DateTime($dateEnd);
        $dateEnd = $date2->format('Y-m-d H:i:s');

        $resourcesDesksData = DB::table('resources')
        ->select(DB::raw("COUNT(*) as count"))
        ->join('resources_desks', 'resources.resource_id', '=', 'resources_desks.resource_id')
        ->join('desks', 'resources_desks.desk_id', '=', 'desks.id')
        ->join('booking_history', 'desks.id', '=', 'booking_history.desk_id')
        ->whereBetween('booking_history.book_time_start', [$dateStart, $dateEnd])
        ->groupBy(DB::raw('resources.resource_id'))
        ->pluck('count');

        $resourcesDesksCategories = DB::table('resources')
        ->select(DB::raw('resource_type'))
        ->join('resources_desks', 'resources.resource_id', '=', 'resources_desks.resource_id')
        ->join('desks', 'resources_desks.desk_id', '=', 'desks.id')
        ->join('booking_history', 'desks.id', '=', 'booking_history.desk_id')
        ->whereBetween('booking_history.book_time_start', [$dateStart, $dateEnd])
        ->groupBy(DB::raw('resources.resource_id'))
        ->pluck('resource_type');

        $totalDesks = 0;
        foreach($resourcesDesksData as $count) {
            $totalDesks += $count;
        }

        $dataDesks = [];

        for($i = 0; $i < count($resourcesDesksData); $i++) {
            $dataDesks[$i]['name'] = $resourcesDesksCategories[$i];
            $dataDesks[$i]['y'] = round($resourcesDesksData[$i] / $totalDesks * 100, 1);
        }

        $resourcesRoomsData = DB::table('resources')
        ->select(DB::raw("COUNT(*) as count"))
        ->join('resources_rooms', 'resources.resource_id', '=', 'resources_rooms.resource_id')
        ->join('rooms', 'resources_rooms.room_id', '=', 'rooms.id')
        ->join('desks', 'rooms.id', '=', 'desks.room_id')
        ->join('booking_history', 'desks.id', '=', 'booking_history.desk_id')
        ->whereBetween('booking_history.book_time_start', [$dateStart, $dateEnd])
        ->groupBy(DB::raw('resources.resource_id'))
        ->pluck('count');
 
        //dd($resourcesRoomsData);
        $resourcesRoomsCategories = DB::table('resources')
        ->select(DB::raw('resource_type'))
        ->join('resources_rooms', 'resources.resource_id', '=', 'resources_rooms.resource_id')
        ->join('rooms', 'resources_rooms.room_id', '=', 'rooms.id')
        ->join('desks', 'rooms.id', '=', 'desks.room_id')
        ->join('booking_history', 'desks.id', '=', 'booking_history.desk_id')
        ->whereBetween('booking_history.book_time_start', [$dateStart, $dateEnd])
        ->groupBy(DB::raw('resources.resource_id'))
        ->pluck('resource_type');


        $totalRooms = 0;
        foreach($resourcesRoomsData as $count) {
            $totalRooms += $count;
        }

        $dataRooms = [];

        for($i = 0; $i < count($resourcesRoomsData); $i++) {
            $dataRooms[$i]['name'] = $resourcesRoomsCategories[$i];
            $dataRooms[$i]['y'] = round($resourcesRoomsData[$i] / $totalRooms * 100, 1);
        }

        return response()->json([$dataDesks, $dataRooms],200);
    }
}