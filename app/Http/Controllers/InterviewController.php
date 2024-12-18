<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Interviewer;
use App\Models\ApplyForJob;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Notifications\InterviewScheduled;
use Twilio\Rest\Client;

class InterviewController extends Controller
{
    public function scheduleInterview(Request $request)
    {
        
        try {
            $request->validate([
                'candidate_id' => 'required|exists:interviewers,id',
                'interview_datetime' => 'required|date_format:Y-m-d H:i',
                'interview_type' => 'required|in:f2f,online'
            ]);
            

            $interviewer = Interviewer::findOrFail($request->candidate_id);
            $interviewer->update([
                'interview_datetime' => $request->interview_datetime,
                'interview_type' => $request->interview_type
            ]);

            // Prepare interview data
            $interviewData = [
                'name' => $interviewer->name,
                'interview_datetime' => $request->interview_datetime,
                'interview_type' => $request->interview_type
            ];

            // Send notifications
            $this->sendNotifications($interviewer, $interviewData);

            $applyForJob = ApplyForJob::where('ic_number', $interviewer->ic_number)->first();
            if ($applyForJob) {
                $applyForJob->update([
                    'interview_datetime' => $request->interview_datetime,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Interview scheduled and notifications sent'
            ]);

        } catch (\Exception $e) {
            \Log::error('Interview scheduling error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error scheduling interview: ' . $e->getMessage()
            ], 500);
        }
    }

    public function sendNotifications($interviewer, $interviewData)
    {
        // Send email notification
        //$interviewer->notify(new InterviewScheduled($interviewData));

        // Send WhatsApp notification (you can implement this function)
        $this->sendWhatsAppNotification($interviewer, $interviewData);
    }

//Whatsapp notification
    public function sendWhatsAppNotification($interviewer, $interviewData)
    {
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $twilio = new Client($sid, $token);
        
        $message = "Hello {$interviewData['name']}, your interview is scheduled on {$interviewData['interview_datetime']} as a {$interviewData['interview_type']} interview. Please be available.";
        dd(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'), env('TWILIO_WHATSAPP_FROM'));
        try {
            $twilio->messages->create("whatsapp:{$interviewer->phone_number}", [
                'from' => 'whatsapp:'. env('TWILIO_WHATSAPP_FROM'),
                'body' => $message
            ]);
        } catch (\Exception $e) {
            \Log::error('Error sending WhatsApp message: ' . $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required',
            'job_title' => 'required',
            'gender' => 'required|in:Male,Female',
            'ic_number' => 'required'
        ]);

        try {
            $interviewer = Interviewer::findOrFail($request->id);
            
            $interviewer->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'job_title' => $request->job_title,
                'gender' => $request->gender,
                'ic_number' => $request->ic_number
            ]);

            Toastr::success('Interviewer updated successfully!','Success');
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error('Error updating interviewer: ' . $e->getMessage());
            Toastr::error('Error updating interviewer: ' . $e->getMessage(), 'Error');
            return redirect()->back()->withInput();
        }
    }

    public function delete(Request $request)
    {
        try {
            $interviewer = Interviewer::findOrFail($request->id);
            $interviewer->delete();

            Toastr::success('Interviewer deleted successfully!','Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Error deleting interviewer: ' . $e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

    public function updateInterviewStatus(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|exists:interviewers,id',
                'status' => 'required|in:interviewed'
            ]);

            $interviewer = Interviewer::findOrFail($request->id);
            
            // Update status in both tables
            $interviewer->update(['status' => $request->status]);
            
            $applyForJob = ApplyForJob::where('ic_number', $interviewer->ic_number)->first();
            if ($applyForJob) {
                $applyForJob->update(['status' => $request->status]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Status update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating status'
            ], 500);
        }
    }

    public function showInterviewers()
    {
        $interviewers = Interviewer::all(); 

        return view('job.interviewer', compact('interviewers'));
    }
}

