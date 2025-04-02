<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LeaveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            'user_id' => [
                            'required',
                            'exists:users,id',
                            function ($attribute, $value, $fail) {

                                $startYear = request()->input('leave_from') ? date('Y', strtotime(request()->input('leave_from'))) : null;

                                $hasMatchingLeave = DB::table('leave_allocations')
                                    ->where('user_id', $value)
                                    ->where('year', $startYear)
                                    ->exists();
                                if (!$hasMatchingLeave) {
                                    $fail('The selected user has no allotted leave for the specified year.');
                                }
                            },
                        ],
            'leave_type' => [
                            'required',
                            Rule::in(['full_day', 'half_day', 'off_day']),
                            function ($attribute, $value, $fail) {

                                $startDate = request()->input('leave_from');
                                $endDate = request()->input('leave_to');
                                if ($value === 'half_day' && $startDate !== $endDate) {
                                    $fail('For half-day leave, the start and end dates must be the same.');
                                }
                            },
                        ],

            'leave_from' => 'required|date|before_or_equal:leave_to',
            'leave_to' => 'required|date|after_or_equal:leave_from',
            'reason' => 'nullable|string|max:255',
            // 'am_pm' => [
            //     function ($attribute, $value, $fail) {
            //         if (request()->input('leave_type') === 'half_day' && empty($value)) {
            //             $fail('Please select AM or PM for half-day leave.');
            //         }
            //     }
            // ],

        ];
    }
}
