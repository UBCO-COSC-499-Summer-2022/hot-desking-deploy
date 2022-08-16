<?php

namespace Tests\Unit;

use App\Http\Controllers\User\CalendarViewController;
use App\Models\Bookings;
use App\Models\Desks;
use App\Models\OccupationPolicyLimit;
use App\Models\RoleRoom;
use App\Models\Roles;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;

class BookingTest extends TestCase
{
    // We will want to do a routes test on the index for the CalendarViewController
    // Just check that you can load the page
    use RefreshDatabase, WithFaker;

    /**
     * Testing that a booking can be stored successfully
     *
     * @return void
     */
    public function test_booking_store_success() {
        // Create a fake user
        $user = User::factory()->create();
        $role = Roles::find($user->role_id);
        $role->num_monthly_bookings = 5; 
        $role->max_booking_window = 21;
        $role->max_booking_duration = 4;
        // Store the changes made into the database
        $role->save();

        // Create a fake desk
        $desk = Desks::factory()->create();
        
        // Create the booking request event variables
        $startTime = Carbon::parse('Today 12:30:00');
        $endTime = Carbon::parse('Today 13:30:00');

        // Fake the booking create request
        $request = Request::create('', '', [
            "user_id" => $user->id,
            "room_id" => $desk->room_id, 
            "desk_id" => $desk->id,
            "book_time_start" => $startTime, 
            "book_time_end" => $endTime,
            "type" => "create",
        ]);

        // Create a controller
        $controller = new CalendarViewController();

        // Pass in our request
        $controller->calendarEvents($request);

        // Assert that the booking exists
        $this->assertDatabaseHas("bookings", [
            "user_id" => $user->id,
            "desk_id" => $desk->id,
            "book_time_start" => $startTime, 
            "book_time_end" => $endTime,
        ]);
    }

    /**
     * Testing that a booking can fail on a booking duration check
     *
     * @return void
     */
    public function test_booking_store_fail_on_booking_duration() {
        if (OccupationPolicyLimit::where('id', 1)->exists()) {
            $policy = OccupationPolicyLimit::find(1);
            $policy->percentage = 100;
            $policy->save();
        } else {
            $policy = new OccupationPolicyLimit;
            $policy->percentage = 100;
            $policy->id = 1;
            $policy->save();
        }

        // Create a fake user
        $user = User::factory()->create();
        $role = Roles::find($user->role_id);
        $role->num_monthly_bookings = 5; 
        $role->max_booking_window = 21;
        $role->max_booking_duration = 4;
        // Store the changes made into the database
        $role->save();

        // Create a fake desk
        $desk = Desks::factory()->create();
        
        // Create the booking request event variables
        $startTime = Carbon::parse('Today 12:30:00');
        $endTime = Carbon::parse('Today 21:30:00');

        // Fake the booking create request
        $request = Request::create('', '', [
            "user_id" => $user->id,
            "room_id" => $desk->room_id, 
            "desk_id" => $desk->id,
            "book_time_start" => $startTime, 
            "book_time_end" => $endTime,
            "type" => "create",
        ]);

        // Create a controller
        $controller = new CalendarViewController();

        // Pass in our request
        $controller->calendarEvents($request);

        // Assert that the booking does not exist
        $this->assertDatabaseMissing("bookings", [
            "user_id" => $user->id,
            "desk_id" => $desk->id,
            "book_time_start" => $startTime, 
            "book_time_end" => $endTime,
        ]);
    }

    /**
     * Testing that a booking can pass a restricted room role check
     *
     * @return void
     */
    public function test_booking_store_success_on_role_restricted_room() {
        if (OccupationPolicyLimit::where('id', 1)->exists()) {
            $policy = OccupationPolicyLimit::find(1);
            $policy->percentage = 100;
            $policy->save();
        } else {
            $policy = new OccupationPolicyLimit;
            $policy->percentage = 100;
            $policy->id = 1;
            $policy->save();
        }

        // Create a fake user
        $user = User::factory()->create();
        $role = Roles::find($user->role_id);
        $role->num_monthly_bookings = 5; 
        $role->max_booking_window = 21;
        $role->max_booking_duration = 4;
        // Store the changes made into the database
        $role->save();

        // Create a fake desk
        $desk = Desks::factory()->create();

        // Get the faked room
        $room = $desk->room; 
        
        // Restrict this room to our users role
        $roleRoom = new RoleRoom;
        $roleRoom->role_id = $role->role_id; 
        $roleRoom->room_id = $room->id; 
        $roleRoom->save();
        
        // Create the booking request event variables
        $startTime = Carbon::parse('Today 12:30:00');
        $endTime = Carbon::parse('Today 13:30:00');

        // Fake the booking create request
        $request = Request::create('', '', [
            "user_id" => $user->id,
            "room_id" => $desk->room_id, 
            "desk_id" => $desk->id,
            "book_time_start" => $startTime, 
            "book_time_end" => $endTime,
            "type" => "create",
        ]);

        // Create a controller
        $controller = new CalendarViewController();

        // Pass in our request
        $controller->calendarEvents($request);

        // Assert that the booking exists
        $this->assertDatabaseHas("bookings", [
            "user_id" => $user->id,
            "desk_id" => $desk->id,
            "book_time_start" => $startTime, 
            "book_time_end" => $endTime,
        ]);
    }

    /**
     * Testing that a booking can fail a restricted room role check
     *
     * @return void
     */
    public function test_booking_store_failure_on_role_restricted_room() {
        if (OccupationPolicyLimit::where('id', 1)->exists()) {
            $policy = OccupationPolicyLimit::find(1);
            $policy->percentage = 100;
            $policy->save();
        } else {
            $policy = new OccupationPolicyLimit;
            $policy->percentage = 100;
            $policy->id = 1;
            $policy->save();
        }

        // Create a fake user
        $user = User::factory()->create();
        $role = Roles::find($user->role_id);
        $role->num_monthly_bookings = 5; 
        $role->max_booking_window = 21;
        $role->max_booking_duration = 4;
        // Store the changes made into the database
        $role->save();

        // Create a fake desk
        $desk = Desks::factory()->create();

        // Get the faked room
        $room = $desk->room; 

        // Create a fake role
        $fakeRole = Roles::factory()->create();
        // Restrict this room to our users role
        $roleRoom = new RoleRoom;
        $roleRoom->role_id = $fakeRole->role_id; 
        $roleRoom->room_id = $room->id; 
        $roleRoom->save();
        
        // Create the booking request event variables
        $startTime = Carbon::parse('Today 12:30:00');
        $endTime = Carbon::parse('Today 13:30:00');

        // Fake the booking create request
        $request = Request::create('', '', [
            "user_id" => $user->id,
            "room_id" => $desk->room_id, 
            "desk_id" => $desk->id,
            "book_time_start" => $startTime, 
            "book_time_end" => $endTime,
            "type" => "create",
        ]);

        // Create a controller
        $controller = new CalendarViewController();

        // Pass in our request
        $controller->calendarEvents($request);

        // Assert that the booking does not exist
        $this->assertDatabaseMissing("bookings", [
            "user_id" => $user->id,
            "desk_id" => $desk->id,
            "book_time_start" => $startTime, 
            "book_time_end" => $endTime,
        ]);
    }

    /**
     * Testing that a booking can succeed on a room that is at partial capacity
     *
     * @return void
     */
    public function test_booking_store_success_on_partially_occupied_room() {
        if (OccupationPolicyLimit::where('id', 1)->exists()) {
            $policy = OccupationPolicyLimit::find(1);
            $policy->percentage = 100;
            $policy->save();
        } else {
            $policy = new OccupationPolicyLimit;
            $policy->percentage = 100;
            $policy->id = 1;
            $policy->save();
        }

        // Create a fake user
        $firstUser = User::factory()->create();
        $role = Roles::find($firstUser->role_id);
        $role->num_monthly_bookings = 5; 
        $role->max_booking_window = 21;
        $role->max_booking_duration = 4;
        // Store the changes made into the database
        $role->save();

        $secondUser = User::factory()->create();

        // Create the first fake desk
        $firstDesk = Desks::factory()->create();
        // Create a second one that belongs to the same room
        $secondDesk = Desks::factory()->create();
        $secondDesk->room_id = $firstDesk->room_id;
        // Store the changes made into the database
        $secondDesk->save();

        // Get the faked room
        $room = $firstDesk->room; 
        // Set the maximum occupancy of the room to only allow 2 bookings
        $room->occupancy = 2;
        // Store the changes made into the database
        $room->save();

        // Create the booking request event variables
        $startTime = Carbon::parse('Today 12:30:00');
        $endTime = Carbon::parse('Today 13:30:00');

        // Insert a booking directly into our faked room
        // Create a fake booking
        $firstBooking = new Bookings;
        // Update the booking details
        $firstBooking->user_id = $firstUser->id; 
        $firstBooking->desk_id = $firstDesk->id; 
        $firstBooking->book_time_start = $startTime; 
        $firstBooking->book_time_end = $endTime;
        // Store the changes made into the database
        $firstBooking->save();
        
        // Fake the booking create request
        $request = Request::create('', '', [
            "user_id" => $secondUser->id,
            "room_id" => $secondDesk->room_id, 
            "desk_id" => $secondDesk->id,
            "book_time_start" => $startTime, 
            "book_time_end" => $endTime,
            "type" => "create",
        ]);

        // Create a controller
        $controller = new CalendarViewController();

        // Pass in our request
        $controller->calendarEvents($request);

        // Assert that the booking does exist
        $this->assertDatabaseHas("bookings", [
            "user_id" => $secondUser->id,
            "desk_id" => $secondDesk->id,
            "book_time_start" => $startTime, 
            "book_time_end" => $endTime,
        ]);
    }

    /**
     * Testing that a booking can fail in a room that is at max capacity
     *
     * @return void
     */
    public function test_booking_store_failure_on_fully_occupied_room() {
        if (OccupationPolicyLimit::where('id', 1)->exists()) {
            $policy = OccupationPolicyLimit::find(1);
            $policy->percentage = 100;
            $policy->save();
        } else {
            $policy = new OccupationPolicyLimit;
            $policy->percentage = 100;
            $policy->id = 1;
            $policy->save();
        }

        // Create a fake user
        $user = User::factory()->create();
        $role = Roles::find($user->role_id);
        $role->num_monthly_bookings = 5; 
        $role->max_booking_window = 21;
        $role->max_booking_duration = 4;
        // Store the changes made into the database
        $role->save();

        // Create the first fake desk
        $firstDesk = Desks::factory()->create();
        // Create a second one that belongs to the same room
        $secondDesk = Desks::factory()->create();
        $secondDesk->room_id = $firstDesk->room_id;
        // Store the changes made into the database
        $secondDesk->save();

        // Get the faked room
        $room = $firstDesk->room; 
        // Set the maximum occupancy of the room to only allow 1 booking
        $room->occupancy = 1;
        // Store the changes made into the database
        $room->save();

        // Create the booking request event variables
        $startTime = Carbon::parse('Today 12:30:00');
        $endTime = Carbon::parse('Today 13:30:00');

        // Insert a booking directly into our faked room
        // Create a fake booking
        $firstBooking = Bookings::factory()->create();
        // Update the booking details 
        $firstBooking->desk_id = $firstDesk->id; 
        $firstBooking->book_time_start = $startTime; 
        $firstBooking->book_time_end = $endTime;
        // Store the changes made into the database
        $firstBooking->save();

        // Fake the booking create request
        $request = Request::create('', '', [
            "user_id" => $user->id,
            "room_id" => $secondDesk->room_id, 
            "desk_id" => $secondDesk->id,
            "book_time_start" => $startTime, 
            "book_time_end" => $endTime,
            "type" => "create",
        ]);

        // Create a controller
        $controller = new CalendarViewController();

        // Pass in our request
        $controller->calendarEvents($request);

        // Assert that the booking does not exist
        $this->assertDatabaseMissing("bookings", [
            "desk_id" => $secondDesk->id,
            "book_time_start" => $startTime, 
            "book_time_end" => $endTime,
        ]);
    }

    /**
     * Testing that a booking can fail when it collides with another booking
     *
     * @return void
     */
    public function test_booking_store_failure_on_booking_collision() {
        // Add in the OccupationPolicyLimit check to all tests
        if (OccupationPolicyLimit::where('id', 1)->exists()) {
            $policy = OccupationPolicyLimit::find(1);
            $policy->percentage = 100;
            $policy->save();
        } else {
            $policy = new OccupationPolicyLimit;
            $policy->percentage = 100;
            $policy->id = 1;
            $policy->save();
        }

        // Create the first fake user
        $firstUser = User::factory()->create();
        $role = Roles::find($firstUser->role_id);
        $role->num_monthly_bookings = 5; 
        $role->max_booking_window = 21;
        $role->max_booking_duration = 4;
        // Store the changes made into the database
        $role->save();

        // Create the second fake user
        $secondUser = User::factory()->create();
        $role = Roles::find($secondUser->role_id);

        // Create a fake desk
        $desk = Desks::factory()->create();

        // Create the booking request event variables
        $startTime = Carbon::parse('Today 12:30:00');
        $endTime = Carbon::parse('Today 13:30:00');

        // Insert a booking directly into our faked room
        // Create a fake booking
        $firstBooking = Bookings::factory()->create();
        // Update the booking details 
        $firstBooking->user_id = $firstUser->id; 
        $firstBooking->desk_id = $desk->id; 
        $firstBooking->book_time_start = $startTime; 
        $firstBooking->book_time_end = $endTime;
        // Store the changes made into the database
        $firstBooking->save();

        $secondStartTime = $startTime;
        $secondEndTime = $endTime;

        // Fake the booking create request
        $request = Request::create('', '', [
            "user_id" => $secondUser->id,
            "room_id" => $desk->room_id, 
            "desk_id" => $desk->id,
            "book_time_start" => $secondStartTime, 
            "book_time_end" => $secondEndTime,
            "type" => "create",
        ]);

        // Create a controller
        $controller = new CalendarViewController();

        // Pass in our request
        $controller->calendarEvents($request);

        // Assert that the booking does not exist
        $this->assertDatabaseMissing("bookings", [
            "user_id" => $secondUser->id,
            "desk_id" => $desk->id,
            "book_time_start" => $startTime, 
            "book_time_end" => $endTime,
        ]);
    }

    /**
     * Testing that a booking can fail a user has another booking at the same time
     *
     * @return void
     */
    public function test_booking_store_failure_on_user_booking_multiple_desks_at_same_time() {
        // Add in the OccupationPolicyLimit check to all tests
        if (OccupationPolicyLimit::where('id', 1)->exists()) {
            $policy = OccupationPolicyLimit::find(1);
            $policy->percentage = 100;
            $policy->save();
        } else {
            $policy = new OccupationPolicyLimit;
            $policy->percentage = 100;
            $policy->id = 1;
            $policy->save();
        }

        // Create the first fake user
        $user = User::factory()->create();
        $role = Roles::find($user->role_id);
        $role->num_monthly_bookings = 5; 
        $role->max_booking_window = 21;
        $role->max_booking_duration = 4;
        // Store the changes made into the database
        $role->save();

        // Create the first fake desk
        $firstDesk = Desks::factory()->create();
        // Create the second fake desk
        $secondDesk = Desks::factory()->create();

        // Create the booking request event variables
        $startTime = Carbon::parse('Today 12:30:00');
        $endTime = Carbon::parse('Today 13:30:00');

        // Insert a booking directly into our faked room
        // Create a fake booking
        $firstBooking = Bookings::factory()->create();
        // Update the booking details 
        $firstBooking->user_id = $user->id; 
        $firstBooking->desk_id = $firstDesk->id; 
        $firstBooking->book_time_start = $startTime; 
        $firstBooking->book_time_end = $endTime;
        // Store the changes made into the database
        $firstBooking->save();

        // Fake the booking create request
        $request = Request::create('', '', [
            "user_id" => $user->id,
            "room_id" => $firstDesk->room_id, 
            "desk_id" => $secondDesk->id,
            "book_time_start" => $startTime, 
            "book_time_end" => $endTime,
            "type" => "create",
        ]);

        // Create a controller
        $controller = new CalendarViewController();

        // Pass in our request
        $controller->calendarEvents($request);

        // Assert that the booking does not exist
        $this->assertDatabaseMissing("bookings", [
            "user_id" => $user->id,
            "desk_id" => $secondDesk->id,
            "book_time_start" => $startTime, 
            "book_time_end" => $endTime,
        ]);
    }

    /**
     * Testing that a booking can be updated successfully
     *
     * @return void
     */
    public function test_update_booking_success() {
        // Create the booking request event variables
        $startTime = Carbon::parse('Today 12:30:00');
        $endTime = Carbon::parse('Today 13:30:00');

        // Create a desk
        $desk = Desks::factory()->create();

        // Create a fake booking
        $booking = Bookings::factory()->create();
        $booking->book_time_start = $startTime;
        $booking->book_time_end = $endTime;
        $booking->desk_id = $desk->id; 
        // Save your changes to the database
        $booking->save();

        // Update the booking start
        $newStartTime = $startTime->modify(' -15 minutes ');

        // Fake the booking update request
        $request = Request::create('', '', [
            "updated_desk_id" => $desk->id,
            "book_time_start" => $newStartTime, 
            "book_time_end" => $endTime,
        ]);

        // Create a controller
        $controller = new CalendarViewController();

        // Pass in our request
        $controller->update($request, $booking->id);

        // Assert that the booking was updated
        $this->assertDatabaseHas("bookings", [
            "id" => $booking->id,
            "desk_id" => $desk->id,
            "book_time_start" => $newStartTime, 
            "book_time_end" => $endTime,
        ]);
    }

    /**
     * Testing that a booking modification can fail when the booking cannot be found
     *
     * @return void
     */
    public function test_update_booking_failure_on_missing_booking() {
        // Create the booking request event variables
        $startTime = Carbon::parse('Today 12:30:00');
        $endTime = Carbon::parse('Today 13:30:00');

        // Create a desk
        $desk = Desks::factory()->create();

        // Create a fake booking
        $booking = Bookings::factory()->create();
        $booking->book_time_start = $startTime;
        $booking->book_time_end = $endTime;
        $booking->desk_id = $desk->id; 
        // Save your changes to the database
        $booking->save();

        // Update the booking start
        $newStartTime = $startTime->modify(' -15 minutes ');

        // Fake the booking update request
        $request = Request::create('', '', [
            "updated_desk_id" => $desk->id,
            "book_time_start" => $newStartTime, 
            "book_time_end" => $endTime,
        ]);

        // Create a controller
        $controller = new CalendarViewController();

        // Pass in our request
        $controller->update($request, 1);

        // Assert that the booking was not updated
        $this->assertDatabaseMissing("bookings", [
            "id" => $booking->id,
            "desk_id" => $desk->id,
            "book_time_start" => $newStartTime, 
            "book_time_end" => $endTime,
        ]);
    }

    /**
     * Testing that a booking can be deleted successfully
     *
     * @return void
     */
    public function test_booking_delete_success() {
        // Create the first fake user
        $user = User::factory()->create();

        // Create a fake desk
        $desk = Desks::factory()->create();

        // Create the booking event variables
        $book_time_start = Carbon::parse('Today 23:30:00');
        $book_time_end = Carbon::parse('Today 23:45:00');

        // Create a fake booking
        $booking = Bookings::factory()->create();
        $booking->user_id = $user->id;
        $booking->desk_id = $desk->id; 
        $booking->book_time_start = $book_time_start;
        $booking->book_time_end = $book_time_end;
        // Save changes to the database
        $booking->save();

        // Fake the booking delete request
        $request = Request::create('', '', [
            "user_id" => $user->id,
            "id" => $booking->id,
            "type" => "delete",
        ]);

        // Create a controller
        $controller = new CalendarViewController();

        // Pass in our request
        $controller->calendarEvents($request);

        // Assert that the booking is deleted
        $this->assertDatabaseMissing("bookings", [
            "id" => $booking->id,
            "book_time_start" => $book_time_start, 
            "book_time_end" => $book_time_end,
        ]);
    }

    /**
     * Testing that a booking cancellation can fail when the user IDs do not match
     *
     * @return void
     */
    public function test_booking_delete_fail_on_wrong_user_id() {
        // Create the first fake user
        $user = User::factory()->create();

        // Create a fake desk
        $desk = Desks::factory()->create();

        // Create the booking event variables
        $book_time_start = Carbon::parse('Today 12:30:00');
        $book_time_end = Carbon::parse('Today 13:30:00');

        // Create a fake booking
        $booking = Bookings::factory()->create();
        $booking->user_id = $user->id;
        $booking->desk_id = $desk->id; 
        $booking->book_time_start = $book_time_start;
        $booking->book_time_end = $book_time_end;
        // Save changes to the database
        $booking->save();

        // Fake the booking delete request
        $request = Request::create('', '', [
            "user_id" => 1,
            "id" => $booking->id,
            "type" => "delete",
        ]);

        // Create a controller
        $controller = new CalendarViewController();

        // Pass in our request
        $controller->calendarEvents($request);

        // Assert that the booking is deleted
        $this->assertDatabaseHas("bookings", [
            "id" => $booking->id,
            "book_time_start" => $book_time_start, 
            "book_time_end" => $book_time_end,
        ]);
    }

    /**
     * Testing that a booking cancellation can fail when the user is past their cancellation deadline
     *
     * @return void
     */
    public function test_booking_delete_fail_when_past_cancellation_deadline() {
        // Create the first fake user
        $user = User::factory()->create();

        // Create a fake desk
        $desk = Desks::factory()->create();

        // Create the booking event variables
        $book_time_start = Carbon::parse('Yesterday 12:30:00');
        $book_time_end = Carbon::parse('Yesterday 13:30:00');

        // Create a fake booking
        $booking = Bookings::factory()->create();
        $booking->user_id = $user->id;
        $booking->desk_id = $desk->id; 
        $booking->book_time_start = $book_time_start;
        $booking->book_time_end = $book_time_end;
        // Save changes to the database
        $booking->save();

        // Fake the booking delete request
        $request = Request::create('', '', [
            "user_id" => 1,
            "id" => $booking->id,
            "type" => "delete",
        ]);

        // Create a controller
        $controller = new CalendarViewController();

        // Pass in our request
        $controller->calendarEvents($request);

        // Assert that the booking is deleted
        $this->assertDatabaseHas("bookings", [
            "id" => $booking->id,
            "book_time_start" => $book_time_start, 
            "book_time_end" => $book_time_end,
        ]);
    }
}
