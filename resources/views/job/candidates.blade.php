<style>
    /* Reduce table cell padding */
.table td, 
.table th {
    padding: 5px; 
    font-size: 12px; 
    vertical-align: middle; 
}

/* Compact the dropdown actions */
.dropdown-menu {
    font-size: 12px; 
    padding: 5px;
}

.dropdown-menu a {
    padding: 5px 10px; 
}

/* Adjust header row for compact size */
.table thead th {
    font-size: 12px; 
    padding: 5px; 
}

/* Optional: Adjust overall table styling */
.table {
    margin-bottom: 10px; 
}


.form-focus .form-control {
    height: 46px;
    padding: 10px 12px;
}

#advanced-search {
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #eee;
}

.btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
}

.form-focus select.form-control {
    padding-top: 10px;
}

</style>
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
                        <h3 class="page-title">Candidates List</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item">Jobs</li>
                            <li class="breadcrumb-item active">Candidates List</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_candidate">
                        <i class="fa fa-plus"></i> Add Candidate
                    </button>
                    </div>
                            <!-- Add Candidate Button -->
                </div>
            </div>
            <!-- /Page Header -->
            <!-- Search Filter -->
            <form id="search-form">
                @csrf
                <div class="row filter-row">
                    <div class="col-sm-6 col-md-3">  
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating" name="name" id="search-name">
                            <label class="focus-label">Name</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">  
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating" name="email" id="search-email">
                            <label class="focus-label">Email</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3"> 
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating" name="candidate_id" id="search-candidate-id">
                            <label class="focus-label">Candidate ID</label>
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
                            <input type="text" class="form-control floating" name="job_title" id="search-job-title">
                            <label class="focus-label">Job Title</label>
                        </div>
                    </div>
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
                            <input type="number" class="form-control floating" name="experience" id="search-experience" min="0">
                            <label class="focus-label">Experience (Years)</label>
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
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Candidate ID</th>
                                    <th>Gender</th>
                                    <th>Mobile Number</th>
                                    <th>Email</th>
                                    <th>Work Experience</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($candidates as $candidate)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <h2 class="table-avatar">
                                            <a href="" class="avatar">
                                                <img alt="" src="{{ URL::to('assets/images/profiles/' . ($candidate->gender == 'Female' ? 'avatar4.jpg' : 'avatar2.jpg')) }}">
                                            </a>
                                                <a href="">{{$candidate -> name}}</a>
                                            </h2>
                                        </td>
                                        <td>{{ $candidate->candidate_id }}</td>
                                        <td>{{ $candidate->gender }}</td>
                                        <td>{{ $candidate->phone_number }}</td>
                                        <td>{{ $candidate->email }}</td>
                                        <td>{{ $candidate->work_experiences }} years</td>
                                        <td class="text-center">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="#"><i class="fa fa-check m-r-5"></i> Approval</a>
                                                    <a class="dropdown-item" href="#"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                    <a class="dropdown-item" href="#"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No candidates found.</td>
                                    </tr>
                                @endforelse
                            </tbody>

                            <!-- <tbody>
                                @foreach ($candidates as $candidate )
                                
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <h2 class="table-avatar">
                                        <a href="" class="avatar">
                                            <img alt="" src="{{ URL::to('assets/images/profiles/' . ($candidate->gender == 'Female' ? 'avatar4.jpg' : 'avatar2.jpg')) }}">
                                        </a>
                                            <a href="">{{$candidate -> name}}</a>
                                        </h2>
                                    </td>
                                    <td>{{$candidate -> candidate_id}}</td>
                                    <td>{{$candidate -> gender}}</td>
                                    <td>{{$candidate -> phone_number}}</td>
                                    <td>{{$candidate -> email}}/td>
                                    <td>{{$candidate -> work_experiences}}</td>
                                    <td class="text-center">
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#approval_modal"><i class="fa fa-check m-r-5"></i> Approval</a>
                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#edit_job"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_job"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody> -->
                        </table>
                    </div>
                </div>
            </div>
            <!-- /Search Filter -->
        </div>
        <!-- /Page Content -->
        

        <!-- Add Candidate Modal -->
        <div class="modal custom-modal fade" id="add_candidate" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Candidate</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('form/apply/job/save') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <!-- Job Title Dropdown -->
                                    <div class="form-group">
                                        <label>Job Title <span class="text-danger">*</span></label>
                                        <select class="form-control @error('job_title') is-invalid @enderror" name="job_title" required>
                                            <option value="" selected disabled>Select Job Title</option>
                                            @foreach($jobs as $job)
                                                <option value="{{ $job->job_title }}">{{ $job->job_title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Name <span class="text-danger">*</span></label>
                                        <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Birth Date</label>
                                        <input class="form-control @error('birth_date') is-invalid @enderror" type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Age</label>
                                        <input class="form-control @error('age') is-invalid @enderror" type="number" name="age" id="age" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Race</label>
                                        <select class="form-control @error('race') is-invalid @enderror" name="race">
                                            <option value="" disabled selected>Select Race</option>
                                            <option value="Malay">Malay</option>
                                            <option value="Chinese">Chinese</option>
                                            <option value="Indian">Indian</option>
                                            <option value="Others">Others</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Gender</label>
                                        <select class="form-control @error('gender') is-invalid @enderror" name="gender">
                                            <option value="" disabled selected>Select Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Phone <span class="text-danger">*</span></label>
                                        <input class="form-control @error('phone_number') is-invalid @enderror" type="tel" name="phone_number" value="{{ old('phone_number') }}" placeholder="60123456789" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Email <span class="text-danger">*</span></label>
                                        <input class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Highest Education</label>
                                        <select class="form-control @error('highest_education') is-invalid @enderror" name="highest_education">
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
                                        <input class="form-control @error('work_experiences') is-invalid @enderror" type="number" name="work_experiences" value="{{ old('work_experiences') }}" min="0">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Upload CV</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('cv_upload') is-invalid @enderror" id="cv_upload" name="cv_upload" onchange="updateFileName(this)">
                                    <label class="custom-file-label" for="cv_upload">Choose file</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Message</label>
                                <textarea class="form-control @error('message') is-invalid @enderror" name="message" rows="4" id="message">{{ old('message') }}</textarea>
                                <small class="form-text text-muted" id="word-count">0/20 words</small>
                            </div>
                            <input type="hidden" name="role_name" value="Candidate">
                            <input type="hidden" name="interview_datetime" value="{{ old('interview_datetime') }}">
                            
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

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
                        <p>Are you sure you want to approve the candidate as an interviewer?</p>
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn-sm">Yes</button>
                            <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        </div>
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
                        <h5 class="modal-title">Edit Candidates</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label"><span class="text-danger">Position*</span></label>
                                        <input class="form-control" type="text" value="" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Name</label>
                                        <input class="form-control" type="text">
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Email <span class="text-danger">*</span></label>
                                        <input class="form-control" type="email">
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">  
                                    <div class="form-group">
                                        <label class="col-form-label">Candidate ID <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-6">  
                                    <div class="form-group">
                                        <label class="col-form-label">Created Date <span class="text-danger">*</span></label>
                                        <div class="cal-icon"><input class="form-control datetimepicker" type="text"></div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Phone </label>
                                        <input class="form-control" type="text">
                                    </div>
                                </div>
                                
                            </div>
                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Save</button>
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
                            <h3>Delete</h3>
                            <p>Are you sure want to delete?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <div class="row">
                                <div class="col-6">
                                    <a href="javascript:void(0);" class="btn btn-primary continue-btn">Delete</a>
                                </div>
                                <div class="col-6">
                                    <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Delete Job Modal -->

        <!-- Apply Job Modal -->
        <div class="modal custom-modal fade" id="apply_job" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Candidate Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('form/apply/job/save') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="hidden" name="interview_datetime" value="{{ old('interview_datetime') }}">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name') }}">
                                        <small class="form-text text-muted">*Fullname in NRIC</small>
                                    </div>
                                    <div class="form-group">
                                        <label>Birth Date</label>
                                        <input class="form-control @error('birth_date') is-invalid @enderror" type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Age</label>
                                        <input class="form-control @error('age') is-invalid @enderror" type="number" name="age" id="age" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Race</label>
                                        <select class="form-control @error('race') is-invalid @enderror" name="race">
                                            <option value="" disabled selected></option>
                                            <option value="malay">Malay</option>
                                            <option value="chinese">Chinese</option>
                                            <option value="indian">Indian</option>
                                            <option value="others">Others</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Gender</label>
                                        <select class="form-control @error('gender') is-invalid @enderror" name="gender">
                                            <option value="" disabled selected></option>
                                            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                            <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input class="form-control @error('phone_number') is-invalid @enderror" type="tel" name="phone_number" id="phone_number" placeholder="Enter phone number with country code" pattern="[0-9]{10,13}" value="{{ old('phone_number') }}">
                                        <small class="form-text text-muted">e.g., 60123456789 for Malaysia</small>
                                    </div>
                                    <div class="form-group">
                                        <label>Email Address</label>
                                        <input class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Highest Education</label>
                                        <select class="form-control @error('highest_education') is-invalid @enderror" name="highest_education">
                                            <option value="" disabled selected>Select your education</option>
                                            <option value="Secondary" {{ old('highest_education') == 'Secondary' ? 'selected' : '' }}>Secondary</option>
                                            <option value="Foundation" {{ old('highest_education') == 'Foundation ' ? 'selected' : '' }}>Foundation</option>
                                            <option value="Diploma" {{ old('highest_education') == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                                            <option value="Degree" {{ old('highest_education') == 'Degree' ? 'selected' : '' }}>Degree</option>
                                            <option value="Master" {{ old('highest_education') == 'Master' ? 'selected' : '' }}>Master</option>
                                            <option value="PhD" {{ old('highest_education') == 'PhD' ? 'selected' : '' }}>PhD</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Work Experiences (Years)</label>
                                        <input class="form-control @error('work_experiences') is-invalid @enderror" type="number" name="work_experiences" value="{{ old('work_experiences') }}" min="0" max="100">
                                    </div>
                                    <div class="form-group">
                                        <label>Role Name</label>
                                        <input class="form-control @error('role_name') is-invalid @enderror" type="text" name="role_name" value="{{ old('role_name', 'Candidate') }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Upload your CV</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('cv_upload') is-invalid @enderror" id="cv_upload" name="cv_upload" onchange="updateFileName(this)">
                                    <label class="custom-file-label" for="cv_upload">Choose file</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Message (short intro)</label>
                                <textarea class="form-control @error('message') is-invalid @enderror" name="message" id="message" placeholder="Max 20 words">{{ old('message') }}</textarea>
                                <small class="form-text text-muted" id="word-count">0/20 words</small>
                            </div>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Wrapper -->
<!-- JavaScript to Toggle Advanced Search -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('search-form');
    const advancedSearch = document.getElementById('advanced-search');
    const toggleButton = document.getElementById('toggle-advanced');
    const inputs = searchForm.querySelectorAll('input, select');
    let debounceTimer;

    // Fix for Advanced Search Toggle
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
            
            fetch("{{ route('candidates/search') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(Object.fromEntries(formData))
            })
            .then(response => response.json())
            .then(data => {
                const tableBody = document.querySelector('.table tbody');
                tableBody.innerHTML = '';

                if (data.candidates.length > 0) {
                    data.candidates.forEach((candidate, index) => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${index + 1}</td>
                            <td>
                                <h2 class="table-avatar">
                                    <a href="" class="avatar">
                                        <img alt="" src="{{ URL::to('assets/images/profiles/') }}/${candidate.gender === 'Female' ? 'avatar4.jpg' : 'avatar2.jpg'}">
                                    </a>
                                    <a href="">${candidate.name}</a>
                                </h2>
                            </td>
                            <td>${candidate.candidate_id || ''}</td>
                            <td>${candidate.gender || ''}</td>
                            <td>${candidate.phone_number || ''}</td>
                            <td>${candidate.email || ''}</td>
                            <td>${candidate.work_experiences ? candidate.work_experiences + ' years' : ''}</td>
                            <td class="text-center">
                                <div class="dropdown dropdown-action">
                                    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#"><i class="fa fa-check m-r-5"></i> Approval</a>
                                        <a class="dropdown-item" href="#"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                        <a class="dropdown-item" href="#"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                    </div>
                                </div>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });
                } else {
                    const row = document.createElement('tr');
                    row.innerHTML = `<td colspan="8" class="text-center">No candidates found.</td>`;
                    tableBody.appendChild(row);
                }
            })
            .catch(error => console.error('Error:', error));
        }, 300);
    }

    // Add event listeners for all input and select fields
    inputs.forEach(input => {
        if (input.tagName === 'SELECT') {
            input.addEventListener('change', performSearch);
        } else {
            input.addEventListener('input', performSearch);
        }
    });
});
</script>
    <!-- Auto-calculate Age Based on Birth Date -->
    <script>
        document.getElementById('birth_date').addEventListener('change', function() {
            const birthDate = new Date(this.value);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            document.getElementById('age').value = age;
        });
    </script>
    <!-- /Auto-calculate Age Based on Birth Date -->
    <!--  Message Field with Word Limit -->
    <script>
        document.getElementById('message').addEventListener('input', function() {
            const message = this.value.trim();
            const words = message.split(/\s+/).filter(word => word.length > 0);
            const wordCount = words.length;
            
            document.getElementById('word-count').textContent = wordCount + '/20 words';

            // Prevent adding more than 20 words
            if (wordCount > 20) {
                this.value = words.slice(0, 20).join(' ');
                document.getElementById('word-count').textContent = '20/20 words'; // Ensure count shows 20/20
            }
        });
    </script>
    <!-- /Message Field with Word Limit-->
    <!--Show CV filename when Upload-->
    <script>
        function updateFileName(input) {
            // Get the label associated with the input
            var label = input.nextElementSibling;
            
            // Check if a file is selected
            if (input.files && input.files[0]) {
                // Set the label to the name of the selected file
                label.innerText = input.files[0].name;
            } else {
                // Reset to default text if no file is selected
                label.innerText = 'Choose file';
            }
        }
        </script>
     <!--/Show CV filename when Upload-->

@endsection
