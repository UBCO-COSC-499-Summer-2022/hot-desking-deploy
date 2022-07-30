<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingsManagerController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\CampusController;
use App\Http\Controllers\FloorController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\DeskController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RolesManagerController;
use App\Http\Controllers\PolicyManagerController;
use App\Http\Controllers\ResourceManagerController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UsageStatisticsController;
use App\Http\Controllers\UserManagerController;
use App\Models\Buildings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
});

Auth::routes(['verify' => true]);

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

Route::resource('/desk', DeskController::class);
Route::post('/deskStore', [DeskController::class, 'store'])->name('deskStore');
Route::post('/deskUpdate/{id}', [DeskController::class, 'update'])->name('deskUpdate');

Route::resource('/user', UserController::class);
Route::post('/userStore', [UserController::class, 'store'])->name('userStore');
Route::post('/userUpdate/{id}', [UserController::class, 'update'])->name('userUpdate');


Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/campusManager', [ResourceManagerController::class, 'index'])->name('campusManager');
Route::get('/addCampus', [ResourceManagerController::class, 'addCampus'])->name('addCampus');
Route::get('/editCampus/{id}', [ResourceManagerController::class, 'editCampus'])->name('editCampus');


Route::get('/buildingManager/{campus_id}', [ResourceManagerController::class, 'buildingManager'])->name('buildingManager');
Route::get('/addBuilding/{campus_id}', [ResourceManagerController::class, 'addBuilding'])->name('addBuilding');
Route::get('/editBuilding/{id}', [ResourceManagerController::class, 'editBuilding'])->name('editBuilding');


Route::get('/floorManager/{building_id}', [ResourceManagerController::class, 'floorManager'])->name('floorManager');
Route::get('/addFloor/{building_id}', [ResourceManagerController::class, 'addFloor'])->name('addFloor');
Route::get('/editFloor/{id}', [ResourceManagerController::class, 'editFloor'])->name('editFloor');


Route::get('/roomManager/{floor_id}', [ResourceManagerController::class, 'roomManager'])->name('roomManager');
Route::get('/addRoom/{floor_id}', [ResourceManagerController::class, 'addRoom'])->name('addRoom');
Route::get('/editRoom/{id}', [ResourceManagerController::class, 'editRoom'])->name('editRoom');


Route::get('/deskManager/{room_id}', [ResourceManagerController::class, 'deskManager'])->name('deskManager');
Route::get('/addDesk/{room_id}', [ResourceManagerController::class, 'addDesk'])->name('addDesk');
Route::get('/editDesk/{id}', [ResourceManagerController::class, 'editDesk'])->name('editDesk');


Route::get('/bookingsManager', [BookingsManagerController::class, 'index'])->name('bookingsManager');
Route::get('/viewBooking/{id}', [BookingsManagerController::class, 'viewBooking'])->name('viewBooking');
Route::get('/editBooking/{id}', [BookingsManagerController::class, 'editBooking'])->name('editBooking');
Route::get('/createBooking', [BookingsManagerController::class, 'createBooking'])->name('createBooking');

Route::resource('/booking', BookingController::class);
Route::post('/bookingUpdate/{id}', [BookingController::class, 'update'])->name('bookingUpdate');

Route::get('/policyManager', [PolicyManagerController::class, 'index'])->name('policyManager');
Route::get('/createPolicy', [PolicyManagerController::class, 'createPolicy'])->name('createPolicy');
Route::get('/viewPolicy', [PolicyManagerController::class, 'viewPolicy'])->name('viewPolicy');
Route::get('/editPolicy', [PolicyManagerController::class, 'editPolicy'])->name('editPolicy');

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
