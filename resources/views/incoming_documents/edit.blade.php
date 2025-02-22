@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>
                        Edit Document
                    </h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="card">

            {!! Form::model($document, ['route' => ['incoming_documents_manager.update', $document->id], 'method' => 'patch', 'enctype' => 'multipart/form-data']) !!}

            <div class="card-body">
                <div class="row">
                    @include('documents.editfields')
                </div>
            </div>

            <div class="card-footer" style="margin-bottom: 50px;">
                {!! Form::submit('SUBMIT', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('incoming_documents_manager.index') }}" class="btn btn-default"> Cancel </a>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
