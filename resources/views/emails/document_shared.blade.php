{{-- resources/views/emails/document_shared.blade.php --}}

@component('mail::message')
# Document Shared

Hello!

A document titled **{{ $documentTitle }}** has been shared with you by **{{ $sharedBy }}**.

@if (!empty($lockCode) && $lockCode !== 0)

This mail is confidential. Do not share to anyone unless you are authorized to do so.
Your unlock code is **{{ $lockCode }}**

@endif

@component('mail::button', ['url' => route('documents_manager.details.shared', $documentId)])
View Document
@endcomponent

Thank you for using our application!

Regards,<br>
{{ config('app.name') }}
@endcomponent
