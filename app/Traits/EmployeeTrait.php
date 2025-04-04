<?php

namespace App\Traits;
use Carbon\Carbon;
use App\Models\Department;
use DateTime;

trait EmployeeTrait
{
    public function employeeDepartment($department_id)
    {
        return Department::where('id',$department_id)->pluck('department');

    }
}
