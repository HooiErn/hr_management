<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Interviewer;
use App\Models\ApplyForJob;
use App\Models\AddJob; 
use App\Models\Employee;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Notifications\InterviewScheduled;
use App\Mail\HiredNotification;
use App\Mail\RejectedNotification;
use App\Models\Candidate;
use App\Models\Timesheet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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

            // Create or update the timesheet entry
            Timesheet::updateOrCreate(
                ['interviewer_id' => $interviewer->id, 'date' => Carbon::now()->format('Y-m-d')],
                ['scheduled_time' => $request->interview_datetime, 'status' => 'Scheduled']
            );

            // Prepare interview data
            $interviewData = [
                'name' => $interviewer->name,
                'interview_datetime' => $request->interview_datetime,
                'interview_type' => $request->interview_type
            ];

            // Generate a room ID
            $roomID = $interviewData['interview_type'] === 'online' ? mt_rand(1000, 9999) : null;

           // Send notification (this will trigger both the email and WhatsApp channels)
            $interviewer->notify(new InterviewScheduled($interviewData, $roomID));


            // Save the room ID in the database if needed
            // ...

            $applyForJob = ApplyForJob::where('ic_number', $interviewer->ic_number)->first();
            if ($applyForJob) {
                $applyForJob->update([
                    'interview_datetime' => $request->interview_datetime,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Interview scheduled and notifications email sent',
                'roomID' => $roomID,
            ]);

        } catch (\Exception $e) {
            \Log::error('Interview scheduling error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error scheduling interview: ' . $e->getMessage()
            ], 500);
        }
    }

    // public function sendNotifications($interviewer, $interviewData)
    // {
    //     // Send email notification
    //     //$interviewer->notify(new InterviewScheduled($interviewData));

    //     // Send WhatsApp notification (you can implement this function)
    //     $this->sendWhatsAppNotification($interviewer, $interviewData);
    // }

// //Whatsapp notification
//     public function sendWhatsAppNotification($interviewer, $interviewData)
//     {
//         $sid = env('TWILIO_SID');
//         $token = env('TWILIO_AUTH_TOKEN');
//         $twilio = new Client($sid, $token);
        
//         $message = "Hello {$interviewData['name']}, your interview is scheduled on {$interviewData['interview_datetime']} as a {$interviewData['interview_type']} interview. Please be available.";
//         dd(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'), env('TWILIO_WHATSAPP_FROM'));
//         try {
//             $twilio->messages->create("whatsapp:{$interviewer->phone_number}", [
//                 'from' => 'whatsapp:'. env('TWILIO_WHATSAPP_FROM'),
//                 'body' => $message
//             ]);
//         } catch (\Exception $e) {
//             \Log::error('Error sending WhatsApp message: ' . $e->getMessage());
//         }
//     }

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

    public function showResume($id)
    {
        $interviewer = Interviewer::findOrFail($id); // Find the corresponding interviewer record

        // Get the file path for the resume
        $filePath = public_path('assets/cv/' . $interviewer->cv_upload);

        // Check if the file exists
        if (file_exists($filePath)) {
            return response()->file($filePath); // Return the file to be opened in the browser
        } else {
            return abort(404); // File does not exist, return a 404 error
        }
    }
    //hired or rejected interviewer
    public function bulkAction(Request $request)
    {
        \Log::info('Bulk action started', $request->all());
        
        try {
            $data = $request->json()->all();
            
            $validator = Validator::make($data, [
                'action' => 'required|in:hired,rejected',
                'interviewers' => 'required|array',
                'interviewers.*' => 'exists:interviewers,id',
                'confirm' => 'required|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            if (!$data['confirm']) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Confirmation required'
                ], 400);
            }

            DB::beginTransaction();
            
            foreach ($data['interviewers'] as $interviewerId) {
                $interviewer = Interviewer::findOrFail($interviewerId);
                \Log::info('Processing interviewer: ' . $interviewer->name);

                if ($data['action'] === 'hired') {
                    // Generate employee ID
                    $employeeId = 'EMP' . str_pad(Employee::count() + 1, 4, '0', STR_PAD_LEFT); // Example ID generation
    
                    // Fetch job details based on the job_title
                    $job = AddJob::where('job_title', $interviewer->job_title)->first();
    
                    if ($job) {
                        // Transfer interviewer data to the employee table
                        $employee = new Employee();
                        $employee->employee_id = $employeeId;
                        $employee->name = $interviewer->name;
                        $employee->email = $interviewer->email;
                        $employee->age = $interviewer->age;
                        $employee->race = $interviewer->race;
                        $employee->highest_education = $interviewer->highest_education;
                        $employee->work_experiences = $interviewer->work_experiences;
                        $employee->ic_number = $interviewer->ic_number;
                        $employee->cv_upload = $interviewer->cv_upload;
                        $employee->birth_date = $interviewer->birth_date;
                        $employee->gender = $interviewer->gender;
                        $employee->phone_number = $interviewer->phone_number;
                        $employee->status = 'Active';
                        $employee->role_name = 'Staff';
                        $employee->position = $job->job_title;
                        $employee->department = $job->department;
                        $employee->salary = $request->salary ?? ""; 
                        $employee->job_type = $job->job_type;
                        $employee->company = env('APP_NAME');
                        $employee->join_date = now();
                        $employee->password = bcrypt('12345678');
                        $employee->save();
    
                        // Send Hired email with contract attachment
                        try {
                            Mail::to($interviewer->email)->send(new HiredNotification($interviewer));
                            \Log::info('Hired email with contract sent to: ' . $interviewer->email);
                        } catch (\Exception $e) {
                            \Log::error('Error sending email: ' . $e->getMessage());
                        }
                        $interviewer->delete();
                    }
                } elseif ($data['action'] === 'rejected') {
                    // Handle rejection logic
                    // Move interviewer data back to candidates table
                    $candidate = new Candidate();
                    $candidate->candidate_id = $interviewer->candidate_id;
                    $candidate->ic_number = $interviewer->ic_number;
                    $candidate->name = $interviewer->name;
                    $candidate->age = $interviewer->age;
                    $candidate->race = $interviewer->race;
                    $candidate->gender = $interviewer->gender;
                    $candidate->phone_number = $interviewer->phone_number;
                    $candidate->email = $interviewer->email;
                    $candidate->birth_date = $interviewer->birth_date;
                    $candidate->job_title = $interviewer->job_title;
                    $candidate->highest_education = $interviewer->highest_education;
                    $candidate->work_experiences = $interviewer->work_experiences;
                    $candidate->role_name = 'Candidate'; // Default role
                    $candidate->message = $interviewer->message;
                    $candidate->interview_datetime = null;
                    $candidate->cv_upload = $interviewer->cv_uploads;
                    $candidate->save();
    
                    // Send Rejected email
                    Mail::to($interviewer->email)->send(new RejectedNotification($interviewer));
                    $interviewer->delete();
                    \Log::info('Interviewer record deleted for: ' . $interviewer->name);
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Action applied successfully.']);

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error in bulkAction: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
    
    
          /*Search Interviewer*/
          public function search(Request $request)
          {
              $query = Interviewer::query();
          
              // Basic Search
              if ($request->filled('name')) {
                  $query->where('name', 'LIKE', '%' . $request->name . '%');
              }
              if ($request->filled('email')) {
                  $query->where('email', 'LIKE', '%' . $request->email . '%');
              }
              if ($request->filled('candidate_id')) {
                  $query->where('candidate_id', 'LIKE', '%' . $request->candidate_id . '%');
              }
          
              // Advanced Search
              if ($request->filled('job_title')) {
                  $query->where('job_title', 'LIKE', '%' . $request->job_title . '%');
              }
              if ($request->filled('gender')) {
                  $query->where('gender', $request->gender);
              }
              if ($request->filled('experience')) {
                  $query->where('work_experiences', '>=', $request->experience);
              }
              if ($request->filled('race')) {
                  $query->where('race', $request->race);
              }
          
              // Log the query for debugging
              \Log::info('Search Query:', ['query' => $query->toSql(), 'bindings' => $query->getBindings()]);
          
              $interviewers = $query->get();
              return response()->json(['interviewers' => $interviewers]);
          }
          
    public function sendEmail(Request $request)
    {
        // Validate the request data
        $request->validate([
            'candidate_id' => 'required|exists:interviewers,id',
            // Add other necessary validations
        ]);

        $interviewer = Interviewer::findOrFail($request->candidate_id);

        // Send the email with the contract
        Mail::to($interviewer->email)->send(new HiredNotification($interviewer));

        return response()->json(['success' => true]);
    }
}

