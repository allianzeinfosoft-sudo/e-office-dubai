<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Appreciation;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FeedsController extends Controller
{
    public function show_feeds(Request $request)
    {
        if ($request->ajax()) {

            $today = Carbon::today();

            $birthdayEmployees = Employee::select('full_name', 'profile_image')
                ->whereMonth('dob', $today->month)
                ->whereDay('dob', $today->day)
                ->get();

                $birthdayFeed = null;

                if ($birthdayEmployees->isNotEmpty()) {
                    $birthdayFeed = [
                        'type' => 'birthday',
                        'display_date' => $today->format('d-F'),
                        'sort_date' => $today->format('Y-m-d'),
                        'employees' => $birthdayEmployees->map(function ($employee) {
                            return [
                                'full_name' => $employee->full_name,
                                'profile_image' => $employee->profile_image ?: '/profile_pics/default-avatar.png',
                            ];
                        }),
                    ];
                }


            $rawAnnouncements = Announcement::whereDate('display_start_date', '<=', $today)
                ->whereDate('display_end_date', '>=', $today)
                ->get();

            $announcements = collect();
            if ($rawAnnouncements->isNotEmpty()) {
                $announcements = $rawAnnouncements->map(function ($announcement) use ($today) {
                     $displayDate = Carbon::parse($announcement->display_start_date);
                    return [
                        'type' => 'announcement',
                        'display_date' => $today->format('d-F'),
                        'sort_date' => $displayDate->format('Y-m-d'),
                        'title' => $announcement->name_announcement,
                        'message' => $announcement->description,
                        'image' => $announcement->picture ?? '',
                        'display_start_date' => $announcement->display_start_date,
                        'create_date' =>  date('d-m-Y',strtotime($announcement->created_at))
                    ];
                });
            }


            // Get all appreciations for today

            $rawAppreciations = Appreciation::whereDate('display_date', '<=', $today)
                ->whereDate('display_end_date', '>=', $today)
                ->get();

            $appreciations = collect();

            if ($rawAppreciations->isNotEmpty()) {
                $appreciations = $rawAppreciations->map(function ($appreciation) {
                    $displayDate = Carbon::parse($appreciation->display_date);
                    $employeeDetails = [];

                    // Get IDs from the 'appreciant' string
                    $ids = array_filter(explode(',', $appreciation->appreciant));

                    if (!empty($ids)) {
                        $employees = Employee::with('user:id,email')->whereIn('user_id', $ids)->get(['id', 'user_id', 'full_name', 'profile_image']);

                        $employeeDetails = $employees->map(function ($employee) {
                            return [
                                'full_name' => $employee->full_name,
                                'email' => $employee->user?->email ?? '',
                                'profile_image' => $employee->profile_image ?: '/assets/img/avatars/default.png',
                            ];
                        })->toArray();
                    }

                    return [
                        'type' => 'appreciation',
                        'display_date' => \Carbon\Carbon::parse($appreciation->display_date)->format('d-F'),
                        'sort_date' => $displayDate->format('Y-m-d'),
                        'employees' => $employeeDetails,
                        'message' => $appreciation->appreciation_details,
                        'image' => $appreciation->picture,
                    ];
                });
            }


            // Combine all feeds
            $feeds = collect();

            // Add birthday feed if available
            if (!is_null($birthdayFeed)) {
                $feeds->push($birthdayFeed);
            }

            $feeds = $feeds->merge($announcements)->merge($appreciations);

            // Merge announcements if not empty
            // if ($announcements->isNotEmpty()) {
            //     $feeds = $feeds->merge($announcements);
            // }

            // Merge appreciations if not empty
            // if ($appreciations->isNotEmpty()) {
            //     $feeds = $feeds->merge($appreciations);
            // }

            // Ensure consistent ordering or re-indexing
            $feeds = $feeds->sortByDesc('sort_date')->values();
            return response()->json(['data' => $feeds]);
        }

        $data['meta_title'] = 'Feeds';
        return view('settings.view_feeds', $data);

    }
}
