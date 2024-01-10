{{ Form::open(['url' => 'staff_leave/changeaction', 'method' => 'post']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-12">
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <div class="card">
                <div class="card-body">
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
                                <select name="leave_type_id" id="leave_type_id" class="form-control select" disabled>
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
                </div>
            </div>
        </div>
        <div class="col-12">
            <h5 class=" d-inline-block mb-4">{{ __('Leave Approval') }}</h5>
            <div class="card-body table-border-style mt-2">
                @if ($leave->hod_approval == 'Pending')
                    @can('manage hod remark')
                        <td>

                            <div>
                                <div class="form-group">
                                    {{ Form::label('hod_remark', __('HOD Remark'), ['class' => 'form-label']) }}
                                    {!! Form::textarea('hod_remark', '', ['class' => 'form-control']) !!}
                                </div>
                            </div>

                        </td>
                    @endcan
                @endif
                @if ($leave->hod_approval == 'Approved' && $leave->admin_approval == 'Pending')
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {{ Form::label('hod_remark', __('HOD Remark: '), ['class' => 'form-label']) }}
                                {!! Form::textarea('hod_remark', $leave->hod_remark, ['class' => 'form-control', 'readonly']) !!}
                            </div>
                        </div>
                        @can('manage admin remark')
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('admin_remark', __('Admin Remark'), ['class' => 'form-label']) }}
                                    {!! Form::textarea('admin_remark', '', ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        @endcan
                    </div>
                @endif
                @if (
                    $leave->hod_approval == 'Approved' &&
                        $leave->admin_approval == 'Approved' &&
                        $leave->chairman_approval == 'Pending')
                    <div class="col-md-12">
                        <div class="form-group">
                            {{ Form::label('hod_remark', __('HOD Remark: '), ['class' => 'form-label']) }}
                            {!! Form::textarea('hod_remark', $leave->hod_remark, ['class' => 'form-control', 'readonly']) !!}
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            {{ Form::label('admin_remark', __('Admin Remark: '), ['class' => 'form-label']) }}
                            {!! Form::textarea('admin_remark', $leave->admin_remark, ['class' => 'form-control', 'readonly']) !!}
                        </div>
                    </div>
                    @can('manage chairman remark')
                        <div class="col-md-12">
                            <div class="form-group">
                                {{ Form::label('chairman_remark', __('Chairman Remark'), ['class' => 'form-label']) }}
                                {!! Form::textarea('chairman_remark', '', ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    @endcan
                @endif
                @if (
                    $leave->hod_approval == 'Approved' &&
                        $leave->admin_approval == 'Approved' &&
                        $leave->chairman_approval == 'Approved')
                    <div class="col-md-12">
                        <div class="form-group">
                            {{ Form::label('hod_remark', __('HOD Remark: '), ['class' => 'form-label']) }}
                            {!! Form::textarea('hod_remark', $leave->hod_remark, ['class' => 'form-control', 'readonly']) !!}
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            {{ Form::label('admin_remark', __('Admin Remark: '), ['class' => 'form-label']) }}
                            {!! Form::textarea('admin_remark', $leave->admin_remark, ['class' => 'form-control', 'readonly']) !!}
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            {{ Form::label('chairman_remark', __('Chairman Remark'), ['class' => 'form-label']) }}
                            {!! Form::textarea('chairman_remark', $leave->chairman_remark, ['class' => 'form-control', 'readonly']) !!}
                        </div>
                    </div>
                @endif
                <div class="col-md-12">
                    <strong>{{ __('HOD Approval: ') }}</strong>
                    {{ $leave->hod_approval }}
                </div>
                <div class="col-md-12">
                    <strong>{{ __('Admin Approval: ') }}</strong>
                    {{ $leave->admin_approval }}
                </div>
                <div class="col-md-12">
                    <strong>{{ __('Chairman Approval: ') }}</strong>
                    {{ $leave->chairman_approval }}
                </div>
                <input type="hidden" value="{{ $leave->id }}" name="leave_id">
            </div>
        </div>
    </div>
</div>
@if ($leave->hod_approval == 'Pending')
    @can('manage hod approval')
        <div class="modal-footer">
            <h3>HOD Approval: </h3>
            <input type="submit" value="{{ __('Approved') }}" class="btn btn-success" data-bs-dismiss="modal"
                name="hod_approval">
            <input type="submit" value="{{ __('Rejected') }}" class="btn btn-danger" name="hod_approval">
        </div>
    @endcan
@endif
@if ($leave->hod_approval == 'Approved' && $leave->admin_approval == 'Pending')
    @can('manage admin approval')
        <div class="modal-footer">
            <h3>Admin Approval: </h3>
            <input type="submit" value="{{ __('Approved') }}" class="btn btn-success" data-bs-dismiss="modal"
                name="admin_approval">
            <input type="submit" value="{{ __('Rejected') }}" class="btn btn-danger" name="admin_approval">
        </div>
    @endcan
@endif
@if (
    $leave->hod_approval == 'Approved' &&
        $leave->admin_approval == 'Approved' &&
        $leave->chairman_approval == 'Pending')
    @can('manage chairman approval')
        <div class="modal-footer">
            <h3>Chairman Approval: </h3>
            <input type="submit" value="{{ __('Approved') }}" class="btn btn-success" data-bs-dismiss="modal"
                name="chairman_approval">
            <input type="submit" value="{{ __('Rejected') }}" class="btn btn-danger" name="chairman_approval">
        </div>
    @endcan
@endif

{{ Form::close() }}
