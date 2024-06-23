@extends('layouts.admin')

@section('page-title')
    {{-- {{__('Requisition Approved')}} --}}
@endsection
@section('breadcrumb')
    {{-- <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Requisitions')}}</li> --}}
@endsection

@section('action-btn')
    {{-- <div class="float-end">
            <a href="{{ route('requisition.create')}}"  data-bs-toggle="tooltip" title="{{__('Create')}}"  class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i>
            </a>
    </div> --}}
@endsection

@section('content')

    <div class="row " style="min-height: ">
        
        <div class="col-md-6 mx-auto my-auto">
            <h4 class="text-center pb-3">Approval Summary</h4>
            <div class="card" style="min-height: 20vh;">
                <div class="card-body p-5 text-center">
                    <p class="mt-4" style="font-weight: 600">You have succesfully approved this requisition</p>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <button class="btn btn-success float-center">Return to Dashboard</button>
            </div>
        </div>
    </div>
@endsection