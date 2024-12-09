@extends('layouts.master')
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Job Applicants</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">Job Applicants</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table mb-0 datatable" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Apply Date</th>
                                    <th>Apply Job</th>
                                    <th class="text-center">Status</th>
                                    <th>Resume</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($apply_for_jobs as $key=>$apply )
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>
                                        <a href="#" class="view-candidate" data-toggle="modal" data-target="#view_candidate" 
                                           data-id="{{ $apply->id }}"
                                           data-name="{{ $apply->name }}"
                                           data-email="{{ $apply->email }}"
                                           data-phone="{{ $apply->phone_number }}"
                                           data-ic="{{ $apply->ic_number }}"
                                           data-birth="{{ date('d M Y', strtotime($apply->birth_date)) }}"
                                           data-age="{{ $apply->age }}"
                                           data-gender="{{ $apply->gender }}"
                                           data-race="{{ $apply->race }}"
                                           data-education="{{ $apply->highest_education }}"
                                           data-experience="{{ $apply->work_experiences }}"
                                           data-job-title="{{ $apply->job_title }}">
                                            {{ $apply->name }}
                                        </a>
                                    </td>
                                    <td>{{ $apply->email }}</td>
                                    <td>{{ $apply->phone_number }}</td>
                                    <td>{{ date('d F, Y',strtotime($apply->created_at)) }}</td>
                                    <td><a href="{{ url('job/details/'.$apply->id) }}">{{ $apply->job_title }}</a></td>
                                    <td class="text-center">
                                        <div class="dropdown action-label">
                                            <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
                                                <i class="fa fa-dot-circle-o text-info"></i> New
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#hired_modal"><i class="fa fa-dot-circle-o text-success"></i> Hired</a>
                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#rejected_modal"><i class="fa fa-dot-circle-o text-danger"></i> Rejected</a>
                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#interviewed_modal"><i class="fa fa-dot-circle-o text-warning"></i> Interviewed</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td><a href="{{ url('cv/download/'.$apply->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-download"></i> Download</a></td>
                                    <td class="text-right">
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#"><i class="fa fa-clock-o m-r-5"></i> Schedule Interview</a>
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
        <!-- Hired Modal -->
        <div id="hired_modal" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Hire Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to hire this candidate as an employee?</p>
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn-sm" onclick="hireCandidate()">Yes</button>
                            <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Hired Modal -->
        <!-- Rejected Modal -->
        <div id="rejected_modal" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Reject Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to reject this candidate?</p>
                        <div class="submit-section">
                            <button class="btn btn-danger submit-btn-sm" onclick="rejectCandidate()">Yes</button>
                            <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Rejected Modal -->
        <!-- Interviewed Modal -->
        <div id="interviewed_modal" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Schedule Interview</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="interview_form">
                            <div class="form-group">
                                <label class="col-form-label">Interview Date & Time</label>
                                <input class="form-control datetimepicker" type="text" id="interview_datetime" required>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">Interview Type</label>
                                <select class="form-control" id="interview_type" onchange="toggleAddress()" required>
                                    <option value="f2f">Face-to-Face</option>
                                    <option value="online">Online</option>
                                </select>
                            </div>
                            <div class="form-group" id="company_address_group">
                                <label class="col-form-label">Company Address</label>
                                <input class="form-control" type="text" value="1234 Company Address, City, Country" readonly>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">Candidate Email</label>
                                <input class="form-control" type="email" value="candidate@example.com" readonly>
                            </div>
                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn" onclick="scheduleInterview()">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Interviewed Modal -->
        <!-- View Candidate Modal -->
        <div id="view_candidate" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Candidate Profile</h5>
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
        <!-- /View Candidate Modal -->
        <!-- /Page Content -->
    </div>
    <!-- /Page Wrapper -->
<script>
function hireCandidate() {
    // Logic to hire the candidate
    alert('Candidate has been hired.');
    // Close modal and update status in the table
    $('#hired_modal').modal('hide');
}

function rejectCandidate() {
    // Logic to reject the candidate and update the table row to red
    alert('Candidate has been rejected.');
    $('#rejected_modal').modal('hide');
    // Example: Change row color to red
    // document.querySelector('#candidate_row').style.backgroundColor = 'red';
}

function toggleAddress() {
    var interviewType = document.getElementById('interview_type').value;
    var addressGroup = document.getElementById('company_address_group');
    if (interviewType === 'f2f') {
        addressGroup.style.display = 'block';
    } else {
        addressGroup.style.display = 'none';
    }
}

function scheduleInterview() {
    // Logic to schedule the interview and send an email
    alert('Interview has been scheduled and an email has been sent to the candidate.');
    $('#interviewed_modal').modal('hide');
}

//send email
function sendEmail(candidateEmail, interviewDate, position, companyName) {
    var emailContent = `
        Dear Candidate,

        We are pleased to inform you that your interview has been scheduled as follows:

        Date & Time: ${interviewDate}
        Position: ${position}
        Company: ${companyName}

        Please make sure to be available at the scheduled time. We look forward to meeting you.

        Best regards,
        [Your Company Name]

        This is an automated message. Please do not reply to this email.
    `;
    console.log(`Sending email to: ${candidateEmail}`);
    console.log(`Email content: ${emailContent}`);
    // Here send the email using a backend service
}

//Automatic Email Sending
function scheduleInterview() {
    var candidateEmail = document.querySelector('#interview_form input[type="email"]').value;
    var interviewDate = document.querySelector('#interview_form input[type="text"]').value;
    var position = 'Software Engineer'; // Example position
    var companyName = 'Your Company Name';
    
    sendEmail(candidateEmail, interviewDate, position, companyName);
    alert('Interview has been scheduled and an email has been sent to the candidate.');
    $('#interviewed_modal').modal('hide');
}

//Styling the Rejected Row
function rejectCandidate() {
    var row = document.getElementById('candidate_row');
    row.style.backgroundColor = 'red';
    alert('Candidate has been rejected.');
    $('#rejected_modal').modal('hide');
}
</script>
<!-- View Candidate Modal -->
<script>
    $(document).ready(function() {
    $('.view-candidate').on('click', function() {
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
});
</script>
@endsection