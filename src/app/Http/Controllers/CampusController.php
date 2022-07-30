<?php

namespace App\Http\Controllers;

use App\Models\Campuses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CampusController extends Controller
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
        $this->validate($request,[
            'name' => 'required|max:255' 
        ]);
        $campus = new Campuses;
        $campus->name = $request->input('name');
        if($request->has('is_closed')) {
            $campus->is_closed=TRUE;
        }else {
            $campus->is_closed=FALSE;
        }
        if($campus->save()) {
            Session::flash('message', 'Successfully created campus: ' .$campus->name); 
            Session::flash('alert-class', 'alert-success');
            return redirect()->route('campusManager');
        }else {
            Session::flash('message', 'Failed to create campus'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('campusManager');
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
        $this->validate($request,[
            'name' => 'required|max:255' 
        ]);
        $campus = Campuses::find($id);
        $campus->name = $request->input('name');
        if($request->has('is_closed')) {
            $campus->is_closed=TRUE;
        }else {
            $campus->is_closed=FALSE;
        }
        if($campus->save()) {
            Session::flash('message', 'Successfully updated campus: ' .$campus->name); 
            Session::flash('alert-class', 'alert-success');
            return redirect()->route('campusManager');
        }else {
            Session::flash('message', 'Failed to update campus'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('campusManager');
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
               if (Campuses::find($id)->exists()) {
                $campus = Campuses::find($id);
                if ($campus->delete()) {
                    Session::flash('message', 'Successfully deleted campus: ' .$campus->name); 
                    Session::flash('alert-class', 'alert-success'); 
                    return redirect()->route('campusManager');
                }
            }
            Session::flash('message', 'Failed to delete campus'); 
            Session::flash('alert-class', 'alert-danger'); 
            return redirect()->route('campusManager');
    }
    
}