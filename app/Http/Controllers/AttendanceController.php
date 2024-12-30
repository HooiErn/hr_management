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

      foreach ($attendances as $attendance) {
          if ($attendance->punch_in) {
              $attendance->punch_in = Carbon::parse($attendance->punch_in);
          }
          if ($attendance->punch_out) {
              $attendance->punch_out = Carbon::parse($attendance->punch_out);
          }

          // Calculate worked hours and overtime
          if ($attendance->punch_in && $attendance->punch_out) {
              $attendance->worked_hours = $attendance->punch_in->diffInMinutes($attendance->punch_out); // in minutes
              $attendance->overtime = $this->calculateOvertime($attendance->punch_in, $attendance->punch_out); // Calculate overtime
              \Log::info('Attendance Record:', [
                'punch_in' => $attendance->punch_in,
                'punch_out' => $attendance->punch_out,
                'worked_minutes' => $attendance->worked_hours,
                'overtime' => $attendance->overtime,
            ]);
          } else {
              $attendance->worked_hours = 0;
              $attendance->overtime = 0; // Ensure overtime is set to 0 if no punch out
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

        // Log the incoming request data
        \Log::info('PunchOut Request Data: ', $request->all());

        // Find the latest punch in record for today
        $attendance = Attendance::where('employee_id', $request->employee_id)
            ->where('date', Carbon::today()->toDateString())
            ->whereNull('punch_out') 
            ->first();

        // Log the attendance record found
        \Log::info('Attendance Record Found: ', [$attendance]);

        if ($attendance) {
            try {
                $attendance->punch_out = Carbon::now();
                $attendance->save();
                $attendance->overtime = $this->calculateOvertime($attendance->punch_in, $attendance->punch_out);
                $attendance->location = 'FBIhotline';
                $attendance->save();



                return response()->json([
                    'message' => 'Punched out successfully!',
                    'attendance' => $attendance,
                ]);
            } catch (\Exception $e) {
                \Log::error('Error during punch out: ' . $e->getMessage());
                return response()->json(['message' => 'An error occurred while punching out.'], 500);
            }
        }

        return response()->json(['message' => 'No punch in record found for today.'], 404);
    }

    private function calculateBreakDuration($punchIn, $punchOut)
    {
        // Return 0 to indicate no break time is calculated
        return 0; 
    }
    private function calculateOvertime($punchIn, $punchOut)
    {
        $overtimeStartTime = Carbon::createFromTime(18, 0); // 6 PM
        $nextDayStartTime = Carbon::createFromTime(9, 0)->addDay(); // 9 AM next day

        // Calculate total worked minutes
        $workedMinutes = $punchIn->diffInMinutes($punchOut);

        // If punch out is after 6 PM, calculate overtime
        if ($punchOut->greaterThan($overtimeStartTime)) {
            // Calculate minutes worked after 6 PM
            $overtimeMinutes = $punchOut->diffInMinutes($overtimeStartTime);
            return min($overtimeMinutes, $workedMinutes); // Ensure we don't exceed total worked minutes
        }

        // If punch in is before 9 AM and punch out is after 6 PM, count those minutes as overtime
        if ($punchIn->lessThan($nextDayStartTime) && $punchOut->greaterThan($overtimeStartTime)) {
            return $workedMinutes; // All worked minutes are considered overtime
        }

        return 0; // No overtime if conditions are not met
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
