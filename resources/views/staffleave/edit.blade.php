@extends('layouts.admin')
@section('page-title')
    {{ __('Edit Leave') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('staff_leave.index') }}">{{ __('Leave') }}</a></li>
    <li class="breadcrumb-item">{{ __('Edit Leave') }}</li>
@endsection
@push('script-page')
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/jquery.repeater.min.js') }}"></script>
@endpush
@section('content')
    <div class="row">
        {{ Form::model($leave, ['route' => ['staff_leave.update', $leave->id], 'method' => 'PUT']) }}
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
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {{ Form::label('reliever_id', __('Reliever'), ['class' => 'form-label']) }}
                            {{ Form::select('reliever_id', $employees, null, ['class' => 'form-control select', 'id' => 'reliever_id', 'placeholder' => __('Select Reliever')]) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {{ Form::label('leave_type_id', __('Leave Type'), ['class' => 'form-label']) }}
                        {{ Form::select('leave_type_id', $leavetypes, null, ['class' => 'form-control select', 'placeholder' => __('Select Leave Type')]) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('start_date', __('Start Date'), ['class' => 'form-label']) }}
                        {{ Form::date('start_date', null, ['class' => 'form-control ']) }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('end_date', __('End Date'), ['class' => 'form-label']) }}
                        {{ Form::date('end_date', null, ['class' => 'form-control ']) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {{ Form::label('leave_reason', __('Leave Description'), ['class' => 'form-label']) }}
                        {{ Form::textarea('leave_reason', null, ['class' => 'form-control', 'placeholder' => __('Leave Reason')]) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <input type="submit" value="{{ __('Update') }}" class="btn  btn-primary">
        </div>
        {{ Form::close() }}
    </div>
@endsection
