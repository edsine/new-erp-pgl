<!DOCTYPE html>
<html>
<head>
    <title>Password Reset</title>
</head>
<body>
    <h4 class="card-title">Password Reset</h4>
    <p>Hello,</p>
    <p>You are receiving this email because we received a password reset request for your account.</p>
    <p>Click the following link to reset your password:</p>
    <a href="{{ $actionUrl }}" style="
    box-sizing: border-box;
    font-family: -apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';
    border-radius: 4px;
    color: #fff;
    display: inline-block;
    overflow: hidden;
    text-decoration: none;
    background-color: #2d3748;
    border-bottom: 8px solid #2d3748;
    border-left: 18px solid #2d3748;
    border-right: 18px solid #2d3748;
    border-top: 8px solid #2d3748;">Reset Password</a>
    <p>If you did not request a password reset, no further action is required.</p>
    <hr/>
    <p>
        If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:
        {{ $actionUrl }}</p>
</body>
</html>
