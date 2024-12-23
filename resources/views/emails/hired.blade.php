<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Congratulations! You are Hired</title>
</head>
<body>
    <h1>Dear {{ $name }},</h1>
    <p>We are excited to inform you that after careful consideration, we would like to offer you the position with us! Congratulations on successfully passing the interview.</p>
    <p>We expect you to start your journey with us within one week, with the starting date being <strong>{{ $start_date }}</strong>.</p>
    <p>You will receive further instructions and details about the onboarding process soon.</p>
    <p>Best regards,<br>{{ config('app.name') }} Team</p>
</body>
</html>
