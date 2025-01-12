@extends('layouts.job')
@section('content')
    {{-- message --}}
    {!! Toastr::message() !!}
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Header -->
        <div class="header">
            <!-- Logo -->
            <div class="header-left">
                <a href="{{ route('form/job/list') }}" class="logo">
                    <img src="{{ URL::to('images/logo-circle.png') }}" width="40" height="40" alt="">
                </a>
            </div>
            <!-- /Logo -->
            <!-- Header Title -->
            <div class="page-title-box float-left">
                <h3><a href="{{ route('form/job/list') }}" class="text-white">Apply for Job</a></h3>
            </div>
            <!-- /Header Title -->
            <!-- Header Menu -->
            <ul class="nav user-menu">
                <!-- Flag -->
                <li class="nav-item dropdown has-arrow flag-nav">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button">
                        <img src="{{ URL::to('assets/img/flags/us.png') }}" alt="" height="20"> <span>English</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="javascript:void(0);" class="dropdown-item">
                            <img src="{{ URL::to('assets/img/flags/us.png') }}" alt="" height="16"> English
                        </a>
                    </div>
                </li>
                <!-- /Flag -->
                
            </ul>
            <!-- /Header Menu -->

            <!-- Mobile Menu -->
            <div class="dropdown mobile-user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="{{ route('login') }}">Login</a>
                    <a class="dropdown-item" href="{{ route('register') }}">Register</a>
                </div>
            </div>
            <!-- /Mobile Menu -->

        </div>
        <!-- /Header -->

        <!-- Page Wrapper -->
        <div class="page-wrapper job-wrapper">
            <!-- Page Content -->
            <div class="content container">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="page-title">Job Details</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('form/job/list') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Job Details</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->
                <div class="row">
                    <div class="col-md-8">
                        <div class="job-info job-widget">
                            <h3 class="job-title">{{ $job_view -> job_title }}</h3>
                            <span class="job-dept">{{ $job_view-> department }}</span>
                            <ul class="job-post-det">
                                <li><i class="fa fa-calendar"></i> Post Date: <span class="text-blue">{{ date('d F, Y',strtotime($job_view->start_date)) }}</span></li>
                                <li><i class="fa fa-calendar"></i> Last Date: <span class="text-blue">{{ date('d F, Y',strtotime($job_view->expired_date)) }}</span></li>
                                <li><i class="fa fa-user-o"></i> Applications: <span class="text-blue">4</span></li>
                                <li><i class="fa fa-eye"></i> Views: <span class="text-blue">
                                    @if (!empty($job_view->count))
                                        {{ $job_view->count }}
                                        @else
                                        0
                                    @endif
                                    </span>
                                </li>
                            </ul>
                        </div>
                        <div class="job-content job-widget">
                            <div class="job-desc-title"><h4>Job Description</h4></div>
                            <div class="job-description">
                                <p>{{ $job_view->description }}</p>
                            </div>
                            <div class="job-desc-title"></div>
                            <div class="job-description">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="job-det-info job-widget">
                            @if (!$now->greaterThanOrEqualTo($expired_date))
                                <a class="btn job-btn" href="#" data-toggle="modal" data-target="#apply_job">Apply For This Job</a>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fa fa-exclamation-triangle"></i> This job posting has expired
                                </div>
                            @endif
                            
                            <div class="info-list">
                                <span><i class="fa fa-bar-chart"></i></span>
                                <h5>Job Type</h5>
                                <p>{{ $job_view->job_type }}</p>
                            </div>
                            <div class="info-list">
                                <span><i class="fa fa-money"></i></span>
                                <h5>Salary</h5>
                                <p>RM{{ $job_view->salary_from }} - RM{{ $job_view->salary_to }}</p>
                            </div>
                            <div class="info-list">
                                <span><i class="fa fa-suitcase"></i></span>
                                <h5>Experience</h5>
                                <p>{{ $job_view->experience }}</p>
                            </div>
                            <div class="info-list">
                                <span><i class="fa fa-user"></i></span>
                                <h5>Age</h5>
                                <p>{{ $job_view->age }} above</p>
                            </div>
                            <div class="info-list">
                                <span><i class="fa fa-ticket"></i></span>
                                <h5>Vacancy</h5>
                                <p>{{ $job_view->no_of_vacancies }}</p>
                            </div>
                            <div class="info-list">
                                <span><i class="fa fa-map-signs"></i></span>
                                <h5>Location</h5>
                                <p>{{ $job_view->job_location }}</p>
                            </div>
                            <div class="info-list">
                                <p class="text-truncate"> 096-566-666
                                <br> <a href="{{route('homepage')}}" title="@example.com">www.HRTechInc.com</a>
                                </p>
                            </div>
                            <div class="info-list text-center">
                                @if ($now->greaterThanOrEqualTo($expired_date))
                                    <p class="text-danger">
                                        <i class="fa fa-clock-o"></i> Job expired on {{ date('d F, Y', strtotime($expired_date)) }}
                                    </p>
                                @else
                                    <a class="app-ends" href="#">
                                        <i class="fa fa-clock-o"></i> Application ends in {{ $diff }}
                                    </a>
                                @endif
                            </div>
                
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /Page Content -->

            <!-- Apply Job Modal -->
            <div class="modal custom-modal fade" id="apply_job" role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Your Details</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                        <form action="{{ route('form/apply/job/save') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Job Title</label>                       
                                        <input class="form-control @error('job_title') is-invalid @enderror" name="job_title" 
                                        value="{{ $job_view->job_title }}" required readonly>                                      
                                    </div>
                                    <input type="hidden" name="interview_datetime" value="{{ old('interview_datetime') }}">
                                    <div class="form-group">
                                        <label>Name <span class="text-danger">*</span></label>
                                        <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" placeholder="Full name as per IC" value="{{ old('name') }}" required>
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
                                        <input class="form-control @error('birth_date') is-invalid @enderror" type="hidden" name="birth_date" id="birth_date" value="">
                                    </div>

                                    <div class="form-group">
                                        <label>Age</label>
                                        <input class="form-control @error('age') is-invalid @enderror" type="number" name="age" id="age" value="{{ old('age') }}" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label>Race</label>
                                        <div class="form-group form-focus">
                                        <select class="form-control floating" name="race">
                                            <option value="" disabled selected></option>
                                            <option value="Malay"  {{ old(key: 'race') == 'Malay' ? 'selected' : '' }}>Malay</option>
                                            <option value="Chinese" {{ old(key: 'race') == 'Chinese' ? 'selected' : '' }}">Chinese</option>
                                            <option value="Indian"{{ old(key: 'race') == 'Indian' ? 'selected' : '' }}">Indian</option>
                                            <option value="Others" {{ old(key: 'race') == 'Others' ? 'selected' : '' }}">Others</option>
                                        </select>
                                        <label class="focus-label">Race</label>
                                    </div>
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
                                        <label>Phone Number <span class="text-danger">*</span></label>
                                        <input class="form-control @error('phone_number') is-invalid @enderror" 
                                               type="tel" 
                                               name="phone_number" 
                                               placeholder="Enter with country code (e.g., 60123456789)" 
                                               pattern="^60\d{9,10}$"
                                               title="Please enter a valid Malaysian phone number starting with 60"
                                               value="{{ old(key: 'phone_number')}}"required>
                                        <small class="form-text text-muted">Format: 60 + phone number without leading 0 (e.g., 60123456789)</small>
                                    </div>

                                    <div class="form-group">
                                        <label>Email <span class="text-danger">*</span></label>
                                        <input class="form-control @error('email') is-invalid @enderror" type="email" name="email" placeholder="e.g., example@domain.com" value="{{ old(key: 'email') }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Highest Education<span class="text-danger">*</span></label>
                                        <select class="form-control @error('highest_education') is-invalid @enderror" name="highest_education" required>
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
                                        <label>Work Experiences (Years)<span class="text-danger">*</span></label>
                                        <input class="form-control @error('work_experiences') is-invalid @enderror" type="number" name="work_experiences" value="{{ old('work_experiences') }}" min="0" max="100" required>
                                        @error('work_experiences')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Role Name</label>
                                        <input class="form-control @error('role_name') is-invalid @enderror" type="text" name="role_name" value="{{ old('role_name', 'Candidate') }}" readonly>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="form-group">
                                <label>Upload your CV<span class="text-danger">*</span></label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('cv_upload') is-invalid @enderror" id="cv_upload" name="cv_upload" onchange="updateFileName(this)" required>
                                    <label class="custom-file-label" for="cv_upload">Choose file (PDF only)</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Message (short intro)</label>
                                <textarea class="form-control @error('message') is-invalid @enderror" name="message" id="message" placeholder="Max 20 words">{{ old('message') }}</textarea>
                                <small class="form-text text-muted" id="word-count">0/20 words</small>
                            </div>
                            

                            <!-- <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Interview Date and Time</label>
                                    <input class="form-control @error('interview_datetime') is-invalid @enderror" type="datetime-local" name="interview_datetime" value="{{ old('interview_datetime') }}">
                                </div>
                            </div>
                        </div> -->
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Submit</button>
                            </div>
                        </form>

                        </div>
                    </div>
                </div>
            </div>
            <!-- /Apply Job Modal -->

        </div>
        <!-- /Page Wrapper -->
    
    <!-- /Main Wrapper -->

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
     <!--Validate IC number format-->
     <script>
     document.addEventListener('DOMContentLoaded', function() {
        const icNumberInput = document.getElementById('ic_number');
        const birthDateInput = document.getElementById('birth_date');
        const ageInput = document.getElementById('age');
        
        if (icNumberInput) {
            icNumberInput.addEventListener('input', function(e) {
                // Remove any non-digits
                let value = this.value.replace(/[^\d]/g, '');
                
                // Limit to 12 digits
                if (value.length > 12) {
                    value = value.slice(0, 12);
                }
                
                this.value = value;
                
                // Clear previous validations if input is incomplete
                if (value.length !== 12) {
                    this.setCustomValidity('IC number must be exactly 12 digits');
                    this.classList.remove('is-valid');
                    birthDateInput.value = '';
                    ageInput.value = '';
                    if (value.length > 0) {
                        this.classList.add('is-invalid');
                    } else {
                        this.classList.remove('is-invalid');
                    }
                    return;
                }

                // Parse IC components
                const yearPrefix = value.substring(0, 2);
                const month = parseInt(value.substring(2, 4));
                const day = parseInt(value.substring(4, 6));
                
                // Validate month and day
                let isValid = true;
                let errorMessage = '';
                
                // Month validation
                if (month < 1 || month > 12) {
                    isValid = false;
                    errorMessage = 'Invalid month in IC number';
                }
                
                // Day validation based on month
                if (isValid) {
                    const daysInMonth = new Date(2000, month, 0).getDate();
                    if (day < 1 || day > daysInMonth) {
                        isValid = false;
                        errorMessage = `Invalid day for ${getMonthName(month)}`;
                    }
                }
                
                if (!isValid) {
                    this.setCustomValidity(errorMessage);
                    this.classList.remove('is-valid');
                    this.classList.add('is-invalid');
                    birthDateInput.value = '';
                    ageInput.value = '';
                    return;
                }

                // Determine full year (19xx or 20xx)
                let fullYear = parseInt(yearPrefix);
                const currentYear = new Date().getFullYear();
                fullYear += fullYear > (currentYear - 2000) ? 1900 : 2000;

                // Format date components
                const formattedMonth = month.toString().padStart(2, '0');
                const formattedDay = day.toString().padStart(2, '0');
                const birthDate = `${fullYear}-${formattedMonth}-${formattedDay}`;
                
                // Calculate age
                const birthDateObj = new Date(birthDate);
                const today = new Date();
                let age = today.getFullYear() - birthDateObj.getFullYear();
                const monthDiff = today.getMonth() - birthDateObj.getMonth();
                
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDateObj.getDate())) {
                    age--;
                }

                // Validate age (minimum 17 years)
                if (age < 17) {
                    isValid = false;
                    errorMessage = 'Applicant must be at least 17 years old';
                    this.setCustomValidity(errorMessage);
                    this.classList.remove('is-valid');
                    this.classList.add('is-invalid');
                    birthDateInput.value = '';
                    ageInput.value = '';
                    return;
                }

                // Set values if everything is valid
                this.setCustomValidity('');
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
                birthDateInput.value = birthDate;
                ageInput.value = age;
            });
        }
    });

    // Helper function to get month name
    function getMonthName(monthNumber) {
        const months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        return months[monthNumber - 1];
    }
</script>


<!--/Validate IC number format-->

@if (session('error'))
    <script>
        toastr.error("{{ session('error') }}");
    </script>
@endif

@if (session('success'))
    <script>
        toastr.success("{{ session('success') }}");
    </script>
@endif
@endsection