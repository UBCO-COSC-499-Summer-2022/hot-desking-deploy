<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\BookingsManagerController;
use App\Http\Controllers\Admin\BookingStatisticsController;
use App\Http\Controllers\Admin\BookingTimeStatisticsController;
use App\Http\Controllers\Admin\BuildingController;
use App\Http\Controllers\Admin\CampusController;
use App\Http\Controllers\Admin\DepartmentStatisticsController;
use App\Http\Controllers\Admin\FloorController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\DeskController;
use App\Http\Controllers\Admin\FacultyStatisticsController;
use App\Http\Controllers\Admin\PoliciesController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RolesManagerController;
use App\Http\Controllers\Admin\PolicyManagerController;
use App\Http\Controllers\Admin\ResourceController;
use App\Http\Controllers\Admin\ResourceManagerController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UsageStatisticsController;
use App\Http\Controllers\Admin\UserManagerController;
use App\Http\Controllers\Admin\HighChartController;
use App\Http\Controllers\Admin\ResourceStatisticsController;
use App\Http\Controllers\Admin\RolesStatisticsController;
use App\Http\Controllers\Admin\WorkspaceManagerController;
use App\Http\Controllers\Admin\EmailLogsController;
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
use App\Http\Controllers\User\ChangeEmailController;
////////////////////////////////// Shared /////////////////////////////////////////////
use App\Http\Controllers\HomeController;

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

Route::get('/suspended', function () {
    return view('suspended');
})->name('suspended');

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
Route::get('/get-filteredRooms', [PolicyManagerController::class, 'getFilteredRooms'])->name('get-filteredRooms');

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

Route::get('/viewResourcesStatistics', [ResourceStatisticsController::class, 'index'])->name('viewResourcesStatistics');
Route::get('/getFilterResources', [ResourceStatisticsController::class, 'getFilterResources']);
Route::get('/viewDepartmentStatistics', [DepartmentStatisticsController::class, 'index'])->name('viewDepartmentStatistics');
Route::get('/getFilterDepartments', [DepartmentStatisticsController::class, 'getFilterDepartments']);
Route::get('/viewRolesStatistics', [RolesStatisticsController::class, 'index'])->name('viewRolesStatistics');
Route::get('/getFilterRoles', [RolesStatisticsController::class, 'getFilterRoles']);
Route::get('/viewBookingTimeStatistics', [BookingTimeStatisticsController::class, 'index'])->name('viewBookingTimeStatistics');
Route::get('/getFilterBookingTimes', [BookingTimeStatisticsController::class, 'getFilterBookingTimes']);

Route::get('/emailLogs', [EmailLogsController::class, 'index'])->name('emailLogs');
Route::get('/downloadLogs', [EmailLogsController::class, 'downloadLogs'])->name('downloadLogs');

/////////////////////////////////// User-Side ////////////////////////////////////////

Route::get('search',function(){
    return view('search');
});

Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::post('/profileUpdate', [ProfileController::class, 'update'])->name('profileUpdate');

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/workSpaceManager', [WorkSpaceManagerController::class, 'index'])->name('workSpaceManager');

Route::get('/search', [SearchController::class, 'index'])->name('search');

Route::get('/bookings', [UserBookingsController::class, 'index'])->name('bookings');  
Route::get('/cancelBooking/{booking_id}', [UserBookingsController::class, 'cancel'])->name('cancelBooking');  

Route::get('/modify', [ModifyController::class, 'index'])->name('modify');

Route::get('/viewUserBooking/{booking_id}', [ViewBookingController::class, 'index'])->name('viewUserBooking');

Route::get('/viewDesk', [ViewDeskController::class, 'index'])->name('viewDesk');

Route::get('/filter', [FilterController::class, 'index'])->name('indicateBuilding');

Route::get('/calendar', [CalendarViewController::class, 'index'])->name('calendar');

Route::post('/bookings-ajax', [CalendarViewController::class, 'calendarEvents']); // updated the delete event dynamic

Route::patch('calendar/update/{id}', [CalendarViewController::class, 'update'])->name('calendarUpdate'); // delete and move the event

Route::get('/changePassword', [ChangePasswordController::class, 'showChangePasswordGet'])->name('changePasswordGet');

Route::post('/changePassword', [ChangePasswordController::class, 'changePasswordPost'])->name('changePasswordPost');

Route::get('/changeEmail', [ChangeEmailController::class, 'showChangeEmailGet'])->name('changeEmailGet');

Route::post('/changeEmail', [ChangeEmailController::class, 'changeEmailPost'])->name('changeEmailPost');