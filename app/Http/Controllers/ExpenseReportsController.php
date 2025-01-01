<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use DB;

class ExpenseReportsController extends Controller
{
    // view page
    public function index()
    {
        return view('reports.expensereport');
    }

    // view page
    public function invoiceReports()
    {
        return view('reports.invoicereports');
    }
    
    // daily report page
    public function dailyReport()
    {
        return view('reports.dailyreports');
    }

    // leave reports page
    public function leaveReport()
    {
        $leaves = DB::table('leaves_admins')
        ->join('employees', 'employees.employee_id', '=', 'leaves_admins.user_id')
        ->select('leaves_admins.leave_type',
                 'leaves_admins.*', 
                 'employees.name as employee_name', 
                 'employees.position', 
                 'employees.department',)
        ->get();

        $departments = DB::table('departments')
        ->select('department')
        ->distinct()
        ->get();

        \Log::info('Leaves Report Data:', $leaves->toArray()); // Log the leaves data

        return view('reports.leavereports', compact('leaves', 'departments'));
    }
}
