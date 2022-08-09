<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Events\WorkSpaceDeletedOrClosed;
use App\Models\Buildings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class BuildingController extends Controller
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
            'name' => 'required|max:255' 
        ]);
        $campus_id = $request->input('campus_id');
        $building = new Buildings();
        $building->campus_id = $campus_id;
        $building->name = $request->input('name');

        if($request->has('is_closed') && (filter_var($request->input('is_closed'), FILTER_VALIDATE_BOOLEAN) == FALSE)) {
            $building->is_closed=FALSE;
        }else {
            $building->is_closed=TRUE;
        }

        if ($building->save()) {
            Session::flash('message', 'Successfully created building: ' .$building->name); 
            Session::flash('alert-class', 'alert-success'); 
            return redirect()->route('buildingManager',$campus_id);
        } else {
            Session::flash('message', 'Failed to create building'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('buildingManager',$campus_id);
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
        //
        // validate request input
        $this->validate($request, [
            'name' => 'required|max:255',
            'campus_id' => 'exists:campuses,id'
        ]);
        
        // get campus_id
        $campus_id = $request->input('campus_id');
        $building = Buildings::find($id);
        $building->campus_id = $campus_id;
        $building->name = $request->input('name');

        if($request->has('is_closed') && (filter_var($request->input('is_closed'), FILTER_VALIDATE_BOOLEAN) == FALSE)) {
            $building->is_closed=FALSE;
        }else {
            $building->is_closed=TRUE;
        }
        
        if($building->save()) {
            if ($building->is_closed) {
                // loop through each floor for this building
                foreach ($building->floors as $floor) {
                    // loop through each room for this floor
                    foreach ($floor->rooms as $room) {
                        // loop through each desk for this room and fire event to notify effected users
                        foreach ($room->desks as $desk) {
                            event(new WorkSpaceDeletedOrClosed($desk));
                        }
                    }
                }
            }
            Session::flash('message', 'Successfully updated building: ' .$building->name); 
            Session::flash('alert-class', 'alert-success');
            return redirect()->route('buildingManager',$campus_id);
        }else {
            Session::flash('message', 'Failed to update building'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('buildingManager',$campus_id);
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
        if (Buildings::find($id)->exists()) {
            $building = Buildings::find($id);
            $campus_id = $building->campus_id;

            // loop through each floor for this building
            foreach ($building->floors as $floor) {
                // loop through each room for this floor
                foreach ($floor->rooms as $room) {
                    // loop through each desk for this room and fire event to notify effected users
                    foreach ($room->desks as $desk) {
                        event(new WorkSpaceDeletedOrClosed($desk));
                    }
                }
            }

            if ($building->delete()) {
                Session::flash('message', 'Successfully deleted building: ' .$building->name); 
                Session::flash('alert-class', 'alert-success'); 
                return redirect()->route('buildingManager',$campus_id);
            }
        }
        Session::flash('message', 'Failed to delete campus'); 
        Session::flash('alert-class', 'alert-danger'); 
        return redirect()->route('buildingManager',$campus_id);
    }
}