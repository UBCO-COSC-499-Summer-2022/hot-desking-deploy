<?php

namespace App\Http\Controllers\User;

use App\Models\Bookings;
use App\Models\Buildings;
use App\Models\Campuses;
use App\Models\Desks;
use App\Models\Floors;
use App\Models\RoleRoom;
use App\Models\Rooms;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
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
    public function index()
    {
        // Records to populate the search dropdowns
        $campuses = Campuses::all();
        $buildings =  Buildings::all();
        $floors =  Floors::all();
        $rooms =  Rooms::all();

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
        $desks = Desks::all();

        return view('user.search')->with('campuses', $campuses)->with('buildings', $buildings)->with('floors', $floors)->with('rooms', $rooms)->with('desks', $desks);
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
