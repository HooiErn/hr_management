<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ChatController extends Controller
{
    public function handleMessage(Request $request)
    {
        try {
            $question = $request->input('question', '');

            if (stripos($question, 'job responsibilities') !== false) {
                $answer = $this->getJobResponsibilities($question);
            } elseif (stripos($question, 'how to apply') !== false) {
                $answer = "To apply for a job, please visit our careers page and submit your application online.";
            } elseif (stripos($question, 'interview tips') !== false) {
                $answer = "Please prepare your CV and profile and carefully review the 
                           requirements of the job you are applying for to find the right 
                           fit for yourself. We welcome any talents to join us!";
            } elseif (stripos($question, 'recommend jobs') !== false) {
                $answer = $this->recommendJobs($request->input('skills'));
            } elseif (stripos($question, 'contact customer service') !== false) {
                $answer = "If you need to contact customer service, please reach out via WhatsApp at [your phone number].";
            } else {
                // Check for job title similarity and provide links
                $relatedJobs = $this->getRelatedJobs($question);
                if ($relatedJobs) {
                    $answer = "Here are some related job titles: " . implode(", ", $relatedJobs);
                } else {
                    $answer = "I'm sorry, I didn't understand that. Can you please rephrase?";
                }
            }

            return response()->json(['answer' => $answer]);
        } catch (\Exception $e) {
            \Log::error('Error in ChatController:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Something went wrong!'], 500);
        }
    }

    private function getRelatedJobs($title)
    {
        // Logic to fetch related job titles from the database
        // Example: return Job::where('title', 'LIKE', "%$title%")->pluck('title')->toArray();
        return []; // Placeholder for actual implementation
    }
}


