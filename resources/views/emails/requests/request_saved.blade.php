{{-- resources/views/emails/requests/request_saved.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        h1 {
            font-size: 24px;
            color: #333;
        }
        p {
            font-size: 16px;
            color: #333;
        }
        .button {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }
        .footer {
            font-size: 14px;
            color: #777;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>National Inland Waterways Authority (NIWA)</h1>
    <h3>Hi {{ $greeting }}</h3>

    <p>
        @if($isCreator)
            You have <strong>{{ strtoupper($actionStatus) }}</strong> a <strong>{{ $requestType }}</strong> request.
        @else
            Action required: Review and process the <strong>{{ $requestType }}</strong> request from <strong>{{ $staffName }}</strong> currently in your queue.
        @endif
    </p>

    <p>
        <a href="{{ $actionUrl }}" class="button">View Request</a>
    </p>

    <p class="footer">
        If you have any questions, please do not hesitate to contact us at <a href="mailto:info@niwa.gov.ng">info@niwa.gov.ng</a>, or visit our website at <a href="http://www.niwa.gov.ng" target="_blank">www.niwa.gov.ng</a> anytime.
    </p>
</body>
</html>
