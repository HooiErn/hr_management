<!DOCTYPE html>
<html>
<head>
    <title>Leaves Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Leaves Report</h1>
    <table>
        <thead>
            <tr>
                <th>UserID</th>
                </th>Leave Type</th>
                <th>From Date</th>
                <th>To Date</th>
                <th>Days</th>
                <th>Leave Reason</th>
                <th>Leave Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($leaves as $leave)
                <tr>
                    <td>{{ $leave->user_id }}</td>
                    <td>{{ $leave->leave_type }}</td>
                    <td>{{ $leave->from_date }}</td>
                    <td>{{ $leave->to_date }}</td>
                    <td>{{ $leave->day }}</td>
                    <td>{{ $leave->leave_reason }}</td>
                    <td>{{ $leave->leave_status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 