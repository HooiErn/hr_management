@extends('layouts.master')

@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <h3 class="page-title">Timesheet</h3>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Interviewer Name</th>
                            <th>Date</th>
                            <th>Scheduled Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($timesheets as $timesheet)
                            <tr>
                                <td>{{ $timesheet->interviewer->name }}</td>
                                <td>{{ $timesheet->date }}</td>
                                <td>{{ $timesheet->scheduled_time }}</td>
                                <td>{{ $timesheet->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
