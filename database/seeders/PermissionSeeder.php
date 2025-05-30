<?php

namespace Database\Seeders;

use App\Models\PermissionCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $categoriesWithPermissions = [
            'User Management' => [
                'view users', 'create users', 'edit users', 'delete users',
                'assign roles', 'reset user password', 'search users', 'user switching','view birthday','view resigned users'
            ],
            'Account' =>[
                'view profile','change password', 'edit profile','lock profile'
            ],
            'Attendance Management' => [
                'view attendance', 'mark attendance', 'edit attendance', 'delete attendance', 'approve attendance',
                'custom attendance', 'custom markout', 'full day entry', 'custom attendance approval', 'incomplete working hour approval'
            ],
            'Leave Management' => [
                'view leave requests', 'create leave request', 'edit leave request', 'delete leave request',
                'approve leave', 'reject leave', 'custom leave', 'leave status', 'leave allocation','pending leave request','leave summary','All Leave Request'
            ],
            'Project Management' => [
                'view projects', 'create project', 'edit project', 'delete project',
                'assign project members', 'change project status', 'view project tasks' ,'create project task',
                'delete project task', 'view productivity task', 'create productivity task', 'edit productivity task',
                'delete productivity task',
            ],
            'Work Management' => [
                'view work', 'view work status', 'view temporary status','open markin','custom work report entry'
            ],
            'Reports Management' => [
                'view reports', 'generate reports', 'export reports', 'delete reports','view my overview', 'view attendance report', 'view my work report',
                'view emergency report', 'view salary slip', 'seen status report', 'view monthly overview', 'view leave report', 'view all attendance report',
                'view all work report', 'view over all work report', 'view emergency attendance report', 'view all emergency work report', 'view my attendance report',
                'view user overview'
            ],
            'Survey Management' => [
                'view surveys', 'create survey', 'edit survey', 'delete survey', 'submit survey response', 'assign survey'
            ],
            'SAR Management' => [
                'view SARs', 'create SAR', 'edit SAR', 'delete SAR', 'review SAR', 'assign SAR'
            ],
            'PAR Management' => [
                'view PARs', 'create PAR', 'edit PAR', 'delete PAR', 'review PAR', 'assign PAR'
            ],
            'Conference Hall Management' => [
                'view bookings', 'create booking', 'edit booking', 'cancel booking', 'approve booking', 'asssigned booking'
            ],
            'Salary Management' => [
                'view salary', 'generate salary', 'edit salary', 'delete salary', 'upload salary'
            ],
            'KSP Management' => [
                'view KSP', 'create KSP', 'edit KSP', 'delete KSP'
            ],
            'Quick Note Management' => [
                'view quick notes', 'create quick note', 'edit quick note', 'delete quick note',
            ],
            'Event Calendar Management' => [
                'view event calendar', 'create event calendar', 'edit event calendar', 'delete event calendar',
            ],
            'Recruitment Management' => [
                'view job applications', 'create job posting', 'edit job posting', 'delete job posting',
                'approve job applications','shortlist candidates', 'schedule interviews'
            ],
            'E-Library Management' => [
                'view e-library', 'add books', 'edit books', 'delete books','issue books'
            ],
            'Thought Management' => [
                'create thought', 'view thought','edit thought', 'delete thought', 'publish thought'
            ],
            'Appreciation Management' => [
                'create appreciation', 'view appreciation', 'edit appreciation', 'delete appreciation', 'publish appreciation'
            ],
            'Announcement Management' => [
                'create announcement', 'view announcement', 'edit announcement', 'delete announcement','publish announcement'
            ],
            'Event Management' => [
                'create event', 'view event', 'edit event', 'delete event', 'publish event'
            ],
            'Holiday Management' => [
                'create holiday', 'view holiday', 'edit holiday', 'delete holiday', 'publish holiday'
            ],
            'Policy Management' => [
                'create policy', 'view policy', 'edit policy', 'delete policy', 'public policy'
            ],
            'MOM Management' => [
                'create MOM', 'View MOM', 'edit MOM', 'delete MOM', 'assign MOM', 'view user moms'
            ],
            'Gallery Management' => [
                'view gallery', 'upload media', 'edit media', 'delete media'
            ],
            'Reminder Management' => [
                'view reminder','create reminder', 'edit reminder', 'delete reminder', 'publish reminder'
            ],
            'Notification Management' => [
                'view user notification', 'view attendance notification', 'leave notification', 'project notification',
                'report notification', 'survey notification', 'PAR notification', 'SAR notification', 'salary notification',
                'tools notification', 'recruitment notification', 'feeds notification', 'thoughts notification',
                'appreciation notification', 'birthday notification', 'announcement notification', 'events notification',
                'holiday notification', 'reminder notification', 'policy notification', 'MOM notification', 'email notification',
                'jobs notification', 'settings notification',
            ],
            'Email Management' => [
                'view emails', 'send email', 'edit email template', 'delete email', 'view starred', 'trashed mail'
            ],
            'Jobs Management' => [
                'view jobs', 'post job', 'edit job', 'delete job', 'publish job', 'assign job'
            ],
            'Settings Management' => [
                'view settings', 'update settings', 'manage roles', 'manage permissions', 'change appearence', 'view department',
                'create department', 'edit department', 'delete department', 'view designation', 'create designation', 'edit designation',
                'delete designation', 'assign designation', 'assign open work', 'view roles and permission', 'assign designation',
                'view shift time', 'create shift time', 'edit shift time', 'delete shift time', 'assign shift time', 'view dashboard', 'change shift time',
                'view feeds', 'leave approvals'
            ]
        ];

        // Insert categories and collect their IDs
        $categoryIds = [];

        foreach ($categoriesWithPermissions as $categoryName => $permissions) {
            $category = PermissionCategory::firstOrCreate(['name' => $categoryName]);
            $categoryIds[$categoryName] = $category->id;

            foreach ($permissions as $permissionName) {
                Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'web'
                ], [
                    'permission_category_id' => $category->id
                ]);
            }
        }
    }

}
