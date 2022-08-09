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

    public function index()
    {
        // $bookingTimeData = BookingHistory::select(DB::raw("COUNT(*) as count"))
        // ->groupBy(DB::raw("book_time_start"))
        // ->pluck('count');

        $bookingTimeData = DB::table('booking_history')
        ->select(DB::raw('COUNT(*) as count'))     
        ->join('desks', 'desks.id', '=', 'booking_history.desk_id')
        ->join('rooms', 'rooms.id', '=', 'desks.room_id')
        ->where('rooms.id', '=', 1)
        ->whereBetween('booking_history.book_time_start', ['2020-07-28 15:27:00', '2024-07-28 15:35:00'])
        ->groupBy(DB::raw('booking_history.book_time_start'))
        ->pluck('count');   

        $bookingTimeCategories = DB::table('booking_history')
        ->select(DB::raw('booking_history.book_time_start'))     
        ->join('desks', 'desks.id', '=', 'booking_history.desk_id')
        ->join('rooms', 'rooms.id', '=', 'desks.room_id')
        ->where('rooms.id', '=', 1)
        ->whereBetween('booking_history.book_time_start', ['2020-07-28 15:27:00', '2024-07-28 15:35:00'])
        ->groupBy(DB::raw('booking_history.book_time_start'))
        ->pluck('booking_history.book_time_start'); 

        $bookingTimeCategoriesTimes = [];
        $i = 0;
        
        foreach($bookingTimeCategories as $times) {
            $date = new DateTime($times);
            $bookingTimeCategoriesTimes[$i] = $date->format('H:i');
            $i++;
        }

        //dd($bookingTimeCategoriesTimes);

        //dd($bookingTimeData);
        // $bookingTimeData = BookingHistory::select(DB::raw("COUNT(*) as count"))
        // ->groupBy(DB::raw(date('h:i',strtotime("book_time_start"))))
        // ->pluck('count');

        //dd($bookingTimeData);
        // $timeCategories = ["00:00" => 0, "00:15" => 0, 
        // "00:30" => 0, "00:45" => 0,
        // "01:00" => 0, "01:15" => 0, 
        // "01:30" => 0, "01:45" => 0,
        // "02:00" => 0, "02:15" => 0, 
        // "02:30" => 0, "02:45" => 0,
        // "03:00" => 0, "03:15" => 0, 
        // "03:30" => 0, "03:45" => 0,
        // "04:00" => 0, "04:15" => 0, 
        // "04:30" => 0, "04:45" => 0,
        // "05:00" => 0, "05:15" => 0, 
        // "05:30" => 0, "05:45" => 0,
        // "06:00" => 0, "06:15" => 0, 
        // "06:30" => 0, "06:45" => 0,
        // "07:00" => 0, "07:15" => 0, 
        // "07:30" => 0, "07:45" => 0,
        // "08:00" => 0, "08:15" => 0, 
        // "08:30" => 0, "08:45" => 0,
        // "09:00" => 0, "09:15" => 0, 
        // "09:30" => 0, "09:45" => 0,
        // "10:00" => 0, "10:15" => 0, 
        // "10:30" => 0, "10:45" => 0,
        // "11:00" => 0, "11:15" => 0, 
        // "11:30" => 0, "11:45" => 0,
        // "12:00" => 0, "12:15" => 0, 
        // "12:30" => 0, "12:45" => 0,
        // "13:00" => 0, "13:15" => 0, 
        // "13:30" => 0, "13:45" => 0,
        // "14:00" => 0, "14:15" => 0, 
        // "14:30" => 0, "14:45" => 0,
        // "15:00" => 0, "15:15" => 0, 
        // "15:30" => 0, "15:45" => 0,
        // "16:00" => 0, "16:15" => 0, 
        // "16:30" => 0, "16:45" => 0,
        // "17:00" => 0, "17:15" => 0, 
        // "17:30" => 0, "17:45" => 0,
        // "18:00" => 0, "18:15" => 0, 
        // "18:30" => 0, "18:45" => 0,
        // "19:00" => 0, "19:15" => 0, 
        // "19:30" => 0, "19:45" => 0,
        // "20:00" => 0, "20:15" => 0, 
        // "20:30" => 0, "20:45" => 0,
        // "21:00" => 0, "21:15" => 0, 
        // "21:30" => 0, "21:45" => 0,
        // "22:00" => 0, "22:15" => 0, 
        // "22:30" => 0, "22:45" => 0,
        // "23:00" => 0, "23:15" => 0, 
        // "23:30" => 0, "23:45" => 0
        //  ];



        // $bookingTimeData = BookingHistory::all();
        // //dd($bookingTimeData);
        // foreach($timeCategories as $k => $times) {
        //     foreach($bookingTimeData as $time) {
        //         if(!strcmp(date_format(Carbon::parse($time->book_time_start),'H:i'), $k)) {
        //             $timeCategories[$k] += 1;
        //         }  
        //     }
        // }
       
        // $i = 0;
        // $newBooking_count = [];
        // foreach ($timeCategories as $time => $book_count) {
        //     $newBooking_count[$i] = $timeCategories[$time];
        //     $i++;
        // }

        //dd($timeCategories);
        //dd($bookingTimeData);




        //dd($bookingTimeData);
       

        //dd($timesCategories);
        //ddd($bookingTimeCategoriesDate);

        // $bookingTimeCategories = [];

        // for($i = 0; $i < count($bookingTimeCategoriesDate); $i++) {
        //     $bookingTimeCategories = date("H:i:s" => 0, $bookingTimeCategoriesDate[2][0]);
        // }

        //$bookingTimeCategories = date("H:i" => 0, $bookingTimeCategoriesDate[0][0]);
        //ddd($bookingTimeCategories);
        //return view('admin.usageStatisticsManagement.bookingTimeStatistics')->with('newBooking_count', $newBooking_count);
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
        ->select(DB::raw('COUNT(*) as count'))     
        ->join('desks', 'desks.id', '=', 'booking_history.desk_id')
        ->join('rooms', 'rooms.id', '=', 'desks.room_id')
        ->where('rooms.id', '=', $roomId)
        ->whereBetween('booking_history.book_time_start', [$dateStart, $dateEnd])
        ->groupBy(DB::raw('booking_history.book_time_start'))
        //->orderBy('booking_history.book_time_start', 'asc')
        ->pluck('count');   

        $bookingTimeCategories = DB::table('booking_history')
        ->select(DB::raw('booking_history.book_time_start'))     
        ->join('desks', 'desks.id', '=', 'booking_history.desk_id')
        ->join('rooms', 'rooms.id', '=', 'desks.room_id')
        ->where('rooms.id', '=', $roomId)
        ->whereBetween('booking_history.book_time_start', [$dateStart, $dateEnd])
        ->groupBy(DB::raw('booking_history.book_time_start'))
        //->orderBy('booking_history.book_time_start', 'asc')
        ->pluck('booking_history.book_time_start'); 

        $bookingTimeCategoriesTimes = [];
        $i = 0;
        
        foreach($bookingTimeCategories as $times) {
            $date = new DateTime($times);
            $bookingTimeCategoriesTimes[$i] = $date->format('h:ia');
            $i++;
        }

        return response()->json([$bookingTimeData, $bookingTimeCategoriesTimes],200);
    }


}