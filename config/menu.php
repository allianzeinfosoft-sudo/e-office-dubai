<?php
return [
    [
        'title' => 'Dashboards',
        'icon' => 'ti ti-smart-home',
        'route' => 'home',
        /* 'badge' => 3,
        'submenu' => [
            [
                'title' => 'Analytics',
                'route' => 'index.html',
                'submenu' => [
                    [
                        'title' => 'Performance',
                        'route' => 'analytics/performance'
                    ],
                    [
                        'title' => 'Revenue',
                        'route' => 'analytics/revenue'
                    ]
                ]
            ],
            [
                'title' => 'CRM',
                'route' => 'dashboards-crm.html'
            ],
            [
                'title' => 'eCommerce',
                'route' => 'dashboards-ecommerce.html'
            ],
        ] */
    ],
    [
        'header' => 'Modules'
    ],
    [
        'title' => 'Attendance',
        'icon' => 'ti ti-mail',
        'route' => 'attendance'
    ],
    [
        'title' => 'Works',
        'icon' => 'ti ti-layout-sidebar',
        'route' => 'javascript:void(0);',
        // 'badge' => 3,
        'submenu' => [
            [
                'title' => 'Work Status',
                'route' => '#',
            ],
            [
                'title' => 'SDU Project Statu',
                'route' => '#'
            ],   
            [
                'title' => 'Temporary Status',
                'route' => '#'
            ],   
            [
                'title' => 'Entry Open Markin',
                'route' => '#'
            ],   
        ]
    ],
    [
        'title' => 'Reports',
        'icon' => 'ti ti-file',
        'route' => 'javascript:void(0);',
        'submenu' => [
            [
                'title' => 'My Overview',
                'route' => '#',
            ],
            [
                'title' => 'My Attendance',
                'route' => '#'
            ],   
            [
                'title' => 'My Work Report',
                'route' => '#'
            ],   
            [
                'title' => 'My Emergency Report',
                'route' => '#'
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
        'icon' => 'ti ti-file-dollar',
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
        'icon' => 'ti ti-file-dollar',
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
        'icon' => 'ti ti-file-dollar',
        'route' => 'javascript:void(0);',
        'submenu' => [
            [
                'title' => 'Apply Leave',
                'route' =>  '#',
            ],  
            [
                'title' => 'Leave Status',
                'route' =>  '#',
            ],  
            [
                'title' => 'Leave Summary',
                'route' =>  '#',
            ],  
        ]
    ],
    [
        'title' => 'Gallery',
        'icon' => 'ti ti-mail',
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
        'icon' => 'ti ti-users',
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
        'title' => 'Feedback',
        'icon' => 'ti ti-users',
        'route' => 'javascript:void(0);',
        'submenu' => [
            [
                'title' => 'Feedback Form',
                'route' =>  '#',
            ],  
            [
                'title' => 'Feedback Reviews',
                'route' =>  '#',
            ],    
        ]
    ],
    [
        'title' => 'My Projects ',
        'icon' => 'ti ti-mail',
        'route' => '#',
    ],
    [
        'title' => 'My Account',
        'icon' => 'ti ti-users',
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
        'icon' => 'ti ti-users',
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
        'title' => 'Jobs',
        'icon' => 'ti ti-users',
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
        'icon' => 'ti ti-users',
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
        'title' => 'Shifts',
        'icon' => 'ti ti-settings',
        'route' => 'javascript:void(0);',
        'submenu' => [
            [
                'title' => 'Shift List ',
                'route' =>  url('workshift'),
            ],  
        ]
    ],
    [
        'title' => 'Roles & Permissions',
        'icon' => 'ti ti-settings',
        'route' => 'javascript:void(0);',
        'submenu' => [
            [
                'title' => 'Roles',
                'route' =>  url('roles'),
            ],  
            [
                'title' => 'Permission',
                'route' =>  url('permissions'),
            ],   
        ]
    ],
    [
        'title' => 'Users',
        'icon' => 'ti ti-users',
        'route' => 'javascript:void(0);',
        'submenu' => [
            [
                'title' => 'List',
                'route' =>  url('users'),
            ], 
        ]
    ],
    [
        'title' => 'Departments',
        'icon' => 'ti ti-mail',
        'route' => url('departments'),
    ],
];

