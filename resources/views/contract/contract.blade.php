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
        <li><strong>Salary:</strong> RM{{ $salary }}</li>
        <!-- Add other contract details here -->
    </ul>

    <h2>Employee Benefits</h2>
    <ul>
        <li><strong>Medical Coverage:</strong> Comprehensive medical insurance coverage for you and your immediate family members after 3 months of employment.</li>
        <li><strong>Annual Bonus:</strong> Performance-based bonus evaluated yearly based on individual and company performance.</li>
        <li><strong>Leave Benefits:</strong> 14 days of paid annual leave, 14 days of medical leave, and other statutory leaves as per Malaysian employment law.</li>
        <li><strong>EPF & SOCSO:</strong> Statutory contributions as per Malaysian law.</li>
        <li><strong>Additional Benefits:</strong>
            <ul>
                <li>Professional development and training opportunities</li>
                <li>Flexible working arrangements</li>
                <li>Annual salary review</li>
                <li>Team building activities</li>
            </ul>
        </li>
    </ul>

    <h2>Declaration of Understanding</h2>
    <p>By signing below, you confirm that you have read and understood the terms and conditions of your employment contract, including the salary, benefits, and other terms mentioned above. You also acknowledge that you agree to abide by the company's rules and policies.</p>
    
    <p>_______________________</p>
    <p>Signed, {{ config('app.name') }} Team</p>
</body>
</html>
