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
                            <input type="text" class="form-control floating" name="position" id="search-position" placeholder="Search by Position">
                            <label class="focus-label">Position</label>
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
                                        <th>Position</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="employee-table-body">
                                    @forelse ($employees as $employee)
                                        <tr>
                                            <td>{{ $employee->employee_id }}</td>
                                            <td>{{ $employee->name }}</td>
                                            <td>{{ $employee->position }}</td>
                                            <td>{{ $employee->email }}</td>
                                            <td>{{ $employee->phone_number }}</td>
                                            <td>{{ $employee->status }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No employees found in this department</td>
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

    <!-- Add Employee Modal -->
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEmployeeModalLabel">Add New Employee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addEmployeeForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="birthDate">Birth Date</label>
                            <input type="date" class="form-control" id="birthDate" name="birthDate" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Employee</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Employee Modal -->
    <div class="modal fade" id="editEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEmployeeModalLabel">Edit Employee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editEmployeeForm">
                    <div class="modal-body">
                        <input type="hidden" id="edit-employee-id" name="id">
                        <div class="form-group">
                            <label for="edit-name">Name</label>
                            <input type="text" class="form-control" id="edit-name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-email">Email</label>
                            <input type="email" class="form-control" id="edit-email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-birthDate">Birth Date</label>
                            <input type="date" class="form-control" id="edit-birthDate" name="birthDate" required>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Employee</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // JavaScript for handling the add and edit employee modals
        document.addEventListener('DOMContentLoaded', function() {
            // Add Employee Form Submission
            document.getElementById('addEmployeeForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                fetch("{{ route('all/employee/save') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload(); // Reload the page to see the new employee
                    } else {
                        alert('Error adding employee: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            });

            // Event listener for edit button
            document.querySelectorAll('.edit-employee').forEach(button => {
                button.addEventListener('click', function() {
                    const employeeId = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    const email = this.getAttribute('data-email');
                    const birthDate = this.getAttribute('data-birthdate');

                    // Populate the modal fields
                    document.getElementById('edit-employee-id').value = employeeId;
                    document.getElementById('edit-name').value = name;
                    document.getElementById('edit-email').value = email;
                    document.getElementById('edit-birthDate').value = birthDate;
                });
            });

            // Handle edit form submission
            document.getElementById('editEmployeeForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                fetch("{{ route('all/employee/update') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload(); // Reload the page to see the updated employee
                    } else {
                        alert('Error updating employee: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
    </script>

    @section('script')
    <script>
    $(document).ready(function() {
        // Search functionality
        $('#search-form input').on('input', function() {
            var searchData = {
                _token: '{{ csrf_token() }}',
                department: '{{ $department }}',
                employee_id: $('#search-employee-id').val(),
                name: $('#search-name').val(),
                position: $('#search-position').val()
            };

            // Perform search immediately when user types
            $.ajax({
                url: "{{ route('department/employees/search') }}",
                method: 'POST',
                data: searchData,
                success: function(response) {
                    var tbody = $('#employee-table-body');
                    tbody.empty();
                    
                    if (response.employees.length === 0) {
                        tbody.append(`
                            <tr>
                                <td colspan="6" class="text-center">No employees found</td>
                            </tr>
                        `);
                    } else {
                        response.employees.forEach(function(employee) {
                            var statusBadge = employee.leave_status === 'paid' 
                                ? '<span class="badge badge-success">Paid Leave</span>'
                                : '<span class="badge badge-warning">Unpaid Leave</span>';

                            var row = `
                                <tr>
                                    <td>${employee.employee_id || ''}</td>
                                    <td>${employee.name || ''}</td>
                                    <td>${employee.position || ''}</td>
                                    <td>${employee.email || ''}</td>
                                    <td>${employee.phone_number || ''}</td>
                                    <td>${statusBadge}</td>
                                </tr>
                            `;
                            tbody.append(row);
                        });
                    }
                },
                error: function(xhr) {
                    console.error('Search error:', xhr);
                    toastr.error('An error occurred while searching');
                }
            });
        });
    });
    </script>
    @endsection

@endsection