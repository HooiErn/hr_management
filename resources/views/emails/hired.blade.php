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
    
    <p>You are expected to start your journey with us within one week, with the starting date being <strong>{{ $start_date }}</strong>.</p>
    
    <p>Please bring the following documents with you on your first day:</p>
    <ul>
        <li>A copy of your IC (Identity Card), both front and back.</li>
        <li>A copy of your highest educational certificate.</li>
        <li>Any relevant work experience certificates or references (if applicable).</li>
        <li>Proof of previous employment (if applicable).</li>
        <li>Your bank account details for salary payments.</li>
        <li>Any other personal identification documents required for onboarding.</li>
    </ul>
    
    <p>You will receive further instructions and details about the onboarding process soon.</p>
    
    <p>Best regards,<br>{{ config('app.name') }} Team</p>
</body>
</html>
