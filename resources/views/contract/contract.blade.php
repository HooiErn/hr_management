<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employment Contract</title>
</head>
<body>
    <h1>Employment Contract</h1>
    <p>Dear {{ $name }},</p>
    <p>We are pleased to confirm your employment with {{ config('app.name') }}. Below are the details of your contract:</p>
    <ul>
        <li><strong>Position:</strong> {{ $position }}</li>
        <li><strong>Start Date:</strong> {{ $start_date }}</li>
        <li><strong>Salary:</strong> ${{ $salary }}</li>
        <!-- Add other contract details here -->
    </ul>
    <p>By signing below, you confirm that you agree to the terms of the contract:</p>
    <p>_______________________</p>
    <p>Signed, {{ config('app.name') }} Team</p>
</body>
</html>
