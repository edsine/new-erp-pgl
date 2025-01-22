<!DOCTYPE html>
<html>
<head>
    <title>Welcome to NIWA</title>
    <style>
        /* body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        } */

        .logo-container {
            text-align: center;
        }

        .logo-container img {
            height: 100px;
        }

        /* Add additional styling for other elements as needed */
    </style>
</head>
<body>
    <div class="logo-container">
        <img src="{{ url(asset('assets/media/logos/NIWA-logo.png')) }}" alt="NIWA Logo"/>
    </div><br/><br/>
    <p>Hello {{ $users->first_name . ' ' . $users->last_name }}</p>
    <p>Congratulations!<br/> Your NIWA Enterprise Business Suite account has been created. You now have access to a wide range of resources that will simplify your day-to-day tasks.</p>
    <p>You can use these details to login and access your dashboard.</p>
    <p>Your login credentials are as follows:</p>
    <p>Email Address: <b>{{ $users->email }}</b></p>
    <p>Password: <b>{{ $password }}</b></p>
    <p>Please log in now to change your password.<br/>Note: it is your responsibility to keep your login credentials secure.</p>
    <p>The EBS Support Team is available to provide all the support you might require.</p>
    <p>Thank you ,<br/>Best Regards<br/>E-NIWA EBS Support</p>
    <a href="{{ url('/login') }}" style="
    box-sizing: border-box;
    font-family: -apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';
    border-radius: 4px;
    color: #fff;
    display: inline-block;
    overflow: hidden;
    text-decoration: none;
    background-color: #178c2d;
    border-bottom: 8px solid #178c2d;
    border-left: 18px solid #178c2d;
    border-right: 18px solid #178c2d;
    border-top: 8px solid #178c2d;">LOGIN</a>
    <hr/>
    <p>
        If you're having trouble clicking the "LOGIN" button, copy and paste this URL below into your web browser:
        {{ url('/login') }}</p>
</body>
</html>
