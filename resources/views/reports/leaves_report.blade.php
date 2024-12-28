@extends('layouts.master')

@section('content')
<div class="content container-fluid">
    <h3 class="page-title">Leaves Report</h3>

    <!-- Export Buttons -->
    <div class="mb-3">
        <a href="{{ route('leaves.export.excel') }}" class="btn btn-success">Export to Excel</a>
        <a href="{{ route('leaves.export.pdf') }}" class="btn btn-danger">Export to PDF</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped custom-table mb-0 datatable">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Leave Type</th>
                    <th>From</th>
                    <th>To</th>
                    <th>No of Days</th>
                    <th>Leave Reason</th>
                    <th class="text-center">Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if($leaves->isEmpty())
                    <tr>
                        <td colspan="8" class="text-center">No leaves found.</td>
                    </tr>
                @else
                    @foreach ($leaves as $leave)
                        <tr>
                            <td>
                                <h2 class="table-avatar">
                                    <a href="#">{{ $leave->employee_name ?? 'No Name' }}<span>{{ $leave->position ?? 'No Position' }}</span></a>
                                </h2>
                            </td>
                            <td>{{ $leave->leave_type ?? 'No Leave Type' }}</td>
                            <td>{{ date('d F, Y', strtotime($leave->from_date)) ?? 'No From Date' }}</td>
                            <td>{{ date('d F, Y', strtotime($leave->to_date)) ?? 'No To Date' }}</td>
                            <td>{{ $leave->day }} Day(s)</td>
                            <td>{{ $leave->leave_reason ?? 'No Reason' }}</td>
                            <td class="text-center">{{ $leave->leave_status ?? 'No Status' }}</td>
                            <td class="text-right">
                                <button class="btn btn-warning leaveEdit" data-id="{{ $leave->id }}" data-leave-type="{{ $leave->leave_type }}" data-from-date="{{ $leave->from_date }}" data-to-date="{{ $leave->to_date }}" data-reason="{{ $leave->leave_reason }}">Edit</button>
                                <form action="{{ route('leaves.delete', $leave->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this leave?');">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection 