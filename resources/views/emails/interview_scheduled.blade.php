<!DOCTYPE html>
<html>
<head>
    <title>Interview Scheduled</title>
</head>
<body>
    <h1>Hello {{ $interviewer->name }},</h1>
    <p>Your interview has been scheduled. Please join the meeting using the following room ID: {{ $roomID }}</p>
    <p>Interview Date and Time: {{ $interview_datetime }}</p>
    <p>Interview Type: {{ $interview_type }}</p>
    <p>Best regards,</p>
    <p>Your Company</p>
</body>
</html> 