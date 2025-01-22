<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Change Notification</title>
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
    <h1>Hello {{ $users->first_name.' '.$users->last_name }},</h1>
    <p>Your password has been successfully changed.</p>
    <p>New Password: <strong>{{ $password }}</strong></p>

    <p>If you did not request this change, please contact our support team immediately.</p>

    <br>
    <p>Best regards,</p>
    <p>NIWA - OPTIMA</p>
</body>
</html>
