<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingsManagerController;
use App\Http\Controllers\BookingStatisticsController;
use App\Http\Controllers\BookingTimeStatisticsController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\CampusController;
use App\Http\Controllers\DepartmentStatisticsController;
use App\Http\Controllers\FloorController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\DeskController;
use App\Http\Controllers\FacultyStatisticsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PoliciesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RolesManagerController;
use App\Http\Controllers\PolicyManagerController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\ResourceManagerController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UsageStatisticsController;
use App\Http\Controllers\UserManagerController;
use App\Http\Controllers\HighChartController;
use App\Http\Controllers\ResourceStatisticsController;
use App\Http\Controllers\RolesStatisticsController;
use App\Http\Controllers\WorkspaceManagerController;
use App\Models\Buildings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
/////////////////////////////////// User-Side ////////////////////////////////////////
use App\Http\Controllers\User\UserBookingsController;
use App\Http\Controllers\User\SearchController;
use App\Http\Controllers\User\ModifyController;
use App\Http\Controllers\User\ViewBookingController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\CalendarViewController;
use App\Http\Controllers\User\FilterController;
use App\Http\Controllers\User\ViewDeskController;
use App\Http\Controllers\User\ChangePasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Auth::routes(['verify' => true]);
Route::get('/register', [RegisterController::class, 'index'])->name('register');

Route::resource('/campus', CampusController::class);
Route::post('/campusStore', [CampusController::class, 'store'])->name('campusStore');
Route::post('/campusUpdate/{id}', [CampusController::class, 'update'])->name('campusUpdate');

//Route::delete('/campusDestroy',[CampusController::class,'destroy'])->name('campusDestroy');

Route::resource('/building',BuildingController::class);
Route::post('/buildingStore',[BuildingController::class,'store'])->name('buildingStore');
Route::post('/buildingUpdate/{id}',[BuildingController::class,'update'])->name('buildingUpdate');

Route::resource('/floor', FloorController::class);
Route::post('/floorStore', [FloorController::class, 'store'])->name('floorStore');
Route::post('/floorUpdate/{id}', [FloorController::class, 'update'])->name('floorUpdate');

Route::resource('/room', RoomController::class);
Route::post('/roomStore', [RoomController::class, 'store'])->name('roomStore');
Route::post('/roomUpdate/{id}', [RoomController::class, 'update'])->name('roomUpdate');
Route::post('/roomSizeUpdate/{id}', [RoomController::class, 'addRoomSize'])->name('roomSizeUpdate');

Route::resource('/desk', DeskController::class);
Route::post('/deskStore', [DeskController::class, 'store'])->name('deskStore');
Route::post('/deskUpdate', [DeskController::class, 'update'])->name('deskUpdate');

Route::resource('/user', UserController::class);
Route::post('/userStore', [UserController::class, 'store'])->name('userStore');
Route::post('/userUpdate/{id}', [UserController::class, 'update'])->name('userUpdate');

Route::resource('/resource', ResourceController::class);
Route::post('/resourceStore', [ResourceController::class, 'store'])->name('resourceStore');
Route::post('/resourceUpdate/{resource_id}', [ResourceController::class, 'update'])->name('resourceUpdate');

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/campusManager', [WorkspaceManagerController::class, 'index'])->name('campusManager');
Route::get('/addCampus', [WorkspaceManagerController::class, 'addCampus'])->name('addCampus');
Route::get('/editCampus/{id}', [WorkspaceManagerController::class, 'editCampus'])->name('editCampus');


Route::get('/buildingManager/{campus_id}', [WorkspaceManagerController::class, 'buildingManager'])->name('buildingManager');
Route::get('/addBuilding/{campus_id}', [WorkspaceManagerController::class, 'addBuilding'])->name('addBuilding');
Route::get('/editBuilding/{id}', [WorkspaceManagerController::class, 'editBuilding'])->name('editBuilding');


Route::get('/floorManager/{building_id}', [WorkspaceManagerController::class, 'floorManager'])->name('floorManager');
Route::get('/addFloor/{building_id}', [WorkspaceManagerController::class, 'addFloor'])->name('addFloor');
Route::get('/editFloor/{id}', [WorkspaceManagerController::class, 'editFloor'])->name('editFloor');


Route::get('/roomManager/{floor_id}', [WorkspaceManagerController::class, 'roomManager'])->name('roomManager');
Route::get('/addRoom/{floor_id}', [WorkspaceManagerController::class, 'addRoom'])->name('addRoom');
Route::get('/editRoom/{id}', [WorkspaceManagerController::class, 'editRoom'])->name('editRoom');


Route::get('/deskManager/{room_id}', [WorkspaceManagerController::class, 'deskManager'])->name('deskManager');
Route::get('/addDesk/{room_id}', [WorkspaceManagerController::class, 'addDesk'])->name('addDesk');
Route::get('/editDesk/{id}', [WorkspaceManagerController::class, 'editDesk'])->name('editDesk');
// Route for AJAX call to load filtered rooms table
Route::get('/get-resources', [DeskController::class, 'getResources']);
Route::get('/get-resources-append-new', [DeskController::class, 'getResourcesAppendNew']);
Route::get('/get-resources-desk', [DeskController::class, 'getResourcesDesk']);

Route::get('/resourceManager', [ResourceManagerController::class, 'index'])->name('resourceManager');
Route::get('/addResource', [ResourceManagerController::class, 'addResource'])->name('addResource');
Route::get('/editResource{id}', [ResourceManagerController::class, 'editResource'])->name('editResource');

Route::get('/bookingsManager', [BookingsManagerController::class, 'index'])->name('bookingsManager');
Route::get('/viewBooking/{id}', [BookingsManagerController::class, 'viewBooking'])->name('viewBooking');
Route::get('/editBooking/{id}', [BookingsManagerController::class, 'editBooking'])->name('editBooking');
Route::get('/createBooking', [BookingsManagerController::class, 'createBooking'])->name('createBooking');

Route::resource('/booking', BookingController::class);
Route::post('/bookingUpdate/{id}', [BookingController::class, 'update'])->name('bookingUpdate');

Route::get('/policyManager', [PolicyManagerController::class, 'index'])->name('policyManager');
Route::get('/editRoomRestrictionsPolicy/{roomId}', [PolicyManagerController::class, 'editRoomRestrictionsPolicy'])->name('editRoomRestrictionsPolicy');
Route::post('/editRestrictionsPolicy/{roomId}', [PoliciesController::class, 'editRestrictionsPolicy'])->name('editRestrictionsPolicy');
Route::get('/cancelRestrictionsPolicy/{roomId}', [PoliciesController::class, 'cancelRestrictionsPolicy'])->name('cancelRestrictionsPolicy');
Route::post('/editOccupationPolicy/{occupationId}', [PoliciesController::class, 'editOccupationPolicy'])->name('editOccupationPolicy');
Route::post('/restoreOccupationPolicy', [PoliciesController::class, 'restoreOccupationPolicy'])->name('restoreOccupationPolicy');
Route::get('/editRolesBookingPolicy/{roleId}', [PolicyManagerController::class, 'editRolesBookingPolicy'])->name('editRolesBookingPolicy');
Route::post('/editRolesBookingPolicies/{roomId}', [PoliciesController::class, 'editRolesBookingPolicies'])->name('editRolesBookingPolicies');
Route::get('/cancelRolesBookingPolicies', [PoliciesController::class, 'cancelRolesBookingPolicies'])->name('cancelRolesBookingPolicies');
// Route for AJAX call to load filtered rooms table
Route::get('/get-filteredRooms', [PolicyManagerController::class, 'getFilteredRooms']);

Route::get('/usageStatistics', [UsageStatisticsController::class, 'index'])->name('usageStatistics');

Route::get('/rolesManager', [RolesManagerController::class, 'index'])->name('rolesManager');
Route::get('/viewRole/{id}', [RolesManagerController::class, 'viewRole'])->name('viewRole');
Route::get('/editRole/{id}', [RolesManagerController::class, 'editRole'])->name('editRole');
Route::get('/createRole', [RolesManagerController::class, 'createRole'])->name('createRole');

Route::resource('/role', RoleController::class);
Route::post('/role/{id}', [RoleController::class, 'update'])->name('roleUpdate');

Route::get('/userManager', [UserManagerController::class, 'index'])->name('userManager');
Route::get('/viewUser/{user_id}', [UserManagerController::class, 'viewUser'])->name('viewUser');
Route::get('/editUser/{user_id}', [UserManagerController::class, 'editUser'])->name('editUser');
Route::get('/addUser', [UserManagerController::class, 'addUser'])->name('addUser');

Route::get('/viewBookingStatistics', [BookingStatisticsController::class, 'index'])->name('viewBookingStatistics');
Route::get('/getYear/{year}', [BookingStatisticsController::class, 'getAjaxRequest']);
Route::get('/viewResourcesStatistics', [ResourceStatisticsController::class, 'index'])->name('viewResourcesStatistics');
Route::get('/viewFacultyStatistics', [FacultyStatisticsController::class, 'index'])->name('viewFacultyStatistics');
Route::get('/viewRolesStatistics', [RolesStatisticsController::class, 'index'])->name('viewRolesStatistics');
Route::get('/viewBookingTimeStatistics', [BookingTimeStatisticsController::class, 'index'])->name('viewBookingTimeStatistics');


/////////////////////////////////// User-Side ////////////////////////////////////////

Route::get('search',function(){
    return view('search');
});

Route::get('/register', [RegisterController::class, 'index'])->name('register');

Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/workSpaceManager', [WorkSpaceManagerController::class, 'index'])->name('workSpaceManager');

Route::get('/search', [SearchController::class, 'index'])->name('search');

Route::get('/bookings', [UserBookingsController::class, 'index'])->name('bookings');  
Route::get('/cancelBooking/{booking_id}', [UserBookingsController::class, 'cancel'])->name('cancelBooking');  

Route::get('/modify', [ModifyController::class, 'index'])->name('modify');

Route::get('/viewBooking/{booking_id}', [ViewBookingController::class, 'index'])->name('viewBooking');

Route::get('/viewDesk', [ViewDeskController::class, 'index'])->name('viewDesk');

Route::get('/filter', [FilterController::class, 'index'])->name('indicateBuilding');

Route::get('/calendar', [CalendarViewController::class, 'index'])->name('calendar');

Route::post('/bookings-ajax', [CalendarViewController::class, 'calendarEvents']); // updated the delete event dynamic

Route::patch('calendar/update/{id}', [CalendarViewController::class, 'update'])->name('calendarUpdate'); // delete and move the event

Route::get('/changePassword', [ChangePasswordController::class, 'showChangePasswordGet'])->name('changePasswordGet');

Route::post('/changePassword', [ChangePasswordController::class, 'changePasswordPost'])->name('changePasswordPost');