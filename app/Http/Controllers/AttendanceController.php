<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AttendanceController extends Controller
{

  // attendance admin
  public function attendanceIndex(Request $request)
  {
    // Get the current month and year or use the request values
    $month = $request->input('month', date('m'));
    $year = $request->input('year', date('Y'));
    $searchDate = $request->input('date');
    
    if ($searchDate) {
        $searchDate = Carbon::parse($searchDate)->format('Y-m-d');
    }

    // Get all employees
    $employees = Employee::all();
    
    // Build the base query
    $query = Attendance::query()
        ->select(
            'employee_id',
            'date',
            'punch_in',
            'punch_out',
            'break_duration',
            'overtime'
        )
        ->with('employee');

    // Apply date filter
    if ($searchDate) {
        $query->whereDate('date', $searchDate);
    } else {
        $query->whereYear('date', $year)
              ->whereMonth('date', $month);
    }

    $attendances = $query->get();

    // Group attendance by employee
    $attendanceData = [];
    foreach ($attendances as $attendance) {
        \Log::info("Raw attendance data:", [
            'employee_id' => $attendance->employee_id,
            'date' => $attendance->date,
            'overtime' => $attendance->overtime,
            'raw_record' => $attendance->toArray()
        ]);

        $employeeId = $attendance->employee_id;
        $date = $attendance->date;

        if (!isset($attendanceData[$employeeId][$date])) {
            // Add null checks for overtime
            $overtimeMinutes = $attendance->overtime ?? 0;
            $overtimeHours = floor($overtimeMinutes / 60);
            $overtimeMinutes = $overtimeMinutes % 60;
            $overtimeFormatted = sprintf('%02d:%02d', $overtimeHours, $overtimeMinutes);

            $attendanceData[$employeeId][$date] = [
                'date' => $date,
                'punch_in' => Carbon::parse($attendance->punch_in)->format('h:i A'),
                'punch_out' => $attendance->punch_out ? Carbon::parse($attendance->punch_out)->format('h:i A') : '--',
                'break_duration' => 0,
                'production' => 0,
                'overtime' => $overtimeMinutes, // Raw minutes for calculations
                'overtime_formatted' => $overtimeFormatted, // Formatted HH:MM
                'employee' => $attendance->employee
            ];
        }

        // Calculate production and overtime
        if ($attendance->punch_out) {
            $punchIn = Carbon::parse($attendance->punch_in);
            $punchOut = Carbon::parse($attendance->punch_out);
            $productionMinutes = $punchOut->diffInMinutes($punchIn);
            
            // Subtract break duration if exists
            $productionMinutes -= ($attendance->break_duration ?? 0);
            
            // Add to total production
            $attendanceData[$employeeId][$date]['production'] += $productionMinutes;

            // Calculate overtime (anything over 8 hours)
            $regularHours = 8 * 60; // 8 hours in minutes
            if ($productionMinutes > $regularHours) {
                $attendanceData[$employeeId][$date]['overtime'] += ($productionMinutes - $regularHours);
            }
        }

        // Add break duration
        $attendanceData[$employeeId][$date]['break_duration'] += ($attendance->break_duration ?? 0);

        // Update punch times (keep earliest punch in and latest punch out)
        $currentPunchIn = Carbon::parse($attendanceData[$employeeId][$date]['punch_in']);
        $newPunchIn = Carbon::parse($attendance->punch_in);
        if ($newPunchIn->lt($currentPunchIn)) {
            $attendanceData[$employeeId][$date]['punch_in'] = $newPunchIn->format('h:i A');
        }

        if ($attendance->punch_out) {
            $currentPunchOut = $attendanceData[$employeeId][$date]['punch_out'] !== '--' 
                ? Carbon::parse($attendanceData[$employeeId][$date]['punch_out'])
                : null;
            $newPunchOut = Carbon::parse($attendance->punch_out);
            if (!$currentPunchOut || $newPunchOut->gt($currentPunchOut)) {
                $attendanceData[$employeeId][$date]['punch_out'] = $newPunchOut->format('h:i A');
            }
        }
    }

    // Convert minutes to hours for display
    foreach ($attendanceData as $employeeId => $dates) {
        foreach ($dates as $date => $data) {
            $attendanceData[$employeeId][$date]['production'] = $data['production'] / 60;
            $attendanceData[$employeeId][$date]['break_duration'] = $data['break_duration'] / 60;
            
            // Format total overtime for the day
            $totalOvertimeHours = floor($data['overtime'] / 60);
            $totalOvertimeMinutes = $data['overtime'] % 60;
            $attendanceData[$employeeId][$date]['overtime_formatted'] = 
                sprintf('%02d:%02d', $totalOvertimeHours, $totalOvertimeMinutes);
        }
    }

    return view('form.attendance', compact('attendanceData', 'month', 'year', 'employees', 'searchDate'));
  }

  // attendance employee
  public function viewAttendance(Request $request)
  {
      // Get employee with ID EMP0001
      $employeeId = \App\Models\Employee::where('employee_id', 'EMP0002')->first()->id;

      $attendances = Attendance::where('employee_id', $employeeId)
          ->where('date', Carbon::today()->toDateString())
          ->get();

      // Pass both the numeric ID and employee_id to the view
      $employee = \App\Models\Employee::find($employeeId);
      
      return view('form.attendanceemployee', compact('attendances', 'employeeId', 'employee'));
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

        // Check if employee ID is valid
        if (!$request->employee_id) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid employee ID'
            ], 400);
        }

        // Check if already punched in today
        $existingPunchIn = Attendance::where('employee_id', $request->employee_id)
            ->whereDate('date', Carbon::today())
            ->whereNull('punch_out')
            ->first();

        if ($existingPunchIn) {
            return response()->json([
                'success' => false,
                'message' => 'Already punched in today'
            ], 400);
        }

        // Get client IP and WiFi info
        $clientIP = $request->ip();
        $wifiName = $request->input('wifi_name', 'Unknown');
        
        // Create attendance with all fields explicitly set
        $attendance = new Attendance();
        $attendance->employee_id = $request->employee_id;
        $attendance->date = Carbon::today()->toDateString();
        $attendance->punch_in = Carbon::now()->format('Y-m-d H:i:s');
        $attendance->location = $wifiName;
        $attendance->ip_address = $clientIP;
        $attendance->session_id = session()->getId();
        
        try {
            $attendance->save();
            
            \Log::info('Punch In Created:', [
                'attendance_id' => $attendance->id,
                'punch_in' => $attendance->punch_in,
                'session_id' => $attendance->session_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Punched in successfully!', 
                'attendance' => $attendance
            ]);
        } catch (\Exception $e) {
            \Log::error('Punch In Failed:', [
                'error' => $e->getMessage(),
                'employee_id' => $request->employee_id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to punch in. Please try again.'
            ], 500);
        }
    }

    public function punchOut(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
        ]);

        $attendance = Attendance::where('employee_id', $request->employee_id)
            ->where('date', Carbon::today()->toDateString())
            ->whereNull('punch_out')
            ->first();

        if ($attendance) {
            $punchOut = Carbon::now();
            
            $calculations = $this->calculateProductionAndOvertime(
                $attendance->punch_in,
                $punchOut
            );
            
            $updated = DB::table('attendances')
                ->where('id', $attendance->id)
                ->update([
                    'punch_out' => $punchOut->format('Y-m-d H:i:s'),
                    'production' => $calculations['production'],
                    'overtime' => $calculations['overtime'],
                    'session_id' => session()->getId()
                ]);

            \Log::info('Final Values Saved:', [
                'attendance_id' => $attendance->id,
                'production' => $calculations['production'],
                'overtime' => $calculations['overtime'],
                'update_success' => $updated
            ]);

            return response()->json([
                'message' => 'Punched out successfully!',
                'attendance' => [
                    'punch_out' => $punchOut->format('Y-m-d H:i:s'),
                    'production' => $calculations['production'],
                    'overtime' => $calculations['overtime'],
                    'session_id' => session()->getId()
                ]
            ]);
        }

        return response()->json(['message' => 'No punch in record found for today.'], 404);
    }

    private function calculateBreakDuration($punchIn, $punchOut)
    {
        $breakStart = Carbon::createFromTime(9, 0); // 9 AM
        $breakEnd = Carbon::createFromTime(17, 0);  // 5 PM
        
        // If punch in and out are within break hours
        if ($punchIn->between($breakStart, $breakEnd) && $punchOut->between($breakStart, $breakEnd)) {
            return $punchIn->diffInMinutes($punchOut);
        }
        
        return 0;
    }
    

    public function search(Request $request)
    {
        try {
            $query = Attendance::with('employee');
            $month = $request->input('month', date('m'));
            $year = $request->input('year', date('Y'));

            // Apply search filters
            if ($request->employee_name) {
                $query->whereHas('employee', function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->employee_name . '%');
                });
            }

            if ($request->date) {
                $query->whereDate('date', $request->date);
            } else {
                // If no specific date is provided, use month and year
                $query->whereYear('date', $year)
                      ->whereMonth('date', $month);
            }

            $attendances = $query->get();
            $employees = Employee::all();

            // Group attendance by employee (same format as attendanceIndex)
            $attendanceData = [];
            foreach ($attendances as $attendance) {
                $attendanceData[$attendance->employee_id][$attendance->date] = $attendance;
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'attendanceData' => $attendanceData,
                        'month' => $month,
                        'year' => $year,
                        'employees' => $employees
                    ]
                ]);
            }

            return view('form.attendance', compact('attendanceData', 'month', 'year', 'employees'));
        } catch (\Exception $e) {
            \Log::error('Attendance search error: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'An error occurred while searching']);
            }
            return back()->with('error', 'An error occurred while searching');
        }
    }

    private function calculateProductionAndOvertime($punchIn, $punchOut, $breakDuration = 0)
    {
        $punchIn = Carbon::parse($punchIn);
        $punchOut = Carbon::parse($punchOut);
        
        // Calculate total minutes worked
        $productionMinutes = $punchOut->diffInMinutes($punchIn);
        $productionMinutes -= ($breakDuration ?? 0);
        
        // Calculate overtime based on time of day
        $punchInHour = (int)$punchIn->format('H');
        
        // If work is done during overtime hours (before 8 AM or after 5 PM)
        if ($punchInHour < 8 || $punchInHour >= 17) {
            $overtime = $productionMinutes;
        } else {
            // Regular hours calculation (over 8 hours)
            $regularHours = 8 * 60; // 8 hours in minutes
            $overtime = max(0, $productionMinutes - $regularHours);
        }
        
        return [
            'production' => $productionMinutes,
            'overtime' => $overtime
        ];
    }

}
