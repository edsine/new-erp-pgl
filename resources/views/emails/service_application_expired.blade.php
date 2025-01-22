<!-- resources/views/emails/service_application_expired.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Service Application Expired</title>
</head>
<body>
    <p>Dear {{$employer->company_name}},</p>

    <p>Your service application ({{ $application->theservice->name }}) is about to expire.</p>

    <p>Details of the application:</p>
    <ul>
        <li>Service Name: {{ $application->theservice->name }}</li>
        <li>Name Of Location: {{ $application->branch->branch_name }}</li>
        <li>Date Applied: {{ date('d M, Y', strtotime($application->created_at)) }}</li>
        <li>Date Of Expiration: {{ date('d M, Y', strtotime($application->expiry_date)) }}</li>
    </ul>
    
    <p>Please take necessary actions.</p>
    

    <p>Visit the url below to renew the service</p>

    <p><a href="{{ env('DOCUMENT_URL') }}service/application/renewal/{{ $application->id }}">View Renewal Status</a></p>

    <p>Best regards,</p>
    <p>NIWA Optima Portal</p>
</body>
</html>
