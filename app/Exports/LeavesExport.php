<?php
namespace App\Exports;

use App\Models\LeavesAdmin;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use DB;

class LeavesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return DB::table('leaves_admins')
            ->join('employees', 'employees.employee_id', '=', 'leaves_admins.user_id')
            ->select('leaves_admins.*', 
                     'employees.name as employee_name', 
                     'employees.position', 
                     'employees.department')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Employee ID',
            'Employee Name',
            'Department',
            'Position',
            'Leave Type',
            'From Date',
            'To Date',
            'Days',
            'Leave Status',
            'Reason'
        ];
    }

    public function map($leave): array
    {
        return [
            $leave->user_id,
            $leave->employee_name,
            $leave->department,
            $leave->position,
            $leave->leave_type,
            date('d M Y', strtotime($leave->from_date)),
            date('d M Y', strtotime($leave->to_date)),
            $leave->day,
            $leave->leave_status === 'paid' ? 'Paid Leave' : 'Unpaid Leave',
            $leave->leave_reason
        ];
    }
} 