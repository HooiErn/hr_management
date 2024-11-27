<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Candidate;
use App\Models\ApplyForJob;

class ChatController extends Controller
{
    private $questions = [
        'job_title' => 'What is the job title you are applying for?',
        'name' => 'What is your name?',
        'age' => 'What is your age?',
        'race' => 'What is your race?',
        'gender' => 'What is your gender?',
        'birth_date' => 'What is your birth date? (YYYY-MM-DD)',
        'phone_number' => 'What is your phone number?',
        'email' => 'What is your email address?',
        'highest_education' => 'What is your highest education level?',
        'work_experiences' => 'Please describe your work experiences.',
        'message' => 'Do you have any additional message?',
        'cv_upload' => 'Please upload your CV.',
        'interview_datetime' => 'When would you prefer to have an interview? (YYYY-MM-DD HH:MM)',
    ];

    public function handleMessage(Request $request)
    {
        $step = $request->input('step', 0);
        $answer = $request->input('answer', '');
        $jobTitle = $request->input('job_title', 'N/A'); // Default value if job_title is not provided
    
        // Define the questions
        $questions = [
            'job_title' => 'What is the job title you are applying for?',
            'name' => 'What is your name?',
            'age' => 'What is your age?',
            'race' => 'What is your race?',
            'gender' => 'What is your gender?',
            'birth_date' => 'What is your birth date? (YYYY-MM-DD)',
            'phone_number' => 'What is your phone number?',
            'email' => 'What is your email address?',
            'highest_education' => 'What is your highest education level?',
            'work_experiences' => 'Please describe your work experiences.',
            'message' => 'Do you have any additional message?',
            'cv_upload' => 'Please upload your CV.',
            'interview_datetime' => 'When would you prefer to have an interview? (YYYY-MM-DD HH:MM)',
        ];
    
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
    

    public function uploadCv(Request $request)
    {
        if ($request->hasFile('cv')) {
            $filePath = $request->file('cv')->store('cvs');
            $sessionData = session('chat_data', []);
            $sessionData['cv_upload'] = $filePath;
            session(['chat_data' => $sessionData]);

            return response()->json(['message' => 'CV uploaded successfully.']);
        }

        return response()->json(['message' => 'Failed to upload CV.'], 400);
    }

    private function generateUniqueCandidateId()
    {
        return 'CAND-' . strtoupper(uniqid());
    }
}


