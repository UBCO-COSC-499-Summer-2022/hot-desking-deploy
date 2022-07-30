<?php

namespace App\Http\Controllers;

use App\Models\Rooms;
use Faker\Provider\Image;
use Illuminate\Contracts\Session\Session as SessionSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use League\CommonMark\Extension\CommonMark\Node\Inline\Image as InlineImage;
use Symfony\Component\HttpFoundation\File\UploadedFile;


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
        ]);
        //GET floor_id
        $floor_id = $request->input('floor_id');
        //CREATE Room in Rooms
        $room = new Rooms;
        $room->floor_id = $request->input('floor_id');
        $room->name = $request->input('name');
        if ($request->input('has_printer')) {
            $room->has_printer = TRUE;
        } else {
            $room->has_printer = FALSE;
        }
        if ($request->input('has_projector')) {
            $room->has_projector = TRUE;
        } else {
            $room->has_projector = FALSE;
        }
        if ($request->input('is_closed')) {
            $room->is_closed = TRUE;
        } else {
            $room->is_closed = FALSE;
        }
        if ($request->hasFile('room_image')) {
            $room->room_image = $request->file('room_image');
        } else {
            $room->room_image = $request->input('room_image');
        }
        if ($room->save()) {
            if ($request->hasFile('room_image')) {
                $file = $request->file('room_image');
                $fileName = strval($room->id) . ".png";
                $destinationPath = public_path() . "/images/rooms";
                $file->move($destinationPath, $fileName);
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
        ]);
        // GET floor_id
        $floor_id = $request->input('floor_id');
        $room = Rooms::find($id);
        // UPDATE TABLE Rooms at id
        $room->floor_id = $floor_id;
        $room->name = $request->input('name');
        if ($request->input('has_printer')) {
            $room->has_printer = TRUE;
        } else {
            $room->has_printer = FALSE;
        }
        if ($request->input('has_projector')) {
            $room->has_projector = TRUE;
        } else {
            $room->has_projector = FALSE;
        }
        if ($request->input('is_closed')) {
            $room->is_closed = TRUE;
        } else {
            $room->is_closed = FALSE;
        }
        if ($request->hasFile('room_image')) {
            $room->room_image = $request->file('room_image');
        }

        if ($request->hasFile('room_image')) {
            $file = $request->file('room_image');
            $fileName = strval($room->id) . ".png";
            $destinationPath = public_path() . "/images/rooms";
            $file->move($destinationPath, $fileName);
        }
        if ($room->save()) {
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
}
