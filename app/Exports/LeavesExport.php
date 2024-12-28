<?php
namespace App\Exports;

use App\Models\LeavesAdmin;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LeavesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return LeavesAdmin::with('employee') // Assuming you have a relationship defined
            ->get(['user_id', 'leave_type', 'from_date', 'to_date', 'day', 'leave_reason', 'leave_status']);
    }

    public function headings(): array
    {
        return [
            'User ID',
            'Leave Type',
            'From Date',
            'To Date',
            'Days',
            'Leave Reason',
            'Leave Status',
        ];
    }
} 