@php
    use Carbon\Carbon;
@endphp

@extends('layouts.master')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Attendance</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Attendance</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Search Filter -->
            <div class="row filter-row">
                <div class="col-sm-6 col-md-2">
                    <div class="form-group form-focus">
                        <input type="text" class="form-control floating" id="employeeName">
                        <label class="focus-label">Employee Name</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-2">
                    <div class="form-group form-focus">
                        <input type="text" class="form-control floating" id="searchDate" readonly>
                        <label class="focus-label"><small>Date (coming soon)</small></label>
                    </div>
                </div>
                <!-- <div class="col-sm-6 col-md-2">
                    <div class="form-group form-focus select-focus">
                        <select class="select floating" id="monthSelect" readonly>
                            <option value="">Select Month(coming soon)</option>
                            @foreach (range(1, 12) as $m)
                                <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                </option>
                            @endforeach
                        </select>
                        <label class="focus-label">Month</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-2">
                    <div class="form-group form-focus select-focus">
                        <select class="select floating" id="yearSelect" readonly>
                            <option value="">Select Year</option>
                            @foreach (range(date('Y'), date('Y') - 5) as $y)
                                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                        <label class="focus-label">Year</label>
                    </div>
                </div> -->
            </div>


            <!-- Table -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table mb-0">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Date</th>
                                    <th>Punch In</th>
                                    <th>Punch Out</th>
                                    <th>Break Duration</th>
                                    <th>Production</th>
                                    <th>Overtime</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="attendanceTableBody">
                                @foreach($employees as $employee)
                                    @php
                                        $searchDate = request('date') ? Carbon::parse(request('date'))->format('Y-m-d') : date('Y-m-d');
                                        $attendance = $attendanceData[$employee->id][$searchDate] ?? null;
                                        
                                        // For debugging
                                        \Log::info("Looking for employee {$employee->id} on date {$searchDate}");
                                    @endphp
                                    <tr class="attendance-row">
                                        <td>
                                            <h2 class="table-avatar">
                                                <a class="avatar avatar-xs" href="javascript:void(0);">
                                                    <img src="{{ asset('assets/img/profiles/avatar-09.jpg') }}" alt="">
                                                </a>
                                                <a href="javascript:void(0);" class="employee-name">{{ $employee->name }}</a>
                                            </h2>
                                        </td>
                                        <td class="attendance-date">{{ Carbon::parse($searchDate)->format('d M Y') }}</td>
                                        <td>{{ $attendance['punch_in'] ?? '--' }}</td>
                                        <td>{{ $attendance['punch_out'] ?? '--' }}</td>
                                        <td>
                                            @if(isset($attendance['break_duration']))
                                                {{ floor($attendance['break_duration']) }}h 
                                                {{ ($attendance['break_duration'] * 60) % 60 }}m
                                            @else
                                                --
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($attendance['production']))
                                                {{ floor($attendance['production']) }}h 
                                                {{ ($attendance['production'] * 60) % 60 }}m
                                            @else
                                                --
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($attendance['overtime']) && $attendance['overtime'] > 0)
                                                {{ floor($attendance['overtime']) }}h 
                                                {{ ($attendance['overtime'] * 60) % 60 }}m
                                            @else
                                                0h 0m
                                            @endif
                                        </td>
                                        <td class="attendance-status">
                                            @if($attendance)
                                                @if($attendance['punch_out'] !== '--')
                                                    <span class="badge bg-success">Complete</span>
                                                @else
                                                    <span class="badge bg-warning">Ongoing</span>
                                                @endif
                                            @else
                                                <span class="badge bg-danger">Absent</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@section('script')
<script>
$(document).ready(function() {
    // Search functionality
    function filterTable() {
        var nameFilter = $('#employeeName').val().toLowerCase();
        var dateFilter = $('#searchDate').val();
        var monthFilter = $('#monthSelect').val();
        var yearFilter = $('#yearSelect').val();

        let totalOvertimeMinutes = 0;

        $('.attendance-row').each(function() {
            var row = $(this);
            var employeeName = row.find('.employee-name').text().toLowerCase();
            var attendanceDate = row.find('.attendance-date').text();

            // Parse the date for comparison
            var rowDate = new Date(attendanceDate);
            var showRow = true;

            // Check employee name
            if (nameFilter && !employeeName.includes(nameFilter)) {
                showRow = false;
            }

            // Check date if selected
            if (dateFilter) {
                var searchDate = new Date(dateFilter);
                if (rowDate.toDateString() !== searchDate.toDateString()) {
                    showRow = false;
                }
            }

            // Check month if selected
            if (monthFilter) {
                if ((rowDate.getMonth() + 1).toString() !== monthFilter) {
                    showRow = false;
                }
            }

            // Check year if selected
            if (yearFilter) {
                if (rowDate.getFullYear().toString() !== yearFilter) {
                    showRow = false;
                }
            }

            row.toggle(showRow);

            // If row is visible, add its overtime to total
            if (showRow) {
                let overtimeText = row.find('td:eq(6)').text().trim(); // Assuming overtime is in 7th column
                if (overtimeText !== '--') {
                    let hours = parseInt(overtimeText.match(/(\d+)h/)[1] || 0);
                    let minutes = parseInt(overtimeText.match(/(\d+)m/)[1] || 0);
                    totalOvertimeMinutes += (hours * 60) + minutes;
                }
            }
        });

        // Update total overtime display
        updateTotalOvertime(totalOvertimeMinutes);
    }

    // Function to update total overtime display
    function updateTotalOvertime(totalMinutes) {
        let hours = Math.floor(totalMinutes / 60);
        let minutes = totalMinutes % 60;
        $('#totalOvertime').text(`${hours}h ${minutes}m`);
    }

    // Attach event listeners
    $('#employeeName').on('keyup', filterTable);
    $('#searchDate').on('change', filterTable);
    $('#monthSelect').on('change', filterTable);
    $('#yearSelect').on('change', filterTable);

    // Reset functionality
    $('#resetBtn').on('click', function() {
        $('#employeeName').val('');
        $('#searchDate').val('');
        $('#monthSelect').val('');
        $('#yearSelect').val('');
        $('.attendance-row').show();
        filterTable(); // Recalculate total overtime
    });

    // Initial calculation
    filterTable();
});
</script>
@endsection
@endsection
