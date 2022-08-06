<?php

namespace Database\Seeders;

use App\Models\Bookings;
use App\Models\Buildings;
use App\Models\Campuses;
use App\Models\Desks;
use App\Models\Floors;
use App\Models\OccupationPolicyLimit;
use App\Models\Rooms;
use App\Models\User;
use App\Models\BookingHistory;
use App\Models\Resources;
use App\Models\Resources_Desks;
use App\Models\Resources_Rooms;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Empty Tables before Seeding
        DB::table('campuses')->truncate();
        DB::table('buildings')->truncate();
        DB::table('floors')->truncate();
        DB::table('rooms')->truncate();
        DB::table('desks')->truncate();
        DB::table('booking_history')->truncate();
        DB::table('policy_occupation_limit')->truncate();
        DB::table('resources')->truncate();
        DB::table('bookings')->truncate();
                
        // Create Okanagan Campus
        $campus = new Campuses;
        $campus->name = 'Okanagan';
        $campus->is_closed = FALSE;
        $campus->save();

        // Create Science Building For Okanagan Campus
        $building = new Buildings;
        $building->campus_id = $campus->id;
        $building->name = 'Science';
        $building->is_closed = FALSE;
        $building->save();

        // Create 1st Floor For Science Building
        $floor = new Floors;
        $floor->building_id = $building->id;
        $floor->floor_num = 1;
        $floor->is_closed = FALSE;
        $floor->save();

        // Create SCI 110 Room For 1st Floor
        $room = new Rooms;
        $room->floor_id = $floor->id;
        $room->name = "SCI 110";
        $room->occupancy = 30;
        $room->is_closed = False;
        $room->save();

        // Create a Desk for SCI 110 Room
        $desk = new Desks;
        $desk->room_id = $room->id;
        $desk->pos_x = 100;
        $desk->pos_y = 100;
        $desk->is_closed = TRUE;
        $desk->save();

        // create user to book with desk
        $user = User::factory()->create();
        // create booking using this user
        $booking = new Bookings;
        $booking->user_id = $user->id;
        $booking->desk_id = $desk->id;
        $booking->book_time_start = "2021-07-25 12:15:00";
        $booking->book_time_end = Carbon::now('GMT-7');
        $booking->save();

        // Create a Desk for SCI 110 Room
        $desk = new Desks;
        $desk->room_id = $room->id;
        $desk->pos_x = 120;
        $desk->pos_y = 120;
        $desk->is_closed = TRUE;
        $desk->save();

        // create booking using this user
        $booking = new Bookings;
        $booking->user_id = $user->id;
        $booking->desk_id = $desk->id;
        $booking->book_time_start = "2021-07-25 03:15:00";
        $booking->book_time_end = Carbon::now('GMT-7');
        $booking->save();

        $user = User::factory()->create();

        // create booking using this user
        $booking = new Bookings;
        $booking->user_id = $user->id;
        $booking->desk_id = $desk->id;
        $booking->book_time_start = "2021-07-25 03:15:00";
        $booking->book_time_end = Carbon::now('GMT-7');
        $booking->save();

        // Create a Desk for SCI 110 Room
        $desk = new Desks;
        $desk->room_id = $room->id;
        $desk->pos_x = 140;
        $desk->pos_y = 140;
        $desk->is_closed = TRUE;
        $desk->save();

        $booking_history = new BookingHistory;
        $booking_history->user_id = $user->id;
        $booking_history->desk_id = $desk->id;
        $booking_history->book_time_start = "2021-07-25 04:15:00";
        $booking_history->book_time_end = Carbon::now('GMT-7')->subYear();
        $booking_history->save();

        $resource = new Resources;
        $resource->resource_type = 'Lamp';
        $resource->icon = '<i class="bi bi-lamp-fill"></i>';
        $resource->colour = '#2081C3';
        $resource->save();

        //Create new Desk Resource
        $resourceDesk = new Resources_Desks;
        $resourceDesk -> resource_id = $resource->resource_id;
        $resourceDesk -> desk_id = $desk->id;
        $resourceDesk->save();

        //Create new Room Resource
        $resourceRoom = new Resources_Rooms;
        $resourceRoom -> resource_id = $resource->resource_id;
        $resourceRoom -> room_id = $room->id;
        $resourceRoom -> description = 'Lamp';
        $resourceRoom->save();

        $booking_history = new BookingHistory;
        $booking_history->user_id = $user->id;
        $booking_history->desk_id = $desk->id;
        $booking_history->book_time_start = "2021-07-25 15:15:00";
        $booking_history->book_time_end = Carbon::now('GMT-7')->subYear();
        $booking_history->save();

        // Create 2nd Floor For Science Building
        $floor = new Floors;
        $floor->building_id = $building->id;
        $floor->floor_num = 2;
        $floor->is_closed = FALSE;
        $floor->save();

        // Create SCI 220 Room For 2nd Floor
        $room = new Rooms;
        $room->floor_id = $floor->id;
        $room->name = "SCI 220";
        $room->occupancy = 30;
        $room->is_closed = False;
        $room->save();

        // Create a Desk for SCI 220 Room
        $desk = new Desks;
        $desk->room_id = $room->id;
        $desk->pos_x = 100;
        $desk->pos_y = 100;
        $desk->is_closed = TRUE;
        $desk->save();

        $booking_history = new BookingHistory;
        $booking_history->user_id = $user->id;
        $booking_history->desk_id = $desk->id;
        $booking_history->book_time_start = "2021-07-25 22:15:00";
        $booking_history->book_time_end = Carbon::now('GMT-7')->subYear();
        $booking_history->save();

        $resource = new Resources;
        $resource->resource_type = 'Printer';
        $resource->icon = '<i class="bi bi-printer-fill"></i>';
        $resource->colour = '#F7567C';
        $resource->save();

        //Create new Desk Resource
        $resourceDesk = new Resources_Desks;
        $resourceDesk -> resource_id = $resource->resource_id;
        $resourceDesk -> desk_id = $desk->id;
        $resourceDesk->save();

        //Create new Room Resource
        $resourceRoom = new Resources_Rooms;
        $resourceRoom -> resource_id = $resource->resource_id;
        $resourceRoom -> room_id = $room->id;
        $resourceRoom -> description = 'Printer';
        $resourceRoom->save();
        

        // Create a Desk for SCI 220 Room
        $desk = new Desks;
        $desk->room_id = $room->id;
        $desk->pos_x = 120;
        $desk->pos_y = 120;
        $desk->is_closed = TRUE;
        $desk->save();

        // Create 3rd Floor For Science Building
        $floor = new Floors;
        $floor->building_id = $building->id;
        $floor->floor_num = 3;
        $floor->is_closed = FALSE;
        $floor->save();

        // Create Arts Building For Okanagan Campus
        $building = new Buildings;
        $building->campus_id = $campus->id;
        $building->name = 'Arts';
        $building->is_closed = TRUE;
        $building->save();

        // Create 1st Floor For Arts Building
        $floor = new Floors;
        $floor->building_id = $building->id;
        $floor->floor_num = 1;
        $floor->is_closed = FALSE;
        $floor->save();

        // Create 2nd Floor For Arts Building
        $floor = new Floors;
        $floor->building_id = $building->id;
        $floor->floor_num = 2;
        $floor->is_closed = FALSE;
        $floor->save();
        
        //Create Booking History
        $booking_history = new BookingHistory;
        $booking_history->user_id = $user->id;
        $booking_history->desk_id = $desk->id;
        $booking_history->book_time_start = "2021-07-25 06:15:00";
        $booking_history->book_time_end = Carbon::now('GMT-7');
        $booking_history->save();

        $booking_history = new BookingHistory;
        $booking_history->user_id = $user->id;
        $booking_history->desk_id = $desk->id;
        $booking_history->book_time_start = "2022-07-25 03:15:00";
        $booking_history->book_time_end = Carbon::now('GMT-7');
        $booking_history->save();

        $booking_history = new BookingHistory;
        $booking_history->user_id = $user->id;
        $booking_history->desk_id = $desk->id;
        $booking_history->book_time_start = "2021-07-25 10:15:00";
        $booking_history->book_time_end = Carbon::now('GMT-7')->subMonth();
        $booking_history->save();

        $booking_history = new BookingHistory;
        $booking_history->user_id = $user->id;
        $booking_history->desk_id = $desk->id;
        $booking_history->book_time_start = "2023-07-25 11:15:00";
        $booking_history->book_time_end = Carbon::now('GMT-7')->addMonth();
        $booking_history->save();

        $booking_history = new BookingHistory;
        $booking_history->user_id = $user->id;
        $booking_history->desk_id = $desk->id;
        $booking_history->book_time_start = "2022-07-25 13:15:00";
        $booking_history->book_time_end = Carbon::now('GMT-7')->addYear();
        $booking_history->save();

        $booking_history = new BookingHistory;
        $booking_history->user_id = $user->id;
        $booking_history->desk_id = $desk->id;
        $booking_history->book_time_start = "2022-07-25 14:15:00";
        $booking_history->book_time_end = Carbon::now('GMT-7')->subYear();
        $booking_history->save();


        // Create Occupation Policy NOTE THIS WILL BE THE DEFAULT VALUE, no other values should be used or created, We will hard code this to set the id == 1
        $occupation = new OccupationPolicyLimit;
        $occupation->id = 1;
        $occupation->percentage = 100;
        $occupation->save();
        
        // Create Resource Outlet
        $resource = new Resources;
        $resource->resource_type = 'Outlet';
        $resource->icon = '<i class="bi bi-outlet"></i>';
        $resource->colour = '#2BC232';
        $resource->save();

        //Create new Desk Resource
        $resourceDesk = new Resources_Desks;
        $resourceDesk -> resource_id = $resource->resource_id;
        $resourceDesk -> desk_id = $desk->id;
        $resourceDesk->save();

        //Create new Room Resource
        $resourceRoom = new Resources_Rooms;
        $resourceRoom -> resource_id = $resource->resource_id;
        $resourceRoom -> room_id = $room->id;
        $resourceRoom -> description = 'Outlet';
        $resourceRoom->save();


        // Create SCI 220 Room For 2nd Floor
        $room = new Rooms;
        $room->floor_id = $floor->id;
        $room->name = "ART 200";
        $room->occupancy = 16;
        $room->rows = 8;
        $room->cols = 8;
        $room->is_closed = False;
        $room->save();

        // Create a Desk for Art 200 Room
        $desk = new Desks;
        $desk->room_id = $room->id;
        $desk->pos_x = 0;
        $desk->pos_y = 0;
        $desk->is_closed = TRUE;
        $desk->save();

        $desk = new Desks;
        $desk->room_id = $room->id;
        $desk->pos_x = 0;
        $desk->pos_y = 1;
        $desk->is_closed = FALSE;
        $desk->save();

        //Create new Printer Resource for Desk
        $resourceDesk = new Resources_Desks;
        $resourceDesk -> resource_id = $resource->resource_id;
        $resourceDesk -> desk_id = $desk->id;
        $resourceDesk->save(); 

        $desk = new Desks;
        $desk->room_id = $room->id;
        $desk->pos_x = 0;
        $desk->pos_y = 2;
        $desk->is_closed = TRUE;
        $desk->save();
        
        $campusV = new Campuses;
        $campusV->name = 'Vancouver';
        $campusV->is_closed = FALSE;
        $campusV->save();

        $booking = new BookingHistory;
        $booking->user_id = $user->id;
        $booking->desk_id = $desk->id;
        $booking->book_time_start = "2021-07-25 03:15:00";
        $booking->book_time_end = Carbon::now('GMT-7');
        $booking->save();
        
        $booking_history = new BookingHistory;
        $booking_history->user_id = $user->id;
        $booking_history->desk_id = $desk->id;
        $booking_history->book_time_start = "2021-07-25 00:15:00";
        $booking_history->book_time_end = Carbon::now('GMT-7')->subMonth();
        $booking_history->save();

        
        $booking_history = new BookingHistory;
        $booking_history->user_id = $user->id;
        $booking_history->desk_id = $desk->id;
        $booking_history->book_time_start = "2021-07-25 23:45:00";
        $booking_history->book_time_end = Carbon::now('GMT-7')->subMonth();
        $booking_history->save();

        // Create Resource Outlet
        $resource = new Resources;
        $resource->resource_type = 'Computer';
        $resource->icon = '<i class="bi bi-pc-display-horizontal"></i>';
        $resource->colour = '#B2F7EF';
        $resource->save();

        //Create new Desk Resource
        $resourceDesk = new Resources_Desks;
        $resourceDesk -> resource_id = $resource->resource_id;
        $resourceDesk -> desk_id = $desk->id;
        $resourceDesk->save();
    }
}