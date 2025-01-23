<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Notification</title>
</head>
<body>
    <h1>Login Notification</h1>
    <p>Hello {{ $user->name }},</p>
    <p>We detected a login to your account on {{ config('app.name') }}.</p>
    <p>If this was you, then no further action is needed.</p>
    <p>If you did not log in, please take immediate action:</p>
    <ul>
        <li><a href="{{ route('password.request') }}">Click here to reset your password.</a></li>
        <li>After you are done reseting your password, click below link to logout the user remotely!!!</a></li>
        <li><a href="{{ url('/logout-remote/' . $user->id . '/' . $token) }}">Click here to log out the unknown user immediately.</a></li>
    </ul>
    <p>If you have any concerns, please contact support.</p>
    <p>Thank you, <br> {{ config('app.name') }} Team</p>
</body>
</html>
