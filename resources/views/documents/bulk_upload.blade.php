@extends('layouts.app')

@section('content')

<style>
    /* Optional custom styles */
    .form-container {
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #f9f9f9;
    }
    .btn-primary {
        margin-top: 10px;
    }
</style>
<div class="row">
    <div class="col-md-8 offset-md-2 col-12">
        <div class="form-container">
            <h4 class="mb-4">Bulk File Upload</h4>
            {{-- @include('layouts.messages') --}}
            @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
            <form action="{{ route('bulk.files.upload.now') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="files" class="form-label">Select Files (Maximum file size of 2MB;Allowed files type are PDFs and jpg, png, and jpeg only;The number of files you choose should match with the number of contents in the csv document)</label>
                    <input type="file" class="form-control" id="files" name="files[]" accept=".pdf,.jpeg,.png,.jpg" multiple>
                    @error('files.*')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="csv_file" class="form-label">Select CSV File (Maximum csv file size of 2MB only)</label>
                    <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv">
                    @error('csv_file')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Bulk Upload</button>
            </form>
            
        </div>
    </div>
</div>


@endsection