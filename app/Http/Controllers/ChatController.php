<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Job;


class ChatController extends Controller
{
    public function handleMessage(Request $request)
    {
        try {
            $userMessage = strtolower($request->input('message'));

            // Handle "how to apply" question
            if (strpos($userMessage, 'how to apply') !== false) {
                return response()->json([
                    'success' => true,
                    'message' => "Here's how to apply for our jobs:\n\n" .
                                "1. Browse our job listings and click on the position you're interested in\n" .
                                "2. Click the 'Apply Now' button on the job details page\n" .
                                "3. Fill in your personal information and upload your resume\n" .
                                "4. Submit your application\n\n" .
                                "Our team will review your application and contact you if you're shortlisted. Good luck! 🍀"
                ]);
            }

            // Handle "interview tips" question
            if (strpos($userMessage, 'interview tips') !== false) {
                return response()->json([
                    'success' => true,
                    'message' => "Here are some helpful interview tips:\n\n" .
                                "1. Research our company thoroughly before the interview\n" .
                                "2. Prepare relevant examples of your work experience\n" .
                                "3. Dress professionally and arrive 10-15 minutes early\n" .
                                "4. Bring extra copies of your resume\n" .
                                "5. Prepare thoughtful questions about the role and company\n" .
                                "6. Follow up with a thank-you email after the interview\n\n" .
                                "Good luck with your interview! 🌟"
                ]);
            }

            // Handle "company info" question
            if (strpos($userMessage, 'intro your company') !== false || strpos($userMessage, 'company info') !== false) {
                return response()->json([
                    'success' => true,
                    'message' => "Welcome to our company! 🏢\n\n" .
                                "We are a leading organization committed to innovation and excellence. " .
                                "Our company focuses on creating value for our customers while providing " .
                                "great opportunities for our employees to grow and develop.\n\n" .
                                "Key highlights:\n" .
                                "• Established presence in the industry\n" .
                                "• Strong focus on employee development\n" .
                                "• Competitive benefits package\n" .
                                "• Positive work culture\n\n" .
                                "Would you like to know more about our current job openings? 💼"
                ]);
            }

            // Handle "contact customer service" question
            if (strpos($userMessage, 'contact customer service') !== false) {
                return response()->json([
                    'success' => true,
                    'message' => "Here's how you can reach our customer service team:\n\n" .
                                "📧 Email: support@company.com\n" .
                                "📞 Phone: +60123456789\n" .
                                "💬 WhatsApp: <a href='https://wa.me/60123456789' target='_blank'>Click here to chat with us on WhatsApp</a>\n" .
                                "⏰ Hours: Monday-Friday, 9AM-5PM\n\n" .
                                "Our team typically responds within 24 hours. For urgent matters, " .
                                "please contact us via WhatsApp or call us directly during business hours.",
                    'isHtml' => true
                ]);
            }

            // Add this before your existing experience check
            if (strpos($userMessage, 'no experience') !== false || 
                strpos($userMessage, 'without experience') !== false || 
                strpos($userMessage, 'entry level') !== false) {
                
                \Log::info('Searching for no experience jobs');
                
                $availableJobs = DB::table('add_jobs')
                    ->where('status', 'Open')
                    ->where('expired_date', '>', now())
                    ->where(function($query) {
                        $query->where('experience', '0')
                              ->orWhere('experience', '')
                              ->orWhereNull('experience')
                              ->orWhere('experience', 'like', '%no%')
                              ->orWhere('experience', 'like', '%entry%');
                    })
                    ->get();

                \Log::info('Found no experience jobs:', ['count' => $availableJobs->count(), 'jobs' => $availableJobs]);

                if ($availableJobs->isEmpty()) {
                    return response()->json([
                        'success' => true,
                        'message' => "Currently, there are no entry-level positions available. Please try checking back later or search for other positions."
                    ]);
                }

                $response = "Here are positions that don't require prior experience:\n\n";
                foreach ($availableJobs as $job) {
                    $jobLink = "<a href='/form/job/view/{$job->id}' target='_blank'>{$job->job_title}</a>";
                    $response .= "🔹 {$jobLink}\n";
                    if ($job->job_type) $response .= "💼 {$job->job_type}\n";
                    $response .= "⭐ Entry Level Position\n";
                    if ($job->age) $response .= "🔸 Age Requirement: {$job->age}+\n";
                    if ($job->salary_from && $job->salary_to) {
                        $response .= "💰 Salary: {$job->salary_from} - {$job->salary_to}\n";
                    }
                    $response .= "\n";
                }

                $response .= "Click on any job title to view more details and apply!";

                return response()->json([
                    'success' => true,
                    'message' => $response,
                    'isHtml' => true
                ]);
            }

            // Check for experience in different formats
            if (preg_match('/(\d+)\s*(?:year|years|yr|yrs).*(?:experience|exp)/', $userMessage, $matches)) {
                $searchExp = (int)$matches[1];
                
                // Add debug logging
                \Log::info('Searching for experience:', ['searchExp' => $searchExp]);

                $availableJobs = DB::table('add_jobs')
                    ->where('status', 'Open')
                    ->where('expired_date', '>', now())
                    ->where(function($query) use ($searchExp) {
                        $query->where('experience', '0')  // Include jobs with no experience required
                              ->orWhere('experience', '')  // Include jobs with empty experience
                              ->orWhereNull('experience')  // Include jobs with null experience
                              ->orWhere(function($q) use ($searchExp) {
                                  $q->whereRaw('CAST(TRIM(experience) AS SIGNED) <= ?', [$searchExp])
                                    ->orWhere('experience', '<=', (string)$searchExp)
                                    ->orWhere('experience', 'LIKE', "{$searchExp}%")
                                    ->orWhere('experience', 'LIKE', "%{$searchExp}%")
                                    ->orWhere('experience', 'LIKE', '%+%');
                              });
                    })
                    ->get();

                // Add debug logging
                \Log::info('Found jobs:', ['count' => $availableJobs->count(), 'jobs' => $availableJobs]);

                if ($availableJobs->isEmpty()) {
                    return response()->json([
                        'success' => true,
                        'message' => "Currently, there are no positions matching {$searchExp} years of experience. Try different requirements or check our other open positions."
                    ]);
                }

                $response = "Here are positions suitable for {$searchExp} years of experience:\n\n";
                foreach ($availableJobs as $job) {
                    $jobLink = "<a href='/form/job/view/{$job->id}' target='_blank'>{$job->job_title}</a>";
                    $response .= "🔹 {$jobLink}\n";
                    if ($job->job_type) $response .= "💼 {$job->job_type}\n";
                    if ($job->experience == '0' || $job->experience == '' || $job->experience === null) {
                        $response .= "⭐ No experience required\n";
                    } else {
                        $response .= "⭐ Experience Required: {$job->experience}\n";
                    }
                    if ($job->age) $response .= "🔸 Age Requirement: {$job->age}+\n";
                    if ($job->salary_from && $job->salary_to) {
                        $response .= "💰 Salary: {$job->salary_from} - {$job->salary_to}\n";
                    }
                    $response .= "\n";
                }

                $response .= "Click on any job title to view more details and apply!";

                return response()->json([
                    'success' => true,
                    'message' => $response,
                    'isHtml' => true
                ]);
            }

            // Check for age in different formats
            $agePatterns = [
                '/show\s*jobs?\s*(?:for)?\s*(?:age)?\s*(\d+)/',  // "show job 25 age" or "show jobs age 25"
                '/(\d+)\s*(?:years?)?\s*(?:old|age)/',           // "25 years old" or "25 age"
                '/age\s*(\d+)/',                                  // "age 25"
            ];

            $searchAge = null;
            foreach ($agePatterns as $pattern) {
                if (preg_match($pattern, $userMessage, $matches)) {
                    $searchAge = (int)$matches[1];
                    break;
                }
            }

            if ($searchAge !== null) {
                $availableJobs = DB::table('add_jobs')
                    ->where('status', 'Open')
                    ->where('expired_date', '>', now())
                    ->where(function($query) use ($searchAge) {
                        $query->whereRaw('CAST(NULLIF(TRIM(age), "") AS SIGNED) <= ?', [$searchAge])
                              ->orWhere('age', '<=', (string)$searchAge)
                              ->orWhere('age', (string)$searchAge)
                              ->orWhere('age', 'LIKE', $searchAge.'%')
                              ->orWhere('age', 'LIKE', '%'.$searchAge.'%')
                              ->orWhere('age', 'LIKE', '%+%');
                    })
                    ->get();

                if ($availableJobs->isEmpty()) {
                    return response()->json([
                        'success' => true,
                        'message' => "Currently, there are no open positions matching your age criteria ({$searchAge} years). Try different requirements or check our other open positions."
                    ]);
                }

                $response = "Here are positions suitable for age {$searchAge}:\n\n";
                foreach ($availableJobs as $job) {
                    $jobLink = "<a href='/form/job/view/{$job->id}' target='_blank'>{$job->job_title}</a>";
                    $response .= "🔹 {$jobLink}\n";
                    if ($job->job_type) $response .= "💼 {$job->job_type}\n";
                    if ($job->experience) $response .= "⭐ Experience Required: {$job->experience}\n";
                    if ($job->age) $response .= "🔸 Age Requirement: {$job->age}+\n";
                    if ($job->salary_from && $job->salary_to) {
                        $response .= "💰 Salary: {$job->salary_from} - {$job->salary_to}\n";
                    }
                    $response .= "\n";
                }

                $response .= "Click on any job title to view more details and apply!";

                return response()->json([
                    'success' => true,
                    'message' => $response,
                    'isHtml' => true
                ]);
            }

            // Update the default response to include this new option
            return response()->json([
                'success' => true,
                'message' => "I can help you with:\n\n" .
                            "• Finding jobs by age or experience\n" .
                            "  Examples:\n" .
                            "  - \"Show jobs for age 25\"\n" .
                            "  - \"5 years experience jobs\"\n" .
                            "  - \"No experience jobs\"\n" .
                            "  - \"Entry level positions\"\n\n" .
                            "• Other information:\n" .
                            "  - \"How to apply\"\n" .
                            "  - \"Interview tips\"\n" .
                            "  - \"Company info\"\n" .
                            "  - \"Contact customer service\"\n\n" .
                            "What would you like to know more about? Just type your question! 😊"
            ]);

        } catch (\Exception $e) {
            \Log::error('Chat error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Sorry, something went wrong. Please try again later.'
            ], 500);
        }
    }

    private function getRelatedJobs($title)
    {
        // Logic to fetch related job titles from the database
        // Example: return Job::where('title', 'LIKE', "%$title%")->pluck('title')->toArray();
        return []; // Placeholder for actual implementation
    }

    private function extractAge($question) 
    {
        preg_match('/(\d+)\s*(?:years?\s*old|y(?:ea)?rs?)/', $question, $matches);
        return isset($matches[1]) ? (int)$matches[1] : null;
    }

    private function extractExperience($question) 
    {
        preg_match('/(\d+)\s*(?:years?\s*experience|y(?:ea)?rs?\s*exp|year\s*experiences?)/', $question, $matches);
        return isset($matches[1]) ? (int)$matches[1] : null;
    }

    private function getJobRecommendations($age, $experience)
    {
        try {
            // Debug log
            \Log::info('Starting job search with:', [
                'age' => $age,
                'experience' => $experience
            ]);

            $query = DB::table('add_jobs')
                    ->select('job_title',  'experience', 'department', 'salary_from', 'salary_to', 'age')
                    ->where('status', 'Active')
                    ->where('expired_date', '>', now());

            if ($age) {
                $query->where(function($q) use ($age) {
                    $q->where('age', '<=', $age)
                      ->orWhereNull('age');
                });
            }

            if ($experience !== null) {
                $query->where(function($q) use ($experience) {
                    $q->where('experience', '<=', $experience)
                      ->orWhere('experience', 'LIKE', "%$experience year%")
                      ->orWhereNull('experience');
                });
            }

            $jobs = $query->get();

            // Debug log
            \Log::info('Found jobs:', [
                'count' => $jobs->count(),
                'jobs' => $jobs->toArray()
            ]);

            if ($jobs->isEmpty()) {
                return "I couldn't find any exact matches for your criteria (Age: $age, Experience: $experience years). Please check all our open positions.";
            }

            $response = "Based on your criteria (Age: $age, Experience: $experience years), here are some job recommendations:\n\n";
            
            foreach ($jobs as $job) {
                $response .= "• {$job->job_title}\n";
                $response .= "  Experience Required: {$job->experience}\n";
                $response .= "  Department: {$job->department}\n";
                if ($job->salary_from && $job->salary_to) {
                    $response .= "  Salary Range: {$job->salary_from} - {$job->salary_to}\n";
                }
                $response .= "\n";
            }

            return nl2br($response);

        } catch (\Exception $e) {
            \Log::error('Job Recommendation Error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e; // Re-throw to be caught by parent
        }
    }
}


