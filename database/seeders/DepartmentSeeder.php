<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        $departments = [
            ['department' => 'Web Development'],
            ['department' => 'IT Support'],
            ['department' => 'Human Resources'],
            ['department' => 'Marketing'],
            ['department' => 'Sales'],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
