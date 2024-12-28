@extends('layouts.master')
@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Video Conference</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Video Conference</li>
                    </ul>
                </div>
                    <div class="col-auto">
                        <button onclick="createMeeting()" class="btn btn-primary btn-md mb-3 w-100" style="background-color:#5a83d2;border:none;">
                                <i class="fa fa-plus-circle"></i> Create New Meeting
                        </button>
                    </div>
                </div>
            </div>
        <!-- /Page Header -->

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <h4>Welcome, {{ Auth::user()->name }}</h4>
                            <p>Join an existing one</p>
                        </div>

                        <div class="row justify-content-center mt-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title text-center">Join Existing Meeting</h5>
                                        <form onsubmit="return joinMeeting(event)">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="roomID" 
                                                       placeholder="Enter Room ID" required>
                                            </div>
                                            <button type="submit" class="btn btn-success w-100">
                                                <i class="fa fa-sign-in"></i> Join Meeting
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function createMeeting() {
    // Generate a random room ID
    const roomID = Math.floor(Math.random() * 10000000);
    window.location.href = "{{ route('page/schedule/timing') }}?roomID=" + roomID;
}

function joinMeeting(event) {
    event.preventDefault();
    const roomID = document.getElementById('roomID').value;
    window.location.href = "{{ route('page/schedule/timing') }}?roomID=" + roomID;
    return false;
}
</script>
@endsection 