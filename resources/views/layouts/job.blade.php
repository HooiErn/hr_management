<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <title>Jobs - HRTech admin dashboard</title>
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
			background-color: #4CAF50;
			color: white;
			border-radius: 50%;
			width: 50px;
			height: 50px;
			display: flex;
			justify-content: center;
			align-items: center;
			cursor: pointer;
			z-index: 1000; /* Ensures the button is in front */
		}

		.floating-chatbot i {
			font-size: 24px;
		}

		.chatbox-container {
			position: fixed;
			margin: 0 auto;
			bottom: 70px;
			right: 20px;
			z-index: 999; /* Ensures chatbox is in front */
			max-width: 400px;
			
		}

		.chatbox {
			border: 1px solid #ccc;
			border-radius: 5px;
			overflow: hidden;
		}

		.chatbox__support {
			display: flex;
			flex-direction: column;
			height: 400px;
			background-color: white;
		}

		.chatbox__header {
			background-color: #f1f1f1;
			padding: 10px;
			display: flex;
			align-items: center;
		}

		.chatbox__messages {
			flex-grow: 1;
			overflow-y: auto;
			padding: 10px;
		}

		.chatbox__footer {
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

		.chatbox__send--footer {
			background-color: #4CAF50;
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
	<	</style>
		<script type="importmap">
		{
			"imports": {
			"@google/generative-ai": "https://esm.run/@google/generative-ai"
			}
		}
		</script>
		<!--Chatbot Button-->
		<div class="floating-chatbot">
			<i class="fa fa-comment" aria-hidden="true"></i>
		</div>
		<!-- Chatbox -->
		<div class="container chatbox-container" style="display: none;">
			<div class="chatbox">
				<div class="chatbox__support">
					<div class="chatbox__header">
						<div class="chatbox__content--header">
							<h4 class="chatbox__heading--header">Gemini AI Chat Support</h4>
							<p class="chatbox__description--header">Ask me anything!</p>
						</div>
					</div>
					<div class="chatbox__messages" id="content-box"></div>
					<div class="chatbox__footer">
						<input type="text" id="chat-input" placeholder="Write a message...">
						<p class="chatbox__send--footer" id="send-button">Send</p>
					</div>
				</div>
			</div>
		</div>

		<!--/ChatBot Button-->
		<script type="module">
			import { GoogleGenerativeAI } from "@google/generative-ai";

			// Replace with your actual API key
			const API_KEY = "AIzaSyCoEN_2dHW-weRCCC5xx9Q56361AqoBp0o";
			const genAI = new GoogleGenerativeAI(API_KEY);
			const model = genAI.getGenerativeModel({ model: "gemini-1.5-flash" });

			async function sendMessage() {
				const input = document.getElementById('chat-input');
				const message = input.value;

				if (message.trim() === '') return;

				const contentBox = document.getElementById('content-box');
				const visitorMessage = document.createElement('div');
				visitorMessage.className = 'messages_item messages_item--visitor';
				visitorMessage.textContent = message;
				contentBox.appendChild(visitorMessage);
				contentBox.scrollTop = contentBox.scrollHeight;

				input.value = '';

				try {
					const result = await model.generateContent(message);
					const response = result.response.text();

					const operatorMessage = document.createElement('div');
					operatorMessage.className = 'messages_item messages_item--operator';
					operatorMessage.textContent = response;
					contentBox.appendChild(operatorMessage);
					contentBox.scrollTop = contentBox.scrollHeight;
				} catch (error) {
					console.error('Error:', error);
					const errorMessage = document.createElement('div');
					errorMessage.className = 'messages_item messages_item--error';
					errorMessage.textContent = 'Error: ' + error.message;
					contentBox.appendChild(errorMessage);
					contentBox.scrollTop = contentBox.scrollHeight;
				}
			}

			// Add event listeners after the DOM is fully loaded
			document.addEventListener('DOMContentLoaded', () => {
				document.getElementById('send-button').addEventListener('click', sendMessage);
				document.getElementById('chat-input').addEventListener('keypress', function(event) {
					if (event.key === 'Enter') {
						sendMessage();
					}
				});

				// Toggle chatbox visibility
				document.querySelector('.floating-chatbot').addEventListener('click', () => {
					const chatboxContainer = document.querySelector('.chatbox-container');
					chatboxContainer.style.display = chatboxContainer.style.display === 'none' ? 'block' : 'none';
				});
			});
		</script>
		<!-- /Chatbox -->
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
    </body>
</html>