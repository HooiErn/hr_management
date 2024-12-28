<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceController extends Controller
{

  // attendance admin
  public function attendanceIndex(Request $request)
  {
    // Get the current month and year or use the request values
    $month = $request->input('month', date('m'));
    $year = $request->input('year', date('Y'));

    // Fetch attendance data for all employees for the selected month and year
    $attendances = Attendance::with('employee')
        ->whereYear('date', $year)
        ->whereMonth('date', $month)
        ->get();

    // Group attendance by employee
    $attendanceData = [];
    foreach ($attendances as $attendance) {
        $attendanceData[$attendance->employee_id][$attendance->date] = $attendance;
    }

    return view('form.attendance', compact('attendanceData', 'month', 'year'));
  }

  // attendance employee
  public function viewAttendance(Request $request)
  {
      $attendances = Attendance::where('employee_id', auth()->user()->id)
          ->where('date', Carbon::today()->toDateString())
          ->get();

      // Parse punch_in and punch_out to Carbon instances
      foreach ($attendances as $attendance) {
          if ($attendance->punch_in) {
              $attendance->punch_in = Carbon::parse($attendance->punch_in);
          }
          if ($attendance->punch_out) {
              $attendance->punch_out = Carbon::parse($attendance->punch_out);
          }
      }

      return view('form.attendanceemployee', compact('attendances'));
  }

    public function checkToday(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
        ]);

        // Get the latest attendance record for today
        $attendance = Attendance::where('employee_id', $request->employee_id)
            ->where('date', Carbon::today()->toDateString())
            ->orderBy('created_at', 'desc')
            ->first();

        // Determine if the user has punched in
        $hasPunchedIn = $attendance && $attendance->punch_in && !$attendance->punch_out;

        return response()->json(['hasPunchedIn' => $hasPunchedIn]);
    }

    public function punchIn(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
        ]);

        // Create a new attendance record
        $attendance = Attendance::create([
            'employee_id' => $request->employee_id,
            'date' => Carbon::today()->toDateString(),
            'punch_in' => Carbon::now(),
        ]);

        return response()->json(['message' => 'Punched in successfully!', 'attendance' => $attendance]);
    }

    public function punchOut(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
        ]);

        // Find the latest punch in record for today
        $attendance = Attendance::where('employee_id', $request->employee_id)
            ->where('date', Carbon::today()->toDateString())
            ->whereNull('punch_out') // Ensure we are punching out the latest punch in
            ->first();

        if ($attendance) {
            $attendance->punch_out = Carbon::now();
            $attendance->save();

            // Calculate break time and overtime
            $breakDuration = $this->calculateBreakDuration($attendance->punch_in, $attendance->punch_out);
            $overtime = $this->calculateOvertime($attendance->punch_in, $attendance->punch_out);

            $attendance->break_duration = $breakDuration;
            $attendance->overtime = $overtime;
            $attendance->save();

            return response()->json(['message' => 'Punched out successfully!', 'attendance' => $attendance]);
        }

        return response()->json(['message' => 'No punch in record found for today.'], 404);
    }

    private function calculateBreakDuration($punchIn, $punchOut)
    {
        $punchInTime = Carbon::parse($punchIn);
        $punchOutTime = Carbon::parse($punchOut);

        // If punch out is before 9 AM, no break is counted
        if ($punchOutTime->lessThanOrEqualTo(Carbon::createFromTime(9, 0))) {
            return 0;
        }

        // If punch in is after 9 AM, count the time from punch in to punch out
        if ($punchInTime->greaterThanOrEqualTo(Carbon::createFromTime(9, 0))) {
            return $punchInTime->diffInMinutes($punchOutTime);
        }

        // If punch in is before 9 AM, count the time from 9 AM to punch out
        return Carbon::createFromTime(9, 0)->diffInMinutes($punchOutTime);
    }

    private function calculateOvertime($punchIn, $punchOut)
    {
        $punchOutTime = Carbon::parse($punchOut);
        $workingEndTime = Carbon::createFromTime(18, 0); // 6 PM

        // If punch out is before 6 PM, no overtime is counted
        if ($punchOutTime->lessThanOrEqualTo($workingEndTime)) {
            return 0;
        }

        // Calculate overtime as the difference between punch out and 6 PM
        return $punchOutTime->diffInMinutes($workingEndTime);
    }

    public function search(Request $request)
    {
        
        $request->validate([
            'employee_name' => 'nullable|string|max:255',
            'month' => 'nullable|integer|min:1|max:12',
            'year' => 'nullable|integer|min:2000|max:2100',
        ]);

       
        $employeeName = $request->input('employee_name');
        $month = $request->input('month');
        $year = $request->input('year');

        
        $attendanceData = Attendance::with('employee')
            ->when($employeeName, function ($query) use ($employeeName) {
                return $query->whereHas('employee', function ($q) use ($employeeName) {
                    $q->where('name', 'like', '%' . $employeeName . '%');
                });
            })
            ->when($month, function ($query) use ($month) {
                return $query->whereMonth('date', $month);
            })
            ->when($year, function ($query) use ($year) {
                return $query->whereYear('date', $year);
            })
            ->get();

        
        return response()->json(['attendanceData' => $attendanceData]);
    }

}
