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
                        <table class="table table-striped custom-table mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Email</th>
                                    <th>Phone Number</th>
                                    <th>Status</th>
                                    <th>Resignation Date</th>
                                    <th>Resignation Reason</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($pastEmployees->isEmpty())
                                    <tr>
                                        <td colspan="7" class="text-center">No past employees found.</td>
                                    </tr>
                                @else
                                    @foreach ($pastEmployees as $employee)
                                        <tr>
                                            <td>{{ $employee->name }}</td>
                                            <td>{{ $employee->role_name }}</td>
                                            <td>{{ $employee->email }}</td>
                                            <td>{{ $employee->phone_number }}</td>
                                            <td>{{ $employee->status }}</td>
                                            <td>{{$employee->resignation_date}}</td>
                                            <td>{{$employee->resignation_reason}}</td>
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

@endsection 