<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RolesPermissions;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Company;
use DB;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Hash;
use App\Rules\MatchOldPassword;

class SettingController extends Controller
{
    // company/settings/page
    public function companySettings()
    {
        $company = Company::first(); 
        return view('settings.companysettings', compact('company'));
    }
    //Save Company Detail
    public function saveCompanySettings(Request $request)
    {
        $validatedData = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'phone_number' => 'nullable|string|max:20',
            'mobile_number' => 'nullable|string|max:20',
            'fax' => 'nullable|string|max:20',
            'website_url' => 'nullable|url|max:255',
        ]);

        Company::updateOrCreate(
            ['id' => 1], // can adjust this logic if multiple companies are allowed
            $validatedData
        );

        return redirect()->back()->with('success', 'Company details saved successfully!');
    }
    
    // Roles & Permissions 
    public function rolesPermissions()
    {
        $rolesPermissions = RolesPermissions::All();
        return view('settings.rolespermissions',compact('rolesPermissions'));
    }

    // add role permissions
    public function addRecord(Request $request)
    {
        $request->validate([
            'roleName' => 'required|string|max:255',
        ]);
        
        DB::beginTransaction();
        try{

            $roles = RolesPermissions::where('permissions_name', '=', $request->roleName)->first();
            if ($roles === null)
            {
                // roles name doesn't exist
                $role = new RolesPermissions;
                $role->permissions_name = $request->roleName;
                $role->save();
            }else{

                // roles name exits
                DB::rollback();
                Toastr::error('Roles name exits :)','Error');
                return redirect()->back();
            }

            DB::commit();
            Toastr::success('Create new role successfully :)','Success');
            return redirect()->back();
        }catch(\Exception $e){
            DB::rollback();
            Toastr::error('Add Role fail :)','Error');
            return redirect()->back();
        }
    }

    // edit roles permissions
    public function editRolesPermissions(Request $request)
    {
        DB::beginTransaction();
        try{
            $id        = $request->id;
            $roleName  = $request->roleName;
            
            $update = [
                'id'               => $id,
                'permissions_name' => $roleName,
            ];

            RolesPermissions::where('id',$id)->update($update);
            DB::commit();
            Toastr::success('Role Name updated successfully :)','Success');
            return redirect()->back();

        }catch(\Exception $e){
            DB::rollback();
            Toastr::error('Role Name update fail :)','Error');
            return redirect()->back();
        }
    }
    // delete roles permissions
    public function deleteRolesPermissions(Request $request)
    {
        try{
            RolesPermissions::destroy($request->id);
            Toastr::success('Role Name deleted successfully :)','Success');
            return redirect()->back();
        
        }catch(\Exception $e){
            DB::rollback();
            Toastr::error('Role Name delete fail :)','Error');
            return redirect()->back();
        }
    }
// get company info
    public function getCompanyInfo()
    {
        try {
            $settings = DB::table('companies')->first();
            return response()->json([
                'success' => true,
                'company' => [
                    'company_name' => $settings->company_name,
                    'address' => $settings->address,
                    'city' => $settings->city,
                    'state' => $settings->state,
                    'postal_code' => $settings->postal_code,
                    'contact_person' => $settings->contact_person,
                    'phone' => $settings->phone,
                    'email' => $settings->email
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching company info: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching company information'
            ]);
        }
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => 'required|string|min:8|different:current_password',
            'new_confirm_password' => 'required|same:new_password'
        ], [
            'current_password.required' => 'Please enter your current password',
            'new_password.required' => 'Please enter a new password',
            'new_password.min' => 'New password must be at least 8 characters',
            'new_password.different' => 'New password must be different from current password',
            'new_confirm_password.same' => 'Password confirmation does not match',
        ]);

        try {
            auth()->user()->update([
                'password' => Hash::make($request->new_password)
            ]);

            Toastr::success('Password changed successfully!', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Error changing password!', 'Error');
            return redirect()->back();
        }
    }
}
