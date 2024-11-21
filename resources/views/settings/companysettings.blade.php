<style>
    /* Reduce overall padding and margin */
body {
    padding: 10px;!important
    margin: 0;
}

.form-group {
    margin-bottom: 10px; /* Reduce space between form fields */
}

input, select, button {
    font-size: 14px; /* Smaller font size for compact fields */
    padding: 8px; /* Adjust padding to make fields smaller */
}

h3.page-title {
    font-size: 20px; /* Reduce header font size */
}

.submit-section {
    text-align: right;
}

/* Adjust the container layout for compact view */
.container-fluid {
    padding: 0 10px;
}

/* Ensure form fields fit within smaller viewports */
input.form-control, select.form-control {
    height: auto;
    min-height: 30px;
}

</style>

@extends('layouts.settings')
@section('content')
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-inner slimscroll">
            <div class="sidebar-menu">
                <ul>
                    <li><a href="{{ route('home') }}"><i class="la la-home"></i> <span>Back to Home</span></a></li>
                    <li class="menu-title">Settings</li>
                    <li class="active"><a href="{{ route('company/settings/page') }}"><i class="la la-building"></i><span>Company Settings</span></a></li>
                    <li><a href="{{ route('roles/permissions/page') }}"><i class="la la-key"></i><span>Roles & Permissions</span></a></li>
                    <li><a href="{{ route('change/password') }}"><i class="la la-lock"></i><span>Change Password</span></a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Sidebar -->

    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <!-- Page Header -->
                    <div class="page-header">
                        <div class="row">
                            <div class="col-sm-12">
                                <h3 class="page-title">Company Settings</h3>
                            </div>
                        </div>
                    </div>
                    <!-- /Page Header -->

                    <!-- Flash Message -->
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Company Settings Form -->
                    <form action="{{ route('company/settings/save') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Company Name <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="company_name" value="{{ old('company_name', $company->company_name ?? '') }}" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Contact Person</label>
                                    <input class="form-control" type="text" name="contact_person" value="{{ old('contact_person', $company->contact_person ?? '') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Address</label>
                                    <input class="form-control" type="text" name="address" value="{{ old('address', $company->address ?? '') }}">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-3">
                                <div class="form-group">
                                    <label>Country</label>
                                    <input class="form-control" type="text" name="country" value="{{ old('country', $company->country ?? '') }}">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-3">
                                <div class="form-group">
                                    <label>City</label>
                                    <input class="form-control" type="text" name="city" value="{{ old('city', $company->city ?? '') }}">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-3">
                                <div class="form-group">
                                    <label>State/Province</label>
                                    <input class="form-control" type="text" name="state" value="{{ old('state', $company->state ?? '') }}">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-3">
                                <div class="form-group">
                                    <label>Postal Code</label>
                                    <input class="form-control" type="text" name="postal_code" value="{{ old('postal_code', $company->postal_code ?? '') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input class="form-control" type="email" name="email" value="{{ old('email', $company->email ?? '') }}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input class="form-control" type="text" name="phone_number" value="{{ old('phone_number', $company->phone_number ?? '') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Mobile Number</label>
                                    <input class="form-control" type="text" name="mobile_number" value="{{ old('mobile_number', $company->mobile_number ?? '') }}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Fax</label>
                                    <input class="form-control" type="text" name="fax" value="{{ old('fax', $company->fax ?? '') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Website URL</label>
                                    <input class="form-control" type="url" name="website_url" value="{{ old('website_url', $company->website_url ?? '') }}">
                                </div>
                            </div>
                        </div>
                        <div class="submit-section">
                            <button type="submit" class="btn btn-primary submit-btn">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
    </div>
    <!-- /Page Wrapper -->
@endsection
