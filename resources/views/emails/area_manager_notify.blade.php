<!-- resources/views/emails/area_manager_notify.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Demand Notice Approval Required</title>
</head>
<body>
    <p>Dear {{$areaManager->first_name}},</p>

    <p>A demand notice for "{{ $application->theservice->name }}" submitted by "{{ $employer->company_name }}" is awaiting your approval.</p>

    <p>Details of the application:</p>
    <ul>
        <li>Service Name: {{ $application->theservice->name }}</li>
        <li>Name Of Location: {{ $application->branch->branch_name }}</li>
        <li>Date Applied: {{ date('d M, Y', strtotime($application->created_at)) }}</li>
        <li>Total Payable Amount: {{ number_format($application->demand_total ? $application->demand_total : '0', 2) }}</li>
    </ul>
    
    <p>Please take necessary actions.</p>
    

    <p>Visit the url below to view it</p>

    <p><a href="{{ route('areamanager') }}">View Demand Notice Status</a></p>

    <p>Best regards,</p>
    <p>NIWA Optima Portal</p>
</body>
</html>

