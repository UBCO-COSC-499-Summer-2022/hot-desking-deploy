<?php

namespace App\Http\Controllers\User;

use App\Models\Bookings;
use App\Http\Controllers\Controller;
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
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;
use App\Mail\SendMail;

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
        $bookings = $user->desks;//->sortBy(strtotime("pivot_book_time_start"),SORT_NUMERIC);
        $bookingsSorted= $bookings->sortBy(function($col) {
            // dd(strtotime($col->pivot->book_time_start));
            return strtotime($col->pivot->book_time_start);
        })->values()->all();
        // dd( $bookings);
        return view('user.bookings')->with("bookings",$bookingsSorted);
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
