<?php
return [
    [
        'title' => 'Dashboard',
        'icon' => 'ti ti-building-store',
        'route' => 'home',
        'isActive' => ['home'],
    ],
    [
        'title' => 'EOffice Feed',
        'icon' => 'ti ti-gift',
        'route' => 'feeds',
        'isActive' => ['feeds'],
        'permission' => ['view feeds'],
    ],
    [
        'title' => 'Attendance',
        'icon' => 'ti ti-alarm',
        'route' => 'attendance',
        'permission' => ['view attendance'],
        'isActive' => ['attendance*'],
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
                'title' => 'Locked Users',
                'route' =>  '/locked-users',
                'permission' => ['view resigned users'],
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
                'route' => 'reports/my-overview',
                'permission' => ['view my overview'],
            ],
            [
                'title' => 'My Attendance Report',
                'route' => '/reports/my-attendance-report',
                'permission' => ['view my attendance report'],
            ],
            [
                'title' => 'My work Report',
                'route' => '/reports/my-work-report',
                'permission' => ['view my work report'],
            ],

            [
                'title' => 'User Overview',
                'route' => 'reports/user-overview',
                'permission' => ['view user overview'],
            ],
            [
                'title' => 'User Monthly Overview',
                'route' => '/reports/user-monthly-overview',
                'permission' => ['view monthly overview'],
            ],
            [
                'title' => 'Daily Attendance Report',
                'route' => '/reports/daily-attendance-report',
                'permission' => ['view attendance report'],
            ],
            [
                'title' => 'Leave Report',
                'route' => '/reports/leave-report',
                'permission' => ['view leave report'],
            ],
            [
                'title' => 'All Attendance Report',
                'route' => '/reports/all-attendance-report',
                'permission' => ['view all attendance report'],
            ],
            [
                'title' => 'All Work Report',
                'route' => '/reports/all-work-report',
                'permission' => ['view all work report'],
            ],
            [
                'title' => 'Over All Work Report',
                'route' => '/reports/over-all-work-report',
                'permission' => ['view over all work report'],
            ],
            [
                'title' => 'All Emergency Attendance Report',
                'route' => '/reports/emergency-reports',
                'permission' => ['view emergency attendance report'],
            ]
        ]
    ],
    [
        'title' => 'Survey',
        'icon' => 'ti ti-rocket',
        'route' => 'javascript:void(0);',
        'permission' => ['create survey','assign survey','view surveys','view surveys report'],
        // 'badge' => "New",
        'submenu' => [
            [
                'title' => 'Survey`s',
                'route' => '/user-surveys',
                'permission' => ['view surveys'],
            ],
            [
                'title' => 'Questions Templates',
                'route' => '/surveytemplate',
                'permission' => ['create survey'],
            ],
            [
                'title' => 'Assign Survey',
                'route' => '/surveytemplate-assign',
                'permission' => ['assign survey'],
            ],
            [
                'title' => 'Survey Report',
                'route' => '/survey report',
            ],

        ]
    ],
    [
        'title' => 'SAR',
        'icon' => 'ti ti-replace',
        'route' => 'javascript:void(0);',
        'permission' => ['create SARs','assign SARs','view SARs','review SARs', 'view SARs report'],
        'submenu' => [
             [
                'title' => 'SAR`s',
                'route' =>  '/user-sars',
                'permission' => ['view SARs'],
            ],
            [
                'title' => 'Questions Templates',
                'route' =>  'sartemplate',
                'permission' => ['view SARs'],
            ],
            [
                'title' => 'Assign SAR',
                'route' =>  '/sartemplate-assign',
                'permission' => ['assign SAR'],
            ],
            [
                'title' => 'SAR Report',
                'route' => '/sar-report',
                'permission' => ['view SARs report'],
            ],
        ]
    ],
    [
        'title' => 'PAR',
        'icon' => 'ti ti-send',
        'route' => 'javascript:void(0);',
        'permission' => ['view PARs','create PARs', 'assign PARs', 'view PARs report'],
        'submenu' => [
            [
                'title' => 'PAR`S',
                'route' => '/user-pars',
                'permission' => ['view PARs'],
            ],
            [
                'title' => 'Questions Templates',
                'route' => '/partemplate',
                'permission' => ['view PARs'],
            ],
            [
                'title' => 'Assign PAR',
                'route' => '/partemplate-assign',
                'permission' => ['assign PAR'],
            ],
            [
                'title' => 'PAR Report',
                'route' => '/par-report',
                'permission' => ['view PARs report'],
            ],

        ]
    ],
    [
        'title' => 'Feedback',
        'icon' => 'ti ti-send',
        'route' => 'javascript:void(0);',
        'permission' => ['view feedback','create feedback', 'assign feedback','view feedback report'],
        'submenu' => [
            [
                'title' => 'Feedback`s',
                'route' => '/user-feedbacks',
                'permission' => ['view feedback']

            ],
            [
                'title' => 'Questions Templates',
                'route' => '/feedback',
                'permission' => ['create feedback']
            ],
            [
                'title' => 'Assign Feedback',
                'route' => '/feedback-assign',
                'permission' => ['assign feedback'],

            ],
            [
                'title' => 'Feedback Report',
                'route' => '/feedback-report',
                'permission' => ['view feedback report']
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
        'route' => 'gallery',
    ],
    [
        'title' => 'View',
        'icon' => 'ti ti-eye',
        'route' => 'javascript:void(0);',
        'permission' => ['view though','view appreciation','view birthday','view announcement', 'view event', 'view policy', 'view holiday', 'view reminder'],
        'submenu' => [
            [
                'title' => 'Thought Of The Day',
                'route' =>  '/thoughts_view',
                'permission' => ['view thought'],

            ],
            [
                'title' => ' Appreciation',
                'route' =>  'appreciation_view',
                'permission' => ['view appreciation']
            ],
            [
                'title' => 'Birthdays',
                'route' =>  'birthday_view',
                'permission' => ['view birthday'],
            ],
            [
                'title' => 'Announcement',
                'route' =>  'announcement_view',
                'permission' => ['view announcement'],
            ],
            [
                'title' => 'Company Policies ',
                'route' =>  '/view/company-policies',
                'permission' => ['view policy'],
                'badge' => "New",
            ],
            [
                'title' => 'Holidays',
                'route' =>  '/view-holidays',
                'permission' => ['view holiday'],
            ],
            [
                'title' => 'User Reminder List',
                'route' =>  'reminder',
                'permission' => ['view reminder'],
            ],
            [
                'title' => 'View MOM',
                'route' =>  '/view/moms',
                'permission' => ['view user moms'],
            ],
        ]
    ],
    [
        'title' => 'Others',
        'icon' => 'ti ti-rss',
        'route' => 'javascript:void(0);',
        'isActive' => ['others*'],
        'permission' => ['seen status report', 'create thought', 'create appreciation','create policy', 'create announcement','create banners','create event', 'create MOM'],
        'submenu' => [
            [
                'title' => 'Thoughts',
                'route' =>  'thoughts',
                'isActive' => [],
                'permission' => ['create thought'],

            ],
            [
                'title' => 'Appreciation',
                'route' =>  'appreciation',
                'isActive' => [],
                'permission' => ['create appreciation']

            ],
            [
                'title' => 'Policies',
                'route' =>  'others/policies',
                'isActive' => ['policy*'],
                'permission' => ['create policy'],
            ],
            [
                'title' => 'Announcement',
                'route' =>  'javascript:void(0);',
                'isActive' => [],
                'permission' => [],
                'submenu' => [
                    [
                        'title' => 'Announcements',
                        'route' => "/others/announcements",
                        'isActive' => [],
                        'permission' => ['create announcement'],
                    ],
                    [
                        'title' => 'Banner',
                        'route' => "banner",
                        'isActive' => [],
                        'permission' => ['create banners'],
                    ],
                ]
            ],
            [
                'title' => 'Events',
                'route' =>  '/others/events',
                'isActive' => ['events*'],
                'permission' => ['create event'],
            ],
            [
                'title' => 'MOM',
                'route' =>  '/others/moms',
                'isActive' => ['mom*'],
                'permission' => ['create MOM'],
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
                'permission' => ['view project tasks'],
            ],
            [
                'title' => 'Productivity Targets',
                'route' =>  'productivity-target',
                'isActive' => ['productivity-target*'],
                'permission' => ['view productivity task'],
            ]
        ]
    ],
    [
        'title' => 'Recruitments',
        'icon' => 'ti ti-target',
        'route' => 'javascript:void(0);',
        'isActive' => ['recruitments*'],
        'permission' => ['view job applications'],
        'submenu' => [
            [
                'title' => 'RRF',
                'route' =>  '/recruitments',
                'permission' => ['view job applications'],
            ],
            [
                'title' => 'Draft',
                'route' =>  '/recruitments/draft-list',
                'permission' => ['view job applications'],
            ],
        ]
    ],
    [
        'title' => 'Email',
        'icon' => 'ti ti-mail-forward',
        'route' => '/mail-boxes',
        'permission' => ['view emails','view starred','send mail','trashed mail']
    ],
    [
        'title' => 'Conference Hall',
        'icon' => 'ti ti-calendar-time',
        'route' => 'javascript:void(0);',
        'isActive' => ['conference-hall*'],
        'permission' => ['view bookings', 'view conference hall report'],
        'submenu' => [
            [
                'title' => 'Bookings',
                'route' =>  '/conferance-hall',
                'permission' => ['view bookings'],
            ],
            [
                'title' => 'Reports',
                'route' =>  '/conferance-hall/report',
                'permission' => ['view conference hall report'],
            ],
        ]
    ],
    [
        'title' => 'Jobs',
        'icon' => 'ti ti-mail-forward',
        'route' => '/jobs',
        'permission' => ['view jobs']
    ],
    [
        'title' => 'Tools',
        'icon' => 'ti ti-tools',
        'route' => 'javascript:void(0);',
        'isActive' => ['conference-hall*'],
        'permission' => ['view quick notes', 'view conference hall report'],
        'submenu' => [
            [
                'title' => 'Quick Note',
                'route' =>  '/tools/quick-note',
                'permission' => ['view quick notes'],
            ],
            [
                'title' => 'Event Calendar',
                'route' =>  '/tools/event-calendar',
                'permission' => ['view event calendar'],
            ],
            [
                'title' => 'KSP',
                'route' =>  'tools/ksp',
                'permission' => ['view KSP'],
            ],
        ]
    ],
    [
        'title' => 'e-Library',
        'icon' => 'ti ti-books',
        'route' => 'javascript:void(0);',
        'isActive' => ['e-library*'],
        'permission' => ['view e-library', 'issue books', 'view books'],
        'submenu' => [
            [
                'title' => 'Book Issue Register',
                'route' =>  '/e-library/issue-register',
                'permission' => ['issue books'],
            ],
            [
                'title' => 'Books',
                'route' =>  '/e-library/books',
                'permission' => ['view books'],
            ],
            [
                'title' => 'Books Categories',
                'route' =>  '/e-library/categories',
                'permission' => ['view books category'],
            ],
            [
                'title' => 'Reports',
                'route' =>  'javascript:void(0);',
                'isActive' => ['reports*'],
                'permission' => ['view e-library reports'],
                'submenu' => [
                    [
                        'title' => 'Issued Books',
                        'route' => "/e-library/reports/issue",
                        'permission' => ['view issued books report'],
                    ],
                    [
                        'title' => 'Pending Books',
                        'route' => "/e-library/reports/pending",
                        'permission' => ['view pending books report'],
                    ],
                    [
                        'title' => 'Damaged / Lost Books',
                        'route' => "/e-library/reports/damaged-lost",
                        'permission' => ['view damage or loss report'],
                        ''
                    ],
                ]
            ],
        ]
    ],
    [
        'title' => 'Ticket Raising',
        'icon' => 'ti ti-icons',
        'route' => 'tickets',
        'permission' => ['view tickets'],
    ],
    [
        'title' => 'Assets',
        'icon' => 'ti ti-apps',
        'route' => '/assets/dashboard',
        'permission' => ['view assets'],
    ],
    [
        'header' => 'Settings',
    ],
    [
        'title' => 'Settings',
        'icon' => 'ti ti-switch-3',
        'route' => 'javascript:void(0);',
        'permission' => [
            'change appearence','view department','assign open work' ,'manage roles',
            'view holiday', 'view shift time', 'change shift time', 'custom markout',
            'custom attendance', 'full day entry', 'custom work report entry', 'edit attendance',
            'leave approvals'
        ],
        'submenu' => [
            [
                'title' => 'Change appearence',
                'route' =>  'appearences',
                'permission' => ['change appearence'],
            ],
            [
                'title' => 'Departments',
                'route' =>  'departments',
                'permission' => ['view department'],
            ],
            [
                'title' => 'Assign Open Work',
                'route' =>  'assign_open_work',
                'permission' => ['assign open work'],
            ],
            [
                'title' => 'Role Management',
                'route' =>  'roles',
                'permission' => ['manage roles'],
            ],
            [
                'title' => 'Permission Management',
                'route' =>  'permissions',
                'permission' => ['manage permissions'],
            ],
            [
                'title' => 'Holiday',
                'route' =>  'holidays',
                'permission' => ['view holiday'],
            ],
            [
                'title' => 'Shift Time',
                'route' =>  '/settings/workshift',
                'permission' => ['view shift time'],
            ],
            [
                'title' => 'Change Shift Time',
                'route' =>  '/shift-times',
                'permission' => ['change shift time'],
            ],
            [
                'title' => 'Custom Markout',
                'route' =>  '/settings/custom-mark-out',
                'permission' => ['custom markout'],
            ],
            [
                'title' => 'Custom Attendance Entry',
                'route' =>  '/settings/custom-attendance-entry',
                'permission' => ['custom attendance'],
            ],
            [
                'title' => 'Fullday Entry',
                'route' =>  '/settings/full-day-attendance-entry',
                'permission' => ['full day entry'],
            ],
            [
                'title' => 'Custom Work Report Entry',
                'route' =>  '/settings/custom-work-report-entry',
                'permission' => ['custom work report entry'],
            ],
            [
                'title' => 'Edit Daily Attendance',
                'route' =>  '/settings/edit-daily-attendance',
                'permission' => ['edit attendance'],
            ],
            [
                'title' => 'Leave Approvals',
                'route' => '/leave_approver/list',
                'permission' => ['leave approvals'],
            ],
        ]
    ],
    [
        'title' => 'DB Backup',
        'icon' => 'ti ti-database-export',
        'route' => '/db-backup',
        'permission' => ['view db backup'],
    ],
];
