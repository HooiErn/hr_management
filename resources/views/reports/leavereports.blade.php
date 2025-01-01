@extends('layouts.master')
@section('content')
<style>
.reason-text {
    cursor: pointer;
    color: inherit; /* Use the default text color */
    text-decoration: none; /* Remove underline */
}
.modal-body {
    padding: 20px;
    font-size: 14px;
    line-height: 1.6;
}
.modal-header .btn-close {
    padding: 0.5rem;
    margin: -0.5rem -0.5rem -0.5rem auto;
    background: none;
    border: 0;
    font-size: 1.5rem;
    font-weight: 700;
    opacity: .5;
    cursor: pointer;
}
.modal-header .btn-close:hover {
    opacity: .75;
}
</style>

<div class="page-wrapper">
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Leave Reports</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Leave Reports</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Search Filter -->
        <div class="row filter-row">
            <div class="col-sm-6 col-md-3">
                <div class="form-group form-focus">
                    <input type="text" class="form-control floating" id="search-name">
                    <label class="focus-label">Employee Name</label>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="form-group form-focus select-focus">
                    <select class="form-control floating" id="search-department">
                        <option value="">All Departments</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->department}}">{{ $department->department}}</option>
                        @endforeach
                    </select>
                    <label class="focus-label">Department</label>
                </div>
            </div>
        </div>

        <!-- Export Buttons -->
        <div class="row mb-3">
            <div class="col-md-12">
                <a href="{{ route('leaves.export.excel') }}" class="btn btn-success">Export Excel</a>
                <a href="{{ route('leaves.export.pdf') }}" class="btn btn-danger">Export PDF</a>
            </div>
        </div>

        
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <div class="dataTables_length" id="DataTables_Table_0_length">
                    <label>
                        Show 
                        <select name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="custom-select custom-select-sm form-control form-control-sm">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        entries
                    </label>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped custom-table datatable">
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
                                <td>{{ date('d M Y', strtotime($leave->from_date)) }}</td>
                                <td>{{ date('d M Y', strtotime($leave->to_date)) }}</td>
                                <td>{{ $leave->day }}</td>
                                <td>
                                    <div class="leave-reason">
                                        <span class="reason-text" data-toggle="modal" data-target="#reasonModalReport{{ $leave->id }}_{{ $leave->employee_id }}">
                                            {{ \Str::limit($leave->leave_reason, 20) }}
                                        </span>
                                    </div>

                                    <!-- Modal with unique ID per employee and leave -->
                                    <div class="modal fade" id="reasonModalReport{{ $leave->id }}_{{ $leave->employee_id }}" tabindex="-1" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Leave Reason - {{ $leave->employee->name ?? 'Employee' }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body p-3">
                                                    <div style="white-space: pre-line; word-break: break-word;">{{ trim($leave->leave_reason) }}</div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($leave->leave_status == 'Approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif($leave->leave_status == 'Pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @else
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
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

<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('.datatable').DataTable({
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        buttons: [],
        searching: true,
        ordering: true
    });

    // Search functionality
    $('#search-name').on('keyup', function() {
        table.column(0).search(this.value).draw();
    });

    $('#search-department').on('change', function() {
        table.column(2).search(this.value).draw();
    });

    // Show full reason on hover
    $('td span[title]').tooltip({
        container: 'body',
        placement: 'top'
    });
});
</script>


@endsection
