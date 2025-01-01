<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EmployeesExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Employee::select(
            'employee_id',
            'name',
            'department',
            'position',
            'role_name',
            'job_type',
            'salary',
            'email',
            'phone_number',
            'status',
            'join_date'
        )->get();
    }

    public function headings(): array
    {
        return [
            'Employee ID',
            'Name',
            'Department',
            'Position',
            'Role',
            'Job Type',
            'Salary (RM)',
            'Email',
            'Phone Number',
            'Status',
            'Join Date'
        ];
    }

    public function map($employee): array
    {
        return [
            $employee->employee_id,
            $employee->name,
            $employee->department,
            $employee->position,
            $employee->role_name,
            $employee->job_type,
            number_format($employee->salary, 2), // Format salary with 2 decimal places
            $employee->email,
            $employee->phone_number,
            $employee->status,
            $employee->join_date ? Carbon::parse($employee->join_date)->format('Y-m-d') : ''
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Make header row bold
            'G' => ['numberFormat' => ['formatCode' => '#,##0.00']], // Format salary column
        ];
    }
}
