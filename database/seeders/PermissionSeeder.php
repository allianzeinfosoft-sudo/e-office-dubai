<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            'dashboard',
            'attendance',
            'works',
            'workStatus',
            'sduProjectStatus',
            'temporaryStatus',
            'openEntry',
            'projects',
            'projectTask',
            'productivityTarget',
            'users',
            'userCreate',
            'reports',
            'myOverview',
            'myAttendance',
            'myWorkReport',
            'myEmergencyReport',
            'mySalarySlip',
            'myOverview',
            'myAttendance',
            'myWorkReport',
            'myEmergencyReport',
            'mySalarySlip',
            'survey',
            'SAR',
            'sarForm',
            'sarReviews',
            'PAR',
            'leave',
            'applyLeave',
            'pendingLeave',
            'approvedLeave',
            'salarySlip',
            'leaveStatus',
            'gallery',
            'views',
            'thoughtOfTheDay',
            'appreciation',
            'birthdays',
            'announcement',
            'companyPolicies',
            'events',
            'userReminderList',
            'conferenceHall',
            'viewBookings',
            'myBookings',
            'assignedBookings',
            'myProjects',
            'myAccounts',
            'myProfile',
            'changePassword',
            'editProfile',
            'tools',
            'quickNotes',
            'eventCalendar',
            'ksp',
            'jobs',
            'myJobs',
            'assignJob',
            'jobsAssignedByYou',
            'email',
            'inbox',
            'starred',
            'sentEmail',
            'trash',
            'settings',
            'workShift',
            'branches',
            'holidays',
            'roles',
            'permissions',
            'departments',
        ];

        $basicPermissions = [
            'view',
            'create',
            'edit',
            'delete',
            'approve'
        ];

        // Create permissions for each module
        foreach ($modules as $module) {
            foreach ($basicPermissions as $action) {
                Permission::firstOrCreate(['name' => "$action $module"]);
            }
        }

        // Additional standalone permissions
        $standalonePermissions = [
            /* 'view reports',
            'edit users',
            'delete users',
            'access HR data', */
        ];

        foreach ($standalonePermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to roles
        $developer = Role::firstOrCreate(['name' => 'Developer']);
        $developer->syncPermissions(Permission::all()); // Full access

        Role::firstOrCreate(['name' => 'G1'])->syncPermissions([
            'view dashboard',
            'view attendance',
            'view works',
            'edit works',
            'view projects',
            'edit projects',
            'view users', 
            'edit users',
            'delete users',
            'view reports',
            'edit reports',
            'view survey',
            'edit survey',
            'view SAR',
            'edit SAR',
            'view PAR',
            'edit PAR',
            'view leave',
            'edit leave',
            'approve leave',
            'view salarySlip',
            'edit salarySlip',
            'approve salarySlip',
            'view gallery',
            'edit gallery',
            'view views',
            'edit views',
            'view conferenceHall',
            'edit conferenceHall',
            'view myProjects',
            'edit myProjects',
            'view myAccounts',
            'edit myAccounts',
            'view tools',
            'edit tools',
            'view jobs',
            'edit jobs',
            'view email',
            'edit email',
            'view settings',
            'edit settings',
        ]);

        Role::firstOrCreate(['name' => 'HR'])->syncPermissions([
            'view attendance',
            'view works',
            'edit works',
            'view projects',
            'edit projects',
            'view users', 
            'edit users',
            'delete users',
            'view reports',
            'edit reports',
            'view survey',
            'edit survey',
            'view SAR',
            'edit SAR',
            'view PAR',
            'edit PAR',
            'view leave',
            'edit leave',
            'approve leave',
            'view salarySlip',
            'edit salarySlip',
            'approve salarySlip',
            'view gallery',
            'edit gallery',
            'view views',
            'edit views',
            'view conferenceHall',
            'edit conferenceHall',
            'view myProjects',
            'edit myProjects',
            'view myAccounts',
            'edit myAccounts',
            'view tools',
            'edit tools',
            'view jobs',
            'edit jobs',
            'view email',
            'edit email',
            'view settings',
            'edit settings',
        ]);

        Role::firstOrCreate(['name' => 'G2'])->syncPermissions([
            'view attendance', 
            'view works', 
            'view projects', 
            'view users', 
            'view reports', 
            'view survey', 
            'view SAR', 
            'view PAR', 
            'view leave', 
            'view salarySlip',
            'view gallery', 
            'view views', 
            'view conferenceHall', 
            'view myProjects', 
            'view myAccounts',
            'view email',
            'view tools', 
            'view jobs',
        ]);

        Role::firstOrCreate(['name' => 'G3'])->syncPermissions([
            'view attendance', 
            'view works', 
            'view projects', 
            'view users', 
            'view reports', 
            'view survey', 
            'view SAR', 
            'view PAR', 
            'view leave', 
            'view salarySlip',
            'view gallery', 
            'view views', 
            'view conferenceHall', 
            'view myProjects', 
            'view myAccounts',
            'view email',
            'view tools', 
            'view jobs',
        ]);

        Role::firstOrCreate(['name' => 'G4'])->syncPermissions([
            'view attendance', 
            'view works', 
            'view projects', 
            'view users', 
            'view reports', 
            'view survey', 
            'view SAR', 
            'view PAR', 
            'view leave', 
            'view salarySlip',
            'view gallery', 
            'view views', 
            'view conferenceHall', 
            'view myProjects', 
            'view myAccounts',
            'view email',
            'view tools', 
            'view jobs',
        ]);

        Role::firstOrCreate(['name' => 'G5'])->syncPermissions([
            'view attendance', 
            'view works', 
            'view projects', 
            'view users', 
            'view reports', 
            'view survey', 
            'view SAR', 
            'view PAR', 
            'view leave', 
            'view salarySlip',
            'view gallery', 
            'view views', 
            'view conferenceHall', 
            'view myProjects', 
            'view myAccounts',
            'view email',
            'view tools', 
            'view jobs',
        ]);
    }
}
