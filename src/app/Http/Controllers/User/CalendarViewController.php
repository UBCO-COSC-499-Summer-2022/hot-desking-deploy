<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Bookings;
use App\Models\Buildings;
use App\Models\Campuses;
use App\Models\Desks;
use App\Models\Floors;
use App\Models\RoleRoom;
use App\Models\Resources;
use App\Models\Resources_Desks;
use App\Models\Roles;
use App\Models\Rooms;
use App\Models\User;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CalendarViewController extends Controller
{
    // We'll need to use the current room ID with multiple functions
    protected $roomId;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        // Server-side validation for search.blade.php form
        $this->validate($request, [
            // 'campus_id'=>'required|integer|exists:campuses,id',
            // 'building_id'=>'required|integer|exists:buildings,id',
            // 'floor_id'=>'required|integer|exists:floors,id',
            'room_id'=>'required|integer|exists:rooms,id',
        ]);
        $room_id = (int)$request->input('room_id');
        // Save the current room ID to use for the occupancy validator later
        $this->roomId = $room_id;
        $currentRoom = Rooms::find($room_id);

        if($request->ajax()) {   
            $data = Bookings::whereDate('book_time_start', '>=', $request->start)
                    ->whereDate('book_time_end',   '<=', $request->end)
                    ->get(['id',  'book_time_start', 'book_time_end']);
            return response()->json($data); //send the data in json format
        }

        $user=User::find(Auth::id());
    
        $userMonthlyBookingCount = Bookings::where('user_id',Auth::id())->get()->count(); 

        $role_info=Roles::find($user->role_id);  // this one contain info from role table
        $campuses=Campuses::all();
        $buildings=Buildings::all();
        $floors=Floors::all();
        $rooms=Rooms::all();

        foreach ($rooms as $key => $room) {
            //if they exist in role rooms, do the following
            if (RoleRoom::where("room_id", $room->id)->exists()){
                //check if role_id = user_role_id where room_id = current room id
                if (!RoleRoom::where("room_id", $room->id)->where("role_id", Auth::user()->role_id)->exists()){
                    //if true, remove the room
                    // $rooms->forget($key);
                    unset($rooms[$key]);
                    // dd('room deleted');
                }
            }
        //if they do not exist in role_rooms, do nothing.
        }
        $rooms = $this->resetKeys($rooms);
        // dd($rooms);

        $desks=Desks::all();
        $allBooking=array();
        $resources_array=$currentRoom-> resources;
            // $resources_desks = Resources_Desks::all();
            // foreach($resources_desks as $resource) {
            //     dd($resource->resources);
            // }
            
            // dd(   $resources_array);
            $desks_resources = [];
            foreach( $currentRoom->desks as $desk){
                $desks_resources[] = $desk->resources;
                foreach( $desk->users as $booking){
                $color=null;
                if( $booking->pivot->user_id == $user->id){
                    $color='#3CB371'; //user own booking has difference color
                }
                $allBooking[]=[
                    "id"=> $booking->pivot->id,
                    "user_id"  =>  $booking->pivot->user_id,
                    "desk_id"  =>  $booking->pivot->desk_id,
                    "start" =>  $booking->pivot ->book_time_start,
                    "end" =>  $booking->pivot ->book_time_end,
                    "color" => $color,
                ];
            }
            }
            // dd($request -> all());
            $message=$request->input("message");
            return view('user.calendar')->with("allBooking",$allBooking)->with("campuses",$campuses)
            ->with("buildings",$buildings)->with("floors",$floors)->with("rooms",$rooms)->with("desks",$desks)->with("user",$user)->with('currentRoom', $currentRoom)
            ->with('resources_array',  $resources_array)->with('desks_resources', $desks_resources)->with('role_info', $role_info)->with('userMonthlyBookingCount', $userMonthlyBookingCount)
            ->with('message', $message);
            }
    


    public function calendarEvents(Request $request) {
        $user=User::find(Auth::id());
        // $desk=Desks::find(1);
        switch ($request->type) {
            case 'create':
                if (RoleRoom::where("room_id", $request->room_id)->exists()){
                    if (!RoleRoom::where("room_id", $request->room_id)->where("role_id", Auth::user()->role_id)->exists()){
                        return response('', 500);
                        break;
                    }
                }
                // Get the room occupancy maximum and percentage
                $roomPolicyOccupationLimit = $this->getRoomPolicyOccupationLimit();
                $maxRoomOccupancy = Rooms::where('id', $request->room_id)->value('occupancy');

                // Get all bookings from the current room on the current date
                // TODO: Narrow the query to grab only bookings from the current room
                $currentRoomBookings = Bookings::whereDate('book_time_start', Carbon::now()->format("Y-m-d"))->orWhere('desk_id', '!=', $request->desk_id)->get();
                
                // Set up the DateTime variables for DatePeriod
                $requestStartDate = new DateTime(date($request->book_time_start)); 
                $requestEndDate = new DateTime(date($request->book_time_end)); 
                $requestEndDate = $requestEndDate->modify( '+1 minute' );

                // This variable will determine how long the intervals are for iterating over a range of time
                $requestTimeInterval = new DateInterval("PT15M");

                // Create the time range to iterate over
                $requestTimeRange = new DatePeriod($requestStartDate, $requestTimeInterval, $requestEndDate, $option = 1);

                // Holds the booking IDs of all concurrent bookings
                $foundBookings = [];

                // Used to hold the midpoint of a time range
                $timeRangeIntervalMidpoint = null;

                // Keep track of the number of concurrent bookings. 
                $concurrentBookings = 0;

                // Create a flag to check if the user is allowed to create the booking
                $ableToBook = true;
                
                // Iterate through our time range in 15 minute chunks
                foreach ($requestTimeRange as $requestTimeChunk) {
                    // Find the (rough) midpoint of the requestTimeChunk variable
                    $timeRangeIntervalMidpoint = $requestTimeChunk->modify( '-6 minute' );

                    foreach ($currentRoomBookings as $booking) {
                        // Check if the midpoint is between the book_start_date and book_end_date of the current booking item
                        $bookingStart = new DateTime(date($booking->book_time_start));
                        $bookingEnd = new DateTime(date($booking->book_time_end));

                        // Check if the midpoint falls within the range of the current booking item
                        if (($timeRangeIntervalMidpoint >= $bookingStart) && ( $timeRangeIntervalMidpoint <= $bookingEnd)) {
                            // Check if the current booking item has already been found to be a concurrent booking
                            if (!in_array($booking->id, $foundBookings)) {
                                $concurrentBookings++;
                                // Mark the current booking item as a concurrent booking for future foreach iterations
                                array_push($foundBookings, $booking->id);
                            }
                        }

                        // Check if the number of concurrent bookings exceeds the maximum room capacity
                        if ($concurrentBookings + 1 > ($roomPolicyOccupationLimit * $maxRoomOccupancy)) {
                            Log::debug("No longer able to book!");
                            $ableToBook = false;
                            break;
                        }
                    }

                    // If we are ever unable to book, stop examining the request and break out of the loop
                    // Check if the number of concurrent bookings exceeds the maximum room capacity
                    if (!$ableToBook) {
                        Log::debug("No longer able to book for real!");
                        break;
                    }
                }

                if ($ableToBook) {
                    // Create a new Booking event. Only do this step once we have verified the room can hold one
                    $event=new Bookings;
                    $event->user_id=$user->id;
                    // $event->user_id=$request->user_id;
                    $event->desk_id=$request->desk_id;
                    $event->book_time_start=$request->book_time_start;
                    $event->book_time_end=$request->book_time_end;
                    $event->save();
                    Log::debug(response()->json($event));
                    return response()->json($event);// sending the data back to ajax
                    break;
                } else {
                    // Return nothing, causing the Ajax response to fail
                    $event=new Bookings;
                    $event->status="500";
                    $event->save();
                    return response()->json($event);
                    break;
                }
            case 'delete':
                $event = Bookings::find($request->id)->delete();
                return response()->json($event);
                break;
            default:
                break;
            }
        }

    public function update(Request $request ,$id){       
        $booking = Bookings::find($id);
        if(! $booking) {
            return response()->json([
                'error' => 'Unable to locate the event'
            ], 404);
        }
        $booking->update([
            'book_time_start' => $request->book_time_start,
            'book_time_end' => $request->book_time_end,
            'desk_id' => $request->updated_desk_id
        ]);
        return response()->json('Event updated');
    }

    public function getRoomPolicyOccupationLimit() {
        $percentage = DB::table('policy_occupation_limit')->where('id', 1)->value('percentage');
        return $percentage / 100;
    }

    public function isDeskBookedOnCurrentDate($deskId) {
        $currentDay = Carbon::now()->format('d');
        $isBooked = (Bookings::where('desk_id', '=', $deskId)->whereDay('book_time_start', $currentDay)->exists());
        return $isBooked;
    }
    public function resetKeys($array) {
        $newArray = [];
        // Reset Keys for High-charts
        $i = 0;
        foreach ($array as $a) {
            $newArray[$i] = $a;
            $i++;
        }
        return $newArray;
    }
}
