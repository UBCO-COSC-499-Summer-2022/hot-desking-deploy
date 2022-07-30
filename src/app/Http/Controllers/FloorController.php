<?php

namespace App\Http\Controllers;

use App\Models\Floors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class FloorController extends Controller
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
            'building_id' => 'exists:buildings,id',
            'floor_num' => 'required|max:10',
        ]);

        $building_id = $request->input('building_id');
        $floor = new Floors;
        $floor->building_id = $building_id;
        $floor->floor_num = $request->input('floor_num');
        if ($request->input('is_closed')) {
            $floor->is_closed = TRUE;
        } else {
            $floor->is_closed = FALSE;
        }

        if ($floor->save()) {
            Session::flash('message', 'Successfully created floor: ' . $floor->floor_num);
            Session::flash('alert-class', 'alert-success');
            return redirect()->route('floorManager', $building_id);
        } else {
            Session::flash('message', 'Failed to create floor');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('floorManager', $building_id);
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
        $this->validate($request, [
            'building_id' => 'exists:buildings,id',
            'floor_num' => 'required|max:10',
        ]);

        $building_id = $request->input('building_id');
        $floor = Floors::find($id);

        $floor->building_id = $building_id;
        $floor->floor_num = $request->input('floor_num');
        if ($request->input('is_closed')) {
            $floor->is_closed = TRUE;
        } else {
            $floor->is_closed = FALSE;
        }

        if ($floor->save()) {
            Session::flash('message', 'Successfully updated floor: ' . $floor->floor_num);
            Session::flash('alert-class', 'alert-success');
            return redirect()->route('floorManager', $building_id);
        } else {
            Session::flash('message', 'Failed to update floor');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('floorManager', $building_id);
        }
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Floors::find($id)->exists()) {
            $floor = Floors::find($id);
            $building_id = $floor->building_id;
            if ($floor->delete()) {
                Session::flash('message', 'Successfully deleted floor: ' . $floor->floor_num);
                Session::flash('alert-class', 'alert-success');
                return redirect()->route('floorManager', $building_id);
            }
        }
        Session::flash('message', 'Failed to delete floor');
        Session::flash('alert-class', 'alert-danger');
        return redirect()->route('floorManager', $building_id);
    }
}
