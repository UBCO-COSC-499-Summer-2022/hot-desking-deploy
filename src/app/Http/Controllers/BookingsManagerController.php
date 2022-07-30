<?php

namespace App\Http\Controllers;

use App\Models\Bookings;
use App\Models\Desks;
use App\Models\User;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class BookingsManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'isAdmin']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $bookings = DB::table('bookings')->leftJoin('users', 'bookings.user_id', '=', 'users.id')->select('bookings.*','users.name')->orderBy('bookings.id')->paginate(10);
        return view('admin.bookings.bookingsManager')->with('bookings',$bookings);
    }

    public function viewBooking($id)
    {
        $booking = Bookings::find($id);
        return view('admin.bookings.viewBooking')->with('booking',$booking);
    }

    public function editBooking($id)
    {
        $booking = DB::table('bookings')->leftJoin('users', 'bookings.user_id', '=', 'users.id')->select('bookings.*','users.name')->where('bookings.id',$id)->first();
        $users = DB::table('users')->select(['id','name'])->orderBy('name')->get();
        $desks = DB::table('desks')->orderBy('room_id')->orderBy('id')->get();
        return view('admin.bookings.editBooking')->with('booking',$booking)->with('users', $users)->with('desks', $desks);
    }


    public function createBooking()
    {
        $users = DB::table('users')->select(['id','name'])->orderBy('name')->get();
        $desks = DB::table('desks')->orderBy('room_id')->orderBy('id')->get();
        return view('admin.bookings.createBooking')->with('users', $users)->with('desks', $desks);
        
    }
}