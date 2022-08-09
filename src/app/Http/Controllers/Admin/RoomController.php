<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Events\WorkSpaceDeletedOrClosed;
use App\Models\Desks;
use App\Models\Resources_Rooms;
use App\Models\Rooms;
use Faker\Provider\Image;
use Illuminate\Contracts\Session\Session as SessionSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use League\CommonMark\Extension\CommonMark\Node\Inline\Image as InlineImage;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class RoomController extends Controller
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
        $this->validate($request, [
            'floor_id' => 'exists:floors,id',
            'name' => 'required|max:30',
            'occupancy' => 'required|integer|numeric|between:0,500',
        ]);
        //GET floor_id
        $floor_id = $request->input('floor_id');
        //CREATE Room in Rooms
        $room = new Rooms;
        $room->floor_id = $request->input('floor_id');
        $room->name = $request->input('name');
        $room->occupancy = $request->input('occupancy');

        if ($request->has('is_closed') && (filter_var($request->input('is_closed'), FILTER_VALIDATE_BOOLEAN) == FALSE)) {
            $room->is_closed = FALSE;
        } else {
            $room->is_closed = TRUE;
        }

        if ($room->save()) {
            if ($request->has('resource_ids') && $request->has('descriptions')) {
                $resource_ids = $request->input('resource_ids');
                $descriptions = $request->input('descriptions');
                // Verify lengths of arrays are equal
                if (count($resource_ids) == count($descriptions)) {
                    for ($i = 0; $i < count($resource_ids); $i++) {
                        $resources_room = new Resources_Rooms;
                        $resources_room->resource_id = $resource_ids[$i];
                        $resources_room->description = $descriptions[$i];
                        $resources_room->room_id = $room->id;
                        $resources_room->save();
                    }
                }
            }
            Session::flash('message', 'Successfully created room: ' . $room->name);
            Session::flash('alert-class', 'alert-success');
            return redirect()->route('roomManager', $floor_id);
        } else {
            Session::flash('message', 'Failed to create room');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('roomManager', $floor_id);
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
        //validate request input
        $this->validate($request, [
            'floor_id' => 'exists:floors,id',
            'name' => 'required|max:30',
            'occupancy' => 'required|integer|numeric|between:0,500',
        ]);
        // GET floor_id
        $floor_id = $request->input('floor_id');
        $room = Rooms::find($id);
        // UPDATE TABLE Rooms at id
        $room->floor_id = $floor_id;
        $room->name = $request->input('name');
        $room->occupancy = $request->input('occupancy');

        if ($request->has('is_closed') && (filter_var($request->input('is_closed'), FILTER_VALIDATE_BOOLEAN) == FALSE)) {
            $room->is_closed = FALSE;
        } else {
            $room->is_closed = TRUE;
        }
        
        $resource_ids = $request->input('resource_ids');
        $descriptions = $request->input('descriptions');
        $resources_rooms = Resources_Rooms::where('room_id', $room->id)->get();
        // check if the room already has resources
        if (count($resources_rooms) > 0) {
            // Delete ALL resources for this room
            foreach ($resources_rooms as $resource_room) { 
                $resource_room->delete();
            }
        }
        // add resources if the array has anything
        if ($request->has('resource_ids') && $request->has('descriptions')) {
            // Verify lengths of arrays are equal
            if (count($resource_ids) == count($descriptions)) {
                // Add the new resources to the resources_rooms table
                for ($i = 0; $i < count($resource_ids); $i++) {
                    $resources_room = new Resources_Rooms;
                    $resources_room->resource_id = $resource_ids[$i];
                    $resources_room->description = $descriptions[$i];
                    $resources_room->room_id = $room->id;
                    $resources_room->save();
                }
            }
        }

        if ($room->save()) {
            if ($room->is_closed) {
                // loop through each desk for this room and fire event to notify effected users
                foreach ($room->desks as $desk) {
                    event(new WorkSpaceDeletedOrClosed($desk));
                }
            }
            Session::flash('message', 'Successfully updated room: ' . $room->id);
            Session::flash('alert-class', 'alert-success');
            return redirect()->route('roomManager', $floor_id);
        } else {
            Session::flash('message', 'Failed to update desk');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('roomManager', $floor_id);
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
        //check if room exists in database
        if (Rooms::find($id)->exists()) {
            //Get Room 
            $room = Rooms::find($id);
            $floor_id = $room->floor_id;

            // loop through each desk for this room and fire event to notify effected users
            foreach ($room->desks as $desk) {
                event(new WorkSpaceDeletedOrClosed($desk));
            }

            if ($room->delete()) {
                Session::flash('message', 'Successfully delete role: ' . $room->id);
                Session::flash('alert-class', 'alert-success');
                return redirect()->route('roomManager', $floor_id);
            }
        }
        $room = Rooms::find($id);
        Session::flash('message', 'Failed to delete room');
        Session::flash('alert-class', 'alert-danger');
        return redirect()->route('roomManager', $room->floor_id);
    }

    public function addRoomSize(Request $request, $room_id) {
        //validate request input
        $this->validate($request, [
            'rows' => 'required|integer|numeric|between:0,50',
            'cols' => 'required|integer|numeric|between:0,50',
        ]);
        // GET floor_id
        $room = Rooms::find($room_id);
        // UPDATE TABLE Rooms at id
        $allDesksInRoom = Desks::where('room_id', $room_id)->get();
        $listOfDeletedDesks = '';
        $removedDesks = FALSE;
        //SCAN for desks to be deleted
            foreach($allDesksInRoom as $deskInRoom){
                if($deskInRoom->pos_x >= $request->input('rows') || $deskInRoom->pos_y >= $request->input('cols')){
                    $removedDesks = TRUE;                             //Room has deleted Desks
                    $listOfDeletedDesks .= $deskInRoom->id . ", ";    //List desk as deleted desk
                    event(new WorkSpaceDeletedOrClosed($deskInRoom)); //Email users affected by delete
                    $deskInRoom->delete();                            //Delete desk from room
                }
            }
        $room->rows = $request->input('rows');
        $room->cols = $request->input('cols');
        
        if ($room->save() && $removedDesks) { //Alert if Desks have been deleted
            Session::flash('message', 'Desks '. $listOfDeletedDesks . ' have now been deleted! Successfully updated room: ' . $room->id);
            Session::flash('alert-class', 'alert-success');
            return redirect()->route('deskManager', $room->id);
        } elseif ($room->save()) { //Alert if Save Room Dimensions is successfull
            Session::flash('message', 'Successfully updated room: ' . $room->id);
            Session::flash('alert-class', 'alert-success');
            return redirect()->route('deskManager', $room->id);
        } else { //Alert if Failure to UPDATE Room Dimensions
            Session::flash('message', 'Failed to update room size');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('roomManager', $room->floor_id);
        }  
    }
}

