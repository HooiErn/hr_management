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
                    <h3 class="page-title">Jobs</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Jobs</li>
                    </ul>
                </div>
                <div class="col-auto float-right ml-auto">
                        <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_job" style="background-color:#5a83d2;border:none; color:white;"><i class="fa fa-plus"></i> Add Job</a>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped custom-table mb-0 datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Job Title</th>
                                <th>Department</th>
                                <th>Start Date</th>
                                <th>Expire Date</th>
                                <th class="text-center">Job Type</th>
                                <th>Expiry Status</th>
                                <th>Applicants</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($job_list as $key=>$items)
                            @php
                                $expired_date = Carbon\Carbon::parse($items->expired_date)->startOfDay();
                                $now = Carbon\Carbon::now('Asia/Kuala_Lumpur')->startOfDay();
                                $is_expired = $now->gt($expired_date);
                                $days_diff = $now->diffInDays($expired_date, false);
                            @endphp
                            <tr class="{{ $is_expired ? 'text-danger' : '' }}">
                                <td>{{ ++$key }}</td>
                                <td hidden class="id">{{ $items->id }}</td>
                                <td hidden class="job_title">{{ $items->job_title }}</td>
                                <td hidden class="job_location">{{ $items->job_location }}</td>
                                <td hidden class="no_of_vacancies">{{ $items->no_of_vacancies }}</td>
                                <td hidden class="department">{{ $items->department }}</td>
                                <td hidden class="experience">{{ $items->experience }}</td>
                                <td hidden class="salary_from">{{ $items->salary_from }}</td>
                                <td hidden class="salary_to">{{ $items->salary_to }}</td>
                                <td hidden class="job_type">{{ $items->job_type }}</td>
                                <td hidden class="status">{{ $items->status }}</td>
                                <td hidden class="start_date">{{ $items->start_date }}</td>
                                <td hidden class="expired_date">{{ $items->expired_date }}</td>
                                <td hidden class="description">{{ $items->description }}</td>
                                <td hidden class="age">{{ $items->age }}</td>
                                <td>{{ $items->job_title }}</td>
                                <td>{{ $items->department }}</td>
                                <td>{{ date('d F, Y', strtotime($items->start_date)) }}</td>
                                <td class="{{ $is_expired ? 'text-danger' : '' }}">
                                    {{ date('d F, Y', strtotime($items->expired_date)) }}
                                </td>
                                <td class="text-center">
                                    <div class="dropdown action-label">
                                        <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-dot-circle-o text-danger"></i> {{ $items->job_type }}
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="#"><i class="fa fa-dot-circle-o text-info"></i> Full Time</a>
                                            <a class="dropdown-item" href="#"><i class="fa fa-dot-circle-o text-success"></i> Part Time</a>
                                            <a class="dropdown-item" href="#"><i class="fa fa-dot-circle-o text-danger"></i> Internship</a>
                                            <a class="dropdown-item" href="#"><i class="fa fa-dot-circle-o text-warning"></i> Temporary</a>
                                            <a class="dropdown-item" href="#"><i class="fa fa-dot-circle-o text-warning"></i> Other</a>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="dropdown action-label">
                                        <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-dot-circle-o text-danger"></i> {{ $items->status }}
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="#"><i class="fa fa-dot-circle-o text-info"></i> Open</a>
                                            <a class="dropdown-item" href="#"><i class="fa fa-dot-circle-o text-success"></i> Closed</a>
                                            <a class="dropdown-item" href="#"><i class="fa fa-dot-circle-o text-danger"></i> Cancelled</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($is_expired)
                                        <span class="badge bg-danger">
                                            @if($is_expired)
                                                Expired ({{ abs($days_diff) }} {{ Str::plural('day', abs($days_diff)) }} ago)
                                            @endif
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            Expires in {{ $days_diff }} {{ Str::plural('day', $days_diff) }}
                                        </span>
                                    @endif
                                </td>
                                @php
                                    $apply = DB::table('apply_for_jobs')->where('job_title',$items->job_title)->count();
                                @endphp
                                <td>
                                    <a href="{{ url('job/applicants/'.$items->job_title) }}" class="btn btn-sm btn-primary">
                                        {{ $apply }}
                                        Candidates
                                    </a>
                                </td>
                                <td>
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                            <i class="material-icons">more_vert</i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            @if(!$is_expired)
                                                <a href="#" class="dropdown-item edit_job" data-toggle="modal" data-target="#edit_job">
                                                    <i class="fa fa-pencil m-r-5"></i> Edit
                                                </a>
                                            @endif
                                            <a href="#" 
                                               class="dropdown-item delete_job" 
                                               data-toggle="modal" 
                                               data-target="#delete_job" 
                                               data-id="{{ $items->id }}">
                                                <i class="fa fa-trash-o m-r-5"></i> Delete
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Content -->
    
    <!-- Add Job Modal -->
    <div id="add_job" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Job</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('form/jobs/save') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Job Title</label>
                                    <input class="form-control @error('job_title') is-invalid @enderror" type="text" name="job_title" value="{{ old('job_title') }}"required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Department</label>
                                    <select class="select @error('department') is-invalid @enderror" name="department" required>
                                        <option selected disabled>--Selete--</option>
                                        @foreach ($department as $value)
                                        <option value="{{ $value->department }}" {{ old('department') == $value->department ? "selected" :""}} required>{{ $value->department }}</option>
                                        @endforeach
                                    </select>
                                </select> 
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Job Location</label>
                                    <textarea class="form-control @error('job_location') is-invalid @enderror" name="job_location" style="height: 44px;">{{ $company->company_name }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>No of Vacancies</label>
                                    <input class="form-control @error('no_of_vacancies') is-invalid @enderror" type="number" name="no_of_vacancies" value="{{ old('no_of_vacancies') }}" min="1" max="99" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Experience</label>
                                    <input class="form-control @error('experience') is-invalid @enderror" type="number" name="experience" value="{{ old('experience') }}" min="0" max='99' required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Age (+)</label>
                                    <input class="form-control @error('age') is-invalid @enderror" 
                                           type="number" 
                                           name="age" 
                                           id="age_input"
                                           value="{{ old('age') }}" 
                                           min="17" 
                                           required>
                                    <small class="text-muted">Minimum age requirement is 17 years old</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Salary From</label>
                                    <input type="text" class="form-control @error('salary_from') is-invalid @enderror" name="salary_from" value="{{ old('salary_from') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Salary To</label>
                                    <input type="text" class="form-control @error('salary_to') is-invalid @enderror" name="salary_to" value="{{ old('salary_to') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Job Type</label>
                                    <select class="select @error('tob_type') is-invalid @enderror" name="job_type" required>
                                        <option selected disabled>--select--</option>
                                        @foreach ($type_job as $job )
                                        <option value="{{ $job->name_type_job }}" {{ old('job_type') == $job->name_type_job ? "selected" :""}} required>{{ $job->name_type_job }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="select @error('status') is-invalid @enderror" name="status" required>
                                        <option value="Open">Open</option>
                                        <option value="Closed">Closed</option>
                                        <option value="Cancelled">Cancelled</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Start Date</label>
                                    <input type="text" 
                                           class="form-control datetimepicker @error('start_date') is-invalid @enderror" 
                                           name="start_date" 
                                           id="start_date"
                                           value="{{ old('start_date') }}" 
                                           required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Expired Date</label>
                                    <input type="text" 
                                           class="form-control datetimepicker @error('expired_date') is-invalid @enderror" 
                                           name="expired_date" 
                                           id="expired_date"
                                           value="{{ old('expired_date') }}" 
                                           required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" name="description" required>{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="submit-section">
                            <button type="submit" class="btn btn-primary submit-btn">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Add Job Modal -->
    
    <!-- Edit Job Modal -->
    <div id="edit_job" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Job</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('form/apply/job/update') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Job Title</label>
                                    <input class="form-control @error('job_title') is-invalid @enderror" 
                                           type="text" 
                                           id="e_job_title" 
                                           name="job_title" 
                                           value="{{ old('job_title') }}">
                                </div>
                            </div>
                            <input type="hidden" id="e_id" name="id" value="{{ old('id') }}">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Department</label>
                                    <select class="select @error('department') is-invalid @enderror" 
                                            id="e_department" 
                                            name="department">
                                        <option value="">Select Department</option>
                                        @foreach ($department as $value)
                                            <option value="{{ $value->department }}">{{ $value->department }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Job Location</label>
                                    <input class="form-control @error('job_location') is-invalid @enderror" 
                                           type="text" 
                                           id="e_job_location" 
                                           name="job_location" 
                                           value="{{ old('job_location') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>No of Vacancies</label>
                                    <input class="form-control @error('no_of_vacancies') is-invalid @enderror" 
                                           type="number" 
                                           id="e_no_of_vacancies" 
                                           name="no_of_vacancies" 
                                           value="{{ old('no_of_vacancies') }}" 
                                           min="1" max="99">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Experience</label>
                                    <input class="form-control @error('experience') is-invalid @enderror" 
                                           type="text" 
                                           id="e_experience" 
                                           name="experience" 
                                           value="{{ old('experience') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Age</label>
                                    <input class="form-control @error('age') is-invalid @enderror" 
                                           type="text" 
                                           id="e_age" 
                                           name="age" 
                                           value="{{ old('age') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Salary From</label>
                                    <input type="text" 
                                           class="form-control @error('salary_from') is-invalid @enderror" 
                                           id="e_salary_from" 
                                           name="salary_from" 
                                           value="{{ old('salary_from') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Salary To</label>
                                    <input type="text" 
                                           class="form-control @error('salary_to') is-invalid @enderror" 
                                           id="e_salary_to" 
                                           name="salary_to" 
                                           value="{{ old('salary_to') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Job Type</label>
                                    <select class="select @error('job_type') is-invalid @enderror" 
                                            id="e_job_type" 
                                            name="job_type">
                                        @foreach ($type_job as $job)
                                        <option value="{{ $job->name_type_job }}" 
                                                {{ old('job_type') == $job->name_type_job ? 'selected' : '' }}>
                                            {{ $job->name_type_job }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="select @error('status') is-invalid @enderror" 
                                            id="e_status" 
                                            name="status">
                                        <option value="Open" {{ old('status') == 'Open' ? 'selected' : '' }}>Open</option>
                                        <option value="Closed" {{ old('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                                        <option value="Cancelled" {{ old('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Start Date</label>
                                    <input type="text" 
                                           class="form-control datetimepicker @error('start_date') is-invalid @enderror" 
                                           id="e_start_date" 
                                           name="start_date" 
                                           value="{{ old('start_date') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Expired Date</label>
                                    <input type="text" 
                                           class="form-control datetimepicker @error('expired_date') is-invalid @enderror" 
                                           id="e_expired_date" 
                                           name="expired_date" 
                                           value="{{ old('expired_date') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              rows="5" 
                                              id="e_description" 
                                              name="description">{{ old('description') }}</textarea>
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
                        <h3>Delete Job</h3>
                        <p>Are you sure you want to delete?</p>
                    </div>
                    <div class="modal-btn delete-action">
                        <div class="row">
                            <div class="col-6">
                                <form id="deleteJobForm" action="{{ route('jobs/delete') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="job_id" id="delete_job_id">
                                    <button type="submit" class="btn btn-primary continue-btn" style="width: 100%;">Delete</button>
                                </form>
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
</div>
<!-- /Page Wrapper -->
@section('script')
    {{-- update --}}
    <script>
        $(document).ready(function() {
            // Debug log to confirm script is running
            console.log('Delete script loaded');

            // When delete button is clicked
            $('.delete_job').on('click', function(e) {
                e.preventDefault();
                var jobId = $(this).data('id');
                console.log('Delete clicked for job ID:', jobId); // Debug log
                
                // Set the job ID in the hidden input
                $('#delete_job_id').val(jobId);
                
                // Show the modal
                $('#delete_job').modal('show');
            });

            // When delete form is submitted
            $('#deleteJobForm').on('submit', function(e) {
                e.preventDefault();
                console.log('Form submitted'); // Debug log
                
                var jobId = $('#delete_job_id').val();
                console.log('Deleting job ID:', jobId); // Debug log

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        console.log('Delete response:', response); // Debug log
                        if (response.success) {
                            toastr.success('Job deleted successfully!');
                            location.reload();
                        } else {
                            toastr.error('Error deleting job!');
                        }
                        $('#delete_job').modal('hide');
                    },
                    error: function(xhr) {
                        console.error('Delete error:', xhr);
                        toastr.error('Error deleting job!');
                        $('#delete_job').modal('hide');
                    }
                });
            });

            // Success and error messages
            @if(Session::has('message'))
                toastr.options = {
                    "closeButton" : true,
                    "progressBar" : true
                }
                toastr.success("{{ session('message') }}");
            @endif

            @if(Session::has('error'))
                toastr.options = {
                    "closeButton" : true,
                    "progressBar" : true
                }
                toastr.error("{{ session('error') }}");
            @endif
        });
    </script>

    <!-- Add this JavaScript for date validation -->
    <script>
    $(document).ready(function() {
        // Initialize datepickers
        $('#start_date').datetimepicker({
            format: 'YYYY-MM-DD',
            minDate: moment(), // Can't select dates before today
            icons: {
                up: "fa fa-angle-up",
                down: "fa fa-angle-down",
                next: 'fa fa-angle-right',
                previous: 'fa fa-angle-left'
            }
        });

        $('#expired_date').datetimepicker({
            format: 'YYYY-MM-DD',
            useCurrent: false, // Important! See issue #1075
            icons: {
                up: "fa fa-angle-up",
                down: "fa fa-angle-down",
                next: 'fa fa-angle-right',
                previous: 'fa fa-angle-left'
            }
        });

        // When start date changes
        $("#start_date").on("dp.change", function (e) {
            $('#expired_date').data("DateTimePicker").minDate(e.date);
            
            // If expired_date is before start_date, reset it
            var expiredDate = $('#expired_date').data("DateTimePicker").date();
            if (expiredDate && expiredDate.isBefore(e.date)) {
                $('#expired_date').data("DateTimePicker").clear();
            }
        });

        // When expired date changes
        $("#expired_date").on("dp.change", function (e) {
            var startDate = $('#start_date').data("DateTimePicker").date();
            if (startDate && e.date.isBefore(startDate)) {
                toastr.error('Expiry date cannot be before start date');
                $(this).data("DateTimePicker").clear();
            }
        });

        // Form validation before submit
        $('#add_job_form').on('submit', function(e) {
            var startDate = $('#start_date').data("DateTimePicker").date();
            var expiredDate = $('#expired_date').data("DateTimePicker").date();
            
            if (!startDate || !expiredDate) {
                e.preventDefault();
                toastr.error('Both start date and expiry date are required');
                return false;
            }

            if (expiredDate.isBefore(startDate)) {
                e.preventDefault();
                toastr.error('Expiry date cannot be before start date');
                return false;
            }
        });
    });
    </script>

    <!-- Age validation -->
    <script>
    $(document).ready(function() {
        var ageTimer;
        
        // Age validation with delay and length check
        $('#age_input').on('input', function() {
            var input = $(this);
            clearTimeout(ageTimer);
            
            // Remove error state while typing
            input.removeClass('is-invalid');
            
            // Only validate if input length is 2 or more digits
            if(input.val().length >= 2) {
                ageTimer = setTimeout(function() {
                    var age = parseInt(input.val());
                    if (age < 17) {
                        toastr.error('Minimum age requirement is 17 years old');
                        input.addClass('is-invalid');
                    }
                }, 500); // Half second delay
            }
        });

        // Form validation before submit remains the same
        $('#add_job_form').on('submit', function(e) {
            var age = parseInt($('#age_input').val());
            if (age < 17) {
                e.preventDefault();
                toastr.error('Minimum age requirement is 17 years old');
                $('#age_input').addClass('is-invalid');
                return false;
            }

            // Date validation code...
        });
    });
    </script>
@endsection
@endsection