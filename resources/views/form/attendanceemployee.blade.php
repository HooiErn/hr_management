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
                                <p>
                                    @if($attendances->isNotEmpty() && $attendances->last()->punch_in)
                                        {{ \Carbon\Carbon::parse($attendances->last()->punch_in)->format('D, d M Y h:i A') }}
                                    @else
                                        Not punched in yet
                                    @endif
                                </p>
                            </div>
                            <div class="punch-info">
                                <div class="punch-hours">
                                    @php
                                        $totalProduction = 0;
                                        foreach($attendances as $attendance) {
                                            if($attendance->punch_in && $attendance->punch_out) {
                                                $totalProduction += $attendance->production;
                                            } elseif($attendance->punch_in) {
                                                // For ongoing session, calculate current duration
                                                $totalProduction += Carbon\Carbon::parse($attendance->punch_in)->diffInMinutes(now());
                                            }
                                        }
                                        
                                        $hours = floor($totalProduction / 60);
                                        $minutes = $totalProduction % 60;
                                    @endphp
                                    
                                    @if($hours > 0)
                                        {{ $hours }}h {{ $minutes }}m
                                    @else
                                        {{ $minutes }}m
                                    @endif
                                </div>
                            </div>
                            <div class="punch-btn-section">
                                @if($attendances->isNotEmpty() && $attendances->last()->punch_in && !$attendances->last()->punch_out)
                                    <button type="button" class="btn btn-danger punch-btn" id="punchOutBtn">Punch Out</button>
                                @else
                                    <button type="button" class="btn btn-primary punch-btn" id="punchInBtn">Punch In</button>
                                @endif
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
                                    <p style="display: flex; align-items: center;">
                                        Today <strong style="margin-left: 5px;">
                                        @php
                                            $todayHours = 0;
                                            $todayMinutes = 0;
                                            
                                            if ($attendances->isNotEmpty()) {
                                                $attendance = $attendances->first();
                                                if ($attendance->punch_in && $attendance->punch_out) {
                                                    $punchIn = \Carbon\Carbon::parse($attendance->punch_in);
                                                    $punchOut = \Carbon\Carbon::parse($attendance->punch_out);
                                                    $diffInMinutes = $punchOut->diffInMinutes($punchIn);
                                                    $todayHours = floor($diffInMinutes / 60);
                                                    $todayMinutes = $diffInMinutes % 60;
                                                }
                                            }
                                        @endphp
                                        {{ $todayHours }} hrs {{ $todayMinutes }} mins
                                    </strong>
                                        <span style="margin-left: 5px;">/ 8 hrs</span>
                                    </p>
                                    <div class="progress">
                                    <div class="progress-bar bg-primary" role="progressbar" 
                                        style="width: {{ ($todayHours + ($todayMinutes / 60)) / 8 * 100 }}%" 
                                        aria-valuenow="{{ ($todayHours + ($todayMinutes / 60)) / 8 * 100 }}" 
                                        aria-valuemin="0" 
                                        aria-valuemax="100">
                                    </div>
                                </div>
                                </div>
                                <div class="stats-info">
                                    <p>This Week <strong><small>coming soon/ 40 hrs</small></strong></p>
                                    <div class="progress">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 31%" aria-valuenow="31" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="stats-info">
                                    <p>This Month <strong><small>coming soon/ 160 hrs</small></strong></p>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 62%" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="stats-info">
                                <p>Remaining <strong><small>coming soon/ 160 hrs</small></strong></p>
                                    <div class="progress">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 62%" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="stats-info">
                                    <p>Overtime <strong>{{ $attendances->isNotEmpty() ? $attendances->first()->overtime . ' mins' : '0 mins' }}</strong></p>
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
                                    <th>Wifi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendances as $index => $attendance)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d M Y') }}</td>
                                    <td>{{ $attendance->punch_in ? \Carbon\Carbon::parse($attendance->punch_in)->format('h:i A') : 'N/A' }}</td>
                                    <td>{{ $attendance->punch_out ? \Carbon\Carbon::parse($attendance->punch_out)->format('h:i A') : 'N/A' }}</td>
                                    <td>{{ formatMinutes($attendance->production) }}</td>
                                    <td>{{ $attendance->location }}</td>
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
        $(document).ready(function() {
            let isPunchedIn = false;
            const employeeId = {{ $employeeId ?? 'null' }};

            // Function to get WiFi information
            async function getWiFiInfo() {
                try {
                    // Try to get network connection type
                    if ('connection' in navigator) {
                        const connection = navigator.connection;
                        if (connection) {
                            return `${connection.type || 'unknown'}_${connection.effectiveType || 'unknown'}`;
                        }
                    }
                    
                    return 'WiFi_unknown';
                } catch (error) {
                    console.error('Error getting WiFi info:', error);
                    return 'Unknown';
                }
            }

            // Check initial punch in status
            checkPunchInStatus();

            // Add click handlers for punch buttons
            $('#punchInBtn').click(async function() {
                const wifiName = await getWiFiInfo();
                punchIn(wifiName);
            });

            $('#punchOutBtn').click(function() {
                punchOut();
            });

            function punchIn(wifiName) {
                // Log the WiFi name for debugging
                console.log('WiFi Name:', wifiName);
                
                // Check if we have a valid employee ID
                if (!employeeId || employeeId === 'null') {
                    alert('Invalid employee ID. Please contact administrator.');
                    return;
                }
                
                $.ajax({
                    url: '{{ route("attendance.punchIn") }}',
                    type: 'POST',
                    data: {
                        employee_id: employeeId,
                        wifi_name: wifiName || 'Unknown',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            console.log('Punch in response:', response);
                            isPunchedIn = true;
                            updateUI();
                            location.reload();
                        } else {
                            alert(response.message || 'Error punching in. Please try again.');
                        }
                    },
                    error: function(xhr) {
                        console.error('Punch in error:', xhr);
                        let errorMessage = 'Error punching in. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        alert(errorMessage);
                    }
                });
            }

            function punchOut() {
                if (!employeeId || employeeId === 'null') {
                    alert('Invalid employee ID. Please contact administrator.');
                    return;
                }

                $.ajax({
                    url: '{{ route("attendance.punchOut") }}',
                    type: 'POST',
                    data: {
                        employee_id: employeeId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            console.log('Punch out response:', response);
                            isPunchedIn = false;
                            updateUI();
                            location.reload();
                        } else {
                            alert(response.message || 'Error punching out. Please try again.');
                        }
                    },
                    error: function(xhr) {
                        console.error('Punch out error:', xhr);
                        let errorMessage = 'Error punching out. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        alert(errorMessage);
                    }
                });
            }

            function checkPunchInStatus() {
                $.ajax({
                    url: '{{ route("attendance.checkToday") }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        employee_id: employeeId
                    },
                    success: function(data) {
                        isPunchedIn = data.hasPunchedIn;
                        updateUI();
                    }
                });
            }

            function updateUI() {
                if (isPunchedIn) {
                    $('#punchInBtn').hide();
                    $('#punchOutBtn').show();
                } else {
                    $('#punchInBtn').show();
                    $('#punchOutBtn').hide();
                }
            }

            @php
                function formatMinutes($minutesString) {
                    if (empty($minutesString)) return '0 hours 0 mins';
                    
                    $minutes = (int)$minutesString;
                    $hours = floor($minutes / 60);
                    $remainingMinutes = $minutes % 60;
                    
                    if ($hours == 0) {
                        return "0 hours {$remainingMinutes} mins";
                    } else {
                        return "{$hours} hours {$remainingMinutes} mins";
                    }
                }

                function formatDecimalHours($decimal) {
                    if (empty($decimal)) return '0 hours 0 mins';
                    
                    $hours = floor($decimal);
                    $minutes = round(($decimal - $hours) * 60);
                    
                    if ($hours == 0) {
                        return "0 hours {$minutes} mins";
                    } else {
                        return "{$hours} hours {$minutes} mins";
                    }
                }
            @endphp
        });
    </script>
@endsection
@endsection
