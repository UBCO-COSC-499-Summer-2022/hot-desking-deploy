<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Bookings;
use App\Models\Desks;
use App\Models\User;
use Database\Factories\BookingsFactory;
use Illuminate\Support\Facades\DB;
use App\Models\Buildings;
use App\Models\Campuses;
use App\Models\Floors;
use App\Models\Rooms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ViewBookingController extends Controller
{
    //
       //
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
    public function index($booking_id)
    {
        $user = User::find(Auth::id());
        $booking = $user->desks()->wherePivot("id", $booking_id)->first();
        return view('user.viewBooking')->with("booking",$booking);
        // dd($booking);
        // if(Bookings::where('id', $booking_id)->exists()){
        //     // $booking=Bookings::find($booking_id);
        //     return view('user.viewBooking')->with("booking",$booking);
        // }
        // Session::flash('message', 'Failed to find booking'); 
        // Session::flash('alert-class', 'alert-danger');
        // return redirect()->route('bookings');
    }
}