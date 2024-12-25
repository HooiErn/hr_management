<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
	<title>Dashboard - HRTech</title>
	<!-- Favicon -->
	<link rel="shortcut icon" type="image/x-icon" href="{{URL::asset('/images/logo-circle.png')}}">
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="{{ URL::to('assets/css/bootstrap.min.css') }}">
	<!-- Include Bootstrap 4 or 5 and DateTimePicker dependencies -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempus-dominus/5.39.0/css/tempus-dominus.min.css" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/tempus-dominus/5.39.0/js/tempus-dominus.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">

	<!-- Fontawesome CSS -->
	<link rel="stylesheet" href="{{ URL::to('assets/css/font-awesome.min.css') }}">
	<!-- Lineawesome CSS -->
	<link rel="stylesheet" href="{{ URL::to('assets/css/line-awesome.min.css') }}">
	<!-- Datatable CSS -->
	<link rel="stylesheet" href="{{ URL::to('assets/css/dataTables.bootstrap4.min.css') }}">
	<!-- Select2 CSS -->
	<link rel="stylesheet" href="{{ URL::to('assets/css/select2.min.css') }}">
	<!-- Datetimepicker CSS -->
	<link rel="stylesheet" href="{{ URL::to('assets/css/bootstrap-datetimepicker.min.css') }}">
	<!-- Chart CSS -->
	<link rel="stylesheet" href="{{ URL::to('ssets/plugins/morris/morris.css') }}">
	<!-- Main CSS -->
	<link rel="stylesheet" href="{{ URL::to('assets/css/style.css') }}">
	{{-- message toastr --}}
	<link rel="stylesheet" href="{{ URL::to('assets/css/toastr.min.css') }}">
	<script src="{{ URL::to('assets/js/toastr_jquery.min.js') }}"></script>
	<script src="{{ URL::to('assets/js/toastr.min.js') }}"></script>
	<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.css' rel='stylesheet' />
</head>

<body>
	<style>    
		.invalid-feedback{
			font-size: 14px;
		}
		.fc-event {
			cursor: pointer;
			padding: 2px 5px;
		}
		
		.holiday-event {
			border-radius: 3px;
		}
		
		.leave-event {
			border-radius: 3px;
		}
		
		#calendar {
			max-width: 1100px;
			margin: 0 auto;
			background: white;
			padding: 20px;
		}
	</style>
	<!-- toastr -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
		<script src="http://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
		<script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
		
	<!-- Audio file
    <audio id="notificationSound" src="{{ asset('audio/notification.mp3') }}" preload="auto"></audio>
	 -->
	<!-- Main Wrapper -->
	<div class="main-wrapper">
		<!-- Loader -->
		<div id="loader-wrapper">
			<div id="loader">
				<div class="loader-ellips">
				  <span class="loader-ellips__dot"></span>
				  <span class="loader-ellips__dot"></span>
				  <span class="loader-ellips__dot"></span>
				  <span class="loader-ellips__dot"></span>
				</div>
			</div>
		</div>
		<!-- /Loader -->

		<!-- Header -->
		<div class="header">
		<!-- Mobile Toggle Button -->
		<a id="toggle_btn" href="javascript:void(0);" class="toggle-btn">
			<span class="bar-icon">
				<span></span>
				<span></span>
				<span></span>
			</span>
		</a>
		<h2 style="margin: 30px; float: left;color:white; font-family:sans-serif;">
			HRTech
		</h2>
		<!-- Mobile Menu Button -->
		<a id="mobile_btn" class="mobile_btn" href="#sidebar"><i class="fa fa-bars"></i></a>

		<!-- Header Menu -->
		<ul class="nav user-menu">
			<!-- Search -->
			<!-- <li class="nav-item" style="margin-right:20px;">
				<div class="top-nav-search" style="margin-left:20px">
					<a href="javascript:void(0);" class="responsive-search"><i class="fa fa-search"></i></a>
					<form action="search.html">
						<input class="form-control" type="text" placeholder="Search here">
						<button class="btn" type="submit"><i class="fa fa-search"></i></button>
					</form>
				</div>
			</li> -->
			<!-- /Search -->

			<!-- Language Dropdown -->
			<li class="nav-item dropdown has-arrow flag-nav">
				<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button">
					<img src="{{ URL::to('assets/img/flags/us.png') }}" alt="" height="20"> <span>English</span>
				</a>
				<div class="dropdown-menu dropdown-menu-right">
					<a href="javascript:void(0);" class="dropdown-item">
						<img src="{{ URL::to('assets/img/flags/us.png') }}" alt="" height="16"> English
					</a>
				</div>
			</li>
			<!-- /Language Dropdown -->

			<!-- Notifications -->
			<li class="nav-item dropdown">
				<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
					<i class="fa fa-bell-o"></i>
					<span class="badge badge-pill"></span> 
				</a>
				<div class="dropdown-menu notifications">
					<div class="topnav-dropdown-header">
						<span class="notification-title">Notifications</span> 
						<a href="javascript:void(0)" class="clear-noti">Clear All</a> 
					</div>
					<div class="noti-content">
						<!-- Notification list here -->
					</div>
					<div class="topnav-dropdown-footer"><a href="activities.html">View all Notifications</a></div>
				</div>
			</li>
			<!-- /Notifications -->

			<!-- Messages -->
			<li class="nav-item dropdown">
				<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
					<i class="fa fa-comment-o"></i> <span class="badge badge-pill"></span>
				</a>
				<div class="dropdown-menu notifications">
					<div class="topnav-dropdown-header">
						<span class="notification-title">Messages</span> 
						<a href="javascript:void(0)" class="clear-noti">Clear All</a>
					</div>
					<div class="noti-content">
						<!-- Messages list here -->
					</div>
					<div class="topnav-dropdown-footer"><a href="chat.html">View all Messages</a></div>
				</div>
			</li>
			<!-- /Messages -->

			<!-- User Dropdown -->
			<li class="nav-item dropdown has-arrow main-drop">
			<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
				<span class="user-img">
					<img src="{{ URL::to('/assets/images/avatar/'. Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}">
					<span class="status online"></span>
				</span>
				<span>{{ Session::get('name') }}</span>
			</a>
				<div class="dropdown-menu">
					<a class="dropdown-item" href="{{ route('profile_user') }}">My Profile</a>
					<a class="dropdown-item" href="{{ route('company/settings/page') }}">Settings</a>
					<a class="dropdown-item" href="{{ route('auth/logout') }}">Logout</a>
				</div>
			</li>
			<!-- /User Dropdown -->
		</ul>
		<!-- /Header Menu -->

		<!-- Mobile Menu -->
		<div class="dropdown mobile-user-menu">
			<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
				<i class="fa fa-ellipsis-v"></i>
			</a>
			<div class="dropdown-menu dropdown-menu-right">
				<a class="dropdown-item" href="{{ route('profile_user') }}">My Profile</a>
				<a class="dropdown-item" href="{{ route('company/settings/page') }}">Settings</a>
				<a class="dropdown-item" href="{{ route('auth/logout') }}">Logout</a>
			</div>
		</div>
		<!-- /Mobile Menu -->
	</div>
		<!-- /Header -->
		<!-- Sidebar -->
		@include('sidebar.sidebar')
		<!-- /Sidebar -->
		<!-- Page Wrapper -->
		@yield('content')
		<!-- /Page Wrapper -->
	</div>
	<!-- /Main Wrapper -->

	<!-- jQuery -->
	<script src="{{ URL::to('assets/js/jquery-3.5.1.min.js') }}"></script>
	<!-- Bootstrap Core JS -->
	<script src="{{ URL::to('assets/js/popper.min.js') }}"></script>
	<script src="{{ URL::to('assets/js/bootstrap.min.js') }}"></script>
	<!-- Chart JS -->
	<script src="{{ URL::to('assets/plugins/morris/morris.min.js') }}"></script>
	<script src="{{ URL::to('assets/plugins/raphael/raphael.min.js') }}"></script>
	<script src="{{ URL::to('assets/js/chart.js') }}"></script>
	<script src="{{ URL::to('assets/js/Chart.min.js') }}"></script>
	<script src="{{ URL::to('assets/js/line-chart.js') }}"></script>	
	<!-- Slimscroll JS -->
	<script src="{{ URL::to('assets/js/jquery.slimscroll.min.js') }}"></script>
	<!-- Select2 JS -->
	<script src="{{ URL::to('assets/js/select2.min.js') }}"></script>
	<!-- Datetimepicker JS -->
	<script src="{{ URL::to('assets/js/moment.min.js') }}"></script>
	<script src="{{ URL::to('assets/js/bootstrap-datetimepicker.min.js') }}"></script>
	<!-- Datatable JS -->
	<script src="{{ URL::to('assets/js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ URL::to('assets/js/dataTables.bootstrap4.min.js') }}"></script>
	<!-- Multiselect JS -->
	<script src="{{ URL::to('assets/js/multiselect.min.js') }}"></script>		
	<!-- Custom JS -->
	<script src="{{ URL::to('assets/js/app.js') }}"></script>
	@yield('script')
</body>
</html>