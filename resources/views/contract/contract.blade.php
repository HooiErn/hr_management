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

    <h2>Employee Benefits</h2>
    <ul>
        <li><strong>Health Insurance:</strong> {{ $health_insurance }}</li>
        <li><strong>Bonus:</strong> {{ $bonus }}</li>
        <li><strong>Paid Time Off:</strong> {{ $paid_time_off }} days per year</li>
        <li><strong>Retirement Fund:</strong> {{ $retirement_fund }}</li>
        <li><strong>Other Benefits:</strong> {{ $other_benefits }}</li>
    </ul>

    <h2>Declaration of Understanding</h2>
    <p>By signing below, you confirm that you have read and understood the terms and conditions of your employment contract, including the salary, benefits, and other terms mentioned above. You also acknowledge that you agree to abide by the company's rules and policies.</p>
    
    <p>_______________________</p>
    <p>Signed, {{ config('app.name') }} Team</p>
</body>
</html>
