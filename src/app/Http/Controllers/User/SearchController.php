<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Buildings;
use App\Models\Campuses;
use App\Models\Desks;
use App\Models\Floors;
use App\Models\Rooms;
use Illuminate\Http\Request;

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
        $campuses = Campuses::all();
        $buildings =  Buildings::all();
        $floors =  Floors::all();
        $rooms =  Rooms::all();
        $desks = Desks::all();
        
        return view('user.search')->with('campuses', $campuses)->with('buildings', $buildings)->with('floors', $floors)->with('rooms', $rooms)->with('desks', $desks);
    }
}
