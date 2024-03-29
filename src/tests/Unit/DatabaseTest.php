<?php

namespace Tests\Unit;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DeskController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\BookingTimeStatisticsController;
use App\Http\Controllers\Admin\BuildingController;
use App\Http\Controllers\Admin\CampusController;
use App\Http\Controllers\Admin\DepartmentStatisticsController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\FloorController;
use App\Http\Controllers\Admin\PoliciesController;
use App\Http\Controllers\Admin\ResourceController;
use App\Http\Controllers\Admin\ResourceStatisticsController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\RolesStatisticsController;
use App\Models\Bookings;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\UserController;
use App\Models\Buildings;
use App\Models\Campuses;
use App\Models\Desks;
use App\Models\Faculty;
use App\Models\Floors;
use App\Models\OccupationPolicyLimit;
use App\Models\Resources;
use App\Models\Roles;
use App\Models\Rooms;
use App\Models\User;
use App\Models\Users;
use Carbon\Factory;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Boolean;
use ReflectionClass;

////////////////////////// Admin //////////////////////////
use App\Http\Controllers\User\UserBookingsController;
use App\Models\BookingHistory;
use App\Models\Department;
use App\Models\Resources_Desks;
use App\Models\Resources_Rooms;
use DateTime;

class DatabaseTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_database_stores_user()
    {
        $user = User::factory()->create();

        $this->assertDatabaseHas('users', [
            'email' => $user->email
        ]);
    }

    public function test_Role_Store_Function()
    {
        $role = $this->faker->firstName();
        $nmb = $this->faker->randomNumber(2);
        $max_booking_window = $this->faker->randomNumber(1);
        $max_booking_duration = $this->faker->randomNumber(1);

        $request = Request::create(route('role.store'), 'POST', [
            'role' => $role,
            'num_monthly_bookings' => $nmb,
            'max_booking_window' => $max_booking_window,
            'max_booking_duration' => $max_booking_duration,
        ]);

        $controller = new RoleController();
        $controller->store($request);

        $this->assertDatabaseHas('roles', [
            'role' => $role,
            'num_monthly_bookings' => $nmb,
            'max_booking_window' => $max_booking_window,
            'max_booking_duration' => $max_booking_duration,
        ]);
    }

    public function test_Role_Update_Function()
    {
        $roleToBeUpdated = Roles::factory()->create();

        $role = $this->faker->firstName();
        $nmb = $this->faker->randomNumber(2);
        $max_booking_window = $this->faker->randomNumber(1);
        $max_booking_duration = $this->faker->randomNumber(1);

        $request = Request::create(route('roleUpdate', $roleToBeUpdated->role_id), 'POST', [
            'role' => $role,
            'num_monthly_bookings' => $nmb,
            'max_booking_window' => $max_booking_window,
            'max_booking_duration' => $max_booking_duration,
        ]);

        $controller = new RoleController();
        $controller->update($request, $roleToBeUpdated->role_id);

        $this->assertDatabaseHas('roles', [
            'role' => $role,
            'num_monthly_bookings' => $nmb,
            'max_booking_window' => $max_booking_window,
            'max_booking_duration' => $max_booking_duration,
        ]);
    }

    public function test_Role_Does_Delete_Function()
    {
        $roleToBeDeleted = Roles::factory()->create();
        $roleToBeDeleted->role_id = 6;
        $roleToBeDeleted->save();

        $controller = new RoleController();
        $controller->destroy($roleToBeDeleted->role_id);

        $this->assertDatabaseMissing('roles', [
            'role_id' => $roleToBeDeleted->role_id,
        ]);
    }

    public function test_Role_Does_Not_Delete_Function() 
    {
        $roleToBeDeleted = Roles::factory()->create();
        $roleToBeDeleted->role_id = 2;
        $roleToBeDeleted->save();

        $controller = new RoleController();
        $controller->destroy($roleToBeDeleted->role_id);

        $this->assertDatabaseHas('roles', [
            'role_id' => $roleToBeDeleted->role_id,
        ]);
    
    }
    
    public function test_Desk_Store_Function()
    {
        $room = Rooms::factory()->create();
        $x = $this->faker->randomNumber(2);
        $y = $this->faker->randomNumber(2);
        $isClosed = $this->faker->boolean(50);


        $request = Request::create(route('deskStore'), 'POST', [
            'room_id' => $room->id,
            'pos_x' => $x,
            'pos_y' => $y,
            'is_closed' => $isClosed,
        ]);

        $controller = new DeskController();
        $controller->store($request);

        $this->assertDatabaseHas('desks', [
            'room_id' => $room->id,
            'pos_x' => $x,
            'pos_y' => $y,
            'is_closed' => $isClosed,
        ]);
    }

    public function test_Desk_Store_With_Resources_Function()
    {
        $room = Rooms::factory()->create();
        $resource1 = Resources::factory()->create();
        $resource2 = Resources::factory()->create();
        $resource3 = Resources::factory()->create();

        $resource_ids = [$resource1->resource_id, $resource2->resource_id, $resource3->resource_id];

        $pos_x = $this->faker->numberBetween(1, 40);
        $pos_y = $this->faker->numberBetween(1, 40);
        $is_closed = $this->faker->boolean(50);

        $request = Request::create(route('deskStore'), 'POST', [
            'room_id' => $room->id,
            'pos_x' => $pos_x,
            'pos_y' => $pos_y,
            'is_closed' => $is_closed,
            'resource_ids' => $resource_ids,
        ]);

        $controller = new DeskController();
        $controller->store($request);

        $this->assertDatabaseHas('desks', [
            'room_id' => $room->id,
            'pos_x' => $pos_x,
            'pos_y' => $pos_y,
            'is_closed' => $is_closed,
        ]);

        $desk = Desks::where('room_id', $room->id)->where('pos_x', $pos_x)->where('pos_y', $pos_y)->where('is_closed', $is_closed)->first();

        for ($i = 0; $i < count($resource_ids); $i++) {
            $this->assertDatabaseHas('resources_desks', [
                'resource_id' => $resource_ids[$i],
                'desk_id' => $desk->id,
            ]);
        }
    }


    public function test_Desk_Update_Function()
    {
        $deskToBeUpdated = Desks::factory()->create();

        $x = $this->faker->randomNumber(2);
        $y = $this->faker->randomNumber(2);
        $isClosed = $this->faker->boolean(50);

        $request = Request::create(route('deskUpdate'), 'POST', [
            'id' => $deskToBeUpdated->id,
            'room_id' => $deskToBeUpdated->room_id,
            'pos_x' => $x,
            'pos_y' => $y,
            'is_closed' => $isClosed,
        ]);

        $controller = new DeskController();
        $controller->update($request);

        $this->assertDatabaseHas('desks', [
            'id' => $deskToBeUpdated->id,
            'room_id' => $deskToBeUpdated->room_id,
            'pos_x' => $x,
            'pos_y' => $y,
            'is_closed' => $isClosed,
        ]);
    }

    public function test_Desk_Update_With_Resources_Function()
    {
        $deskToBeUpdated = Desks::factory()->create();

        $pos_x = $this->faker->numberBetween(1, 40);
        $pos_y = $this->faker->numberBetween(1, 40);
        $is_closed = $this->faker->boolean(50);
        $resource1 = Resources::factory()->create();
        $resource2 = Resources::factory()->create();
        $resource3 = Resources::factory()->create();
        $resource_ids = [$resource1->resource_id, $resource2->resource_id, $resource3->resource_id];

        $request = Request::create(route('deskUpdate'), 'POST', [
            'id' => $deskToBeUpdated->id,
            'room_id' => $deskToBeUpdated->room_id,
            'pos_x' => $pos_x,
            'pos_y' => $pos_y,
            'is_closed' => $is_closed,
            'resource_ids' => $resource_ids,
        ]);

        $controller = new DeskController();
        $controller->update($request, $deskToBeUpdated->id);

        $this->assertDatabaseHas('desks', [
            'id' => $deskToBeUpdated->id,
            'room_id' => $deskToBeUpdated->room_id,
            'pos_x' => $pos_x,
            'pos_y' => $pos_y,
            'is_closed' => $is_closed,
        ]);

        for ($i = 0; $i < count($resource_ids); $i++) {
            $this->assertDatabaseHas('resources_desks', [
                'resource_id' => $resource_ids[$i],
                'desk_id' => $deskToBeUpdated->id,
            ]);
        }
    }

    public function test_Desk_Delete_Function()
    {
        $deskToBeDeleted = Desks::factory()->create();

        $controller = new DeskController();
        $controller->destroy($deskToBeDeleted->id);

        $this->assertDatabaseHas('desks', [
            'id' => $deskToBeDeleted->id,
        ]);

        // Get Soft Deleted desk
        $desk = DB::table('desks')->where('id', $deskToBeDeleted->id)->first();
        
        $this->assertNotNull($desk->deleted_at);
    }

    public function test_Booking_Store_Function()
    {
        $user = User::factory()->create();
        $desk = Desks::factory()->create();
        $book_start = Carbon::now('GMT-7');
        $book_end = Carbon::tomorrow();

        $request = Request::create(route('booking.store'), 'POST', [
            'user_id' => $user->id,
            'desk_id' => $desk->id,
            'book_time_start' => $book_start,
            'book_time_end' => $book_end,
        ]);

        $controller = new BookingController();
        $controller->store($request);

        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'desk_id' => $desk->id,
            'book_time_start' => $book_start,
            'book_time_end' => $book_end,
        ]);
    }

    public function test_Booking_Update_Function()
    {
        $bookingToBeUpdated = Bookings::factory()->create();
        $user = User::factory()->create();
        $desk = Desks::factory()->create();

        $book_start = Carbon::now('GMT-7');
        $book_end = Carbon::tomorrow();

        $request = Request::create(route('bookingUpdate', $bookingToBeUpdated->id), 'POST', [
            'user_id' => $user->id,
            'desk_id' => $desk->id,
            'book_time_start' => $book_start,
            'book_time_end' => $book_end,
        ]);

        $controller = new BookingController();
        $controller->update($request, $bookingToBeUpdated->id);

        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'desk_id' => $desk->id,
            'book_time_start' => $book_start,
            'book_time_end' => $book_end,
        ]);
    }

    public function test_Booking_Delete_Function()
    {
        $BookingToBeDeleted = Bookings::factory()->create();

        $controller = new BookingController();
        $controller->destroy($BookingToBeDeleted->id);

        $this->assertDatabaseMissing('bookings', [
            'id' => $BookingToBeDeleted->id,
        ]);
    }

    public function test_Campus_Store_Function()
    {
        $campus = $this->faker->firstName();
        $available = $this->faker->boolean(50);

        $request = Request::create(route('campusStore'), 'POST', [
            'name' => $campus,
            'is_closed' => $available
        ]);

        $controller = new CampusController();
        $controller->store($request);

        $this->assertDatabaseHas('campuses', [
            'name' => $campus,
            'is_closed' => $available
        ]);
    }

    public function test_Campus_Update_Function()
    {
        $campusToBeUpdated = Campuses::factory()->create();

        $campus = $this->faker->firstName();
        $available = $this->faker->boolean(50);

        $request = Request::create(route('campusUpdate', $campusToBeUpdated->id), 'POST', [
            'name' => $campus,
            'is_closed' => $available
        ]);

        $controller = new CampusController();
        $controller->update($request, $campusToBeUpdated->id);

        $this->assertDatabaseHas('campuses', [
            'name' => $campus,
            'is_closed' => $available
        ]);
    }

    public function test_Campus_Delete_Function()
    {
        $campusToBeDeleted = Campuses::factory()->create();

        $controller = new CampusController();
        $controller->destroy($campusToBeDeleted->id);

        $this->assertDatabaseHas('campuses', [
            'id' => $campusToBeDeleted->id,
        ]);

        // Get Soft Deleted campus
        $campus = DB::table('campuses')->where('id', $campusToBeDeleted->id)->first();
        
        $this->assertNotNull($campus->deleted_at);
    }


    public function test_Room_Store_Function()
    {
        $floor = Floors::factory()->create();

        $name = $this->faker->firstName();
        $occupancy = $this->faker->numberBetween(1, 40);
        $is_closed = $this->faker->boolean(50);

        $request = Request::create(route('roomStore'), 'POST', [
            'floor_id' => $floor->id,
            'name' => $name,
            'occupancy' => $occupancy,
            'is_closed' => $is_closed,
        ]);

        $controller = new RoomController();
        $controller->store($request);

        $this->assertDatabaseHas('rooms', [
            'floor_id' => $floor->id,
            'name' => $name,
            'occupancy' => $occupancy,
            'is_closed' => $is_closed,
        ]);
    }

    public function test_Room_Store_With_Resources_Function()
    {
        $floor = Floors::factory()->create();
        $resource1 = Resources::factory()->create();
        $resource2 = Resources::factory()->create();
        $resource3 = Resources::factory()->create();

        $resource_ids = [$resource1->resource_id, $resource2->resource_id, $resource3->resource_id];
        $descriptions = [$resource1->description, $resource2->description, $resource3->description];

        $name = $this->faker->firstName();
        $occupancy = $this->faker->numberBetween(1, 40);
        $is_closed = $this->faker->boolean(50);

        $request = Request::create(route('roomStore'), 'POST', [
            'floor_id' => $floor->id,
            'name' => $name,
            'occupancy' => $occupancy,
            'is_closed' => $is_closed,
            'resource_ids' => $resource_ids,
            'descriptions' => $descriptions,
        ]);

        $controller = new RoomController();
        $controller->store($request);

        $this->assertDatabaseHas('rooms', [
            'floor_id' => $floor->id,
            'name' => $name,
            'occupancy' => $occupancy,
            'is_closed' => $is_closed,
        ]);

        $room = Rooms::where('floor_id', $floor->id)->where('name', $name)->where('occupancy', $occupancy)->where('is_closed', $is_closed)->first();

        for ($i = 0; $i < count($resource_ids); $i++) {
            $this->assertDatabaseHas('resources_rooms', [
                'resource_id' => $resource_ids[$i],
                'room_id' => $room->id,
                'description' => $descriptions[$i],
            ]);
        }
    }

    public function test_Room_Update_With_Resources_Function()
    {
        $roomToBeUpdated = Rooms::factory()->create();

        $name = $this->faker->firstName();
        $occupancy = $this->faker->numberBetween(1, 40);
        $is_closed = $this->faker->boolean(50);
        $resource1 = Resources::factory()->create();
        $resource2 = Resources::factory()->create();
        $resource3 = Resources::factory()->create();
        $resource_ids = [$resource1->resource_id, $resource2->resource_id, $resource3->resource_id];
        $descriptions = [$resource1->description, $resource2->description, $resource3->description];

        $request = Request::create(route('roomUpdate', $roomToBeUpdated->id), 'POST', [
            'floor_id' => $roomToBeUpdated->id,
            'name' => $name,
            'occupancy' => $occupancy,
            'is_closed' => $is_closed,
            'resource_ids' => $resource_ids,
            'descriptions' => $descriptions,
        ]);

        $controller = new RoomController();
        $controller->update($request, $roomToBeUpdated->id);

        $this->assertDatabaseHas('rooms', [
            'floor_id' => $roomToBeUpdated->id,
            'name' => $name,
            'occupancy' => $occupancy,
            'is_closed' => $is_closed,
        ]);

        for ($i = 0; $i < count($resource_ids); $i++) {
            $this->assertDatabaseHas('resources_rooms', [
                'resource_id' => $resource_ids[$i],
                'room_id' => $roomToBeUpdated->id,
                'description' => $descriptions[$i],
            ]);
        }
    }

    public function test_Room_Update_Function()
    {
        $roomToBeUpdated = Rooms::factory()->create();

        $name = $this->faker->firstName();
        $occupancy = $this->faker->numberBetween(1, 40);
        $is_closed = $this->faker->boolean(50);

        $request = Request::create(route('roomUpdate', $roomToBeUpdated->id), 'POST', [
            'floor_id' => $roomToBeUpdated->id,
            'name' => $name,
            'occupancy' => $occupancy,
            'is_closed' => $is_closed,
        ]);

        $controller = new RoomController();
        $controller->update($request, $roomToBeUpdated->id);

        $this->assertDatabaseHas('rooms', [
            'floor_id' => $roomToBeUpdated->id,
            'name' => $name,
            'occupancy' => $occupancy,
            'is_closed' => $is_closed,
        ]);
    }

    public function test_Room_Delete_Function()
    {
        $roomToBeDeleted = Rooms::factory()->create();

        $controller = new RoomController();
        $controller->destroy($roomToBeDeleted->id);

        $this->assertDatabaseHas('rooms', [
            'id' => $roomToBeDeleted->id,
        ]);

        // Get Soft Deleted room
        $room = DB::table('rooms')->where('id', $roomToBeDeleted->id)->first();
        
        $this->assertNotNull($room->deleted_at);
    }

    public function test_Room_Add_Size_Function()
    {
        $roomToBeUpdated = Rooms::factory()->create();

        $rows = $this->faker->numberBetween(1, 40);
        $cols = $this->faker->numberBetween(1, 40);

        $request = Request::create(route('roomSizeUpdate', $roomToBeUpdated->id), 'POST', [
            'id' => $roomToBeUpdated->id,
            'rows' => $rows,
            'cols' => $cols,
        ]);
        $controller = new RoomController();
        $controller->addRoomSize($request, $roomToBeUpdated->id);

        $this->assertDatabaseHas('rooms', [
            'id' => $roomToBeUpdated->id,
            'rows' => $rows,
            'cols' => $cols,
        ]);
    }

    public function test_User_Store_Function()
    {
        $role_id = Roles::factory()->create()->role_id;
        $department = Department::factory()->create();
        $department_id = $department->department_id;
        $faculty_id = $department->faculty_id;

        $first_name = $this->faker->firstName();
        $last_name = $this->faker->lastName();
        $email = $this->faker->email();
        $password = $this->faker->password(9);
        $is_suspended = $this->faker->boolean(50);
        $is_admin = $this->faker->boolean(50);

        $request = Request::create(route('user.store'), 'POST', [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'password' => $password,
            'is_suspended' => $is_suspended,
            'is_admin' => $is_admin,
            'role_id' => $role_id,
            'faculty_id' => $faculty_id,
            'department_id' => $department_id
        ]);

        $controller = new UserController();
        $controller->store($request);

        $this->assertDatabaseHas('users', [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'is_suspended' => $is_suspended,
            'is_admin' => $is_admin,
            'role_id' => $role_id,
            'department_id' => $department_id
        ]);
    }

    public function test_User_Update_Function()
    {
        $userToBeUpdated = User::factory()->create();
        $role_id = Roles::factory()->create()->role_id;
        $department = Department::factory()->create();
        $department_id = $department->department_id;
        $faculty_id = $department->faculty_id;

        $first_name = $this->faker->firstName();
        $last_name = $this->faker->lastName();
        $email = $this->faker->email();
        $is_suspended = $this->faker->boolean(50);
        $is_admin = $this->faker->boolean(50);

        $request = Request::create(route('userUpdate', $userToBeUpdated->id), 'POST', [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'is_suspended' => $is_suspended,
            'is_admin' => $is_admin,
            'role_id' => $role_id,
            'faculty_id' => $faculty_id,
            'department_id' => $department_id,
        ]);

        $controller = new UserController();
        $controller->update($request, $userToBeUpdated->id);

        $this->assertDatabaseHas('users', [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'is_suspended' => $is_suspended,
            'is_admin' => $is_admin,
            'role_id' => $role_id,
            'department_id' => $department_id
        ]);
    }

    public function test_User_Suspended_Update_Function()
    {
        $userToBeUpdated = User::factory()->create();
        $userToBeUpdated->is_suspended = false;
        $userToBeUpdated->save();

        $role_id = Roles::factory()->create()->role_id;
        $department = Department::factory()->create();
        $department_id = $department->department_id;
        $faculty_id = $department->faculty_id;

        $first_name = $this->faker->firstName();
        $last_name = $this->faker->lastName();
        $email = $this->faker->email();
        $is_suspended = true;
        $is_admin = $this->faker->boolean(50);

        $request = Request::create(route('userUpdate', $userToBeUpdated->id), 'POST', [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'is_suspended' => $is_suspended,
            'is_admin' => $is_admin,
            'role_id' => $role_id,
            'faculty_id' => $faculty_id,
            'department_id' => $department_id,
        ]);

        $controller = new UserController();
        $controller->update($request, $userToBeUpdated->id);

        $this->assertDatabaseHas('users', [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'is_suspended' => $is_suspended,
            'is_admin' => $is_admin,
            'role_id' => $role_id,
            'department_id' => $department_id
        ]);
    }

    public function test_User_UnSuspended_Update_Function()
    {
        $userToBeUpdated = User::factory()->create();
        $userToBeUpdated->is_suspended = true;
        $userToBeUpdated->save();

        $role_id = Roles::factory()->create()->role_id;
        $department = Department::factory()->create();
        $department_id = $department->department_id;
        $faculty_id = $department->faculty_id;

        $first_name = $this->faker->firstName();
        $last_name = $this->faker->lastName();
        $email = $this->faker->email();
        $is_suspended = false;
        $is_admin = $this->faker->boolean(50);

        $request = Request::create(route('userUpdate', $userToBeUpdated->id), 'POST', [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'is_admin' => $is_admin,
            'role_id' => $role_id,
            'faculty_id' => $faculty_id,
            'department_id' => $department_id,
        ]);

        $controller = new UserController();
        $controller->update($request, $userToBeUpdated->id);

        $this->assertDatabaseHas('users', [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'is_suspended' => $is_suspended,
            'is_admin' => $is_admin,
            'role_id' => $role_id,
            'department_id' => $department_id
        ]);
    }

    public function test_User_Delete_Function()
    {
        $userToBeDeleted = User::factory()->create();

        $controller = new UserController();
        $controller->destroy($userToBeDeleted->id);

        $this->assertDatabaseHas('users', [
            'id' => $userToBeDeleted->id,
        ]);

        // Get Soft Deleted desk
        $user = DB::table('users')->where('id', $userToBeDeleted->id)->first();
        
        $this->assertNotNull($user->deleted_at);
    }


    public function test_Building_Store_Function()
    {
        $campus = Campuses::factory()->create();
        $name = $this->faker->firstName();
        $available = $this->faker->boolean(50);

        $request = Request::create(route('buildingStore'), 'POST', [
            'campus_id' => $campus->id,
            'name' => $name,
            'is_closed' => $available

        ]);

        $controller = new BuildingController();
        $controller->store($request);

        $this->assertDatabaseHas('buildings', [
            'campus_id' => $campus->id,
            'name' => $name,
            'is_closed' => $available
        ]);
    }

    public function test_Building_Update_Function()
    {
        $buildingToBeUpdated = Buildings::factory()->create();

        $building = $this->faker->firstName();
        $available = $this->faker->boolean(50);

        $request = Request::create(route('buildingUpdate', $buildingToBeUpdated->id), 'POST', [
            'campus_id' => $buildingToBeUpdated->campus_id,
            'name' => $building,
            'is_closed' => $available
        ]);

        $controller = new BuildingController();
        $controller->update($request, $buildingToBeUpdated->id);

        $this->assertDatabaseHas('buildings', [
            'campus_id' => $buildingToBeUpdated->campus_id,
            'name' => $building,
            'is_closed' => $available
        ]);
    }
    
    public function test_Building_Delete_Function()
    {
        $buildingToBeDeleted = Buildings::factory()->create();

        $controller = new BuildingController();
        $controller->destroy($buildingToBeDeleted->id);

        $this->assertDatabaseHas('buildings', [
            'id' => $buildingToBeDeleted->id,
        ]);

        // Get Soft Deleted building
        $building = DB::table('buildings')->where('id', $buildingToBeDeleted->id)->first();
        
        $this->assertNotNull($building->deleted_at);
    }

    public function test_Floor_Store_Function()
    {
        $building_id = Buildings::factory()->create()->id;

        $floor_num = $this->faker->numberBetween(0, 10);
        $is_closed = $this->faker->boolean(50);

        $request = Request::create(route('floor.store'), 'POST', [
            'building_id' => $building_id,
            'floor_num' => $floor_num,
            'is_closed' => $is_closed,
        ]);

        $controller = new FloorController();
        $controller->store($request);

        $this->assertDatabaseHas('floors', [
            'building_id' => $building_id,
            'floor_num' => $floor_num,
            'is_closed' => $is_closed,
        ]);
    }

    public function test_Floor_Update_Function()
    {
        $floorToBeUpdated = Floors::factory()->create();
        $floor_num = $this->faker->numberBetween(0, 10);
        $is_closed = $this->faker->boolean(50);

        $request = Request::create(route('floorUpdate', $floorToBeUpdated->id), 'POST', [
            'building_id' => $floorToBeUpdated->building_id,
            'floor_num' => $floor_num,
            'is_closed' => $is_closed,
        ]);
        $controller = new FloorController();
        $controller->update($request, $floorToBeUpdated->id);

        $this->assertDatabaseHas('floors', [
            'building_id' => $floorToBeUpdated->building_id,
            'floor_num' => $floor_num,
            'is_closed' => $is_closed,
        ]);
    }

    public function test_Floor_Delete_Function()
    {
        $floorToBeDeleted = Floors::factory()->create();

        $controller = new FloorController();
        $controller->destroy($floorToBeDeleted->id);

        $this->assertDatabaseHas('floors', [
            'id' => $floorToBeDeleted->id,
        ]);

        // Get Soft Deleted floor
        $floor = DB::table('floors')->where('id', $floorToBeDeleted->id)->first();
        
        $this->assertNotNull($floor->deleted_at);
    }

    public function test_task_scheduler_resets_user_bookings_used() {
        $user1 = User::factory()->create();
        $user1->bookings_used = 8;
        $user1->save();

        $user2 = User::factory()->create();
        $user2->bookings_used = 10;
        $user2->save();
        
        // call the command that gets executed by the schedular
        Artisan::call('reset:bookings');

        $users = DB::table('users')->get();
        foreach ($users as $user) {
            $this->assertTrue($user->bookings_used == 0);
        }
    }

    public function test_task_scheduler_moves_old_bookings_and_stores_them_in_history_table() {
        // verify this get moved to the history table
        $booking_old = Bookings::factory()->create();
        $booking_old->book_time_start = Carbon::yesterday();
        $booking_old->book_time_end = Carbon::yesterday();
        $booking_old->save();

        // verify this stays in the booking table
        $booking_new = Bookings::factory()->create();
        $booking_new->book_time_start = Carbon::tomorrow();
        $booking_new->book_time_end = Carbon::tomorrow();
        $booking_new->save();

        // call the command that gets executed by the schedular
        Artisan::call('verify:bookings');

        // check that old data is no longer in the bookings table
        $this->assertDatabaseMissing('bookings', [
            'user_id' => $booking_old->user_id,
            'desk_id' => $booking_old->desk_id,
            'book_time_start' => $booking_old->book_time_start,
            'book_time_end' => $booking_old->book_time_end,
        ]);
        // check that old data is now in the booking_history table
        $this->assertDatabaseHas('booking_history', [
            'user_id' => $booking_old->user_id,
            'desk_id' => $booking_old->desk_id,
            'book_time_start' => $booking_old->book_time_start,
            'book_time_end' => $booking_old->book_time_end,
        ]);

        // check that booking in the future is in the bookings table
        $this->assertDatabaseHas('bookings', [
            'user_id' => $booking_new->user_id,
            'desk_id' => $booking_new->desk_id,
            'book_time_start' => $booking_new->book_time_start,
            'book_time_end' => $booking_new->book_time_end,
        ]);
        // check that booking in the future is not in the booking_history table
        $this->assertDatabaseMissing('booking_history', [
            'user_id' => $booking_new->user_id,
            'desk_id' => $booking_new->desk_id,
            'book_time_start' => $booking_new->book_time_start,
            'book_time_end' => $booking_new->book_time_end,
        ]);
    }

    public function test_Registration_Create_Function()
    {
        $role_id = Roles::factory()->create()->role_id;
        $department_id = Department::factory()->create()->faculty_id;

        $array = [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->safeEmail(),
            'password' => $this->faker->password(8),
            'role_id' => $role_id,
            'department_id' => $department_id,
        ];

        $controller = new RegisterController();
        $controller->testCreateFunction($array);
        // remove password when asserting
        array_splice($array, 3);

        $this->assertDatabaseHas('users', $array);
    }

    public function test_Policy_Restrictions_With_Multiple_Roles_Function()
    {
        $room = Rooms::factory()->create();
        $role1 = Roles::factory()->create();
        $role2 = Roles::factory()->create();
        $role3 = Roles::factory()->create();


        $request = Request::create(route('editRestrictionsPolicy', $room->id), 'POST', [
            'role_ids' => [
                $role1->role_id,
                $role2->role_id,
                $role3->role_id,
            ],
        ]);
        
        $controller = new PoliciesController();
        $controller->editRestrictionsPolicy($request, $room->id);

        $this->assertDatabaseHas('role_room', [
            'role_id' => $role1->role_id,
            'room_id' => $room->id,
        ]);
        $this->assertDatabaseHas('role_room', [
            'role_id' => $role2->role_id,
            'room_id' => $room->id,
        ]);
        $this->assertDatabaseHas('role_room', [
            'role_id' => $role3->role_id,
            'room_id' => $room->id,
        ]);
    }

    public function test_Update_Policy_Occupation_limit_Function()
    {
        // create OccupationPolicyLimit where id == 1
        $occupation = new OccupationPolicyLimit;
        $occupation->id = 1;
        $occupation->percentage = 100;
        $occupation->save();

        $newPercentage = 75;

        $request = Request::create(route('editOccupationPolicy', $occupation->id), 'POST', [
            'percentage' => $newPercentage,
        ]);

        $controller = new PoliciesController();
        $controller->editOccupationPolicy($request, $occupation->id);

        $this->assertDatabaseHas('policy_occupation_limit', [
            'id' => $occupation->id,
            'percentage' => $newPercentage,
        ]);
    }

    public function test_Restore_Policy_Occupation_limit_Function()
    {
        $controller = new PoliciesController();
        $controller->restoreOccupationPolicy();

        $this->assertDatabaseHas('policy_occupation_limit', [
            'id' => 1,
            'percentage' => 100,
        ]);
    }

    public function test_Policy_Bookings_Role_Update_Function()
    {
        $roleToBeUpdated = Roles::factory()->create();

        $max_booking_window = $this->faker->randomNumber(1);
        $max_booking_duration = $this->faker->randomNumber(1);

        $request = Request::create(route('editRolesBookingPolicies', $roleToBeUpdated->role_id), 'POST', [
            'max_booking_window' => $max_booking_window,
            'max_booking_duration' => $max_booking_duration,
        ]);

        $controller = new PoliciesController();
        $controller->editRolesBookingPolicies($request, $roleToBeUpdated->role_id);

        $this->assertDatabaseHas('roles', [
            'max_booking_window' => $max_booking_window,
            'max_booking_duration' => $max_booking_duration,
        ]);
    }
    
    public function test_Resource_Create_Function()
    {
        $resource_type = $this->faker->name();
        $icon = $this->faker->name();
        $colour = $this->faker->hexColor();


        $request = Request::create(route('resource.store'), 'POST', [
            'resource_type' => $resource_type,
            'icon' => $icon,
            'colour' => $colour,
        ]);

        $controller = new ResourceController();
        $controller->store($request);

        $this->assertDatabaseHas('resources', [
            'resource_type' => $resource_type,
            'icon' => $icon,
            'colour' => $colour,
        ]);
    }

    public function test_Resource_Update_Function()
    {
        $resourceToBeUpdated = Resources::factory()->create();

        $resource_type = $this->faker->name();
        $icon = $this->faker->name();
        $colour = $this->faker->hexColor();

        $request = Request::create(route('resourceUpdate', $resourceToBeUpdated->resource_id), 'POST', [
            'resource_type' => $resource_type,
            'icon' => $icon,
            'colour' => $colour,
        ]);
        $controller = new ResourceController();
        $controller->update($request, $resourceToBeUpdated->resource_id);

        $this->assertDatabaseHas('resources', [
            'resource_type' => $resource_type,
            'icon' => $icon,
            'colour' => $colour,
        ]);
    }

    public function test_Resource_Delete_Function()
    {
        $resourceToBeDeleted = Resources::factory()->create();

        $controller = new ResourceController();
        $controller->destroy($resourceToBeDeleted->resource_id);

        $this->assertDatabaseMissing('resources', [
            'resource_id' => $resourceToBeDeleted->resource_id,
        ]);
    }

    public function test_Room_Update_Size_Delete_Desk_Function() 
    {   
        //Create Room
        $roomToBeUpdated = Rooms::factory()->create();
        //Set Rows and Cols of Room 
        $rows = 5;
        $cols = 5;

        $requestRoom = Request::create(route('roomSizeUpdate', $roomToBeUpdated->id), 'POST', [
            'rows' => $rows,
            'cols' => $cols,
        ]);
        
        $controller = new RoomController();
        $controller->addRoomSize($requestRoom, $roomToBeUpdated->id);

        $this->assertDatabaseHas('rooms', [
            'id' => $roomToBeUpdated->id,
            'rows' => $rows,
            'cols' => $cols,
        ]);

        $pos_x = $this->faker->numberBetween(1, 4);
        $pos_y = $this->faker->numberBetween(1, 4);
        $isClosed = $this->faker->boolean(50);

        $request = Request::create(route('deskStore'), 'POST', [
            'room_id' => $roomToBeUpdated->id,
            'pos_x' => $pos_x,
            'pos_y' => $pos_y,
            'is_closed' => $isClosed,
        ]);

        //Create Desk
        $controller = new DeskController();
        $controller->store($request);

        $deskToBeDeleted = DB::table('desks')->where('room_id', $roomToBeUpdated->id)->where('pos_x', $pos_x)->where('pos_y', $pos_y)->first();

        $this->assertDatabaseHas('desks', [
            'room_id' => $roomToBeUpdated->id,
            'id' => $deskToBeDeleted->id,
            'pos_x' => $deskToBeDeleted->pos_x,
            'pos_y' => $deskToBeDeleted->pos_y,
        ]);
        
        //Change room size
        $rows = 0;
        $cols = 0;

        $requestRoom = Request::create(route('roomSizeUpdate', $roomToBeUpdated->id), 'POST', [
            'rows' => $rows,
            'cols' => $cols,
        ]);
        
        $controller = new RoomController();
        $controller->addRoomSize($requestRoom, $roomToBeUpdated->id);
        
        $this->assertDatabaseHas('rooms', [
            'id' => $roomToBeUpdated->id,
            'rows' => $rows,
            'cols' => $cols,
        ]);
        // validate size has update & desk has been deleted
        $desk = DB::table('desks')->where('id', $deskToBeDeleted->id)->first();
        
        $this->assertNotNull($desk->deleted_at);
    }

    public function test_User_Role_Default_On_Delete_Function()
    {
        $roleDefault = Roles::factory()->create();
        $roleDefault->role_id = 1;
        $roleDefault->save();

        $user = User::factory()->create();
        $role = Roles::find($user->role_id);
        $role->role_id = 6;
        $role->save();

        $controller = new RoleController();
        $controller->destroy($role->role_id);

        $this->assertDatabaseMissing('roles', [
            'role_id' => $role->role_id,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role_id' => $roleDefault->role_id,
        ]);
    }

    public function test_Get_Filter_Resources_Desks_Positive_Function() {
        $user = User::factory()->create();
        $user->is_admin = true;
        $user->save();

        $user = User::factory()->create();
        $desk = Desks::factory()->create();

        $booking_history = new BookingHistory;
        $booking_time_start = Carbon::yesterday();
        $booking_time_end =  Carbon::now('GMT-7');
        $booking_history->user_id = $user->id;
        $booking_history->desk_id = $desk->id;
        $booking_history->book_time_start = $booking_time_start;
        $booking_history->book_time_end = $booking_time_end;
        $booking_history->save(); 

        $resource = Resources::factory()->create();
        $deskResource = new Resources_Desks;
        $deskResource->desk_id = $desk->id;
        $deskResource->resource_id = $resource->resource_id;
        $deskResource->save();

        $date1 = new DateTime(Carbon::now()->subMonth());
        $result1 = $date1->format('Y-m-d H:i:s');
        $date2 = new DateTime(Carbon::now()->addMonth());
        $result2 = $date2->format('Y-m-d H:i:s');

        $dateRange = $result1 .' - '. $result2;

        $request = Request::create('', '', [
            'dateRange' => $dateRange,
        ]);

        $controller = new ResourceStatisticsController();
        $response = $controller->getFilterResources($request);

        $this->assertSame(json_encode([[['name' => $resource->resource_type,'y' => 100,]], []]),$response->getContent());
    }

    public function test_Get_Filter_Resources_Desks_Negative_Function() {
        $user = User::factory()->create();
        $user->is_admin = true;
        $user->save();

        $user = User::factory()->create();
        $desk = Desks::factory()->create();

        $booking_history = new BookingHistory;
        $booking_time_start = Carbon::yesterday();
        $booking_time_end =  Carbon::now('GMT-7');
        $booking_history->user_id = $user->id;
        $booking_history->desk_id = $desk->id;
        $booking_history->book_time_start = $booking_time_start;
        $booking_history->book_time_end = $booking_time_end;
        $booking_history->save(); 

        $resource = Resources::factory()->create();
        $deskResource = new Resources_Desks;
        $deskResource->desk_id = $desk->id;
        $deskResource->resource_id = $resource->resource_id;
        $deskResource->save();

        $date1 = new DateTime(Carbon::now()->subMonth(3));
        $result1 = $date1->format('Y-m-d H:i:s');
        $date2 = new DateTime(Carbon::now()->subMonth(2));
        $result2 = $date2->format('Y-m-d H:i:s');

        $dateRange = $result1 .' - '. $result2;

        $request = Request::create('', '', [
            'dateRange' => $dateRange,
        ]);

        $controller = new ResourceStatisticsController();
        $response = $controller->getFilterResources($request);

        $this->assertSame(json_encode([[],[]]),$response->getContent());
    }

    public function test_Get_Filter_Resources_Rooms_Positive_Function() {
        $user = User::factory()->create();
        $user->is_admin = true;
        $user->save();

        $user = User::factory()->create();
        $room = Rooms::factory()->create();
        $desk = Desks::factory()->create();

        $desk->room_id = $room->id;
        $desk->save();

        $booking_history = new BookingHistory;
        $booking_time_start = Carbon::yesterday();
        $booking_time_end =  Carbon::now('GMT-7');
        $booking_history->user_id = $user->id;
        $booking_history->desk_id = $desk->id;
        $booking_history->book_time_start = $booking_time_start;
        $booking_history->book_time_end = $booking_time_end;
        $booking_history->save(); 

        $resource = Resources::factory()->create();
        $roomResource = new Resources_Rooms;
        $roomResource->room_id = $room->id;
        $roomResource->resource_id = $resource->resource_id;
        $roomResource->save();

        $date1 = new DateTime(Carbon::now()->subMonth());
        $result1 = $date1->format('Y-m-d H:i:s');
        $date2 = new DateTime(Carbon::now()->addMonth());
        $result2 = $date2->format('Y-m-d H:i:s');

        $dateRange = $result1 .' - '. $result2;

        $request = Request::create('', '', [
            'dateRange' => $dateRange,
        ]);

        $controller = new ResourceStatisticsController();
        $response = $controller->getFilterResources($request);

        $this->assertSame(json_encode([ [], [['name' => $resource->resource_type,'y' => 100]]]),$response->getContent());

    }

    public function test_Get_Filter_Resources_Rooms_Negative_Function() {
        $user = User::factory()->create();
        $user->is_admin = true;
        $user->save();

        $user = User::factory()->create();
        $room = Rooms::factory()->create();
        $desk = Desks::factory()->create();

        $desk->room_id = $room->id;
        $desk->save();

        $booking_history = new BookingHistory;
        $booking_time_start = Carbon::yesterday();
        $booking_time_end =  Carbon::now('GMT-7');
        $booking_history->user_id = $user->id;
        $booking_history->desk_id = $desk->id;
        $booking_history->book_time_start = $booking_time_start;
        $booking_history->book_time_end = $booking_time_end;
        $booking_history->save(); 

        $resource = Resources::factory()->create();
        $roomResource = new Resources_Rooms;
        $roomResource->room_id = $room->id;
        $roomResource->resource_id = $resource->resource_id;
        $roomResource->save();

        $date1 = new DateTime(Carbon::now()->subMonth(3));
        $result1 = $date1->format('Y-m-d H:i:s');
        $date2 = new DateTime(Carbon::now()->subMonth(2));
        $result2 = $date2->format('Y-m-d H:i:s');

        $dateRange = $result1 .' - '. $result2;

        $request = Request::create('', '', [
            'dateRange' => $dateRange,
        ]);

        $controller = new ResourceStatisticsController();
        $response = $controller->getFilterResources($request);

        $this->assertSame(json_encode([[], []]),$response->getContent());

    }

    public function test_Get_Filter_Resources_Desks_Rooms_Positive_Function() {
        $user = User::factory()->create();
        $user->is_admin = true;
        $user->save();

        $user = User::factory()->create();
        $room = Rooms::factory()->create();
        $desk = Desks::factory()->create();

        $desk->room_id = $room->id;
        $desk->save();

        $booking_history = new BookingHistory;
        $booking_time_start = Carbon::yesterday();
        $booking_time_end =  Carbon::now('GMT-7');
        $booking_history->user_id = $user->id;
        $booking_history->desk_id = $desk->id;
        $booking_history->book_time_start = $booking_time_start;
        $booking_history->book_time_end = $booking_time_end;
        $booking_history->save(); 

        $resource = Resources::factory()->create();
        $roomResource = new Resources_Rooms;
        $roomResource->room_id = $room->id;
        $roomResource->resource_id = $resource->resource_id;
        $roomResource->save();

        $resource2 = Resources::factory()->create();
        $deskResource = new Resources_Desks;
        $deskResource->desk_id = $desk->id;
        $deskResource->resource_id = $resource2->resource_id;
        $deskResource->save();

        $date1 = new DateTime(Carbon::now()->subMonth());
        $result1 = $date1->format('Y-m-d H:i:s');
        $date2 = new DateTime(Carbon::now()->addMonth());
        $result2 = $date2->format('Y-m-d H:i:s');

        $dateRange = $result1 .' - '. $result2;

        $request = Request::create('', '', [
            'dateRange' => $dateRange,
        ]);

        $controller = new ResourceStatisticsController();
        $response = $controller->getFilterResources($request);

        $this->assertSame(json_encode([[['name' => $resource2->resource_type,'y' => 100,]], [['name' => $resource->resource_type,'y' => 100]]]),$response->getContent());

    }

    public function test_Get_Filter_Resources_Desks_Rooms_Negative_Function() {
        $user = User::factory()->create();
        $user->is_admin = true;
        $user->save();

        $user = User::factory()->create();
        $room = Rooms::factory()->create();
        $desk = Desks::factory()->create();

        $desk->room_id = $room->id;
        $desk->save();

        $booking_history = new BookingHistory;
        $booking_time_start = Carbon::yesterday();
        $booking_time_end =  Carbon::now('GMT-7');
        $booking_history->user_id = $user->id;
        $booking_history->desk_id = $desk->id;
        $booking_history->book_time_start = $booking_time_start;
        $booking_history->book_time_end = $booking_time_end;
        $booking_history->save(); 

        $resource = Resources::factory()->create();
        $roomResource = new Resources_Rooms;
        $roomResource->room_id = $room->id;
        $roomResource->resource_id = $resource->resource_id;
        $roomResource->save();

        $resource2 = Resources::factory()->create();
        $deskResource = new Resources_Desks;
        $deskResource->desk_id = $desk->id;
        $deskResource->resource_id = $resource2->resource_id;
        $deskResource->save();

        $date1 = new DateTime(Carbon::now()->subMonth(3));
        $result1 = $date1->format('Y-m-d H:i:s');
        $date2 = new DateTime(Carbon::now()->subMonth(2));
        $result2 = $date2->format('Y-m-d H:i:s');

        $dateRange = $result1 .' - '. $result2;

        $request = Request::create('', '', [
            'dateRange' => $dateRange,
        ]);

        $controller = new ResourceStatisticsController();
        $response = $controller->getFilterResources($request);

        $this->assertSame(json_encode([[], []]),$response->getContent());

    }


    public function test_Get_Filter_Roles_Positive_Function() {
        $role = Roles::factory()->create();

        $user = User::factory()->create();
        $user->is_admin = true;
        $user->role_id = $role->role_id;
        $user->save();

        $room = Rooms::factory()->create();
        $roomId = $room->id;

        $desk = Desks::factory()->create();

        $desk->room_id = $room->id;
        $desk->save();

        $booking_history = new BookingHistory;
        $booking_time_start = Carbon::yesterday();
        $booking_time_end =  Carbon::now('GMT-7');
        $booking_history->user_id = $user->id;
        $booking_history->desk_id = $desk->id;
        $booking_history->book_time_start = $booking_time_start;
        $booking_history->book_time_end = $booking_time_end;
        $booking_history->save(); 

        $date1 = new DateTime(Carbon::now()->subMonth());
        $result1 = $date1->format('Y-m-d H:i:s');
        $date2 = new DateTime(Carbon::now()->addMonth());
        $result2 = $date2->format('Y-m-d H:i:s');

        $dateRange = $result1 .' - '. $result2;

        $request = Request::create('', '', [
            'dateRange' => $dateRange,
            'roomId' => $roomId
        ]);

        $controller = new RolesStatisticsController();
        $response = $controller->getFilterRoles($request);



        $this->assertSame(json_encode([[1], [$role->role]]),$response->getContent());

    }

    public function test_Get_Filter_Roles_Negative_Date_Function() {
        $role = Roles::factory()->create();

        $user = User::factory()->create();
        $user->is_admin = true;
        $user->role_id = $role->role_id;
        $user->save();

        $room = Rooms::factory()->create();
        $roomId = $room->id;

        $desk = Desks::factory()->create();

        $desk->room_id = $room->id;
        $desk->save();

        $booking_history = new BookingHistory;
        $booking_time_start = Carbon::yesterday();
        $booking_time_end =  Carbon::now('GMT-7');
        $booking_history->user_id = $user->id;
        $booking_history->desk_id = $desk->id;
        $booking_history->book_time_start = $booking_time_start;
        $booking_history->book_time_end = $booking_time_end;
        $booking_history->save(); 

        $date1 = new DateTime(Carbon::now()->subMonth(3));
        $result1 = $date1->format('Y-m-d H:i:s');
        $date2 = new DateTime(Carbon::now()->subMonth(2));
        $result2 = $date2->format('Y-m-d H:i:s');

        $dateRange = $result1 .' - '. $result2;

        $request = Request::create('', '', [
            'dateRange' => $dateRange,
            'roomId' => $roomId
        ]);

        $controller = new RolesStatisticsController();
        $response = $controller->getFilterRoles($request);



        $this->assertSame(json_encode([[], []]),$response->getContent());

    }

    public function test_Get_Filter_Roles_Negative_RoomId_Function() {
        $role = Roles::factory()->create();

        $user = User::factory()->create();
        $user->is_admin = true;
        $user->role_id = $role->role_id;
        $user->save();

        $room = Rooms::factory()->create();

        $room2 = Rooms::factory()->create();
        $roomId2 = $room2->id;

        $desk = Desks::factory()->create();

        $desk->room_id = $room->id;
        $desk->save();

        $booking_history = new BookingHistory;
        $booking_time_start = Carbon::yesterday();
        $booking_time_end =  Carbon::now('GMT-7');
        $booking_history->user_id = $user->id;
        $booking_history->desk_id = $desk->id;
        $booking_history->book_time_start = $booking_time_start;
        $booking_history->book_time_end = $booking_time_end;
        $booking_history->save(); 

        $date1 = new DateTime(Carbon::now()->subMonth());
        $result1 = $date1->format('Y-m-d H:i:s');
        $date2 = new DateTime(Carbon::now()->addMonth());
        $result2 = $date2->format('Y-m-d H:i:s');

        $dateRange = $result1 .' - '. $result2;

        $request = Request::create('', '', [
            'dateRange' => $dateRange,
            'roomId' => $roomId2
        ]);

        $controller = new RolesStatisticsController();
        $response = $controller->getFilterRoles($request);



        $this->assertSame(json_encode([[], []]),$response->getContent());

    }

    public function test_Get_Filter_Roles_Negative_Date_RoomId_Function() {
        $role = Roles::factory()->create();

        $user = User::factory()->create();
        $user->is_admin = true;
        $user->role_id = $role->role_id;
        $user->save();

        $room = Rooms::factory()->create();

        $room2 = Rooms::factory()->create();
        $roomId2 = $room2->id;

        $desk = Desks::factory()->create();
        $desk->room_id = $room->id;
        $desk->save();

        $booking_history = new BookingHistory;
        $booking_time_start = Carbon::yesterday();
        $booking_time_end =  Carbon::now('GMT-7');
        $booking_history->user_id = $user->id;
        $booking_history->desk_id = $desk->id;
        $booking_history->book_time_start = $booking_time_start;
        $booking_history->book_time_end = $booking_time_end;
        $booking_history->save(); 

        $date1 = new DateTime(Carbon::now()->subMonth(3));
        $result1 = $date1->format('Y-m-d H:i:s');
        $date2 = new DateTime(Carbon::now()->subMonth(2));
        $result2 = $date2->format('Y-m-d H:i:s');

        $dateRange = $result1 .' - '. $result2;

        $request = Request::create('', '', [
            'dateRange' => $dateRange,
            'roomId' => $roomId2
        ]);

        $controller = new RolesStatisticsController();
        $response = $controller->getFilterRoles($request);



        $this->assertSame(json_encode([[], []]),$response->getContent());

    }

    public function test_Get_Filter_Departments_Positive_Function() {
        $department = Department::factory()->create();

        $user = User::factory()->create();
        $user->is_admin = true;
        $user->department_id = $department->department_id;
        $user->save();

        $room = Rooms::factory()->create();
        $roomId = $room->id;

        $desk = Desks::factory()->create();
        $desk->room_id = $room->id;
        $desk->save();

        $booking_history = new BookingHistory;
        $booking_time_start = Carbon::yesterday();
        $booking_time_end =  Carbon::now('GMT-7');
        $booking_history->user_id = $user->id;
        $booking_history->desk_id = $desk->id;
        $booking_history->book_time_start = $booking_time_start;
        $booking_history->book_time_end = $booking_time_end;
        $booking_history->save(); 

        $date1 = new DateTime(Carbon::now()->subMonth());
        $result1 = $date1->format('Y-m-d H:i:s');
        $date2 = new DateTime(Carbon::now()->addMonth());
        $result2 = $date2->format('Y-m-d H:i:s');

        $dateRange = $result1 .' - '. $result2;

        $request = Request::create('', '', [
            'dateRange' => $dateRange,
            'roomId' => $roomId
        ]);

        $controller = new DepartmentStatisticsController();
        $response = $controller->getFilterDepartments($request);



        $this->assertSame(json_encode([[1], [$department->department]]),$response->getContent());

    }

    public function test_Get_Filter_Departments_Negative_Date_Function() {
        $department = Department::factory()->create();

        $user = User::factory()->create();
        $user->is_admin = true;
        $user->department_id = $department->department_id;
        $user->save();

        $room = Rooms::factory()->create();
        $roomId = $room->id;

        $desk = Desks::factory()->create();
        $desk->room_id = $room->id;
        $desk->save();

        $booking_history = new BookingHistory;
        $booking_time_start = Carbon::yesterday();
        $booking_time_end =  Carbon::now('GMT-7');
        $booking_history->user_id = $user->id;
        $booking_history->desk_id = $desk->id;
        $booking_history->book_time_start = $booking_time_start;
        $booking_history->book_time_end = $booking_time_end;
        $booking_history->save(); 

        $date1 = new DateTime(Carbon::now()->subMonth(3));
        $result1 = $date1->format('Y-m-d H:i:s');
        $date2 = new DateTime(Carbon::now()->subMonth(2));
        $result2 = $date2->format('Y-m-d H:i:s');

        $dateRange = $result1 .' - '. $result2;

        $request = Request::create('', '', [
            'dateRange' => $dateRange,
            'roomId' => $roomId
        ]);

        $controller = new DepartmentStatisticsController();
        $response = $controller->getFilterDepartments($request);



        $this->assertSame(json_encode([[], []]),$response->getContent());

    }

    public function test_Get_Filter_Departments_Negative_Date_RoomId_Function() {
        $department = Department::factory()->create();

        $user = User::factory()->create();
        $user->is_admin = true;
        $user->department_id = $department->department_id;
        $user->save();

        $room = Rooms::factory()->create();

        $room2 = Rooms::factory()->create();
        $roomId2 = $room2->id;

        $desk = Desks::factory()->create();
        $desk->room_id = $room->id;
        $desk->save();

        $booking_history = new BookingHistory;
        $booking_time_start = Carbon::yesterday();
        $booking_time_end =  Carbon::now('GMT-7');
        $booking_history->user_id = $user->id;
        $booking_history->desk_id = $desk->id;
        $booking_history->book_time_start = $booking_time_start;
        $booking_history->book_time_end = $booking_time_end;
        $booking_history->save(); 

        $date1 = new DateTime(Carbon::now()->subMonth(3));
        $result1 = $date1->format('Y-m-d H:i:s');
        $date2 = new DateTime(Carbon::now()->subMonth(2));
        $result2 = $date2->format('Y-m-d H:i:s');

        $dateRange = $result1 .' - '. $result2;

        $request = Request::create('', '', [
            'dateRange' => $dateRange,
            'roomId' => $roomId2
        ]);

        $controller = new DepartmentStatisticsController();
        $response = $controller->getFilterDepartments($request);



        $this->assertSame(json_encode([[], []]),$response->getContent());

    }

    public function test_Get_Filter_BookingTimes_Positive_Function() {
        $user = User::factory()->create();
        $user->is_admin = true;
        $user->save();

        $room = Rooms::factory()->create();
        $roomId = $room->id;

        $desk = Desks::factory()->create();

        $desk->room_id = $room->id;
        $desk->save();

        $booking_history = new BookingHistory;
        $booking_time_start = Carbon::yesterday();
        $booking_time_end =  Carbon::now('GMT-7');
        $booking_history->user_id = $user->id;
        $booking_history->desk_id = $desk->id;
        $booking_history->book_time_start = $booking_time_start;
        $booking_history->book_time_end = $booking_time_end;
        $booking_history->save(); 

        $date1 = new DateTime(Carbon::now()->subMonth());
        $result1 = $date1->format('Y-m-d H:i:s');
        $date2 = new DateTime(Carbon::now()->addMonth());
        $result2 = $date2->format('Y-m-d H:i:s');

        $dateRange = $result1 .' - '. $result2;

        $request = Request::create('', '', [
            'dateRange' => $dateRange,
            'roomId' => $roomId
        ]);

        $controller = new BookingTimeStatisticsController();
        $response = $controller->getFilterBookingTimes($request);

        $book_time_start_formatted = $booking_time_start->format('h:ia');


        $this->assertSame(json_encode([[1], [$book_time_start_formatted]]),$response->getContent());

    }

    public function test_Get_Filter_BookingTimes_Date_Negative_Function() {
        $user = User::factory()->create();
        $user->is_admin = true;
        $user->save();

        $room = Rooms::factory()->create();
        $roomId = $room->id;

        $desk = Desks::factory()->create();

        $desk->room_id = $room->id;
        $desk->save();

        $booking_history = new BookingHistory;
        $booking_time_start = Carbon::yesterday();
        $booking_time_end =  Carbon::now('GMT-7');
        $booking_history->user_id = $user->id;
        $booking_history->desk_id = $desk->id;
        $booking_history->book_time_start = $booking_time_start;
        $booking_history->book_time_end = $booking_time_end;
        $booking_history->save(); 

        $date1 = new DateTime(Carbon::now()->subMonth(3));
        $result1 = $date1->format('Y-m-d H:i:s');
        $date2 = new DateTime(Carbon::now()->subMonth(2));
        $result2 = $date2->format('Y-m-d H:i:s');

        $dateRange = $result1 .' - '. $result2;

        $request = Request::create('', '', [
            'dateRange' => $dateRange,
            'roomId' => $roomId
        ]);

        $controller = new BookingTimeStatisticsController();
        $response = $controller->getFilterBookingTimes($request);



        $this->assertSame(json_encode([[], []]),$response->getContent());

    }

    public function test_Get_Filter_BookingTime_RoomId_Negative_Function() {
        $user = User::factory()->create();
        $user->is_admin = true;
        $user->save();

        $room = Rooms::factory()->create();

        $room2 = Rooms::factory()->create();
        $roomId2 = $room2->id;

        $desk = Desks::factory()->create();

        $desk->room_id = $room->id;
        $desk->save();

        $booking_history = new BookingHistory;
        $booking_time_start = Carbon::yesterday();
        $booking_time_end =  Carbon::now('GMT-7');
        $booking_history->user_id = $user->id;
        $booking_history->desk_id = $desk->id;
        $booking_history->book_time_start = $booking_time_start;
        $booking_history->book_time_end = $booking_time_end;
        $booking_history->save(); 

        $date1 = new DateTime(Carbon::now()->subMonth());
        $result1 = $date1->format('Y-m-d H:i:s');
        $date2 = new DateTime(Carbon::now()->addMonth());
        $result2 = $date2->format('Y-m-d H:i:s');

        $dateRange = $result1 .' - '. $result2;

        $request = Request::create('', '', [
            'dateRange' => $dateRange,
            'roomId' => $roomId2
        ]);

        $controller = new BookingTimeStatisticsController();
        $response = $controller->getFilterBookingTimes($request);



        $this->assertSame(json_encode([[], []]),$response->getContent());

    }

    public function test_Get_Filter_BookingTime_Date_RoomId_Negative_Function() {
        $user = User::factory()->create();
        $user->is_admin = true;
        $user->save();

        $room = Rooms::factory()->create();

        $room2 = Rooms::factory()->create();
        $roomId2 = $room2->id;

        $desk = Desks::factory()->create();

        $desk->room_id = $room->id;
        $desk->save();

        $booking_history = new BookingHistory;
        $booking_time_start = Carbon::yesterday();
        $booking_time_end =  Carbon::now('GMT-7');
        $booking_history->user_id = $user->id;
        $booking_history->desk_id = $desk->id;
        $booking_history->book_time_start = $booking_time_start;
        $booking_history->book_time_end = $booking_time_end;
        $booking_history->save(); 

        $date1 = new DateTime(Carbon::now()->subMonth(3));
        $result1 = $date1->format('Y-m-d H:i:s');
        $date2 = new DateTime(Carbon::now()->subMonth(2));
        $result2 = $date2->format('Y-m-d H:i:s');

        $dateRange = $result1 .' - '. $result2;

        $request = Request::create('', '', [
            'dateRange' => $dateRange,
            'roomId' => $roomId2
        ]);

        $controller = new BookingTimeStatisticsController();
        $response = $controller->getFilterBookingTimes($request);



        $this->assertSame(json_encode([[], []]),$response->getContent());

    }

    
    public function test_Booking_Effected_By_Desk_Deletion()
    {
        $bookingToBeDeleted = Bookings::factory()->create();
        $desk = Desks::find($bookingToBeDeleted->desk_id);

        $this->assertDatabaseHas('bookings', [
            'id' => $bookingToBeDeleted->id,
        ]);

        $controller = new DeskController();
        $controller->destroy($desk->id);

        $this->assertDatabaseMissing('bookings', [
            'id' => $bookingToBeDeleted->id,
        ]);
    }

    public function test_Booking_Effected_By_Desk_Closure()
    {
        $bookingToBeDeleted = Bookings::factory()->create();
        $desk = Desks::find($bookingToBeDeleted->desk_id);

        $request = Request::create(route('deskUpdate'), 'POST', [
            'id' => $desk->id,
            'room_id' => $desk->room_id,
            'pos_x' => 1,
            'pos_y' => 2,
            'is_closed' => true,
        ]);

        $this->assertDatabaseHas('bookings', [
            'id' => $bookingToBeDeleted->id,
        ]);

        $controller = new DeskController();
        $controller->update($request);

        $this->assertDatabaseMissing('bookings', [
            'id' => $bookingToBeDeleted->id,
        ]);
    }

    public function test_Booking_Effected_By_Room_Deletion()
    {
        $bookingToBeDeleted = Bookings::factory()->create();
        $room = Desks::find($bookingToBeDeleted->desk_id)->room;

        $this->assertDatabaseHas('bookings', [
            'id' => $bookingToBeDeleted->id,
        ]);

        $controller = new RoomController();
        $controller->destroy($room->id);

        $this->assertDatabaseMissing('bookings', [
            'id' => $bookingToBeDeleted->id,
        ]);
    }

    public function test_Booking_Effected_By_Room_Closure()
    {
        $bookingToBeDeleted = Bookings::factory()->create();
        $room = Desks::find($bookingToBeDeleted->desk_id)->room;

        $request = Request::create(route('roomUpdate', $room->id), 'POST', [
            'floor_id' => $room->floor_id,
            'name' => $room->name,
            'occupancy' => $room->occupancy,
            'is_closed' => true,
        ]);

        $this->assertDatabaseHas('bookings', [
            'id' => $bookingToBeDeleted->id,
        ]);

        $controller = new RoomController();
        $controller->update($request, $room->id);

        $this->assertDatabaseMissing('bookings', [
            'id' => $bookingToBeDeleted->id,
        ]);
    }

    public function test_Booking_Effected_By_Floor_Deletion()
    {
        $bookingToBeDeleted = Bookings::factory()->create();
        $floor = Desks::find($bookingToBeDeleted->desk_id)->room->floor;

        $this->assertDatabaseHas('bookings', [
            'id' => $bookingToBeDeleted->id,
        ]);

        $controller = new FloorController();
        $controller->destroy($floor->id);

        $this->assertDatabaseMissing('bookings', [
            'id' => $bookingToBeDeleted->id,
        ]);
    }

    public function test_Booking_Effected_By_Floor_Closure()
    {
        $bookingToBeDeleted = Bookings::factory()->create();
        $floor = Desks::find($bookingToBeDeleted->desk_id)->room->floor;

        $request = Request::create(route('floorUpdate', $floor->id), 'POST', [
            'building_id' => $floor->building_id,
            'floor_num' => $floor->floor_num,
            'is_closed' => true,
        ]);

        $this->assertDatabaseHas('bookings', [
            'id' => $bookingToBeDeleted->id,
        ]);

        $controller = new FloorController();
        $controller->update($request, $floor->id);

        $this->assertDatabaseMissing('bookings', [
            'id' => $bookingToBeDeleted->id,
        ]);
    }

    public function test_Booking_Effected_By_Building_Deletion()
    {
        $bookingToBeDeleted = Bookings::factory()->create();
        $building = Desks::find($bookingToBeDeleted->desk_id)->room->floor->building;

        $this->assertDatabaseHas('bookings', [
            'id' => $bookingToBeDeleted->id,
        ]);

        $controller = new BuildingController();
        $controller->destroy($building->id);

        $this->assertDatabaseMissing('bookings', [
            'id' => $bookingToBeDeleted->id,
        ]);
    }

    public function test_Booking_Effected_By_Building_Closure()
    {
        $bookingToBeDeleted = Bookings::factory()->create();
        $building = Desks::find($bookingToBeDeleted->desk_id)->room->floor->building;

        $request = Request::create(route('buildingUpdate', $building->id), 'POST', [
            'name' => $building->name,
            'campus_id' => $building->campus_id,
            'is_closed' => true,
        ]);

        $this->assertDatabaseHas('bookings', [
            'id' => $bookingToBeDeleted->id,
        ]);

        $controller = new BuildingController();
        $controller->update($request, $building->id);

        $this->assertDatabaseMissing('bookings', [
            'id' => $bookingToBeDeleted->id,
        ]);
    }

    public function test_Booking_Effected_By_Campus_Deletion()
    {
        $bookingToBeDeleted = Bookings::factory()->create();
        $campus = Desks::find($bookingToBeDeleted->desk_id)->room->floor->building->campus;

        $this->assertDatabaseHas('bookings', [
            'id' => $bookingToBeDeleted->id,
        ]);

        $controller = new CampusController();
        $controller->destroy($campus->id);

        $this->assertDatabaseMissing('bookings', [
            'id' => $bookingToBeDeleted->id,
        ]);
    }

    public function test_Booking_Effected_By_Campus_Closure()
    {
        $bookingToBeDeleted = Bookings::factory()->create();
        $campus = Desks::find($bookingToBeDeleted->desk_id)->room->floor->building->campus;

        $request = Request::create(route('campusUpdate', $campus->id), 'POST', [
            'name' => $campus->name,
            'is_closed' => true,
        ]);

        $this->assertDatabaseHas('bookings', [
            'id' => $bookingToBeDeleted->id,
        ]);

        $controller = new CampusController();
        $controller->update($request, $campus->id);

        $this->assertDatabaseMissing('bookings', [
            'id' => $bookingToBeDeleted->id,
        ]);
    }

    /////////////////////////// User-Side ////////////////////////////////
    public function test_Booking_Delete_Function_User()
    {
        $bookingToBeDeleted = Bookings::factory()->create();

        $controller = new UserBookingsController();
        $controller->cancel($bookingToBeDeleted->id);

        $this->assertDatabaseMissing('bookings', [
            'id' => $bookingToBeDeleted->id,
        ]);
    }
}