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
                        <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_leave" style="background-color:#5a83d2;border:none; "><i class="fa fa-plus"></i> Add Leave</a>
                    </div>
                </div>
            </div>
            <!-- Leave Statistics -->
            <div class="row">
                <div class="col-md-3">
                    <div class="stats-info">
                        <h6>Today Presents</h6>
                        <h4><span>Coming Soon</span></h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-info">
                        <h6>Planned Leaves</h6>
                        <h4><span>Coming Soon</span></h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-info">
                        <h6>Unplanned Leaves</h6>
                        <h4><span>Coming Soon</span></h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-info">
                        <h6>Pending Requests</h6>
                        <h4><span>Coming Soon</span></h4>
                    </div>
                </div>
            </div>
            <!-- /Leave Statistics -->

            <!-- Search Filter -->
            <form id="search-form">
                @csrf
                <div class="row filter-row">
                    <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">  
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating" name="employee_name" id="search-employee">
                            <label class="focus-label"><small>Employee Name/ID</small></label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">  
                        <div class="form-group form-focus select-focus">
                            <select class="select floating" name="leave_type" id="search-leave-type"> 
                                <option value=""> -- Select -- </option>
                                <option value="Casual Leave">Casual Leave</option>
                                <option value="Medical Leave">Medical Leave</option>
                                <option value="Loss of Pay">Loss of Pay</option>
                            </select>
                            <label class="focus-label">Leave Type</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12"> 
                        <div class="form-group form-focus select-focus">
                            <select class="select floating" name="leave_status" id="search-status"> 
                                <option value=""> -- Select -- </option>
                                <option value="paid">Paid Leave</option>
                                <option value="unpaid">Unpaid Leave</option>
                            </select>
                            <label class="focus-label">Leave Status</label>
                        </div>
                    </div>
                </div>
            </form>
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
                                            <td>
                                                <div class="leave-reason">
                                                    <span class="reason-text" data-toggle="modal" data-target="#reasonModal{{ $leave->id }}">
                                                        {{ \Str::limit($leave->leave_reason, 20) }}
                                                    </span>
                                                </div>

                                                <!-- Modal -->
                                                <div class="modal fade" id="reasonModal{{ $leave->id }}" tabindex="-1" role="dialog">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Leave Reason</h5>
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
                                            <td class="text-center">
                                                @if($leave->leave_status === 'paid')
                                                    <span class="badge badge-success">Paid Leave</span>
                                                @else
                                                    <span class="badge badge-warning">Unpaid Leave</span>
                                                @endif
                                            </td>
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
                            
                            <div class="form-group">
                                <label>Leave Status</label>
                                <select class="select" name="leave_status" id="edit_leave_status" required>
                                    <option value="paid">Paid Leave</option>
                                    <option value="unpaid">Unpaid Leave</option>
                                </select>
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

<script>
$(document).ready(function() {
    // Search functionality
    function performSearch() {
        $.ajax({
            url: "{{ route('leaves/search') }}",
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                employee_name: $('#search-employee').val(), // This is actually employee_id now
                leave_type: $('#search-leave-type').val(),
                leave_status: $('#search-status').val()
            },
            success: function(response) {
                var tbody = $('table tbody');
                tbody.empty();
                
                if (response.leaves.length === 0) {
                    tbody.append(`
                        <tr>
                            <td colspan="8" class="text-center">No leaves found.</td>
                        </tr>
                    `);
                } else {
                    response.leaves.forEach(function(leave) {
                        var statusBadge = leave.leave_status === 'paid' 
                            ? '<span class="badge badge-success">Paid Leave</span>'
                            : '<span class="badge badge-warning">Unpaid Leave</span>';

                        var row = `
                            <tr>
                                <td>
                                    <h2 class="table-avatar">
                                        <a href="#">${leave.employee_name} (${leave.user_id})<span>${leave.position}</span></a>
                                    </h2>
                                </td>
                                <td>${leave.leave_type}</td>
                                <td>${formatDate(leave.from_date)}</td>
                                <td>${formatDate(leave.to_date)}</td>
                                <td>${leave.day} Day(s)</td>
                                <td>${leave.leave_reason}</td>
                                <td class="text-center">${statusBadge}</td>
                                <td class="text-right">
                                    <div class="dropdown dropdown-action">
                                        <button class="btn btn-warning btn-sm" onclick="editLeave(${JSON.stringify(leave)})">
                                            <i class="fa fa-pencil m-r-5"></i> Edit
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteLeave(${leave.id})">
                                            <i class="fa fa-trash-o m-r-5"></i> Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;
                        tbody.append(row);
                    });
                }
            },
            error: function(xhr) {
                console.error('Search error:', xhr);
                toastr.error('An error occurred while searching');
            }
        });
    }

    // Helper function to format dates
    function formatDate(dateString) {
        if (!dateString) return 'No Date';
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
    }

    // Trigger search on input change
    $('#search-form input, #search-form select').on('input change', function() {
        performSearch();
    });
});
</script>
<script>
$(document).ready(function() {
    // Add this to your existing form submit handler
    $('#leaveForm').on('submit', function(e) {
        var fromDate = new Date($('#from_date').val());
        var toDate = new Date($('#to_date').val());
        
        if (fromDate > toDate) {
            e.preventDefault();
            alert('Leave From date cannot be later than Leave To date');
            return false;
        }
    });

    // For date inputs, add this change handler
    $('#from_date, #to_date').on('change', function() {
        var fromDate = new Date($('#from_date').val());
        var toDate = new Date($('#to_date').val());
        
        if (fromDate > toDate) {
            alert('Leave From date cannot be later than Leave To date');
            $(this).val(''); // Clear the invalid date
        }
    });
});
</script>
<style>
    .leave-reason {
        position: relative;
    }
    .reason-text.truncated {
        display: inline-block;
    }
    .show-more-btn, .show-less-btn {
        color: #0066cc;
        text-decoration: none;
        margin-left: 5px;
        font-size: 0.9em;
    }
    .show-more-btn:hover, .show-less-btn:hover {
        text-decoration: underline;
    }
    .reason-text {
        cursor: pointer;
        color: inherit;
        text-decoration: none;
    }
    .modal-body {
        font-size: 14px;
        line-height: 1.6;
        max-height: 400px;
        overflow-y: auto;
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
    .modal-content {
        max-width: 100%;
        word-wrap: break-word;
    }
</style>
<script>
$(document).ready(function() {
    // Handle Show More/Less clicks
    $('.show-more-btn').on('click', function() {
        var container = $(this).closest('.leave-reason');
        container.find('.reason-text').text(container.find('.reason-full').text());
        $(this).hide();
        container.find('.show-less-btn').show();
    });

    $('.show-less-btn').on('click', function() {
        var container = $(this).closest('.leave-reason');
        container.find('.reason-text').text(container.find('.reason-text').text().substring(0, 50) + '...');
        $(this).hide();
        container.find('.show-more-btn').show();
    });
});
</script>
<script>
$(document).ready(function() {
    // Click handler for the reason text
    $('.reason-text').on('click', function() {
        var modalId = $(this).data('target');
        $(modalId).modal('show');
    });

    // Handle modal close
    $('.close, [data-dismiss="modal"]').on('click', function() {
        $(this).closest('.modal').modal('hide');
    });
});
</script>
    @endsection
@endsection
