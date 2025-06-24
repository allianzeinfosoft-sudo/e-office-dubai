<?php

use App\Http\Controllers\AppreciationController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\FeedsController;
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
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\ThoughtsController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AppearenceController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\MomController;
use App\Http\Controllers\CompanyPolicyController;
use App\Http\Controllers\MailBoxController;
use App\Http\Controllers\CustomAttendanceController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\WorkFromHomeAttendanceController;
use App\Http\Controllers\ConferenceHallController;
use App\Http\Controllers\QuickNoteController;
use App\Http\Controllers\EventCalendarController;
use App\Http\Controllers\KspController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\BookIssueController;

use App\Models\Appearence;
use App\Models\Appreciation;
use App\Models\Designation;
use App\Models\Reminder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Helpers\CustomHelper;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\FeedbackReportController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ParTemplateController;
use App\Http\Controllers\PerformanceAppraisalReportController;
use App\Http\Controllers\SarTemplate;
use App\Http\Controllers\SarTemplateController;
use App\Http\Controllers\SelfAppraisalReportController;
use App\Http\Controllers\SurveyReportController;
use App\Http\Controllers\SurveyTemplateController;
use App\Http\Controllers\TicketRaisingController;
use App\Models\PerformanceAppraisalReport;

Route::middleware(['web'])->group(function () {
    Auth::routes();  // or your custom login routes

    Route::get('/', function () {
        return view('auth.login'); // Just shows login form
    })->name('login');

    Route::post('/logout', function () {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/');
    })->name('logout');




});

Route::get('/password/change', [HomeController::class, 'showChangeForm'])->name('password.change.form')->middleware('auth');
Route::post('/password/change', [HomeController::class, 'change'])->name('password.change')->middleware('auth');

Route::middleware(['web', 'auth','force.password.change'])->group(function () {
    /* Home */
    Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');
    // Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/attendance-analytics', [HomeController::class, 'getAnalytics'])->name('attendance.analytics');
    Route::get('/leave-summary', [HomeController::class, 'getLeaveSummary'])->name('leave.summary');

    /* Attendance */
    Route::get('/attendance',[AttendanceController::class, 'index'])->name('attendance');
    Route::post('/attendance/mark-in',[AttendanceController::class, 'markIn'])->name('attendance.mark-in');
    Route::post('/attendance/mark-out',[AttendanceController::class, 'markOut'])->name('attendance.mark-out');
    Route::post('/attendance/custom-mark-in',[AttendanceController::class, 'customMarkIn'])->name('attendance.custom-mark-in');
    Route::post('/attendance/emergency-mark',[AttendanceController::class, 'emergencyMark'])->name('attendance.emergency-mark');
    Route::post('/attendance/custom-mark-out/{id}',[AttendanceController::class, 'customMarkOut'])->name('attendance.custom-mark-out');
    Route::get('/attendance/marked-in-list',[AttendanceController::class, 'markedInList'])->name('attendance.marked-in-list');
    Route::get('/attendance/emplyee-markin/{id}',[AttendanceController::class, 'employeeMarkin'])->name('attendance.emplyee-markin');
    Route::delete('/attendance/destroy/{id}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');
    Route::post('/attendance/custom-attendance-entry', [AttendanceController::class, 'customAttendanceEntry'])->name('attendance.custom-attendance-entry');
    Route::post('attendance/full-day-attendance-entry', [AttendanceController::class, 'storeFullDayEntry'])->name('attendance.full-day-attendance-entry');
    Route::get('attendance/incomplete-working-hours', [AttendanceController::class, 'getIncompleteWorkingHours'])->name('attendance.incomplete-working-hours');
    Route::get('attendance/get-incomplete-working-hours-report', [AttendanceController::class, 'getIncompleteWorkingHoursReport'])->name('attendance.get-incomplete-working-hours-report');
    Route::get('/attendance/incomplete/approve/{id}', [AttendanceController::class, 'approveIncompleteAttendance'])->name('attendance.incomplete.approve');
    Route::get('/update-brake-time/{id}', [AttendanceController::class, 'update_brake_time'])->name('update-brake-time');

    Route::delete('/attendance/{id}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');

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
    Route::get('/locked-users-list',[UserController::class, 'lockedUsers'])->name('locked.users.view');
    Route::get('/locked-users',[UserController::class, 'locked_index'])->name('locked.users');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::get('/users/{id}/limited-edit', [UserController::class, 'limited_edit'])->name('users.limited.edit');

    Route::post('/users/{id}/limited-update', [UserController::class,'limitedUpdate'])->name('users.limited_update');

    Route::get('/user/profile/{userid}' ,[UserController::class, 'userProfile'])->name('user.profile');
    Route::delete('/user-delete/{userId}', [UserController::class, 'destroy'])->name('user.destroy');
    Route::post('/user-restore/{userId}', [UserController::class, 'restore_user'])->name('user.restore');
    Route::get('/users/{userId}/profile-edit', [UserController::class, 'profileEdit'])->name('users.profile-edit');
    Route::post('/users/store-or-update/{id?}', [UserController::class, 'storeOrUpdate'])->name('users.storeOrUpdate');
    Route::get('/lock-profile/{id}',[UserController::class,'lockProfile'])->name('user.lock_profile');
    Route::post('/change-password',[UserController::class,'change_password'])->name('change_password');
    Route::post('/check-old-password', [UserController::class, 'checkOldPassword'])->name('check_old_password');
    Route::get('/assign_open_work', [UserController::class, 'assign_open_work'])->name('assign_open_work');
    Route::post('/open_work_assign', [UserController::class, 'open_work_assign'])->name('open.work.assign');
    Route::get('/birthday_view', [UserController::class, 'users_birthday'])->name('birthday_view');
    Route::post('/check-email', [UserController::class, 'checkEmail']);
    Route::post('/check-employee-id', [UserController::class, 'checkEmployeeId']);
    Route::post('/check-username', [UserController::class, 'checkUsename']);
    Route::post('/check-aadhar', [UserController::class, 'checkAadhar']);
    Route::post('/check-esi', [UserController::class, 'checkEsi']);
    Route::post('/check-pf', [UserController::class, 'checkPf']);
    Route::post('/check-electoral', [UserController::class, 'checkElectoral']);
    Route::post('/check-pan', [UserController::class, 'checkPAN']);
    Route::post('/check-account-number', [UserController::class, 'checkAccountNumber']);

    Route::get('/blacklist-users', [UserController::class, 'blockedUsers'])->name('blacklist-users.index');
    Route::get('/unblock-users/{id}', [UserController::class, 'unblockUser'])->name('unblock-users.index');
    Route::get('/list-of-latecomers', [UserController::class, 'listOfLatecomers'])->name('list-of-latecomers.index');
    Route::get('/list-of-latecomers-data', [UserController::class, 'lateOfComersData'])->name('list-of-latecomers-data');
    Route::get('/user-latecomers-list', [UserController::class, 'userLateCommers'])->name('user-latecomers-list');
    Route::get('/list-of-incomplete-work', [UserController::class, 'listOfIncompleteWork'])->name('list-of-incomplete-work.index');
    Route::get('/list-of-incomplete-work-data', [UserController::class, 'incompletData'])->name('list-of-incomplete-work-data');

    Route::get('/get-user-details/{userId}', [UserController::class, 'getUserDetails'])->name('get-user-details');
    Route::post('/change-user-password', [UserController::class, 'ChangeUserPassword'])->name('user_change_password');


    /* department */
    Route::resource('departments',DepartmentController::class);
    Route::post('/department/save',[BranchController::class, 'department_store'])->name('department.store');
    Route::post('/designation/create',[DepartmentController::class, 'designation_store'])->name('create.designation');

    /* Branches */
    Route::resource('branchs',BranchController::class);
    Route::get('/branch-list',[BranchController::class, 'getBranches']);
    Route::get('/branches/{branch}/departments', [BranchController::class, 'getDepartments'])->name('branch.departments');

    /* designiations */
    Route::get('/departments/{department}/designations', [BranchController::class, 'getDesignations'])->name('department.designations');
    Route::post('/designation/save',[BranchController::class, 'designation_store'])->name('designation.store');


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
    Route::delete('/leaves/{id}', [LeaveController::class, 'destroy'])->name('leaves.destroy');
    Route::delete('/approver-delete/{id}', [LeaveController::class, 'leave_approval_delete'])->name('approver.destroy');
    Route::get('/custom_leave',[LeaveController::class,'custom_leave'])->name('custom.leave');
    Route::get('/leave_approver/list',[LeaveController::class, 'leave_approver'])->name('leave.approver');
    Route::post('/leave_approval_store',[LeaveController::class, 'leave_approval_store'])->name('leave_approval_store');

    // Route::post('/leave_summary_filter', [LeaveController::class, 'myWorkReportsData'])->name('reports.my-work-report-data');


    /* Prjects */
    Route::get('/projects',[ProjectController::class, 'index'])->name('projects');
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/get-projects', [ProjectController::class, 'getProject'])->name('projects.get-projects');
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
    Route::delete('/productivity-target/{id}', [ProductivityTargetController::class, 'destroy'])->name('productivity-target.destroy');

    /* Work Report */
    Route::post('/work-report/store', [WorkReportController::class, 'store'])->name('work-report.store');
    Route::get('/work-report/{workReport}/edit', [WorkReportController::class, 'edit'])->name('work-report.edit');
    Route::put('/work-report/{workReport}/update', [WorkReportController::class, 'update'])->name('work-report.update');
    Route::delete('/work-report/{workReport}', [WorkReportController::class, 'destroy'])->name('work-report.destroy');
    Route::post('/work-report/custom-workstore', [WorkReportController::class, 'customWorkstore'])->name('work-report.custom-workstore');
    Route::get('/work-report/emerbency-work-report', [WorkReportController::class, 'emergencyWorkReport'])->name('work-report.emerbency-work-report');
    Route::post('/work-report/get-productivity-target', [WorkReportController::class, 'getProductivityTarget'])->name('work-report.get-productivity-target');

    /* Works Module */
    Route::get('works/status',[AttendanceController::class, 'index'])->name('works.status');
    Route::get('works/sud-project-status',[WorksController::class, 'sudProjectStatus'])->name('works.sud-project-status');
    Route::get('works/temporary-status',[WorksController::class, 'temporaryStatus'])->name('works.temporary-status');
    Route::get('works/entry-open',[WorksController::class, 'entryOpen'])->name('works.entry-open');

    /* Holiday */
    Route::resource('holidays',HolidayController::class);
    Route::get('/holiday/list', [HolidayController::class, 'getHolidayList'])->name('holiday.list');
    Route::delete('/holiday-delete/{holidayId}', [HolidayController::class, 'destroy'])->name('holiday.destroy');
    Route::get('/view-holidays',[HolidayController::class, 'show_holiday'])->name('view.holiday');

    /* Notification */
    // Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::get('/notifications', [NotificationController::class, 'getNotifications'])->name('notifications.fetch');
    Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markAsRead']);



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
    Route::get('/recruitments/draft-list', [RecruitmentController::class, 'draftList'])->name('recruitments.draft-list');
    Route::delete('/recruitments/{recruitment}/destroy', [RecruitmentController::class, 'destroy'])->name('recruitments.destroy');
    Route::get('/recruitments/{recruitment}/show', [RecruitmentController::class, 'show'])->name('recruitments.show');
    Route::post('/recruitments/update-status', [RecruitmentController::class, 'updateStatus'])->name('recruitments.update-status');
    Route::post('/recruitments/update-status', [RecruitmentController::class, 'updateStatus'])->name('recruitments.update-status');
    Route::get('/recruitments/rrf_approvals', [RecruitmentController::class, 'rrf_approvals'])->name('recruitments.rrf-approvals');
    Route::get('/recruitments/rrf-approve/{rrfTd}', [RecruitmentController::class, 'rrf_approve'])->name('recruitments.rrf-approve');
    Route::post('/recruitments/reject', [RecruitmentController::class, 'reject'])->name('recruitments.reject');

    /*feeds*/
    Route::get('/feeds',[FeedsController::class, 'show_feeds'])->name('show.feeds');

    /*Thoughts*/
    Route::resource('thoughts',ThoughtsController::class);
    Route::get('/thoughts/{thought}/edit', [ThoughtsController::class, 'edit'])->name('thoughts.edit');
    Route::post('/thoughts/{thought}/update', [ThoughtsController::class, 'update'])->name('thoughts.update');
    Route::get('/thoughts_view', [ThoughtsController::class, 'view_thoughts'])->name('thoughts.view');

    /* Views/Company policies */

    Route::get('/view/company-policies', [CompanyPolicyController::class, 'index'])->name('view.company-policies');
    Route::post('/view/company-policies/store', [CompanyPolicyController::class, 'store'])->name('view.company-policies.store');
    Route::get('/view/company-policies/edit/{companyPolicy}', [CompanyPolicyController::class, 'edit'])->name('view.company-policies.edit');
    Route::delete('/view/company-policies/delete/{companyPolicy}', [CompanyPolicyController::class, 'destroy'])->name('view.company-policies.destroy');
    Route::get('/view/company-policies/show/{companyPolicy}', [CompanyPolicyController::class, 'show'])->name('view.company-policies.show');
    Route::post('/view/company-policies/{companyPolicy}/mark-as-read', [CompanyPolicyController::class, 'markAsRead'])->name('view.company-policies.mark-as-read');

    /* Others/Announcements */
    Route::get('/others/announcements', [AnnouncementController::class, 'index'])->name('others.announcements.index');
    Route::post('/others/announcements/store', [AnnouncementController::class, 'store'])->name('others.announcements.store');
    Route::get('/others/announcements/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('others.announcements.edit');
    Route::delete('/others/announcements/{announcement}/destroy', [AnnouncementController::class, 'destroy'])->name('others.announcements.destroy');
    Route::get('/announcement_view', [AnnouncementController::class, 'view_announcement'])->name('announcement_view');

    Route::post('/check-announcement', [AnnouncementController::class, 'checkAnnouncement']);
    Route::post('/mark-announcement-read', [AnnouncementController::class, 'markAsRead']);
    /* Others/Events */
    Route::get('/others/events', [EventController::class, 'index'])->name('others.events.index');
    Route::post('/others/events/store', [EventController::class, 'store'])->name('others.events.store');
    Route::get('/others/events/{event}/edit', [EventController::class, 'edit'])->name('others.events.edit');
    Route::delete('/others/events/{event}/destroy', [EventController::class, 'destroy'])->name('others.events.destroy');

    /* Others/Policies */
    Route::get('/others/policies', [PolicyController::class, 'index'])->name('others.policies.index');
    Route::post('/others/policies/store', [PolicyController::class, 'store'])->name('others.policies.store');
    Route::get('/others/policies/{policy}/edit', [PolicyController::class, 'edit'])->name('others.policies.edit');
    Route::delete('/others/policies/{policy}/destroy', [PolicyController::class, 'destroy'])->name('others.policies.destroy');

    /* Others/MOMs */
    Route::get('/others/moms', [MomController::class, 'index'])->name('others.moms.index');
    Route::post('/others/moms/store', [MomController::class, 'store'])->name('others.moms.store');
    Route::get('/others/moms/{mom}/edit', [MomController::class, 'edit'])->name('others.moms.edit');
    Route::delete('/others/moms/{mom}/destroy', [MomController::class, 'destroy'])->name('others.moms.destroy');
    Route::get('/others/moms/{mom}/show', [MomController::class, 'show'])->name('others.moms.show');
    Route::post('/others/moms/{mom}/mark-as-read', [MomController::class, 'markAsRead'])->name('others.moms.mark-as-read');

    Route::get('/view/moms', [MomController::class, 'userWiseMoms'])->name('view.moms');


    /*Appreciation*/
    Route::resource('appreciation', AppreciationController::class);
    Route::get('/appreciation_view',[AppreciationController::class, 'view_appreciation'])->name('view_appreciation');
    /* Mailbox */

    // Route::get('/mail-box', [MailBoxController::class, 'index'])->name('mailbox.index');
    Route::resource('mail-boxes', MailBoxController::class);
    Route::get('/mail-boxes/folder/{folder}', [MailBoxController::class, 'folder']);
    Route::get('/mail-boxes/starred', [MailBoxController::class, 'starred']);
    Route::get('/mail-boxes/{mailBox}/show', [MailBoxController::class, 'show'])->name('mail-boxes.show');
    Route::post('/mail-boxes/mark-as-starred', [MailBoxController::class, 'markAsStarred'])->name('mail-boxes.mark-as-starred');
    Route::post('/mail-boxes/move-to-folder', [MailBoxController::class, 'moveToFolder'])->name('mail-boxes.move-to-folder');
    Route::post('/mail-boxes/mark-as-read', [MailBoxController::class, 'markAsRead'])->name('mail-boxes.mark-as-read');
    Route::post('/mail-boxes/mark-read', [MailBoxController::class, 'markRead'])->name('mail-boxes.mark-read');
    Route::post('/mail-boxes/destroy', [MailBoxController::class, 'destroy'])->name('mail-boxes.destroy');

    /* Banner */
    Route::resource('banner', BannerController::class);

    /* Reminder */
    Route::resource('reminder',ReminderController::class);

    /* Settings */
    Route::get('/settings/userstastus',[SettingsController::class, 'list_user_status'])->name('userstatus');
    Route::get('/settings/workshift',[SettingsController::class, 'list_work_shift'])->name('workshift');
    Route::post('/settings/workshift/save',[SettingsController::class, 'store_work_shift'])->name('store.workshift');
    Route::get('/workshift/list',[SettingsController::class, 'getWorkShift']);
    Route::get('/settings/custom-mark-out', [SettingsController::class, 'customMakeOut'])->name('settings.custom-mark-out');
    Route::get('/settings/custom-attendance-entry', [SettingsController::class, 'customAttendanceEntry'])->name('settings.custom-attendance-entry');
    Route::delete('/workshift/delete/{workshiftId}', [SettingsController::class, 'delete_work_shift'])->name('workshift.destroy');
    Route::get('/workshift/{targetId}/edit',[SettingsController::class, 'edit_work_shift'])->name('workshift.edit');
    Route::post('/change-shift-time',[SettingsController::class, 'change_shift'])->name('change_shift');
    Route::get('/shift-times',[SettingsController::class, 'userShifts'])->name('users.shifts');
    Route::post('/update-user-shift', [SettingsController::class, 'update_user_shift'])->name('update.user.shift');
    Route::post('/save-login-limited-time',[SettingsController::class,'store_login_limited_time'])->name('save.login_limited_time');
    Route::get('/settings/full-day-attendance-entry', [SettingsController::class, 'fullDayAttendanceEntry'])->name('settings.full-day-attendance-entry');
    Route::get('/settings/custom-work-report-entry', [SettingsController::class, 'customWorkReportEntry'])->name('settings.custom-work-report-entry');
    Route::get('/settings/edit-daily-attendance', [SettingsController::class, 'editDailyAttendance'])->name('settings.edit-daily-attendance');
    Route::get('/settings/get-attendance-data', [SettingsController::class, 'getAttendanceData'])->name('settings.get-attendance-data');
    Route::post('/settings/update-attendance-data/{id}', [SettingsController::class, 'updateAttendance'])->name('settings.update-attendance-data');
    Route::get('/get-user-shifts/{userId}', [SettingsController::class, 'getUserShifts']);
    Route::get('/settings/get-working-hours', [SettingsController::class, 'getWorkingHours']) ->name('settings.get-working-hours');

    /* shifts */

    /* Appearence */
    Route::resource('appearences', AppearenceController::class);
    Route::post('/background-images/select', [AppearenceController::class, 'Bg_select'])->name('background-images.select');

    /* Custom Attendance */
    Route::get('/custom-attendance', [CustomAttendanceController::class, 'index'])->name('custom-attendance.index');
    Route::get('/custom-attendance/accept-custom-mark-in/{id}', [CustomAttendanceController::class, 'acceptCustomMarkIn'])->name('custom-attendance.accept-custom-mark-in');
    Route::get('/custom-attendance/reject-custom-mark-in/{id}', [CustomAttendanceController::class, 'rejectCustomMarkIn'])->name('custom-attendance.reject-custom-mark-in');

    /* Reports  */
    Route::get('/reports/my-overview', [ReportController::class, 'my_overview'])->name('reports.my-overview');
    Route::get('/reports/my-attendance-report', [ReportController::class, 'myAttendanceReport'])->name('reports.my-attendance-report');
    Route::get('/reports/my-work-report', [ReportController::class, 'myWorkReport'])->name('reports.my-work-report');
    Route::get('/reports/user-work-report/{id}', [ReportController::class, 'userWorkReport'])->name('reports.user-work-report');
    Route::post('/reports/my-work-report-data', [ReportController::class, 'myWorkReportsData'])->name('reports.my-work-report-data');

    Route::get('/reports/user-overview', [ReportController::class, 'user_overview'])->name('reports.user-overview');
    Route::get('/reports/user-monthly-overview', [ReportController::class, 'monthlyOverview'])->name('reports.user-monthly-overview');
    Route::get('/reports/user-monthly-overview-data', [ReportController::class, 'monthlyOverviewReport'])->name('reports.user-monthly-overview-data');
    Route::get('/reports/daily-attendance-report', [ReportController::class, 'dailyAttendanceReport'])->name('reports.daily-attendance-report');
    Route::get('/reports/daily-attendance', [ReportController::class, 'dailyAttendanceData'])->name('reports.daily-attendance');
    Route::get('/reports/leave-report', [ReportController::class, 'leaveReport'])->name('reports.leave-report');
    Route::get('/reports/leave-report-data', [ReportController::class, 'leaveReportData'])->name('reports.leave-report-data');
    Route::get('/reports/all-attendance-report', [ReportController::class, 'allAttendanceReport'])->name('reports.all-attendance-report');
    Route::post('/reports/all-attendance-data', [ReportController::class, 'allAttendanceData'])->name('reports.all-attendance-data');
    Route::get('/reports/all-work-report', [ReportController::class, 'allWorkReport'])->name('reports.all-work-report');
    Route::post('/reports/all-work-report', [ReportController::class, 'allWorkReportData'])->name('reports.all-work-report');
    Route::get('/reports/over-all-work-report', [ReportController::class, 'overAllWorkReport'])->name('reports.over-all-work-report');
    Route::get('/reports/get-projects-by-employee', [ReportController::class, 'getProjectsByEmployee'])->name('reports.get-projects-by-employee');
    Route::post('/reports/get-employee-reports', [ReportController::class, 'getFilteredReports']) ->name('reports.get-employee-reports');
    Route::get('/reports/emergency-reports', [ReportController::class, 'emergencyAttendanceReport']) ->name('reports.emergency-reports');
    Route::post('/reports/get-emergency-attendance', [ReportController::class, 'getEmergencyAttendance']) ->name('reports.get-emergency-attendance');
    Route::post('/check-leave-overlap', [LeaveController::class, 'checkOverlap'])->name('check.leave.overlap');
    Route::post('/leave_summary_filter', [LeaveController::class, 'leave_summary_filter'])->name('leave_summary_filter');
    Route::get('/check-leave-allocation/{user}', [LeaveController::class, 'check_leave_allocated']);

    /*Galley*/
    Route::resource('gallery', GalleryController::class);
    Route::post('/gallery/upload-image', [GalleryController::class, 'uploadImage'])->name('gallery.upload.image');
    Route::get('/gallery/{id}', [GalleryController::class, 'show'])->name('gallery.show');
    Route::delete('/gallery/{gallery}/image', [GalleryController::class, 'deleteImage'])->name('gallery.image.delete');

    /* workfrom home */
    Route::post('/work-from-home-attendance/store', [WorkFromHomeAttendanceController::class, 'store'])->name('work-from-home-attendance.store');
    Route::get('/wfs-wfh-approval-list', [WorkFromHomeAttendanceController::class, 'get_wfs_wfh_approval_list'])->name('wfs-wfh-approval-list');
    Route::get('/wfs-wfh-approve/{id}', [WorkFromHomeAttendanceController::class, 'approval_wfs_wfh'])->name('wfs-wfh-approve');
    Route::get('/wfs-wfh-reject/{id}', [WorkFromHomeAttendanceController::class, 'reject_wfs_wfh'])->name('wfs-wfh-reject');

    /* conference hall */
    Route::get('/conferance-hall', [ConferenceHallController::class, 'index'])->name('conferance-hall.index');
    Route::post('/conferance-hall/store', [ConferenceHallController::class, 'store'])->name('conferance-hall.store');
    Route::get('/conferance-hall/{conferenceHall}/edit', [ConferenceHallController::class, 'edit'])->name('conferance-hall.edit');
    Route::delete('/conferance-hall/{conferenceHall}/distroy', [ConferenceHallController::class, 'destroy'])->name('conferance-hall.destroy');
    Route::get('/conferance-hall/report', [ConferenceHallController::class, 'conferanceHallReport'])->name('conferance-hall.report');
    Route::get('/conferance-hall/report-data', [ConferenceHallController::class, 'conferanceHallReportData'])->name('conferance-hall.report-data');

    /* Tools - Quick Note */
    Route::get('/tools/quick-note', [QuickNoteController::class, 'index'])->name('tools.quick-note.index');
    Route::post('/tools/quick-note/store', [QuickNoteController::class, 'store'])->name('tools.quick-note.store');
    Route::get('/tools/quick-note/{quickNote}/edit', [QuickNoteController::class, 'edit'])->name('tools.quick-note.edit');
    Route::get('/tools/quick-note/{id}/destroy', [QuickNoteController::class, 'destroy'])->name('tools.quick-note.destroy');
    Route::get('/tools/quick-note/{quickNote}/show', [QuickNoteController::class, 'show'])->name('tools.quick-note.show');
    Route::post('/tools/quick-note/comment', [QuickNoteController::class, 'storeComment'])->name('quick-note.comment.store');

    /* Tools - Quick Note */
    Route::get('/tools/event-calendar', [EventCalendarController::class, 'index'])->name('tools.event-calendar.index');
    Route::post('/tools/event-calendar/store', [EventCalendarController::class, 'store'])->name('tools.event-calendar.store');
    Route::delete('/tools/event-calendar/delete/{type}/{id}', [EventCalendarController::class, 'destroy'])->name('tools.event-calendar.delete');

    /* Tools - KSP */
    Route::get('/tools/ksp', [KspController::class, 'index'])->name('tools.ksp.index');
    Route::post('/tools/ksp/store', [KspController::class, 'store'])->name('tools.ksp.store');
    Route::get('/tools/ksp/{ksp}/edit', [KspController::class, 'edit'])->name('tools.ksp.edit');
    Route::delete('/tools/ksp/{ksp}/destroy', [KspController::class, 'destroy'])->name('tools.ksp.destroy');
    Route::get('/tools/ksp/{ksp}/show', [KspController::class, 'show'])->name('tools.ksp.show');
    Route::post('/tools/ksp/store-category', [KspController::class, 'store_category'])->name('tools.ksp.store-category');

    /* Library */
    Route::get('/e-library/categories', [LibraryController::class, 'books_categories'])->name('e-library.categories');
    Route::post('/e-library/categories/store', [LibraryController::class, 'store_category'])->name('e-library.categories.store');
    Route::get('/e-library/categories/edit/{id}', [LibraryController::class, 'edit_category'])->name('e-library.categories.edit');
    Route::delete('/e-library/categories/category-destroy/{id}', [LibraryController::class, 'delete_category'])->name('e-library.categories.destroy');


    Route::get('/e-library/books', [LibraryController::class, 'books'])->name('e-library.books');
    Route::get('/e-library/books/edit/{id}', [LibraryController::class, 'edit_book'])->name('e-library.edit');
    Route::post('/e-library/book/save', [LibraryController::class, 'save_book'])->name('e-library.book.save');
    Route::delete('/e-library/books/book-destroy/{id}', [LibraryController::class, 'book_destroy'])->name('e-library.book-destroy');

    Route::get('/e-library/reports/issue', [LibraryController::class, 'issueReport'])->name('e-library.reports.issue');
    Route::get('/e-library/reports/issue-report-data', [LibraryController::class, 'issueReportData'])->name('e-library.reports.issue-report-data');
    Route::get('/e-library/reports/pending', [LibraryController::class, 'pendingReport'])->name('e-library.reports.pending');
    Route::get('/e-library/reports/pending-report-data', [LibraryController::class, 'pendingReportData'])->name('e-library.reports.pending-report-data');
    Route::get('/e-library/reports/damaged-lost', [LibraryController::class, 'damagedLostReport'])->name('e-library.reports.damaged_lost');
    Route::get('e-library/reports/damaged-lost-report-data', [LibraryController::class, 'damagedLostReportData'])->name('e-library.reports.damaged-lost-report-data');

    /* Book Issue */
    Route::get('/e-library/issue-register', [BookIssueController::class, 'index'])->name('e-library.book-issues.index');
    Route::get('/e-library/issue-book', [BookIssueController::class, 'create'])->name('e-library.book-issues.create');
    Route::post('/e-library/issue-book', [BookIssueController::class, 'store'])->name('e-library.book-issues.store');
    Route::post('/e-library/return-book/{id}', [BookIssueController::class, 'return'])->name('e-library.book-issues.return');
    Route::delete('/e-library/book-issues/{id}', [BookIssueController::class, 'destroy'])->name('e-library.book-issues.destroy');



    /* Mail Testing Route */
    Route::get('/test-mail', function () {
        $html = view('emails.notification', [
            'name' => 'Tester',
            'message' => 'This is a test'
        ])->render();

        $to = 'vinayak@mail.allianzegroup.com';
        $subject = 'Test Mail';

        return CustomHelper::sendNotificationMail($to, $subject, $html)
            ? 'Mail sent successfully!'
            : 'Mail sending failed!';
    });


    //SAR
    Route::resource('sartemplate', SarTemplateController::class);
    Route::get('/sartemplate/{id}/fetch', [SarTemplateController::class, 'fetch']);
    Route::get('/sartemplate-assign', [SarTemplateController::class, 'assign_template'])->name('sar.user.assign');
    Route::post('/store-sar-assign',[SarTemplateController::class, 'store_assign_template'])->name('store.sar.assign');

    Route::get('/branches/{branch}/sar_templates', [SarTemplateController::class, 'getTemplates'])->name('branch.sar_emplates');
    Route::get('/sar-assign-edit', [SarTemplateController::class, 'sar_edit'])->name('sar_assigning.edit');
    Route::delete('/sar_assign/{id}', [SarTemplateController::class, 'destroyAssign'])->name('sar.assign.delete');

    Route::get('/user-sars',[SarTemplateController::class, 'user_sars'])->name('user.sars');
    Route::get('/usersars/{id}/sarfetch', [SarTemplateController::class, 'sarsfetch']);
    Route::get('/usersars/{id}/saranswerfetch', [SarTemplateController::class, 'sarAnswerfetch']);
    Route::resource('self-appraisal', SelfAppraisalReportController::class);

    Route::get('/usersars/{id}/generate-pdf', [SarTemplateController::class, 'generatePdf']);
    Route::delete('/sartemplate/{sarTemplate}', [SarTemplateController::class, 'destroy'])->name('sartemplate.destroy');


    //PAR
    Route::resource('partemplate', ParTemplateController::class);
    Route::get('/partemplate/{id}/fetch', [ParTemplateController::class, 'fetch']);
    Route::get('/partemplate-assign', [ParTemplateController::class, 'assign_template'])->name('par.user.assign');
    Route::post('/store-par-assign',[ParTemplateController::class, 'store_assign_template'])->name('store.par.assign');

    Route::get('/branches/{branch}/par_templates', [ParTemplateController::class, 'getTemplates'])->name('branch.par_templates');
    Route::get('/par-assign-edit', [ParTemplateController::class, 'par_edit'])->name('par_assigning.edit');
    Route::delete('/par_assign/{id}', [ParTemplateController::class, 'destroyAssign'])->name('par.assign.delete');

    Route::get('/user-pars',[ParTemplateController::class, 'user_pars'])->name('user.pars');
    Route::get('/userpars/{id}/parfetch', [ParTemplateController::class, 'parsfetch']);
    Route::get('/userpars/{id}/paranswerfetch', [ParTemplateController::class, 'parAnswerfetch']);
    Route::resource('performance-appraisal', PerformanceAppraisalReportController::class);

    Route::get('/userpars/{id}/generate-pdf', [ParTemplateController::class, 'generatePdf']);
    Route::delete('/partemplate/{parTemplate}', [ParTemplateController::class, 'destroy'])->name('partemplate.destroy');



    //SURVEY
    Route::resource('surveytemplate', SurveyTemplateController::class);
    Route::get('/surveytemplate/{id}/fetch', [SurveyTemplateController::class, 'fetch']);
    Route::get('/surveytemplate-assign', [SurveyTemplateController::class, 'assign_template'])->name('survey.user.assign');
    Route::post('/store-survey-assign',[SurveyTemplateController::class, 'store_assign_template'])->name('store.survey.assign');

    Route::get('/branches/{branch}/survey_templates', [SurveyTemplateController::class, 'getTemplates'])->name('branch.survey_templates');
    Route::get('/survey-assign-edit', [SurveyTemplateController::class, 'survey_edit'])->name('survey_assigning.edit');
    Route::delete('/survey_assign/{id}', [SurveyTemplateController::class, 'destroyAssign'])->name('survey.assign.delete');

    Route::get('/user-surveys',[SurveyTemplateController::class, 'user_surveys'])->name('user.surveys');
    Route::get('/usersurveys/{id}/surveyfetch', [SurveyTemplateController::class, 'surveysfetch']);
    Route::get('/usersurveys/{id}/surveyanswerfetch', [SurveyTemplateController::class, 'surveyAnswerfetch']);
    Route::resource('survey', SurveyReportController::class);

    Route::get('/usersurveys/{id}/generate-pdf', [SurveyTemplateController::class, 'generatePdf']);


      //FEEDBACK
    Route::resource('feedback', FeedbackController::class);
    Route::get('/feedback/{id}/fetch', [FeedbackController::class, 'fetch']);
    Route::get('/feedback-assign', [FeedbackController::class, 'assign_feedback'])->name('feedback.user.assign');
    Route::post('/store-feedback-assign',[FeedbackController::class, 'store_assign_feedback'])->name('store.feedback.assign');

    Route::get('/branches/{branch}/feedbacks', [FeedbackController::class, 'getFeedbacks'])->name('branch.feedback');
    Route::get('/feedback-assign-edit', [FeedbackController::class, 'feedback_edit'])->name('feedback_assigning.edit');
    Route::delete('/feedback_assign/{id}', [FeedbackController::class, 'destroyAssign'])->name('feedback.assign.delete');

    Route::get('/user-feedbacks',[FeedbackController::class, 'user_feedbacks'])->name('user.feedbacks');
    Route::get('/userfeedbacks/{id}/feedbackfetch', [FeedbackController::class, 'feedbackfetch']);
    Route::get('/userfeedbacks/{id}/feedbackanswerfetch', [FeedbackController::class, 'feedbackAnswerfetch']);
    Route::resource('feedback_report', FeedbackReportController::class);

    Route::get('/userfeedbacks/{id}/generate-pdf', [FeedbackController::class, 'generatePdf']);
    Route::delete('/feedbacktemplate/{feedbackTemplate}', [FeedbackController::class, 'destroy'])->name('feedback.destroy');

    Route::get('/feedback-report',[FeedbackReportController::class, 'feedback_report'])->name('feedback.report.list');

    Route::get('/feedbacks/{id}/export', [FeedbackReportController::class, 'exportFeedbackReport'])->name('feedback.export');

    /* Jobs */
    Route::get('/jobs', [JobController::class, 'index'])->name('job.index');
    Route::post('/job/store', [JobController::class, 'store'])->name('job.store');
    Route::get('/job/{job}/edit', [JobController::class, 'edit'])->name('job.edit');
    Route::get('/job/{id}/destroy', [JobController::class, 'destroy'])->name('jobs.destroy');
    Route::get('/job/{job}/show', [JobController::class, 'show'])->name('job.show');
    Route::post('/job/comment', [JobController::class, 'storeComment'])->name('job.comment.store');

     /*Ticket Raising*/
    Route::resource('tickets',TicketRaisingController::class);
    Route::get('/tickets/{ticket}/edit', [TicketRaisingController::class, 'edit'])->name('tickets.edit');
    Route::post('/tickets/{ticket}/update', [TicketRaisingController::class, 'update'])->name('tickets.update');
    Route::get('/tickets_view', [TicketRaisingController::class, 'view_tickets'])->name('tickets.view');

    Route::post('/tickets/{id}/close', [TicketRaisingController::class, 'close'])->name('tickets.close');


});
