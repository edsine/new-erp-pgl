<!DOCTYPE html>
<html>
<head>
    <title>Client Documents</title>
</head>
<body>
    <p>Dear {{ $user->contact_firstname.' '.$user->contact_surname }},</p>

    @if($status == "approve")
    <p>This is to notify you that all your documents have been approved.</p>
    @else
    <p>This is to notify you that some documents you uploaded were rejected.</p>
    @endif

    @if($status == "decline")
    <p>Details of your documents:</p>
    <ul>
        @foreach($employerDocuments as $document)
            <li>Document Name: {{ $document->name }}</li>
            <a href="{{ env('DOCUMENT_URL') }}storage/{{ $document->path }}" target="_blank">Open PDF Document</a>
            <!-- Add more details as needed -->
        @endforeach
    </ul>
    @endif

    <p>{{ $service_app->mse_document_verification_comment }} </p>

    @if($status == "decline")
    <p>Visit the url below to upload new documents</p>
    <p><a href="{{ env('DOCUMENT_URL') }}documents/index ">View Documents Status</a></p>
    @endif


    <p>Best regards,</p>
    <p>NIWA OPTIMA Portal</p>
</body>
</html>
