<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use PDF;
use App\Models\User;
use App\Models\ApplyForJob; 
use App\Models\Company;
use App\Models\Employee;
use App\Models\Interviewer;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    // main dashboard
    public function index()
    {
        $employeeCount = Employee::count();
        $interviewerCount = Interviewer::count();
        $leaveCount = DB::table('leaves_admins')
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        return view('dashboard.dashboard', compact('employeeCount', 'interviewerCount', 'leaveCount'));
    }

    public function showHomepage()
    {
        // Fetch open positions and company information
        $jobs = ApplyForJob::all(); 
        $company = Company::first(); 

        return view('home', compact('jobs', 'company'));
    }
    // employee dashboard
    public function emDashboard()
    {
        $dt        = Carbon::now();
        $todayDate = $dt->toDayDateTimeString();
        return view('dashboard.emdashboard',compact('todayDate'));
    }

    public function generatePDF(Request $request)
    {
        // $data = ['title' => 'Welcome to ItSolutionStuff.com'];
        // $pdf = PDF::loadView('payroll.salaryview', $data);
        // return $pdf->download('text.pdf');
        // selecting PDF view
        $pdf = PDF::loadView('payroll.salaryview');
        // download pdf file
        return $pdf->download('pdfview.pdf');
    }

    
    

}
