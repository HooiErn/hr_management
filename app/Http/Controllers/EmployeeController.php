<?php

namespace App\Http\Controllers;

use App\Exports\PastEmployeesExport;
use Illuminate\Http\Request;
use DB;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Employee;
use App\Models\department;
use App\Models\User;
use App\Models\module_permission;
use App\Models\PastEmployee;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EmployeesExport;
use App\Models\Timesheet;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    // all employee card view
    public function cardAllEmployee(Request $request)
    {
        $department = DB::table('departments')->get();
        $users = DB::table('employees')->get(); 
        $permission_lists = DB::table('permission_lists')->get();
        return view('form.allemployeecard',compact('users','permission_lists','department'));
    }
    // all employee list
    public function listAllEmployee()
    {
        $users = Employee::all(); // Fetch all employees
        return view('form.employee_list', compact('users'));
    }

    // save data employee
    public function saveRecord(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:employees,email',
            'ic_number' => 'required|string|size:12|unique:employees,ic_number|regex:/^\d{12}$/',
            'salary' => 'required|numeric',
            'status' => 'required',
            'role_name' => 'nullable|string',
            'phone_number' => 'nullable|string|regex:/^[0-9]{10,13}$/',
            'age' => 'nullable|integer|min:0|max:100',
            'gender' => 'nullable|string',
            'race' => 'nullable|string',
            'highest_education' => 'nullable|string',
            'work_experiences' => 'nullable|integer',
            'company' => 'nullable|string',
            'cv_upload' => 'nullable|file|mimes:pdf|max:2048',
            'birth_date' => 'nullable|date',
            'avatar' => 'nullable|string',
            'position' => 'nullable|string',
            'department' => 'nullable|string',
            'leaves' => 'nullable|string',
            'contracts' => 'nullable|string',
            'job_type' => 'required|string',
            'password' => 'required|string|min:8', // Assuming password is part of your employee data
        ]);

        try {
            DB::beginTransaction();
            
            // Generate employee_id
            $employeeId = 'EMP' . str_pad(Employee::count() + 1, 4, '0', STR_PAD_LEFT);
            
            // Handle CV upload (if provided)
            $cvUploadPath = time().'.'.$request->cv_upload->extension();  
            $request->cv_upload->move(public_path('assets/cv/'), $cvUploadPath);


            $employee = new Employee();
            $employee->employee_id = $employeeId;
            $employee->name = $request->name;
            $employee->email = $request->email;
            $employee->age = $request->age;
            $employee->race = $request->race;
            $employee->highest_education = $request->highest_education;
            $employee->work_experiences = $request->work_experiences;
            $employee->ic_number = $request->ic_number;
            $employee->cv_upload = $cvUploadPath;
            $employee->birth_date = $request->birth_date;
            $employee->gender = $request->gender;
            $employee->phone_number = $request->phone_number;
            $employee->status = $request->status;
            $employee->role_name = $request->role_name;
            $employee->position = $request->position;
            $employee->department = $request->department;
            $employee->salary = $request->salary; 
            $employee->job_type = $request->job_type;
            $employee->company = 'HRTech Inc.';
            $employee->join_date = now();
            $employee->password = bcrypt($request->password);
            $employee->save();


            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    // view edit record
    public function viewRecord($employee_id)
    {
        $permission = DB::table('employees')
            ->join('module_permissions', 'employees.employee_id', '=', 'module_permissions.employee_id')
            ->select('employees.*', 'module_permissions.*')
            ->where('employees.employee_id','=',$employee_id)
            ->get();
        $employees = DB::table('employees')->where('employee_id',$employee_id)->get();
        return view('form.edit.editemployee',compact('employees','permission'));
    }
    // update record employee
    public function updateRecord(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:employees,email,' . $request->id,
            'salary' => 'required|numeric',
            'status' => 'required',
            'role_name' => 'nullable|string',
            'phone_number' => 'nullable|string|regex:/^[0-9]{10,13}$/',
            'gender' => 'nullable|string',
            'position' => 'nullable|string',
            'department' => 'nullable|string',
            'job_type' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            
            $employee = Employee::findOrFail($request->id);

            $employee->name = $request->name;
            $employee->email = $request->email;
            $employee->phone_number = $request->phone_number;
            $employee->status = $request->status;
            $employee->position = $request->position;
            $employee->department = $request->department;
            $employee->job_type = $request->job_type;
            $employee->salary = $request->salary;
            $employee->role_name = $request->role_name;
            $employee->save();

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    // delete record
    public function deleteRecord($employee_id)
    {
        DB::beginTransaction();
        try{

            Employee::where('employee_id',$employee_id)->delete();
            module_permission::where('employee_id',$employee_id)->delete();

            DB::commit();
            Toastr::success('Delete record successfully :)','Success');
            return redirect()->route('all/employee/card');

        }catch(\Exception $e){
            DB::rollback();
            Toastr::error('Delete record fail :)','Error');
            return redirect()->back();
        }
    }
    // allemployeecard search
    public function employeeSearch(Request $request)
    {
        $users = DB::table('users')
                    ->join('employees', 'users.user_id', '=', 'employees.employee_id')
                    ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.company')
                    ->get();
        $permission_lists = DB::table('permission_lists')->get();
        $userList = DB::table('users')->get();

        // search by id
        if($request->employee_id)
        {
            $users = DB::table('users')
                        ->join('employees', 'users.user_id', '=', 'employees.employee_id')
                        ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.company')
                        ->where('employee_id','LIKE','%'.$request->employee_id.'%')
                        ->get();
        }
        // search by name
        if($request->name)
        {
            $users = DB::table('users')
                        ->join('employees', 'users.user_id', '=', 'employees.employee_id')
                        ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.company')
                        ->where('users.name','LIKE','%'.$request->name.'%')
                        ->get();
        }
        // search by name
        if($request->position)
        {
            $users = DB::table('users')
                        ->join('employees', 'users.user_id', '=', 'employees.employee_id')
                        ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.company')
                        ->where('users.position','LIKE','%'.$request->position.'%')
                        ->get();
        }

        // search by name and id
        if($request->employee_id && $request->name)
        {
            $users = DB::table('users')
                        ->join('employees', 'users.user_id', '=', 'employees.employee_id')
                        ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.company')
                        ->where('employee_id','LIKE','%'.$request->employee_id.'%')
                        ->where('users.name','LIKE','%'.$request->name.'%')
                        ->get();
        }
        // search by position and id
        if($request->employee_id && $request->position)
        {
            $users = DB::table('users')
                        ->join('employees', 'users.user_id', '=', 'employees.employee_id')
                        ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.company')
                        ->where('employee_id','LIKE','%'.$request->employee_id.'%')
                        ->where('users.position','LIKE','%'.$request->position.'%')
                        ->get();
        }
        // search by name and position
        if($request->name && $request->position)
        {
            $users = DB::table('users')
                        ->join('employees', 'users.user_id', '=', 'employees.employee_id')
                        ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.company')
                        ->where('users.name','LIKE','%'.$request->name.'%')
                        ->where('users.position','LIKE','%'.$request->position.'%')
                        ->get();
        }
         // search by name and position and id
         if($request->employee_id && $request->name && $request->position)
         {
             $users = DB::table('users')
                         ->join('employees', 'users.user_id', '=', 'employees.employee_id')
                         ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.company')
                         ->where('employee_id','LIKE','%'.$request->employee_id.'%')
                         ->where('users.name','LIKE','%'.$request->name.'%')
                         ->where('users.position','LIKE','%'.$request->position.'%')
                         ->get();
         }
        return view('form.allemployeecard',compact('users','userList','permission_lists'));
    }

    public function employeeListSearch(Request $request)
    {
        $users = DB::table('users')
                    ->join('employees', 'users.user_id', '=', 'employees.employee_id')
                    ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.company')
                    ->get(); 
        $permission_lists = DB::table('permission_lists')->get();
        $userList = DB::table('users')->get();

        // search by id
        if($request->employee_id)
        {
            $users = DB::table('users')
                        ->join('employees', 'users.user_id', '=', 'employees.employee_id')
                        ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.company')
                        ->where('employee_id','LIKE','%'.$request->employee_id.'%')
                        ->get();
        }
        // search by name
        if($request->name)
        {
            $users = DB::table('users')
                        ->join('employees', 'users.user_id', '=', 'employees.employee_id')
                        ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.company')
                        ->where('users.name','LIKE','%'.$request->name.'%')
                        ->get();
        }
        // search by name
        if($request->position)
        {
            $users = DB::table('users')
                        ->join('employees', 'users.user_id', '=', 'employees.employee_id')
                        ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.company')
                        ->where('users.position','LIKE','%'.$request->position.'%')
                        ->get();
        }

        // search by name and id
        if($request->employee_id && $request->name)
        {
            $users = DB::table('users')
                        ->join('employees', 'users.user_id', '=', 'employees.employee_id')
                        ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.company')
                        ->where('employee_id','LIKE','%'.$request->employee_id.'%')
                        ->where('users.name','LIKE','%'.$request->name.'%')
                        ->get();
        }
        // search by position and id
        if($request->employee_id && $request->position)
        {
            $users = DB::table('users')
                        ->join('employees', 'users.user_id', '=', 'employees.employee_id')
                        ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.company')
                        ->where('employee_id','LIKE','%'.$request->employee_id.'%')
                        ->where('users.position','LIKE','%'.$request->position.'%')
                        ->get();
        }
        // search by name and position
        if($request->name && $request->position)
        {
            $users = DB::table('users')
                        ->join('employees', 'users.user_id', '=', 'employees.employee_id')
                        ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.company')
                        ->where('users.name','LIKE','%'.$request->name.'%')
                        ->where('users.position','LIKE','%'.$request->position.'%')
                        ->get();
        }
        // search by name and position and id
        if($request->employee_id && $request->name && $request->position)
        {
            $users = DB::table('users')
                        ->join('employees', 'users.user_id', '=', 'employees.employee_id')
                        ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.company')
                        ->where('employee_id','LIKE','%'.$request->employee_id.'%')
                        ->where('users.name','LIKE','%'.$request->name.'%')
                        ->where('users.position','LIKE','%'.$request->position.'%')
                        ->get();
        }
        return view('form.employeelist',compact('users','userList','permission_lists'));
    }

    // employee profile with all controller user
    public function profileEmployee($user_id)
    {
        $users = DB::table('users')
                ->leftJoin('personal_information','personal_information.user_id','users.user_id')
                ->leftJoin('profile_information','profile_information.user_id','users.user_id')
                ->where('users.user_id',$user_id)
                ->first();
        $user = DB::table('users')
                ->leftJoin('personal_information','personal_information.user_id','users.user_id')
                ->leftJoin('profile_information','profile_information.user_id','users.user_id')
                ->where('users.user_id',$user_id)
                ->get(); 
        return view('form.employeeprofile',compact('user','users'));
    }

    //resigned employee
    public function resign($employeeId)
{
    // Get the employee record
    $employee = Employee::find($employeeId);

    if ($employee) {
        // Transfer the employee data to the past_employees table
        PastEmployee::create([
            'name' => $employee->name,
            'email' => $employee->email,
            'department' => $employee->department,
            'role_name' => $employee->role_name,
            'phone_number' => $employee->phone_number,
            'status' => 'resigned',  // Mark status as resigned
            'resignation_date' => now(),  
            'resignation_reason' => 'Voluntary resignation', 
        ]);

        // Delete the employee record from employees table
        $employee->delete();

        // Optionally, return a success message or redirect
        return redirect()->back()->with('success', 'Employee resigned successfully and moved to past employees.');
    }

    return redirect()->back()->with('error', 'Employee not found.');
}

    public function pastEmployeePage(){
        $pastEmployees = PastEmployee::all();
        return view('form.past_employee_list', compact('pastEmployees'));
    }

    /** page departments */
    public function index()
    {
        $departments = DB::table('departments')->get();
        return view('form.departments',compact('departments'));
    }

    /** save record department */
    public function saveRecordDepartment(Request $request)
    {
        $request->validate([
            'department'        => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try{

            $department = department::where('department',$request->department)->first();
            if ($department === null)
            {
                $department = new department;
                $department->department = $request->department;
                $department->save();
    
                DB::commit();
                Toastr::success('Add new department successfully :)','Success');
                return redirect()->route('form/departments/page');
            } else {
                DB::rollback();
                Toastr::error('Add new department exits :)','Error');
                return redirect()->back();
            }
        }catch(\Exception $e){
            DB::rollback();
            Toastr::error('Add new department fail :)','Error');
            return redirect()->back();
        }
    }

    /** update record department */
    public function updateRecordDepartment(Request $request)
    {
        DB::beginTransaction();
        try{
            // update table departments
            $department = [
                'id'=>$request->id,
                'department'=>$request->department,
            ];
            department::where('id',$request->id)->update($department);
        
            DB::commit();
            Toastr::success('updated record successfully :)','Success');
            return redirect()->route('form/departments/page');
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('updated record fail :)','Error');
            return redirect()->back();
        }
    }

    /** delete record department */
    public function deleteRecordDepartment(Request $request) 
    {
        try {

            department::destroy($request->id);
            Toastr::success('Department deleted successfully :)','Success');
            return redirect()->back();
        
        } catch(\Exception $e) {

            DB::rollback();
            Toastr::error('Department delete fail :)','Error');
            return redirect()->back();
        }
    }

    /** page designations */
    public function designationsIndex()
    {
        return view('form.designations');
    }

    /** page time sheet */
    public function timeSheetIndex()
    {
        // Retrieve all timesheets with their associated interviewers
        $timesheets = Timesheet::with('interviewer')->get();

        return view('form.timesheet', compact('timesheets'));
    }

    /** page overtime */
    public function overTimeIndex()
    {
        return view('form.overtime');
    }

    public function search(Request $request)
    {
        $query = Employee::query();

        if ($request->has('name') && $request->name != '') {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->has('department') && $request->department != '') {
            $query->where('department', 'like', '%' . $request->department . '%');
        }
        if ($request->has('role_name') && $request->role_name != '') {
            $query->where('role_name', 'like', '%' . $request->role_name . '%');
        }
        if ($request->has('employee_id') && $request->employee_id != '') {
            $query->where('employee_id', 'like', '%' . $request->employee_id . '%');
        }

        $employees = $query->get();

        return response()->json(['employees' => $employees]);
    }

    public function showByDepartment($department)
    {
        $employees = Employee::where('department', $department)->get();

        if ($employees->isEmpty()) {
            return view('form.department_employee_list', [
                'employees' => $employees,
                'department' => $department,
                'message' => 'No employees found in this department.'
            ]);
        }

        return view('form.department_employee_list', compact('employees', 'department'));
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        
        // Move employee data to past employees table (you need to create this table and model)
        PastEmployee::create($employee->toArray());
        
        // Delete the employee
        $employee->delete();

        return response()->json(['success' => true]);
    }

    public function searchByDepartment(Request $request, $department)
    {
        $query = Employee::where('department', $department);

        if ($request->has('employee_id') && $request->employee_id != '') {
            $query->where('employee_id', 'like', '%' . $request->employee_id . '%');
        }
        if ($request->has('name') && $request->name != '') {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->has('role_name') && $request->role_name != '') {
            $query->where('role_name', 'like', '%' . $request->role_name . '%');
        }

        $employees = $query->get();

        return response()->json(['employees' => $employees]);
    }

    public function exportPDF()
    {
        $employees = Employee::all();

        
        if ($employees->isEmpty()) {
            $employees = collect([['#'=>'','Employee ID' => '', 'Name' => '', 'Department' => '', 'Role' => '', 'Email' => '', 'Phone Number' => '', 'Status' => '', 'Join Date' => '']]);
        }

        $pdf = PDF::loadView('pdf.employees', compact('employees'));
        return $pdf->download('employees.pdf');
    }
    public function exportExcel()
    {
        return Excel::download(new EmployeesExport, 'employee.xlsx');
    }

    //past employees export
    
    public function exportPDFforPastEmployee()
    {
        $pastemployees = PastEmployee::all();

        
        if ($pastemployees->isEmpty()) {
            $pastemployees = collect([['#'=>'','Name' => '', 'Role' => '', 'Email' => '', 'Phone Number' => '', 'Status' => '', 'Resignation Date' => '', 'Resignation Reason' => '']]);
        }

        $pdf = PDF::loadView('pdf.pastemployees', compact('pastemployees'));
        return $pdf->download('Past_employees.pdf');
    }
    public function exportExcelforPastEmployee()
    {
        return Excel::download(new PastEmployeesExport, 'Pastemployee.xlsx');
    }


}
