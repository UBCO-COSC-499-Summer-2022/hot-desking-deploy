<?php

namespace Tests\Feature;

use App\Models\Bookings;
use App\Models\Buildings;
use App\Models\Campuses;
use App\Models\Desks;
use App\Models\Floors;
use App\Models\Resources;
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

    // Workspace Manager Controller
    public function test_non_admin_can_not_access_Workspace_Manager_Index()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('campusManager'));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Workspace_Manager_Index()
    {
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('campusManager'));

        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_Workspace_Manager_Add_Campus()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('addCampus'));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Workspace_Manager_Add_Campus()
    {
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('addCampus'));

        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_Workspace_Manager_Edit_Campus()
    {
        $campus = Campuses::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('editCampus', $campus->id));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Workspace_Manager_Edit_Campus()
    {
        $campus = Campuses::factory()->create();
        
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('editCampus', $campus->id));

        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_Workspace_Manager_Building_Manager()
    {
        $campus = Campuses::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('buildingManager', $campus->id));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Workspace_Manager_Building_Manager()
    {
        $campus = Campuses::factory()->create();
        
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('buildingManager', $campus->id));

        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_Workspace_Manager_Add_Building()
    {
        $campus = Campuses::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('addBuilding', $campus->id));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Workspace_Manager_Add_Building()
    {
        $campus = Campuses::factory()->create();
        
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('addBuilding', $campus->id));

        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_Workspace_Manager_Edit_Building()
    {
        $building = Buildings::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('editBuilding', $building->id));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Workspace_Manager_Edit_Building()
    {
        $building = Buildings::factory()->create();
        
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('editBuilding', $building->id));

        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_Workspace_Manager_Floor_Manager()
    {
        $building = Buildings::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('floorManager', $building->id));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Workspace_Manager_Floor_Manager()
    {
        $building = Buildings::factory()->create();
        
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('floorManager', $building->id));

        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_Workspace_Manager_Add_Floor()
    {
        $building = Buildings::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('addFloor', $building->id));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Workspace_Manager_Add_Floor()
    {
        $building = Buildings::factory()->create();
        
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('addFloor', $building->id));

        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_Workspace_Manager_Edit_Floor()
    {
        $floor = Floors::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('editFloor', $floor->id));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Workspace_Manager_Edit_Floor()
    {
        $floor = Floors::factory()->create();
        
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('editFloor', $floor->id));

        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_Workspace_Manager_Room_Manager()
    {
        $floor = Floors::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('roomManager', $floor->id));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Workspace_Manager_Room_Manager()
    {
        $floor = Floors::factory()->create();
        
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('roomManager', $floor->id));

        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_Workspace_Manager_Add_Room()
    {
        $floor = Floors::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('addRoom', $floor->id));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Worskpace_Manager_Add_Room()
    {
        $floor = Floors::factory()->create();
        
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('addRoom', $floor->id));

        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_Workspace_Manager_Edit_Room()
    {
        $room = Rooms::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('editRoom', $room->id));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Workspace_Manager_Edit_Room()
    {
        $room = Rooms::factory()->create();
        
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('editRoom', $room->id));

        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_Workspace_Manager_Desk_Manager()
    {
        $room = Rooms::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('deskManager', $room->id));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Workspace_Manager_Desk_Manager()
    {
        $room = Rooms::factory()->create();
        
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('deskManager', $room->id));

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

        $response = $this->actingAs($user)->get(route('viewRole', $role->role_id));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_View_Role()
    {
        $role = Roles::factory()->create();

        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('viewRole', $role->role_id));

        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_Edit_Role()
    {
        $role = Roles::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('editRole', $role->role_id));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Edit_Role()
    {
        $role = Roles::factory()->create();

        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('editRole', $role->role_id));

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

    public function test_non_admin_can_not_access_Add_User_Manager()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('addUser'));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Add_User_Manager()
    {
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('addUser'));

        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_Policy_Manager()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('policyManager'));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Policy_Manager()
    {
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('policyManager'));

        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_Edit_Room_Restriction_Policy_Manager()
    {
        $user = User::factory()->create();
        $room = Rooms::factory()->create();

        $response = $this->actingAs($user)->get(route('editRoomRestrictionsPolicy', $room->id));
        
        $response->assertStatus(302);
    }

    public function test_admin_can_access_Edit_Room_Restriction_Policy_Manager()
    {
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();
        $room = Rooms::factory()->create();

        $response = $this->actingAs($user)->get(route('editRoomRestrictionsPolicy', $room->id));
        
        $response->assertStatus(200);
    }

    public function test_admin_can_access_Resource_Manager()
    {
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('resourceManager'));

        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_Resource_Manager()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('resourceManager'));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Add_Resource()
    {
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();
        
        $response = $this->actingAs($user)->get(route('addResource'));
        
        $response->assertStatus(200);

    }

    public function test_non_admin_can_not_access_Add_Resource()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('addResource'));

        $response->assertStatus(302);
    }
    
    public function test_admin_can_access_Edit_Resource()
    {
        $resource = Resources::factory()->create();

        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();
        
        $response = $this->actingAs($user)->get(route('editResource', $resource->resource_id));
        
        $response->assertStatus(200);

    }

    public function test_non_admin_can_not_access_Edit_Resource()
    {
        $resource = Resources::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('editResource', $resource->resource_id));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Downlaod_Logs()
    {
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();
        $response = $this->actingAs($user)->get(route('emailLogs'));
        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_Downlaod_Logs()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('emailLogs'));
        $response->assertStatus(302);
    }

    public function test_admin_can_access_Download_Log_Function()
    {
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('downloadLogs'));
        $response->assertDownload();
    }

    public function test_non_admin_can_not_access_Download_Log_Function()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('downloadLogs'));
        $response->assertStatus(302);
    }
    
    public function test_admin_can_access_Booking_Time_Statistics()
    {
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('viewBookingTimeStatistics'));

        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_Booking_Time_Statistics()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('viewBookingTimeStatistics'));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Department_Statistics()
    {
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();
        
        $response = $this->actingAs($user)->get(route('viewDepartmentStatistics'));

        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_Department_Statistics()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('viewDepartmentStatistics'));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Resource_Statistics()
    {
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('viewResourcesStatistics'));

        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_Resource_Statistics()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('viewResourcesStatistics'));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Role_Statistics()
    {
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('viewRolesStatistics'));

        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_Role_Statistics()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('viewRolesStatistics'));

        $response->assertStatus(302);
    }

    public function test_admin_can_access_Usage_Statistics()
    {
        $user = User::factory()->create();
        $user->is_admin = TRUE;
        $user->save();

        $response = $this->actingAs($user)->get(route('usageStatistics'));

        $response->assertStatus(200);
    }

    public function test_non_admin_can_not_access_Usage_Statistics()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('usageStatistics'));

        $response->assertStatus(302);
    }

    //////////////////////////// User-Side ////////////////////////////////

    // =========================BookingsController=========================

    /**
     * A basic route test to check that an unverified user can't access the 'My Bookings' page. 
     *
     * @return void
     */
    public function test_unverified_user_can_not_access_my_bookings_page() {
        // Create an unverified user
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get(route('bookings'));
        $response->assertStatus(302);
    }

    /**
     * A basic route test to check that a verified user can access the 'My Bookings' page. 
     *
     * @return void
     */
    public function test_verified_user_can_access_my_bookings_page()
    {
        // Create a verified user
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('bookings'));

        $response->assertStatus(200);
    }

    // =========================ProfileController=========================

        /**
     * A basic route test to check that an unverified user can't access the 'Profile' page. 
     *
     * @return void
     */
    public function test_unverified_user_can_not_access_profile_page() {
        // Create an unverified user
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get(route('profile'));
        $response->assertStatus(302);
    }

    /**
     * A basic route test to check that a verified user can access the 'Profile' page. 
     *
     * @return void
     */
    public function test_verified_user_can_access_profile_page()
    {
        // Create a verified user
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('profile'));

        $response->assertStatus(200);
    }
    
    // =========================HomeController=========================

        /**
     * A basic route test to check that an unverified user can't access the 'Dashboard' page. 
     *
     * @return void
     */
    public function test_unverified_user_can_not_access_dashboard_page() {
        // Create an unverified user
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get(route('home'));
        $response->assertStatus(302);
    }

    /**
     * A basic route test to check that a verified user can access the 'Dashboard' page. 
     *
     * @return void
     */
    public function test_verified_user_can_access_dashboard_page()
    {
        // Create a verified user
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('home'));

        $response->assertStatus(200);
    }

    // =========================SearchController=========================

        /**
     * A basic route test to check that an unverified user can't access the 'Search Available Bookings' page. 
     *
     * @return void
     */
    public function test_unverified_user_can_not_access_search_available_bookings_page() {
        // Create an unverified user
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get(route('search'));
        $response->assertStatus(302);
    }

    /**
     * A basic route test to check that a verified user can access the 'Dashboard' page. 
     *
     * @return void
     */
    public function test_verified_user_can_access_search_available_bookings_page()
    {
        // Create a verified user
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('search'));

        $response->assertStatus(200);
    }
    
    // =========================ModifyController=========================

        /**
     * A basic route test to check that an unverified user can't access the 'Modify' page. 
     *
     * @return void
     */
    public function test_unverified_user_can_not_access_modify_page() {
        // Create an unverified user
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get(route('modify'));
        $response->assertStatus(302);
    }

    /**
     * A basic route test to check that a verified user can access the 'Dashboard' page. 
     *
     * @return void
     */
    public function test_verified_user_can_access_modify_page()
    {
        // Create a verified user
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('modify'));

        $response->assertStatus(200);
    }

    // =========================ViewDeskController=========================

    /**
     * A basic route test to check that an unverified user can't access the 'View Desk' page. 
     *
     * @return void
     */
    public function test_unverified_user_can_not_access_view_desk_page() {
        // Create an unverified user
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get(route('viewDesk'));
        $response->assertStatus(302);
    }

    /**
     * A basic route test to check that a verified user can access the 'View Desk' page. 
     *
     * @return void
     */
    public function test_verified_user_can_access_view_desk_page()
    {
        // Create a verified user
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('viewDesk'));

        $response->assertStatus(200);
    }

    // =========================FilterController=========================

    /**
     * A basic route test to check that an unverified user can't access the 'Filter' page. 
     *
     * @return void
     */
    public function test_unverified_user_can_not_access_filter_page() {
        // Create an unverified user
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get(route('indicateBuilding'));
        $response->assertStatus(302);
    }

    /**
     * A basic route test to check that a verified user can access the 'Filter' page. 
     *
     * @return void
     */
    public function test_verified_user_can_access_filter_page()
    {
        // Create a verified user
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('indicateBuilding'));

        $response->assertStatus(200);
    }

    // =========================ChangePasswordController=========================

    /**
     * A basic route test to check that an unverified user can't access the 'Change Password' page. 
     *
     * @return void
     */
    public function test_unverified_user_can_not_access_change_password_page() {
        // Create an unverified user
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get(route('changePasswordGet'));
        $response->assertStatus(302);
    }

    /**
     * A basic route test to check that a verified user can access the 'Change Password' page. 
     *
     * @return void
     */
    public function test_verified_user_can_access_change_password_page()
    {
        // Create a verified user
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('changePasswordGet'));

        $response->assertStatus(200);
    }
    
     // =========================ChangeEmailController=========================

    /**
     * A basic route test to check that an unverified user can't access the 'Change Email' page. 
     *
     * @return void
     */
    public function test_unverified_user_can_not_access_change_email_page() {
        // Create an unverified user
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get(route('changeEmailGet'));
        $response->assertStatus(302);
    }

    /**
     * A basic route test to check that a verified user can access the 'Change Email' page. 
     *
     * @return void
     */
    public function test_verified_user_can_access_change_email_page()
    {
        // Create a verified user
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('changeEmailGet'));

        $response->assertStatus(200);
    }
}
