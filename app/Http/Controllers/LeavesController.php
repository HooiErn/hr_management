<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\LeavesAdmin;
use DB;
use DateTime;
use App\Models\Employee;

class LeavesController extends Controller
{
    // leaves
    public function leaves()
    {
        $leaves = DB::table('leaves_admins')
                    ->join('users', 'users.user_id', '=', 'leaves_admins.user_id')
                    ->select('leaves_admins.*', 'users.position','users.name','users.avatar')
                    ->get();

        return view('form.leaves',compact('leaves'));
    }
    // save record
    public function saveRecord(Request $request)
    {
        $request->validate([
            'leave_type'   => 'required|string|max:255',
            'from_date'    => 'required|date',
            'to_date'      => 'required|date',
            'leave_reason' => 'required|string|max:255',
            'user_id'      => 'required|exists:users,user_id',
        ]);

        DB::beginTransaction();
        try {
            // Calculate the number of days
            $from_date = new DateTime($request->from_date);
            $to_date = new DateTime($request->to_date);
            $days = $from_date->diff($to_date)->d + 1; // Include the start date

            // Save the leave request for the employee
            $leaves = new LeavesAdmin;
            $leaves->user_id = $request->user_id;
            $leaves->leave_type = $request->leave_type;
            $leaves->from_date = $request->from_date;
            $leaves->to_date = $request->to_date;
            $leaves->day = $days;
            $leaves->leave_reason = $request->leave_reason;
            $leaves->status = 'pending'; // Set status to pending
            $leaves->save();

            DB::commit();
            Toastr::success('Leave request submitted successfully!', 'Success');
            
            // Redirect to the leave report page or wherever appropriate
            return redirect()->route('leaves.report');
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Failed to submit leave request.', 'Error');
            return redirect()->back();
        }
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
        try {

            LeavesAdmin::destroy($request->id);
            Toastr::success('Leaves admin deleted successfully :)','Success');
            return redirect()->back();
        
        } catch(\Exception $e) {

            DB::rollback();
            Toastr::error('Leaves admin delete fail :)','Error');
            return redirect()->back();
        }
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
                    ->join('users', 'users.user_id', '=', 'leaves_admins.user_id')
                    ->select('leaves_admins.*', 'users.name', 'users.position')
                    ->get();

        return view('reports.leavereports', compact('leaves'));
    }

    public function searchEmployee(Request $request)
    {
        $searchTerm = $request->input('q'); // Get the search term
        $employees = Employee::where('name', 'LIKE', "%{$searchTerm}%")
                             ->orWhere('employee_id', 'LIKE', "%{$searchTerm}%")
                             ->get(['employee_id', 'name']); // Adjust fields as necessary

        return response()->json($employees);
    }
}
