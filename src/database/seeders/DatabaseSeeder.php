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

class DatabaseSeeder extends Seeder
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

        // Admin Account - enter the info for you admin account
        $admin = User::create([
            'first_name' => 'Damyn',
            'last_name' => 'Admin',
            'bookings_used' => 0,
            'role_id' => $role->role_id,
            'email' => 'damyn@ubc.ca',
            'password' => Hash::make('password'), /*default local password is "password" */
            'is_admin' => TRUE,
            'email_verified_at' => Carbon::now(),
            'department_id' => Department::factory()->create()->department_id
        ]);

        // User Account - enter the info for you user account
        $user = User::create([
            'first_name' => 'Damyn',
            'last_name' => 'User',
            'bookings_used' => 0,
            'role_id' => $role->role_id,
            'email' => 'damyn@gmail.com',
            'password' => Hash::make('password'), /*default local password is "password" */
            'email_verified_at' => Carbon::now(),
            'department_id' => Department::factory()->create()->department_id
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
        $user->first_name = 'Jackeline';
        $user->save();

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

        // create faculties for the vancouver campus
        $facultyAS = new Faculty;
        $facultyAS->faculty = 'Faculty of Applied Science';
        $facultyAS->save();

        $facultyALA = new Faculty;
        $facultyALA->faculty = 'Faculty of Architecture and Landscape Architecture';
        $facultyALA->save();

        // Departments for the applied sciences faculty
        $departmentALA = new Department;
        $departmentALA->department = 'School of Architecture and Landscape Architecture';
        $departmentALA->faculty_id = $facultyAS->faculty_id;
        $departmentALA->save();

        $departmentCRP = new Department;
        $departmentCRP->department = 'School of (SCARP) Community and Regional Planning';
        $departmentCRP->faculty_id = $facultyAS->faculty_id;
        $departmentCRP->save();

        $departmentBE = new Department;
        $departmentBE->department = 'School of Biomedical Engineering';
        $departmentBE->faculty_id = $facultyAS->faculty_id;
        $departmentBE->save();

        $departmentCBE = new Department;
        $departmentCBE->department = 'Department of Chemical and Biological Engineering';
        $departmentCBE->faculty_id = $facultyAS->faculty_id;
        $departmentCBE->save();

        $departmentCE = new Department;
        $departmentCE->department = 'Department of Civil Engineering';
        $departmentCE->faculty_id = $facultyAS->faculty_id;
        $departmentCE->save();

        $departmentECE = new Department;
        $departmentECE->department = 'Department of Electrical and Computer Engineering';
        $departmentECE->faculty_id = $facultyAS->faculty_id;
        $departmentECE->save();

        $departmentEP = new Department;
        $departmentEP->department = 'Engineering Physics';
        $departmentEP->faculty_id = $facultyAS->faculty_id;
        $departmentEP->save();

        $departmentEE = new Department;
        $departmentEE->department = 'Environmental Engineering';
        $departmentEE->faculty_id = $facultyAS->faculty_id;
        $departmentEE->save();

        $departmentEEJ = new Department;
        $departmentEEJ->department = 'Environmental Engineering (Joint UBC/UNBC program)';
        $departmentEEJ->faculty_id = $facultyAS->faculty_id;
        $departmentEEJ->save();

        $departmentGE = new Department;
        $departmentGE->department = 'Geological Engineering';
        $departmentGE->faculty_id = $facultyAS->faculty_id;
        $departmentGE->save();

        $departmentIE = new Department;
        $departmentIE->department = 'Integrated Engineering';
        $departmentIE->faculty_id = $facultyAS->faculty_id;
        $departmentIE->save();

        $departmentME = new Department;
        $departmentME->department = 'Manufacturing Engineering';
        $departmentME->faculty_id = $facultyAS->faculty_id;
        $departmentME->save();

        $departmentMAE = new Department;
        $departmentMAE->department = 'Department of Materials Engineering';
        $departmentMAE->faculty_id = $facultyAS->faculty_id;
        $departmentMAE->save();

        $departmentMECE = new Department;
        $departmentMECE->department = 'Department of Mechanical Engineering';
        $departmentMECE->faculty_id = $facultyAS->faculty_id;
        $departmentMECE->save();

        $departmentMINE = new Department;
        $departmentMINE->department = 'Norman B. Keevil Institute of Mining Engineering';
        $departmentMINE->faculty_id = $facultyAS->faculty_id;
        $departmentMINE->save();

        $departmentMEL = new Department;
        $departmentMEL->department = 'Master of Engineering Leadership';
        $departmentMEL->faculty_id = $facultyAS->faculty_id;
        $departmentMEL->save();

        $departmentMHLP = new Department;
        $departmentMHLP->department = 'Master of Health Leadership and Policy';
        $departmentMHLP->faculty_id = $facultyAS->faculty_id;
        $departmentMHLP->save();

        $departmentN = new Department;
        $departmentN->department = 'School of Nursing';
        $departmentN->faculty_id = $facultyAS->faculty_id;
        $departmentN->save();

        // Department of Architecture and Landscape Architecture
        $departmentSALA = new Department;
        $departmentSALA->department = 'School of Architecture and Landscape Architecture';
        $departmentSALA->faculty_id = $facultyALA->faculty_id;
        $departmentSALA->save();

        // Faculty and department of arts ubcV
        $facultyARTS = new Faculty;
        $facultyARTS->faculty = 'Faculty of Arts';
        $facultyARTS->save();

        $departmentDOA = new Department;
        $departmentDOA->department = 'Anthropology';
        $departmentDOA->faculty_id = $facultyARTS->faculty_id;
        $departmentDOA->save();

        $departmentAHVA = new Department;
        $departmentAHVA->department = 'Art History, Visual Art and Theory';
        $departmentAHVA->faculty_id = $facultyARTS->faculty_id;
        $departmentAHVA->save();

        $departmentAO = new Department;
        $departmentAO->department = 'Arts One';
        $departmentAO->faculty_id = $facultyARTS->faculty_id;
        $departmentAO->save();

        $departmentDAS = new Department;
        $departmentDAS->department = 'Asian Studies';
        $departmentDAS->faculty_id = $facultyARTS->faculty_id;
        $departmentDAS->save();

        $departmentCENES = new Department;
        $departmentCENES->department = 'Central Eastern Northern European Studies';
        $departmentCENES->faculty_id = $facultyARTS->faculty_id;
        $departmentCENES->save();

        $departmentCNERS = new Department;
        $departmentCNERS->department = 'Classical, Near Eastern and Religious Studies';
        $departmentCNERS->faculty_id = $facultyARTS->faculty_id;
        $departmentCNERS->save();

        $departmentCOAP = new Department;
        $departmentCOAP->department = 'Co-ordinated Arts Program';
        $departmentCOAP->faculty_id = $facultyARTS->faculty_id;
        $departmentCOAP->save();

        $departmentCWP = new Department;
        $departmentCWP->department = 'Creative Writing Program';
        $departmentCWP->faculty_id = $facultyARTS->faculty_id;
        $departmentCWP->save();

        $departmentVSE = new Department;
        $departmentVSE->department = 'Vancouver School of Economics';
        $departmentVSE->faculty_id = $facultyARTS->faculty_id;
        $departmentVSE->save();

        $departmentELLD = new Department;
        $departmentELLD->department = 'English Language & Literatures';
        $departmentELLD->faculty_id = $facultyARTS->faculty_id;
        $departmentELLD->save();

        $departmentFHIS = new Department;
        $departmentFHIS->department = 'French, Hispanic, & Italian Studies';
        $departmentFHIS->faculty_id = $facultyARTS->faculty_id;
        $departmentFHIS->save();

        $departmentFNELP = new Department;
        $departmentFNELP->department = 'First Nations and Endangered Languages Program';
        $departmentFNELP->faculty_id = $facultyARTS->faculty_id;
        $departmentFNELP->save();

        $departmentFNISP = new Department;
        $departmentFNISP->department = 'First Nations and Indigenous Studies Program';
        $departmentFNISP->faculty_id = $facultyARTS->faculty_id;
        $departmentFNISP->save();

        $departmentGRSSJ = new Department;
        $departmentGRSSJ->department = 'Institute for Gender, Race, Sexuality and Social Justice';
        $departmentGRSSJ->faculty_id = $facultyARTS->faculty_id;
        $departmentGRSSJ->save();

        $departmentCSS = new Department;
        $departmentCSS->department = 'Critical Studies in Sexuality';
        $departmentCSS->faculty_id = $facultyARTS->faculty_id;
        $departmentCSS->save();

        $departmentGEO = new Department;
        $departmentGEO->department = 'Geography';
        $departmentGEO->faculty_id = $facultyARTS->faculty_id;
        $departmentGEO->save();

        $departmentHIST = new Department;
        $departmentHIST->department = 'History';
        $departmentHIST->faculty_id = $facultyARTS->faculty_id;
        $departmentHIST->save();

        $departmentICIS = new Department;
        $departmentICIS->department = 'Institute for Critical Indigenous Studies';
        $departmentICIS->faculty_id = $facultyARTS->faculty_id;
        $departmentICIS->save();

        $departmentJ = new Department;
        $departmentJ->department = 'School of Journalism';
        $departmentJ->faculty_id = $facultyARTS->faculty_id;
        $departmentJ->save();

        $departmentLAIS = new Department;
        $departmentLAIS->department = 'Library, Archival and Information Studies';
        $departmentLAIS->faculty_id = $facultyARTS->faculty_id;
        $departmentLAIS->save();

        $departmentLING = new Department;
        $departmentLING->department = 'Linguistics';
        $departmentLING->faculty_id = $facultyARTS->faculty_id;
        $departmentLING->save();

        $departmentMA = new Department;
        $departmentMA->department = 'Museum of Anthropology';
        $departmentMA->faculty_id = $facultyARTS->faculty_id;
        $departmentMA->save();

        $departmentM = new Department;
        $departmentM->department = 'School of Music';
        $departmentM->faculty_id = $facultyARTS->faculty_id;
        $departmentM->save();

        $departmentP = new Department;
        $departmentP->department = 'Philosophy';
        $departmentP->faculty_id = $facultyARTS->faculty_id;
        $departmentP->save();

        $departmentPOLI = new Department;
        $departmentPOLI->department = 'Political Science';
        $departmentPOLI->faculty_id = $facultyARTS->faculty_id;
        $departmentPOLI->save();

        $departmentEUR = new Department;
        $departmentEUR->department = 'Institute of European Studies';
        $departmentEUR->faculty_id = $facultyARTS->faculty_id;
        $departmentEUR->save();

        $departmentPSYCH = new Department;
        $departmentPSYCH->department = 'Psychology';
        $departmentPSYCH->faculty_id = $facultyARTS->faculty_id;
        $departmentPSYCH->save();

        $departmentPPGA = new Department;
        $departmentPPGA->department = 'School of Public Policy and Global Affairs';
        $departmentPPGA->faculty_id = $facultyARTS->faculty_id;
        $departmentPPGA->save();

        $departmentSSW = new Department;
        $departmentSSW->department = 'School of Social Work';
        $departmentSSW->faculty_id = $facultyARTS->faculty_id;
        $departmentSSW->save();

        $departmentSOC = new Department;
        $departmentSOC->department = 'Sociology';
        $departmentSOC->faculty_id = $facultyARTS->faculty_id;
        $departmentSOC->save();

        $departmentTAF = new Department;
        $departmentTAF->department = 'Theatre and Film';
        $departmentTAF->faculty_id = $facultyARTS->faculty_id;
        $departmentTAF->save();

        // Faculty of Audio Audiology and Speech Science
        $facultyASS = new Faculty;
        $facultyASS->faculty = 'Audiology and Speech Sciences';
        $facultyASS->save();

        $departmentASS = new Department;
        $departmentASS->department = 'Audiology and Speech Sciences';
        $departmentASS->faculty_id = $facultyASS->faculty_id;
        $departmentASS->save();

        // Faculty of Business
        $facultyBUS = new Faculty;
        $facultyBUS->faculty = 'Sauder School of Business';
        $facultyBUS->save();

        $departmentAIS = new Department;
        $departmentAIS->department = 'Division of Accounting and Information Systems';
        $departmentAIS->faculty_id = $facultyBUS->faculty_id;
        $departmentAIS->save();

        $departmentBC = new Department;
        $departmentBC->department = 'Bachelor of Commerce';
        $departmentBC->faculty_id = $facultyBUS->faculty_id;
        $departmentBC->save();

        $departmentEIG = new Department;
        $departmentEIG->department = 'Entrepreneurship & Innovation Group';
        $departmentEIG->faculty_id = $facultyBUS->faculty_id;
        $departmentEIG->save();

        $departmentEEDU = new Department;
        $departmentEEDU->department = 'Executive Education';
        $departmentEEDU->faculty_id = $facultyBUS->faculty_id;
        $departmentEEDU->save();

        $departmentDF = new Department;
        $departmentDF->department = 'Division of Finance';
        $departmentDF->faculty_id = $facultyBUS->faculty_id;
        $departmentDF->save();

        $departmentLBCG = new Department;
        $departmentLBCG->department = 'Law & Business Communications Group';
        $departmentLBCG->faculty_id = $facultyBUS->faculty_id;
        $departmentLBCG->save();

        $departmentDMBS = new Department;
        $departmentDMBS->department = 'Division of Marketing & Behavioural Science';
        $departmentDMBS->faculty_id = $facultyBUS->faculty_id;
        $departmentDMBS->save();

        $departmentMBA = new Department;
        $departmentMBA->department = 'Master of Business Administration';
        $departmentMBA->faculty_id = $facultyBUS->faculty_id;
        $departmentMBA->save();

        $departmentMBA = new Department;
        $departmentMBA->department = 'Master of Business Administration';
        $departmentMBA->faculty_id = $facultyBUS->faculty_id;
        $departmentMBA->save();

        $departmentMOM = new Department;
        $departmentMOM->department = 'Master of Management';
        $departmentMOM->faculty_id = $facultyBUS->faculty_id;
        $departmentMOM->save();

        $departmentDOL = new Department;
        $departmentDOL->department = 'Division of Operations and Logistics';
        $departmentDOL->faculty_id = $facultyBUS->faculty_id;
        $departmentDOL->save();

        $departmentOBHR = new Department;
        $departmentOBHR->department = 'Division of Organizational Behaviour and Human Resources';
        $departmentOBHR->faculty_id = $facultyBUS->faculty_id;
        $departmentOBHR->save();

        $departmentPHDP = new Department;
        $departmentPHDP->department = 'PhD Program';
        $departmentPHDP->faculty_id = $facultyBUS->faculty_id;
        $departmentPHDP->save();

        $departmentRED = new Department;
        $departmentRED->department = 'Real Estate Division';
        $departmentRED->faculty_id = $facultyBUS->faculty_id;
        $departmentRED->save();

        $departmentSBE = new Department;
        $departmentSBE->department = 'Division of Strategy and Business Economics';
        $departmentSBE->faculty_id = $facultyBUS->faculty_id;
        $departmentSBE->save();

        // Community and Regional Planning, School of
        $faculty = new Faculty;
        $faculty->faculty = 'Community and Regional Planning';
        $faculty->save();

        $department = new Department;
        $department->department = 'Centre for Human Settlements';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Dentistry
        $faculty = new Faculty;
        $faculty->faculty = 'Faculty of Dentistry';
        $faculty->save();

        $department = new Department;
        $department->department = 'Department of Oral Biological and Medical Sciences (OBMS)';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Oral Health Sciences (OHS)';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Education 
        $faculty = new Faculty;
        $faculty->faculty = 'Faculty of Education';
        $faculty->save();

        $department = new Department;
        $department->department = 'Department of Educational and Counselling Psychology, and Special Education (ECPS)';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Centre for Cross-Faculty Inquiry in Education (CCFI)';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Curriculum and Pedagogy (EDCP)';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Educational Studies (EDST)';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'School of Kinesiology (HKIN)';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Language and Literacy Education (LLED)';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Teacher Education';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Extended Learning
        $faculty = new Faculty;
        $faculty->faculty = 'Extended Learning';
        $faculty->save();

        $department = new Department;
        $department->department = 'Continuing Education for Adult Learners (In-class and Online)';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'English Language Institute';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Forestry
        $faculty = new Faculty;
        $faculty->faculty = 'Faculty of Forestry';
        $faculty->save();

        $department = new Department;
        $department->department = 'Department of Forest Resources Management';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Forest Sciences';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Wood Science';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Graduate and Postdoctoral Studies
        $faculty = new Faculty;
        $faculty->faculty = 'Graduate and Postdoctoral Studies';
        $faculty->save();

        $department = new Department;
        $department->department = 'Green College';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = "St. John's College";
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Journalism
        $faculty = new Faculty;
        $faculty->faculty = 'School of Journalism';
        $faculty->save();

        $department = new Department;
        $department->department = 'International Reporting Program';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Journalism
        $faculty = new Faculty;
        $faculty->faculty = 'School of Kinesiology';
        $faculty->save();

        $department = new Department;
        $department->department = 'Kinesiology';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();
        
        // Journalism
        $faculty = new Faculty;
        $faculty->faculty = 'Faculty of Land and Food Systems';
        $faculty->save();

        $department = new Department;
        $department->department = 'Global Resource Systems';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Applied Biology';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Food, Nutrition & Health';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Graduate Programs';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Journalism
        $faculty = new Faculty;
        $faculty->faculty = 'Peter A. Allard School of Law';
        $faculty->save();

        $department = new Department;
        $department->department = 'School of Law';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Library, Archival and Information Studies, School of
        $faculty = new Faculty;
        $faculty->faculty = 'School of Library, Archival and Information Studies';
        $faculty->save();
        
        $department = new Department;
        $department->department = 'Library, Archival and Information Studies';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Medicine
        $faculty = new Faculty;
        $faculty->faculty = 'Faculty of Medicine';
        $faculty->save();

        $department = new Department;
        $department->department = 'Department of Anesthesiology, Pharmacology & Therapeutics';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'School of Audiology and Speech Sciences';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Biochemistry and Molecular Biology';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Cellular and Physiological Sciences';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Dermatology and Skin Science';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Family Practice';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Division of Midwifery';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'International Collaboration on Repair Discoveries (ICORD)';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Medical Genetics';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Medicine';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Division of Allergy and Immunology';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Division of Cardiology';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Division of Critical Care Medicine';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Division of Endocrinology';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Division of Experimental Medicine';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Division of Gastroenterology';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Division of General Internal Medicine';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Division of Geriatric Medicine';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Division of Hematology';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Division of Infectious Diseases';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Division of Medical Oncology';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();
        
        $department = new Department;
        $department->department = 'Division of Nephrology';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Division of Neurology';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Division of Physical Medicine and Rehabilitation';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Division of Respiratory Medicine';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Division of Rheumatology';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Neuroscience';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Obstetrics and Gynaecology';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Occupational Science & Occupational Therapy';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Ophthalmology and Visual Sciences';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Orthopaedic Surgery';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Pathology and Laboratory Medicine';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Pediatrics';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Physical Therapy';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'School of Population and Public Health';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Psychiatry';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Radiology';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Division of Nuclear Medicine';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Surgery';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Urologic Sciences';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Music
        $faculty = new Faculty;
        $faculty->faculty = 'School of Music';
        $faculty->save();

        $department = new Department;
        $department->department = 'Music';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Music
        $faculty = new Faculty;
        $faculty->faculty = 'School of Nursing';
        $faculty->save();

        $department = new Department;
        $department->department = 'Nursing';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // School of population and Public Health
        $faculty = new Faculty;
        $faculty->faculty = 'School of Population and Public Health';
        $faculty->save();

        $department = new Department;
        $department->department = 'Applied Ethics, W Maurice Young Centre for (CAE)';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Human Early Learning Partnership (HELP)';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();
        
        // School of population and Public Health
        $faculty = new Faculty;
        $faculty->faculty = 'School of Public Policy and Global Affairs';
        $faculty->save();

        $department = new Department;
        $department->department = 'Institute of Asian Research';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Canadian International Resources and Development Institute';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Liu Institute for Global Issues';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Centre for the Study of Democratic Institutions';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Pharmaceutical Sciences, Faculty of
        $faculty = new Faculty;
        $faculty->faculty = 'Faculty of Pharmaceutical Sciences';
        $faculty->save();

        $department = new Department;
        $department->department = 'Pharmaceutical Sciences';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // science
        $faculty = new Faculty;
        $faculty->faculty = 'Faculty of Science';
        $faculty->save();

        $department = new Department;
        $department->department = 'Applied Mathematics, Institute of (IAM)';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Bioinformatics';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Botany';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Chemistry';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Computer Science';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Earth and Ocean Sciences';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Fisheries Centre (FC)';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Fisheries Economics Research Unit';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Marine Mammal Research Unit';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Project Seahorse';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Sea Around Us Project';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Fisheries Ecosystems Restoration Research';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Quantitative Analysis and Modeling';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Mathematics';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();
        
        $department = new Department;
        $department->department = 'Department of Microbiology and Immunology';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Physics and Astronomy';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();
        
        $department = new Department;
        $department->department = 'Institute for Resources, Environment and Sustainability (IRES)';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Eco-Risk Research Unit';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Forest Economics and Policy Analysis';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Sustainable Development Research Initiative';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Westwater Research Unit';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Resources, Management and Environmental Studies';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Statistics';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Zoology';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // social work
        $faculty = new Faculty;
        $faculty->faculty = 'School of Social Work';
        $faculty->save();

        $department = new Department;
        $department->department = 'Social Work';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // UBC Vantage College
        $faculty = new Faculty;
        $faculty->faculty = 'UBC Vantage College';
        $faculty->save();

        $department = new Department;
        $department->department = 'UBC Vantage College';
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

        // Faculty of Medicine Southern Medical Program
        $faculty = new Faculty;
        $faculty->faculty = 'Faculty of Medicine Southern Medical Program';
        $faculty->save();

        $department = new Department;
        $department->department = 'Southern Medical Program';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // College of Graduate Studies
        $faculty = new Faculty;
        $faculty->faculty = 'College of Graduate Studies';
        $faculty->save();

        $department = new Department;
        $department->department = 'Graduate Studies';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();
    }
}
