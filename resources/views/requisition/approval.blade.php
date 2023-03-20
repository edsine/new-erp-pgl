@extends('layouts.admin')

@section('page-title')
    {{__('Requisition Manager')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Requisition Approvals')}}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
            <div class="card-body table-border-style">
                    <div class="table-responsive">
                    <table class="table datatable">
                            <thead>
                            <tr>
                                <th>{{__('Date')}}</th>
                                <th>{{__('Title')}}</th>
                                <th>{{__('Total Amount')}}</th>
                                <th>{{__('Additional Document')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody class="font-style">
                           @foreach($requisitions as $item)
                           <tr>
                            {{-- @dd($item) --}}
                            <td> {{$item->requisition_date}} </td>
                            <td>{{$item->title}}</td>
                            <td>{{$item->totalAmount()}}</td>
                            <td>
                                @if(isset($item->document))
                                <div class="action-btn bg-secondary ms-2">
                                    <a class="mx-3 btn btn-sm align-items-center" href="{{ URL::to('/') }}/storage/requisition/{{$item->employee_id . '/' . $item->document }}" target="_blank"  >
                                        <i class="ti ti-crosshair text-white" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Preview') }}"></i>
                                    </a>
                                </div>
                                @endif
                            <td> {{$item->status}} </td>
                            <td>  
                                
                                <div class="action-btn bg-warning ms-2">
                                    <a href="#"
                                        data-url="{{ URL::to('requisition/' . $item->id . '/action') }}"
                                        data-size="lg" data-ajax-popup="true"
                                        data-title="{{ __('Requisition Action') }}"
                                        class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip"
                                        title="{{ __('Requisition Action') }}"
                                        data-original-title="{{ __('Requisition Action') }}">
                                        <i class="ti ti-caret-right text-white"></i> </a>
                                </div>
                            </td>
                           </tr>
                           @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection