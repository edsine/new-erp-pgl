{{Form::open(array('url'=>'leave/changeaction','method'=>'post'))}}
<div class="modal-body">
    <div class="row">
        <div class="col-12">
                <table class="table modal-table">
                    <tr role="row">
                        <th>{{__('Employee')}}</th>
                        <td>{{ !empty($employee->name)?$employee->name:'' }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Reliever')}}</th>
                        <td>{{ !empty($reliever->name)?$reliever->name:'' }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Leave Type ')}}</th>
                        <td>{{ !empty($leavetype->title)?$leavetype->title:'' }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Appplied On')}}</th>
                        <td>{{\Auth::user()->dateFormat( $leave->applied_on) }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Start Date')}}</th>
                        <td>{{ \Auth::user()->dateFormat($leave->start_date) }}</td>
                    </tr>
                    <tr>
                        <th>{{__('End Date')}}</th>
                        <td>{{ \Auth::user()->dateFormat($leave->end_date) }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Leave Description')}}</th>
                        <td>{{ !empty($leave->leave_reason)?$leave->leave_reason:'' }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Status')}}</th>
                        <td>{{ !empty($leave->status)?$leave->status:'' }}</td>
                    </tr>
                    <input type="hidden" value="{{ $leave->id }}" name="leave_id">
                </table>
        </div>

    </div>
</div>
@if($leave->status == 'Pending')
<div class="modal-footer">
    <input type="submit" value="{{__('Approved')}}" class="btn btn-success" data-bs-dismiss="modal" name="status">
    <input type="submit" value="{{__('Under Review')}}" class="btn btn-info" name="status">
    <input type="submit" value="{{__('Rejected')}}" class="btn btn-danger" name="status">
</div>
@elseif($leave->status == 'Under Review')
<div class="modal-footer">
    <input type="submit" value="{{__('Approved')}}" class="btn btn-success" data-bs-dismiss="modal" name="status">
    <input type="submit" value="{{__('Under Review')}}" class="btn btn-info" name="status">
    <input type="submit" value="{{__('Rejected')}}" class="btn btn-danger" name="status">
</div>
@endif
{{Form::close()}}
