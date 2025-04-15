<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectTaskController;
use App\Http\Controllers\WorkReportController;
use App\Http\Controllers\WorksController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProductivityTargetController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\RecruitmentController;
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
Route::middleware(['web'])->group(function () {
    Route::get('/', function () { return view('auth/login');});

    Auth::routes();

    Route::post('/logout', function () {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/');
    })->name('logout');

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    /* Attendance */
    Route::get('/attendance',[AttendanceController::class, 'index'])->name('attendance');
    Route::post('/attendance/mark-in',[AttendanceController::class, 'markIn'])->name('attendance.mark-in');
    Route::post('/attendance/mark-out',[AttendanceController::class, 'markOut'])->name('attendance.mark-out');
    Route::post('/attendance/custom-mark-in',[AttendanceController::class, 'customMarkIn'])->name('attendance.custom-mark-in');
    Route::post('/attendance/emergency-mark',[AttendanceController::class, 'emergencyMark'])->name('attendance.emergency-mark');
    Route::post('/attendance/custom-mark-out/{id}',[AttendanceController::class, 'customMarkOut'])->name('attendance.custom-mark-out');

    /* roles */
    Route::resource('roles', RoleController::class);
    Route::get('/user/roles',[RoleController::class, 'getroles']);
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
    Route::get('/roles/{role}/permissions', [RoleController::class, 'getRolePermissions']);

    /* Permissions */
    Route::resource('permissions', PermissionController::class);
    Route::delete('/permissions/{id}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
    Route::get('/permissions-list', [PermissionController::class, 'getPermissions']);

    /* users */
    Route::resource('users', UserController::class);
    Route::get('/user-list',[UserController::class, 'getUsers']);
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::get('/user/profile/{userid}' ,[UserController::class, 'userProfile'])->name('user.profile');
    Route::get('/settings/userstastus',[SettingsController::class, 'list_user_status'])->name('userstatus');
    Route::post('/check-email', [UserController::class, 'checkEmail']);
    Route::delete('/user-delete/{userId}', [UserController::class, 'destroy'])->name('user.destroy');
    Route::get('/users/{userId}/profile-edit', [UserController::class, 'profileEdit'])->name('users.profile-edit');
    Route::post('/users/store-or-update/{id?}', [UserController::class, 'storeOrUpdate'])->name('users.storeOrUpdate');

    /* department */
    Route::resource('departments',DepartmentController::class);
    Route::post('/department/save',[BranchController::class, 'department_store'])->name('department.store');

    /* Branches */
    Route::resource('branchs',BranchController::class);
    Route::get('/branch-list',[BranchController::class, 'getBranches']);
    Route::get('/branches/{branch}/departments', [BranchController::class, 'getDepartments'])->name('branch.departments');

    /* designiations */
    Route::get('/departments/{department}/designations', [BranchController::class, 'getDesignations'])->name('department.designations');
    Route::post('/designation/save',[BranchController::class, 'designation_store'])->name('designation.store');

    /* shifts */
    Route::get('/settings/workshift',[SettingsController::class, 'list_work_shift'])->name('workshift');
    Route::get('/workshift/list',[SettingsController::class, 'getWorkShift']);
    Route::post('/settings/workshift/save',[SettingsController::class, 'store_work_shift'])->name('store.workshift');

    // leave route
    Route::resource('leaves',LeaveController::class);
    Route::get('/leave-list',[LeaveController::class, 'leave_list']);
    Route::get('leave-status',[LeaveController::class,'show_leave_status'])->name('leaves.status');
    Route::get('/leave-status/{user_id}',[LeaveController::class, 'leave_status'])->name('leave.status');
    Route::get('/pending-leaves',[LeaveController::class,'leave_pending_show'])->name('leaves.pending.show');
    Route::get('/leave-pending',[LeaveController::class, 'pending_leaves'])->name('leaves.pending');
    Route::post('leave/action', [LeaveController::class, 'leave_action'])->name('leaves.leave_action');
    Route::get('/leave-allocation',[LeaveController::class,'leave_allocation'])->name('leaves.allocation');
    Route::get('/allocated-leaves', [LeaveController::class, 'allocated_leaves'])->name('leaves.allocated_leaves');
    Route::post('/leave_allocate',[LeaveController::class,'leave_allocate'])->name('leaves.leave_allocate');
    Route::post('/check-leave' ,[LeaveController::class, 'checkLeave'])->name('check.leave');
    Route::post('/get-leave-details',[LeaveController::class, 'getLeaveDetails'])->name('leave.leave.details');
    Route::post('/update-leave-allocation',[LeaveController::class, 'updateLeaveAllocation'])->name('leave.update_leave_allocation');

    /* Prjects */
    Route::get('/projects',[ProjectController::class, 'index'])->name('projects');
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/project/create', [ProjectController::class, 'create'])->name('project.create');
    Route::post('/project/store', [ProjectController::class, 'store'])->name('project.store');
    Route::get('/project/{project}/edit', [ProjectController::class, 'edit'])->name('project.edit');
    Route::post('/project/{project}/update', [ProjectController::class, 'update'])->name('project.update');
    Route::delete('/project/{projectId}/destroy', [ProjectController::class, 'destroy'])->name('projects.destroy');

    /* Project Tasks */
    Route::get('/tasks-project', [ProjectTaskController::class, 'index'])->name('tasks-project.index');
    Route::get('/tasks-project/create', [ProjectTaskController::class, 'create'])->name('tasks-project.create');
    Route::post('/tasks-project/store', [ProjectTaskController::class, 'store'])->name('tasks-project.store');
    Route::get('/tasks-project/{projectTask}/edit', [ProjectTaskController::class, 'edit'])->name('tasks-project.edit');
    Route::post('/tasks-project/{projectTask}/update', [ProjectTaskController::class, 'update'])->name('tasks-project.update');
    Route::delete('/tasks-project/{projectTask}/destroy', [ProjectTaskController::class, 'destroy'])->name('tasks-project.destroy');
    Route::get('/tasks-project/{project_id}/get-tasks-by-project', [ProjectTaskController::class, 'getTasksByProject'])->name('tasks-project.get-tasks-by-project');
    Route::get('/tasks-project/{employee_id}/get-members', [ProjectTaskController::class, 'getMembers'])->name('tasks-project.get-members');
    Route::post('/tasks-project/store-task-name', [ProjectTaskController::class, 'storeTaskName'])->name('tasks-project.store-task-name');
    
    /* productivity Target */
    Route::get('/productivity-target', [ProductivityTargetController::class, 'index'])->name('productivity-target.index');
    Route::post('/productivity-target/store', [ProductivityTargetController::class, 'store'])->name('productivity-target.store');
    Route::get('/productivity-target/{ProductivityTarget}/edit', [ProductivityTargetController::class, 'edit'])->name('productivity-target.edit');
    
    
    /* Work Report */
    Route::post('/work-report/store', [WorkReportController::class, 'store'])->name('work-report.store');
    Route::get('/work-report/{workReport}/edit', [WorkReportController::class, 'edit'])->name('work-report.edit');
    Route::put('/work-report/{workReport}/update', [WorkReportController::class, 'update'])->name('work-report.update');
    Route::delete('/work-report/{workReport}', [WorkReportController::class, 'destroy'])->name('work-report.destroy');
    
    /* Works Module */
    Route::get('works/status',[AttendanceController::class, 'index'])->name('works.status');
    Route::get('works/sud-project-status',[WorksController::class, 'sudProjectStatus'])->name('works.sud-project-status');
    Route::get('works/temporary-status',[WorksController::class, 'temporaryStatus'])->name('works.temporary-status');
    Route::get('works/entry-open',[WorksController::class, 'entryOpen'])->name('works.entry-open');
    
    /* Holiday */
    Route::resource('holidays',HolidayController::class);
    Route::get('/holiday/list', [HolidayController::class, 'getHolidayList'])->name('holiday.list');
    Route::delete('/holiday-delete/{holidayId}', [HolidayController::class, 'destroy'])->name('holiday.destroy');
    
    /* Notification */
    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    
    /*salary*/
    Route::get('/salarySlip/view',[SalaryController::class, 'view_salary_slip'])->name('view.salary.slip');
    Route::get('/fetch/salarySlip',[SalaryController::class,'fetch_salary_slip'])->name('fetch.salarySlip');
    Route::post('/salary/upload', [SalaryController::class, 'upload'])->name('upload.salary.file');
    
    /* Recruitments */
    Route::get('/recruitments', [RecruitmentController::class, 'index'])->name('recruitments.index');
    Route::post('/recruitments/load-modal-form', [RecruitmentController::class, 'loadModalFrom'])->name('recruitments.load-modal-form');
    Route::post('/recruitments/load-modal-form', [RecruitmentController::class, 'loadModalFrom'])->name('recruitments.load-modal-form');
    Route::post('/recruitments/store-graduation', [RecruitmentController::class, 'storeGraduation'])->name('recruitments.store-graduation');
    Route::post('/recruitments/store-mini-qualification', [RecruitmentController::class, 'storeMinimumQualification'])->name('recruitments.store-mini-qualification');
    Route::post('/recruitments/store-position', [RecruitmentController::class, 'storePosition'])->name('recruitments.store-position');
    Route::post('/recruitments/store-project', [RecruitmentController::class, 'storeProject'])->name('recruitments.store-project');
    Route::post('/recruitments/store-skills', [RecruitmentController::class, 'storeSkills'])->name('recruitments.store-skills');
    Route::post('/recruitments/store-keywords', [RecruitmentController::class, 'storeKeywords'])->name('recruitments.store-keywords');
    Route::post('/recruitments/store', [RecruitmentController::class, 'store'])->name('recruitments.store');
    Route::get('/recruitments/{recruitment}/edit', [RecruitmentController::class, 'edit'])->name('recruitments.edit');
    Route::delete('/recruitments/{recruitment}/destroy', [RecruitmentController::class, 'destroy'])->name('recruitments.destroy');
    Route::get('/recruitments/draft-list', [RecruitmentController::class, 'draftList'])->name('recruitments.draft-list');
    Route::get('/recruitments/{recruitment}/show', [RecruitmentController::class, 'show'])->name('recruitments.show');
    Route::post('/recruitments/update-status', [RecruitmentController::class, 'updateStatus'])->name('recruitments.update-status'); 
});

