@extends('layouts.master')
@section('content')

    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Employee List</h3>
                        <button type="button" class="btn" style="background-color:#5a83d2; border:none;" data-toggle="modal" data-target="#addEmployeeModal">
                            Add Employee
                        </button>
                    </div>
                </div>
            </div>

            <!-- Search and Entries Selector -->
            <div class="row filter-row">
                <div class="col-sm-6 col-md-3">
                    <div class="form-group">
                        <label>Show Entries</label>
                        <select class="form-control" id="entries-limit">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus">
                        <input type="text" class="form-control floating" id="search-input">
                        <label class="focus-label">Search Employee</label>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table mb-0" id="employees-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Department</th>
                                    <th>Role</th>
                                    <th>Email</th>
                                    <th>Phone Number</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="employee-table-body">
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td><a href="{{ route('employees/byDepartment', $user->department) }}">{{ $user->department }}</a></td>
                                        <td>{{ $user->role_name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone_number }}</td>
                                        <td>{{ $user->status }}</td>
                                        <td>
                                            <button class="btn btn-warning edit-employee" data-id="{{ $user->id }}" data-toggle="modal" data-target="#editEmployeeModal" data-name="{{ $user->name }}" data-email="{{ $user->email }}" data-birthdate="{{ $user->birth_date }}" data-gender="{{ $user->gender }}" data-company="{{ $user->company }}">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select class="form-control" id="gender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="employee_id">Employee ID</label>
                            <input type="text" class="form-control" id="employee_id" name="employee_id" required>
                        </div>
                        <div class="form-group">
                            <label for="company">Company</label>
                            <input type="text" class="form-control" id="company" name="company" required>
                        </div>
                        <!-- Add other fields as necessary based on your migration -->
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
                        <div class="form-group">
                            <label for="edit-gender">Gender</label>
                            <select class="form-control" id="edit-gender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit-company">Company</label>
                            <input type="text" class="form-control" id="edit-company" name="company" required>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Existing code...

            // Event listener for edit button
            document.querySelectorAll('.edit-employee').forEach(button => {
                button.addEventListener('click', function() {
                    const employeeId = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    const email = this.getAttribute('data-email');
                    const birthDate = this.getAttribute('data-birthdate');
                    const gender = this.getAttribute('data-gender');
                    const company = this.getAttribute('data-company');

                    // Populate the modal fields
                    document.getElementById('edit-employee-id').value = employeeId;
                    document.getElementById('edit-name').value = name;
                    document.getElementById('edit-email').value = email;
                    document.getElementById('edit-birthDate').value = birthDate;
                    document.getElementById('edit-gender').value = gender;
                    document.getElementById('edit-company').value = company;
                });
            });

            // Handle edit form submission
            document.getElementById('editEmployeeForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                fetch("{{ route('employees.update') }}", { // Adjust the route as necessary
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

    <script>
    $(document).ready(function() {
        var currentPage = 1;
        var entriesPerPage = 10;

        // Handle entries limit change
        $('#entries-limit').on('change', function() {
            entriesPerPage = parseInt($(this).val());
            currentPage = 1;
            performSearch();
        });

        // Handle search input
        $('#search-input').on('input', function() {
            currentPage = 1;
            performSearch();
        });

        function performSearch() {
            $.ajax({
                url: "{{ route('employees/search') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    search: $('#search-input').val(),
                    limit: entriesPerPage,
                    page: currentPage
                },
                success: function(response) {
                    if (response.success) {
                        var tbody = $('#employees-table tbody');
                        tbody.empty();

                        if (response.employees.data.length === 0) {
                            tbody.append('<tr><td colspan="11" class="text-center">No employees found</td></tr>');
                            return;
                        }

                        response.employees.data.forEach(function(employee) {
                            var row = `
                                <tr>
                                    <td>${employee.employee_id}</td>
                                    <td>${employee.name}</td>
                                    <td>${employee.department}</td>
                                    <td>${employee.position}</td>
                                    <td>${employee.role_name}</td>
                                    <td>${employee.job_type}</td>
                                    <td>${employee.email}</td>
                                    <td>${employee.phone_number}</td>
                                    <td>${employee.status}</td>
                                    <td>${formatDate(employee.join_date)}</td>
                                    <td class="text-right">
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="{{ url('employee/edit') }}/${employee.employee_id}"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                <a class="dropdown-item" href="#" onclick="deleteEmployee('${employee.employee_id}')"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            `;
                            tbody.append(row);
                        });

                        // Update pagination
                        updatePagination(response.employees);
                    }
                },
                error: function(xhr) {
                    console.error('Search error:', xhr);
                    toastr.error('An error occurred while searching');
                }
            });
        }

        function updatePagination(employees) {
            var pagination = $('.pagination');
            pagination.empty();

            var totalPages = Math.ceil(employees.total / entriesPerPage);

            // Previous button
            pagination.append(`
                <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${currentPage - 1}">Previous</a>
                </li>
            `);

            // Page numbers
            for (var i = 1; i <= totalPages; i++) {
                pagination.append(`
                    <li class="page-item ${currentPage === i ? 'active' : ''}">
                        <a class="page-link" href="#" data-page="${i}">${i}</a>
                    </li>
                `);
            }

            // Next button
            pagination.append(`
                <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${currentPage + 1}">Next</a>
                </li>
            `);

            // Handle pagination clicks
            $('.page-link').click(function(e) {
                e.preventDefault();
                currentPage = parseInt($(this).data('page'));
                performSearch();
            });
        }

        function formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }

        // Initial search
        performSearch();
    });
    </script>

@endsection 