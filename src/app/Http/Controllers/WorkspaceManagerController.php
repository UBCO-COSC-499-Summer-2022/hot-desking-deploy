<?php

namespace App\Http\Controllers;

use App\Models\Buildings;
use App\Models\Campuses;
use App\Models\Desks;
use App\Models\Floors;
use App\Models\Resources;
use App\Models\Rooms;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class WorkspaceManagerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'isAdmin', 'isSuspended']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $campuses = Campuses::paginate(10);
        return view('admin.workspaceManagement.campusManager')->with("campuses",$campuses);

    }

    public function addCampus() 
    {
        //ddd($request->all());
        return view('admin.workspaceManagement.addCampus');
    }

    public function editCampus($id)
    {
        $campus = Campuses::find($id);
        return view('admin.workspaceManagement.editCampus')->with('campus',$campus);
    }


    

    public function buildingManager($campus_id)
    {
        $buildings = Buildings::where("campus_id",$campus_id)->paginate(10);
        $campus = Campuses::find($campus_id);
        //ddd($buildings); useful for testing
        return view('admin.workspaceManagement.buildingManager')->with('campus_name', $campus->name)->with("buildings",$buildings)->with('campus_id',$campus_id);
    }

    public function addBuilding($campus_id)
    {
        return view('admin.workspaceManagement.addBuilding')->with("campus_id",$campus_id);
    }

    public function editBuilding($id)
    {
        $building = Buildings::find($id);
        //ddd($building);
        return view('admin.workspaceManagement.editBuilding')->with('building',$building);
    }



    public function floorManager($building_id)
    {
        $building = Buildings::find($building_id);
        $campus = Campuses::find($building->campus_id);
        $floors = Floors::where("building_id",$building_id)->paginate(10);
        return view('admin.workspaceManagement.floorManager')->with('campus_name', $campus->name)->with('building_name', $building->name)->with("floors",$floors)->with('building_id',$building_id)->with('campus_id',$building->campus_id);
    }

    public function addFloor($building_id)
    {
        return view('admin.workspaceManagement.addFloor')->with('building_id',$building_id);
    }

    public function editFloor($id)
    {
        $floor = Floors::find($id);
        return view('admin.workspaceManagement.editFloor')->with('floor',$floor);
    }



    public function roomManager($floor_id)
    {
        $floor = Floors::find($floor_id);
        $building = Buildings::find($floor->building_id);
        $campus = Campuses::find($building->campus_id);
        $rooms = Rooms::where("floor_id",$floor_id)->paginate(10);
        return view('admin.workspaceManagement.roomManager')->with('campus_name', $campus->name)->with('building_name', $building->name)->with('floor_num', $floor->floor_num)->with("rooms",$rooms)->with('floor_id',$floor_id)->with('building_id',$floor->building_id)->with('campus_id',$building->campus_id);
    }

    public function addRoom($floor_id)
    {
        $resources = Resources::all();
        return view('admin.workspaceManagement.addRoom')->with('floor_id',$floor_id)->with('resources', $resources);
    }

    public function editRoom($id)
    {
        $room = Rooms::find($id);
        $resources = Resources::all();
        return view('admin.workspaceManagement.editRoom')->with('room',$room)->with('resources', $resources);
    }


    public function deskManager($room_id)
    {
        $room = Rooms::find($room_id);
        
        $floor = Floors::find($room->floor_id);
        $building = Buildings::find($floor->building_id);
        $campus= Campuses::find($building->campus_id);
        $desks = Desks::where("room_id",$room_id)->get();
        $resources = Resources::all();
        return view('admin.workspaceManagement.deskManager')->with('floor_num', $floor->floor_num)->with('building_name', $building->name)->with('campus_name', $campus->name)->with('room', $room)->with("desks",$desks)->with('room_id',$room_id)->with('floor_id',$room->floor_id)->with('building_id',$floor->building_id)->with('campus_id',$building->campus_id)->with('resources', $resources);
    }

    public function addDesk($room_id)
    {
        return view('admin.workspaceManagement.addDesk')->with('room_id',$room_id);
    }

    public function editDesk($id)
    {
        $desk = Desks::find($id);
        return view('admin.workspaceManagement.editDesk')->with('desk',$desk);
    }

}
