{{ Form::open(['url' => 'requisition/changeactionByChairman', 'method' => 'post']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-12">
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <div class="card1">
                <div class="card-body1">
                    <div class="row">
                        <div class="col-md-611">
                            <div class="form-group">
                                {{ Form::label('employee_id', __('Employee'), ['class' => 'form-label']) }}
                                {!! Form::text('', $employee->name, ['class' => 'form-control disabled', 'readonly']) !!}
                            </div>
                        </div>
                        <div class="col-md-611">
                            <div class="form-group">
                                {{ Form::label('requisition_date', __('Issue Date'), ['class' => 'form-label']) }}
                                <div class="form-icon-user">
                                    {{ Form::date('requisition_date', $requisition->requisition_date, ['class' => 'form-control', 'required' => 'required', 'readonly']) }}

                                </div>
                            </div>
                        </div>

                       
                            <div class="col-md-41">
                                <div class="form-group">
                                    {{ Form::label('ref_number', __('Reference Number'), ['class' => 'form-label']) }}
                                    <div class="form-icon-user">
                                        
                                        {{ Form::text('ref_number', $requisition->ref_number, ['class' => 'form-control disabled', 'readonly']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-81">
                                <div class="form-group">
                                    {{ Form::label('title', __('Requisition Title'), ['class' => 'form-label']) }}
                                    {!! Form::text('title', $requisition->title, ['class' => 'form-control disabled', 'readonly']) !!}
                                </div>
                            </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <h5 class=" d-inline-block mb-4">{{ __('Requisition') }}</h5>
            <div class="card-body table-border-style mt-2">
                <div class="table-responsive">
                    <table class="table  mb-0 table-custom-style" data-repeater-list="items" id="sortable-table">
                        <thead>
                            <tr>
                                <th>{{ __('Items') }}</th>
                                <th></th>
                                <th>{{ __('Quantity') }}</th>
                                <th></th>
                                <th>{{ __('Rate') }} </th>
                                <th class="text-end">{{ __('Amount') }}</th>
                            </tr>
                        </thead>
                        @foreach ($requisitionItem as $value)
                            <tbody class="ui-sortable" data-repeater-item>
                                <tr>
                                    <td width="45%">
                                        {{ Form::text('item', $value->item, ['class' => 'form-control item', 'required' => 'required', 'placeholder' => __('Item'), 'readonly']) }}
                                    </td>
                                    <td></td>
                                    <td width="15%">
                                        <div class="form-group price-input input-group search-form">
                                            {{ Form::number('quantity', $value->quantity, ['class' => 'form-control quantity', 'required' => 'required', 'placeholder' => __('Qty'), 'readonly']) }}
                                        </div>
                                    </td>
                                    <td></td>
                                    <td>
                                        <div class="form-group price-input input-group search-form">
                                            {{ Form::number('rate', $value->rate, ['class' => 'form-control rate', 'required' => 'required', 'placeholder' => __('Rate'), 'readonly']) }}
                                            <span
                                                class="input-group-text bg-transparent">{{ \Auth::user()->currencySymbol() }}</span>
                                        </div>
                                    </td>

                                    <td class="text-end amount">0.00</td>
                                </tr>

                            </tbody>
                        @endforeach

                        <tfoot>
                            <tr>
                                <td>
                                    {{ Form::label('document', __('View Additional Document'), ['class' => 'form-label']) }}
                                    @if (isset($requisition->document))
                                        <div class="action-btn bg-secondary ms-2">
                                            <a class="mx-3 btn btn-sm align-items-center"
                                                href="{{ URL::to('/') }}/storage/requisition/{{ $requisition->employee_id . '/' . $requisition->document }}"
                                                target="_blank">
                                                <i class="ti ti-crosshair text-white" data-bs-toggle="tooltip"
                                                    data-bs-original-title="{{ __('Preview Document') }}"></i>
                                            </a>
                                        </div>
                                    @endif
                                </td>

                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td class="blue-text"><strong>{{ __('Total Amount') }}
                                        ({{ \Auth::user()->currencySymbol() }})</strong></td>
                                <td class="text-end blue-text">{{ $requisition->totalAmount() }}</td>
                                <td></td>
                            </tr>
                            @if ($requisition->hod_approval == 'Pending')
                                <tr>
                                    @can('manage hod remark')
                                        <td>

                                            <div>
                                                {{-- <div class="form-group">
                                                    {{ Form::label('hod_remark', __('HOD Remark'), ['class' => 'form-label']) }}
                                                    {!! Form::text('hod_remark', '', ['class' => 'form-control']) !!}
                                                </div> --}}
                                            </div>

                                        </td>
                                    @endcan
                                </tr>
                            @endif
                            @if ($requisition->hod_approval == 'Approved' && $requisition->admin_approval == 'Pending')
                                <tr>
                                    <td>
                                        <div class="row">
                                            {{-- <div class="col-md-12">
                                                <div class="form-group">
                                                    {{ Form::label('hod_remark', __('HOD Remark: '), ['class' => 'form-label']) }}
                                                    {!! Form::textarea('hod_remark', $requisition->hod_remark, ['class' => 'form-control', 'readonly']) !!}
                                                </div>
                                            </div> --}}
                                            @can('manage admin remark')
                                                {{-- <div class="col-md-12">
                                                    <div class="form-group">
                                                        {{ Form::label('admin_remark', __('Admin Remark'), ['class' => 'form-label']) }}
                                                        {!! Form::text('admin_remark', '', ['class' => 'form-control']) !!}
                                                    </div>
                                                </div> --}}
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endif
                            @if (
                                $requisition->hod_approval == 'Approved' &&
                                    $requisition->admin_approval == 'Approved' &&
                                    $requisition->chairman_approval == 'Pending')
                                <tr>
                                    <td>
                                        <div class="col-md-12">
                                            {{-- <div class="form-group">
                                                {{ Form::label('hod_remark', __('HOD Remark: '), ['class' => 'form-label']) }}
                                                {!! Form::textarea('hod_remark', $requisition->hod_remark, ['class' => 'form-control', 'readonly']) !!}
                                            </div> --}}
                                        </div>
                                        <div class="col-md-12">
                                            {{-- <div class="form-group">
                                                {{ Form::label('admin_remark', __('Admin Remark: '), ['class' => 'form-label']) }}
                                                {!! Form::textarea('admin_remark', $requisition->admin_remark, ['class' => 'form-control', 'readonly']) !!}
                                            </div> --}}
                                        </div>
                                        @can('manage chairman remark')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    {{ Form::label('chairman_remark', __('Chairman Remark'), ['class' => 'form-label']) }}
                                                    {!! Form::text('chairman_remark', '', ['class' => 'form-control']) !!}
                                                </div>
                                            </div>
                                        @endcan
                                    </td>
                                </tr>
                            @endif
                            @if (
                                $requisition->hod_approval == 'Approved' &&
                                    $requisition->admin_approval == 'Approved' &&
                                    $requisition->chairman_approval == 'Approved')
                                <tr>
                                    <td>
                                       {{--  <div class="col-md-12">
                                            <div class="form-group">
                                                {{ Form::label('hod_remark', __('HOD Remark: '), ['class' => 'form-label']) }}
                                                {!! Form::textarea('hod_remark', $requisition->hod_remark, ['class' => 'form-control', 'readonly']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {{ Form::label('admin_remark', __('Admin Remark: '), ['class' => 'form-label']) }}
                                                {!! Form::textarea('admin_remark', $requisition->admin_remark, ['class' => 'form-control', 'readonly']) !!}
                                            </div>
                                        </div> --}}
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {{ Form::label('chairman_remark', __('Chairman Remark'), ['class' => 'form-label']) }}
                                                {!! Form::textarea('chairman_remark', $requisition->chairman_remark, ['class' => 'form-control', 'readonly']) !!}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endif

                            {{-- <tr>
                                <td>
                                    <strong>{{ __('HOD Approval: ') }}</strong>
                                    {{ $requisition->hod_approval }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>{{ __('Admin Approval: ') }}</strong>
                                    {{ $requisition->admin_approval }}
                                </td>
                            </tr> --}}
                            <tr>
                                <td>
                                    <strong>{{ __('Chairman Approval: ') }}</strong>
                                    {{ $requisition->chairman_approval }}
                                </td>
                            </tr>
                        </tfoot>
                        <input type="hidden" value="{{ $requisition->id }}" name="requisition_id">
                    </table>
                </div>
            </div>
        </div>
    </div>


@if (
    $requisition->hod_approval == 'Approved' &&
        $requisition->admin_approval == 'Approved' &&
        $requisition->chairman_approval == 'Approved' &&
        $requisition->payment_status !== 'Paid')
    @can('manage payment status')
        <div class="form-group row col-md-12 p-5">
            <div class="form-group col-md-61">
                {{ Form::label('expense_type', __('Expense Type'), ['class' => 'form-label']) }}
                {{ Form::select('expense_type', ['' => 'Select...', '1' => 'Project', '2' => 'Admin'], null, ['class' => 'form-control select', 'required' => 'required', 'id' => 'expense_type']) }}
            </div>
            <div class="form-group col-md-61">
                {{ Form::label('account_id', __('Account'), ['class' => 'form-label']) }}
                {{ Form::select('account_id', $accounts, null, ['class' => 'form-control select', 'required' => 'required']) }}
            </div>
            <div class="form-group col-md-61">
                {{ Form::label('vender_id', __('Vendor'), ['class' => 'form-label']) }}
                {{ Form::select('vender_id', $venders, null, ['class' => 'form-control select', 'required' => 'required']) }}
            </div>
            <div id="client_dropdown" class="form-group col-md-61 d-none">
                {{ Form::label('client_id', __('Customer'), ['class' => 'form-label']) }}
                {{ Form::select('client_id', $customers, null, ['class' => 'form-control select', 'id' => 'client_id']) }}
            </div>
            <div id="project_dropdown" class="form-group col-md-61 d-none">
                {{ Form::label('project_id', __('Project'), ['class' => 'form-label']) }}
                {{ Form::select('project_id', ['' => 'Select...'], null, ['class' => 'form-control select', 'id' => 'project_id']) }}
            </div>
            <div id="department_dropdown" class="form-group col-md-61 d-none">
                {{ Form::label('department_id', __('Department'), ['class' => 'form-label']) }}
                {{ Form::select('department_id', $departments, null, ['class' => 'form-control select', 'id' => 'department_id']) }}
            </div>
            <div class="form-group row">
                <div id="expense_head_debit" class="form-group col-md-61">
                    {{ Form::label('expense_head_debit', __('Expense Head (Debit)'), ['class' => 'form-label']) }}
                    {{ Form::select('expense_head_debit', $chart_of_accounts, null, ['class' => 'form-control select', 'required', 'id' => 'expense_head_debit']) }}
                </div>
                <div id="expense_head_credit" class="form-group col-md-61">
                    {{ Form::label('expense_head_credit', __('Expense Head (Credit)'), ['class' => 'form-label']) }}
                    {{ Form::select('expense_head_credit', $chart_of_accounts, null, ['class' => 'form-control select', 'required', 'id' => 'expense_head_credit']) }}
                </div>
            </div>
        </div>
    @endcan
@endif
</div>
{{-- @if ($requisition->hod_approval == 'Pending')
    @can('manage hod approval')
        <div class="modal-footer">
            <h3>HOD Approval: </h3>
            <input type="submit" value="{{ __('Approved') }}" class="btn btn-success" data-bs-dismiss="modal"
                name="hod_approval">
            <input type="submit" value="{{ __('Rejected') }}" class="btn btn-danger" name="hod_approval">
        </div>
    @endcan
@endif --}}
@if ($requisition->hod_approval == 'Approved' && $requisition->admin_approval == 'Pending')
    @can('manage admin approval')
        <div class="modal-footer">
            <h3>MD/COO Approval: </h3>
            <input type="submit" value="{{ __('Approved') }}" class="btn btn-success" data-bs-dismiss="modal"
                name="admin_approval">
            <input type="submit" value="{{ __('Rejected') }}" class="btn btn-danger" name="admin_approval">
        </div>
    @endcan
@endif
@if (
    $requisition->hod_approval == 'Approved' &&
        $requisition->admin_approval == 'Approved' &&
        $requisition->chairman_approval == 'Pending')
    @can('manage chairman approval')
    <h3>Chairman Approval: </h3>
        <div class="modal-footer">
            
            <input type="submit" value="{{ __('Approve') }}" class="btn btn-success" data-bs-dismiss="modal"
                name="chairman_approval">
            <input type="submit" value="{{ __('Reject') }}" class="btn btn-danger" name="chairman_approval">
        </div>
    @endcan
@endif
@if ($requisition->chairman_approval == 'Approved' && $requisition->payment_status !== 'Paid')
    @can('manage payment status')
        <div class="modal-footer">
            <h4>Finance Payment: </h4>
            <input type="submit" value="{{ __('Paid') }}" class="btn btn-success" data-bs-dismiss="modal"
                name="payment_status">
        </div>
    @endcan
@endif
{{ Form::close() }}



