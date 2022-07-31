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

class UserBookingsController extends Controller
{
    //
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        //$bookings = Bookings::where('user_id',Auth::id())->get();
        $user = User::find(Auth::id());
        $bookings = $user->desks;
        return view('user.bookings')->with("bookings",$bookings);
    }
    
    public function cancel($booking_id)
    {
        $bookings = Bookings::where('user_id',Auth::id())->get();
        /** check if booking exists in database */
        if(Bookings::find($booking_id)->exists()){
            /**get booking */
            $booking=Bookings::find($booking_id);
            if($booking->delete()){
                Session::flash('message', 'Successfully deleted booking');
                Session::flash('alert-class', 'alert-success');
                return redirect()->route('bookings')->with("bookings",$bookings);
            }
        }
        Session::flash('message', 'Failed to delete booking');
        Session::flash('alert-class', 'alert-danger');
        return redirect()->route('bookings')->with("bookings",$bookings);
    }

}
