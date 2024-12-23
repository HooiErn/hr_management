@extends('layouts.plain') 
@section('content')
<style>
    body {
        margin: 0;
        padding: 0;
        min-height: 100vh;
        background: #f7f7f7;
    }
    .page-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh !important;
        padding: 20px;
    }
    .centered-container {
        width: 100%;
        max-width: 800px !important;
        margin: 0 auto !important;
    }
    .meeting-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        width: 100%;
    }
    .meeting-header {
        background: #00c5fb;
        color: #fff;
        padding: 25px;
        text-align: center;
    }
    .meeting-header h3 {
        margin: 0;
        font-size: 24px;
        font-weight: 500;
    }
    .meeting-body {
        padding: 40px;
    }
    .form-group {
        margin-bottom: 25px;
    }
    .form-group label {
        display: block;
        margin-bottom: 10px;
        font-weight: 500;
        font-size: 16px;
    }
    .form-control {
        height: 50px;
        width: 100%;
        padding: 10px 20px;
        border: 1px solid #e3e3e3;
        border-radius: 4px;
        font-size: 16px;
    }
    .btn-join {
        background: #00c5fb;
        color: #fff;
        padding: 15px 30px;
        border: none;
        border-radius: 4px;
        width: 100%;
        font-size: 18px;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.3s ease;
    }
    .btn-join:hover {
        background: #00b4e4;
    }
    #root {
        width: 100%;
        height: 85vh;
        border-radius: 8px;
        overflow: hidden;
    }
</style>

<div class="page-wrapper">
    <div class="centered-container">
        <div class="meeting-card">
            @if(isset($roomID))
                <div id="root"></div>
            @else
                <div class="meeting-header">
                    <h3>Join Interview Session</h3>
                </div>
                <div class="meeting-body">
                    <form method="POST" action="{{ route('public.meeting.join') }}">
                        @csrf
                        <div class="form-group">
                            <label>Your Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   name="name" 
                                   required 
                                   placeholder="Enter your full name">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Room ID <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('roomID') is-invalid @enderror" 
                                   name="roomID" 
                                   required 
                                   placeholder="Enter the room ID provided">
                            @error('roomID')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group mb-0">
                            <button type="submit" class="btn-join">
                                Join Interview
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@if(isset($roomID))
    <script src="https://unpkg.com/@zegocloud/zego-uikit-prebuilt/zego-uikit-prebuilt.js"></script>
    <script>
        window.onload = function () {
            const roomID = "{{ $roomID }}";
            const userName = "{{ $userName }}";
            const userID = Math.floor(Math.random() * 10000) + "";
            const appID = {{ config('zego.app_id') }};
            const serverSecret = "{{ config('zego.server_secret') }}";

            const kitToken = ZegoUIKitPrebuilt.generateKitTokenForTest(
                appID,
                serverSecret,
                roomID,
                userID,
                userName
            );

            const zp = ZegoUIKitPrebuilt.create(kitToken);
            zp.joinRoom({
                container: document.querySelector("#root"),
                scenario: {
                    mode: ZegoUIKitPrebuilt.VideoConference,
                },
                showTurnOffRemoteCameraButton: true,
                showTurnOffRemoteMicrophoneButton: true,
                turnOnMicrophoneWhenJoining: true,
                turnOnCameraWhenJoining: true,
                showMyCameraToggleButton: true,
                showMyMicrophoneToggleButton: true,
                showAudioVideoSettingsButton: true,
                showScreenSharingButton: true,
                showTextChat: true,
                showUserList: true,
                maxUsers: 2,
                layout: "Auto",
                showLayoutButton: false,
            });
        }
    </script>
@endif
@endsection 