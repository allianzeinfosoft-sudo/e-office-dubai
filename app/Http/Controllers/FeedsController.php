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
                    return [
                        'type' => 'announcement',
                        'display_date' => $today->format('d-F'),
                        'title' => $announcement->name_announcement,
                        'message' => $announcement->description,
                        'image' => $announcement->picture ?? '',
                        'display_start_date' => $announcement->display_start_date,
                        'create_date' =>  date('d-m-Y',strtotime($announcement->created_at))
                    ];
                });
            }


            // Get all appreciations for today
            $today = now();
            $rawAppreciations = Appreciation::whereMonth('display_date', $today->month)
                ->whereDay('display_date', $today->day)
                ->get();

            $appreciations = collect();

            if ($rawAppreciations->isNotEmpty()) {
                $appreciations = $rawAppreciations->map(function ($appreciation) {
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

            // Merge announcements if not empty
            if ($announcements->isNotEmpty()) {
                $feeds = $feeds->merge($announcements);
            }

            // Merge appreciations if not empty
            if ($appreciations->isNotEmpty()) {
                $feeds = $feeds->merge($appreciations);
            }

            // Ensure consistent ordering or re-indexing
            $feeds = $feeds->values();
            return response()->json(['data' => $feeds]);
        }

        $data['meta_title'] = 'Feeds';
        return view('settings.view_feeds', $data);

    }
}
