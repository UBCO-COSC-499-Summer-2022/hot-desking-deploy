<?php

namespace App\Http\Controllers\Admin;

use App\Events\WorkSpaceDeletedOrClosed;
use App\Http\Controllers\Controller;
use App\Models\Desks;
use App\Models\Resources;
use App\Models\Resources_Desks;
use App\Models\Rooms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DeskController extends Controller
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
        // validate request input
        $this->validate($request, [
            'pos_x' => 'required|integer|numeric|between:0,9999',
            'pos_y' => 'required|integer|numeric|between:0,9999',
            'room_id' => 'exists:rooms,id',
        ]);
        $allDesksinRoom = Desks::where('room_id', $request->input('room_id'))->get();

        foreach($allDesksinRoom as $desk){
            if($desk->pos_x == $request->input('pos_x') && $desk->pos_y == $request->input('pos_y')){
            Session::flash('message', 'Failed to create desk: Desk exists at this position '. $desk->pos_x . ',' . $desk->pos_y . '!');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('deskManager', $request->input('room_id'));
            }
        }
        // get room_id
        $room_id = $request->input('room_id');
        // Create Desk
        $desk = new Desks;
        $desk->room_id = $room_id;
        $desk->pos_x = $request->input('pos_x');
        $desk->pos_y = $request->input('pos_y');

        if ($request->has('is_closed') && (filter_var($request->input('is_closed'), FILTER_VALIDATE_BOOLEAN) == FALSE)) {
            $desk->is_closed = FALSE;
        } else {
            $desk->is_closed = TRUE;
        }

        if ($desk->save()) {
            if ($request->has('resource_ids')) {
                $resource_ids = $request->input('resource_ids');
                for ($i = 0; $i < count($resource_ids); $i++) {
                    $resources_desk = new Resources_Desks;
                    $resources_desk->resource_id = $resource_ids[$i];
                    $resources_desk->desk_id = $desk->id;
                    $resources_desk->save();
                }
            }
            Session::flash('message', 'Successfully created desk: ' . $desk->id);
            Session::flash('alert-class', 'alert-success');
            return redirect()->route('deskManager', $room_id);
        } else {
            Session::flash('message', 'Failed to create desk');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('deskManager', $room_id);
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
    public function update(Request $request)
    {
        //dd($request->all());
        // validate request input
        $this->validate($request, [
            'id' => 'exists:desks,id',
            'room_id' => 'exists:rooms,id',
            'pos_x' => 'required|integer|numeric|between:0,9999',
            'pos_y' => 'required|integer|numeric|between:0,9999',
        ]);
        $allDesksinRoom = Desks::where('room_id', $request->input('room_id'))->get();

        foreach($allDesksinRoom as $desk){
            if($desk->pos_x == $request->input('pos_x') && $desk->pos_y == $request->input('pos_y') && $desk->id != $request->input('id')){
            Session::flash('message', 'Failed to update desk: Desk exists at position '. $desk->pos_x . ',' . $desk->pos_y . '!');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('deskManager', $request->input('room_id'));
            }
        }

        //Grab room through id 
        //  Check if posX > row || posY > col
        //            Flash Error Out of Bounds Message
        //             return route with msg
        
        //for all desks in room_id if pos_x and Pos_y == input, this already exists, 
        $id = $request->input('id');

        // get room_id
        $room_id = $request->input('room_id');
        $desk = Desks::find($id);
        // Find desk and update fields
        
        $desk->room_id = $room_id;
        $desk->pos_x = $request->input('pos_x');
        $desk->pos_y = $request->input('pos_y');

        if ($request->has('is_closed') && (filter_var($request->input('is_closed'), FILTER_VALIDATE_BOOLEAN) == FALSE)) {
            $desk->is_closed = FALSE;
        } else {
            $desk->is_closed = TRUE;
        }

        $resource_ids = $request->input('resource_ids');
        $resources_desk = Resources_Desks::where('desk_id', $desk->id)->get();
        // check if the room already has resources
        if (count($resources_desk) > 0) {
            // Delete ALL resources for this room
            foreach ($resources_desk as $resource_room) { 
                $resource_room->delete();
            }
        }
        // add resources if the array has anything
        if ($request->has('resource_ids')) {
            // Add the new resources to the resources_rooms table
            for ($i = 0; $i < count($resource_ids); $i++) {
                $resource_desk = new Resources_Desks;
                $resource_desk->resource_id = $resource_ids[$i];
                $resource_desk->desk_id = $desk->id;
                $resource_desk->save();
            }
        }

        if ($desk->save()) {
            if ($desk->is_closed) {
                // call the event desk deleted - checks if there are users effected and emails them to let them know
                event(new WorkSpaceDeletedOrClosed($desk));
            }
            Session::flash('message', 'Successfully updated desk: ' . $desk->id);
            Session::flash('alert-class', 'alert-success');
            return redirect()->route('deskManager', $room_id);
        } else {
            Session::flash('message', 'Failed to update desk');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('deskManager', $room_id);
        }
        return redirect()->route('deskManager', $room_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // check if desk exists in database
        if (Desks::find($id)->exists()) {
            // Get Desk
            $desk = Desks::find($id);
            $room_id = $desk->room_id;

            // call the event desk deleted - checks if there are users effected and emails them to let them know
            event(new WorkSpaceDeletedOrClosed($desk));

            if ($desk->delete()) {
                Session::flash('message', 'Successfully deleted desk: ' . $desk->id);
                Session::flash('alert-class', 'alert-success');
                return redirect()->route('deskManager', $room_id);
            }
        }
        $desk = Desks::find($id);
        Session::flash('message', 'Failed to delete desk');
        Session::flash('alert-class', 'alert-danger');
        return redirect()->route('deskManager', $desk->room_id);
    }

    public function getResources(Request $request) {
        $desk_id = $request->data;
        $desk = Desks::find($desk_id);
        $allResources = Resources::all();

        $output = '';
        if (Resources_Desks::where('desk_id', $desk_id)->exists()) {
            foreach($desk->resources as $resource) {
                $output .= '<tr><td class="col-md-11"><select name="resource_ids[]" class="form-select text-md-start">';
                $output .= '<option value="'.$resource->resource_id.'">'.$resource->resource_type.'</option>';
                foreach($allResources as $eachResource) {
                    if ($eachResource->resource_id != $resource->resource_id) {
                        $output .= '<option value="'.$eachResource->resource_id.'">'.$eachResource->resource_type.'</option>';
                    }
                }
                $output .= '</select></td><td class="col-md-1 text-center"><button type="button" class="btn btn-danger" onclick="deleteResource(this)"><i class="bi bi-trash3-fill"></i></button></td></tr>';
            }
        }
        $count = Resources_Desks::where('desk_id', $desk_id)->count();

        return response()->json([$output, $count], 200); 
    }
    
    public function getResourcesAppendNew(Request $request) {
        $allResources = Resources::all();

        $output = '';
        $output .= '<tr><td class="col-md-11"><select name="resource_ids[]" class="form-select text-md-start">';
        $output .= '<option disabled selected value="NULL">Please Select a Resource</option>';
        foreach($allResources as $eachResource) {
            $output .= '<option value="'.$eachResource->resource_id.'">'.$eachResource->resource_type.'</option>';
        }
        $output .= '</select></td><td class="col-md-1 text-center"><button type="button" class="btn btn-danger" onclick="deleteResource(this)"><i class="bi bi-trash3-fill"></i></button></td></tr>';

        return response()->json($output, 200); 
    }

    public function getResourcesDesk(Request $request) {
        $desk_id = $request->data;
        $desk = Desks::find($desk_id);
        $allResources = Resources::all();

        $resourceIcons = '';
        if($desk->is_closed) {
            $resourceIcons='<button class="btn editClosedDesk " id="${desk.id}" onclick="editDesk('.$desk->id.','.$desk->pos_x.','.$desk->pos_y.','.$desk->room_id.','.$desk->is_closed.')">Desk #'.$desk->id.' CLOSED<br>';
        }
        else {
            $resourceIcons='<button class="btn editDeskButton " id="${desk.id}" onclick="editDesk('.$desk->id.','.$desk->pos_x.','.$desk->pos_y.','.$desk->room_id.','.$desk->is_closed.')">Desk #'.$desk->id.'<br>';
        }
        if (Resources_Desks::where('desk_id', $desk_id)->exists()){
            foreach($desk->resources as $resource) {
                $resourceIcons .= '<div class="h4 d-inline p-2" style="color:'.$resource->colour.';" data-bs-toggle="tooltip" data-bs-placement="top" title="'.$resource->resource_type.'">' .$resource->icon.'</div>';
            } 
        }
        $resourceIcons .= '</button>';
        return response()->json($resourceIcons, 200);
    }
}
