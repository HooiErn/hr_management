<!DOCTYPE html>
<html>
<head>
    <title>Employees List</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px; /* Reduced font size to fit more columns */
        }
        th, td {
            border: 1px solid black;
            padding: 4px;
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
        .contract-status {
            color: #28a745;
        }
        .no-contract {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="report-header">
        <h1>Employees List</h1>
    </div>
    
    <div class="report-date">
        Generated on: {{ date('d F, Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Employee ID</th>
                <th>Name</th>
                <th>Department</th>
                <th>Position</th>
                <th>Role</th>
                <th>Job Type</th>
                <th>Salary (RM)</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Status</th>
                <th>Join Date</th>
                <th>Contract Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employees as $employee)
                <tr>
                    <td>{{ $employee->employee_id }}</td>
                    <td>{{ $employee->name }}</td>
                    <td>{{ $employee->department }}</td>
                    <td>{{ $employee->position }}</td>
                    <td>{{ $employee->role_name }}</td>
                    <td>{{ $employee->job_type }}</td>
                    <td>{{ number_format($employee->salary, 2) }}</td>
                    <td>{{ $employee->email }}</td>
                    <td>{{ $employee->phone_number }}</td>
                    <td>{{ $employee->status }}</td>
                    <td>{{ $employee->join_date ? date('Y-m-d', strtotime($employee->join_date)) : '' }}</td>
                    <td>
                        @if($employee->contracts)
                            <span class="contract-status">Available</span>
                        @else
                            <span class="no-contract">Not Available</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px; font-size: 11px;">
        <p><strong>Note:</strong> This is an automatically generated report.</p>
    </div>
</body>
</html> 