@extends('layouts.admin')

@section('title', 'Service')


@push('styles')
@endpush


@section('content')
@include('layouts.messages')
    {{-- <div class="components-preview wide-md mx-auto"> --}}
    <div class="nk-block-head nk-block-head-sm">
        <div class="nk-block-between">
            <div class="nk-block-head-content">
                <h3 class="nk-block-title page-title">Edit File</h3>
                <div class="nk-block-des text-soft">
                   
                </div>
            </div><!-- .nk-block-head-content -->
            <!-- .nk-block-head-content -->
        </div><!-- .nk-block-between -->
    </div><!-- .nk-block-head -->
    @can("update files")
    <div class="nk-block nk-block-lg">
        <div class="card card-bordered card-preview">
            <div class="card-inner">
                <form action="{{ route('documents_category.update', $documents_category->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('documents_categories.form')
                </form>
            </div>
        </div><!-- .card-preview -->
    </div> <!-- nk-block -->
    @endcan
    {{-- </div><!-- .components-preview --> --}}

@endsection



