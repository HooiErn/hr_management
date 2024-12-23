<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $employees = [
            [
                'name' => 'Alice Smith',
                'email' => 'alice.smith@example.com',
                'age' => 30,
                'race' => 'Chinese',
                'highest_education' => 'Bachelor',
                'work_experiences' => 5,
                'ic_number' => '123456789012',
                'cv_upload' => 'path/to/cv1.pdf',
                'birth_date' => '1993-01-01',
                'gender' => 'Female',
                'employee_id' => 'EMP001',
                'company' => 'HRTech Inc.',
                'join_date' => '2020-01-15',
                'phone_number' => '123-456-7890',
                'status' => 'Active',
                'role_name' => 'Developer',
                'avatar' => 'path/to/avatar1.jpg',
                'position' => 'Software Engineer',
                'department' => 'Web Development',
                'leaves' => '0',
                'contracts' => 'Full-time',
                'password' => bcrypt('password123'), // Ensure to hash the password
            ],
            // Add more employees as needed
        ];

        foreach ($employees as $employee) {
            Employee::create($employee);
        }
    }
}
