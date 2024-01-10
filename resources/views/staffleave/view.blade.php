<div class="modal-body">
    <div class="row">
        <div class="col-12">
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="row">
                            <p><strong>{{ __('Current Status: ') }}</strong>
                                {{ $leave->status }}</p>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('employee_id', __('Employee'), ['class' => 'form-label']) }}
                                    {!! Form::text('', $employee->name, ['class' => 'form-control disabled', 'readonly']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('reliever_id', __('Reliever'), ['class' => 'form-label']) }}
                                    {!! Form::text('', $reliever->name, ['class' => 'form-control disabled', 'readonly']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('leave_type_id', __('Leave Type'), ['class' => 'form-label']) }}
                                    <select name="leave_type_id" id="leave_type_id" class="form-control select"
                                        disabled>
                                        @foreach ($leavetypes as $leave_type)
                                            <option value="{{ $leave_type->id }}"
                                                {{ $leave->leave_type_id == $leave_type->id ? 'selected' : '' }}>
                                                {{ $leave_type->title }} (<p class="float-right pr-5">
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
                                    {{ Form::date('start_date', $leave->start_date, ['class' => 'form-control', 'readonly']) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('end_date', __('End Date'), ['class' => 'form-label']) }}
                                    {{ Form::date('end_date', $leave->end_date, ['class' => 'form-control', 'readonly']) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('leave_reason', __('Leave Description'), ['class' => 'form-label']) }}
                                    {{ Form::textarea('leave_reason', $leave->leave_reason, ['class' => 'form-control', 'readonly', 'placeholder' => __('Leave Description')]) }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <p><strong>{{ __('HOD Approval: ') }}</strong>
                                {{ $leave->hod_approval }}</p>
                            <div class="form-group">
                                {{ Form::label('hod_remark', __('HOD Remark: '), ['class' => 'form-label']) }}
                                {!! Form::textarea('hod_remark', $leave->hod_remark, ['class' => 'form-control', 'readonly']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <p><strong>{{ __('Admin Approval: ') }}</strong>
                                {{ $leave->admin_approval }}</p>
                            <div class="form-group">
                                {{ Form::label('admin_remark', __('Admin Remark: '), ['class' => 'form-label']) }}
                                {!! Form::textarea('admin_remark', $leave->admin_remark, ['class' => 'form-control', 'readonly']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <p><strong>{{ __('Chairman Approval: ') }}</strong>
                                {{ $leave->chairman_approval }}</p>
                            <div class="form-group">
                                {{ Form::label('chairman_remark', __('Chairman Remark'), ['class' => 'form-label']) }}
                                {!! Form::textarea('chairman_remark', $leave->chairman_remark, ['class' => 'form-control', 'readonly']) !!}
                            </div>
                        </div>
                        <input type="hidden" value="{{ $leave->id }}" name="leave_id">
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
