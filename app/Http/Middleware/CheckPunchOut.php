<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\Attendance;

class CheckPunchOut
{
    public function handle($request, Closure $next)
    {
        if ($request->is('logout') && auth()->check()) {
            $hasPendingPunchOut = Attendance::where('employee_id', auth()->id())
                ->where('date', Carbon::today()->toDateString())
                ->whereNull('punch_out')
                ->exists();

            if ($hasPendingPunchOut) {
                return redirect()->back()->with('error', 'Please punch out before logging out.');
            }
        }

        return $next($request);
    }
} 