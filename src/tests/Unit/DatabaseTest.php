<?php

namespace Tests\Unit;

use App\Http\Controllers\DeskController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\CampusController;
use App\Http\Controllers\FloorController;
use App\Http\Controllers\RoleController;
use App\Models\Bookings;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use App\Models\Buildings;
use App\Models\Campuses;
use App\Models\Desks;
use App\Models\Floors;
use App\Models\Roles;
use App\Models\Rooms;
use App\Models\User;
use App\Models\Users;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use phpDocumentor\Reflection\Types\Boolean;

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
        $freq = $this->faker->randomNumber(1);

        $request = Request::create(route('role.store'), 'POST', [
            'role' => $role,
            'num_monthly_bookings' => $nmb,
            'frequency' => $freq,
        ]);

        $controller = new RoleController();
        $controller->store($request);

        $this->assertDatabaseHas('roles', [
            'role' => $role,
            'num_monthly_bookings' => $nmb,
            'frequency' => $freq,
        ]);
    }

    public function test_Role_Update_Function()
    {
        $roleToBeUpdated = Roles::factory()->create();

        $role = $this->faker->firstName();
        $nmb = $this->faker->randomNumber(2);
        $freq = $this->faker->randomNumber(1);

        $request = Request::create(route('roleUpdate', $roleToBeUpdated->id), 'POST', [
            'role' => $role,
            'num_monthly_bookings' => $nmb,
            'frequency' => $freq,
        ]);

        $controller = new RoleController();
        $controller->update($request, $roleToBeUpdated->id);

        $this->assertDatabaseHas('roles', [
            'role' => $role,
            'num_monthly_bookings' => $nmb,
            'frequency' => $freq,
        ]);
    }

    public function test_Role_Delete_Function()
    {
        $roleToBeDeleted = Roles::factory()->create();

        $controller = new RoleController();
        $controller->destroy($roleToBeDeleted->id);

        $this->assertDatabaseMissing('roles', [
            'id' => $roleToBeDeleted->id,
        ]);
    }

    public function test_Desk_Store_Function()
    {
        $room = Rooms::factory()->create();
        $x = $this->faker->randomNumber(2);
        $y = $this->faker->randomNumber(2);
        $outlet = $this->faker->boolean(50);
        $isClosed = $this->faker->boolean(50);


        $request = Request::create(route('deskStore'), 'POST', [
            'room_id' => $room->id,
            'pos_x' => $x,
            'pos_y' => $y,
            'has_outlet' => $outlet,
            'is_closed' => $isClosed,
        ]);

        $controller = new DeskController();
        $controller->store($request);

        $this->assertDatabaseHas('desks', [
            'room_id' => $room->id,
            'pos_x' => $x,
            'pos_y' => $y,
            'has_outlet' => $outlet,
            'is_closed' => $isClosed,
        ]);
    }


    public function test_Desk_Update_Function()
    {
        $deskToBeUpdated = Desks::factory()->create();

        $x = $this->faker->randomNumber(2);
        $y = $this->faker->randomNumber(2);
        $outlet = $this->faker->boolean(50);
        $isClosed = $this->faker->boolean(50);

        $request = Request::create(route('deskUpdate', $deskToBeUpdated->id), 'POST', [
            'room_id' => $deskToBeUpdated->room_id,
            'pos_x' => $x,
            'pos_y' => $y,
            'has_outlet' => $outlet,
            'is_closed' => $isClosed,
        ]);

        $controller = new DeskController();
        $controller->update($request, $deskToBeUpdated->id);

        $this->assertDatabaseHas('desks', [
            'room_id' => $deskToBeUpdated->room_id,
            'pos_x' => $x,
            'pos_y' => $y,
            'has_outlet' => $outlet,
            'is_closed' => $isClosed,
        ]);
    }

    public function test_Desk_Delete_Function()
    {
        $deskToBeDeleted = Desks::factory()->create();

        $controller = new DeskController();
        $controller->destroy($deskToBeDeleted->id);

        $this->assertDatabaseMissing('desks', [
            'id' => $deskToBeDeleted->id,
        ]);
    }

    public function test_Booking_Store_Function()
    {
        $user = User::factory()->create();
        $desk = Desks::factory()->create();
        $book_start = Carbon::now();
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

        $book_start = Carbon::now();
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
        $available = true;

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
        $available = true;

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

        $this->assertDatabaseMissing('campuses', [
            'id' => $campusToBeDeleted->id,
        ]);
    }


    public function test_Room_Store_Function()
    {
        $floor_id = Floors::factory()->create();

        $name = $this->faker->firstName();
        $has_printer = $this->faker->boolean(50);
        $has_projector = $this->faker->boolean(50);
        $is_closed = $this->faker->boolean(50);
        $room_image = 01;

        $request = Request::create(route('roomStore'), 'POST', [
            'floor_id' => $floor_id->id,
            'name' => $name,
            'has_printer' => $has_printer,
            'has_projector' => $has_projector,
            'is_closed' => $is_closed,
            'room_image' => $room_image
        ]);

        $controller = new RoomController();
        $controller->store($request);

        $this->assertDatabaseHas('rooms', [
            'floor_id' => $floor_id->id,
            'name' => $name,
            'has_printer' => $has_printer,
            'has_projector' => $has_projector,
            'is_closed' => $is_closed,
            'room_image' => $room_image
        ]);
    }
    public function test_Room_Update_Function()
    {
        $roomToBeUpdated = Rooms::factory()->create();

        $name = $this->faker->firstName();
        $has_printer = $this->faker->boolean(50);
        $has_projector = $this->faker->boolean(50);
        $is_closed = $this->faker->boolean(50);
        $room_image = 01;

        $request = Request::create(route('roomUpdate', $roomToBeUpdated->id), 'POST', [
            'floor_id' => $roomToBeUpdated->id,
            'name' => $name,
            'has_printer' => $has_printer,
            'has_projector' => $has_projector,
            'is_closed' => $is_closed,
            'room_image' => $room_image
        ]);

        $controller = new RoomController();
        $controller->update($request, $roomToBeUpdated->id);

        $this->assertDatabaseHas('rooms', [
            'floor_id' => $roomToBeUpdated->id,
            'name' => $name,
            'has_printer' => $has_printer,
            'has_projector' => $has_projector,
            'is_closed' => $is_closed,
            'room_image' => $room_image
        ]);
    }
    public function test_Room_Delete_Function()
    {
        $roomToBeDeleted = Rooms::factory()->create();

        $controller = new RoomController();
        $controller->destroy($roomToBeDeleted->id);

        $this->assertDatabaseMissing('rooms', [
            'id' => $roomToBeDeleted->id,
        ]);
    }

    public function test_User_Store_Function()
    {
        $name = $this->faker->userName();
        $email = $this->faker->email();
        $password = $this->faker->password(9);
        $is_admin = true;

        $request = Request::create(route('user.store'), 'POST', [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'is_admin' => $is_admin
        ]);

        $controller = new UserController();
        $controller->store($request);

        $this->assertDatabaseHas('users', [
            'name' => $name,
            'email' => $email,
            'is_admin' => $is_admin
        ]);
    }

    public function test_User_Update_Function()
    {
        $userToBeUpdated = User::factory()->create();

        $name = $this->faker->userName();
        $email = $this->faker->email();
        $is_admin = true;

        $request = Request::create(route('userUpdate', $userToBeUpdated->id), 'POST', [
            'name' => $name,
            'email' => $email,
            'is_admin' => $is_admin
        ]);

        $controller = new UserController();
        $controller->update($request, $userToBeUpdated->id);

        $this->assertDatabaseHas('users', [
            'name' => $name,
            'email' => $email,
            'is_admin' => $is_admin
        ]);
    }

    public function test_User_Delete_Function()
    {
        $userToBeDeleted = User::factory()->create();

        $controller = new UserController();
        $controller->destroy($userToBeDeleted->id);

        $this->assertDatabaseMissing('users', [
            'id' => $userToBeDeleted->id,
        ]);
    }


    public function test_Building_Store_Function()
    {
        $campus = Campuses::factory()->create();
        $name = $this->faker->firstName();
        $available = true;

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
        $available = true;

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

        $this->assertDatabaseMissing('campuses', [
            'id' => $buildingToBeDeleted->id,
        ]);
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

        $this->assertDatabaseMissing('floors', [
            'id' => $floorToBeDeleted->id,
        ]);
    }
}