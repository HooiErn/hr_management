<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    protected $fillable = [
        'employee_id', 
        'punch_in',
        'punch_out',
        'break_duration',
        'overtime',
        'date', 
        'session_id',
        'location',
        'ip_address'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Helper method to check if current time is break time
    public function isBreakTime()
    {
        $now = Carbon::now();
        $startWork = Carbon::createFromTime(9, 0); // 9 AM
        $endWork = Carbon::createFromTime(17, 0);  // 5 PM
        return $now->between($startWork, $endWork);
    }

    // Helper method to check if it's regular work hours
    public function isWorkHours()
    {
        $punchInTime = Carbon::parse($this->punch_in);
        return $punchInTime->between(
            Carbon::createFromTime(8, 0),  // 8 AM
            Carbon::createFromTime(9, 30)  // 9:30 AM
        );
    }
}
