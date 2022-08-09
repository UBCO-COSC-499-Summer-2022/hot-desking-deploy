<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoleRoom;
use App\Models\Rooms;
use App\Models\OccupationPolicyLimit;
use App\Models\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PoliciesController extends Controller
{
    //
    public function editRestrictionsPolicy(Request $request, $room_id) {
        $room = Rooms::find($room_id);
        $roleIdsArray = $request->input('role_ids');

        // check if the request has the role_ids input field
        if ($roleIdsArray) {
            $roleIds = $roleIdsArray;
            // check if this room already exists in the 'role_room' table
            $roles_room = RoleRoom::where('room_id', $room_id)->get();
            foreach ($roleIds as $roleId) {
                DB::table('role_room')->updateOrInsert(
                    ['role_id' => $roleId, 'room_id' => $room_id]
                );
            }
            // Loop through all of the input added along with any previously stored fields. Uses the $found variable to compare data in database against the data from the $request 
            foreach ($roles_room as $existingRoleRoom) {
                $found = false;
                foreach ($roleIds as $roleId) {
                    if ($existingRoleRoom->role_id == $roleId) {
                        $found = true;
                    }
                }
                // If role is not found then delete it from the db
                if (!$found) {
                    RoleRoom::where('role_id', $existingRoleRoom->role_id)->where('room_id', $room_id)->delete();
                }
            }
        } else {
            // check if role_room entry exists, and delete it if it does (Case admin deletes restrictions that we're previously set)
            if (RoleRoom::where('room_id', $room_id)->exists()) {
                RoleRoom::where('room_id', $room_id)->delete();
            }
        }

        Session::flash('message', 'Successfully added role restriction to: ' .$room->name); 
        Session::flash('alert-class', 'alert-success');
        return redirect()->route('policyManager')->with('tab', 1)->with('floor_id', $room->floor_id)->with('building_id', $room->floor->building_id)->with('campus_id', $room->floor->building->campus_id);
    }

    public function cancelRestrictionsPolicy($room_id) {
        $room = Rooms::find($room_id);
        return redirect()->route('policyManager')->with('tab', 1)->with('floor_id', $room->floor_id)->with('building_id', $room->floor->building_id)->with('campus_id', $room->floor->building->campus_id);
    }

    public function editOccupationPolicy(Request $request, $occupation_policy_id) {
        $this->validate($request,[
            'percentage'=>'required|integer|between:0,100',
        ]);

        $occupation = OccupationPolicyLimit::find($occupation_policy_id);
        $occupation->percentage = $request->input('percentage');
        
        if ($occupation->save()) {
            Session::flash('message', 'Successfully updated the occupation limit policy' ); 
            Session::flash('alert-class', 'alert-success');
            return redirect()->route('policyManager')->with('tab', 2);
        }

        Session::flash('message', 'Failed to update the occupation limit policy' ); 
        Session::flash('alert-class', 'alert-danger');
        return redirect()->route('policyManager')->with('tab', 2);
    }

    // This function acts as a failsafe, this should never be needed.
    public function restoreOccupationPolicy() {
        if (OccupationPolicyLimit::where('id', 1)->exists()) {
            OccupationPolicyLimit::where('id', 1)->delete();
        }
        $occupation = new OccupationPolicyLimit;
        $occupation->id = 1;
        $occupation->percentage = 100;
        if ($occupation->save()){
            Session::flash('message', 'Successfully restored the occupation limit policy' ); 
            Session::flash('alert-class', 'alert-success');
            return redirect()->route('policyManager')->with('tab', 2);
        }
        Session::flash('message', 'Failed to restore the occupation limit policy, please contact IT services' ); 
        Session::flash('alert-class', 'alert-danger');
        return redirect()->route('policyManager')->with('tab', 2);
    }

    public function editRolesBookingPolicies(Request $request, $role_id) {
        // validate request input
        $this->validate($request, [
            'max_booking_window' => 'required|integer',
            'max_booking_duration' => 'required|integer',
        ]);

        if (Roles::find($role_id)->exists()) {
            $role = Roles::find($role_id);
            $role->max_booking_window = $request->input('max_booking_window');
            $role->max_booking_duration = $request->input('max_booking_duration');
            if ($role->save()) {
                Session::flash('message', 'Successfully updated role bookings policy for role: ' . $role->role);
                Session::flash('alert-class', 'alert-success');
                return redirect()->route('policyManager')->with('tab', 3);
            }
        }
        Session::flash('message', 'Failed to updated role bookings policy');
        Session::flash('alert-class', 'alert-danger');
        return redirect()->route('policyManager')->with('tab', 3);
    }

    public function cancelRolesBookingPolicies() {
        return redirect()->route('policyManager')->with('tab', 3);
    }
}
