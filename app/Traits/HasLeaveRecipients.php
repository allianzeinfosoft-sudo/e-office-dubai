<?php

// app/Traits/HasLeaveRecipients.php

namespace App\Traits;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Collection;

trait HasLeaveRecipients
{
    /**
     * Get the recipients (users who manage the current user).
     *
     * @return \Illuminate\Support\Collection
     */
    public function getLeaveRecipients($user_details): Collection
    {
        // $this is the current user
        if (!$user_details->reporting_to) {
            return collect();// no manager assigned
        }

        return Employee::where('user_id', $user_details->reporting_to)->pluck('id');
    }
}

