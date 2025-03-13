<?php

use App\Http\Controllers\BranchController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use App\Models\Designation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth/login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('roles', RoleController::class);
    Route::get('/user/roles',[RoleController::class, 'getroles']);
    Route::resource('permissions', PermissionController::class);
    Route::resource('users', UserController::class);
    Route::get('/permissions-list', [PermissionController::class, 'getPermissions']);
    Route::get('/roles/{role}/permissions', [RoleController::class, 'getRolePermissions']);
    Route::resource('departments',DepartmentController::class);
    Route::resource('branchs',BranchController::class);
    Route::get('/branches/{branch}/departments', [BranchController::class, 'getDepartments'])
         ->name('branch.departments');
    Route::get('/departments/{department}/designations', [BranchController::class, 'getDesignations'])
         ->name('department.designations');
    Route::post('/department/save',[BranchController::class, 'department_store'])->name('department.store');
    Route::post('/designation/save',[BranchController::class, 'designation_store'])->name('designation.store');
    Route::get('/branch-list',[BranchController::class, 'getBranches']);
    Route::get('/user-list',[UserController::class, 'getUsers']);
    Route::get('/user/profile/{userid}' ,[UserController::class, 'userProfile'])->name('user.profile');
    Route::post('/check-email', [UserController::class, 'checkEmail']);

    Route::get('/settings/workshift',[SettingsController::class, 'list_work_shift'])->name('workshift');
    Route::get('/workshift/list',[SettingsController::class, 'getWorkShift']);
    Route::post('/settings/workshift/save',[SettingsController::class, 'store_work_shift'])->name('store.workshift');
    Route::get('/settings/userstastus',[SettingsController::class, 'list_user_status'])->name('userstatus');

    // leave route
    Route::resource('leaves',LeaveController::class);
});

