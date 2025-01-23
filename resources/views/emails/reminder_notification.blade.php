<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reminder Notification</title>
</head>
<body>
    <h1>Reminder Notification</h1>
    <p>Hello {{ $user->name }},</p>
    <p>You have a new reminder:</p>
    <p><strong>Subject:</strong> {{ $reminder->subject }}</p>
    <p><strong>Message:</strong> {{ $reminder->message }}</p>
    <p><strong>Frequency:</strong> {{ $reminder->frequency }}</p>
    <p><strong>Reminder Period:</strong> From {{ $reminder->reminderstart_date }} to {{ $reminder->reminderend_date }}</p>
    <p>If you have any concerns, please contact support.</p>
    <p>Thank you,<br>{{ config('app.name') }} Team</p>
</body>
</html>
