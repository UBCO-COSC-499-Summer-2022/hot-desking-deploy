<?php

namespace App\Http\Controllers;

use App\Models\Resources;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ResourceController extends Controller
{
    //
    public function index(){
    
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

    public function store(Request $request)
    {
        $this->validate($request, [
            'resource_type' => 'required|max:60',
            'icon' => 'required|max:255',
            'colour' => 'required|max:7',
        ]);

        $resource = new Resources;
        $resource->resource_type = $request->input('resource_type');
        $resource->icon = $request->input('icon');
        $resource->colour = $request->input('colour');

        if ($resource->save()) {
            Session::flash('message', 'Successfully created resource: ' . $resource->resource_type);
            Session::flash('alert-class', 'alert-success');
            return redirect()->route('resourceManager');
        } else {
            Session::flash('message', 'Failed to create resource');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('resourceManager');
        }

    }

    public function update(Request $request, $resource_id)
    {
        $this->validate($request, [
            'resource_type' => 'required|max:60',
            'icon' => 'required|max:255',
            'colour' => 'required|max:7',
        ]);

        $resource = Resources::find($resource_id);
        $resource->resource_type = $request->input('resource_type');
        $resource->icon = $request->input('icon');
        $resource->colour = $request->input('colour');

        if($resource->save()){
            Session::flash('message', 'Successfully updated resource: ' . $resource->resource_type);
            Session::flash('alert-class', 'alert-success');
            return redirect()->route('resourceManager');
        } else {
            Session::flash('message', 'Failed to updated resource');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('resourceManager');
        }
    }

    public function destroy($resource_id)
    {
        // check if role exists in database
        if (Resources::find($resource_id)->exists()) {
            // Get Role
            $resource = Resources::find($resource_id);
            if ($resource->delete()) {
                Session::flash('message', 'Successfully deleted resource: ' . $resource->resource_id);
                Session::flash('alert-class', 'alert-success');
                return redirect()->route('resourceManager');
            }
        }
        Session::flash('message', 'Failed to delete role');
        Session::flash('alert-class', 'alert-danger');
        return redirect()->route('resourceManager');
    }
}
