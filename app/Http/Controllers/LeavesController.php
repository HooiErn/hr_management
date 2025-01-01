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
            'user_id' => 'required',
            'leave_type' => 'required',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'leave_reason' => 'required',
            'leave_status' => 'required|in:paid,unpaid',
            'remaining_days' => 'required',
        ]);

        try {
            // Calculate number of days (inclusive of both from_date and to_date)
            $from_date = new DateTime($request->from_date);
            $to_date = new DateTime($request->to_date);
            $days = $from_date->diff($to_date)->days + 1; // Add 1 to include both start and end dates

            // Calculate remaining days directly
            $totalLeaveEntitlement = 15;
            $usedLeaveDays = LeavesAdmin::where('user_id', $request->user_id)
                ->whereYear('from_date', date('Y'))
                ->sum('day');
            $remainingDays = $totalLeaveEntitlement - $usedLeaveDays;

            $leave = new LeavesAdmin;
            $leave->user_id = $request->user_id;
            $leave->leave_type = $request->leave_type;
            $leave->from_date = $request->from_date;
            $leave->to_date = $request->to_date;
            $leave->day = $days; // Use calculated days
            $leave->leave_reason = $request->leave_reason;
            $leave->leave_status = $request->leave_status;
            $leave->remaining_days = $remainingDays;
            $leave->save();

            Toastr::success('Leave has been created successfully :)','Success');
            return redirect()->back();
        } catch(\Exception $e) {
            Toastr::error('Error occurred while creating leave :(','Error');
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
        return redirect()->route('form/leave/reports/page'); // Redirect to the report page
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
        return Excel::download(new LeavesExport, 'leaves_employeeList.xlsx');
    }

    public function exportPDF()
    {
        $leaves = LeavesAdmin::with('employee')->get(); // Fetch leaves with employee data

        $pdf = PDF::loadView('pdf.leaves_pdf', compact('leaves')); // Create PDF from view
        return $pdf->download('leaves_employeeList.pdf'); // Download the PDF
    }

    public function updateRecord(Request $request)
    {
        $request->validate([
            'leave_type' => 'required',
            'from_date' => 'required|date',
            'to_date' => 'required|date',
            'leave_reason' => 'required',
            'leave_status' => 'required|in:paid,unpaid',
        ],[
            'to_date.after_or_equal' => 'Leave To date cannot be earlier than Leave From date'
        ]);

        try {
            $leave = LeavesAdmin::find($request->id);
            $leave->leave_type = $request->leave_type;
            $leave->from_date = $request->from_date;
            $leave->to_date = $request->to_date;
            $leave->leave_reason = $request->leave_reason;
            $leave->leave_status = $request->leave_status;
            $leave->save();

            Toastr::success('Leave has been updated successfully :)','Success');
            return redirect()->back();
        } catch(\Exception $e) {
            Toastr::error('Error occurred while updating leave :(','Error');
            return redirect()->back();
        }
    }

    public function searchLeaves(Request $request)
    {
        try {
            $query = LeavesAdmin::query()
                ->join('employees', 'employees.employee_id', '=', 'leaves_admins.user_id')
                ->select('leaves_admins.*', 
                        'employees.name as employee_name', 
                        'employees.position', 
                        'employees.department');

            if ($request->filled('employee_name')) {
                $query->where('employees.name', 'LIKE', '%' . $request->employee_name . '%')
                      ->orWhere('employees.employee_id', 'LIKE', '%' . $request->employee_name . '%');
            }

            if ($request->filled('leave_type')) {
                $query->where('leaves_admins.leave_type', $request->leave_type);
            }

            if ($request->filled('leave_status')) {
                $query->where('leaves_admins.leave_status', $request->leave_status);
            }

            $leaves = $query->get();

            return response()->json([
                'success' => true,
                'leaves' => $leaves->map(function($leave) {
                    return [
                        'id' => $leave->id,
                        'employee_name' => $leave->employee_name,
                        'position' => $leave->position,
                        'leave_type' => $leave->leave_type,
                        'from_date' => $leave->from_date,
                        'to_date' => $leave->to_date,
                        'day' => $leave->day,
                        'leave_reason' => $leave->leave_reason,
                        'leave_status' => $leave->leave_status,
                        'user_id' => $leave->user_id
                    ];
                })
            ]);
        } catch (\Exception $e) {
            \Log::error('Search error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error occurred while searching: ' . $e->getMessage()
            ], 500);
        }
    }

}
