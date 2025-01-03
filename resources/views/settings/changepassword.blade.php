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
                    <!-- <li><a href="{{ route('roles/permissions/page') }}"><i class="la la-key"></i><span>Roles & Permissions</span></a></li> -->
                    <li><a href="{{ route('settings/password') }}"><i class="la la-lock"></i><span>Change Password</span></a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Sidebar -->
    
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <!-- Page Header -->
                    <div class="page-header">
                        <div class="row">
                            <div class="col-sm-12">
                                <h3 class="page-title">Change Password</h3>
                            </div>
                        </div>
                    </div>
                    <!-- /Page Header -->
                    <form method="POST" action="{{ route('settings/password') }}">
                        @csrf
                        <div class="form-group">
                            <label>Old password</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror " name="current_password" value="{{ old('current_password') }}" placeholder="Enter Old Password">
                            @error('current_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                             @enderror
                        </div>
                        <div class="form-group">
                            <label>New password</label>
                            <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                                   name="new_password" placeholder="Enter New Password">
                            <div class="password-strength"></div>
                            <small class="form-text text-muted">Password must be at least 8 characters long</small>
                            @error('new_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Confirm password</label>
                            <input type="password" class="form-control @error('new_confirm_password') is-invalid @enderror" name="new_confirm_password" placeholder="Choose Confirm Password">
                            @error('new_confirm_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="submit-section">
                            <button type="submit" class="btn btn-primary submit-btn">Update Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
    </div>
    <!-- /Page Wrapper -->
@endsection

@section('script')
<script>
$(document).ready(function() {
    // Password strength indicator
    $('input[name="new_password"]').keyup(function() {
        var password = $(this).val();
        var strength = 0;
        
        // Check length
        if (password.length >= 8) {
            strength += 1;
        }
        
        // Check for mixed case
        if (password.match(/[a-z]/) && password.match(/[A-Z]/)) {
            strength += 1;
        }
        
        // Check for numbers
        if (password.match(/\d/)) {
            strength += 1;
        }
        
        // Check for special characters
        if (password.match(/[^a-zA-Z\d]/)) {
            strength += 1;
        }
        
        // Update strength indicator
        var strengthBar = $('.password-strength');
        switch(strength) {
            case 0:
                strengthBar.removeClass().addClass('password-strength weak');
                break;
            case 1:
                strengthBar.removeClass().addClass('password-strength fair');
                break;
            case 2:
                strengthBar.removeClass().addClass('password-strength good');
                break;
            case 3:
            case 4:
                strengthBar.removeClass().addClass('password-strength strong');
                break;
        }
    });

    // Form validation
    $('form').submit(function(e) {
        var newPassword = $('input[name="new_password"]').val();
        var confirmPassword = $('input[name="new_confirm_password"]').val();
        
        if (newPassword.length < 8) {
            e.preventDefault();
            toastr.error('Password must be at least 8 characters long');
            return false;
        }
        
        if (newPassword !== confirmPassword) {
            e.preventDefault();
            toastr.error('Passwords do not match');
            return false;
        }
    });
});
</script>
@endsection

@section('styles')
<style>
.password-strength {
    height: 4px;
    margin-top: 5px;
    border-radius: 2px;
    transition: all 0.3s ease;
}
.password-strength.weak { background-color: #ff4d4d; width: 25%; }
.password-strength.fair { background-color: #ffa64d; width: 50%; }
.password-strength.good { background-color: #99cc00; width: 75%; }
.password-strength.strong { background-color: #2eb82e; width: 100%; }
</style>
@endsection