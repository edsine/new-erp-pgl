<!-- resources/views/emails/client_demand_notice.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Demand Notice Due For Payment</title>
</head>
<body>
    <p>Dear {{$employer->company_name}},</p>

    <p>The demand notice for service application ({{ $application->theservice->name }}) is ready for payment.</p>

    <p>Details of the application:</p>
    <ul>
        <li>Service Name: {{ $application->theservice->name }}</li>
        <li>Name Of Location: {{ $application->branch->branch_name }}</li>
        <li>Date Applied: {{ date('d M, Y', strtotime($application->created_at)) }}</li>
        <li>Total Payable Amount: {{ number_format($application->demand_total ? $application->demand_total : '0', 2) }}</li>
    </ul>
    
    <p>Please take necessary actions.</p>
    

    <p>Visit the url below to pay for the service</p>

    <p><a href="{{ env('DOCUMENT_URL') }}equipment-fee-payment/{{ $application->id }}">View Demand Notice Status</a></p>

    <p>Best regards,</p>
    <p>NIWA Optima Portal</p>
</body>
</html>
