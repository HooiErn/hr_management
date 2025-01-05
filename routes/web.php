<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LockScreen;
use App\Http\Controllers\JobController;
use App\Http\Controllers\CalenderController;
use App\Http\Controllers\LeavesController;
use App\Http\Controllers\ExpenseReportsController;
use App\Http\Controllers\PersonalInformationController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\InterviewController;
use App\Http\Controllers\AttendanceController;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/** for side bar menu active */
// Ensure the function is not redeclared
if (!function_exists('set_active')) {
    function set_active($route) {
        if (is_array($route)) {
            return in_array(Request::path(), $route) ? 'active' : '';
        }
        return Request::path() == $route ? 'active' : '';
    }
}

Route::get('/', [HomeController::class, 'showHomepage'])->name('homepage');

Route::get('/hr', function () {
    return view('auth.login');
});

Route::group(['middleware'=>'auth'],function()
{
    Route::get('home',function()
    {
        return view('auth.login');
    });
    Route::get('home',function()
    {
        return view('auth.login');
    });
});

Auth::routes();

// ----------------------------- main dashboard ------------------------------//
Route::controller(HomeController::class)->group(function () {
    Route::get('/home', 'index')->name('home');
    Route::get('em/dashboard', 'emDashboard')->name('em/dashboard');
});

// -----------------------------settings----------------------------------------//
Route::controller(SettingController::class)->group(function () {
    Route::get('company/settings/page', 'companySettings')->middleware('auth')->name('company/settings/page');
    Route::post('/settings/companysettings/save', 'saveCompanySettings')->middleware('auth')->name('company/settings/save');
    Route::get('roles/permissions/page', 'rolesPermissions')->middleware('auth')->name('roles/permissions/page');
    Route::post('roles/permissions/save', 'addRecord')->middleware('auth')->name('roles/permissions/save');
    Route::post('roles/permissions/update', 'editRolesPermissions')->middleware('auth')->name('roles/permissions/update');
    Route::post('roles/permissions/delete', 'deleteRolesPermissions')->middleware('auth')->name('roles/permissions/delete');
    Route::get('get/company/info', 'getCompanyInfo')->name('get.company.info');
    Route::post('change/password', 'changePassword')->middleware('auth')->name('settings/password');
});

// -----------------------------login----------------------------------------//
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('auth/logout');
});

// ----------------------------- lock screen --------------------------------//
Route::controller(LockScreen::class)->group(function () {
    Route::get('lock_screen','lockScreen')->middleware('auth')->name('lock_screen');
    Route::post('unlock', 'unlock')->name('unlock');    
});

// ------------------------------ register ---------------------------------//
Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'register')->name('register');
    Route::post('/register','storeUser')->name('store/register');    
});

// ----------------------------- forget password ----------------------------//
Route::controller(ForgotPasswordController::class)->group(function () {
    Route::get('forget-password', 'getEmail')->name('forget-password');
    Route::post('forget-password', 'postEmail')->name('password/email');    
});

// ----------------------------- reset password -----------------------------//
Route::controller(ResetPasswordController::class)->group(function () {
    Route::get('reset-password/{token}', 'getPassword')->name('reset-password');
    Route::post('reset-password', 'updatePassword')->name('reset-password.post');    
});

// ----------------------------- user profile ------------------------------//
Route::controller(UserManagementController::class)->group(function () {
    Route::get('profile_user', 'profile')->middleware('auth')->name('profile_user');
    Route::post('profile/information/save', 'profileInformation')->name('profile/information/save');    
});

// ----------------------------- user userManagement -----------------------//
Route::controller(UserManagementController::class)->group(function () {
    Route::get('userManagement', 'index')->middleware('auth')->name('userManagement');
    Route::post('user/add/save', 'addNewUserSave')->name('user/add/save');
    Route::post('search/user/list', 'searchUser')->name('search/user/list');
    Route::post('update', 'update')->name('update');
    Route::post('user/delete', 'delete')->middleware('auth')->name('user/delete');
    Route::get('activity/log', 'activityLog')->middleware('auth')->name('activity/log');
    Route::get('activity/login/logout', 'activityLogInLogOut')->middleware('auth')->name('activity/login/logout');    
});

// ----------------------------- search user management ------------------------------//
Route::controller(UserManagementController::class)->group(function () {
    Route::post('search/user/list', 'searchUser')->name('search/user/list');
});

// ----------------------------- form change password ------------------------------//
Route::controller(UserManagementController::class)->group(function () {
    Route::get('change/password', 'changePasswordView')->middleware('auth')->name('change/password');
        
});

// ----------------------------- job ------------------------------//
Route::controller(JobController::class)->group(function () {
    Route::get('form/job/list','jobList')->name('form/job/list');
    Route::get('form/job/view/{id}', 'jobView');
    Route::get('user/dashboard/index', 'userDashboard')->middleware('auth')->name('user/dashboard/index');    
    Route::get('jobs/dashboard/index', 'jobsDashboard')->middleware('auth')->name('jobs/dashboard/index');    
    Route::get('user/dashboard/all', 'userDashboardAll')->middleware('auth')->name('user/dashboard/all');    
    Route::get('user/dashboard/save', 'userDashboardSave')->middleware('auth')->name('user/dashboard/save');
    Route::get('user/dashboard/applied/jobs', 'userDashboardApplied')->middleware('auth')->name('user/dashboard/applied/jobs');
    Route::get('user/dashboard/interviewing', 'userDashboardInterviewing')->middleware('auth')->name('user/dashboard/interviewing');
    Route::get('user/dashboard/offered/jobs', 'userDashboardOffered')->middleware('auth')->name('user/dashboard/offered/jobs');
    Route::get('user/dashboard/visited/jobs', 'userDashboardVisited')->middleware('auth')->name('user/dashboard/visited/jobs');
    Route::get('user/dashboard/archived/jobs', 'userDashboardArchived')->middleware('auth')->name('user/dashboard/archived/jobs');
    Route::get('jobs', 'Jobs')->middleware('auth')->name('jobs');
    Route::post('/jobs/delete',  'JobsDeleteRecord')->middleware('auth')->name('jobs/delete');
    Route::get('job/applicants/{job_title}', 'jobApplicants')->middleware('auth')->name('job/application');
    Route::get('job/details/{id}', 'jobDetails')->middleware('auth');
    Route::get('cv/download/{id}', 'downloadCV')->middleware('auth');
    Route::get('/jobs/filter', 'filter')->middleware('auth')->name('jobs/filter');
    Route::post('form/jobs/save', 'JobsSaveRecord')->name('form/jobs/save');
    Route::post('form/apply/job/save', 'applyJobSaveRecord')->name('form/apply/job/save');
    Route::post('form/apply/job/update', 'applyJobUpdateRecord')->middleware('auth')->name('form/apply/job/update');
    Route::post('/job/update-status', 'updateJobStatus')->middleware('auth')->name('job.update.status');
    //delete multiple jobs
    Route::post('/jobs/delete', 'deleteJob')->middleware('auth')->name('jobs/delete');


    Route::get('page/manage/resumes', 'manageResumesIndex')->middleware('auth')->name('page/manage/resumes');
    Route::post('all/resumes/search', 'employeeSearch')->name('all/resumes/search');

    Route::get('page/candidates', 'candidatesIndex')->middleware('auth')->name('page/candidates');
    Route::post('candidates/search', [JobController::class, 'search'])->middleware('auth')->name('candidates/search');
    Route::get('page/interviewer', 'InterviewerIndex')->middleware('auth')->name('page/interviwer');
    Route::get('page/schedule/timing', 'scheduleTimingIndex')->middleware('auth')->name('page/schedule/timing');
    Route::get('page/aptitude/result', 'aptituderesultIndex')->middleware('auth')->name('page/aptitude/result');
    Route::post('candidate/approve', 'approveCandidate')->middleware('auth')->name('candidate/approve');
    Route::post('candidate/edit', 'editCandidate')->middleware('auth')->name('candidate/edit');
    Route::post('candidate/delete', 'deleteCandidate')->middleware('auth')->name('candidate/delete');
    Route::get('video/dashboard', 'videoDashboard')->name('video.dashboard');
});

// ----------------------------- Interviewer  ------------------------------//
Route::controller(InterviewController::class)->group(function () {
    Route::post('schedule/interview', 'scheduleInterview')->middleware('auth')->name('schedule.interview');
    Route::post('interviewer/update', 'update')->name('interviewer/update');
    Route::delete('interviewer/delete', 'destroy')->name('interviewer/delete');
    Route::get('/resume/{id}', 'showResume')->middleware('auth')->name('resume');
    Route::post('interviewer/bulkAction', 'bulkAction')->middleware('auth')->name('interviewer/bulkAction');
    Route::post('/interviewers/search',  'search')->middleware('auth')->name('interviewers/search');
    Route::post('/interviewers/send-email',  'sendEmail')->middleware('auth')->name('interviewers/send-email');
   
});

// ----------------------------- chatbox candidates ------------------------------//
Route::controller(ChatController::class)->group(function () {
    Route::post('/chat', [ChatController::class, 'handleMessage'])->name('chat/handle');
    Route::post('/upload-cv', [ChatController::class, 'uploadCv'])->name('chat/uploadCv');
});
 
// ----------------------------- form employee ------------------------------//
Route::controller(EmployeeController::class)->group(function () {
    Route::get('all/employee/card', 'cardAllEmployee')->middleware('auth')->name('all/employee/card');
    Route::get('all/employee/list', 'listAllEmployee')->middleware('auth')->name('all/employee/list');
    Route::post('all/employee/save', 'saveRecord')->middleware('auth')->name('all/employee/save');
    Route::get('all/employee/view/edit/{employee_id}', 'viewRecord');
    Route::post('all/employee/update', 'updateRecord')->middleware('auth')->name('all/employee/update');
    Route::get('all/employee/delete/{employee_id}', 'deleteRecord')->middleware('auth');
    
    //search
    Route::post('employees/search', 'search')->middleware('auth')->name('employees/search');
    Route::get('employees/searchbydepartment', 'SearchByDepartment')->middleware('auth')->name('employees/searchByDepartment');
    //search in department employee
    Route::post('department/employees/search', 'searchDepartmentEmployees')->middleware('auth')->name('department/employees/search');
    
    
    Route::get('employees/list', 'employeeList')->middleware('auth')->name('employees/list');
    Route::get('employees/department/{department}', 'showByDepartment')->middleware('auth')->name('employees/byDepartment');

    //resign
    Route::get('/resign/{employeeId}',  'resign')->middleware('auth')->name('employee.resign');
    Route::get('/pastEmployee',  'pastEmployeePage')->middleware('auth')->name('employee/past');
    Route::post('past/employees/search', 'searchPastEmployees')->middleware('auth')->name('past/employees/search');
    //export
    Route::get('/employees/export/excel', 'exportExcel')->middleware('auth')->name('employees.export.excel');
    Route::get('/employees/export/pdf',  'exportPDF')->middleware('auth')->name('employees.export.pdf');
    Route::get('/pastemployees/export/excel', 'exportExcelforPastEmployee')->middleware('auth')->name('pastemployees.export.excel');
    Route::get('/pastemployees/export/pdf',  'exportPDFforPastEmployee')->middleware('auth')->name('pastemployees.export.pdf');



    //departments
    Route::get('form/departments/page', 'index')->middleware('auth')->name('form/departments/page');    
    Route::post('form/departments/save', 'saveRecordDepartment')->middleware('auth')->name('form/departments/save');    
    Route::post('form/department/update', 'updateRecordDepartment')->middleware('auth')->name('form/department/update');    
    Route::post('form/department/delete', 'deleteRecordDepartment')->middleware('auth')->name('form/department/delete');  
    
    Route::get('form/designations/page', 'designationsIndex')->middleware('auth')->name('form/designations/page');    
    Route::post('form/designations/save', 'saveRecordDesignations')->middleware('auth')->name('form/designations/save');    
    Route::post('form/designations/update', 'updateRecordDesignations')->middleware('auth')->name('form/designations/update');    
    Route::post('form/designations/delete', 'deleteRecordDesignations')->middleware('auth')->name('form/designations/delete');
    
    Route::get('form/timesheet/page', 'timeSheetIndex')->middleware('auth')->name('form/timesheet/page');    
    Route::post('form/timesheet/save', 'saveRecordTimeSheets')->middleware('auth')->name('form/timesheet/save');    
    Route::post('form/timesheet/update', 'updateRecordTimeSheets')->middleware('auth')->name('form/timesheet/update');    
    Route::post('form/timesheet/delete', 'deleteRecordTimeSheets')->middleware('auth')->name('form/timesheet/delete');
    
    Route::get('form/overtime/page', 'overTimeIndex')->middleware('auth')->name('form/overtime/page');    
    Route::post('form/overtime/save', 'saveRecordOverTime')->middleware('auth')->name('form/overtime/save');    
    Route::post('form/overtime/update', 'updateRecordOverTime')->middleware('auth')->name('form/overtime/update');    
    Route::post('form/overtime/delete', 'deleteRecordOverTime')->middleware('auth')->name('form/overtime/delete');  
});

// ----------------------------- profile employee ------------------------------//
Route::controller(EmployeeController::class)->group(function () {
    Route::get('employee/profile/{user_id}', 'profileEmployee')->middleware('auth');
  

});

// ----------------------------- form calender ------------------------------//
Route::controller(CalenderController::class)->group(function () {
    Route::get('form/calender/new', 'calender')->middleware('auth')->name('form/calender/new');
    Route::post('form/calender/save', 'saveRecord')->middleware('auth')->name('form/calender/save');
    Route::post('form/calender/update', 'updateRecord')->middleware('auth')->name('form/calender/update');    
    Route::delete('/calendar/delete/{id}', 'deleteRecord')->middleware('auth')->name('calendar.delete');
});

// ----------------------------- form leaves ------------------------------//
Route::controller(LeavesController::class)->group(function () {
    Route::get('form/leaves/new', 'leaves')->middleware('auth')->name('form/leaves/new');
    Route::get('form/leavesemployee/new', 'leavesEmployee')->middleware('auth')->name('form/leavesemployee/new');
    Route::post('form/leaves/save', 'saveRecord')->middleware('auth')->name('form/leaves/save');
    Route::post('form/leaves/edit', 'editRecordLeave')->middleware('auth')->name('form/leaves/edit');
    Route::post('form/leaves/edit/delete','deleteLeave')->middleware('auth')->name('form/leaves/edit/delete');    
    Route::get('/getRemainingLeaveDays/{userId}','getRemainingLeaveDays')->middleware('auth');

    //search employee id in input field
    Route::get('leaves/employees/search',  'searchEmployee')->middleware('auth')->name('leaves.employees.search');

    //search
    Route::post('leaves/search', 'searchLeaves')->middleware('auth')->name('leaves/search');


});

// ----------------------------- form attendance  ------------------------------//
Route::controller(LeavesController::class)->group(function () {
    Route::get('form/leavesettings/page', 'leaveSettings')->middleware('auth')->name('form/leavesettings/page');
    Route::get('form/shiftscheduling/page', 'shiftScheduLing')->middleware('auth')->name('form/shiftscheduling/page');
    Route::get('form/shiftlist/page', 'shiftList')->middleware('auth')->name('form/shiftlist/page');    

    //export
    Route::get('/leaves/export/excel', 'exportExcel')->middleware('auth')->name('leaves.export.excel');
    Route::get('/leaves/export/pdf', 'exportPDF')->middleware('auth')->name('leaves.export.pdf');
    
});

// ----------------------------- reports  ------------------------------//
Route::controller(ExpenseReportsController::class)->group(function () {
    Route::get('form/leave/reports/page','leaveReport')->middleware('auth')->name('form/leave/reports/page');
});

// ----------------------------- Attendance  ------------------------------//
Route::controller(AttendanceController::class)->group(function () {
    Route::post('/attendance/punch-in', 'punchIn')->middleware('auth')->name('attendance.punchIn');
    Route::post('/attendance/punch-out', 'punchOut')->middleware('auth')->name('attendance.punchOut');
    Route::get('attendance/page', 'attendanceIndex')->middleware('auth')->name('attendance/page');
    Route::get('attendance/employee/page', 'viewAttendance')->middleware('auth')->name('attendance/employee/page');
    Route::post('/attendance/check-today', 'checkToday')->middleware('auth')->name('attendance.checkToday');
    Route::post('attendance/search', 'search')->middleware('auth')->name('attendance/search');
});


// ----------------------------- training type  ------------------------------//
Route::controller(PersonalInformationController::class)->group(function () {
    Route::post('user/information/save', 'saveRecord')->middleware('auth')->name('user/information/save');
});

// Public meeting routes (no auth required)
Route::get('public/meeting', [JobController::class, 'publicMeeting'])->name('public.meeting');
Route::post('public/meeting/join', [JobController::class, 'joinPublicMeeting'])->name('public.meeting.join');
