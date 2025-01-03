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
.btn-primary {
    border: none !important;
}

</style>
@extends('layouts.master')
@section('content')
  {{-- message --}}
  {!! Toastr::message() !!}
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
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_candidate" style="background-color:#5a83d2;">
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
                            <select class="form-control floating" name="job_title" id="search-job-title">
                                <option value="">Select Job Title</option>
                                @foreach($jobTitles as $jobTitle)
                                    <option value="{{ $jobTitle }}">{{ $jobTitle }}</option>
                                @endforeach
                            </select>
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
                                    <th>Job Title</th>
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
                                            <a href="#" class="avatar">
                                                <img alt="" src="{{ URL::to('assets/images/profiles/' . ($candidate->gender == 'Female' ? 'avatar4.jpg' : 'avatar2.jpg')) }}">
                                            </a>
                                                <a href="#" class="view-candidate" data-toggle="modal" data-target="#view_candidate" 
                                                   data-id="{{ $candidate->id }}"
                                                   data-name="{{ $candidate->name }}"
                                                   data-email="{{ $candidate->email }}"
                                                   data-phone="{{ $candidate->phone_number }}"
                                                   data-ic="{{ $candidate->ic_number }}"
                                                   data-birth="{{ $candidate->birth_date }}"
                                                   data-age="{{ $candidate->age }}"
                                                   data-gender="{{ $candidate->gender }}"
                                                   data-race="{{ $candidate->race }}"
                                                   data-education="{{ $candidate->highest_education }}"
                                                   data-experience="{{ $candidate->work_experiences }}"
                                                   data-job-title="{{ $candidate->job_title }}">
                                                    {{ $candidate->name }}

                                                </a>
                                            </h2>
                                        </td>
                                        <td>{{ $candidate->candidate_id }}</td>
                                        <td>{{ $candidate->job_title }}</td>
                                        <td>{{ $candidate->phone_number }}</td>
                                        <td>{{ $candidate->email }}</td>
                                        <td>{{ $candidate->work_experiences }} years</td>
                                        <td class="text-center">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item approve-candidate" href="#" data-toggle="modal" data-target="#approval_modal" data-id="{{ $candidate->id }}">
                                                        <i class="fa fa-check m-r-5"></i> Approval
                                                    </a>
                                                    <a class="dropdown-item edit-candidate" href="#" 
                                                       data-toggle="modal" 
                                                       data-target="#edit_candidate"
                                                       data-id="{{ $candidate->id }}"
                                                       data-race="{{ $candidate->race }}"
                                                       data-gender="{{ $candidate->gender }}"
                                                       data-name="{{ $candidate->name }}"
                                                       data-candidate-id="{{ $candidate->candidate_id }}"
                                                       data-email="{{ $candidate->email }}"
                                                       data-phone="{{ $candidate->phone_number }}"
                                                       data-ic-number="{{ $candidate->ic_number }}"
                                                       data-birth-date="{{ $candidate->birth_date }}"
                                                       data-age="{{ $candidate->age }}"
                                                       data-gender="{{ $candidate->gender }}"
                                                       data-race="{{ $candidate->race }}"
                                                       data-education="{{ $candidate->highest_education }}"
                                                       data-experience="{{ $candidate->work_experiences }}">
                                                        <i class="fa fa-pencil m-r-5"></i> Edit
                                                    </a>
                                                    <a class="dropdown-item delete-candidate" href="#" data-toggle="modal" data-target="#delete_job" data-id="{{ $candidate->id }}">
                                                        <i class="fa fa-trash-o m-r-5"></i> Delete
                                                    </a>
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
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
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
                                        <label>IC Number <span class="text-danger">*</span></label>
                                        <input class="form-control @error('ic_number') is-invalid @enderror" 
                                            type="text" 
                                            name="ic_number" 
                                            id="ic_number" 
                                            pattern="\d{12}" 
                                            maxlength="12"
                                            placeholder="Enter IC number without dashes (e.g., 991231121234)" 
                                            value="{{ old('ic_number') }}" 
                                            required>
                                        <small class="form-text text-muted">Format: YYMMDDPPXXXX (12 digits without dashes)</small>
                                        @error('ic_number')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control @error('birth_date') is-invalid @enderror" type="hidden" name="birth_date" id="birth_date" value="{{ old('birth_date') }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Age <span class="text-danger">*</span></label>
                                        <input class="form-control @error('age') is-invalid @enderror" type="number" name="age" id="age" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Race <span class="text-danger">*</span></label>
                                        <select class="form-control @error('race') is-invalid @enderror" name="race" required>
                                            <option value="" disabled selected>Select Race</option>
                                            <option value="Malay ?? {{ old('Malay') }}">Malay</option>
                                            <option value="Chinese ?? {{ old('Chinese') }}">Chinese</option>
                                            <option value="Indian ??{{ old('Indian') }}">Indian</option>
                                            <option value="Others ??{{ old('Others') }}">Others</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Gender <span class="text-danger">*</span></label>
                                        <select class="form-control @error('gender') is-invalid @enderror" name="gender" required>
                                            <option value="" disabled selected>Select Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Phone Number <span class="text-danger">*</span></label>
                                        <input class="form-control @error('phone_number') is-invalid @enderror" 
                                               type="tel" 
                                               name="phone_number" 
                                               placeholder="Enter with country code (e.g., 60123456789)" 
                                               pattern="^60\d{9,10}$"
                                               title="Please enter a valid Malaysian phone number starting with 60"
                                               required>
                                        <small class="form-text text-muted">(e.g., 60123456789)</small>
                                    </div>
                                    <div class="form-group">
                                        <label>Email <span class="text-danger">*</span></label>
                                        <input class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required>
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
                                        <label>Work Experience (Years)<span class="text-danger">*</span></label>
                                        <input class="form-control @error('work_experiences') is-invalid @enderror" type="number" name="work_experiences" value="{{ old('work_experiences') }}" min="0" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Upload CV  <span class="text-danger">*</span></label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('cv_upload') is-invalid @enderror" id="cv_upload" name="cv_upload" onchange="updateFileName(this)">
                                    <label class="custom-file-label" for="cv_upload">Choose file (PDF only)</label>
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
                        <form action="{{ route('candidate/approve') }}" method="POST">
                            @csrf
                            <input type="hidden" name="candidate_id" id="approve_candidate_id">
                            <p>Are you sure you want to approve this candidate as an interviewer?</p>
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

        <!-- Edit Candidate Modal -->
        <div id="edit_candidate" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Candidate</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @if(isset($candidate) && $candidate)
                            <form action="{{ route('candidate/edit') }}" method="POST">
                                @csrf
                                <input type="hidden" name="candidate_id" id="edit_candidate_id" value="{{ $candidate->candidate_id }}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Name <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="name" id="edit_name" value="{{ old('name', $candidate->name ?? '') }}"required>
                                        </div>
                                        <div class="form-group">
                                            <label>Birth Date<span class="text-danger">*</span></label>
                                            <input class="form-control" type="date" name="birth_date" id="edit_birth_date" value="{{ old('birth_date', $candidate->birth_date ?? '') }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Age<span class="text-danger">*</span></label>
                                            <input class="form-control" type="number" name="age" id="edit_age" value="{{ old('age', $candidate->age ?? '') }}" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Race<span class="text-danger">*</span></label>
                                            <select class="form-control" name="race" id="edit_race">
                                                <option value="">Select Race</option>
                                                <option value="Malay">Malay</option>
                                                <option value="Chinese">Chinese</option>
                                                <option value="Indian">Indian</option>
                                                <option value="Others">Others</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Work Experience (Years)<span class="text-danger">*</span></label>
                                            <input class="form-control" type="number" name="work_experiences" 
                                                   id="edit_experience" value="{{ old('work_experiences', $candidate->work_experiences ?? '') }}" min="0" max="100" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Phone <span class="text-danger">*</span></label>
                                            <input class="form-control" type="tel" name="phone_number" id="edit_phone_number" 
                                                   pattern="[0-9]{10,13}" value="{{ old('phone_number', $candidate->phone_number ?? '') }}" required>
                                            <small class="form-text text-muted">e.g., 0123456789</small>
                                        </div>
                                        <div class="form-group">
                                            <label>Email <span class="text-danger">*</span></label>
                                            <input class="form-control" type="email" name="email" id="edit_email" value="{{ old('email', $candidate->email ?? '') }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Highest Education</label>
                                            <select class="form-control" name="highest_education" id="edit_education" required>
                                                <option value="">Select Education</option>
                                                <option value="Secondary" {{ old('highest_education', $candidate->highest_education ?? '') == 'Secondary' ? 'selected' : '' }}>Secondary</option>
                                                <option value="Foundation" {{ old('highest_education', $candidate->highest_education ?? '') == 'Foundation' ? 'selected' : '' }}>Foundation</option>
                                                <option value="Diploma" {{ old('highest_education', $candidate->highest_education ?? '') == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                                                <option value="Degree" {{ old('highest_education', $candidate->highest_education ?? '') == 'Degree' ? 'selected' : '' }}>Degree</option>
                                                <option value="Master" {{ old('highest_education', $candidate->highest_education ?? '') == 'Master' ? 'selected' : '' }}>Master</option>
                                                <option value="PhD" {{ old('highest_education', $candidate->highest_education ?? '') == 'PhD' ? 'selected' : '' }}>PhD</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Gender</label>
                                            <select class="form-control" name="gender" id="edit_gender" required>
                                                <option value="">Select Gender</option>
                                                <option value="Male" {{ old('gender', $candidate->gender ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                                                <option value="Female" {{ old('gender', $candidate->gender ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                                                <option value="Other" {{ old('gender', $candidate->gender ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>IC Number <span class="text-danger">*</span></label>
                                    <input class="form-control" 
                                           type="text" 
                                           name="ic_number" 
                                           id="edit_ic_number" 
                                           pattern="\d{12}" 
                                           maxlength="12"
                                           placeholder="Enter IC number without dashes" 
                                           value="{{ old('ic_number', $candidate->ic_number ?? '') }}" 
                                           required>
                                    <small class="form-text text-muted">Format: 12 digits without dashes (e.g., 991231121234)</small>
                                </div>
                                <div class="submit-section">
                                    <button type="submit" class="btn btn-primary submit-btn">Update</button>
                                </div>
                            </form>
                        @else
                            <div class="alert alert-info">
                                No candidate data available to edit.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- /Edit Candidate Modal -->

        <!-- Delete Job Modal -->
        <div class="modal custom-modal fade" id="delete_job" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete Candidate</h3>
                            <p>Are you sure want to delete this candidate?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <form action="{{ route('candidate/delete') }}" method="POST">
                                @csrf
                                <input type="hidden" name="candidate_id" id="delete_candidate_id">
                                <div class="row">
                                    <div class="col-6 px-2">
                                        <button type="submit" class="btn btn-danger w-100" style="width: 100%;">Delete</button>
                                    </div>
                                    <div class="col-6 px-2">
                                        <button type="button" data-dismiss="modal" class="btn btn-secondary w-100" style="width: 100%;">Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Delete Job Modal -->


        <!-- View Candidate Modal -->
        <div id="view_candidate" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Candidate Information</h5>
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
                        <div class="card mb-3">
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
                    </div>
                </div>
            </div>
        </div>
        <!-- /View Candidate Modal -->
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
                                    <a href="#" class="avatar">
                                        <img alt="" src="{{ URL::to('assets/images/profiles/') }}/${candidate.gender === 'Female' ? 'avatar4.jpg' : 'avatar2.jpg'}">
                                    </a>
                                    <a href="#" class="view-candidate" data-toggle="modal" data-target="#view_candidate" 
                                       data-id="${candidate.id}"
                                       data-name="${candidate.name}"
                                       data-email="${candidate.email}"
                                       data-phone="${candidate.phone_number}"
                                       data-ic="${candidate.ic_number}"
                                       data-birth="${candidate.birth_date}"
                                       data-age="${candidate.age}"
                                       data-gender="${candidate.gender}"
                                       data-race="${candidate.race}"
                                       data-education="${candidate.highest_education}"
                                       data-experience="${candidate.work_experiences}"
                                       data-job-title="${candidate.job_title}">
                                        ${candidate.name}
                                    </a>
                                </h2>
                            </td>
                            <td>${candidate.candidate_id || ''}</td>
                            <td>${candidate.job_title || ''}</td>
                            <td>${candidate.phone_number || ''}</td>
                            <td>${candidate.email || ''}</td>
                            <td>${candidate.work_experiences ? candidate.work_experiences + ' years' : ''}</td>
                            <td class="text-center">
                                <div class="dropdown dropdown-action">
                                    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item approve-candidate" href="#" data-toggle="modal" data-target="#approval_modal" data-id="${candidate.id}">
                                            <i class="fa fa-check m-r-5"></i> Approval
                                        </a>
                                        <a class="dropdown-item edit-candidate" href="#" 
                                           data-toggle="modal" 
                                           data-target="#edit_candidate"
                                           data-id="${candidate.id}"
                                           data-race="${candidate.race}"
                                           data-gender="${candidate.gender}"
                                           data-name="${candidate.name}"
                                           data-candidate-id="${candidate.candidate_id}"
                                           data-email="${candidate.email}"
                                           data-phone="${candidate.phone_number}"
                                           data-ic-number="${candidate.ic_number}"
                                           data-birth-date="${candidate.birth_date}"
                                           data-age="${candidate.age}"
                                           data-gender="${candidate.gender}"
                                           data-race="${candidate.race}"
                                           data-education="${candidate.highest_education}"
                                           data-experience="${candidate.work_experiences}">
                                            <i class="fa fa-pencil m-r-5"></i> Edit
                                        </a>
                                        <a class="dropdown-item delete-candidate" href="#" data-toggle="modal" data-target="#delete_job" data-id="${candidate.id}">
                                            <i class="fa fa-trash-o m-r-5"></i> Delete
                                        </a>
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
        const birthDate = new Date(this.value);  // Get the birth date
        const today = new Date();  // Get today's date
        let age = today.getFullYear() - birthDate.getFullYear();  
        const monthDiff = today.getMonth() - birthDate.getMonth();  
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;  // Adjust age if the birthday hasn't occurred yet this year
        }

        // Check if age is less than 17
        if (age < 17) {
            alert("Invalid birth date. Please select a valid date.");  // Alert the user
            this.value = '';  // Clear the birth date input
            document.getElementById('age').value = '';  // Clear the age field
        } else {
            document.getElementById('age').value = age;  // Set the age field if valid
        }
    });
</script>
    <!-- /Auto-calculate Age Based on Birth Date -->
    <!--Validate IC number format-->
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const icNumberInput = document.getElementById('ic_number');
            const birthDateInput = document.getElementById('birth_date');
            
            if (icNumberInput) {
                icNumberInput.addEventListener('input', function(e) {
                    // Remove non-digit characters
                    let value = this.value.replace(/[^\d]/g, '');
                    
                    // Limit to 12 digits
                    if (value.length > 12) {
                        value = value.slice(0, 12);
                    }
                    
                    this.value = value;
                    
                    // Validation for IC number length (must be exactly 12 digits)
                    if (value.length === 12) {
                        // Extract birth date from IC number (first 6 digits: YYMMDD)
                        const yearPrefix = value.substring(0, 2);  // First two digits of year
                        const month = value.substring(2, 4);
                        const day = value.substring(4, 6);

                        // Determine if the year is from 1900s or 2000s
                        let year = parseInt(yearPrefix);
                        const currentYear = new Date().getFullYear();
                        const currentCentury = Math.floor(currentYear / 100); // Get the current century (20 for 2023)
                        
                        // If the yearPrefix is greater than the current year, it's from the 1900s
                        if (year > currentYear % 100) {
                            year += 1900; // If it’s larger than the current year, it’s from the 1900s
                        } else {
                            year += 2000; // Otherwise, it's from the 2000s
                        }

                        // Format the birth date as YYYY-MM-DD
                        const birthDate = `${year}-${month}-${day}`;

                        // Set the birth date in the hidden field
                        birthDateInput.value = birthDate;

                        // Calculate and set age based on the birth date
                        const birthDateObj = new Date(birthDate);
                        const today = new Date();
                        let age = today.getFullYear() - birthDateObj.getFullYear();
                        const monthDiff = today.getMonth() - birthDateObj.getMonth();
                        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDateObj.getDate())) {
                            age--;
                        }
                        
                        // Display the age in a separate field or use the value for further actions
                        document.getElementById('age').value = age;  // Assume you have an 'age' field

                        // Visual feedback for valid IC number
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    } else {
                        // Provide feedback for invalid IC number
                        this.classList.remove('is-valid');
                        this.classList.add('is-invalid');
                    }
                });
                
                // Add blur event for additional validation
                icNumberInput.addEventListener('blur', function() {
                    if (this.value.length > 0 && this.value.length < 12) {
                        this.classList.add('is-invalid');
                        this.setCustomValidity('IC number must be exactly 12 digits');
                    }
                });
            }
        });
    </script>
    <!--/Validate IC number format-->

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

     <script>
     document.addEventListener('DOMContentLoaded', function() {
         const icNumberInput = document.getElementById('ic_number');
         
         if (icNumberInput) {
             icNumberInput.addEventListener('input', function(e) {
                 // Remove any non-digits and dashes
                 let value = this.value.replace(/[^\d]/g, '');
                 
                 // Limit to 12 digits
                 if (value.length > 12) {
                     value = value.slice(0, 12);
                 }
                 
                 this.value = value;
                 
                 // Validation
                 if (value.length === 12) {
                     const year = parseInt(value.substring(0, 2));
                     const month = parseInt(value.substring(2, 4));
                     const day = parseInt(value.substring(4, 6));
                     
                     let isValid = true;
                     let errorMessage = '';
                     
                     // Validate month (01-12)
                     if (month < 1 || month > 12) {
                         isValid = false;
                         errorMessage = 'Invalid month in IC number';
                     }
                     
                     // Validate day (01-31)
                     if (day < 1 || day > 31) {
                         isValid = false;
                         errorMessage = 'Invalid day in IC number';
                     }
                     
                     // Additional validation for specific months
                     if (isValid) {
                         if (month === 2) {
                             // February
                             const isLeapYear = (year % 4 === 0);
                             if (day > (isLeapYear ? 29 : 28)) {
                                 isValid = false;
                                 errorMessage = 'Invalid day for February';
                             }
                         } else if ([4, 6, 9, 11].includes(month) && day > 30) {
                             // Months with 30 days
                             isValid = false;
                             errorMessage = 'Invalid day for this month';
                         }
                     }
                     
                     this.setCustomValidity(errorMessage);
                     
                     // Visual feedback
                     if (isValid) {
                         this.classList.remove('is-invalid');
                         this.classList.add('is-valid');
                     } else {
                         this.classList.remove('is-valid');
                         this.classList.add('is-invalid');
                     }
                 } else {
                     this.setCustomValidity('IC number must be exactly 12 digits');
                     this.classList.remove('is-valid');
                     if (value.length > 0) {
                         this.classList.add('is-invalid');
                     } else {
                         this.classList.remove('is-invalid');
                     }
                 }
             });
             
             // Add blur event for additional validation
             icNumberInput.addEventListener('blur', function() {
                 if (this.value.length > 0 && this.value.length < 12) {
                     this.classList.add('is-invalid');
                     this.setCustomValidity('IC number must be exactly 12 digits');
                 }
             });
         }
     });
     </script>

     <script>
     $(document).ready(function() {
         $('.approve-candidate').click(function() {
             var candidateId = $(this).data('id');
             $('#approve_candidate_id').val(candidateId);
         });
     });
     </script>

    <!--View Candidates Model-->
    <script>
    $(document).ready(function() {
        // Delegate the click event to the parent container (the table)
        $(document).on('click', '.view-candidate', function() {
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


    <script>
    $(document).ready(function() {
        $('.edit-candidate').on('click', function() {
            // Get data from data attributes
            var id = $(this).data('id');
            var name = $(this).data('name');
            var email = $(this).data('email');
            var phone = $(this).data('phone');
            var ic = $(this).data('ic-number');
            var birth = $(this).data('birth-date');
            var age = $(this).data('age');
            var gender = $(this).data('gender');
            var race = $(this).data('race');
            var education = $(this).data('education');
            var experience = $(this).data('experience');

            // Update modal fields
            $('#edit_candidate_id').val(id);
            $('#edit_name').val(name);
            $('#edit_email').val(email);
            $('#edit_phone_number').val(phone);
            $('#edit_ic_number').val(ic);
            $('#edit_birth_date').val(birth);
            $('#edit_age').val(age);
            $('#edit_gender').val(gender);
            $('#edit_race').val(race);
            $('#edit_education').val(education);
            $('#edit_experience').val(experience);
        });
    });
    </script>

    <script>
    $(document).ready(function() {
        // When edit button is clicked
        $('.edit-candidate').on('click', function() {
            // Get data from data attributes
            var race = $(this).data('race');
            var gender = $(this).data('gender');
            
            // Set values in the form
            $('#edit_race').val(race);
            $('#edit_gender').val(gender);
            
            // Debug logs
            console.log('Race:', race);
            console.log('Gender:', gender);
        });
    });
    </script>

    <script>
    $(document).ready(function() {
        // When delete button is clicked
        $('.delete-candidate').click(function() {
            var candidateId = $(this).data('id');
            $('#delete_candidate_id').val(candidateId);
        });
    });
    </script>
@endsection

