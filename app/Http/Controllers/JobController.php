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

class JobController extends Controller
{
    // job List
    public function jobList()
    {    
        $job_list = DB::table('add_jobs')->get();
        return view('job.joblist',compact('job_list'));
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
            $candidate->save();
            
            // Save job application data
            $apply_job = new ApplyForJob;
            $apply_job->job_title          = $request->job_title;
            $apply_job->name               = $request->name;
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
        DB::beginTransaction();
        try {
            $update = [
                'id'              => $request->id,
                'job_title'       => $request->job_title,
                'department'      => $request->department,
                'job_location'    => $request->job_location,
                'no_of_vacancies' => $request->no_of_vacancies,
                'experience'      => $request->experience,
                'age'             => $request->age,
                'salary_from'     => $request->salary_from,
                'salary_to'       => $request->salary_to,
                'job_type'        => $request->job_type,
                'status'          => $request->status,
                'start_date'      => $request->start_date,
                'expired_date'    => $request->expired_date,
                'description'     => $request->description,
            ];

            AddJob::where('id',$request->id)->update($update);
            DB::commit();
            Toastr::success('Updated Leaves successfully :)','Success');
            return redirect()->back();
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Update Leaves fail :)','Error');
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

    /** shortlist candidates */
    public function shortlistCandidatesIndex()
    {
        return view('job.shortlistcandidates');
    }

    /**Interviwerer page */
    public function InterviewerIndex(){
        return view('job.interviewer');
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

    /** interviewQuestions Save */
    public function categorySave( Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {

            $save = new Category;
            $save->category = $request->category;
            $save->save();
            
            DB::commit();
            Toastr::success('Create new Category successfully :)','Success');
            return redirect()->back();
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Add Category fail :)','Error');
            return redirect()->back();
        }
    }

    /** save question */
    public function questionSave(Request $request)
    {
        $request->validate([
            'category'           => 'required|string|max:255',
            'department'         => 'required|string|max:255',
            'questions'          => 'required|string|max:255',
            'option_a'           => 'required|string|max:255',
            'option_b'           => 'required|string|max:255',
            'option_c'           => 'required|string|max:255',
            'option_d'           => 'required|string|max:255',
            'answer'             => 'required|string|max:255',
            'code_snippets'      => 'required|string|max:255',
            'answer_explanation' => 'required|string|max:255',
            'video_link'         => 'required|url',
            'image_to_question'  => 'required',
        ]);

        DB::beginTransaction();
        try {

            /** upload file */
            $image_to_questions = time().'.'.$request->image_to_question->extension();  
            $request->image_to_question->move(public_path('assets/images/question'), $image_to_questions);

            $save = new Question;
            $save->category   = $request->category;
            $save->department = $request->department;
            $save->questions  = $request->questions;
            $save->option_a = $request->option_a;
            $save->option_b = $request->option_b;
            $save->option_c = $request->option_c;
            $save->option_d = $request->option_d;
            $save->answer   = $request->answer;
            $save->code_snippets      = $request->code_snippets;
            $save->answer_explanation = $request->answer_explanation;
            $save->video_link         = $request->video_link;
            $save->image_to_question  = $image_to_questions;
            $save->save();
            
            DB::commit();
            Toastr::success('Create new Question successfully :)','Success');
            return redirect()->back();
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Add Question fail :)','Error');
            return redirect()->back();
        } 
    }

    /** question update */
    public function questionsUpdate(Request $request)
    {
        DB::beginTransaction();
        try {
            
            $update = [
                'id'            => $request->id,
                'category'      => $request->category,
                'department'    => $request->department,
                'questions'     => $request->questions,
                'option_a'      => $request->option_a,
                'option_b'      => $request->option_b,
                'option_c'      => $request->option_c,
                'option_d'      => $request->option_d,
                'answer'        => $request->answer,
                'code_snippets' => $request->code_snippets,
                'answer_explanation' => $request->answer_explanation,
                'video_link' => $request->video_link,
            ];

            Question::where('id',$request->id)->update($update);
            DB::commit();
            Toastr::success('Updated Questions successfully :)','Success');
            return redirect()->back();
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Update Questions fail :)','Error');
            return redirect()->back();
        }
    }

    /** delete question */
    public function questionsDelete(Request $request)
    {
        try {

            Question::destroy($request->id);
            Toastr::success('Question deleted successfully :)','Success');
            return redirect()->back();
        
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Question delete fail :)','Error');
            return redirect()->back();
        }
    }

    /** offer approvals */
    public function offerApprovalsIndex()
    {
        return view('job.offerapprovals');
    }

    /** experience level */
    public function experienceLevelIndex()
    {
        return view('job.experiencelevel');
    }

    /** candidates */
    public function candidatesIndex()
    {
        $candidates = DB::table('candidates')->get();
        $jobs = DB::table('add_jobs')->get(); // Get available jobs
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
}
