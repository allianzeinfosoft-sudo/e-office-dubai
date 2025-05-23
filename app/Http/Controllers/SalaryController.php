<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Salary;
use App\Models\User;
use App\Traits\DateFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Traits\EmployeeTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
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

    public function fetch_salary_slip(Request $request)
    {

        $user = Auth::user();
        $query = Salary::with('employee');

        // Only HR can filter by username
            if($request->filled('username'))
            {
                $query->where('user_id', $request->username);

            }
            if (!$user->hasRole('HR')) {
                $query->where('user_id', $user->id);
            }

            if ($request->filled('select2Month')) {
                $query->where('salary_slip_month', $request->select2Month);
            }

            if ($request->filled('select2Year')) {
                $query->where('salary_slip_year', $request->select2Year);
            }
        $salary_slips = $query->get()->map(function ($salary_slip) {
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

        $response = response()->json(['data' => $salary_slips]);
        $json_data = json_decode($response->getContent(), true)['data'];

        return json_encode(['data' => $json_data]);
    }


    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:pdf|max:10240', // Allow PDF up to 10MB
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()]);
        }
        else
        {


            if ($request->hasFile('file'))
                {
                    $file = $request->file('file');
                    $userId = $file->getClientOriginalName();
                    $userId = explode('.', $userId);
                    $userId = $userId[0] ?? null;

                    if (!is_numeric($userId)) {
                        return response()->json(['status' => 'error', 'message' => 'No user exist belongs to this file']);
                    }
                    else
                    {
                        $userCheck = Employee::where('employeeID', $userId)->first();
                        if($userCheck)
                        {
                            $fileName = time() . '_' . $file->getClientOriginalName();
                            $file->storeAs('uploads', $fileName, 'public'); // Save in storage/app/public/uploads

                            // Set current year and month
                            $year = date('Y');
                            $month = date('n');

                            if ($month == 1) {
                                $month = 12;
                                $year = $year - 1;
                            } else {
                                $month = $month - 1;
                            }

                            $existing = Salary::where('user_id', $userCheck->user_id)
                                        ->where('salary_slip_year', $year)
                                        ->where('salary_slip_month', $month)
                                        ->first();

                            if ($existing) {
                                // Delete the old file
                                if (Storage::disk('public')->exists('uploads/' . $existing->salary_slip)) {
                                    Storage::disk('public')->delete('uploads/' . $existing->salary_slip);
                                }

                                // Update the record
                                $existing->update([
                                    'salary_slip' => $fileName,
                                ]);
                            } else {
                                // Create a new record
                                Salary::create([
                                    'user_id' => $userCheck->user_id,
                                    'salary_slip' => $fileName,
                                    'salary_slip_year' => $year,
                                    'salary_slip_month' => $month,
                                ]);

                                $file->storeAs('uploads', $fileName, 'public');
                            }

                            return response()->json([
                                'status' => 'success',
                                'message' => $existing ? 'File updated successfully!' : 'File uploaded successfully!',
                                'filename' => $fileName
                            ]);
                        }else{
                            return response()->json(['
                            status' => 'error',
                            'message' => 'User does not exist belongs to this file']);
                        }


                    }

                }else{
                    return response()->json(['
                            status' => 'error',
                            'message' => 'No file uploaded']);
                }

        }


    }

}
