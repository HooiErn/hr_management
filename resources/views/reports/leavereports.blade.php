@extends('layouts.master')
@section('content')
<style>
    .leave-reason {
        max-width: 200px; 
        overflow: hidden; 
        text-overflow: ellipsis; /* Add ellipsis for overflowed text */
        white-space: nowrap; 
    }
</style>
    {{-- message --}}
    {!! Toastr::message() !!}
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <h3 class="page-title">Leave Report</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Leave Report</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table mb-0">
                            <thead>
                                <tr>
                                    <th>Employee Name</th>
                                    <th>Leave Type</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>No of Days</th>
                                    <th class="leave-reason">Reason</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($leaves->isEmpty())
                                    <tr>
                                        <td colspan="6" class="text-center">No leaves found.</td>
                                    </tr>
                                @else
                                    @foreach ($leaves as $leave)
                                        <tr>
                                            <td>{{ $leave->name }}</td>
                                            <td>{{ $leave->leave_type }}</td>
                                            <td>{{ date('d F, Y', strtotime($leave->from_date)) }}</td>
                                            <td>{{ date('d F, Y', strtotime($leave->to_date)) }}</td>
                                            <td>{{ $leave->day }} Day(s)</td>
                                            <td class="leave-reason" data-reason="{{ $leave->leave_reason }}" style="cursor: pointer;">{{ $leave->leave_reason }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).on('click', '.leave-reason', function() {
            var reasonText = $(this).text(); // Get the leave reason text
            $('#fullReasonText').text(reasonText); // Set it in the modal
            $('#fullReasonModal').modal('show'); // Show the modal
        });
    </script>
    <!-- Full Reason Modal -->
    <div id="fullReasonModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Leave Reason</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="fullReasonText"></p>
                </div>
            </div>
        </div>
    </div>
@endsection
