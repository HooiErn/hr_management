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
            $step = $request->input('step', 0);
            $answer = $request->input('answer', '');
            $confirm = $request->input('confirm', false);
            $sessionData = session('chat_data', []);

            if ($confirm) {
                $this->saveApplication($sessionData);
                return response()->json([
                    'message' => 'Your application has been submitted successfully.',
                    'status' => 'complete'
                ]);
            }

            if ($step > 0 && !empty($answer)) {
                $currentQuestion = $this->getNextQuestion($step - 1);
                
                if ($currentQuestion['field'] === 'work_experiences') {
                    if (!is_numeric($answer) || $answer < 0) {
                        return response()->json([
                            'error' => true,
                            'message' => 'Please enter a valid number for work experience.',
                            'question' => $currentQuestion['question']
                        ]);
                    }
                    $answer = (int)$answer;
                }

                $currentField = $currentQuestion['field'];
                $sessionData[$currentField] = $answer;
                session(['chat_data' => $sessionData]);
            }

            $nextQuestion = $this->getNextQuestion($step);
            
            if ($nextQuestion) {
                return response()->json([
                    'question' => $nextQuestion['question'],
                    'options' => $nextQuestion['options'] ?? null,
                    'field' => $nextQuestion['field'],
                    'type' => $nextQuestion['type'] ?? 'text',
                    'step' => $step + 1
                ]);
            } else {
                $summary = $this->generateSummary($sessionData);
                return response()->json([
                    'summary' => $summary,
                    'needsConfirmation' => true
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error in ChatController:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Something went wrong!'], 500);
        }
    }

    private function saveApplication($data)
    {
        try {
            $candidate = new Candidate();
            $candidate->candidate_id = $this->generateUniqueCandidateId();
            $candidate->name = $data['name'];
            $candidate->gender = $data['gender'];
            $candidate->email = $data['email'];
            $candidate->job_title = $data['job_title'];
            $candidate->birth_date = $data['birth_date'];
            $candidate->age = $data['age'];
            $candidate->race = $data['race'];
            $candidate->phone_number = $data['phone_number'];
            $candidate->highest_education = $data['highest_education'];
            $candidate->work_experiences = $data['work_experiences'];
            $candidate->message = $data['message'];
            $candidate->cv_upload = $data['cv_upload'];
            $candidate->interview_datetime = $data['interview_datetime'];
            $candidate->role_name = 'Candidate';
            $candidate->save();

            $applyForJob = new ApplyForJob();
            $applyForJob->job_title = $data['job_title'];
            $applyForJob->name = $data['name'];
            $applyForJob->age = $data['age'];
            $applyForJob->race = $data['race'];
            $applyForJob->gender = $data['gender'];
            $applyForJob->birth_date = $data['birth_date'];
            $applyForJob->phone_number = $data['phone_number'];
            $applyForJob->email = $data['email'];
            $applyForJob->highest_education = $data['highest_education'];
            $applyForJob->work_experiences = $data['work_experiences'];
            $applyForJob->message = $data['message'];
            $applyForJob->cv_upload = $data['cv_upload'];
            $applyForJob->interview_datetime = $data['interview_datetime'];
            $applyForJob->save();
        } catch (\Exception $e) {
            \Log::error('Error in ChatController:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Something went wrong!'], 500);
        }
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

    private function getNextQuestion($step)
    {
        $questions = [
            [
                'field' => 'name',
                'question' => 'What is your name?'
            ],
            [
                'field' => 'gender',
                'question' => 'What is your gender?',
                'options' => ['Male', 'Female', 'Other']
            ],
            [
                'field' => 'email',
                'question' => 'What is your email address?'
            ],
            [
                'field' => 'phone_number',
                'question' => 'What is your phone number?(e.g. 0123456789)'
            ],
            [
                'field' => 'birth_date',
                'question' => 'What is your birth date? (YYYY-MM-DD)'
            ],
            [
                'field' => 'highest_education',
                'question' => 'What is your highest level of education?',
                'options' => ['Secondary', 'Foundation', 'Diploma', 'Degree', 'Master', 'PhD']
            ],
            [
                'field' => 'work_experiences',
                'question' => 'How many years of work experience do you have?',
                'type' => 'number',
                'validation' => 'numeric'
            ]
        ];

        return $questions[$step] ?? null;
    }

    private function getFieldNameByStep($step)
    {
        $fields = ['name', 'gender', 'email', 'phone_number', 'birth_date', 'highest_education', 'work_experiences'];
        return $fields[$step] ?? null;
    }
}


