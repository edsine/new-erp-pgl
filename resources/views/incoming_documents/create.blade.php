@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    {{-- <img src="{{ asset('niwa.jpg') }}" style="height: 100px;"/> --}}
                    <h2>
                        Add New Incoming Letter
                    </h2>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3 mb-10">

        @include('adminlte-templates::common.errors')

        <div class="card">

            {!! Form::open(['route' => 'incoming_store', 'enctype' => 'multipart/form-data']) !!}
            @csrf

            <div class="card-body">

                <div class="row">
                    @include('incoming_documents.fields')



                </div>

            </div>

            <div class="card-footer" style="margin-bottom: 30px;">
                {!! Form::submit('SUBMIT', ['class' => 'btn btn-primary']) !!}
                {{-- <a href="{{ route('incoming_documents_manager.index') }}" class="btn btn-default"> Cancel </a> --}}
            </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
@push('page_scripts')
{{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script> --}}
<style>
    textarea.select2-search__field{
       width: 100%;

    }
    .select2-container .select2-search--inline .select2-search__field {
    box-sizing: border-box;
    border: none;
    font-size: 100%;
    margin-top: 0px;
    margin-left: 5px;
    padding: 0;
    max-width: 300px;
    resize: none;
    height: 25px;
    vertical-align: top;
    font-family: sans-serif;
    overflow: hidden;
    word-break: keep-all;
    }
</style>
<script>
    $(document).ready(function () {
        $('.form-control[multiple]').select2({
            theme: 'bootstrap',
            //placeholder: 'Select options',
            allowClear: true
        });
    });
</script>


@endpush
