<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\AddJob;
use App\Models\ApplyForJob;
use App\Models\Candidate;
use App\Models\Category;
use App\Models\Question;
use Carbon\Carbon;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Interviewer;

class JobController extends Controller
{
    // job List
    public function jobList(Request $request)
    {    
        $query = DB::table('add_jobs');

        // If search parameter exists and not empty
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('job_title', 'LIKE', "%{$search}%");
        }

        $job_list = $query->get();
        
        // If it's an AJAX request
        if ($request->ajax()) {
            return response()->json($job_list);
        }

        return view('job.joblist', compact('job_list'));
    }
    
    // job view
    public function jobView($id)
    { 
        /** update count */
        $post = AddJob::find($id);
        $update = ['count' =>$post->count + 1,];
        AddJob::where('id',$post->id)->update($update);

        // Get all jobs for the dropdown
        $jobs = DB::table('add_jobs')->get();
        
        $job_view = DB::table('add_jobs')->where('id',$id)->get();
        return view('job.jobview', compact('job_view', 'jobs'));
    }

    /** users dashboard index */
    public function userDashboard()
    {
        $job_list   = DB::table('add_jobs')->get();
        return view('job.userdashboard',compact('job_list'));
    }

    /** jobs dashboard index */
    public function jobsDashboard() {
        return view('job.jobsdashboard');
    }
    /** user all job */
    public function userDashboardAll() 
    {
        return view('job.useralljobs');
    }

    /** save job */
    public function userDashboardSave()
    {
      return view('job.savedjobs');
    }

    /** applied job*/
    public function userDashboardApplied()
    {
        return view('job.appliedjobs');
    }

    /** interviewing job*/
    public function userDashboardInterviewing()
    {
        return view('job.interviewing');
    }

    /** interviewing job*/
    public function userDashboardOffered()
    {
        return view('job.offeredjobs');
    }

    /** visited job*/
    public function userDashboardVisited()
    {
        return view('job.visitedjobs');
    }

    /** archived job*/
    public function userDashboardArchived()
    {
        return view('job.visitedjobs');
    }

    /** jobs */
    public function Jobs()
    {
        $jobs = AddJob::all(); 
        $department = DB::table('departments')->get();
        $type_job   = DB::table('type_jobs')->get();
        $job_list   = DB::table('add_jobs')->get();
        return view('job.jobs',compact('department','type_job','job_list','jobs'));
    }

    /** job save record */
    public function JobsSaveRecord(Request $request)
    {
        $request->validate([
            'job_title'       => 'required|string|max:255',
            'department'      => 'required|string|max:255',
            'job_location'    => 'required|string|max:255',
            'no_of_vacancies' => 'required|string|max:255',
            'experience'      => 'required|string|max:255',
            'age'             => 'required',
            'salary_from'     => 'required|string|max:255',
            'salary_to'       => 'required|string|max:255',
            'job_type'        => 'required|string|max:255',
            'status'          => 'required|string|max:255',
            'start_date'      => 'required|string|max:255',
            'expired_date'    => 'required|string|max:255',
            'description'     => 'required',
        ]);

        DB::beginTransaction();
        try {
            
            $add_job = new AddJob;
            $add_job->job_title       = $request->job_title;
            $add_job->department      = $request->department;
            $add_job->job_location    = $request->job_location;
            $add_job->no_of_vacancies = $request->no_of_vacancies;
            $add_job->experience   = $request->experience;
            $add_job->age          = $request->age;
            $add_job->salary_from  = $request->salary_from;
            $add_job->salary_to    = $request->salary_to;
            $add_job->job_type     = $request->job_type;
            $add_job->status       = $request->status;
            $add_job->start_date   = $request->start_date;
            $add_job->expired_date = $request->expired_date;
            $add_job->description  = $request->description;
            $add_job->save();

            DB::commit();
            Toastr::success('Create job successfully :)','Success');
            return redirect()->back();
            
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Add Job fail :)','Error');
            return redirect()->back();
        } 
    }

    /*Delete Jobs */
    public function JobsDeleteRecord(Request $request)
{
    DB::beginTransaction();
    try {
        $job = AddJob::findOrFail($request->job_id);
        $job->delete();

        DB::commit();
        Toastr::success('Job deleted successfully :)', 'Success');
        return redirect()->back();
    } catch (\Exception $e) {
        DB::rollback();
        Toastr::error('Failed to delete the job :)', 'Error');
        return redirect()->back();
    }
}

    
    /** job applicants */
    public function jobApplicants($job_title)
    {
       $apply_for_jobs = DB::table('apply_for_jobs')->where('job_title',$job_title)->get();
        return view('job.jobapplicants',compact('apply_for_jobs'));
    }

    /** download */
    public function downloadCV($id) {
        $cv_uploads = DB::table('apply_for_jobs')->where('id',$id)->first();
        $pathToFile = public_path("assets/cv/{$cv_uploads->cv_upload}");
        return \Response::download($pathToFile);
    }

    /** job details */
    public function jobDetails($id)
    {
        $job_view_detail = DB::table('add_jobs')->where('id',$id)->get();
        return view('job.jobdetails',compact('job_view_detail'));
    }

    protected function generateUniqueCandidateId()
    {
        return 'CAND-' . time() . '-' . mt_rand(1000, 9999); // format
    }

    /** apply Job SaveRecord */
    public function applyJobSaveRecord(Request $request) 
    {
        $request->validate([
            'job_title' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'ic_number' => 'required|string|size:12|unique:candidates,ic_number|regex:/^\d{12}$/',
            'phone_number' => 'required|string|regex:/^[0-9]{10,13}$/', 
            'email' => 'required|email|max:255',
            'age' => 'nullable|integer|min:0|max:120',
            'race' => 'nullable|string|max:50',
            'gender' => 'nullable|string|in:Male,Female,Other', 
            'birth_date' => 'nullable|date', 
            'highest_education' => 'nullable|string|max:255',
            'work_experiences' => 'nullable|integer|min:0|max:100',
            'message' => 'nullable|string|max:1000',
            'cv_upload' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'interview_datetime' => 'nullable|date_format:Y-m-d H:i:s'
        ]);

        DB::beginTransaction();
        try {

            /** upload file */
            $cv_uploads = time().'.'.$request->cv_upload->extension();  
            $request->cv_upload->move(public_path('assets/cv/'), $cv_uploads);

            // Generate a unique candidate ID
            $candidate_id = $this->generateUniqueCandidateId(); // Call the method

            // Save candidate data
            $candidate = new Candidate;
            $candidate->candidate_id = $candidate_id;
            $candidate->ic_number = $request->ic_number;
            $candidate->name = $request->name;
            $candidate->age = $request->age; 
            $candidate->race = $request->race; 
            $candidate->gender = $request->gender; 
            $candidate->phone_number = $request->phone_number; 
            $candidate->email = $request->email;
            $candidate->birth_date = $request->birth_date; 
            $candidate->job_title = $request->job_title;
            $candidate->highest_education = $request->highest_education; 
            $candidate->work_experiences = $request->work_experiences;
            $candidate->role_name = $request->role_name; 
            $candidate->interview_datetime = $request->interview_datetime; 
            $candidate->cv_upload = $request->$cv_uploads;
            $candidate->save();
            
            // Save job application data
            $apply_job = new ApplyForJob;
            $apply_job->job_title          = $request->job_title;
            $apply_job->name               = $request->name;
            $apply_job->ic_number          = $request->ic_number;
            $apply_job->age                = $request->age; 
            $apply_job->race               = $request->race; 
            $apply_job->gender             = $request->gender; 
            $apply_job->birth_date         = $request->birth_date; 
            $apply_job->phone_number       = $request->phone_number;
            $apply_job->email              = $request->email;
            $apply_job->highest_education  = $request->highest_education;
            $apply_job->work_experiences   = $request->work_experiences; 
            $apply_job->message            = $request->message;
            $apply_job->cv_upload          = $cv_uploads;
            $apply_job->interview_datetime = $request->interview_datetime;
            $apply_job->save();

            DB::commit();
            Toastr::success('Apply job successfully :)','Success');
            return redirect()->back();
            
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Apply Job fail :)','Error');
            return redirect()->back();
        } 
    }

    /** applyJobUpdateRecord */
    public function applyJobUpdateRecord(Request $request)
    {
        // Validate the request
        $request->validate([
            'job_title' => 'required|string',
            'department' => 'required|string',
            'job_location' => 'required|string',
            'no_of_vacancies' => 'required|integer',
            'experience' => 'required|string',
            'salary_from' => 'required',
            'salary_to' => 'required',
            'job_type' => 'required|string',
            'status' => 'required|string',
            'start_date' => 'required|date',
            'expired_date' => 'required|date',
            'description' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $update = [
                'job_title' => $request->job_title,
                'department' => $request->department,
                'job_location' => $request->job_location,

                'no_of_vacancies' => $request->no_of_vacancies,
                'experience' => $request->experience,
                'age' => $request->age,
                'salary_from' => $request->salary_from,
                'salary_to' => $request->salary_to,
                'job_type' => $request->job_type,
                'status' => $request->status,
                'start_date' => $request->start_date,
                'expired_date' => $request->expired_date,
                'description' => $request->description,
            ];

            AddJob::where('id', $request->id)->update($update);
            
            DB::commit();
            Toastr::success('Job updated successfully :)', 'Success');
            return redirect()->back();
        } catch(\Exception $e) {
            DB::rollback();
            \Log::error('Job Update Error: ' . $e->getMessage());
            Toastr::error('Failed to update job: ' . $e->getMessage(), 'Error');
            return redirect()->back();
        } 
    }

    /** manage Resumes */
    public function manageResumesIndex()
    {
        $department = DB::table('departments')->get();
        $type_job   = DB::table('type_jobs')->get();
        $manageResumes = DB::table('add_jobs')
                        ->join('apply_for_jobs', 'apply_for_jobs.job_title', 'add_jobs.job_title')
                        ->select('add_jobs.*', 'apply_for_jobs.*')->get();
        return view('job.manageresumes',compact('manageResumes','department','type_job'));
    }

    /**Interviwerer page */
    public function InterviewerIndex()
    {
        $interviewers = Interviewer::all();
        return view('job.interviewer', compact('interviewers'));
    }

    /** interview questions */
    public function interviewQuestionsIndex()
    {
        $question    = DB::table('questions')->get();
        $category    = DB::table('categories')->get();
        $department  = DB::table('departments')->get();
        $answer      = DB::table('answers')->get();
        return view('job.interviewquestions',compact('category','department','answer','question'));
    }

    /** candidates */
    public function candidatesIndex()
    {
        $candidates = DB::table('candidates')->get();
        $jobs = DB::table('add_jobs')->get();
        
        return view('job.candidates', compact('candidates', 'jobs'));
    }

    /*Search Candidates*/
    public function search(Request $request)
    {
        $query = Candidate::query();

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

        $candidates = $query->get();
        return response()->json(['candidates' => $candidates]);
    }


    /** schedule timing */
    public function scheduleTimingIndex()
    {
        return view('job.scheduletiming');
    }
    /** aptitude result */

    public function aptituderesultIndex()
    {
        return view('job.aptituderesult');
    }

    public function approveCandidate(Request $request)
    {
        // Validate if candidate_id exists
        if (!$request->has('candidate_id')) {
            Toastr::error('No candidate selected for approval', 'Error');
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            // Try to find the candidate, handle if not found
            $candidate = Candidate::find($request->candidate_id);
            
            if (!$candidate) {
                DB::rollback();
                Toastr::error('Candidate not found', 'Error');
                return redirect()->back();
            }

            // Create new interviewer record with candidate's data
            $interviewer = new Interviewer();
            $interviewer->job_title = $candidate->job_title;
            $interviewer->candidate_id = $candidate->candidate_id;
            $interviewer->name = $candidate->name;
            $interviewer->ic_number = $candidate->ic_number;
            $interviewer->age = $candidate->age;
            $interviewer->race = $candidate->race;
            $interviewer->gender = $candidate->gender;
            $interviewer->birth_date = $candidate->birth_date;
            $interviewer->phone_number = $candidate->phone_number;
            $interviewer->email = $candidate->email;
            $interviewer->highest_education = $candidate->highest_education;
            $interviewer->work_experiences = $candidate->work_experiences;
            $interviewer->message = $candidate->message;
            $interviewer->cv_upload = $candidate->cv_upload;
            $interviewer->interview_datetime = $candidate->interview_datetime;
            $interviewer->status = 'Approved';
            $interviewer->save();

            // Delete candidate record
            $candidate->delete();

            DB::commit();
            Toastr::success('Candidate approved as interviewer successfully :)', 'Success');
            return redirect()->back();
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Failed to approve candidate: ' . $e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

    // Add edit candidate method
    public function editCandidate(Request $request)
    {
        // Add debugging to see what's being received
        \Log::info('Edit Candidate Request:', $request->all());

        if (!$request->has('candidate_id')) {
            Toastr::error('No candidate selected for editing', 'Error');
            return redirect()->back();
        }

        try {
            // Try to find the candidate using the primary key
            $candidate = Candidate::where('id', $request->candidate_id)
                                ->orWhere('candidate_id', $request->candidate_id)
                                ->first();
            
            // If no candidate found, return with message
            if (!$candidate) {
                \Log::error('Candidate not found with ID: ' . $request->candidate_id);
                Toastr::error('Candidate not found or has been deleted', 'Error');
                return redirect()->back();
            }

            // Only proceed with validation if we have a candidate
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'ic_number' => 'required|string|size:12',
                'phone_number' => 'required|string|regex:/^[0-9]{10,13}$/',
                'birth_date' => 'nullable|date',
                'age' => 'nullable|integer|min:0|max:120',
                'gender' => 'nullable|string|in:Male,Female,Other',
                'race' => 'nullable|string|max:50',
                'highest_education' => 'nullable|string|max:255',
                'work_experiences' => 'nullable|integer|min:0|max:100',
            ]);

            DB::beginTransaction();
            
            $candidate->name = $request->name;
            $candidate->email = $request->email;
            $candidate->ic_number = $request->ic_number;
            $candidate->phone_number = $request->phone_number;
            $candidate->birth_date = $request->birth_date;
            $candidate->age = $request->age;
            $candidate->gender = $request->gender;
            $candidate->race = $request->race;
            $candidate->highest_education = $request->highest_education;
            $candidate->work_experiences = $request->work_experiences;
            
            $candidate->save();

            DB::commit();
            \Log::info('Candidate updated successfully:', $candidate->toArray());
            Toastr::success('Candidate updated successfully :)', 'Success');
            return redirect()->back();
        } catch(\Exception $e) {
            DB::rollback();
            \Log::error('Edit Candidate Error: ' . $e->getMessage());
            Toastr::error('Failed to update candidate: ' . $e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

    public function editCandidateForm($id)
    {
        $candidate = Candidate::findOrFail($id);
        return view('job.edit_candidate', compact('candidate'));
    }

    public function videoDashboard()
    {
        return view('job.video-dashboard');
    }
// public meeting for interviewer
    public function publicMeeting()
    {
        return view('job.public-meeting');
    }

    public function joinPublicMeeting(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'roomID' => 'required|string'
        ]);

        return view('job.public-meeting', [
            'roomID' => $request->roomID,
            'userName' => $request->name
        ]);
    }

}
