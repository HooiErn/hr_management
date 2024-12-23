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
                            <button id="apply-action" class="btn btn-primary">Apply</button>
                        </div>
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
                                    <th><input type="checkbox" id="select-all"></th>
                                    <th>#</th>
                                    <th>Interviewer ID</th>
                                    <th>Name</th>
                                    <th>Mobile Number</th>
                                    <th>Job Applied</th>
                                    <th>Gender</th>
                                    <th>Resume</th>
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
                                                   data-birth="{{ date('d M Y', strtotime($interviewer->birth_date)) }}"
                                                   data-age="{{ $interviewer->age }}"
                                                   data-gender="{{ $interviewer->gender }}"
                                                   data-race="{{ $interviewer->race }}"
                                                   data-education="{{ $interviewer->highest_education }}"
                                                   data-experience="{{ $interviewer->work_experiences }}"
                                                   data-job-title="{{ $interviewer->job_title }}">
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
                                        <td>
                                            @if($interviewer->cv_upload)
                                                <a href="{{ asset('assets/cv/' . $interviewer->cv_upload) }}" class="btn btn-sm btn-primary" target="_blank">
                                                    <i class="fa fa-download"></i> Download
                                                </a>
                                            @else
                                                <span class="text-muted">No Resume</span>
                                            @endif
                                        </td>
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
                                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#edit_job" 
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
                            <!-- <div class="send-notification" style="text-align:center; border-radius:50px; margin-top:15px;">
                            <button type="button" class="btn btn-success" id="sendWhatsApp" disabled>
                                Send WhatsApp Notification
                            </button>
                            </div> -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Schedule Interview Modal -->
         
    </div>
    <!-- /Page Wrapper -->

<!-- View Interviewer Modal -->
<script>
    $(document).ready(function() {
    $('.view-interviewer').on('click', function() {
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

        // Update modal fields
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
    });

    // $('#sendWhatsApp').on('click', function() {
    //     var phoneNumber = $('#candidate_phone').val().replace(/[^0-9]/g, '');
    //     var interviewDateTime = $('#interview_datetime').val();
    //     var interviewType = $('#interview_type').val();
    //     var roomID = Math.floor(Math.random() * 10000); // Generate room ID
        
    //     // Validate inputs
    //     if (!phoneNumber || !interviewDateTime || !interviewType) {
    //         toastr.error('Please ensure all fields are filled');
    //         return;
    //     }

 
});
    })
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

        $.ajax({
            url: '{{ route("schedule.interview") }}',
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log("Response:", response); // Log the actual server response
                if (response.success) {
                    $('#schedule_interview').modal('hide');
                    toastr.success('Interview scheduled successfully');
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    toastr.error('Unexpected response format. Please check the server response.');
                }
            },
            error: function(xhr, status, error) {
                // More detailed error logging
                console.error('Error Status:', status);
                console.error('Error:', error);
                console.error('Response:', xhr.responseText); // This will show the actual error message
                toastr.error('Error scheduling interview. Please check console for details.');
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

    // When edit button is clicked
    $(document).on('click', '.dropdown-item', function() {
        if ($(this).data('target') === '#edit_job') {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var email = $(this).data('email');
            var phone = $(this).data('phone_number');
            var jobTitle = $(this).data('job-title');
            var gender = $(this).data('gender');
            var ic = $(this).data('ic');

            // Set the values in the edit form
            $('#edit_id').val(id);
            $('#edit_name').val(name);
            $('#edit_email').val(email);
            $('#edit_phone').val(phone);
            $('#edit_job_title').val(jobTitle);
            $('#edit_gender').val(gender);
            $('#edit_ic').val(ic);
        }
    });

    // Add form submission handler
    $('#edit_job form').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#edit_job').modal('hide');
                toastr.success('Interviewer updated successfully!');
                setTimeout(function() {
                    location.reload();
                }, 1500);
            },
            error: function(xhr, status, error) {
                console.error(error);
                toastr.error('Error updating interviewer. Please try again.');
            }
        });
    });

    // Update the WhatsApp button to be initially disabled
    $('#schedule_interview .modal-body').find('.send-notification').html(`
        <button type="button" class="btn btn-success" id="sendWhatsApp" disabled>
            Send WhatsApp Notification
        </button>
    `);

    // Add listener for interview type selection
    $('#interview_type').on('change', function() {
        const interviewType = $(this).val();
        const interviewDateTime = $('#interview_datetime').val();
        
        // Enable/disable WhatsApp button based on selections
        if (interviewType && interviewDateTime) {
            $('#sendWhatsApp').prop('disabled', false);
        } else {
            $('#sendWhatsApp').prop('disabled', true);
        }
    });

    // Add listener for datetime selection
    $('#interview_datetime').on('change', function() {
        const interviewDateTime = $(this).val();
        const interviewType = $('#interview_type').val();
        
        // Enable/disable WhatsApp button based on selections
        if (interviewType && interviewDateTime) {
            $('#sendWhatsApp').prop('disabled', false);
        } else {
            $('#sendWhatsApp').prop('disabled', true);
        }
    });

    $('#sendWhatsApp').on('click', function() {
        var phoneNumber = $('#candidate_phone').val().replace(/[^0-9]/g, '');
        var interviewDateTime = $('#interview_datetime').val();
        var interviewType = $('#interview_type').val();
        
        // Validate required data
        if (!phoneNumber || !interviewDateTime || !interviewType) {
            toastr.error('Please ensure interview type and date/time are selected');
            return;
        }

        // Fetch company information using Ajax
        $.ajax({
            url: '{{ route("get.company.info") }}', 
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const companyName = response.company.company_name;
                    const companyAddress = response.company.address;
                    const companyCity = response.company.city;
                    const companyState = response.company.state;
                    const companyPostalC =response.company.postal_code;
                    
                    // Format the message with company information
                    var message = "Dear candidate,\n\n"
                        + "Your interview has been scheduled at " + companyName + ":\n\n"
                        + "Date & Time: " + interviewDateTime + "\n"
                        + "Interview Type: " + (interviewType === 'f2f' ? 'Face-to-Face' : 'Online') + "\n";
                    
                    // Add location information for face-to-face interviews
                    if (interviewType === 'f2f') {
                        message += "Location: " + companyAddress + "," + companyPostalC +" "+ companyState
                         + "," + companyCity  + "\n" +
                         "Please arrive 15 minutes before the scheduled time.\n";
                    }
                    
                    message += "\nPlease confirm your attendance.\n"
                        + "Thank you.";
                    
                    // Encode the message for URL
                    var encodedMessage = encodeURIComponent(message);
                    
                    // Create WhatsApp URL with direct send
                    var whatsappURL = "https://api.whatsapp.com/send?phone=" + phoneNumber + "&text=" + encodedMessage;
                    
                    // Open WhatsApp in new window
                    var newWindow = window.open(whatsappURL, '_blank');
                    
                    // Show success message
                    toastr.success('WhatsApp notification window opened');
                    
                    // Close the schedule modal
                    $('#schedule_interview').modal('hide');
                } else {
                    toastr.error('Could not fetch company information');
                }
            },
            error: function() {
                toastr.error('Error fetching company information');
            }
        });
    });
});
</script>
<script>
    // Select all checkboxes
    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('input[name="interviewers[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Handle bulk action
    document.getElementById('apply-action').addEventListener('click', function() {
        const action = document.getElementById('bulk-action').value;
        const selectedInterviewers = Array.from(document.querySelectorAll('input[name="interviewers[]"]:checked')).map(cb => cb.value);

        if (action && selectedInterviewers.length > 0) {
            // Perform AJAX request to handle bulk action
            $.ajax({
                url: '{{ route("interviewer/bulkAction") }}',
                method: 'POST',
                data: {
                    action: action,
                    interviewers: selectedInterviewers,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    toastr.success('Action applied successfully.');
                    location.reload(); // Reload the page to see changes
                },
                error: function(xhr) {
                    toastr.error('Error applying action. Please try again.');
                    console.error(xhr);  // Debug AJAX error
                }
            });
        } else {
            toastr.warning('Please select an action and at least one interviewer.');
        }
    });
</script>
<style>
/* Enhanced styles for time picker */
.bootstrap-datetimepicker-widget .timepicker {
    margin-top: 0;
    padding: 1em;
}

.bootstrap-datetimepicker-widget .timepicker-hour,
.bootstrap-datetimepicker-widget .timepicker-minute {
    font-size: 1.2em;
    font-weight: bold;
    cursor: pointer;
    border-radius: 4px;
    padding: 5px;
}

.bootstrap-datetimepicker-widget .timepicker-hour:hover,
.bootstrap-datetimepicker-widget .timepicker-minute:hover {
    background-color: #f8f9fa;
}

.bootstrap-datetimepicker-widget .btn[data-action] {
    padding: 6px 12px;
    margin: 2px;
    border-radius: 4px;
}

.bootstrap-datetimepicker-widget .timepicker-picker table td {
    padding: 5px;
    text-align: center;
}

.bootstrap-datetimepicker-widget .timepicker-picker table td span {
    width: 35px;
    height: 35px;
    line-height: 35px;
    display: inline-block;
}

/* Make sure the time picker is visible */
.bootstrap-datetimepicker-widget.wider {
    width: 300px !important;
}

.bootstrap-datetimepicker-widget .timepicker-picker table td a {
    padding: 5px;
    width: 100%;
    display: inline-block;
    border: 1px solid transparent;
    border-radius: 4px;
}

.bootstrap-datetimepicker-widget .timepicker-picker table td a:hover {
    background-color: #f8f9fa;
    border-color: #dee2e6;
}

.btn-success {
    margin-left: 10px;
    background-color: #25d366;
    border-color: #25d366;
}

.btn-success:hover {
    background-color: #128c7e;
    border-color: #128c7e;
}
</style>


@endsection