@extends('layouts.master')

@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Leave Reports</h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Employee Name</th>
                                <th>Position</th>
                                <th>Department</th>
                                <th>Leave Type</th>
                                <th>From Date</th>
                                <th>To Date</th>
                                <th>Days</th>
                                <th>Leave Reason</th>
                                <th>Status</th>
                                <th>Remaining Days</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($leaves as $leave)
                            <tr>
                                <td>{{ $leave->employee_name }}</td>
                                <td>{{ $leave->position }}</td>
                                <td>{{ $leave->department }}</td>
                                <td>{{ $leave->leave_type }}</td>
                                <td>{{ $leave->from_date }}</td>
                                <td>{{ $leave->to_date }}</td>
                                <td>{{ $leave->day }}</td>
                                <td>{{ $leave->leave_reason }}</td>
                                <td>{{ $leave->leave_status }}</td>
                                <td>{{ $leave->remaining_days }}</td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
