<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Desks;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ViewDeskController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $desks = Desks::where('room_id', $request->input('room_id'))->get();
        $date=Carbon::now();
        
        return view('user.viewDesk')->with('desks',$desks)->with('date',$date);
    }
}
