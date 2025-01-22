<!-- Title Field -->
<div class="col-sm-12">
    {!! Form::label('title', 'Title:', ['class' => 'h4']) !!}
    <p>{{ $memo->title }}</p>
</div>

<!-- Description Field -->
<div class="col-sm-12">
    {!! Form::label('description', 'Description:', ['class' => 'h4']) !!}
    <p>{{ $memo->description }}</p>
</div>

<!-- Created By Field -->
<div class="col-sm-12">
    {!! Form::label('created_by', 'Created By:', ['class' => 'h4']) !!}
    <p>{{ $memo->createdBy ? $memo->createdBy->name : '' }}</p>
</div>

<!-- Document Id Field -->
<div class="col-sm-12">
    {!! Form::label('document_id', 'Document URL:', ['class' => 'h4']) !!}
    @php
        $latestDocumentUrl = $memo->document
            ->documentVersions()
            ->latest()
            ->first()
            ? $memo->document
                ->documentVersions()
                ->latest()
                ->first()->document_url
            : '#';
    @endphp
    {{-- <p>{{ $latestDocumentUrl }}</p> --}}
    <a target="_blank" href="{{ asset($latestDocumentUrl) }}">
        <p>View</p>
    </a>
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', 'Created At:', ['class' => 'h4']) !!}
    <p>{{ $memo->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="col-sm-12">
    {!! Form::label('updated_at', 'Updated At:', ['class' => 'h4']) !!}
    <p>{{ $memo->updated_at }}</p>
</div>
