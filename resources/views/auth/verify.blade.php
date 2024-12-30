<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <p>
        You are receiving this email because we received a password reset request for your account.
    </p>
    <p>
        Please click the link below to reset your password:
    </p>
    <p>
        <a href="{{ url('reset-password', $token) }}">Reset Password</a>
    </p>
    <p>
        If you did not request a password reset, no further action is required.
    </p>
    <p>
        This password reset link will expire in 60 minutes.
    </p>
</body>
</html>
