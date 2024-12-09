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
                        <h3 class="page-title">Job Details</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Job Details</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            
            <div class="row">
                <div class="col-md-8">
                    <div class="job-info job-widget">
                        <h3 class="job-title">{{ $job_view_detail[0]->job_title }}</h3>
                        <span class="job-dept">{{ $job_view_detail[0]->department }}</span>
                        <ul class="job-post-det">
                            <li><i class="fa fa-calendar"></i> Post Date: <span class="text-blue">{{ date('d F, Y',strtotime($job_view_detail[0]->start_date)) }}</span></li>
                            <li><i class="fa fa-calendar"></i> Last Date: <span class="text-blue">{{ date('d F, Y',strtotime($job_view_detail[0]->expired_date)) }}</span></li>
                            <li><i class="fa fa-user-o"></i> Applications: <span class="text-blue"></span></li>
                            <li><i class="fa fa-eye"></i> Views: <span class="text-blue"></span></li>
                        </ul>
                    </div>
                    <div class="job-content job-widget">
                        <div class="job-desc-title"><h4>Job Description</h4></div>
                        <div class="job-description">
                            <p>{{ $job_view_detail[0]->description }}</p>
                        </div>
                        <div class="job-desc-title"><h4></h4></div>
                        <div class="job-description">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="job-det-info job-widget">
                        <a class="btn job-btn" href="#" data-toggle="modal" data-target="#edit_job">Edit</a>
                        <div class="info-list">
                            <span><i class="fa fa-bar-chart"></i></span>
                            <h5>Job Type</h5>
                            <p>{{ $job_view_detail[0]->job_type }}</p>
                        </div>
                        <div class="info-list">
                            <span><i class="fa fa-money"></i></span>
                            <h5>Salary</h5>
                            <p>{{ $job_view_detail[0]->salary_from }}$ - {{ $job_view_detail[0]->salary_to }}$</p>
                        </div>
                        <div class="info-list">
                            <span><i class="fa fa-suitcase"></i></span>
                            <h5>Experience</h5>
                            <p>{{ $job_view_detail[0]->experience }}</p>
                        </div>
                        <div class="info-list">
                            <span><i class="fa fa-ticket"></i></span>
                            <h5>Vacancy</h5>
                            <p>{{ $job_view_detail[0]->no_of_vacancies }}</p>
                        </div>
                        <div class="info-list">
                            <span><i class="fa fa-map-signs"></i></span>
                            <h5>Location</h5>
                            <p>{{ $job_view_detail[0]->job_location }}</p>
                        </div>
                        <div class="info-list">
                            <p class="text-truncate"> 096-566-666
                            <br> <a href="#" title="soengsouy@example.com">HRTech@example.com</a>
                            <br> <a href="#" target="_blank">https://www.example.com</a>
                            </p>
                        </div>
                        <div class="info-list text-center">
                            <a class="app-ends" href="#">Application ends in 2d 7h 6m</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
        
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
                            <input type="hidden" name="id" value="{{ $job_view_detail[0]->id }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Job Title</label>
                                        <input class="form-control" type="text" name="job_title" 
                                               value="{{ $job_view_detail[0]->job_title }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Department</label>
                                        <select class="select form-control" name="department">
                                            <option value="">Select Department</option>
                                            <option value="Web Development" {{ $job_view_detail[0]->department == 'Web Development' ? 'selected' : '' }}>Web Development</option>
                                            <option value="Application Development" {{ $job_view_detail[0]->department == 'Application Development' ? 'selected' : '' }}>Application Development</option>
                                            <option value="IT Management" {{ $job_view_detail[0]->department == 'IT Management' ? 'selected' : '' }}>IT Management</option>
                                            <option value="Accounts Management" {{ $job_view_detail[0]->department == 'Accounts Management' ? 'selected' : '' }}>Accounts Management</option>
                                            <option value="Support Management" {{ $job_view_detail[0]->department == 'Support Management' ? 'selected' : '' }}>Support Management</option>
                                            <option value="Marketing" {{ $job_view_detail[0]->department == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Job Location</label>
                                        <input class="form-control" type="text" name="job_location" 
                                               value="{{ $job_view_detail[0]->job_location }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>No of Vacancies</label>
                                        <input class="form-control" type="text" name="no_of_vacancies" 
                                               value="{{ $job_view_detail[0]->no_of_vacancies }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Experience</label>
                                        <input class="form-control" type="text" name="experience" 
                                               value="{{ $job_view_detail[0]->experience }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Age</label>
                                        <input class="form-control" type="text" name="age" 
                                               value="{{ $job_view_detail[0]->age }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Salary From</label>
                                        <input type="text" class="form-control" name="salary_from" 
                                               value="{{ $job_view_detail[0]->salary_from }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Salary To</label>
                                        <input type="text" class="form-control" name="salary_to" 
                                               value="{{ $job_view_detail[0]->salary_to }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Job Type</label>
                                        <select class="select form-control" name="job_type">
                                            <option value="Full Time" {{ $job_view_detail[0]->job_type == 'Full Time' ? 'selected' : '' }}>Full Time</option>
                                            <option value="Part Time" {{ $job_view_detail[0]->job_type == 'Part Time' ? 'selected' : '' }}>Part Time</option>
                                            <option value="Internship" {{ $job_view_detail[0]->job_type == 'Internship' ? 'selected' : '' }}>Internship</option>
                                            <option value="Temporary" {{ $job_view_detail[0]->job_type == 'Temporary' ? 'selected' : '' }}>Temporary</option>
                                            <option value="Remote" {{ $job_view_detail[0]->job_type == 'Remote' ? 'selected' : '' }}>Remote</option>
                                            <option value="Others" {{ $job_view_detail[0]->job_type == 'Others' ? 'selected' : '' }}>Others</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select class="select form-control" name="status">
                                            <option value="Open" {{ $job_view_detail[0]->status == 'Open' ? 'selected' : '' }}>Open</option>
                                            <option value="Closed" {{ $job_view_detail[0]->status == 'Closed' ? 'selected' : '' }}>Closed</option>
                                            <option value="Cancelled" {{ $job_view_detail[0]->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Start Date</label>
                                        <input type="text" class="form-control datetimepicker" name="start_date" 
                                               value="{{ $job_view_detail[0]->start_date }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Expired Date</label>
                                        <input type="text" class="form-control datetimepicker" name="expired_date" 
                                               value="{{ $job_view_detail[0]->expired_date }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea class="form-control" name="description">{{ $job_view_detail[0]->description }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Edit Job Modal -->
    </div>
    <!-- /Page Wrapper -->
@endsection