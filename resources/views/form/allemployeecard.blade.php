@extends('layouts.master')
@section('content')

    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Employee List</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">Employee List</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <!-- Search Filter -->
            <form id="search-form">
                @csrf
                <div class="row filter-row">
                    <div class="col-sm-6 col-md-3">  
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating" name="employee_id" id="search-employee-id" placeholder="Search by Employee ID">
                            <label class="focus-label">Employee ID</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">  
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating" name="name" id="search-name" placeholder="Search by Name">
                            <label class="focus-label">Name</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3"> 
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating" name="department" id="search-department" placeholder="Search by Department">
                            <label class="focus-label">Department</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3"> 
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating" name="role_name" id="search-role-name" placeholder="Search by Role">
                            <label class="focus-label">Role</label>
                        </div>
                    </div>
                </div>
            </form>
            <!-- /Search Filter -->

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table mb-0 datatable">
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>Name</th>
                                    <th>Department</th>
                                    <th>Role</th>
                                    <th>Email</th>
                                    <th>Phone Number</th>
                                    <th>Status</th>
                                    <th>Join Date</th>
                                </tr>
                            </thead>
                            <tbody id="employee-table-body">
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->employee_id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td><a href="{{ route('employees/byDepartment', $user->department) }}">{{ $user->department }}</a></td>
                                        <td>{{ $user->role_name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone_number }}</td>
                                        <td class="text-center">
                                            @if($user->status == 'active')
                                                <div class="btn btn-success btn-sm btn-rounded">
                                                    <i class="fa fa-dot-circle-o"></i> Active
                                                </div>
                                            @else
                                                <div class="dropdown action-label">
                                                    <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa fa-dot-circle-o text-danger"></i> {{ ucfirst($user->status) }}
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="#" onclick="confirmResignation({{ $user->id }})">
                                                            <i class="fa fa-dot-circle-o text-warning"></i> Resigned
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                        <td>{{ $user->join_date }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Wrapper -->

    @section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchForm = document.getElementById('search-form');
            const employeeTableBody = document.getElementById('employee-table-body');

            // Function to perform AJAX search
            function performSearch() {
                const formData = new FormData(searchForm);
                fetch("{{ route('employees/search') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    employeeTableBody.innerHTML = '';
                    data.employees.forEach(employee => {
                        const row = document.createElement('tr');
                        const departmentLink = employee.department 
                            ? `<a href="{{ route('employees/byDepartment', '') }}/${employee.department}">${employee.department}</a>`
                            : 'No Department';

                        row.innerHTML = `
                            <td>${employee.employee_id}</td>
                            <td>${employee.name}</td>
                            <td>${departmentLink}</td>
                            <td>${employee.role_name}</td>
                            <td>${employee.email}</td>
                            <td>${employee.phone_number}</td>
                            <td>${employee.join_date}</td>
                        `;
                        employeeTableBody.appendChild(row);
                    });
                })
                .catch(error => console.error('Error:', error));
            }

            // Event listener for search input
            searchForm.addEventListener('input', performSearch);
        });
    </script>
    <!-- Resignation Confirmation Modal -->
    <script>
    function confirmResignation(employeeId) {
        if (confirm("Are you sure this employee is resigning?")) {
            // Send the resignation request to the backend
            window.location.href = '/resign/' + employeeId;
        }
    }
    </script>
    @endsection

@endsection
