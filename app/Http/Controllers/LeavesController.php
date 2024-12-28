<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\LeavesAdmin;
use DB;
use DateTime;
use App\Models\Employee;
use App\Exports\LeavesExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class LeavesController extends Controller
{
    // leaves
    public function leaves()
    {
        // Total leave entitlement (set this based on your company policy)
        $totalLeaveEntitlement = 15; // Example value

        // Fetch leave data for employees
        $leaves = DB::table('leaves_admins')
            ->join('employees', 'employees.employee_id', '=', 'leaves_admins.user_id')
            ->select('leaves_admins.*', 
                     'employees.name as employee_name', 
                     'employees.position', 
                     'employees.department', 
                     'leaves_admins.from_date', 
                     'leaves_admins.to_date')   
            ->get();

        // Calculate remaining leave days for each employee
        foreach ($leaves as $leave) {
            // Fetch used leave days for the employee in the current year
            $usedLeaveDays = DB::table('leaves_admins')
                ->where('user_id', $leave->user_id) // Assuming user_id is the foreign key
                ->whereYear('from_date', date('Y')) // Filter by current year
                ->sum('day'); // Assuming 'day' stores the number of days taken

            // Calculate remaining leave days
            $remainingLeaveDays = $totalLeaveEntitlement - $usedLeaveDays;

            // Add remaining days to the leave object
            $leave->remaining_days = $remainingLeaveDays;
        }

        \Log::info('Leaves Data:', $leaves->toArray()); // Log the leaves data

        return view('form.leaves', compact('leaves'));
    }

    // save record
    public function saveRecord(Request $request)
    {
        $request->validate([
            'leave_type'   => 'required|string|max:255',
            'from_date'    => 'required|date',
            'to_date'      => 'required|date',
            'leave_reason' => 'required|string|max:255',
            'user_id'      => 'required|exists:employees,employee_id',
            'leave_status' => 'required|string',
            'remaining_days' => 'required|integer',
        ]);

        // Log the validated data
        \Log::info('Validated Data:', $request->all());

        DB::beginTransaction();
        try {
            // Calculate the number of days
            $from_date = new DateTime($request->from_date);
            $to_date = new DateTime($request->to_date);
            $days = $from_date->diff($to_date)->d + 1; // Include the start date
    
            // Fetch total leave entitlement and calculate remaining leave days
            $totalLeaveEntitlement = 15; // Set this based on your company policy
            $usedLeaveDays = DB::table('leaves_admins')
                ->where('user_id', $request->user_id)
                ->whereYear('from_date', date('Y'))
                ->sum('day'); // Assume 'day' stores the number of days taken
    
            // Calculate remaining leave days
            $remainingLeaveDays = $totalLeaveEntitlement - $usedLeaveDays - $days;
    
            // Check if remaining leave days are sufficient
            if ($remainingLeaveDays < 0) {
                Toastr::error('Not enough leave days available!', 'Error');
                return redirect()->back();
            }
    
            // Save the leave request
            $leaves = new LeavesAdmin();
            $leaves->user_id = $request->user_id;
            $leaves->leave_type = $request->leave_type;
            $leaves->from_date = $request->from_date;
            $leaves->to_date = $request->to_date;
            $leaves->day = $days;
            $leaves->leave_reason = $request->leave_reason;
            $leaves->leave_status = $request->leave_status; // Save leave status
            $leaves->remaining_days = $remainingLeaveDays; // Save remaining days
            $leaves->save();
    
            DB::commit();
            Toastr::success('Leave request submitted successfully!', 'Success');
    
            // Redirect to the leave report page or wherever appropriate
            return redirect()->route('leaves.report');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error saving leave request: ' . $e->getMessage()); // Log the error message
            Toastr::error('Failed to submit leave request.', 'Error');
            return redirect()->back();
        }
    }
    
    public function getRemainingLeaveDays($userId)
    {
        // Fetch total leave entitlement and calculate remaining leave days
        $totalLeaveEntitlement = 15; // Set this based on your company policy
        $usedLeaveDays = LeavesAdmin::where('user_id', $userId)
            ->whereYear('from_date', date('Y'))
            ->sum('day'); // Assume 'day' stores the number of days taken

        // Calculate remaining leave days
        $remainingLeaveDays = $totalLeaveEntitlement - $usedLeaveDays;

        return response()->json([
            'remaining_days' => $remainingLeaveDays,
        ]);
    }


    // edit record
    public function editRecordLeave(Request $request)
    {
        DB::beginTransaction();
        try {

            $from_date = new DateTime($request->from_date);
            $to_date = new DateTime($request->to_date);
            $day     = $from_date->diff($to_date);
            $days    = $day->d;

            $update = [
                'id'           => $request->id,
                'leave_type'   => $request->leave_type,
                'from_date'    => $request->from_date,
                'to_date'      => $request->to_date,
                'day'          => $days,
                'leave_reason' => $request->leave_reason,
            ];

            LeavesAdmin::where('id',$request->id)->update($update);
            DB::commit();
            Toastr::success('Updated Leaves successfully :)','Success');
            return redirect()->back();
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Update Leaves fail :)','Error');
            return redirect()->back();
        }
    }

    // delete record
    public function deleteLeave(Request $request)
    {
        $leave = LeavesAdmin::find($request->id);
        if (!$leave) {
            Toastr::error('Leave record not found.', 'Error');
            return redirect()->back();
        }

        $leave->delete();
        Toastr::success('Leave record deleted successfully!', 'Success');
        return redirect()->route('leaves.report'); // Redirect to the report page
    }

    // leaveSettings
    public function leaveSettings()
    {
        return view('form.leavesettings');
    }

    // leaves Employee
    public function leavesEmployee()
    {
        return view('form.leavesemployee');
    }

    // shiftscheduling
    public function shiftScheduLing()
    {
        return view('form.shiftscheduling');
    }

    // shiftList
    public function shiftList()
    {
        return view('form.shiftlist');
    }

    public function leaveReport()
    {
        $leaves = DB::table('leaves_admins')
                    ->join('employees', 'employees.employee_id', '=', 'leaves_admins.user_id')
                    ->select('leaves_admins.leave_type',
                             'leaves_admins.*', 
                             'employees.name as employee_name', 
                             'employees.position', 
                             'employees.department')
                    ->get();

        \Log::info('Leaves Report Data:', $leaves->toArray()); // Log the leaves data

        return view('reports.leavereports', compact('leaves'));
    }

    public function searchEmployee(Request $request)
    {
        $searchTerm = $request->input('q'); // Get the search term
        \Log::info('Search Term: ' . $searchTerm); // Log the search term
    
        $employees = Employee::where('name', 'LIKE', "%{$searchTerm}%")
                             ->orWhere('employee_id', 'LIKE', "%{$searchTerm}%")
                             ->get(['employee_id', 'name']); // Adjust fields as necessary
    
        \Log::info('Employees Found: ', $employees->toArray()); // Log the found employees
    
        return response()->json($employees); // Return employee data as JSON
    }
    
    public function exportExcel()
    {
        return Excel::download(new LeavesExport, 'leaves.xlsx');
    }

    public function exportPDF()
    {
        $leaves = LeavesAdmin::with('employee')->get(); // Fetch leaves with employee data

        $pdf = PDF::loadView('pdf.leaves_pdf', compact('leaves')); // Create PDF from view
        return $pdf->download('leaves.pdf'); // Download the PDF
    }
}
