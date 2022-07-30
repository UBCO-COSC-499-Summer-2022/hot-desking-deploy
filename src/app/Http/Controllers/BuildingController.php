<?php

namespace App\Http\Controllers;

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

        if($request->has('is_closed')) {
            $building->is_closed=TRUE;
        }else {
            $building->is_closed=FALSE;
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

        if($request->has('is_closed')) {
            $building->is_closed=TRUE;
        }else {
            $building->is_closed=FALSE;
        }
        if($building->save()) {
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