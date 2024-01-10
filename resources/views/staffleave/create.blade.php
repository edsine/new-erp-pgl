@extends('layouts.admin')
@section('page-title')
    {{ __('Create Leave') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('staff_leave.index') }}">{{ __('Leave') }}</a></li>
    <li class="breadcrumb-item">{{ __('Create Leave') }}</li>
@endsection
@push('script-page')
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/jquery.repeater.min.js') }}"></script>
@endpush
@section('content')
    <div class="row">
        {{ Form::open(['route' => 'staff_leave.store', 'method' => 'post']) }}
        <div class="modal-body">

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {{ Form::label('employee_id', __('Employee'), ['class' => 'form-label']) }}
                        @if (isset($employee))
                        @else
                            {{ Form::select('employee_id', $employees, null, ['class' => 'form-control select', 'id' => 'employee_id', 'placeholder' => __('Select Employee')]) }}
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {{ Form::label('reliever_id', __('Reliever'), ['class' => 'form-label']) }}
                        {{ Form::select('reliever_id', $employees, null, ['class' => 'form-control select', 'id' => 'reliever_id', 'placeholder' => __('Select Reliever')]) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {{ Form::label('leave_type_id', __('Leave Type'), ['class' => 'form-label']) }}
                        <select name="leave_type_id" id="leave_type_id" class="form-control select">
                            @foreach ($leavetypes as $leave_type)
                                <option value="{{ $leave_type->id }}">{{ $leave_type->title }} (<p class="float-right pr-5">
                                        {{ $leave_type->days }}</p>)</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('start_date', __('Start Date'), ['class' => 'form-label']) }}
                        {{ Form::date('start_date', null, ['class' => 'form-control']) }}


                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('end_date', __('End Date'), ['class' => 'form-label']) }}
                        {{ Form::date('end_date', null, ['class' => 'form-control']) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {{ Form::label('leave_reason', __('Leave Description'), ['class' => 'form-label']) }}
                        {{ Form::textarea('leave_reason', null, ['class' => 'form-control', 'placeholder' => __('Leave Description')]) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary">
        </div>
        {{ Form::close() }}

    </div>
@endsection
