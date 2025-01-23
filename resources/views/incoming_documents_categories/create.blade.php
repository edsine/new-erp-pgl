@extends('layouts.app')

@section('title', 'Document Category')


@push('styles')
@endpush


@section('content')

{{-- @include('layouts.messages') --}}
    {{-- <div class="components-preview wide-md mx-auto"> --}}
    <div class="nk-block-head nk-block-head-sm">
        <div class="nk-block-between">
            <div class="nk-block-head-content">
                <h3 class="nk-block-title page-title">Create New File</h3>
                
            </div><!-- .nk-block-head-content -->
            
        </div><!-- .nk-block-between -->
    </div><!-- .nk-block-head -->
    @can("create files")
    <div class="nk-block nk-block-lg">
        <div class="card card-bordered card-preview">
            <div class="card-inner">
                <form action="{{ route('incoming_documents_category.store') }}" method="POST">
                    @csrf
                    @include('incoming_documents_categories.form')
                </form>
            </div>
        </div><!-- .card-preview -->
    </div> <!-- nk-block -->
    @endcan
    {{-- </div><!-- .components-preview --> --}}

@endsection