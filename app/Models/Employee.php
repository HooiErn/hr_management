<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    
        protected $fillable = [
            'name', 'email', 'employee_id', 'age', 'race', 'highest_education', 
            'work_experiences', 'ic_number', 'cv_upload', 'birth_date', 'gender', 
            'phone_number', 'status', 'role_name', 'avatar', 'position', 'department', 
            'leaves', 'contracts', 'salary','job_type', 'company', 'join_date', 'password'
        ];
    
        // generate employee_id auto
        protected static function booted()
        {
            static::creating(function ($employee) {
                if (empty($employee->employee_id)) {
                    $employee->employee_id = 'EMP' . str_pad(self::generateEmployeeId(), 3, '0', STR_PAD_LEFT);
                }
            });
        }
    
        // generate unique employee ID (EMP001, EMP002, ...)
        public static function generateEmployeeId()
        {
            $latestEmployee = self::latest('id')->first();
    
            if (!$latestEmployee) {
                return 1;
            }
    

            $lastEmployeeId = (int) substr($latestEmployee->employee_id, 3);
    
            return $lastEmployeeId + 1;
        }
    }
    

