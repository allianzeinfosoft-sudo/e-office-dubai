<?php

namespace App\Traits;
use Carbon\Carbon;
use App\Models\Department;
use DateTime;

trait EmployeeTrait
{
    public function employeeBranch($branch_id)
    {
        return $employee_branch = Department::where('id',$branch_id)->pluck('department');

    }
}
