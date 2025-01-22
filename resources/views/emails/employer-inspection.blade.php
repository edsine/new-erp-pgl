<!-- resources/views/emails/employer-document.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Client Inspection Notification Message</title>
</head>
<body>
    <p>Dear {{ $user->contact_firstname.' '.$user->contact_surname }},</p>

    <p>This is to notify you of a new inspection date from NIWA portal.</p>

    <p>Details of a new client document:</p>
    <ul>
        <li>Inspection date: {{ $service_app->date_of_inspection }}</li>
        <li>Inspection Comments: {{ $service_app->comments_on_inspection }}</li>
    </ul>

    <p>Visit the url below to follow up and make payment form inspection</p>

    <p><a href="{{ env('DOCUMENT_URL') }}service-applications">Inspection Status</a></p>

    <p>Best regards,</p>
    <p>NIWA OPTIMA Portal</p>
</body>
</html>
