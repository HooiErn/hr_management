<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class InterviewController extends Controller
{
    public function checkInterviewTime()
    {
        // Assuming interviewDatetime is the interview time retrieved from the database or a model
        $interviewDatetime = Carbon::parse('2024-11-15 15:30:00'); // Example interview time
        $currentTime = Carbon::now();

        // Check if the interview time is within the next 15 minutes
        if ($currentTime->diffInMinutes($interviewDatetime) <= 15 && $currentTime->lessThanOrEqualTo($interviewDatetime)) {
            // Trigger a notification
            session()->flash('notification', 'Your interview is about to start!');
        }

        return view('interviews.index'); // Return to the view where the notification is shown
    }
}
