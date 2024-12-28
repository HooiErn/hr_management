<!DOCTYPE html>
<html>
<head>
    <title>Employees List</title>
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
    <h1>Employees List</h1>
    <table>
        <thead>
            <tr>
                <th>Employee ID</th>
                <th>Name</th>
                <th>Department</th>
                <th>Role</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Status</th>
                <th>Join Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employees as $employee)
                <tr>
                    <td>{{ $employee->employee_id }}</td>
                    <td>{{ $employee->name }}</td>
                    <td>{{ $employee->department }}</td>
                    <td>{{ $employee->role_name }}</td>
                    <td>{{ $employee->email }}</td>
                    <td>{{ $employee->phone_number }}</td>
                    <td>{{ $employee->status }}</td>
                    <td>{{ $employee->join_date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 