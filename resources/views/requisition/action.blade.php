{{Form::open(array('url'=>'requisition/changeaction','method'=>'post'))}}
<div class="modal-body">
    <div class="row">
        <div class="col-12">
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('employee_id', __('Employee'), ['class' => 'form-label']) }}
                                    {!! Form::text('', $employee->name, ['class' => 'form-control disabled', 'readonly']) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('requisition_date', __('Issue Date'), ['class' => 'form-label']) }}
                                    <div class="form-icon-user">
                                        {{ Form::date('requisition_date', $requisition->requisition_date, ['class' => 'form-control', 'required' => 'required','readonly']) }}

                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('ref_number', __('Reference Number'), ['class' => 'form-label']) }}
                                        <div class="form-icon-user">
                                            <span><i class="ti ti-joint"></i></span>
                                            {{ Form::text('ref_number', $requisition->ref_number, ['class' => 'form-control', 'readonly']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        {{ Form::label('title', __('Requisition Title'), ['class' => 'form-label']) }}
                                        {!! Form::text('title', $requisition->title, ['class' => 'form-control','readonly']) !!}
                                    </div>
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
                                        <td width="45%" >
                                            {{ Form::text('item', $value->item, ['class' => 'form-control item', 'required' => 'required', 'placeholder' => __('Item'), 'readonly']) }}
                                        </td>
                                        <td></td>
                                        <td width="15%">
                                            <div class="form-group price-input input-group search-form">
                                                {{ Form::number('quantity', $value->quantity, ['class' => 'form-control quantity', 'required' => 'required', 'placeholder' => __('Qty') , 'readonly']) }}
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
                                        @if(isset($requisition->document))
                                        <div class="action-btn bg-secondary ms-2">
                                            <a class="mx-3 btn btn-sm align-items-center" href="{{ URL::to('/') }}/storage/requisition/{{$requisition->employee_id . '/' . $requisition->document }}" target="_blank"  >
                                                <i class="ti ti-crosshair text-white" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Preview Document') }}"></i>
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
                                @if($requisition->hod_approval == 'Pending')
                                <tr>
                                    @can('manage hod remark')
                                    <td>
                                        
                                        <div>
                                            <div class="form-group">
                                                {{Form::label('hod_remark',__('HOD Remark') ,['class'=>'form-label'])}}
                                                {!! Form::text('hod_remark', '', array('class' => 'form-control')) !!}
                                            </div>
                                        </div>
                                      
                                    </td>
                                    @endcan
                                </tr>
                                @endif
                                @if($requisition->hod_approval == 'Approved' && $requisition->admin_approval == 'Pending')
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    {{Form::label('hod_remark',__('HOD Remark: ') ,['class'=>'form-label'])}}
                                                    {!! Form::textarea('hod_remark', $requisition->hod_remark, array('class' => 'form-control', 'readonly')) !!}
                                                </div>
                                            </div>
                                            @can('manage admin remark')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    {{Form::label('admin_remark',__('Admin Remark') ,['class'=>'form-label'])}}
                                                    {!! Form::text('admin_remark', '', array('class' => 'form-control')) !!}
                                                </div>
                                            </div>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @endif
                                @if($requisition->hod_approval == 'Approved' && $requisition->admin_approval == 'Approved' && $requisition->chairman_approval == 'Pending')
                                <tr>
                                    <td>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {{Form::label('hod_remark',__('HOD Remark: ') ,['class'=>'form-label'])}}
                                                {!! Form::textarea('hod_remark', $requisition->hod_remark, array('class' => 'form-control', 'readonly')) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {{Form::label('admin_remark',__('Admin Remark: ') ,['class'=>'form-label'])}}
                                                {!! Form::textarea('admin_remark', $requisition->admin_remark, array('class' => 'form-control', 'readonly')) !!}
                                            </div>
                                        </div>
                                        @can('manage chairman remark')
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {{Form::label('chairman_remark',__('Chairman Remark') ,['class'=>'form-label'])}}
                                                {!! Form::text('chairman_remark', '', array('class' => 'form-control')) !!}
                                            </div>
                                        </div>
                                        @endcan
                                    </td>
                                </tr>
                                @endif
                                @if($requisition->hod_approval == 'Approved' && $requisition->admin_approval == 'Approved' && $requisition->chairman_approval == 'Approved')
                                <tr>
                                    <td>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {{Form::label('hod_remark',__('HOD Remark: ') ,['class'=>'form-label'])}}
                                                {!! Form::textarea('hod_remark', $requisition->hod_remark, array('class' => 'form-control', 'readonly')) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {{Form::label('admin_remark',__('Admin Remark: ') ,['class'=>'form-label'])}}
                                                {!! Form::textarea('admin_remark', $requisition->admin_remark, array('class' => 'form-control', 'readonly')) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {{Form::label('chairman_remark',__('Chairman Remark') ,['class'=>'form-label'])}}
                                                {!! Form::textarea('chairman_remark', $requisition->chairman_remark, array('class' => 'form-control', 'readonly')) !!}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                                <tr>
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
                                </tr>
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
</div>
@if($requisition->hod_approval == 'Pending')
@can('manage hod approval')
<div class="modal-footer">
    <h3>HOD Approval: </h3>
    <input type="submit" value="{{__('Approved')}}" class="btn btn-success" data-bs-dismiss="modal" name="hod_approval">
    <input type="submit" value="{{__('Rejected')}}" class="btn btn-danger" name="hod_approval">
</div>
@endcan
@endif
@if($requisition->hod_approval == 'Approved' && $requisition->admin_approval == 'Pending')
@can('manage admin approval')
<div class="modal-footer">
    <h3>Admin Approval: </h3>
    <input type="submit" value="{{__('Approved')}}" class="btn btn-success" data-bs-dismiss="modal" name="admin_approval">
    <input type="submit" value="{{__('Rejected')}}" class="btn btn-danger" name="admin_approval">
</div>
@endcan
@endif
@if($requisition->hod_approval == 'Approved' && $requisition->admin_approval == 'Approved' && $requisition->chairman_approval == 'Pending')
@can('manage chairman approval')
<div class="modal-footer">
    <h3>Chairman Approval: </h3>
    <input type="submit" value="{{__('Approved')}}" class="btn btn-success" data-bs-dismiss="modal" name="chairman_approval">
    <input type="submit" value="{{__('Rejected')}}" class="btn btn-danger" name="chairman_approval">
</div>
@endcan
@endif
@if($requisition->chairman_approval == 'Approved' && $requisition->payment_status !== 'Paid')
@can('manage payment status')
<div class="modal-footer">
<h4>Finance Payment: </h4>
    <input type="submit" value="{{__('Paid')}}" class="btn btn-success" data-bs-dismiss="modal" name="payment_status">
</div>
@endcan
@endif
{{Form::close()}}
