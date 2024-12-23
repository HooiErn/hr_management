<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function punchIn(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id', // Assuming you have an employees table
        ]);

        $attendance = Attendance::updateOrCreate(
            [
                'employee_id' => $request->employee_id,
                'date' => Carbon::today()->toDateString(),
            ],
            [
                'punch_in' => Carbon::now(),
            ]
        );

        return response()->json(['message' => 'Punched in successfully!', 'attendance' => $attendance]);
    }

    public function punchOut(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id', // Assuming you have an employees table
        ]);

        $attendance = Attendance::where('employee_id', $request->employee_id)
            ->where('date', Carbon::today()->toDateString())
            ->first();

        if ($attendance) {
            $attendance->punch_out = Carbon::now();
            $attendance->save();

            return response()->json(['message' => 'Punched out successfully!', 'attendance' => $attendance]);
        }

        return response()->json(['message' => 'No punch in record found for today.'], 404);
    }

    public function viewAttendance(Request $request)
    {
        $attendances = Attendance::with('employee') // Assuming you have a relationship defined
            ->where('date', $request->date ?? Carbon::today()->toDateString())
            ->get();

        return view('form.attendance', compact('attendances'));
    }
}
