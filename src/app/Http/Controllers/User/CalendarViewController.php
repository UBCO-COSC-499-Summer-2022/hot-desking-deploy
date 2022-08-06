<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Bookings;
use App\Models\Buildings;
use App\Models\Campuses;
use App\Models\Desks;
use App\Models\Floors;
use App\Models\Resources;
use App\Models\Resources_Desks;
use App\Models\Roles;
use App\Models\Rooms;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarViewController extends Controller
{
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
            'campus_id'=>'required|integer|exists:campuses,id',
            'building_id'=>'required|integer|exists:buildings,id',
            'floor_id'=>'required|integer|exists:floors,id',
            'room_id'=>'required|integer|exists:rooms,id',
        ]);
        $room_id = (int)$request->input('room_id');
        
        $currentRoom = Rooms::find($room_id);

        if($request->ajax()) {   
            $data = Bookings::whereDate('book_time_start', '>=', $request->start)
                    ->whereDate('book_time_end',   '<=', $request->end)
                    ->get(['id',  'book_time_start', 'book_time_end']);
            return response()->json($data); //send the data in json format
        }

        $user=User::find(Auth::id());
    
    $your_booking_count = Bookings::where('user_id',Auth::id())->get()->count(); // to do, need to  get the booking number within the week
    

        $role_info=Roles::find($user->role_id);  // this one contain info from role table
        $campuses=Campuses::all();
        $buildings=Buildings::all();
        $floors=Floors::all();
        $rooms=Rooms::all();
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

            return view('user.calendar')->with("allBooking",$allBooking)->with("campuses",$campuses)
            ->with("buildings",$buildings)->with("floors",$floors)->with("rooms",$rooms)->with("desks",$desks)->with("user",$user)->with('currentRoom', $currentRoom)
            ->with('resources_array',  $resources_array)->with('desks_resources', $desks_resources)->with('role_info', $role_info)->with('your_booking_count', $your_booking_count);
            }
    


    public function calendarEvents(Request $request) {
        //    dd($request->all());
        $user=User::find(Auth::id());
        // $desk=Desks::find(1);
        switch ($request->type) {
            case 'create':
                $event=new Bookings;
                $event->user_id=$user->id;
                // $event->user_id=$request->user_id;
                $event->desk_id=$request->desk_id;
                $event->book_time_start=$request->book_time_start;
                $event->book_time_end=$request->book_time_end;
                $event->save();
                //  dd(response()->json($event));
                return response()->json($event);// sending the data back to ajax
                break;
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
}
