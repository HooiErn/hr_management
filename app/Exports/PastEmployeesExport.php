<?php

namespace App\Exports;

use App\Models\PastEmployee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PastEmployeesExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return PastEmployee::all();
    }

    public function headings(): array
    {
        return [
            '#',
            'Name',
            'Role',
            'Email',
            'Phone Number',
            'Status',
            'Resignation Date',
            'Resignation Reason',
        ];
    }
}
