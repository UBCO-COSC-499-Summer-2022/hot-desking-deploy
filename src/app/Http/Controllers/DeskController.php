<?php

namespace App\Http\Controllers;

use App\Models\Desks;
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
        // get room_id
        $room_id = $request->input('room_id');
        // Create Desk
        $desk = new Desks;
        $desk->room_id = $room_id;
        $desk->pos_x = $request->input('pos_x');
        $desk->pos_y = $request->input('pos_y');
        if ($request->input('has_outlet')) {
            $desk->has_outlet = TRUE;
        } else {
            $desk->has_outlet = FALSE;
        }
        if ($request->input('is_closed')) {
            $desk->is_closed = TRUE;
        } else {
            $desk->is_closed = FALSE;
        }
        if ($desk->save()) {
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
    public function update(Request $request, $id)
    {
        // validate request input
        $this->validate($request, [
            'pos_x' => 'required|integer|numeric|between:0,9999',
            'pos_y' => 'required|integer|numeric|between:0,9999',
            'room_id' => 'exists:rooms,id',
        ]);

        // get room_id
        $room_id = $request->input('room_id');
        $desk = Desks::find($id);
        // Find desk and update fields
        $desk->room_id = $room_id;
        $desk->pos_x = $request->input('pos_x');
        $desk->pos_y = $request->input('pos_y');
        if ($request->input('has_outlet')) {
            $desk->has_outlet = TRUE;
        } else {
            $desk->has_outlet = FALSE;
        }
        if ($request->input('is_closed')) {
            $desk->is_closed = TRUE;
        } else {
            $desk->is_closed = FALSE;
        }
        if ($desk->save()) {
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
}
