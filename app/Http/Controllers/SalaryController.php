<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SalaryController extends Controller
{
    public function view_salary_slip()
    {
        return view('salary.view_salary_slip');
    }
}
