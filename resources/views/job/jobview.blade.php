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
                <a href="{{ route('home') }}" class="logo">
                    <img src="{{ URL::to('images/logo-circle.png') }}" width="40" height="40" alt="">
                </a>
            </div>
            <!-- /Logo -->
            <!-- Header Title -->
            <div class="page-title-box float-left">
                <h3>Apply Job</h3>
            </div>
            <!-- /Header Title -->
            <!-- Header Menu -->
            <ul class="nav user-menu">
                <!-- Search -->
                <li class="nav-item">
                    <div class="top-nav-search">
                        <a href="javascript:void(0);" class="responsive-search">
                            <i class="fa fa-search"></i>
                        </a>
                        <form action="search.html">
                            <input class="form-control" type="text" placeholder="Search here">
                            <button class="btn" type="submit"><i class="fa fa-search"></i></button>
                        </form>
                    </div>
                </li>
                <!-- /Search -->
                <!-- Flag -->
                <li class="nav-item dropdown has-arrow flag-nav">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button">
                        <img src="{{ URL::to('assets/img/flags/us.png') }}" alt="" height="20"> <span>English</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="javascript:void(0);" class="dropdown-item">
                            <img src="{{ URL::to('assets/img/flags/us.png') }}" alt="" height="16"> English
                        </a>
                        <a href="javascript:void(0);" class="dropdown-item">
                            <img src="{{ URL::to('assets/img/flags/kh.png') }}" alt="" height="16"> Khmer 
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
                            <h3 class="page-title">Jobs</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('form/job/list') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Jobs</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->
                <div class="row">
                    <div class="col-md-8">
                        <div class="job-info job-widget">
                            <h3 class="job-title">{{ $job_view[0]->job_title }}</h3>
                            <span class="job-dept">{{ $job_view[0]->department }}</span>
                            <ul class="job-post-det">
                                <li><i class="fa fa-calendar"></i> Post Date: <span class="text-blue">{{ date('d F, Y',strtotime($job_view[0]->start_date)) }}</span></li>
                                <li><i class="fa fa-calendar"></i> Last Date: <span class="text-blue">{{ date('d F, Y',strtotime($job_view[0]->expired_date)) }}</span></li>
                                <li><i class="fa fa-user-o"></i> Applications: <span class="text-blue">4</span></li>
                                <li><i class="fa fa-eye"></i> Views: <span class="text-blue">
                                    @if (!empty($job_view[0]->count))
                                        {{ $job_view[0]->count }}
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
                                <p>{{ $job_view[0]->description }}</p>
                            </div>
                            <div class="job-desc-title"><h4>Job Description</h4></div>
                            <div class="job-description">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>
                                <ul class="square-list">
                                    <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
                                    <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
                                    <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
                                    <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
                                    <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="job-det-info job-widget">
                            <a class="btn job-btn" href="#" data-toggle="modal" data-target="#apply_job">Apply For This Job</a>
                            <div class="info-list">
                                <span><i class="fa fa-bar-chart"></i></span>
                                <h5>Job Type</h5>
                                <p>{{ $job_view[0]->job_type }}</p>
                            </div>
                            <div class="info-list">
                                <span><i class="fa fa-money"></i></span>
                                <h5>Salary</h5>
                                <p>RM{{ $job_view[0]->salary_from }} - RM{{ $job_view[0]->salary_to }}</p>
                            </div>
                            <div class="info-list">
                                <span><i class="fa fa-suitcase"></i></span>
                                <h5>Experience</h5>
                                <p>{{ $job_view[0]->experience }}</p>
                            </div>
                            <div class="info-list">
                                <span><i class="fa fa-user"></i></span>
                                <h5>Age</h5>
                                <p>{{ $job_view[0]->age }} above</p>
                            </div>
                            <div class="info-list">
                                <span><i class="fa fa-ticket"></i></span>
                                <h5>Vacancy</h5>
                                <p>{{ $job_view[0]->no_of_vacancies }}</p>
                            </div>
                            <div class="info-list">
                                <span><i class="fa fa-map-signs"></i></span>
                                <h5>Location</h5>
                                <p>{{ $job_view[0]->job_location }}</p>
                            </div>
                            <div class="info-list">
                                <p class="text-truncate"> 096-566-666
                                <br> <a href="https://www.souysoeng.com" title="soengsouy@example.com">soengsouy@example.com</a>
                                <br> <a href="https://www.souysoeng.com" target="_blank" title="https://www.souysoeng.com">https://www.souysoeng.com</a>
                                </p>
                            </div>
                            <div class="info-list text-center">
                                <a class="app-ends" href="#">Application ends in 2d 7h 6m</a>
                            </div>
                
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Chatbox Icon -->
                <div id="chatbox-icon" style="position: fixed; bottom: 20px; right: 20px; cursor: pointer; background: #007bff; color: #fff; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;" onclick="toggleChatbox()">
                    <i class="fa fa-comments" style="font-size: 24px;"></i> 
                </div>

                <!-- Chatbox -->
                <div id="chatbox" style="display: none; position: fixed; bottom: 90px; right: 20px; background: #fff; border: 1px solid #ddd; border-radius: 10px; width: 300px; max-height: 400px; overflow: hidden; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">
                <div style="background: #007bff; color: #fff; padding: 10px; text-align: center; border-radius: 10px 10px 0 0;">
                    Chat with Us
                    <span style="float: right; cursor: pointer;" onclick="toggleChatbox()">×</span> <!-- Close button -->
                </div>
                <div id="chatbox-messages" style="padding: 10px; height: 300px; overflow-y: auto;">
                    <!-- Initial static message -->
                    <div style="text-align: left;">Hello! Let’s start your job application for <b>{{ $job_view[0]->job_title ?? 'the position' }}</b>.</div>
                </div>
                <div style="display: flex; border-top: 1px solid #ddd;">
                    <input type="file" id="chatbox-file" name="file" style="display:none;">
                    <input type="text" id="chatbox-input" style="flex: 1; border: none; padding: 10px;" placeholder="Type your message..." />
                    <button id="chatbox-send" style="background: #007bff; color: #fff; border: none; padding: 10px;" onclick="sendMessage()">Send</button>
                </div>
            </div>

                <!-- /Chatbox -->

            </div>
            <!-- /Page Content -->

            <!-- Apply Job Modal -->
            <div class="modal custom-modal fade" id="apply_job" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
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
                                <input type="hidden" name="interview_datetime" value="{{ old('interview_datetime') }}">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="hidden" name="job_title" value="{{ $job_view[0]->job_title }}">
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
                                        <div class="form-group form-focus">
                                        <select class="form-control floating" name="race">
                                            <option value="" disabled selected></option>
                                            <option value="malay">Malay</option>
                                            <option value="chinese">Chinese</option>
                                            <option value="indian">Indian</option>
                                            <option value="others">Others</option>
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
    <!--Chatbox-->
    <script>
        const CHAT_HANDLE_URL = "{{ route('chat/handle') }}";
        const CSRF_TOKEN = "{{ csrf_token() }}";
    </script>
    <script src="/assets/js/chatbox.js"></script>
    <!--/Chatbox-->
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