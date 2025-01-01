@extends('layouts.master')
@section('content')

    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Past Employees</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('employees/list') }}">Employee List</a></li>
                            <li class="breadcrumb-item active">Past Employees</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Search and Entries Selector -->
            <div class="row filter-row">
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus focused">
                            <input type="text" class="form-control floating" id="search-input">
                        <label class="focus-label">Past Employee Name</label>
                    </div>
                </div>
            </div>

            <!-- Export Buttons -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <a href="{{ route('pastemployees.export.excel') }}" class="btn btn-success">Export Excel</a>
                    <a href="{{ route('pastemployees.export.pdf') }}" class="btn btn-danger">Export PDF</a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table datatable mb-0" id="past-employees-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Department</th>
                                    <th>Role</th>
                                    <th>Email</th>
                                    <th>Phone Number</th>
                                    <th>Status</th>
                                    <th>Resignation Date</th>
                                    <th>Resignation Reason</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Table body will be populated by JavaScript -->
                            </tbody>
                        </table>
                        <!-- Pagination -->
                        <div class="row mt-3">
                            <div class="col-sm-12 col-md-5">
                                <div class="dataTables_info" id="entries-info" role="status" aria-live="polite">
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-7">
                                <div class="dataTables_paginate">
                                    <ul class="pagination justify-content-end">
                                        <!-- Pagination will be populated by JavaScript -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@section('script')
<script>
$(document).ready(function() {
    var currentPage = 1;
    var entriesPerPage = 10;

    // Handle entries limit change
    $('#entries-limit').on('change', function() {
        entriesPerPage = parseInt($(this).val());
        currentPage = 1;
        performSearch();
    });

    // Handle search input with debounce
    var searchTimeout;
    $('#search-input').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            currentPage = 1;
            performSearch();
        }, 300);
    });

    function performSearch() {
        $('#past-employees-table tbody').html('<tr><td colspan="8" class="text-center">Loading...</td></tr>');

        $.ajax({
            url: "{{ route('past/employees/search') }}",
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                search: $('#search-input').val() || '',
                limit: entriesPerPage,
                page: currentPage
            },
            success: function(response) {
                var tbody = $('#past-employees-table tbody');
                tbody.empty();

                if (!response.success || !response.employees.data || response.employees.data.length === 0) {
                    tbody.html('<tr><td colspan="8" class="text-center">No past employees found</td></tr>');
                    updatePagination({ total: 0, per_page: entriesPerPage });
                    updateEntriesInfo({ total: 0, per_page: entriesPerPage });
                    return;
                }

                response.employees.data.forEach(function(employee) {
                    var row = `
                        <tr>
                            <td>${employee.name || ''}</td>
                            <td>${employee.department || ''}</td>
                            <td>${employee.role_name || ''}</td>
                            <td>${employee.email || ''}</td>
                            <td>${employee.phone_number || ''}</td>
                            <td>${employee.status || ''}</td>
                            <td>${formatDate(employee.resignation_date)}</td>
                            <td>${employee.resignation_reason || ''}</td>
                        </tr>
                    `;
                    tbody.append(row);
                });

                updatePagination(response.employees);
                updateEntriesInfo(response.employees);
            },
            error: function(xhr) {
                console.error('Search error:', xhr);
                $('#past-employees-table tbody').html(
                    '<tr><td colspan="8" class="text-center">Error occurred while searching. Please try again.</td></tr>'
                );
            }
        });
    }


    function formatDate(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }

    // Initial load
    performSearch();
});
</script>
@endsection

@endsection 