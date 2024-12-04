<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Candidate;
use App\Models\ApplyForJob;

class ChatController extends Controller
{

    public function handleMessage(Request $request)
    {
        try {
            // Log the request input for debugging
            \Log::info('ChatController Request:', $request->all());
    
            // Original controller code...
        } catch (\Exception $e) {
            \Log::error('Error in ChatController:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Something went wrong!'], 500);
        }
        
        // Get input data and the current step
        $step = $request->input('step', 0);
        $answer = $request->input('answer', '');
        $jobTitle = $request->input('job_title', 'N/A'); // Default value if job_title is not provided
       

        // Define the questions
        $questions = [
            'name' => 'What is your name?',
            'age' => 'What is your age?',
            'race' => 'What is your race?',
            'gender' => 'What is your gender?', 
            'birth_date' => 'What is your birth date? (YYYY-MM-DD)',
            'phone_number' => 'What is your phone number?',
            'email' => 'What is your email address?',
            'highest_education' => 'What is your highest level of education?',
            'work_experiences' => 'How many years of work experience do you have? (answer in number)',
            'cv_upload' => 'Please upload your CV.',  
        ];

           // Start with a greeting
            if ($step === 0) {
                return response()->json([
                    'question' => $questions[0],
                    'step' => 1,  // Proceed to the first real question
                ]);
            }

        // Get the keys of the questions (to keep track of the order)
        $questionKeys = array_keys($questions);

        // Retrieve session data (answers so far)
        $sessionData = session('chat_data', []);

        // Store the current answer if it exists
        if ($step > 0 && isset($questionKeys[$step - 1])) {
            $currentKey = $questionKeys[$step - 1];
            $sessionData[$currentKey] = $answer;
            session(['chat_data' => $sessionData]);
        }

        // If all questions are answered, show the confirmation screen
        if ($step >= count($questions)) {
            $confirmationData = [];
            foreach ($sessionData as $key => $value) {
                $confirmationData[] = ['question' => $questions[$key], 'answer' => $value];
            }

            return response()->json([
                'confirmation' => $confirmationData,
                'step' => 'confirm',  // Mark that we're at the confirmation step
            ]);
        }

        // Proceed to the next question if it's available
        $nextQuestionKey = $questionKeys[$step] ?? null;
        if ($nextQuestionKey) {
            return response()->json([
                'question' => $questions[$nextQuestionKey],
                'step' => $step + 1, // Move to the next question
            ]);
        }

        return response()->json([
            'message' => 'Your application is complete.',
        ]);
    }

    public function saveApplication(Request $request)
    {
        // Create the candidate record
        $candidate = new Candidate();
        $candidate->candidate_id = $this->generateUniqueCandidateId();
        $candidate->name = $request->name;
        $candidate->gender = $request->gender;  // Gender instead of sex
        $candidate->email = $request->email;
        $candidate->job_title = $request->job_title;
        $candidate->birth_date = $request->birth_date;
        $candidate->age = $request->age;
        $candidate->race = $request->race;
        $candidate->phone_number = $request->phone_number;
        $candidate->highest_education = $request->highest_education;
        $candidate->work_experiences = $request->work_experiences;
        $candidate->message = $request->message;
        $candidate->cv_upload = $request->cv_upload;
        $candidate->interview_datetime = $request->interview_datetime;
        $candidate->role_name = 'Candidate';  // Default role for applicants
        $candidate->save();

        return response()->json([
            'message' => 'Your application has been saved successfully!',
            'candidate_id' => $candidate->candidate_id,
        ]);
    }
    
    public function uploadCv(Request $request)
    {
        // Handle file upload logic
        $file = $request->file('cv');
        if ($file) {
            $path = $file->store('cv_uploads');
            return response()->json(['message' => 'CV uploaded successfully', 'path' => $path]);
        }

        return response()->json(['message' => 'No file uploaded'], 400);
    }

    private function generateUniqueCandidateId()
    {
        return 'CAND-' . strtoupper(uniqid());
    }
}


