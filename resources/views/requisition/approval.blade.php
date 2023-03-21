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
                                <th>{{__('Amount')}}</th>
                                <th>{{__('Document')}}</th>
                                <th>{{__('Workflow')}}</th>
                                <th>{{__('Requisition Status')}}</th>
                                <th>{{__('Payment Status')}}</th>
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
                            </td>
                            <td>
                                @if($item->hod_approval == 'Pending')
                                Awaiting HOD Approval
                                @elseif($item->admin_approval == 'Pending')
                                Awaiting Admin Approval
                                @elseif($item->chairman_approval == 'Pending')
                                Awaiting Chairman Approval
                                @endif
                            </td>
                            <td> {{$item->status}} </td>
                            <td>
                                @if($item->payment_status == 'Paid')
                                <span class="text-success"><i class="fas fa-check-circle"></i> @lang('Paid')</span>
                                @else
                                <span class="text-danger"><i class="fas fa-times-circle"></i> @lang('Not Paid')</span>
                                @endif
                            </td>
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