<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resources;
use Illuminate\Http\Request;

class ResourceManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'isAdmin', 'isSuspended']);
    }

    public function index()
    {   
        $resources = Resources::paginate(10);
        return view('admin.resourceManagement.resourceManager')->with('resources', $resources);
    }

    public function addResource()
    {

        return view('admin.resourceManagement.addResource');
    }

    public function editResource($resource_id)
    {   
        $resource = Resources::find($resource_id);
        return view('admin.resourceManagement.editResource')->with('resource', $resource);
    }

}
