<?php
return [
    [
        'title' => 'Dashboards',
        'icon' => 'ti ti-building-store',
        'route' => 'home',
        'isActive' => ['home'],
    ],
    [
        'header' => 'Modules'
    ],
    [
        'title' => 'Attendance',
        'icon' => 'ti ti-alarm',
        'route' => 'attendance',
        'isActive' => ['attendance*'],
    ],
    [
        'title' => 'Works',
        'icon' => 'ti ti-building-bank',
        'route' => 'javascript:void(0);',
        // 'badge' => 3,
        'submenu' => [
            [
                'title' => 'Work Status',
                'route' => 'works/status',
            ],
            [
                'title' => 'SDU Project Status',
                'route' => 'works/sud-project-status',
            ],
            [
                'title' => 'Temporary Status',
                'route' => 'works/temporary-status',
            ],
            [
                'title' => 'Entry Open Markin',
                'route' => 'works/entry-open',
            ],
        ]
    ],
    [
        'title' => 'Projects',
        'icon' => 'ti ti-briefcase',
        'route' => 'javascript:void(0);',
        'isActive' => ['projects*', 'project*', 'tasks-project*', 'productivity-target*'],
        'submenu' => [
            [
                'title' => 'Projects',
                'route' =>  'projects',
                'isActive' => ['projects', 'project*'],
            ],
            [
                'title' => 'Project Tasks',
                'route' =>  'tasks-project',
                'isActive' => ['tasks-project*'],
            ],
            [
                'title' => 'Productivity Targets',
                'route' =>  'productivity-target',
                'isActive' => ['productivity-target*'],
            ],

        ]
    ],
    [
        'title' => 'Users',
        'icon' => 'ti ti-users',
        'route' => 'javascript:void(0);',
        'isActive' => ['users*'],
        'submenu' => [
            [
                'title' => 'Users',
                'route' =>  'users',
            ],
            [
                'title' => 'Add Users',
                'route' =>  '/users/create',
            ],
        ]
    ],
    [
        'title' => 'Reports',
        'icon' => 'ti ti-printer',
        'route' => 'javascript:void(0);',
        'submenu' => [
            [
                'title' => 'My Overview',
                'route' => '#',
            ],
            [
                'title' => 'My Attendance',
                'route' => '#',
            ],
            [
                'title' => 'My Work Report',
                'route' => '#',
            ],
            [
                'title' => 'My Emergency Report',
                'route' => '#',
            ],
            [
                'title' => 'Salary Slip',
                'route' => '#',
                'badge' => 'new',
            ],
        ]
    ],
    [
        'title' => 'Survey',
        'icon' => 'ti ti-rocket',
        'route' => 'javascript:void(0);',
        'badge' => "New",
        'submenu' => [
            [
                'title' => 'View Survey',
                'route' => '#',
            ],
        ]
    ],
    [
        'title' => 'PAR',
        'icon' => 'ti ti-send',
        'route' => 'javascript:void(0);',
        'badge' => "New",
        'submenu' => [
            [
                'title' => 'View PAR',
                'route' => '#',
            ],
            
        ]
    ],
    [
        'title' => 'Leave',
        'icon' => 'ti ti-leaf',
        'route' => 'javascript:void(0);',
        'submenu' => [
            [
                'title' => 'Apply Leave',
                'route' =>  '/leaves/create',
            ],
            [
                'title' => 'Pending Request',
                'route' =>  'pending-leaves',
            ],
            [
                'title' => 'Leave Status',
                'route' =>  'leave-status',
            ],
            [
                'title' => 'Leave Summary',
                'route' =>  'leaves',
            ],
            [
                'title' => 'Leave Allocation',
                'route' =>  '/leave-allocation',
            ],
        ]
    ],
    [
        'title' => 'Salary Slip',
        'icon' => 'ti ti-cash',
        'route' => 'javascript:void(0);',
        'submenu' => [

            // [
            //     'title' => 'Uploload Salary Slip',
            //     'route' =>  '/salarySlip/upload',
            // ],
            [
                'title' => 'Salary Slip',
                'route' =>  '/salarySlip/view',
            ],
        ]
    ],
    [
        'title' => 'Gallery',
        'icon' => 'ti ti-icons',
        'route' => '#',
    ],
    [
        'title' => 'View',
        'icon' => 'ti ti-eye',
        'route' => 'javascript:void(0);',
        'submenu' => [
            [
                'title' => 'Thought Of The Day',
                'route' =>  '#',
            ],
            [
                'title' => ' Appreciation',
                'route' =>  '#',
            ],
            [
                'title' => 'Birthdays',
                'route' =>  '#',
            ],
            [
                'title' => 'Announcement',
                'route' =>  '#',
            ],
            [
                'title' => 'Company Policies ',
                'route' =>  '#',
                'badge' => "New",
            ],
            [
                'title' => 'Events',
                'route' =>  '#',
            ],
            [
                'title' => 'Holidays',
                'route' =>  '#',
            ],
            [
                'title' => 'User Reminder List',
                'route' =>  '#',
            ],
        ]
    ],
    [
        'title' => 'Conference Hall',
        'icon' => 'ti ti-podium',
        'route' => 'javascript:void(0);',
        'submenu' => [
            [
                'title' => 'Booking',
                'route' =>  '#',
            ],
            [
                'title' => 'View Bookings',
                'route' =>  '#',
            ],
            [
                'title' => 'My Bookings',
                'route' =>  '#',
            ],
            [
                'title' => 'Assigned Bookings',
                'route' =>  '#',
            ],
        ]
    ],
    [
        'title' => 'SAR',
        'icon' => 'ti ti-replace',
        'route' => 'javascript:void(0);',
        'submenu' => [
            [
                'title' => 'SAR Form',
                'route' =>  '#',
            ],
            [
                'title' => 'SAR Reviews',
                'route' =>  '#',
            ],
        ]
    ],
    [
        'title' => 'My Projects ',
        'icon' => 'ti ti-presentation',
        'route' => '#',
    ],
    [
        'title' => 'My Account',
        'icon' => 'ti ti-user',
        'route' => 'javascript:void(0);',
        'submenu' => [
            [
                'title' => 'My Profile',
                'route' =>  '#',
            ],
            [
                'title' => 'Change Password',
                'route' =>  '#',
            ],
            [
                'title' => 'Edit Profile',
                'route' =>  '#',
            ],
        ]
    ],
    [
        'title' => 'Tools',
        'icon' => 'ti ti-tools',
        'route' => 'javascript:void(0);',
        'submenu' => [
            [
                'title' => 'Quick Notes',
                'route' =>  '#',
            ],
            [
                'title' => 'Event Calendar',
                'route' =>  '#',
            ],
            [
                'title' => 'KSP',
                'route' =>  '#',
            ],
        ]
    ],
    [
        'title' => 'Recruitments',
        'icon' => 'ti ti-target',
        'route' => 'javascript:void(0);',
        'isActive' => ['recruitments*'],
        'submenu' => [
            [
                'title' => 'RRF',
                'route' =>  '/recruitments',
            ],
            [
                'title' => 'Appove RRF',
                'route' =>  '#',
            ],
            [
                'title' => 'Applications',
                'route' =>  '#',
            ],
            [
                'title' => 'Draff',
                'route' =>  '#',
            ],
        ]
    ],
    [
        'title' => 'Jobs',
        'icon' => 'ti ti-briefcase',
        'route' => 'javascript:void(0);',
        'submenu' => [
            [
                'title' => 'My Jobs',
                'route' =>  '#',
            ],
            [
                'title' => 'Assign A job',
                'route' =>  '#',
            ],
            [
                'title' => 'Jobs Assigned by You',
                'route' =>  '#',
            ],
        ]
    ],
    [
        'title' => 'Email',
        'icon' => 'ti ti-mail-forward',
        'route' => 'javascript:void(0);',
        'submenu' => [
            [
                'title' => 'Inbox',
                'route' =>  '#',
            ],
            [
                'title' => 'Starred',
                'route' =>  '#',
            ],
            [
                'title' => 'Sent Email',
                'route' =>  '#',
            ],
            [
                'title' => 'Trash',
                'route' =>  '#',
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
        'submenu' => [
            [
                'title' => 'Work Shift',
                'route' =>  'settings/workshift',
            ],
            [
                'title' => 'Branches',
                'route' =>  'branchs',
            ],
            [
                'title' => 'Holidays',
                'route' =>  'holidays',
            ],
        ]
    ],
    [
        'title' => 'Roles & Permissions',
        'icon' => 'ti ti-drone',
        'route' => 'javascript:void(0);',
        'submenu' => [
            [
                'title' => 'Roles',
                'route' =>  'roles',
            ],
            [
                'title' => 'Permission',
                'route' =>  'permissions',
            ],
        ]
    ],
    [
        'title' => 'Departments',
        'icon' => 'ti ti-arrow-guide',
        'route' => "/branchs",
    ],
];

