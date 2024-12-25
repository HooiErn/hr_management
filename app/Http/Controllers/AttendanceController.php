<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceController extends Controller
{

  // attendance admin
  public function attendanceIndex()
  {
      return view('form.attendance');
  }

  // attendance employee
  public function viewAttendance(Request $request)
  {
      $attendances = Attendance::where('employee_id', auth()->user()->id)
          ->where('date', Carbon::today()->toDateString())
          ->get();
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
        // Assuming break time is calculated as the time between punch in and punch out
        // You can adjust this logic based on your requirements
        return Carbon::parse($punchOut)->diffInMinutes(Carbon::parse($punchIn));
    }

    private function calculateOvertime($punchIn, $punchOut)
    {
        $workingEndTime = Carbon::createFromTime(18, 0); // 6 PM
        $punchOutTime = Carbon::parse($punchOut);

        if ($punchOutTime->greaterThan($workingEndTime)) {
            return $punchOutTime->diffInMinutes($workingEndTime);
        }

        return 0;
    }


}
