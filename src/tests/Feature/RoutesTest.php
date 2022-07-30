<?php

namespace Tests\Feature;

use App\Models\Bookings;
use App\Models\Buildings;
use App\Models\Campuses;
use App\Models\Desks;
use App\Models\Floors;
use App\Models\Roles;
use App\Models\Rooms;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoutesTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    // test dashboard redirects user not logged in
    public function test_dashboard_not_logged_in()
    {
        $response = $this->get('/home');

        $response->assertStatus(302);
    }

    // Bookings Manager Controller
    public function test_non_admin_can_not_access_Bookings_Manager()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('bookingsManager'));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Bookings_Manager()
    {
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('bookingsManager'));

        $response->assertStatus(200);
    }

    // Bookings Manager Controller
    public function test_non_admin_can_not_access_Bookings_Manager_View()
    {
        $booking = Bookings::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('viewBooking', $booking->id));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Bookings_Manager_View()
    {
        $booking = Bookings::factory()->create();

        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('viewBooking', $booking->id));

        $response->assertStatus(200);
    }

    // Bookings Manager Controller
    public function test_non_admin_can_not_access_Bookings_Manager_Edit()
    {
        $booking = Bookings::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('editBooking', $booking->id));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Bookings_Manager_Edit()
    {
        $booking = Bookings::factory()->create();

        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('editBooking', $booking->id));

        $response->assertStatus(200);
    }
    // Bookings Manager Controller
    public function test_non_admin_can_not_access_Bookings_Manager_Create()
    {
        $booking = Bookings::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('createBooking', $booking->id));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Bookings_Manager_Create()
    {
        $booking = Bookings::factory()->create();

        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('createBooking', $booking->id));

        $response->assertStatus(200);
    }

    // Resource Manager Controller
    public function test_non_admin_can_not_access_Resource_Manager_Index()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('campusManager'));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Resource_Manager_Index()
    {
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('campusManager'));

        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_Resource_Manager_Add_Campus()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('addCampus'));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Resource_Manager_Add_Campus()
    {
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('addCampus'));

        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_Resource_Manager_Edit_Campus()
    {
        $campus = Campuses::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('editCampus', $campus->id));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Resource_Manager_Edit_Campus()
    {
        $campus = Campuses::factory()->create();
        
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('editCampus', $campus->id));

        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_Resource_Manager_Building_Manager()
    {
        $campus = Campuses::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('buildingManager', $campus->id));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Resource_Manager_Building_Manager()
    {
        $campus = Campuses::factory()->create();
        
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('buildingManager', $campus->id));

        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_Resource_Manager_Add_Building()
    {
        $campus = Campuses::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('addBuilding', $campus->id));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Resource_Manager_Add_Building()
    {
        $campus = Campuses::factory()->create();
        
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('addBuilding', $campus->id));

        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_Resource_Manager_Edit_Building()
    {
        $building = Buildings::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('editBuilding', $building->id));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Resource_Manager_Edit_Building()
    {
        $building = Buildings::factory()->create();
        
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('editBuilding', $building->id));

        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_Resource_Manager_Floor_Manager()
    {
        $building = Buildings::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('floorManager', $building->id));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Resource_Manager_Floor_Manager()
    {
        $building = Buildings::factory()->create();
        
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('floorManager', $building->id));

        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_Resource_Manager_Add_Floor()
    {
        $building = Buildings::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('addFloor', $building->id));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Resource_Manager_Add_Floor()
    {
        $building = Buildings::factory()->create();
        
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('addFloor', $building->id));

        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_Resource_Manager_Edit_Floor()
    {
        $floor = Floors::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('editFloor', $floor->id));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Resource_Manager_Edit_Floor()
    {
        $floor = Floors::factory()->create();
        
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('editFloor', $floor->id));

        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_Resource_Manager_Room_Manager()
    {
        $floor = Floors::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('roomManager', $floor->id));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Resource_Manager_Room_Manager()
    {
        $floor = Floors::factory()->create();
        
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('roomManager', $floor->id));

        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_Resource_Manager_Add_Room()
    {
        $floor = Floors::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('addRoom', $floor->id));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Resource_Manager_Add_Room()
    {
        $floor = Floors::factory()->create();
        
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('addRoom', $floor->id));

        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_Resource_Manager_Edit_Room()
    {
        $room = Rooms::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('editRoom', $room->id));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Resource_Manager_Edit_Room()
    {
        $room = Rooms::factory()->create();
        
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('editRoom', $room->id));

        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_Resource_Manager_Desk_Manager()
    {
        $room = Rooms::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('deskManager', $room->id));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Resource_Manager_Desk_Manager()
    {
        $room = Rooms::factory()->create();
        
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('deskManager', $room->id));

        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_Resource_Manager_Add_Desk()
    {
        $room = Rooms::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('addDesk', $room->id));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Resource_Manager_Add_Desk()
    {
        $room = Rooms::factory()->create();
        
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('addDesk', $room->id));

        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_Resource_Manager_Edit_Desk()
    {
        $desk = Desks::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('editDesk', $desk->id));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Resource_Manager_Edit_Desk()
    {
        $desk = Desks::factory()->create();
        
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('editDesk', $desk->id));

        $response->assertStatus(200);
    }

    // Roles Manager Controller
    public function test_non_admin_can_not_access_Role_Manager()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('rolesManager'));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Role_Manager()
    {
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('rolesManager'));

        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_View_Role()
    {
        $role = Roles::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('viewRole', $role->id));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_View_Role()
    {
        $role = Roles::factory()->create();

        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('viewRole', $role->id));

        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_Edit_Role()
    {
        $role = Roles::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('editRole', $role->id));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Edit_Role()
    {
        $role = Roles::factory()->create();

        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('editRole', $role->id));

        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_Create_Role()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('createRole'));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Create_Role()
    {
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('createRole'));

        $response->assertStatus(200);
    }

    // Users Manager Controller
    public function test_non_admin_can_not_access_User_Manager()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('userManager'));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_User_Manager()
    {
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('userManager'));

        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_View_User()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('viewUser', $user->id));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_View_User()
    {
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('viewUser', $user->id));

        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_Edit_User()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('editUser', $user->id));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Edit_User()
    {
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('editUser', $user->id));

        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_Add_Manager()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('addUser'));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Add_Manager()
    {
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('addUser'));

        $response->assertStatus(200);
    }
}
