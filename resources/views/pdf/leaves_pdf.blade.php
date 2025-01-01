<!DOCTYPE html>
<html>
<head>
    <title>Leaves Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 12px;
        }
        th, td {
            border: 1px solid black;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .report-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .report-date {
            text-align: right;
            margin-bottom: 10px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="report-header">
        <h1>Leaves Report</h1>
    </div>
    
    <div class="report-date">
        Generated on: {{ date('d F, Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Employee ID</th>
                <th>Employee Name</th>
                <th>Department</th>
                <th>Position</th>
                <th>Leave Type</th>
                <th>From Date</th>
                <th>To Date</th>
                <th>Days</th>
                <th>Leave Status</th>
                <th>Reason</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($leaves as $leave)
                <tr>
                    <td>{{ $leave->user_id }}</td>
                    <td>{{ $leave->employee_name }}</td>
                    <td>{{ $leave->department }}</td>
                    <td>{{ $leave->position }}</td>
                    <td>{{ $leave->leave_type }}</td>
                    <td>{{ date('d M Y', strtotime($leave->from_date)) }}</td>
                    <td>{{ date('d M Y', strtotime($leave->to_date)) }}</td>
                    <td>{{ $leave->day }}</td>
                    <td>
                        @if($leave->leave_status === 'paid')
                            Paid Leave
                        @else
                            Unpaid Leave
                        @endif
                    </td>
                    <td>{{ $leave->leave_reason }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px; font-size: 11px;">
        <p><strong>Note:</strong> This is an automatically generated report.</p>
    </div>
</body>
</html> 