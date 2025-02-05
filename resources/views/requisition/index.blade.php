@extends('layouts.admin')

@section('page-title')
    {{__('Requisition Manager')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Requisitions')}}</li>
@endsection

@section('action-btn')
    <div class="float-end">
            <a href="{{ route('requisition.create')}}"  data-bs-toggle="tooltip" title="{{__('Create')}}"  class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i>
            </a>
    </div>
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
                                <th>{{__('Status')}}</th>
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
                            <td> {{$item->status}} </td>
                            <td>
                                @if($item->payment_status == 'Paid')
                                <span class="text-success"><i class="fas fa-check-circle"></i> @lang('Paid')</span>
                                @else
                                <span class="text-danger"><i class="fas fa-times-circle"></i> @lang('Not Paid')</span>
                                @endif
                                
                            </td>
                            <td>  
                                
                                {{-- <div class="action-btn bg-warning ms-2">
                                    <a href="#"
                                        data-url="{{ URL::to('requisition/' . $item->id . '/action') }}"
                                        data-size="lg" data-ajax-popup="true"
                                        data-title="{{ __('Requisition Action') }}"
                                        class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip"
                                        title="{{ __('Requisition Action') }}"
                                        data-original-title="{{ __('Requisition Action') }}">
                                        <i class="ti ti-caret-right text-white"></i> </a>
                                </div> --}}
                                <div class="action-btn bg-info ms-2">
                                    <a href="#"
                                        data-url="{{ URL::to('requisition/' . $item->id . '/view') }}"
                                        data-size="lg" data-ajax-popup="true"
                                        data-title="{{ __('Requisition Action') }}"
                                        class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip"
                                        title="{{ __('View Approval') }}"
                                        data-original-title="{{ __('View Approval') }}">
                                        <i class="ti ti-caret-right text-white"></i> </a>
                                </div>
                                @if($item->chairman_approval == 'Pending')
                                <div class="action-btn bg-primary ms-2">
                                    <a href="{{ route('requisition.edit', $item->id)}}"  data-size="lg" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-original-title="{{__('Edit')}}"><i class="ti ti-pencil text-white"></i></a>
                                </div>

                                <div class="action-btn bg-danger ms-2">
                                {!! Form::open(['method' => 'DELETE', 'route' => ['requisition.destroy', $item->id],'id'=>'delete-form-'.$item->id]) !!}

                                    <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$item->id}}').submit();"><i class="ti ti-trash text-white"></i></a>
                                    {!! Form::close() !!}
                                </div>
                                @endif
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