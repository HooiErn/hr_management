@extends('layouts.job')
@section('content')
    <div class="main-wrapper">
        <!-- Header -->
        <div class="header">
            <!-- Logo -->
            <div class="header-left">
                <a href="{{ route('homepage') }}" class="logo">
                    <img src="{{ URL::to('images/logo-circle.png') }}" width="45" height="45" alt="HR logo">
                </a>
            </div>
            <!-- /Logo -->
            <!-- Header Title -->
            <div class="page-title-box float-left">
                <h3>Jobs List</h3>
            </div>
            <!-- /Header Title -->
            <!-- Header Menu -->
            <ul class="nav user-menu">
                <!-- Search -->
                <li class="nav-item">
                    <div class="top-nav-search">
                        <a href="javascript:void(0);" class="responsive-search">
                            <i class="fa fa-search"></i>
                        </a>
                    </div>
                </li>
                <!-- /Search -->
            </ul>
            <!-- /Header Menu -->
        </div>
        <!-- /Header -->
        
        <!-- Sidebar Search -->
        <div class="sidebar-search" id="sidebar-search" style="display: none;">
            <button id="close-filter" class="btn btn-secondary" style="float: right;">&lt;</button>
            <h4>Filter Jobs</h4>
            <input type="text" id="job-title" class="form-control" placeholder="Job Title" onkeyup="searchJobs()">
            <input type="number" id="salary-from" class="form-control" placeholder="Salary From" onkeyup="searchJobs()">
            <input type="number" id="salary-to" class="form-control" placeholder="Salary To" onkeyup="searchJobs()">
            <select id="job-type" class="form-control" onchange="searchJobs()">
                <option value="">Select Job Type</option>
                <option value="Full Time">Full Time</option>
                <option value="Part Time">Part Time</option>
                <option value="Internship">Internship</option>
                <option value="Temporary">Temporary</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <!-- /Sidebar Search -->
        
        <!-- Floating Search Button -->
        <div class="floating-search" id="floating-search">
            <button id="toggle-search" class="btn btn-primary">
                <i class="fa fa-search"></i>
            </button>
        </div>

        <!-- Page Wrapper -->
        <div class="page-wrapper job-wrapper" id="page-wrapper">
            <!-- Page Content -->
            <div class="content container">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="page-title">Get a Job !</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('form/job/list') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Jobs</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->
                <div class="row" id="job-cards-container">
                    @foreach ($job_list as $list)
                    @php
                        $date = $list->created_at;
                        $date = Carbon\Carbon::parse($date);
                        $elapsed =  $date->diffForHumans();
                    @endphp
                    <div class="col-md-6">
                        <a class="job-list" href="{{ url('form/job/view/'.$list->id) }}">
                            <div class="job-list-det">
                                <div class="job-list-desc">
                                    <h3 class="job-list-title">{{ $list->job_title }}</h3>
                                    <h4 class="job-department">{{ $list->department }}</h4>
                                </div>
                                <div class="job-type-info">
                                    <span class="job-types">{{ $list->job_type }}</span>
                                </div>
                            </div>
                            <div class="job-list-footer">
                                <ul>
                                    <li><i class="fa fa-map-signs"></i>{{ $list->job_location }}</li>
                                    <li><i class="fa fa-money"></i>{{ $list->salary_from }}-{{ $list->salary_to }}</li>
                                    <li><i class="fa fa-clock-o"></i>{{ $elapsed }}</li>
                                </ul>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- /Page Wrapper -->

    <script>
    let searchTimeout;

    document.getElementById('toggle-search').addEventListener('click', () => {
        const sidebarSearch = document.getElementById('sidebar-search');
        const pageWrapper = document.getElementById('page-wrapper');
        const searchButton = document.getElementById('toggle-search');

        sidebarSearch.style.display = 'block';
        pageWrapper.style.marginLeft = '250px';
        searchButton.style.display = 'none';
    });

    document.getElementById('close-filter').addEventListener('click', () => {
        const sidebarSearch = document.getElementById('sidebar-search');
        const pageWrapper = document.getElementById('page-wrapper');
        const searchButton = document.getElementById('toggle-search');

        sidebarSearch.style.display = 'none';
        pageWrapper.style.marginLeft = '0';
        searchButton.style.display = 'block';
    });

    function searchJobs() {
        clearTimeout(searchTimeout);
        
        searchTimeout = setTimeout(() => {
            const jobTitle = document.getElementById('job-title').value;
            const salaryFrom = document.getElementById('salary-from').value;
            const salaryTo = document.getElementById('salary-to').value;
            const jobType = document.getElementById('job-type').value;

            // Build the query string based on filled inputs
            let query = '';
            if (jobTitle || salaryFrom || salaryTo || jobType) {
                query = `?job_title=${jobTitle}&salary_from=${salaryFrom}&salary_to=${salaryTo}&job_type=${jobType}`;
            }

            fetch(`{{ route('form/job/list') }}${query}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                updateJobCards(data);
            })
            .catch(error => console.error('Error fetching job data:', error));
        }, 300);
    }

    function updateJobCards(data) {
        const jobCardsContainer = document.getElementById('job-cards-container');
        let html = '';

        if (data.length > 0) {
            data.forEach(list => {
                const elapsed = calculateElapsedTime(list.created_at);
                html += `
                    <div class="col-md-6">
                        <a class="job-list" href="{{ url('form/job/view/') }}/${list.id}">
                            <div class="job-list-det">
                                <div class="job-list-desc">
                                    <h3 class="job-list-title">${list.job_title}</h3>
                                    <h4 class="job-department">${list.department}</h4>
                                </div>
                                <div class="job-type-info">
                                    <span class="job-types">${list.job_type}</span>
                                </div>
                            </div>
                            <div class="job-list-footer">
                                <ul>
                                    <li><i class="fa fa-map-signs"></i>${list.job_location}</li>
                                    <li><i class="fa fa-money"></i>${list.salary_from}-${list.salary_to}</li>
                                    <li><i class="fa fa-clock-o"></i>${elapsed}</li>
                                </ul>
                            </div>
                        </a>
                    </div>
                `;
            });
        } else {
            html = '<div class="col-12 text-center"><p>No jobs found matching your search.</p></div>';
        }
        jobCardsContainer.innerHTML = html;
    }

    function calculateElapsedTime(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const elapsed = now - date; // Difference in milliseconds

        const seconds = Math.floor(elapsed / 1000);
        const minutes = Math.floor(seconds / 60);
        const hours = Math.floor(minutes / 60);
        const days = Math.floor(hours / 24);

        if (days > 0) return `${days} day${days > 1 ? 's' : ''} ago`;
        if (hours > 0) return `${hours} hour${hours > 1 ? 's' : ''} ago`;
        if (minutes > 0) return `${minutes} minute${minutes > 1 ? 's' : ''} ago`;
        return `${seconds} second${seconds > 1 ? 's' : ''} ago`;
    }
    </script>

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

    <style>
        .floating-search {
            position: fixed;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            border-radius: 0 20px 20px 0;
            padding: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .floating-search .btn {
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-search {
            position: fixed;
            left: 0;
            top: 55%;
            transform: translateY(-50%);
            width: 250px;
            background-color: white;
            border: 1px solid #ccc;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 999;
            padding: 20px;
        }
    </style>

@endsection
