<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bookings;
use App\Models\Desks;
use App\Models\User;
use App\Models\Users;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class BookingsManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'isAdmin', 'isSuspended']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $bookings = DB::table('bookings')->leftJoin('users', 'bookings.user_id', '=', 'users.id')->select('bookings.*','users.first_name')->orderBy('bookings.id')->paginate(10);
        return view('admin.bookings.bookingsManager')->with('bookings',$bookings);
    }

    public function viewBooking($id)
    {
        $booking = Bookings::find($id);
        return view('admin.bookings.viewBooking')->with('booking',$booking);
    }

    public function editBooking($id)
    {
        $booking = DB::table('bookings')->leftJoin('users', 'bookings.user_id', '=', 'users.id')->select('bookings.*','users.first_name')->where('bookings.id',$id)->first();
        $users = DB::table('users')->select(['id','first_name'])->orderBy('first_name')->get();
        $desks = Desks::orderBy('room_id')->orderBy('id')->get();
        return view('admin.bookings.editBooking')->with('booking',$booking)->with('users', $users)->with('desks', $desks);
    }


    public function createBooking()
    {
        $users = DB::table('users')->select(['id','first_name'])->orderBy('first_name')->get();
        $desks = Desks::orderBy('room_id')->orderBy('id')->get();
        return view('admin.bookings.createBooking')->with('users', $users)->with('desks', $desks);
        
    }
}