<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Faculty;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Bookings;
use App\Models\Buildings;
use App\Models\Campuses;
use App\Models\Desks;
use App\Models\Floors;
use App\Models\OccupationPolicyLimit;
use App\Models\Rooms;
use App\Models\BookingHistory;
use App\Models\Resources;
use App\Models\Resources_Desks;
use App\Models\Resources_Rooms;
use Carbon\Carbon;

class DBSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Empty Tables before Seeding
        DB::table('roles')->truncate();
        DB::table('users')->truncate();
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
        // Empty Tables before Seeding
        DB::table('faculties')->truncate();
        DB::table('departments')->truncate();

        $role = new Roles;
        $role->role = 'None';
        $role->num_monthly_bookings = 0;
        $role->max_booking_window = 0;
        $role->max_booking_duration = 0;
        $role->save();
        // create Staff role
        $role = new Roles;
        $role->role = 'Staff';
        $role->num_monthly_bookings = 8;
        $role->max_booking_window = 10;
        $role->max_booking_duration = 2;
        $role->save();

        // create Faculty role
        $role = new Roles;
        $role->role = 'Faculty';
        $role->num_monthly_bookings = 12;
        $role->max_booking_window = 21;
        $role->max_booking_duration = 8;
        $role->save();

        // create Graduate role
        $role = new Roles;
        $role->role = 'Graduate';
        $role->num_monthly_bookings = 12;
        $role->max_booking_window = 21;
        $role->max_booking_duration = 4;
        $role->save();

         // create Undergraduate role
        $role = new Roles;
        $role->role = 'Undergraduate';
        $role->num_monthly_bookings = 12;
        $role->max_booking_window = 21;
        $role->max_booking_duration = 4;
        $role->save();

        $role = new Roles;
        $role->role = 'Guest';
        $role->num_monthly_bookings = 10;
        $role->max_booking_window = 10;
        $role->max_booking_duration = 4;
        $role->save();

        // College of Graduate Studies
        $faculty = new Faculty;
        $faculty->faculty = 'College of Graduate Studies';
        $faculty->save();

        $department = new Department;
        $department->department = 'Graduate Studies';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Faculty of Creative and critical Studies
        $faculty = new Faculty;
        $faculty->faculty = 'Faculty of Creative and Critical Studies';
        $faculty->save();

        $department = new Department;
        $department->department = 'Creative Studies';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'English and Cultural Studies';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Languages and World Literature';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Faculty of Health and Social Development
        $faculty = new Faculty;
        $faculty->faculty = 'Faculty of Health and Social Development';
        $faculty->save();

        $department = new Department;
        $department->department = 'Health and Exercise Sciences';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Nursing';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Social Work';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Faculty of Health and Social Development
        $faculty = new Faculty;
        $faculty->faculty = 'Faculty of Management';
        $faculty->save();

        $department = new Department;
        $department->department = 'Management';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Faculty of Medicine Southern Medical Program
        $faculty = new Faculty;
        $faculty->faculty = 'Faculty of Medicine Southern Medical Program';
        $faculty->save();

        $department = new Department;
        $department->department = 'Southern Medical Program';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Irving K. Barber Faculty of Arts and Social Sciences
        $faculty = new Faculty;
        $faculty->faculty = 'Irving K. Barber Faculty of Arts and Social Sciences';
        $faculty->save();

        $department = new Department;
        $department->department = 'Community, Culture and Global Studies';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Economics, Philosophy and Political Science';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'History and Sociology';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Psychology';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Irving K. Barber Faculty of Science
        $faculty = new Faculty;
        $faculty->faculty = 'Irving K. Barber Faculty of Science';
        $faculty->save();

        $department = new Department;
        $department->department = 'Biology';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Chemistry';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Computer Science, Mathematics, Physics, and Statistics';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Earth, Environmental and Geographic Sciences';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Okanagan School of Education
        $faculty = new Faculty;
        $faculty->faculty = 'Okanagan School of Education';
        $faculty->save();

        $department = new Department;
        $department->department = 'Education';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // School of Engineering
        $faculty = new Faculty;
        $faculty->faculty = 'School of Engineering';
        $faculty->save();

        $department = new Department;
        $department->department = 'Engineering';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Admin Account - enter the info for you admin account
        $admin = User::create([
            'first_name' => 'Damyn',
            'last_name' => 'Admin',
            'bookings_used' => 0,
            'role_id' => 5,
            'email' => 'damyn@ubc.ca',
            'password' => Hash::make('password'), /*default local password is "password" */
            'is_admin' => TRUE,
            'email_verified_at' => Carbon::now(),
            'department_id' => 16, // comp sci department
        ]);

        // User Account - enter the info for you user account
        $user = User::create([
            'first_name' => 'Damyn',
            'last_name' => 'User',
            'bookings_used' => 0,
            'role_id' => 5,
            'email' => 'damyn@gmail.com',
            'password' => Hash::make('password'), /*default local password is "password" */
            'email_verified_at' => Carbon::now(),
            'department_id' => 16, // comp sci department
        ]);

        // Create Okanagan Campus
        $campus = new Campuses;
        $campus->name = 'Okanagan';
        $campus->is_closed = FALSE;
        $campus->save();

        // Create Creative and Critical Studies Building For Okanagan Campus
        $building = new Buildings;
        $building->campus_id = $campus->id;
        $building->name = 'Creative and Critical Studies';
        $building->is_closed = FALSE;
        $building->save();

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
        $room->rows = 6;
        $room->cols = 6;
        $room->occupancy = 30;
        $room->is_closed = False;
        $room->save();

        // Create a Desk for SCI 110 Room
        $desk = new Desks;
        $desk->room_id = $room->id;
        $desk->pos_x = 1;
        $desk->pos_y = 1;
        $desk->is_closed = false;
        $desk->save();

        // create user to book with desk
        // User Account - enter the info for you user account
        $user = User::create([
            'first_name' => 'Sally',
            'last_name' => 'Jones',
            'bookings_used' => 0,
            'role_id' => 4,
            'email' => 'sallyjones@gmail.com',
            'password' => Hash::make('password'), /*default local password is "password" */
            'email_verified_at' => Carbon::now(),
            'department_id' => 17,
        ]);

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
        $desk->pos_x = 2;
        $desk->pos_y = 2;
        $desk->is_closed = false;
        $desk->save();

        // create booking using this user
        $booking = new Bookings;
        $booking->user_id = $user->id;
        $booking->desk_id = $desk->id;
        $booking->book_time_start = "2021-07-25 03:15:00";
        $booking->book_time_end = Carbon::now('GMT-7');
        $booking->save();

        $user = User::create([
            'first_name' => 'Jerry',
            'last_name' => 'Thompson',
            'bookings_used' => 0,
            'role_id' => 3,
            'email' => 'jerrythompson@gmail.com',
            'password' => Hash::make('password'), /*default local password is "password" */
            'email_verified_at' => Carbon::now(),
            'department_id' => 11,
        ]);

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
        $desk->pos_x = 1;
        $desk->pos_y = 2;
        $desk->is_closed = false;
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
        $room->rows = 6;
        $room->cols = 6;
        $room->occupancy = 30;
        $room->is_closed = False;
        $room->save();

        // Create a Desk for SCI 220 Room
        $desk = new Desks;
        $desk->room_id = $room->id;
        $desk->pos_x = 1;
        $desk->pos_y = 1;
        $desk->is_closed = false;
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
        $desk->pos_x = 1;
        $desk->pos_y = 2;
        $desk->is_closed = false;
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
        $building->is_closed = false;
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
        $desk->is_closed = false;
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
        $desk->is_closed = false;
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
