@extends('layouts.master')
@section('content')
    <style>
        .table tbody tr:hover {
            background-color: #f2f2f2; 
            cursor: pointer; 
        }
    </style>
    <!-- Page Wrapper -->
    <div class="page-wrapper">	
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Interviewer List</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item">Jobs</li>
                            <li class="breadcrumb-item active">Interviewer List</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        <div class="btn-group">
                            <select id="bulk-action" class="form-control">
                                <option value="">Select Action</option>
                                <option value="hired">Hired</option>
                                <option value="rejected">Rejected</option>
                            </select>
                            <button id="apply-action" class="btn btn-primary" style="background-color:#5a83d2;border:none;">Apply</button>
                        </div>
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
                            <input type="text" class="form-control floating" name="name" id="search-name" placeholder="Search by Name">
                            <label class="focus-label">Name</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">  
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating" name="email" id="search-email" placeholder="Search by Email">
                            <label class="focus-label">Email</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3"> 
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating" name="candidate_id" id="search-candidate-id" placeholder="Search by Candidate ID">
                            <label class="focus-label">Imterviewer ID</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <button type="button" class="btn btn-secondary btn-block" id="toggle-advanced">Advanced Search</button>
                    </div>
                </div>

                <!-- Advanced Search Fields -->
                <div class="row filter-row" id="advanced-search" style="display: none;">
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus">
                            <select class="form-control floating" name="gender" id="search-gender">
                                <option value=""></option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                            <label class="focus-label">Gender</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus">
                            <select class="form-control floating" name="race" id="search-race">
                                <option value=""></option>
                                <option value="Malay">Malay</option>
                                <option value="Chinese">Chinese</option>
                                <option value="Indian">Indian</option>
                                <option value="Others">Others</option>
                            </select>
                            <label class="focus-label">Race</label>
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
                                    <th><input type="checkbox" id="select-all"></th>
                                    <th>#</th>
                                    <th>Interviewer ID</th>
                                    <th>Name</th>
                                    <th>Mobile Number</th>
                                    <th>Job Applied</th>
                                    <th>Gender</th>
                                    <th>Email</th>
                                    <th>Schedule DateTime</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($interviewers as $key => $interviewer)
                                    <tr>
                                        <td><input type="checkbox" name="interviewers[]" value="{{ $interviewer->id }}"></td>
                                        <td>{{ $key + 1 }}</td>
                                        <td onclick="window.open('{{ route('resume', ['id' => $interviewer->id]) }}', '_blank')" style="color:blue">
                                        {{ $interviewer->candidate_id }}</td>
                                        <td>
                                            <h2>
                                                <a href="#" class="view-interviewer" data-toggle="modal" data-target="#view_interviewer" 
                                                   data-id="{{ $interviewer->id }}"
                                                   data-name="{{ $interviewer->name }}"
                                                   data-email="{{ $interviewer->email }}"
                                                   data-phone="{{ $interviewer->phone_number }}"
                                                   data-ic="{{ $interviewer->ic_number }}"
                                                   data-birth="{{$interviewer->birth_date }}"
                                                   data-age="{{ $interviewer->age }}"
                                                   data-gender="{{ $interviewer->gender }}"
                                                   data-race="{{ $interviewer->race }}"
                                                   data-education="{{ $interviewer->highest_education }}"
                                                   data-experience="{{ $interviewer->work_experiences }}"
                                                   data-job-title="{{ $interviewer->job_title }}"
                                                   data-message="{{ $interviewer->message }}">
                                                    {{ $interviewer->name }}
                                                </a>
                                            </h2>
                                        </td>
                                        <td>{{ $interviewer->phone_number }}</td>
                                        <td>
                                            <a href="{{ route('job/application', ['job_title' => $interviewer->job_title]) }}">
                                                {{ $interviewer->job_title }}
                                            </a>
                                        </td>
                                        <td>{{ $interviewer->gender }}</td>
                                        <td>{{ $interviewer->email }}</td>
                                        <td>
                                            <a href="#" class="schedule-datetime" data-toggle="modal" data-target="#schedule_interview" 
                                               data-candidate-id="{{ $interviewer->id }}">
                                                {{ $interviewer->interview_datetime ?? 'Not Scheduled' }}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                    <i class="material-icons">more_vert</i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item edit-interviewer" href="#" data-toggle="modal" data-target="#edit_job" 
                                                       data-id="{{ $interviewer->id }}"
                                                       data-name="{{ $interviewer->name }}"
                                                       data-email="{{ $interviewer->email }}"
                                                       data-phone="{{ $interviewer->phone_number }}"
                                                       data-job-title="{{ $interviewer->job_title }}"
                                                       data-gender="{{ $interviewer->gender }}"
                                                       data-ic="{{ $interviewer->ic_number }}">
                                                        <i class="fa fa-pencil m-r-5"></i> Edit
                                                    </a>
                                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_job" 
                                                       data-id="{{ $interviewer->id }}">
                                                        <i class="fa fa-trash-o m-r-5"></i> Delete
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No interviewers found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
    
        <!-- Approval Modal -->
        <div id="approval_modal" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Approval Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('interviewer/bulkAction') }}" method="POST">
                            @csrf
                            <input type="hidden" name="action" value="approve">
                            <input type="hidden" name="interviewers[]" id="approve_interviewer_id">
                            <p>Are you sure you want to approve this interviewer?</p>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Yes, Approve</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Approval Modal -->

        <!-- Edit Job Modal -->
        <div id="edit_job" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Interviewer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('interviewer/update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" id="edit_id" value="{{ old('id', $interviewer->id ?? '') }}">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Name <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="name" id="edit_name" 
                                               value="{{ old('name', $interviewer->name ?? '') }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">IC Number</label>
                                        <input class="form-control" type="text" name="ic_number" id="edit_ic" 
                                               value="{{ old('ic_number', $interviewer->ic_number ?? '') }}" readonly>
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Gender</label>
                                        <select class="form-control" name="gender" id="edit_gender">
                                            <option value="">Select Gender</option>
                                            <option value="Male" {{ old('gender', $interviewer->gender ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ old('gender', $interviewer->gender ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Job Title</label>
                                        <input class="form-control" type="text" name="job_title" id="edit_job_title" 
                                               value="{{ old('job_title', $interviewer->job_title ?? '') }}">
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Email</label>
                                        <input class="form-control" type="email" name="email" id="edit_email" 
                                               value="{{ old('email', $interviewer->email ?? '') }}">
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Phone Number</label>
                                        <input class="form-control" type="text" name="phone_number" id="edit_phone" 
                                               value="{{ old('phone_number', $interviewer->phone_number ?? '') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Edit Job Modal -->

        <!-- Delete Job Modal -->
        <div class="modal custom-modal fade" id="delete_job" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete Interviewer</h3>
                            <p>Are you sure want to delete?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <form action="{{ route('interviewer/delete') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" id="delete_id">
                                <div class="row">
                                    <div class="col-6 text-center">
                                        <button type="submit" class="btn btn-primary w-100">Delete</button>
                                    </div>
                                    <div class="col-6 text-center">
                                        <button type="button" class="btn btn-primary w-100" data-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Delete Job Modal -->
            <!-- View Interviewer Modal -->
        <div id="view_interviewer" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Inteviewer Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Basic Information -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Basic Information</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-muted">Full Name</label>
                                            <p class="font-weight-bold" id="view_name"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-muted">Applied Position</label>
                                            <p class="font-weight-bold" id="view_job_title"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Contact Information</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-muted">Email</label>
                                            <p class="font-weight-bold" id="view_email"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-muted">Phone Number</label>
                                            <p class="font-weight-bold" id="view_phone"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Personal Information -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Personal Information</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-muted">IC Number</label>
                                            <p class="font-weight-bold" id="view_ic"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-muted">Birth Date</label>
                                            <p class="font-weight-bold" id="view_birth"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="text-muted">Age</label>
                                            <p class="font-weight-bold" id="view_age"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="text-muted">Gender</label>
                                            <p class="font-weight-bold" id="view_gender"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="text-muted">Race</label>
                                            <p class="font-weight-bold" id="view_race"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Professional Information -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Professional Information</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-muted">Highest Education</label>
                                            <p class="font-weight-bold" id="view_education"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-muted">Work Experience</label>
                                            <p class="font-weight-bold" id="view_experience"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Short Intro</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-muted">Message (short intro)</label>
                                            <p class="font-weight-bold" id="view_message"></p>
                                        </div>
                                    </div>                                  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /View Interviewer Modal -->

        <!-- Schedule Interview Modal -->
        <div class="modal custom-modal fade" id="schedule_interview" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Schedule Interview</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="schedule_form">
                            @csrf
                            <input type="hidden" id="candidate_id" name="candidate_id">
                            <input type="hidden" id="candidate_phone" name="candidate_phone">
                            <div class="form-group">
                                <label>Interview Date & Time</label>
                                <div class="cal-icon">
                                    <input type="text" 
                                           class="form-control datetimepicker" 
                                           name="interview_datetime" 
                                           id="interview_datetime"
                                           required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Interview Type</label>
                                <select class="form-control" name="interview_type" id="interview_type" required>
                                    <option value="">Select Interview Type</option>
                                    <option value="f2f">Face-to-Face</option>
                                    <option value="online">Online</option>
                                </select>
                            </div>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Schedule</button>
                            </div>
                            <div id="loadingSpinner" style="display: none; text-align: center; margin-top: 15px;">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <span> Processing...</span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Schedule Interview Modal -->
         
    </div>
    <!-- /Page Wrapper -->

<!-- Confirmation Modal -->
<div id="confirmationModal" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="modalConfirmationMessage">Are you sure you want to proceed?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="confirmActionBtn">Confirm</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>


<!--Advanced Search Filter-->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('search-form');
    const advancedSearch = document.getElementById('advanced-search');
    const toggleButton = document.getElementById('toggle-advanced');
    let debounceTimer;

    // Toggle Advanced Search
    toggleButton.addEventListener('click', function() {
        const isHidden = advancedSearch.style.display === 'none' || advancedSearch.style.display === '';
        advancedSearch.style.display = isHidden ? 'flex' : 'none';
        this.textContent = isHidden ? 'Hide Advanced Search' : 'Advanced Search';
    });

    // Function to perform AJAX search with debounce
    function performSearch() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            const formData = new FormData(searchForm);
            const queryParams = Object.fromEntries(formData);

            // Check if all fields are empty (i.e., show all interviewers)
            if (Object.values(queryParams).every(val => !val)) {
                queryParams['reset'] = true; // Add a custom flag to indicate a reset request
            }

            fetch("{{ route('interviewers/search') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(queryParams)
            })
            .then(response => response.json())
            .then(data => {
                const tableBody = document.querySelector('.table tbody');
                tableBody.innerHTML = ''; // Clear existing rows

                if (data.interviewers.length > 0) {
                    data.interviewers.forEach((interviewer, index) => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td><input type="checkbox" name="interviewers[]" value="${interviewer.id}"></td>
                            <td>${index + 1}</td>
                            <td onclick="window.open('{{ route('resume', ['id' => $interviewer->id]) }}', '_blank')" style="color:blue">
                                {{ $interviewer->candidate_id }}</td>
                           <td>
                                <h2>
                                    <a href="#" class="view-interviewer" data-toggle="modal" data-target="#view_interviewer" 
                                        data-id="${interviewer.id}"
                                        data-name="${interviewer.name}"
                                        data-email="${interviewer.email}"
                                        data-phone="${interviewer.phone_number}"
                                        data-ic="${interviewer.ic_number}"
                                        data-birth="{{$interviewer->birth_date }}"
                                        data-age="${interviewer.age}"
                                        data-gender="${interviewer.gender}"
                                        data-race="${interviewer.race}"
                                        data-education="${interviewer.highest_education}"
                                        data-experience="${interviewer.work_experiences}"
                                        data-job-title="${interviewer.job_title}"
                                        data-message="${interviewer.message}">
                                        ${interviewer.name}
                                    </a>
                                </h2>
                            </td>
                            <td>${interviewer.phone_number}</td>
                            <td>${interviewer.job_title}</td>
                            <td>${interviewer.gender}</td>
                            <td>${interviewer.email}</td>
                            <td>
                                <a href="#" class="schedule-datetime" data-toggle="modal" data-target="#schedule_interview" 
                                   data-candidate-id="${interviewer.id}">
                                    ${interviewer.interview_datetime || 'Not Scheduled'}
                                </a>
                            </td>
                            <td class="text-center">
                                <div class="dropdown dropdown-action">
                                    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item edit-interviewer" href="#" data-toggle="modal" data-target="#edit_interviewer" data-id="${interviewer.id}"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_interviewer" data-id="${interviewer.id}"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                    </div>
                                </div>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });
                } else {
                    const row = document.createElement('tr');
                    row.innerHTML = '<td colspan="10" class="text-center">No interviewers found.</td>';
                    tableBody.appendChild(row);
                }
            })
            .catch(error => console.error('Error:', error));
        }, 300);  // Debounce timeout set to 300ms
    }

    // Attach input event listeners to search fields
    searchForm.addEventListener('input', performSearch);
});
</script>
<!-- View Interviewer Modal -->
<script>
$(document).ready(function() {
    // Delegate the click event to the parent container (the table)
    $(document).on('click', '.view-interviewer', function() {
        // Get data from data attributes
        var name = $(this).data('name');
        var email = $(this).data('email');
        var phone = $(this).data('phone');
        var ic = $(this).data('ic');
        var birth = $(this).data('birth');
        var age = $(this).data('age');
        var gender = $(this).data('gender');
        var race = $(this).data('race');
        var education = $(this).data('education');
        var experience = $(this).data('experience');
        var jobTitle = $(this).data('job-title');
        var message = $(this).data('message'); // Added message data attribute
        
        // Handle Missing Data and fill modal fields
        $('#view_name').text(name || 'Not provided');
        $('#view_job_title').text(jobTitle || 'Not provided');
        $('#view_email').text(email || 'Not provided');
        $('#view_phone').text(phone || 'Not provided');
        $('#view_ic').text(ic || 'Not provided');
        $('#view_birth').text(birth || 'Not provided');
        $('#view_age').text(age ? age + ' years' : 'Not provided');
        $('#view_gender').text(gender || 'Not provided');
        $('#view_race').text(race || 'Not provided');
        $('#view_education').text(education || 'Not provided');
        $('#view_experience').text(experience ? experience + ' years' : 'Not provided');
        $('#view_message').text(message || 'Not provided');
    });
});
</script>


<!-- Schedule Interview JavaScript -->
<script>
    $(document).ready(function() {
    // Initialize datetimepicker with fixed time selection
    $('.datetimepicker').datetimepicker({
        format: 'YYYY-MM-DD HH:mm',  
        minDate: moment(),
        stepping: 15,
        showTodayButton: true,
        showClear: true,
        showClose: true,
        sideBySide: true,
        icons: {
            time: 'fa fa-clock-o',
            date: 'fa fa-calendar',
            up: 'fa fa-chevron-up',
            down: 'fa fa-chevron-down',
            previous: 'fa fa-chevron-left',
            next: 'fa fa-chevron-right',
            today: 'fa fa-crosshairs',
            clear: 'fa fa-trash',
            close: 'fa fa-times'
        }
    });

    // When submitting the form, ensure the date is properly formatted
    $('#schedule_form').on('submit', function(e) {
        e.preventDefault();
        
        // Get the date and ensure it's in the correct format
        var selectedDate = $('#interview_datetime').val();
        var formattedDate = moment(selectedDate).format('YYYY-MM-DD HH:mm');
        
        var formData = {
            candidate_id: $('#candidate_id').val(),
            interview_datetime: formattedDate,  // Use the formatted date
            interview_type: $('#interview_type').val(),
            _token: $('input[name="_token"]').val()
        };
            // Show loading spinner and hide submit button
            $('#loadingSpinner').show();
            $('.submit-btn').prop('disabled', true); 
            
        $.ajax({
            url: '{{ route("schedule.interview") }}',
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log("Response:", response); // Log the actual server response
                // Hide loading spinner
                $('#loadingSpinner').hide();
                $('.submit-btn').prop('disabled', false); 

                if (response.success) {
                    $('#schedule_interview').modal('hide');
                    toastr.success('Interview scheduled successfully');
                    setTimeout(function() {
                        location.reload();
                    }, 1500); // Reload page after 1.5 seconds
                } else {
                    toastr.error('Unexpected response format. Please check the server response.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error Status:', status);
                console.error('Error:', error);
                console.error('Response:', xhr.responseText); // This will show the actual error message
                toastr.error('Error scheduling interview. Please check console for details.');
            
                // Hide loading spinner and re-enable the submit button
                $('#loadingSpinner').hide();
                $('.submit-btn').prop('disabled', false); 
            }
        });
    });

    // Handle time selection
    $('.datetimepicker').on('dp.change', function(e) {
        // Format the selected date and time
        if(e.date) {
            var formattedDateTime = e.date.format('YYYY-MM-DD HH:mm');
            $(this).val(formattedDateTime);
        }
    });

    // Handle schedule link click
    $('.schedule-datetime').on('click', function() {
        var candidateId = $(this).data('candidate-id');
        var currentDateTime = $(this).text().trim();
        var candidatePhone = $(this).closest('tr').find('td:eq(3)').text(); // Get phone from table
        
        $('#candidate_id').val(candidateId);
        $('#candidate_phone').val(candidatePhone);
        
        // Set the datetime value if it exists and isn't "Not Scheduled"
        if (currentDateTime && currentDateTime !== 'Not Scheduled') {
            $('#interview_datetime').val(currentDateTime);
        } else {
            $('#interview_datetime').val('');
        }
    });
});
</script>

<script>
$(document).ready(function() {
    $('#apply-action').click(function() {
        const action = $('#bulk-action').val();
        const selectedInterviewers = $('input[name="interviewers[]"]:checked').map(function() {
            return this.value;
        }).get();

        if (!action) {
            toastr.error('Please select an action');
            return;
        }

        if (selectedInterviewers.length === 0) {
            toastr.error('Please select at least one interviewer');
            return;
        }

        // Show confirmation modal
        $('#confirmationModal').modal('show');
        $('#modalConfirmationMessage').text(`Are you sure you want to mark ${selectedInterviewers.length} interviewer(s) as ${action}?`);

        // Handle confirmation button click
        $('#confirmActionBtn').off('click').on('click', function() {
            const formData = {
                _token: '{{ csrf_token() }}',
                action: action,
                interviewers: selectedInterviewers,
                confirm: true, // This will be converted to boolean
                salary: action === 'hired' ? 3000 : null
            };

            $.ajax({
                url: '{{ route("interviewer/bulkAction") }}',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(formData),
                processData: false,
                beforeSend: function() {
                    $('#confirmActionBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        toastr.error(response.message || 'An error occurred');
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'An error occurred';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    toastr.error(errorMessage);
                },
                complete: function() {
                    $('#confirmActionBtn').prop('disabled', false).text('Confirm');
                    $('#confirmationModal').modal('hide');
                }
            });
        });
    });
});
</script>

<script>
$(document).ready(function() {
    // Handle edit button click
    $(document).on('click', '.edit-interviewer', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var email = $(this).data('email');
        var phone = $(this).data('phone');
        var jobTitle = $(this).data('job-title');
        var gender = $(this).data('gender');
        var ic = $(this).data('ic');

        // Set values in the edit modal
        $('#edit_id').val(id);
        $('#edit_name').val(name);
        $('#edit_email').val(email);
        $('#edit_phone').val(phone);
        $('#edit_job_title').val(jobTitle);
        $('#edit_gender').val(gender);
        $('#edit_ic').val(ic);
    });
});
</script>
@endsection