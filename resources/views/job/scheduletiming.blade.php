@extends('layouts.master')
@section('content')
    <style>
        #root {
            width: 100%;
            height: 80vh;
            margin: 0;
            padding: 20px;
            border-radius: 8px;
            overflow: hidden;
        }
        .room-info {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .room-info code {
            background: #fff;
            padding: 5px;
            border-radius: 3px;
        }
    </style>

    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Video Conference Room</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Video Conference</li>
                        </ul>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('video.dashboard') }}" class="btn btn-primary">
                            <i class="fa fa-arrow-left"></i> Join Meeting
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="room-info">
                        <strong>Room ID:</strong> <code id="roomIdDisplay"></code>
                        <button class="btn btn-sm btn-secondary ml-2" onclick="copyRoomId()">
                            <i class="fa fa-copy"></i> Copy
                        </button>
                    </div>
                    <div id="root"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/@zegocloud/zego-uikit-prebuilt/zego-uikit-prebuilt.js"></script>
    <script>
        window.onload = function () {
            function getUrlParams(url) {
                let urlStr = url.split('?')[1];
                const urlSearchParams = new URLSearchParams(urlStr);
                const result = Object.fromEntries(urlSearchParams.entries());
                return result;
            }

            const roomID = getUrlParams(window.location.href)['roomID'] || (Math.floor(Math.random() * 10000) + "");
            document.getElementById('roomIdDisplay').textContent = roomID;

            const userID = Math.floor(Math.random() * 10000) + "";
            const userName = "{{ Auth::user()->name }}" || "User" + userID;
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
                sharedLinks: [{
                    name: 'Personal link',
                    url: window.location.protocol + '//' + window.location.host + window.location.pathname + '?roomID=' + roomID,
                }],
                scenario: {
                    mode: ZegoUIKitPrebuilt.VideoConference,
                },
                showTurnOffRemoteCameraButton: true,
                showTurnOffRemoteMicrophoneButton: true,
                showRemoveUserButton: true,
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

        function copyRoomId() {
            const roomId = document.getElementById('roomIdDisplay').textContent;
            navigator.clipboard.writeText(roomId);
            toastr.success('Room ID copied to clipboard');
        }
    </script>
@endsection