<!-- Title Field -->
<div class="col-sm-12">
    {!! Form::label('title', 'Title:', ['class' => 'h4']) !!}
    <p>{{ $document->title }}</p>
</div>

<!-- Description Field -->
<div class="col-sm-12">
    {!! Form::label('description', 'Description:', ['class' => 'h4']) !!}
    <p>{{ $document->description }}</p>
</div>

<!-- Created By Field -->
<div class="col-sm-12">
    {!! Form::label('assigned_by', 'Assigned By:', ['class' => 'h4']) !!}
    <p>{{ $document->name }}</p>
</div>

<!-- Document Id Field -->
<div class="col-sm-12">
    {!! Form::label('document_id', 'Document URL:', ['class' => 'h4']) !!}
    
    {{-- <p>{{ $latestDocumentUrl }}</p> --}}
    <a target="_blank" href="{{ getDocumentUrl($document->document_url) }}">
        <p>View Document</p>
    </a>
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', 'Sent At:', ['class' => 'h4']) !!}
    <p>{{ get_datetime_format($document->created_at) }}</p>
</div>

<!-- Updated At Field -->
{{-- <div class="col-sm-12">
    {!! Form::label('updated_at', 'Updated At:', ['class' => 'h4']) !!}
    <p>{{ get_datetime_format($$document->updated_at) }}</p>
</div> --}}
