@extends('layouts.master')
@section('content')
<!-- Include Select2 CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<!-- Include Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Leaves <span id="year"></span></h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Leaves</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_leave"><i class="fa fa-plus"></i> Add Leave</a>
                    </div>
                </div>
            </div>
            <!-- Leave Statistics -->
            <div class="row">
                <div class="col-md-3">
                    <div class="stats-info">
                        <h6>Today Presents</h6>
                        <h4>0</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-info">
                        <h6>Planned Leaves</h6>
                        <h4><span>Today</span></h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-info">
                        <h6>Unplanned Leaves</h6>
                        <h4><span>Today</span></h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-info">
                        <h6>Pending Requests</h6>
                        <h4>0</h4>
                    </div>
                </div>
            </div>
            <!-- /Leave Statistics -->

            <!-- Search Filter -->
            <div class="row filter-row">
                <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">  
                    <div class="form-group form-focus">
                        <input type="text" class="form-control floating">
                        <label class="focus-label">Employee Name</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">  
                    <div class="form-group form-focus select-focus">
                        <select class="select floating"> 
                            <option> -- Select -- </option>
                            <option>Casual Leave</option>
                            <option>Medical Leave</option>
                            <option>Loss of Pay</option>
                        </select>
                        <label class="focus-label">Leave Type</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12"> 
                    <div class="form-group form-focus select-focus">
                        <select class="select floating"> 
                            <option> -- Select -- </option>
                            <option> Pending </option>
                            <option> Approved </option>
                            <option> Rejected </option>
                        </select>
                        <label class="focus-label">Leave Status</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">  
                    <div class="form-group form-focus">
                        <div class="cal-icon">
                            <input class="form-control floating datetimepicker" type="text">
                        </div>
                        <label class="focus-label">From</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">  
                    <div class="form-group form-focus">
                        <div class="cal-icon">
                            <input class="form-control floating datetimepicker" type="text">
                        </div>
                        <label class="focus-label">To</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">  
                    <a href="#" class="btn btn-success btn-block"> Search </a>  
                </div>     
            </div>
            <!-- /Search Filter -->

			<!-- /Page Header -->
            {!! Toastr::message() !!}
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table mb-0 datatable">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Leave Type</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>No of Days</th>
                                    <th>Reason</th>
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
                                                <form action="{{ route('form/leaves/edit/delete', $leave->id) }}" method="POST" style="display:inline;">
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
            </div>
        </div>
        <!-- /Page Content -->
       
        <!-- Add Leave Modal -->
        <div id="add_leave" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Leave</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="addLeaveForm" action="{{ route('form/leaves/save') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Employee ID <span class="text-danger">*</span></label>
                                <select class="select2" id="employeeSelect" name="user_id" required>
                                    <option value="">Select Employee</option>
                                    <!-- Options will be populated via AJAX -->
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Leave Type <span class="text-danger">*</span></label>
                                <select class="select" id="leaveType" name="leave_type" required>
                                    <option>Select Leave Type</option>
                                    <option>Casual Leave</option>
                                    <option>Medical Leave</option>
                                    <option>Loss of Pay</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="from_date">From Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="from_date" name="from_date" required>
                            </div>

                            <div class="form-group">
                                <label for="to_date">To Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="to_date" name="to_date" required>
                            </div>
                            <div class="form-group">
                                <input class="form-control" readonly type="hidden" id="numberOfDays" name="number_of_days">
                            </div>
                            <div class="form-group">
                            <label for="leave_reason">Leave Reason <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="leave_reason" name="leave_reason" required></textarea>
                            </div>
                            <div class="form-group">
                                <label>Leave Status <span class="text-danger">*</span></label>
                                <select class="select" name="leave_status" required>
                                    <option value="">Select Leave Status</option>
                                    <option value="paid">Paid Leave</option>
                                    <option value="unpaid">Unpaid Leave</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="hidden" class="form-control" id="remaining_days" name="remaining_days" value="0" readonly>
                            </div>
                                <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Add Leave Modal -->
				
        <!-- Edit Leave Modal -->
        <div id="edit_leave" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Leave</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editLeaveForm" action="{{ route('form/leaves/edit') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" id="edit_leave_id">
                            
                            <div class="form-group">
                                <label>Leave Type</label>
                                <input type="text" class="form-control" name="leave_type" id="edit_leave_type" required>
                            </div>
                            
                            <div class="form-group">
                                <label>From Date</label>
                                <input type="date" class="form-control" name="from_date" id="edit_from_date" required>
                            </div>
                            
                            <div class="form-group">
                                <label>To Date</label>
                                <input type="date" class="form-control" name="to_date" id="edit_to_date" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Leave Reason</label>
                                <textarea class="form-control" name="leave_reason" id="edit_leave_reason" required></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Update Leave</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Edit Leave Modal -->

        <!-- Approve Leave Modal -->
        <div class="modal custom-modal fade" id="approve_leave" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Leave Approve</h3>
                            <p>Are you sure want to approve for this leave?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <div class="row">
                                <div class="col-6">
                                    <a href="javascript:void(0);" class="btn btn-primary continue-btn">Approve</a>
                                </div>
                                <div class="col-6">
                                    <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Decline</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Approve Leave Modal -->
        
        <!-- Delete Leave Modal -->
        <div class="modal custom-modal fade" id="delete_approve" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete Leave</h3>
                            <p>Are you sure want to delete this leave?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <form action="{{ route('form/leaves/edit/delete') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" class="e_id" value="">
                                <div class="row">
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-primary continue-btn submit-btn">Delete</button>
                                    </div>
                                    <div class="col-6">
                                        <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Delete Leave Modal -->

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
                        <p id="fullReasonText"></p> <!-- This will hold the full reason text -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Wrapper -->
    @section('script')
    <script>
    $(document).on('click', '.leave_reason', function() {
        var reasonText = $(this).data('reason'); // Get the leave reason from data attribute
        $('#fullReasonText').text(reasonText); // Set it in the modal
        $('#fullReasonModal').modal('show'); // Show the modal
    });
</script>
    <script>
        document.getElementById("year").innerHTML = new Date().getFullYear();
    </script>
    {{-- update js --}}
    <script>
        $(document).on('click','.leaveUpdate',function()
        {
            var _this = $(this).parents('tr');
            $('#e_id').val(_this.find('.id').text());
            $('#e_number_of_days').val(_this.find('.day').text());
            $('#e_from_date').val(_this.find('.from_date').text());  
            $('#e_to_date').val(_this.find('.to_date').text());  
            $('#e_leave_reason').val(_this.find('.leave_reason').text());

            var leave_type = (_this.find(".leave_type").text());
            var _option = '<option selected value="' + leave_type+ '">' + _this.find('.leave_type').text() + '</option>'
            $( _option).appendTo("#e_leave_type");
        });
    </script>
    {{-- delete model --}}
    <script>
        $(document).on('click','.leaveDelete',function()
        {
            var _this = $(this).parents('tr');
            $('.e_id').val(_this.find('.id').text());
        });
    </script>
    <script>
    $(document).ready(function() {
    // Initialize Select2 with autocomplete functionality
    $('#employeeSelect').select2({
        ajax: {
            url: "{{ route('leaves.employees.search') }}", // Route to fetch employee data
            dataType: 'json',
            delay: 250, // Wait for user to stop typing before sending request
            data: function(params) {
                return {
                    q: params.term // Send the search term as a parameter
                };
            },
            processResults: function(data) {
                return {
                    results: data.map(function(employee) {
                        return {
                            id: employee.employee_id,
                            text: employee.name + ' (' + employee.employee_id + ')'
                        };
                    })
                };
            },
            cache: true
        },
        placeholder: 'Search for an Employee...',
        minimumInputLength: 1 // Minimum number of characters to trigger the search
    });
});
</script>
<!-- get remaining leave days -->
<script>
    document.getElementById('user_id').addEventListener('change', function() {
        const userId = this.value;

        // Make an AJAX call to get the remaining leave days for the selected employee
        if (userId) {
            fetch(`/getRemainingLeaveDays/${userId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('remaining_days').value = data.remaining_days;
                });
        } else {
            document.getElementById('remaining_days').value = 0;
        }
    });
</script>

<script>
$(document).on('click', '.leaveEdit', function() {
    var leaveId = $(this).data('id');
    var leaveType = $(this).data('leave-type');
    var fromDate = $(this).data('from-date');
    var toDate = $(this).data('to-date');
    var reason = $(this).data('reason');

    // Populate the modal fields
    $('#edit_leave_id').val(leaveId);
    $('#edit_leave_type').val(leaveType);
    $('#edit_from_date').val(fromDate);
    $('#edit_to_date').val(toDate);
    $('#edit_leave_reason').val(reason);

    // Show the modal
    $('#edit_leave').modal('show');
});
</script>

    @endsection
@endsection
