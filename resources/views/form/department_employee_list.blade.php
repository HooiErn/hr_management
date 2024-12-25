@extends('layouts.master')
@section('content')

    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Employees in {{ $department }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('employees/list') }}">Employee List</a></li>
                            <li class="breadcrumb-item active">Department: {{ $department }}</li>
                        </ul>
                    </div>
                </div>
            </div>

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
                            <input type="text" class="form-control floating" name="role_name" id="search-role" placeholder="Search by Role">
                            <label class="focus-label">Role</label>
                        </div>
                    </div>
                </div>
            </form>
            <!-- /Search Filter -->

            @if(isset($message))
                <div class="alert alert-warning">{{ $message }}</div>
            @else
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped custom-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Employee ID</th>
                                        <th>Name</th>
                                        <th>Role</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($employees as $employee)
                                        <tr>
                                            <td>{{ $employee->employee_id }}</td>
                                            <td>{{ $employee->name }}</td>
                                            <td>{{ $employee->role_name }}</td>
                                            <td>{{ $employee->email }}</td>
                                            <td>{{ $employee->phone_number }}</td>
                                            <td>{{ $employee->status }}</td>
                                            <td>
                                                <button class="btn btn-danger delete-employee" data-id="{{ $employee->id }}">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No employees found in this department</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchForm = document.getElementById('search-form');
            const employeeTableBody = document.getElementById('employee-table-body');

            // Function to perform AJAX search
            function performSearch() {
                const formData = new FormData(searchForm);
                fetch("{{ route('employees/searchByDepartment', $department) }}", {
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
                        row.innerHTML = `
                            <td>${employee.employee_id}</td>
                            <td>${employee.name}</td>
                            <td>${employee.role_name}</td>
                            <td>${employee.email}</td>
                            <td>${employee.phone_number}</td>
                            <td>${employee.status}</td>
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

@endsection