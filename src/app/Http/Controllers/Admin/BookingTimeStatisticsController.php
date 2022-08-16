<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingHistory;
use App\Models\Buildings;
use App\Models\Campuses;
use App\Models\Floors;
use App\Models\Rooms;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class BookingTimeStatisticsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'isAdmin', 'isSuspended']);
    }

    public function index()
    {
        $campuses = Campuses::all();
        $buildings = Buildings::all();
        $floors = Floors::all();
        $rooms = Rooms::all();
        
        $room_id = Session::get('room_id');
        $floor_id = Session::get('floor_id');
        $building_id = Session::get('building_id');
        $campus_id = Session::get('campus_id');
        return view('admin.usageStatisticsManagement.bookingTimeStatistics')->with('campuses', $campuses)->with('buildings', $buildings)->with('floors', $floors)->with('rooms', $rooms)->with('floor_id', $floor_id)->with('building_id', $building_id)->with('campus_id', $campus_id)->with('room_id', $room_id); 

    }

    public function getFilterBookingTimes(Request $request) {

        $dateRange = $request->dateRange;
        $array = explode(' - ', $dateRange);
        $dateStart = $array[0];
        $dateEnd = $array[1];
        $date = new DateTime($dateStart);
        $dateStart = $date->format('Y-m-d H:i:s');
        $date2 = new DateTime($dateEnd);
        $dateEnd = $date2->format('Y-m-d H:i:s');

        $roomId = $request->roomId;
        
        $bookingTimeData = DB::table('booking_history')
        ->join('desks', 'desks.id', '=', 'booking_history.desk_id')
        ->join('rooms', 'rooms.id', '=', 'desks.room_id')
        ->where('rooms.id', '=', $roomId)
        ->whereBetween('booking_history.book_time_start', [$dateStart, $dateEnd])
        ->get();

        $bookingTimeCategoriesTimes = [];
        $i = 0;

        foreach($bookingTimeData as $times) {
            $date = new DateTime($times->book_time_start);
            $bookingTimeCategoriesTimes[$i] = $date->format('h:ia');
            $i++;
        }

        $timeCategories = ["12:00am", "12:15am", 
        "12:30am", "12:45am",
        "01:00am", "01:15am", 
        "01:30am", "01:45am",
        "02:00am", "02:15am", 
        "02:30am", "02:45am",
        "03:00am", "03:15am", 
        "03:30am", "03:45am",
        "04:00am", "04:15am", 
        "04:30am", "04:45am",
        "05:00am", "05:15am", 
        "05:30am", "05:45am",
        "06:00am", "06:15am", 
        "06:30am", "06:45am",
        "07:00am", "07:15am", 
        "07:30am", "07:45am",
        "08:00am", "08:15am", 
        "08:30am", "08:45am",
        "09:00am", "09:15am", 
        "09:30am", "09:45am",
        "10:00am", "10:15am", 
        "10:30am", "10:45am",
        "11:00am", "11:15am", 
        "11:30am", "11:45am",
        "12:00pm", "12:15pm", 
        "12:30pm", "12:45pm",
        "01:00pm", "01:15pm", 
        "01:30pm", "01:45pm",
        "02:00pm", "02:15pm", 
        "02:30pm", "02:45pm",
        "03:00pm", "03:15pm", 
        "03:30pm", "03:45pm",
        "04:00pm", "04:15pm", 
        "04:30pm", "04:45pm",
        "05:00pm", "05:15pm", 
        "05:30pm", "05:45pm",
        "06:00pm", "06:15pm", 
        "06:30pm", "06:45pm",
        "07:00pm", "07:15pm", 
        "07:30pm", "07:45pm",
        "08:00pm", "08:15pm", 
        "08:30pm", "08:45pm",
        "09:00pm", "09:15pm", 
        "09:30pm", "09:45pm",
        "10:00pm", "10:15pm", 
        "10:30pm", "10:45pm",
        "11:00pm", "11:15pm", 
        "11:30pm", "11:45pm",
         ];

        $bookingTimesSorted = [];
        for($i=0; $i < count($timeCategories); $i++) {
            for($j=0; $j < count($bookingTimeCategoriesTimes); $j++) {
                if($bookingTimeCategoriesTimes[$j] == $timeCategories[$i]) {
                    array_push($bookingTimesSorted, $bookingTimeCategoriesTimes[$j]);
                }
            }
        }

        $countBookings = array_count_values($bookingTimesSorted);

        $keys = array_keys($countBookings);

        

        $count = array_values($countBookings);

        return response()->json([$count, $keys],200);

    }
}