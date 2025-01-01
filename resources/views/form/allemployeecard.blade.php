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
                    <button type="button" class="btn" style="color:white;background-color:#5a83d2; border:none;" data-toggle="modal" data-target="#addEmployeeModal">
                    <i class="fa fa-plus"></i> Add Employee
                    </button>
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

            <!-- Export Buttons -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <a href="{{ route('employees.export.excel') }}" class="btn btn-success">Export Excel</a>
                    <a href="{{ route('employees.export.pdf') }}" class="btn btn-danger">Export PDF</a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table mb-0 datatable">
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>Name</th>
                                    <th>Department</th>
                                    <th>Position</th>
                                    <th>Email</th>
                                    <th>Phone Number</th>
                                    <th>Status</th>
                                    <th>Join Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="employee-table-body">
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->employee_id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td><a href="{{ route('employees/byDepartment', $user->department) }}">{{ $user->department }}</a></td>
                                        <td>{{ $user->position }}</td>
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
                                        <td>
                                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editEmployeeModal" data-id="{{ $user->id }}" data-name="{{ $user->name }}" data-email="{{ $user->email }}" data-phone="{{ $user->phone_number }}" data-salary="{{ $user->salary }}" data-contracts="{{ $user->contracts }}">
                                                <i class="fa fa-pencil"></i> Edit
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
 <!--/Page Wrapper-->
  <!-- Add Employee Modal -->
  <div class="modal fade" id="addEmployeeModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Employee</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="addEmployeeForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <!-- Column 1: Personal Information -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input type="email"  @error('email') is-invalid @enderror" name="email" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>IC Number <span class="text-danger">*</span></label>
                                    <input type="text" name="ic_number" class="form-control" maxlength="12" required>
                                </div>
                                <div class="form-group">
                                    <label>Birth Date</label>
                                    <input type="date" name="birth_date" class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Age</label>
                                    <input type="number" name="age" class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Gender</label>
                                    <select name="gender" class="form-control" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Column 2: Additional Information -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input type="text" name="phone_number" class="form-control" placeholder="e.g., 60123456789" required>
                                </div>
                                <div class="form-group">
                                        <label>Race <span class="text-danger">*</span></label>
                                        <select class="form-control" name="race" required>
                                            <option value="" disabled selected>Select Race</option>
                                            <option value="Malay">Malay</option>
                                            <option value="Chinese">Chinese</option>
                                            <option value="Indian">Indian</option>
                                            <option value="Others">Others</option>
                                        </select>
                                    </div>
                                <div class="form-group">
                                        <label>Highest Education<span class="text-danger">*</span></label>
                                        <select class="form-control @error('highest_education') is-invalid @enderror" name="highest_education" required>
                                            <option value="" disabled selected>Select Education</option>
                                            <option value="Secondary">Secondary</option>
                                            <option value="Foundation">Foundation</option>
                                            <option value="Diploma">Diploma</option>
                                            <option value="Degree">Degree</option>
                                            <option value="Master">Master</option>
                                            <option value="PhD">PhD</option>
                                        </select>
                                    </div>
                                <div class="form-group">
                                    <label>Work Experience (Years)</label>
                                    <input type="number" name="work_experiences" class="form-control" min="0" max="100" required>
                                </div>
                                <div class="form-group">
                                    <label>CV Upload (PDF only)</label>
                                    <input type="file" name="cv_upload" class="form-control" accept=".pdf" required>
                                    <small class="text-muted">Max size: 2MB</small>
                                </div>
                                <div class="form-group">
                                    <label>Contract Document (PDF) <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" name="contract_file" accept=".pdf" required>
                                    <small class="text-muted">Upload contract document in PDF format (max 5MB)</small>
                                </div>
                                <div class="form-group">
                                    <label>Status <span class="text-danger">*</span></label>
                                    <input type="text" name="status" class="form-control" value="Active" readonly>
                                </div>
                            </div>
                            <!-- Column 3: Employment Details -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Role <span class="text-danger">*</span></label>
                                    <input type="text" name="role_name" class="form-control" value="Employee" readonly required>
                                </div>
                                <div class="form-group">
                                    <label>Position</label>
                                    <input type="text" name="position" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Department</label>
                                    <select name="department" class="form-control">
                                        @foreach ($department as $item)
                                            <option value="{{$item->department}}">{{$item->department}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Job Type <span class="text-danger">*</span></label>
                                    <select name="job_type" class="form-control" required>
                                        <option value="Full-time">Full-time</option>
                                        <option value="Part-time">Part-time</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Salary <span class="text-danger">*</span></label>
                                    <input type="number" name="salary" class="form-control" required>
                                </div>
                                <!-- hidden password -->
                                <div class="form-group">
                                    <input type="hidden" name="password" class="form-control"  value="12345678" required minlength="8">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Edit Employee Modal -->
    <div class="modal fade" id="editEmployeeModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Employee</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="editEmployeeForm">
                    @csrf
                    <input type="hidden" name="id" id="edit_id">
                    <div class="modal-body">
                        <div class="row">
                            <!-- Column 1 -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="edit_name" class="form-control" value="{{$user->name}}" required>
                                </div>
                                <div class="form-group">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="edit_email" class="form-control" value="{{$user->email}}" required>
                                </div>
                                <div class="form-group">
                                    <label>Role <span class="text-danger">*</span></label>
                                    <input type="text" name="role_name" id="edit_role_name" class="form-control" value="{{$user->role_name}}" required>
                                </div>
                            </div>
                            <!-- Column 2 -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input type="text" name="phone_number" id="edit_phone" class="form-control" value="{{$user->phone_number}}" required>
                                </div>
                                <div class="form-group">
                                    <label>Status <span class="text-danger">*</span></label>
                                    <input type="text" name="status" class="form-control" value="Active" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Position</label>
                                    <input type="text" name="position" id="edit_position" class="form-control" value="{{$user->position}}" required>
                                </div>
                            </div>
                            <!-- Column 3 -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Department</label>
                                    <input type="text" name="department" id="edit_department" class="form-control" value="{{$user->department}}" required>
                                </div>
                                <div class="form-group">
                                    <label>Job Type <span class="text-danger">*</span></label>
                                    <select name="job_type" id="edit_job_type" class="form-control" required>
                                        <option value="Full-time">Full-time</option>
                                        <option value="Part-time">Part-time</option>  
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Salary <span class="text-danger">*</span></label>
                                    <input type="number" name="salary" id="edit_salary" class="form-control" value="{{$user->salary}}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt-3">
                            <div class="form-group">
                                <label>Update Contract Document (PDF)</label>
                                <input type="file" class="form-control" name="contract_file" accept=".pdf">
                                <small class="text-muted">Upload contract document in PDF format (max 5MB)</small>
                                <div class="mt-2" id="current-contract-section">
                                    <!-- Contract status will be dynamically updated via JavaScript -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @section('script')
    <script>
        $(document).ready(function() {
            // IC Number validation and calculation
            $('input[name="ic_number"]').on('input', function() {
                const icNumber = $(this).val();
                
                // Remove any non-numeric characters
                $(this).val(icNumber.replace(/[^0-9]/g, ''));
                
                if (icNumber.length === 12) {
                    const year = icNumber.substring(0, 2);
                    const month = icNumber.substring(2, 4);
                    const day = icNumber.substring(4, 6);
                    
                    // Validate year (1900-current year)
                    const fullYear = (parseInt(year) > (new Date().getFullYear() % 100)) ? `19${year}` : `20${year}`;
                    const currentYear = new Date().getFullYear();
                    
                    if (parseInt(fullYear) < 1900 || parseInt(fullYear) > currentYear) {
                        toastr.error('Invalid birth year. Year must be between 1900 and current year.');
                        $(this).val('');
                        $('input[name="birth_date"]').val('');
                        $('input[name="age"]').val('');
                        return;
                    }

                    // Validate month (01-12)
                    if (parseInt(month) < 1 || parseInt(month) > 12) {
                        toastr.error('Invalid birth month. Month must be between 01 and 12.');
                        $(this).val('');
                        $('input[name="birth_date"]').val('');
                        $('input[name="age"]').val('');
                        return;
                    }

                    // Validate day (01-31)
                    if (parseInt(day) < 1 || parseInt(day) > 31) {
                        toastr.error('Invalid birth day. Day must be between 01 and 31.');
                        $(this).val('');
                        $('input[name="birth_date"]').val('');
                        $('input[name="age"]').val('');
                        return;
                    }

                    const birthDate = `${fullYear}-${month}-${day}`;
                    const age = currentYear - parseInt(fullYear);

                    // Validate minimum age (18 years)
                    if (age < 18) {
                        toastr.error('Employee must be at least 18 years old.');
                        $(this).val('');
                        $('input[name="birth_date"]').val('');
                        $('input[name="age"]').val('');
                        return;
                    }

                    $('input[name="birth_date"]').val(birthDate);
                    $('input[name="age"]').val(age);
                }
            });

            // Prevent negative numbers in age field
            $('input[name="age"]').on('input', function() {
                if (parseInt($(this).val()) < 0) {
                    $(this).val('');
                    toastr.error('Age cannot be negative.');
                }
            });

            // Add maxlength attribute to IC number input if not already present
            $('input[name="ic_number"]').attr('maxlength', 12);

            // Add Employee Form Submit
            $('#addEmployeeForm').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                
                $.ajax({
                    url: "{{ route('all/employee/save') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $('#addEmployeeModal').modal('hide');
                            toastr.success('Employee added successfully!', 'Success');
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        } else {
                            toastr.error(response.message || 'Something went wrong!', 'Error');
                        }
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        if (errors) {
                            $.each(errors, function(key, value) {
                                toastr.error(value[0], 'Validation Error');
                            });
                        } else {
                            toastr.error('Something went wrong!', 'Error');
                        }
                    }
                });
            });

            // Edit Employee Modal Population
            $('#editEmployeeModal').on('show.bs.modal', function(e) {
                var button = $(e.relatedTarget);
                var id = button.data('id');
                var name = button.data('name');
                var email = button.data('email');
                var phone = button.data('phone');
                var salary = button.data('salary');
                var contracts = button.data('contracts');
                
                var modal = $(this);
                modal.find('#edit_id').val(id);
                modal.find('#edit_name').val(name);
                modal.find('#edit_email').val(email);
                modal.find('#edit_phone').val(phone);
                modal.find('#edit_salary').val(salary);
                modal.find('#current-contract-section').html(`
                    <span class="text-muted">Current contract: </span>
                    <a href="{{ asset('assets/contracts/') }}/${contracts}" target="_blank" class="text-primary">
                        ${contracts}
                    </a>
                `);
            });

            // Edit Employee Form Submit
            $('#editEmployeeForm').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                
                $.ajax({
                    url: "{{ route('all/employee/update') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $('#editEmployeeModal').modal('hide');
                            toastr.success('Employee updated successfully!', 'Success');
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        } else {
                            toastr.error(response.message || 'Something went wrong!', 'Error');
                        }
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        if (errors) {
                            $.each(errors, function(key, value) {
                                toastr.error(value[0], 'Validation Error');
                            });
                        } else {
                            toastr.error('Something went wrong!', 'Error');
                        }
                    }
                });
            });

            // Search functionality
            $('#search-form input').on('input', function() {
                $.ajax({
                    url: "{{ route('employees/search') }}",
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        employee_id: $('#search-employee-id').val(),
                        name: $('#search-name').val(),
                        department: $('#search-department').val(),
                        role_name: $('#search-role-name').val()
                    },
                    success: function(response) {
                        var tbody = $('#employee-table-body');
                        tbody.empty();
                        
                        response.employees.forEach(function(employee) {
                            var row = `
                                <tr>
                                    <td>${employee.employee_id}</td>
                                    <td>${employee.name}</td>
                                    <td><a href="/employees/department/${employee.department}">${employee.department}</a></td>
                                    <td>${employee.position}</td>
                                    <td>${employee.email}</td>
                                    <td>${employee.phone_number}</td>
                                    <td class="text-center">
                                        ${employee.status === 'active' 
                                            ? '<div class="btn btn-success btn-sm btn-rounded"><i class="fa fa-dot-circle-o"></i> Active</div>'
                                            : `<div class="dropdown action-label">
                                                <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa fa-dot-circle-o text-danger"></i> ${employee.status.charAt(0).toUpperCase() + employee.status.slice(1)}
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="#" onclick="confirmResignation(${employee.id})">
                                                        <i class="fa fa-dot-circle-o text-warning"></i> Resigned
                                                    </a>
                                                </div>
                                            </div>`
                                        }
                                    </td>
                                    <td>${employee.join_date}</td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editEmployeeModal" 
                                            data-id="${employee.id}" 
                                            data-name="${employee.name}" 
                                            data-email="${employee.email}" 
                                            data-phone="${employee.phone_number}" 
                                            data-salary="${employee.salary}"
                                            data-contracts="${employee.contracts}">
                                            <i class="fa fa-pencil"></i> Edit
                                        </button>
                                    </td>
                                </tr>
                            `;
                            tbody.append(row);
                        });
                    }
                });
            });

            // Phone number validation
            $('input[name="phone_number"]').on('input', function() {
                let phoneNumber = $(this).val();
                
                // Remove any non-numeric characters
                phoneNumber = phoneNumber.replace(/[^0-9]/g, '');
                
                // Always set the value back to cleaned number
                $(this).val(phoneNumber);

                // Check if number starts with 60
                if (phoneNumber.length >= 2 && !phoneNumber.startsWith('60')) {
                    toastr.error('Phone number must start with 60 (Malaysia country code)');
                    $(this).val('60');
                    return;
                }

                // Validate length (60 + 9-10 digits = 11-12 total)
                if (phoneNumber.length > 12) {
                    toastr.error('Phone number is too long. Format: 60XXXXXXXXX');
                    $(this).val(phoneNumber.substring(0, 12));
                    return;
                }

                // Full validation when number is complete
                if (phoneNumber.length >= 11) {
                    // Check if it's a valid Malaysian format after 60
                    // Valid formats after 60: 1xxxxxxxx, 3xxxxxxxx, 4xxxxxxxx, 6xxxxxxxx, 7xxxxxxxx, 8xxxxxxxx, 9xxxxxxxx
                    const validPrefixes = ['1', '3', '4', '6', '7', '8', '9'];
                    const prefix = phoneNumber.substring(2, 3);
                    
                    if (!validPrefixes.includes(prefix)) {
                        toastr.error('Invalid phone number format. Must start with 60 followed by 1, 3, 4, 6, 7, 8, or 9');
                        $(this).val('60');
                    }
                }
            });

            // Set default value if empty
            $('input[name="phone_number"]').on('focus', function() {
                if (!this.value) {
                    $(this).val('60');
                }
            });

            // Prevent backspace from deleting "60"
            $('input[name="phone_number"]').on('keydown', function(e) {
                const phoneNumber = $(this).val();
                if (e.keyCode === 8 && phoneNumber.length <= 2) { // 8 is backspace key
                    e.preventDefault();
                }
            });
        });
    </script>
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

    <script>
    $('#editEmployeeModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var contracts = button.data('contracts'); // Add data-contracts to your edit button
        var contractSection = $('#current-contract-section');
        
        if (contracts) {
            contractSection.html(`
                <span class="text-muted">Current contract: </span>
                <a href="{{ asset('assets/contracts/') }}/${contracts}" target="_blank" class="text-primary">
                    ${contracts}
                </a>
            `);
        } else {
            contractSection.html('<span class="text-muted">No contract uploaded yet</span>');
        }
    });
    </script>

@endsection
