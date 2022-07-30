<?php

namespace App\Http\Controllers;

use App\Models\Buildings;
use App\Models\Campuses;
use App\Models\Desks;
use App\Models\Floors;
use App\Models\Rooms;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ResourceManagerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'isAdmin']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $campuses = Campuses::paginate(10);
        return view('admin.resourceManagement.campusManager')->with("campuses",$campuses);

    }

    public function addCampus() 
    {
        //ddd($request->all());
        return view('admin.resourceManagement.addCampus');
    }

    public function editCampus($id)
    {
        $campus = Campuses::find($id);
        return view('admin.resourceManagement.editCampus')->with('campus',$campus);
    }


    

    public function buildingManager($campus_id)
    {
        $buildings = Buildings::where("campus_id",$campus_id)->paginate(10);
        //ddd($buildings); useful for testing
        return view('admin.resourceManagement.buildingManager')->with("buildings",$buildings)->with('campus_id',$campus_id);
    }

    public function addBuilding($campus_id)
    {
        return view('admin.resourceManagement.addBuilding')->with("campus_id",$campus_id);
    }

    public function editBuilding($id)
    {
        $building = Buildings::find($id);
        //ddd($building);
        return view('admin.resourceManagement.editBuilding')->with('building',$building);
    }



    public function floorManager($building_id)
    {
        $building = Buildings::find($building_id);
        $floors = Floors::where("building_id",$building_id)->paginate(10);
        return view('admin.resourceManagement.floorManager')->with("floors",$floors)->with('building_id',$building_id)->with('campus_id',$building->campus_id);
    }

    public function addFloor($building_id)
    {
        return view('admin.resourceManagement.addFloor')->with('building_id',$building_id);
    }

    public function editFloor($id)
    {
        $floor = Floors::find($id);
        return view('admin.resourceManagement.editFloor')->with('floor',$floor);
    }



    public function roomManager($floor_id)
    {
        $floor = Floors::find($floor_id);
        $building = Buildings::find($floor->building_id);
        $rooms = Rooms::where("floor_id",$floor_id)->paginate(10);
        return view('admin.resourceManagement.roomManager')->with("rooms",$rooms)->with('floor_id',$floor_id)->with('building_id',$floor->building_id)->with('campus_id',$building->campus_id);
    }

    public function addRoom($floor_id)
    {
        return view('admin.resourceManagement.addRoom')->with('floor_id',$floor_id);
    }

    public function editRoom($id)
    {
        $room = Rooms::find($id);
        return view('admin.resourceManagement.editRoom')->with('room',$room);
    }


    public function deskManager($room_id)
    {
        $room = Rooms::find($room_id);
        $floor = Floors::find($room->floor_id);
        $building = Buildings::find($floor->building_id);
        $desks = Desks::where("room_id",$room_id)->paginate(10);
        return view('admin.resourceManagement.deskManager')->with("desks",$desks)->with('room_id',$room_id)->with('floor_id',$room->floor_id)->with('building_id',$floor->building_id)->with('campus_id',$building->campus_id);
    }

    public function addDesk($room_id)
    {
        return view('admin.resourceManagement.addDesk')->with('room_id',$room_id);
    }

    public function editDesk($id)
    {
        $desk = Desks::find($id);
        return view('admin.resourceManagement.editDesk')->with('desk',$desk);
    }

}
