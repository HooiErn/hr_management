<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
		<meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Job - HRTech Company</title>
		<!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="{{URL::asset('/images/logo-circle.png')}}">
		<!-- Bootstrap CSS -->
        <link rel="stylesheet" href="{{ URL::to('assets/css/bootstrap.min.css') }}">
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
		<!-- Main CSS -->
        <link rel="stylesheet" href="{{ URL::to('assets/css/style.css') }}">
		{{-- message toastr --}}
        <link rel="stylesheet" href="{{ URL::to('assets/css/toastr.min.css') }}">
        <script src="{{ URL::to('assets/js/toastr_jquery.min.js') }}"></script>
        <script src="{{ URL::to('assets/js/toastr.min.js') }}"></script>		
    </head>
    <body>
		<style>
			.floating-chatbot {
			position: fixed;
			bottom: 20px;
			right: 20px;
			background: #007bff;
			color: white;
			border-radius: 50%;
			width: 90px;
			height: 80px;
			display: flex;
			justify-content: center;
			align-items: center;
			z-index: 1000;
			cursor: pointer;
		}

		.floating-chatbot i {
			font-size: 32px;
		}
		.chat-message {
			display: flex; 
			align-items: flex-start; 
		}
		.custom-chatbox-container {
			display: none;
			position: fixed;
			bottom: 80px;
			right: 20px;
			width: 320px;
			height: 400px;
			background: white;
			border-radius: 10px;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
			z-index: 1000;
			padding:0;
		}

		.custom-chatbox {
			border: 1px solid #007bff;
			border-radius: 5px;
			overflow: hidden;
			width: 100%;
			height: 100%;
		}

		.custom-chatbox__support {
			display: flex;
			flex-direction: column;
			height: 100%;
			background-color: #f1f1f1;
		}

		.custom-chatbox__header {
			background-color: #007bff;
			color: white;
			padding: 5px;
			display: flex;
			align-items: center;
		}

		.custom-chatbox__messages {
			flex-grow: 1;
			overflow-y: auto;
			padding: 10px;
		}

		.custom-chatbox__footer {
			display: flex;
			padding: 10px;
			background-color: #f1f1f1;
		}

		#chat-input {
			flex-grow: 1;
			padding: 5px;
			border: 1px solid #ccc;
			border-radius: 3px;
		}

		.custom-chatbox__send--footer {
			background-color: #007bff;
			color: white;
			border: none;
			padding: 5px 10px;
			margin-left: 5px;
			cursor: pointer;
			border-radius: 3px;
			margin: 0 0 0 5px;
		}

		.messages_item {
			margin-bottom: 10px;
			padding: 5px 10px;
			border-radius: 5px;
		}

		.messages_item--visitor {
			background-color: #e6f3ff;
			align-self: flex-end;
		}

		.messages_item--operator {
			background-color: #f0f0f0;
			align-self: flex-start;
		}

		.messages_item--error {
			color: red;
		}

		.search-results-container {
			position: absolute;
			top: 100%;
			left: 0;
			right: 0;
			background: white;
			border: 1px solid #ddd;
			border-radius: 4px;
			box-shadow: 0 2px 4px rgba(0,0,0,0.1);
			z-index: 1000;
			max-height: 300px;
			overflow-y: auto;
		}

		.search-result-item {
			padding: 10px 15px;
			border-bottom: 1px solid #eee;
			cursor: pointer;
		}

		.search-result-item:hover {
			background-color: #f5f5f5;
		}

		.bot-icon {
			width: 40px; 
			height: auto;
			margin-right: 5px; 
		}

		.custom-chat-message {
			display: flex; 
			align-items: flex-start; 
			margin-bottom: 10px; 
		}

		.bot-icon {
			width: 40px; 
			height: 40px; 
			margin-right: 10px; 
			border-radius: 50%;
			position: relative; 
			top: 10px; 
		}

		.bot-message {
			background-color: #6f42c1; /* Purple background */
			color: white; 
			border-radius: 10px; 
			padding: 10px; 
			max-width: 80%; 
			word-wrap: break-word; 
			position: relative;
			margin-left: 10px; 
		}

		.user-message {
			background-color: #d1e7dd;
			color: black; 
			border-radius: 10px; 
			padding: 10px; 
			max-width: 80%; 
			word-wrap: break-word; 
			margin-left: auto; 
		}

		.quick-options {
			display: flex; 
			flex-direction: column; 
			margin-top: 10px; 
			font-size: 12px; 
			margin-left: 50px; 
		}

		.quick-options button {
			background-color: #6f42c1; 
			color: white;
			border: none; 
			border-radius: 5px; 
			padding: 5px; 
			margin: 2px 0; 
			cursor: pointer; 
		}
	</style>
		   <!-- Chatbox Icon -->
		   <div id="chatbox-icon" onclick="toggleChatbox()" style="position: fixed; bottom: 20px; right: 20px; cursor: pointer; background: #007bff; color: #fff; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; z-index: 1000;">
                    <i class="fa fa-comments" style="font-size: 24px;"></i>
                </div>

		<!-- Chatbox -->
		<div class="container custom-chatbox-container">
			<div class="custom-chatbox">
				<div class="custom-chatbox__support">
					<div class="custom-chatbox__header">
						<h4>Customer Support</h4>
					</div>
					<div class="custom-chatbox__messages" id="content-box" style="padding: 10px; overflow-y: auto; height: 300px;"></div>
					<div class="custom-chatbox__footer" style="padding: 10px; display: flex;">
						<input type="text" id="chat-input" placeholder="Write a message..." style="flex-grow: 1; padding: 5px; border: 1px solid #ccc; border-radius: 3px;">
						<button id="send-button" style="background-color: #007bff; color: white; border: none; padding: 5px 10px; margin-left: 5px; border-radius: 3px; cursor: pointer;">Send</button>
					</div>
				</div>
			</div>
		</div>
		<script src="{{ asset('assets/js/chatbox.js') }}"></script>
		<!--/Chatbox-->
		<!-- Main Wrapper -->
        @yield('content')
		<!-- /Main Wrapper -->
		
		<!-- jQuery -->
        <script src="{{ URL::to('assets/js/jquery-3.5.1.min.js') }}"></script>
		<!-- Bootstrap Core JS -->
        <script src="{{ URL::to('assets/js/popper.min.js') }}"></script>
        <script src="{{ URL::to('assets/js/bootstrap.min.js') }}"></script>
		<!-- Slimscroll JS -->
		<script src="{{ URL::to('assets/js/jquery.slimscroll.min.js') }}"></script>
		<!-- Select2 JS -->
		<script src="{{ URL::to('assets/js/select2.min.js') }}"></script>
		<!-- Datatable JS -->
		<script src="{{ URL::to('assets/js/jquery.dataTables.min.js') }}"></script>
		<script src="{{ URL::to('assets/js/dataTables.bootstrap4.min.js') }}"></script>
		<!-- Datetimepicker JS -->
		<script src="{{ URL::to('assets/js/moment.min.js') }}"></script>
		<script src="{{ URL::to('assets/js/bootstrap-datetimepicker.min.js') }}"></script>
		<!-- Custom JS -->
		<script src="{{ URL::to('assets/js/app.js') }}"></script>
		@yield('script')

		<!--Chatbox-->
		<script>
			function toggleChatbox() {
				const chatboxContainer = document.querySelector('.custom-chatbox-container');
				chatboxContainer.style.display = chatboxContainer.style.display === 'none' ? 'block' : 'none';
			}

			// Ensure the chatbox is hidden initially
			document.addEventListener('DOMContentLoaded', () => {
				document.querySelector('.custom-chatbox-container').style.display = 'none';
			});
		</script>
		<script>
			const botIconUrl = "{{ asset('images/logo-circle.png') }}";
		</script>
    </body>
</html>