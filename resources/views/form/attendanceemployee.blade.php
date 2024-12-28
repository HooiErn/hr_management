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
                        <h3 class="page-title">Attendance</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Attendance</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <div class="col-md-4">
                    <div class="card punch-status">
                        <div class="card-body">
                            <h5 class="card-title">Timesheet <small class="text-muted">{{ Carbon\Carbon::today()->format('d M Y') }}</small></h5>
                            <div class="punch-det">
                                <h6>Punch In at</h6>
                                <p>{{ $attendances->isNotEmpty() && $attendances->first()->punch_in ? \Carbon\Carbon::parse($attendances->first()->punch_in)->format('D, d M Y h:i A') : 'N/A' }}</p>
                            </div>
                            <div class="punch-info">
                                <div class="punch-hours">
                                    <span>{{ $attendances->isNotEmpty() ? $attendances->first()->overtime . ' hrs' : '0 hrs' }}</span>
                                </div>
                            </div>
                            <div class="punch-btn-section">
                                <button type="button" class="btn btn-primary punch-btn" id="punchInBtn">Punch In</button>
                                <button type="button" class="btn btn-danger punch-btn" id="punchOutBtn" style="display: none;">Punch Out</button>
                            </div>
                            <div class="statistics">
                                <div class="row">
                                    <div class="col-md-6 col-6 text-center">
                                        <div class="stats-box">
                                            <p>Break</p>
                                            <h6>{{ $attendances->isNotEmpty() ? $attendances->first()->break_duration . ' hrs' : '0 hrs' }}</h6>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-6 text-center">
                                        <div class="stats-box">
                                            <p>Overtime</p>
                                            <h6>{{ $attendances->isNotEmpty() ? $attendances->first()->overtime . ' hrs' : '0 hrs' }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card att-statistics">
                        <div class="card-body">
                            <h5 class="card-title">Statistics</h5>
                            <div class="stats-list">
                                <div class="stats-info">
                                    <p>Today <strong>{{ $attendances->isNotEmpty() ? $attendances->first()->punch_in->diffInHours($attendances->first()->punch_out) . ' <small>/ 8 hrs</small>' : '0 <small>/ 8 hrs</small>' }}</strong></p>
                                    <div class="progress">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $attendances->isNotEmpty() ? ($attendances->first()->punch_in->diffInHours($attendances->first()->punch_out) / 8) * 100 : 0 }}%" aria-valuenow="{{ $attendances->isNotEmpty() ? ($attendances->first()->punch_in->diffInHours($attendances->first()->punch_out) / 8) * 100 : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="stats-info">
                                    <p>This Week <strong>28 <small>/ 40 hrs</small></strong></p>
                                    <div class="progress">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 31%" aria-valuenow="31" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="stats-info">
                                    <p>This Month <strong>90 <small>/ 160 hrs</small></strong></p>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 62%" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="stats-info">
                                    <p>Remaining <strong>90 <small>/ 160 hrs</small></strong></p>
                                    <div class="progress">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 62%" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="stats-info">
                                    <p>Overtime <strong>4</strong></p>
                                    <div class="progress">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 22%" aria-valuenow="22" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card recent-activity">
                        <div class="card-body">
                            <h5 class="card-title">Today Activity</h5>
                            <ul class="res-activity-list">
                                @foreach($attendances as $attendance)
                                    @if($attendance->punch_in)
                                        <li>
                                            <p class="mb-0">Punch In at</p>
                                            <p class="res-activity-time">
                                                <i class="fa fa-clock-o"></i>
                                                {{ \Carbon\Carbon::parse($attendance->punch_in)->format('h:i A') }}.
                                            </p>
                                        </li>
                                    @endif
                                    @if($attendance->punch_out)
                                        <li>
                                            <p class="mb-0">Punch Out at</p>
                                            <p class="res-activity-time">
                                                <i class="fa fa-clock-o"></i>
                                                {{ \Carbon\Carbon::parse($attendance->punch_out)->format('h:i A') }}.
                                            </p>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search Filter -->
            <div class="row filter-row">
                <div class="col-sm-3">  
                    <div class="form-group form-focus">
                        <div class="cal-icon">
                            <input type="text" class="form-control floating datetimepicker">
                        </div>
                        <label class="focus-label">Date</label>
                    </div>
                </div>
                <div class="col-sm-3"> 
                    <div class="form-group form-focus select-focus">
                        <select class="select floating"> 
                            <option>-</option>
                            <option>Jan</option>
                            <option>Feb</option>
                            <option>Mar</option>
                            <option>Apr</option>
                            <option>May</option>
                            <option>Jun</option>
                            <option>Jul</option>
                            <option>Aug</option>
                            <option>Sep</option>
                            <option>Oct</option>
                            <option>Nov</option>
                            <option>Dec</option>
                        </select>
                        <label class="focus-label">Select Month</label>
                    </div>
                </div>
                <div class="col-sm-3"> 
                    <div class="form-group form-focus select-focus">
                        <select class="select floating"> 
                            <option>-</option>
                            <option>2019</option>
                            <option>2018</option>
                            <option>2017</option>
                            <option>2016</option>
                            <option>2015</option>
                        </select>
                        <label class="focus-label">Select Year</label>
                    </div>
                </div>
                <div class="col-sm-3">  
                    <a href="#" class="btn btn-success btn-block"> Search </a>  
                </div>     
            </div>
            <!-- /Search Filter -->
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Date</th>
                                    <th>Punch In</th>
                                    <th>Punch Out</th>
                                    <th>Production</th>
                                    <th>Break</th>
                                    <th>Overtime</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendances as $index => $attendance)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d M Y') }}</td>
                                    <td>{{ $attendance->punch_in ? \Carbon\Carbon::parse($attendance->punch_in)->format('h:i A') : 'N/A' }}</td>
                                    <td>{{ $attendance->punch_out ? \Carbon\Carbon::parse($attendance->punch_out)->format('h:i A') : 'N/A' }}</td>
                                    <td>{{ $attendance->punch_in && $attendance->punch_out ? $attendance->punch_in->diffInHours($attendance->punch_out) . ' hrs' : '0 hrs' }}</td>
                                    <td>{{ $attendance->break_duration }} hrs</td>
                                    <td>{{ $attendance->overtime }} hrs</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
    </div>
    <!-- /Page Wrapper -->
    @section('script')
    <script>
        const employeeId = {{ auth()->user()->id }}; // Get the authenticated user's ID

        // Check if the user has already punched in today
        fetch('{{ route("attendance.checkToday") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ employee_id: employeeId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.hasPunchedIn) {
                document.getElementById('punchInBtn').style.display = 'none';
                document.getElementById('punchOutBtn').style.display = 'block';
            } else {
                document.getElementById('punchInBtn').style.display = 'block';
                document.getElementById('punchOutBtn').style.display = 'none';
            }
        })
        .catch(error => console.error('Error:', error));

        document.getElementById('punchInBtn').addEventListener('click', function() {
            fetch('{{ route("attendance.punchIn") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    employee_id: employeeId
                })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                // Update UI
                document.getElementById('punchInBtn').style.display = 'none';
                document.getElementById('punchOutBtn').style.display = 'block';
            })
            .catch(error => console.error('Error:', error));
        });

        document.getElementById('punchOutBtn').addEventListener('click', function() {
            fetch('{{ route("attendance.punchOut") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    employee_id: employeeId
                })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                // Update UI
                document.getElementById('punchOutBtn').style.display = 'none';
                document.getElementById('punchInBtn').style.display = 'block';
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
@endsection
@endsection
