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
            'break_duration'
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
        $employeeId = $attendance->employee_id;
        $date = $attendance->date;

        if (!isset($attendanceData[$employeeId][$date])) {
            $attendanceData[$employeeId][$date] = [
                'date' => $date,
                'punch_in' => Carbon::parse($attendance->punch_in)->format('h:i A'),
                'punch_out' => $attendance->punch_out ? Carbon::parse($attendance->punch_out)->format('h:i A') : '--',
                'break_duration' => 0,
                'production' => 0,
                'overtime' => 0,
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
            $attendanceData[$employeeId][$date]['overtime'] = $data['overtime'] / 60;
            $attendanceData[$employeeId][$date]['break_duration'] = $data['break_duration'] / 60;
        }
    }

    return view('form.attendance', compact('attendanceData', 'month', 'year', 'employees', 'searchDate'));
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
              
              // Calculate worked hours and overtime
              $productionMinutes = $attendance->punch_in->diffInMinutes($attendance->punch_out);
              
              // Subtract break duration if exists
              $productionMinutes -= ($attendance->break_duration ?? 0);
              
              $attendance->production = $productionMinutes;
              
              // Calculate overtime (anything over 8 hours)
              $regularHours = 8 * 60; // 8 hours in minutes
              if ($productionMinutes > $regularHours) {
                  $attendance->overtime = $productionMinutes - $regularHours;
              } else {
                  $attendance->overtime = 0;
              }
              
              // Save the calculated values to database
              $attendance->save();
              
              \Log::info('Attendance Record:', [
                  'punch_in' => $attendance->punch_in,
                  'punch_out' => $attendance->punch_out,
                  'worked_minutes' => $attendance->production,
                  'overtime' => $attendance->overtime,
              ]);
          } else {
              $attendance->worked_hours = 0;
              $attendance->overtime = 0;
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
        $attendance->save();

        \Log::info('Punch In Created:', [
            'attendance_id' => $attendance->id,
            'punch_in' => $attendance->punch_in,
            'session_id' => $attendance->session_id
        ]);

        return response()->json(['message' => 'Punched in successfully!', 'attendance' => $attendance]);
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
            $punchIn = Carbon::parse($attendance->punch_in);
            
            // Calculate total minutes worked
            $productionMinutes = $punchIn->diffInMinutes($punchOut);
            
            // Get hours for punch in
            $punchInHour = (int)$punchIn->format('H');
            
            // Set overtime equal to production minutes if:
            // - Time is between midnight and 8 AM (0-7) OR
            // - Time is between 5 PM and midnight (17-23)
            $overtime = $productionMinutes; // Since these times are all in overtime period
            
            \Log::info('Detailed Time Calculations:', [
                'punch_in_time' => $punchIn->format('H:i:s'),
                'punch_in_hour' => $punchInHour,
                'punch_out_time' => $punchOut->format('H:i:s'),
                'production_minutes' => $productionMinutes,
                'is_overtime_period' => 'Yes - Before 8 AM',
                'overtime_minutes' => $overtime
            ]);

            // Update database with explicit values
            $updated = DB::table('attendances')
                ->where('id', $attendance->id)
                ->update([
                    'punch_out' => $punchOut->format('Y-m-d H:i:s'),
                    'production' => $productionMinutes,
                    'overtime' => $overtime, // This should now be equal to production minutes
                    'session_id' => session()->getId()
                ]);

            \Log::info('Final Values Saved:', [
                'attendance_id' => $attendance->id,
                'production' => $productionMinutes,
                'overtime' => $overtime,
                'update_success' => $updated
            ]);

            return response()->json([
                'message' => 'Punched out successfully!',
                'attendance' => [
                    'punch_out' => $punchOut->format('Y-m-d H:i:s'),
                    'production' => $productionMinutes,
                    'overtime' => $overtime,
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

    private function calculateOvertime($punchIn, $punchOut)
    {
        $regularEndTime = Carbon::createFromTime(17, 0); // 5 PM
        
        // If punch out is after 5 PM
        if ($punchOut->greaterThan($regularEndTime)) {
            // If punch in was before 5 PM
            if ($punchIn->lessThan($regularEndTime)) {
                return $punchOut->diffInMinutes($regularEndTime);
            }
            // If both punch in and out were after 5 PM
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

 
}
