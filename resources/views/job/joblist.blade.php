@extends('layouts.job')
@section('content')
    <div class="main-wrapper">
        <!-- Header -->
        <div class="header">
            <!-- Logo -->
            <div class="header-left">
                <a href="{{ route('form/job/list') }}" class="logo">
                    <img src="{{ URL::to('images/logo-circle.png') }}" width="45" height="45" alt="HR logo">
                </a>
            </div>
            <!-- /Logo -->
            <!-- Header Title -->
            <div class="page-title-box float-left">
                <h3>Jobs List</h3>
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
                        <form action="javascript:void(0);">
                            <input class="form-control" type="text" 
                                   placeholder="Search job title..." 
                                   onkeyup="searchJobs(this.value)">
                            <button class="btn" type="submit"><i class="fa fa-search"></i></button>
                        </form>
                    </div>
                </li>
                <!-- /Search -->
            </ul>
            <!-- /Header Menu -->
        </div>
        <!-- /Header -->
        
        <!-- Page Wrapper -->
        <div class="page-wrapper job-wrapper">
            <!-- Page Content -->
            <div class="content container">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="page-title">Get a Job !</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('form/job/list') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Jobs</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->
                <div class="row" id="job-cards-container">
                    @foreach ($job_list as $list)
                    @php
                        $date = $list->created_at;
                        $date = Carbon\Carbon::parse($date);
                        $elapsed =  $date->diffForHumans();
                    @endphp
                    <div class="col-md-6">
                        <a class="job-list" href="{{ url('form/job/view/'.$list->id) }}">
                            <div class="job-list-det">
                                <div class="job-list-desc">
                                    <h3 class="job-list-title">{{ $list->job_title }}</h3>
                                    <h4 class="job-department">{{ $list->department }}</h4>
                                </div>
                                <div class="job-type-info">
                                    <span class="job-types">{{ $list->job_type }}</span>
                                </div>
                            </div>
                            <div class="job-list-footer">
                                <ul>
                                    <li><i class="fa fa-map-signs"></i>{{ $list->job_location }}</li>
                                    <li><i class="fa fa-money"></i>{{ $list->salary_from }}-{{ $list->salary_to }}</li>
                                    <li><i class="fa fa-clock-o"></i>{{ $elapsed }}</li>
                                </ul>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- /Page Wrapper -->
    </div>

    <!-- Add this JavaScript at the bottom of your file -->
    <script>
    let searchTimeout;

    function searchJobs(searchTerm) {
        clearTimeout(searchTimeout);
        
        searchTimeout = setTimeout(() => {
            fetch(`{{ route('form/job/list') }}?search=${searchTerm}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                let html = '';
                if (data.length > 0) {
                    data.forEach(list => {
                        const date = new Date(list.created_at);
                        const elapsed = moment(date).fromNow(); // Make sure to include moment.js

                        html += `
                            <div class="col-md-6">
                                <a class="job-list" href="{{ url('form/job/view/') }}/${list.id}">
                                    <div class="job-list-det">
                                        <div class="job-list-desc">
                                            <h3 class="job-list-title">${list.job_title}</h3>
                                            <h4 class="job-department">${list.department}</h4>
                                        </div>
                                        <div class="job-type-info">
                                            <span class="job-types">${list.job_type}</span>
                                        </div>
                                    </div>
                                    <div class="job-list-footer">
                                        <ul>
                                            <li><i class="fa fa-map-signs"></i>${list.job_location}</li>
                                            <li><i class="fa fa-money"></i>${list.salary_from}-${list.salary_to}</li>
                                            <li><i class="fa fa-clock-o"></i>${elapsed}</li>
                                        </ul>
                                    </div>
                                </a>
                            </div>
                        `;
                    });
                } else {
                    html = '<div class="col-12 text-center"><p>No jobs found matching your search.</p></div>';
                }
                document.getElementById('job-cards-container').innerHTML = html;
            });
        }, 300);

        // If search term is empty, reload all jobs
        if (!searchTerm) {
            location.reload();
        }
    }
    </script>
@endsection
