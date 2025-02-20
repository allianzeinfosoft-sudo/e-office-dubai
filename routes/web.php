<?php

use App\Http\Controllers\BranchController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
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
    Route::get('branch-list',[BranchController::class, 'getBranches']);
});

