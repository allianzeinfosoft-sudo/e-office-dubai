<?php
return [

    [
        'title' => 'Dashboards',
        'icon' => 'ti ti-building-store',
        'route' => 'home',
        'isActive' => ['home'],
    ],
    [
        'title' => 'EOffice Feeds',
        'icon' => 'ti ti-gift',
        'route' => 'feeds',
        'isActive' => ['feeds'],
        'permission' => ['view feeds'],

    ],
    [
        'header' => 'Modules'
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
        'title' => 'My Account',
        'icon' => 'ti ti-user',
        'route' => 'javascript:void(0);',
        'submenu' => [
            [
                'title' => 'My Profile',
                'route' =>  '#'
            ],
            [
                'title' => 'Change Password',
                'route' =>  '#'
            ],
            [
                'title' => 'Edit Profile',
                'route' =>  '#'
            ],
        ]
    ],
    [
        'title' => 'Salary',
        'icon' => 'ti ti-cash',
        'route' => 'javascript:void(0);',
        'permission' => ['view salary'],
        'submenu' => [

            [
                'title' => 'Salary Slip',
                'route' =>  '/salarySlip/view',
            ],
        ]
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
        'permission' => ['view work status', 'view temporary status', 'open markin'],
        'route' => 'javascript:void(0);',
        // 'badge' => 3,
        'submenu' => [
            [
                'title' => 'Work Status',
                'route' => 'works/status',
                'permission' => ['view work status'],
            ],
            [
                'title' => 'Temporary Status',
                'route' => 'works/temporary-status',
                'permission' => ['view temporary status'],
            ],
            [
                'title' => 'Entry Open Markin',
                'route' => 'works/entry-open',
                'permission' => ['open markin'],
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
                'permission' => ['view my overview'],
            ],
            [
                'title' => 'My Attendance',
                'route' => '#',
                'permission' => ['view attendance report'],
            ],
            [
                'title' => 'My Work Report',
                'route' => '#',
                'permission' => ['view my work report'],
            ],
            [
                'title' => 'My Emergency Report',
                'route' => '#',
                'permission' => ['view emergency report'],
            ],
            [
                'title' => 'Salary Slip Report',
                'route' => '#',
                'permission' => ['view salary slip'],
                // 'badge' => 'new',
            ],
        ]
    ],
    [
        'title' => 'Survey',
        'icon' => 'ti ti-rocket',
        'route' => 'javascript:void(0);',
        'permission' => ['create survey','assign survey','view surveys'],
        // 'badge' => "New",
        'submenu' => [
            [
                'title' => 'Add Survey',
                'route' => '#',
                'permission' => ['create survey'],
            ],
            [
                'title' => 'Assign Survey',
                'route' => '#',
                'permission' => ['assign survey'],
            ],
            [
                'title' => 'View Survey',
                'route' => '#',
            ],
        ]
    ],
    [
        'title' => 'SAR',
        'icon' => 'ti ti-replace',
        'route' => 'javascript:void(0);',
        'permission' => ['view SARs','review SAR'],
        'submenu' => [
            [
                'title' => 'SAR Form',
                'route' =>  '#',
                'permission' => ['view SARs'],
            ],
            [
                'title' => 'SAR Reviews',
                'route' =>  '#',
                'permission' => ['review SAR'],
            ],
        ]
    ],
    [
        'title' => 'PAR',
        'icon' => 'ti ti-send',
        'route' => 'javascript:void(0);',
        'permission' => ['view PAR','create PAR', 'assign PAR'],
        'badge' => "New",
        'submenu' => [
            [
                'title' => 'Add PAR',
                'route' => '#',
                'permission' => ['create PAR'],
            ],
            [
                'title' => 'Assing PAR',
                'route' => '#',
                'permission' => ['assign PAR'],
            ],
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
        'permission' => ['create leave request','leave status','leave summary','custom leave', 'pending leave request','leave allocation'],
        'submenu' => [
            [
                'title' => 'Apply Leave',
                'route' =>  '/leaves/create',
                'permission' => ['create leave request'],
            ],
            [
                'title' => 'Leave Status',
                'route' =>  'leave-status',
                'permission' => ['leave status'],
            ],
            [
                'title' => 'Leave Summary',
                'route' =>  'leaves',
                'permission' => ['leave summary'],
            ],
            [
                'title' => 'Custom Leave',
                'route' =>  '/custom_leave',
                'permission' => ['custom leave'],
            ],
            [
                'title' => 'Pending Request',
                'route' =>  'pending-leaves',
                'permission' => ['pending leave request'],
            ],

            [
                'title' => 'Leave Allocation',
                'route' =>  '/leave-allocation',
                'permission' => ['leave allocation'],
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
        'permission' => ['view though','view appreciation','view birthday', 'view event', 'view policy', 'view holiday', 'view reminder'],
        'submenu' => [
            [
                'title' => 'Thought Of The Day',
                'route' =>  'thoughts',
                'permission' => ['view thought'],

            ],
            [
                'title' => ' Appreciation',
                'route' =>  '#',
            ],
            [
                'title' => 'Birthdays',
                'route' =>  '#',
                'permission' => ['view birthday'],
            ],
            [
                'title' => 'Announcement',
                'route' =>  '#',
            ],
            [
                'title' => 'Company Policies ',
                'route' =>  '#',
                'permission' => ['view policy'],
                'badge' => "New",
            ],
            [
                'title' => 'Events',
                'route' =>  '#',
                'permission' => ['view event'],
            ],
            [
                'title' => 'Holidays',
                'route' =>  '#',
                'permission' => ['view holiday'],
            ],
            [
                'title' => 'User Reminder List',
                'route' =>  '#',
                'permission' => ['view reminder'],
            ],
        ]
    ],
    [
        'title' => 'Others',
        'icon' => 'ti ti-rss',
        'route' => 'javascript:void(0);',
        'isActive' => ['others*'],
        'permission' => ['seen status report', 'view thoughts', 'view appreciation','view policy', 'view announcement','view banner','view event'],
        'submenu' => [
            [
                'title' => 'Seen status report',
                'route' =>  '#',
                'isActive' => [],
                'permission' => ['seen status report']
            ],
            [
                'title' => 'Thoughts',
                'route' =>  '#',
                'isActive' => [],
                'permission' => ['view thoughts'],
                'submenu' => [

                    [
                        'title' => 'view thoughts',
                        'route' => "#",
                        'isActive' => [],
                        'permission' => ['view thoughts'],
                        ''
                    ]
                ]
            ],
            [
                'title' => 'Appreciation',
                'route' =>  '#',
                'isActive' => [],
                'permission' => ['view appreciation'],
                'submenu' => [

                    [
                        'title' => 'view Appreciation',
                        'route' => "#",
                        'isActive' => [],
                        'permission' => ['view appreciation'],
                        ''
                    ]
                ]

            ],
            [
                'title' => 'Policies',
                'route' =>  'others/policies',
                'isActive' => ['policy*'],
                'permission' => ['view policy'],
            ],
            [
                'title' => 'Announcement',
                'route' =>  'javascript:void(0);',
                'isActive' => ['announcements*'],
                'permission' => [],
                'submenu' => [
                    [
                        'title' => 'Announcements',
                        'route' => "/others/announcements",
                        'isActive' => [],
                        'permission' => [],
                        ''
                    ],
                    [
                        'title' => 'Banners',
                        'route' => "#",
                        'isActive' => [],
                        'permission' => [],
                        ''
                    ],
                ]
            ],
            [
                'title' => 'Events',
                'route' =>  '/others/events',
                'isActive' => ['events*'],
                'permission' => ['view events'],
            ],
        ]
    ],
    [
        'title' => 'Conference Hall',
        'icon' => 'ti ti-podium',
        'route' => 'javascript:void(0);',
        'permission' => ['create booking','view bookings','assigned booking'],
        'submenu' => [
            [
                'title' => 'Booking',
                'route' =>  '#',
                'permission' => ['create booking'],
            ],
            [
                'title' => 'View Bookings',
                'route' =>  '#',
                'permission' => ['view bookings'],
            ],
            [
                'title' => 'Assigned Bookings',
                'route' =>  '#',
                'permission' => ['assigned booking'],
            ],
        ]
    ],
    [
        'title' => 'Projects',
        'icon' => 'ti ti-briefcase',
        'route' => 'javascript:void(0);',
        'isActive' => ['projects*', 'project*', 'tasks-project*', 'productivity-target*'],
        'permission' => ['view projects','view project task','view productivity target'],
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
                'permission' => ['view project task'],
            ],
            [
                'title' => 'Productivity Targets',
                'route' =>  'productivity-target',
                'isActive' => ['productivity-target*'],
                'permission' => ['view productivity target'],
            ]
        ]
    ],
    // [
    //     'title' => 'My Projects ',
    //     'icon' => 'ti ti-presentation',
    //     'route' => '#',
    //     'permission' => ['view myProjects'],
    // ],

    [
        'title' => 'Tools',
        'icon' => 'ti ti-tools',
        'route' => 'javascript:void(0);',
        'permission' => ['view quick notes','view event calendar', 'view KSP'],
        'submenu' => [
            [
                'title' => 'Quick Notes',
                'route' =>  '#',
                'permission' => ['view quick notes'],
            ],
            [
                'title' => 'Event Calendar',
                'route' =>  '#',
                'permission' => ['view event calendar'],
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
                'title' => 'Draff',
                'route' =>  '/recruitments/draft-list',
            ],
        ]
    ],
    [
        'title' => 'Jobs',
        'icon' => 'ti ti-briefcase',
        'route' => 'javascript:void(0);',
        'permission' => ['view jobs','assign job'],
        'submenu' => [
            [
                'title' => 'My Jobs',
                'route' =>  '#',
                'permission' => ['view jobs'],
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
        'permission' => ['view emails','view starred','send mail','trashed mail'],
        'submenu' => [
            [
                'title' => 'Inbox',
                'route' =>  '#',
                'permission' => ['view emails'],
            ],
            [
                'title' => 'Starred',
                'route' =>  '#',
            ],
            [
                'title' => 'Sent Email',
                'route' =>  '#',
                'permission' => ['send email'],
            ],
            [
                'title' => 'Trash',
                'route' =>  '#',
                'permission' => ['trashed mail'],
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
        'permission' => ['change appearence','view department','assign open work' ,'manage roles', 'view holiday', 'view shift time', 'change shift time', 'custom markout', 'custom attendance', 'full day entry', 'custom work report entry', 'edit attendance'],
        'submenu' => [
            [
                'title' => 'Change appearence',
                'route' =>  '',
                'permission' => ['change appearence'],
            ],
            [
                'title' => 'Departments',
                'route' =>  'branchs',
                'permission' => ['view department'],
            ],
            [
                'title' => 'Assign Open Work',
                'route' =>  '',
                'permission' => ['assign open work'],
            ],
            [
                'title' => 'Role Management',
                'route' =>  'roles',
                'permission' => ['manage roles'],
            ],
            [
                'title' => 'Holiday',
                'route' =>  'holidays',
                'permission' => ['view holiday'],
            ],
            [
                'title' => 'Shift Time',
                'route' =>  '/workshift/list',
                'permission' => ['view shift time'],
            ],
            [
                'title' => 'Change Shift Time',
                'route' =>  '',
                'permission' => ['change shift time'],
            ],
            [
                'title' => 'Custom Markout',
                'route' =>  '',
                'permission' => ['custom markout'],
            ],
            [
                'title' => 'Custom Attendance Entry',
                'route' =>  '',
                'permission' => ['custom attendance'],
            ],
            [
                'title' => 'Fullday Entry',
                'route' =>  '',
                'permission' => ['full day entry'],
            ],
            [
                'title' => 'Custom Work Report Entry',
                'route' =>  '',
                'permission' => ['custom work report entry'],
            ],
            [
                'title' => 'Edit Daily Attendance',
                'route' =>  '',
                'permission' => ['edit attendance'],
            ]
        ]
    ]

];

