<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buildings;
use App\Models\Campuses;
use App\Models\Floors;
use App\Models\OccupationPolicyLimit;
use App\Models\Roles;
use App\Models\Rooms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Component\CssSelector\Node\ElementNode;

class PolicyManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'isAdmin', 'isSuspended']);
    }

    public function index()
    {
        // Used to handle the loading of the page if it's coming from the PolicyController
        if (Session::get('tab') == NULL) {
            $tab = 0;
            $floor_id = NULL;
            $building_id = NULL;
            $campus_id = NULL;
        } elseif(Session::get('tab') == 1) {
            $tab = Session::get('tab');
            $floor_id = Session::get('floor_id');
            $building_id = Session::get('building_id');
            $campus_id = Session::get('campus_id');
        } else {
            $tab = Session::get('tab');
            $floor_id = NULL;
            $building_id = NULL;
            $campus_id = NULL;
        }
        
        $campuses = Campuses::all();
        $buildings = Buildings::all();
        $floors = Floors::all();
        $rooms = Rooms::all();
        $occupation = OccupationPolicyLimit::find(1);
        $roles = Roles::all();
        return view('admin.policyManagement.policyManager')->with('campuses', $campuses)->with('buildings', $buildings)->with('floors', $floors)->with('rooms', $rooms)->with('occupation', $occupation)->with('roles', $roles)->with('tab', $tab)->with('floor_id', $floor_id)->with('building_id', $building_id)->with('campus_id', $campus_id);
    }

    public function getFilteredRooms(Request $request) {
        $filteredRooms = $request->data;
        $output = '';

        foreach ($filteredRooms as $room) {
            $output .= '<tr><td class="text-center align-middle">'. $room["name"] .'</td>';
            
            // get room from id, this will be used to join onto the roles table
            $room_id = $room["id"];
            $roomFromDB = Rooms::find($room_id);
            
            if (!count($roomFromDB->roles) < 1) {
                $output .=  '<td class="text-center align-middle">';
                $i = 0;
                foreach ($roomFromDB->roles as $role) {
                    if ($i != 0) {
                        $output .= ', '.$role->role;
                    } else {
                        $output .= $role->role;
                    }
                    $i++;
                }
                $output .= '</td>';
            } else {
                $output .= '<td class="text-center align-middle">All Roles</td>';
            }
            $route = route('editRoomRestrictionsPolicy', $room["id"]);
            $output .= '<td><a href="'. $route .'" role="button" class="btn btn-secondary float-end"><i class="bi bi-pencil-square"></i></a></td></tr>';
        }
        return response()->json($output, 200); 
    }

    public function editRoomRestrictionsPolicy($room_id) {
        $room = Rooms::find($room_id);
        $roles = Roles::all();
        return view('admin.policyManagement.editRestrictions')->with('room', $room)->with('roles', $roles);
    }

    public function editRolesBookingPolicy($role_id) {
        $role = Roles::find($role_id);
        return view('admin.policyManagement.editRolesBookingPolicy')->with('role', $role);
    }
}
