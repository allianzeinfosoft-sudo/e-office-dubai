<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Models\User;
use App\Traits\DateFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Traits\EmployeeTrait;
use ZipArchive;

class SalaryController extends Controller
{
    use EmployeeTrait;
    use DateFormatter;

    public function view_salary_slip()
    {

        $users = Cache::remember('users', now()->addMinutes(10), function () {
            return User::all();
        });

        return view('salary.view_salary_slip',compact('users'));
    }

    public function fetch_salary_slip()
    {
        $salary_slip = Salary::with('employee')->get()->map(function ($salary_slip) {
            return [
                'id' => $salary_slip->id,
                'user_id' => $salary_slip->user_id,
                'full_name' => $salary_slip->employee->full_name,
                'pf_no' => $salary_slip->employee->pf_no,
                'department' =>  $this->employeeDepartment($salary_slip->employee->department_id),
                'salary_slip' => $salary_slip->salary_slip,
                'salary_slip_month' => $this->getMonthNames($salary_slip->salary_slip_month).'-'.$salary_slip->salary_slip_year,
                'created_date' => $salary_slip->created_at->format("d M Y, g:i A"),
            ];
        });

        $response = response()->json(['data' => $salary_slip]);
        $json_data = json_decode($response->getContent(), true)['data'];
        return json_encode(['data' => $json_data]);
    }


    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:zip|max:51200', // 50MB limit
        ]);

        if ($request->hasFile('file')) {

            $file = $request->file('file');
            $fileName = time() . '-' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads', $fileName, 'public');

            return response()->json(['message' => 'File uploaded successfully', 'path' => $filePath]);
        }

        return response()->json(['error' => 'File upload failed'], 400);
    }

}
