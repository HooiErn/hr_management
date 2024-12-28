@extends('layouts.master')
@section('content')

    <!-- Page Wrapper -->
    <div class="page-wrapper">
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

            <!-- Search Filter -->
            <div class="row filter-row mb-4 d-flex align-items-center">
                <form method="GET" action="{{ route('attendance/page') }}" class="w-100 d-flex" id="attendanceSearchForm">
                    <div class="col-sm-3">  
                        <div class="form-group form-focus">
                            <input type="text" name="employee_name" class="form-control floating" placeholder="Employee Name" value="{{ request('employee_name') }}" id="employeeName">
                            <label class="focus-label">Employee Name</label>
                        </div>
                    </div>
                    <div class="col-sm-3"> 
                        <div class="form-group form-focus select-focus">
                            <select name="month" class="select floating" id="monthSelect"> 
                                <option value="">Select Month</option>
                                @foreach (range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                                @endforeach
                            </select>
                            <label class="focus-label">Select Month</label>
                        </div>
                    </div>
                    <div class="col-sm-3"> 
                        <div class="form-group form-focus select-focus">
                            <select name="year" class="select floating" id="yearSelect"> 
                                <option value="">Select Year</option>
                                @foreach (range(date('Y'), date('Y') - 5) as $y)
                                    <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                            <label class="focus-label">Select Year</label>
                        </div>
                    </div>
                    <div class="col-sm-3">  
                        <button type="submit" class="btn btn-success btn-block"> Search </button>  
                    </div>     
                </form>
            </div>
            <!-- /Search Filter -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table table-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    @for ($day = 1; $day <= 31; $day++)
                                        <th>{{ $day }}</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody id="attendanceTableBody">
                                @if (empty($attendanceData) || count($attendanceData) === 0)
                                    <tr>
                                        <td colspan="32" class="text-center">No Data</td>
                                    </tr>
                                @else
                                    @foreach ($attendanceData as $employeeId => $attendanceRecords)
                                        <tr>
                                            <td>
                                                <h2 class="table-avatar">
                                                    <a class="avatar avatar-xs" href="profile.html"><img alt="" src="{{ URL::to('assets/img/profiles/avatar-09.jpg') }}"></a>
                                                    <a href="profile.html">{{ $attendanceRecords[array_key_first($attendanceRecords)]->employee->name }}</a>
                                                </h2>
                                            </td>
                                            @for ($day = 1; $day <= 31; $day++)
                                                <td>
                                                    @if (isset($attendanceRecords[date($year . '-' . $month . '-' . $day)]))
                                                        <a href="javascript:void(0);" data-toggle="modal" data-target="#attendance_info" data-attendance="{{ json_encode($attendanceRecords[date($year . '-' . $month . '-' . $day)]) }}">
                                                            <i class="fa fa-check text-success"></i>
                                                        </a>
                                                    @else
                                                        <i class="fa fa-close text-danger"></i>
                                                    @endif
                                                </td>
                                            @endfor
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
    </div>
    <!-- /Page Wrapper -->

    <!-- Attendance Modal -->
    <div class="modal fade" id="attendance_info" tabindex="-1" role="dialog" aria-labelledby="attendanceModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="attendanceModalLabel">Attendance Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Punch In:</strong> <span id="punch_in"></span></p>
                    <p><strong>Punch Out:</strong> <span id="punch_out"></span></p>
                    <p><strong>Break Duration:</strong> <span id="break_duration"></span> hrs</p>
                    <p><strong>Overtime:</strong> <span id="overtime"></span> hrs</p>
                </div>
            </div>
        </div>
    </div>

    @section('script')
    <script>
        // Handle the attendance modal data
        $('#attendance_info').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var attendance = button.data('attendance'); // Extract info from data-* attributes

            // Update the modal's content
            var modal = $(this);
            modal.find('#punch_in').text(attendance.punch_in ? new Date(attendance.punch_in).toLocaleString() : 'N/A');
            modal.find('#punch_out').text(attendance.punch_out ? new Date(attendance.punch_out).toLocaleString() : 'N/A');
            modal.find('#break_duration').text(attendance.break_duration || 0);
            modal.find('#overtime').text(attendance.overtime || 0);
        });

        $(document).ready(function() {
            // Auto search on input change
            $('#employeeName, #monthSelect, #yearSelect').on('change keyup', function() {
                performSearch();
            });

            function performSearch() {
                const employeeName = $('#employeeName').val();
                const month = $('#monthSelect').val();
                const year = $('#yearSelect').val();

                $.ajax({
                    url: "{{ route('attendance/search') }}",
                    method: 'GET',
                    data: {
                        employee_name: employeeName,
                        month: month,
                        year: year
                    },
                    success: function(data) {
                        const tableBody = $('#attendanceTableBody');
                        tableBody.empty();

                        if (data.attendanceData.length > 0) {
                            data.attendanceData.forEach((record, index) => {
                                let row = `<tr>
                                    <td>
                                        <h2 class="table-avatar">
                                            <a class="avatar avatar-xs" href="profile.html"><img alt="" src="{{ URL::to('assets/img/profiles/avatar-09.jpg') }}"></a>
                                            <a href="profile.html">${record.employee.name}</a>
                                        </h2>
                                    </td>`;
                                for (let day = 1; day <= 31; day++) {
                                    row += `<td>
                                        ${record.attendance[day] ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-close text-danger"></i>'}
                                    </td>`;
                                }
                                row += `</tr>`;
                                tableBody.append(row);
                            });
                        } else {
                            tableBody.append('<tr><td colspan="32" class="text-center">No Data</td></tr>');
                        }
                    },
                    error: function() {
                        console.error('Error fetching attendance data');
                    }
                });
            }
        });
    </script>
@endsection
@endsection
