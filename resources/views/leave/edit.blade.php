{{ Form::model($leave, ['route' => ['leave.update', $leave->id], 'method' => 'PUT']) }}
<div class="modal-body">

    @if ($leave->status == 'Pending')
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('employee_id', __('Employee'), ['class' => 'form-label']) }}
                    {{ Form::select('employee_id', $employees, null, ['class' => 'form-control select', 'placeholder' => __('Select Employee')]) }}
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
        @if (\Auth::user()->type == 'HR')
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {{ Form::label('remark', __('Remark'), ['class' => 'form-label']) }}
                        {{ Form::textarea('remark', null, ['class' => 'form-control', 'placeholder' => __('Leave Remark')]) }}
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {{ Form::label('remark', __('Remark'), ['class' => 'form-label']) }}
                        {{ Form::textarea('remark', null, ['class' => 'form-control', 'readonly', 'placeholder' => __('Leave Remark')]) }}
                    </div>
                </div>
            </div>
        @endif
        {{-- @role('Company')
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{Form::label('status',__('Status'))}}
                <select name="status" id="" class="form-control select2">
                    <option value="">{{__('Select Status')}}</option>
                    <option value="pending" @if ($leave->status == 'Pending') selected="" @endif>{{__('Pending')}}</option>
                    <option value="approval" @if ($leave->status == 'Approval') selected="" @endif>{{__('Approval')}}</option>
                    <option value="reject" @if ($leave->status == 'Reject') selected="" @endif>{{__('Reject')}}</option>
                </select>
            </div>
        </div>
    </div>
    @endrole --}}

</div>
@else
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('employee_id', __('Employee'), ['class' => 'form-label']) }}
            {{ Form::select('employee_id', $employees, null, ['class' => 'form-control select', 'readonly', 'placeholder' => __('Select Employee')]) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('reliever_id', __('Reliever'), ['class' => 'form-label']) }}
            {{ Form::select('reliever_id', $employees, null, ['class' => 'form-control select', 'readonly', 'id' => 'reliever_id', 'placeholder' => __('Select Reliever')]) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('leave_type_id', __('Leave Type'), ['class' => 'form-label']) }}
            {{ Form::select('leave_type_id', $leavetypes, null, ['class' => 'form-control select', 'readonly', 'placeholder' => __('Select Leave Type')]) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('start_date', __('Start Date'), ['class' => 'form-label']) }}
            {{ Form::date('start_date', null, ['class' => 'form-control ', 'readonly']) }}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('end_date', __('End Date'), ['class' => 'form-label']) }}
            {{ Form::date('end_date', null, ['class' => 'form-control ', 'readonly']) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('leave_reason', __('Leave Description'), ['class' => 'form-label']) }}
            {{ Form::textarea('leave_reason', null, ['class' => 'form-control', 'readonly', 'placeholder' => __('Leave Description')]) }}
        </div>
    </div>
</div>
@if (\Auth::user()->type == 'HR')
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('remark', __('Remark'), ['class' => 'form-label']) }}
                {{ Form::textarea('remark', null, ['class' => 'form-control', 'placeholder' => __('Leave Remark')]) }}
            </div>
        </div>
    </div>
@else
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('remark', __('Remark'), ['class' => 'form-label']) }}
                {{ Form::textarea('remark', null, ['class' => 'form-control', 'readonly', 'placeholder' => __('Leave Remark')]) }}
            </div>
        </div>
    </div>
@endif
{{-- @role('HR')
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {{Form::label('status',__('Status'))}}
            <select name="status" id="" class="form-control select2">
                <option value="">{{__('Select Status')}}</option>
                <option value="pending" @if ($leave->status == 'Pending') selected="" @endif>{{__('Pending')}}</option>
                <option value="approval" @if ($leave->status == 'Approval') selected="" @endif>{{__('Approval')}}</option>
                <option value="reject" @if ($leave->status == 'Reject') selected="" @endif>{{__('Reject')}}</option>
            </select>
        </div>
    </div>
</div>
@endrole --}}

</div>
@endif

@if (\Auth::user()->type == 'HR' || $leave->status == 'Pending')
    <div class="modal-footer">
        <input type="button" value="{{ __('Cancel') }}" class="btn  btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{ __('Update') }}" class="btn  btn-primary">
    </div>
@else
    <div class="modal-footer">
        <input type="button" value="{{ __('Cancel') }}" class="btn  btn-light" data-bs-dismiss="modal">
    </div>
@endif
{{ Form::close() }}
