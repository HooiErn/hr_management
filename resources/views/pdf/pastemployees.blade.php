<!DOCTYPE html>
<html>
<head>
    <title>Past Employees</title>
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
    <h1>Past Employees List</h1>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Role</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Status</th>
                <th>Resignation Date</th>
                <th>Resignation Reason</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pastemployees as $employee)
                <tr>
                    <td>{{ $employee->name }}</td>
                    <td>{{ $employee->role_name }}</td>
                    <td>{{ $employee->email }}</td>
                    <td>{{ $employee->phone_number }}</td>
                    <td>{{ $employee->status }}</td>
                    <td>{{ $employee->resignation_date }}</td>
                    <td>{{ $employee->resignation_reason }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 