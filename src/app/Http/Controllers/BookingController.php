<?php

namespace App\Http\Controllers;

use App\Models\Bookings;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request,[
            'user_id'=>'required|integer',
            'desk_id'=>'required|integer',
            'book_time_start'=>'required|date',
            'book_time_end'=>'required|date|after:book_time_start',
        ]);
        $booking= new Bookings;
        $booking->user_id=$request->input('user_id');
        $booking->desk_id=$request->input('desk_id');
        $booking->book_time_start=$request->input('book_time_start');
        $booking->book_time_end=$request->input('book_time_end');
        if($booking->save()){
            $user=User::find($booking->user_id);
            Session::flash('message', 'Successfully created booking for user: ' .$user->first_name); 
            Session::flash('alert-class', 'alert-success');
            return redirect()->route('bookingsManager');
        }else{
            Session::flash('message', 'Failed to create booking'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('bookingsManager');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // validate request input
        $this->validate($request, [
            'user_id'=>'required|integer',
            'desk_id'=>'required|integer',
            'book_time_start'=>'required|date',
            'book_time_end'=>'required|date|after:book_time_start',
        ]);
        
        $booking = Bookings::find($id);
        $booking->user_id=$request->input('user_id');
        $booking->desk_id=$request->input('desk_id');
        $booking->book_time_start=$request->input('book_time_start');
        $booking->book_time_end=$request->input('book_time_end');

        if ($booking->save()) {
            $user=User::find($booking->user_id);
            Session::flash('message', 'Successfully updated booking for: ' .$user->first_name); 
            Session::flash('alert-class', 'alert-success'); 
            return redirect()->route('bookingsManager');
        } else {
            Session::flash('message', 'Failed to update booking'); 
            Session::flash('alert-class', 'alert-danger'); 
            return redirect()->route('bookingsManager');   
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // check if role exists in database
        if (Bookings::find($id)->exists()) {
            // Get Role
            $booking = Bookings::find($id);
            if ($booking->delete()) {
                Session::flash('message', 'Successfully deleted booking: ' .$booking->id); 
                Session::flash('alert-class', 'alert-success'); 
                return redirect()->route('bookingsManager');
            }
        }
        Session::flash('message', 'Failed to delete role'); 
        Session::flash('alert-class', 'alert-danger'); 
        return redirect()->route('bookingsManager');
    }
}