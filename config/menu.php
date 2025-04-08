<?php
return [
    [
        'title' => 'Dashboards',
        'icon' => 'ti ti-building-store',
        'route' => 'home',
        'isActive' => ['home'],
        'permission' => ['view dashboard'],
    ],
    [
        'header' => 'Modules'
    ],
    [
        'title' => 'Attendance',
        'icon' => 'ti ti-alarm',
        'route' => 'attendance',
        'permission' => ['view attendance'],
        'isActive' => ['attendance*'],
    ],
    [
        'title' => 'Works',
        'icon' => 'ti ti-building-bank',
        'permission' => ['view works', 'view workStatus', 'view sduProjectStatus', 'view temporaryStatus', 'view openEntry'],
        'route' => 'javascript:void(0);',
        // 'badge' => 3,
        'submenu' => [
            [
                'title' => 'Work Status',
                'route' => 'works/status',
                'permission' => ['view workStatus'],
            ],
            [
                'title' => 'SDU Project Status',
                'route' => 'works/sud-project-status',
                'permission' => ['view sduProjectStatus'],
            ],
            [
                'title' => 'Temporary Status',
                'route' => 'works/temporary-status',
                'permission' => ['view temporaryStatus'],
            ],
            [
                'title' => 'Entry Open Markin',
                'route' => 'works/entry-open',
                'permission' => ['view openEntry'],
            ],
        ]
    ],
    [
        'title' => 'Projects',
        'icon' => 'ti ti-briefcase',
        'route' => 'javascript:void(0);',
        'isActive' => ['projects*', 'project*', 'tasks-project*', 'productivity-target*'],
        'permission' => ['view projects', 'view projectTask', 'view productivityTarget'],
        'submenu' => [
            [
                'title' => 'Projects',
                'route' =>  'projects',
                'isActive' => ['projects', 'project*'],
                'permission' => ['view projects'],
            ],
            [
                'title' => 'Project Tasks',
                'route' =>  'tasks-project',
                'isActive' => ['tasks-project*'],
                'permission' => ['view projectTask'],
            ],
            [
                'title' => 'Productivity Targets',
                'route' =>  'productivity-target',
                'isActive' => ['productivity-target*'],
                'permission' => ['view productivityTarget'],
            ],

        ]
    ],
    [
        'title' => 'Users',
        'icon' => 'ti ti-users',
        'route' => 'javascript:void(0);',
        'isActive' => ['users*'],
        'permission' => ['view users', 'view userCreate'],
        'submenu' => [
            [
                'title' => 'Users',
                'route' =>  'users',
                'permission' => ['view users'],
            ],
            [
                'title' => 'Add Users',
                'route' =>  '/users/create',
                'permission' => ['view userCreate'],
            ],
        ]
    ],
    [
        'title' => 'Reports',
        'icon' => 'ti ti-printer',
        'route' => 'javascript:void(0);',
        'permission' => ['view reports'],
        'submenu' => [
            [
                'title' => 'My Overview',
                'route' => '#',
                'permission' => ['view myOverview'],
            ],
            [
                'title' => 'My Attendance',
                'route' => '#',
                'permission' => ['view myAttendance'],
            ],
            [
                'title' => 'My Work Report',
                'route' => '#',
                'permission' => ['view myWorkReport'],
            ],
            [
                'title' => 'My Emergency Report',
                'route' => '#',
                'permission' => ['view myEmergencyReport'],
            ],
            [
                'title' => 'Salary Slip',
                'route' => '#',
                'permission' => ['view mySalarySlip'],
                'badge' => 'new',
            ],
        ]
    ],
    [
        'title' => 'Survey',
        'icon' => 'ti ti-rocket',
        'route' => 'javascript:void(0);',
        'permission' => ['view survey'],
        'badge' => "New",
        'submenu' => [
            [
                'title' => 'View Survey',
                'route' => '#',
                'permission' => ['view survey'],
            ],
        ]
    ],
    [
        'title' => 'PAR',
        'icon' => 'ti ti-send',
        'route' => 'javascript:void(0);',
        'permission' => ['view PAR'],
        'badge' => "New",
        'submenu' => [
            [
                'title' => 'View PAR',
                'route' => '#',
                'permission' => ['view PAR'],
            ],
            
        ]
    ],
    [
        'title' => 'Leave',
        'icon' => 'ti ti-leaf',
        'route' => 'javascript:void(0);',
        'permission' => ['view leave'],
        'submenu' => [
            [
                'title' => 'Apply Leave',
                'route' =>  '/leaves/create',
                'permission' => ['view applyLeave'],
            ],
            [
                'title' => 'Pending Request',
                'route' =>  'pending-leaves',
                'permission' => ['view pendingLeave'],
            ],
            [
                'title' => 'Leave Status',
                'route' =>  'leave-status',
                'permission' => ['view leaveStatus'],
            ],
            [
                'title' => 'Leave Summary',
                'route' =>  'leaves',
                'permission' => ['view leaveSummary'],
            ],
            [
                'title' => 'Leave Allocation',
                'route' =>  '/leave-allocation',
                'permission' => ['view leaveAllocation'],
            ],
        ]
    ],
    [
        'title' => 'Salary Slip',
        'icon' => 'ti ti-cash',
        'route' => 'javascript:void(0);',
        'permission' => ['view salarySlip'],
        'submenu' => [

            // [
            //     'title' => 'Uploload Salary Slip',
            //     'route' =>  '/salarySlip/upload',
            // ],
            [
                'title' => 'Salary Slip',
                'route' =>  '/salarySlip/view',
                'permission' => ['view leaveStatus'],
            ],
        ]
    ],
    [
        'title' => 'Gallery',
        'icon' => 'ti ti-icons',
        'route' => '#',
        'permission' => ['view gallery'],
    ],
    [
        'title' => 'View',
        'icon' => 'ti ti-eye',
        'route' => 'javascript:void(0);',
        'permission' => ['view views'],
        'submenu' => [
            [
                'title' => 'Thought Of The Day',
                'route' =>  '#',
                'permission' => ['view thoughtOfTheDay'],
                
            ],
            [
                'title' => ' Appreciation',
                'route' =>  '#',
                'permission' => ['view appreciation'],
            ],
            [
                'title' => 'Birthdays',
                'route' =>  '#',
                'permission' => ['view birthdays'],
            ],
            [
                'title' => 'Announcement',
                'route' =>  '#',
                'permission' => ['view announcement'],
            ],
            [
                'title' => 'Company Policies ',
                'route' =>  '#',
                'permission' => ['view companyPolicies'],
                'badge' => "New",
            ],
            [
                'title' => 'Events',
                'route' =>  '#',
                'permission' => ['view events'],
            ],
            [
                'title' => 'Holidays',
                'route' =>  '#',
                'permission' => ['view holidays'],
            ],
            [
                'title' => 'User Reminder List',
                'route' =>  '#',
                'permission' => ['view userReminderList'],
            ],
        ]
    ],
    [
        'title' => 'Conference Hall',
        'icon' => 'ti ti-podium',
        'route' => 'javascript:void(0);',
        'permission' => ['view conferenceHall'],
        'submenu' => [
            [
                'title' => 'Booking',
                'route' =>  '#',
                'permission' => ['view booking'],
            ],
            [
                'title' => 'View Bookings',
                'route' =>  '#',
                'permission' => ['view viewBookings'],
            ],
            [
                'title' => 'My Bookings',
                'route' =>  '#',
                'permission' => ['view myBookings'],
            ],
            [
                'title' => 'Assigned Bookings',
                'route' =>  '#',
                'permission' => ['view assignedBookings'],
            ],
        ]
    ],
    [
        'title' => 'SAR',
        'icon' => 'ti ti-replace',
        'route' => 'javascript:void(0);',
        'permission' => ['view SAR'],
        'submenu' => [
            [
                'title' => 'SAR Form',
                'route' =>  '#',
                'permission' => ['view sarForm'],
            ],
            [
                'title' => 'SAR Reviews',
                'route' =>  '#',
                'permission' => ['view sarReviews'],
            ],
        ]
    ],
    [
        'title' => 'My Projects ',
        'icon' => 'ti ti-presentation',
        'route' => '#',
        'permission' => ['view myProjects'],
    ],
    [
        'title' => 'My Account',
        'icon' => 'ti ti-user',
        'route' => 'javascript:void(0);',
        'permission' => ['view myAccount'],
        'submenu' => [
            [
                'title' => 'My Profile',
                'route' =>  '#',
                'permission' => ['view myProfile'],
            ],
            [
                'title' => 'Change Password',
                'route' =>  '#',
                'permission' => ['view changePassword'],
            ],
            [
                'title' => 'Edit Profile',
                'route' =>  '#',
                'permission' => ['view editProfile'],
            ],
        ]
    ],
    [
        'title' => 'Tools',
        'icon' => 'ti ti-tools',
        'route' => 'javascript:void(0);',
        'permission' => ['view tools'],
        'submenu' => [
            [
                'title' => 'Quick Notes',
                'route' =>  '#',
                'permission' => ['view quickNotes'],
            ],
            [
                'title' => 'Event Calendar',
                'route' =>  '#',
                'permission' => ['view eventCalendar'],
            ],
            [
                'title' => 'KSP',
                'route' =>  '#',
                'permission' => ['view ksp'],
            ],
        ]
    ],
    [
        'title' => 'Jobs',
        'icon' => 'ti ti-briefcase',
        'route' => 'javascript:void(0);',
        'permission' => ['view jobs'],
        'submenu' => [
            [
                'title' => 'My Jobs',
                'route' =>  '#',
                'permission' => ['view myJobs'],
            ],
            [
                'title' => 'Assign A job',
                'route' =>  '#',
                'permission' => ['view assignJob'],
            ],
            [
                'title' => 'Jobs Assigned by You',
                'route' =>  '#',
                'permission' => ['view jobsAssignedByYou'],
            ],
        ]
    ],
    [
        'title' => 'Email',
        'icon' => 'ti ti-mail-forward',
        'route' => 'javascript:void(0);',
        'permission' => ['view email'],
        'submenu' => [
            [
                'title' => 'Inbox',
                'route' =>  '#',
                'permission' => ['view inbox'],
            ],
            [
                'title' => 'Starred',
                'route' =>  '#',
                'permission' => ['view starred'],
            ],
            [
                'title' => 'Sent Email',
                'route' =>  '#',
                'permission' => ['view sentEmail'],
            ],
            [
                'title' => 'Trash',
                'route' =>  '#',
                'permission' => ['view trash'],
            ],
        ]
    ],
    [
        'header' => 'Settings',
    ],
    [
        'title' => 'Settings',
        'icon' => 'ti ti-switch-3',
        'route' => 'javascript:void(0);',
        'permission' => ['view settings'],
        'submenu' => [
            [
                'title' => 'Work Shift',
                'route' =>  'settings/workshift',
                'permission' => ['view workShift'],
            ],
            [
                'title' => 'Branches',
                'route' =>  'branchs',
                'permission' => ['view branches'],
            ],
            [
                'title' => 'Holidays',
                'route' =>  'holidays',
                'permission' => ['view holidays'],
            ],
        ]
    ],
    [
        'title' => 'Roles & Permissions',
        'icon' => 'ti ti-drone',
        'route' => 'javascript:void(0);',
        'permission' => ['view roles', 'view permissions'],
        'submenu' => [
            [
                'title' => 'Roles',
                'route' =>  'roles',
                'permission' => ['view roles'],
            ],
            [
                'title' => 'Permission',
                'route' =>  'permissions',
                'permission' => ['view permissions'],
            ],
        ]
    ],
    [
        'title' => 'Departments',
        'icon' => 'ti ti-arrow-guide',
        'route' => "/branchs",
        'permission' => ['view departments'],
    ],
];

