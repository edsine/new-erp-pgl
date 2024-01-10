@extends('layouts.admin')

@section('page-title')
    {{ __('Leave Manager') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Leave Approvals') }}</li>
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
                                    <th>{{ __('Leave Type') }}</th>
                                    <th>{{ __('Applied On') }}</th>
                                    <th>{{ __('Requested By') }}</th>
                                    <th>{{ __('Start Date') }}</th>
                                    <th>{{ __('End Date') }}</th>
                                    <th>{{ __('Total Days') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="font-style">
                                @foreach ($leaves as $leave)
                                    <tr>
                                        {{-- @dd($item) --}}
                                        <td> {{ $leave->leaveType->title }} </td>
                                        <td>{{ \Auth::user()->dateFormat($leave->applied_on) }}</td>
                                        <td>{{ $leave->employees->name }}</td>
                                        <td>{{ \Auth::user()->dateFormat($leave->start_date) }}</td>
                                        <td>{{ \Auth::user()->dateFormat($leave->end_date) }}</td>
                                        @php
                                            $startDate = new \DateTime($leave->start_date);
                                            $endDate = new \DateTime($leave->end_date);

                                            $total_leave_days = 0;

                                            // Iterate through each day between start and end dates
                                            $interval = new \DateInterval('P1D');
                                            $period = new \DatePeriod($startDate, $interval, $endDate->modify('+1 day'));

                                            foreach ($period as $day) {
                                                // Check if the day is not Saturday (6) or Sunday (0)
                                                if ($day->format('N') < 6) {
                                                    $total_leave_days++;
                                                }
                                            }
                                        @endphp
                                        <td>{{ $total_leave_days }}</td>
                                        <td>

                                            @if ($leave->hod_approval == 'Pending')
                                                Awaiting HOD Approval
                                            @elseif($leave->admin_approval == 'Pending')
                                                Awaiting Admin Approval
                                            @elseif($leave->chairman_approval == 'Pending')
                                                Awaiting Chairman Approval
                                            @elseif($leave->chairman_approval == 'Approved')
                                                Leave Approved
                                            @endif
                                        </td>
                                        <td>
                                            <div class="action-btn bg-info ms-2">
                                                <a href="#"
                                                    data-url="{{ URL::to('staff_leave/' . $leave->id . '/view') }}"
                                                    data-size="lg" data-ajax-popup="true"
                                                    data-title="{{ __('Leave Action') }}"
                                                    class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip"
                                                    title="{{ __('View Approval') }}"
                                                    data-original-title="{{ __('View Approval') }}">
                                                    <i class="ti ti-caret-right text-white"></i> </a>
                                            </div>
                                            <div class="action-btn bg-primary ms-2">
                                                <a href="{{ route('staff_leave.edit', $leave->id) }}" data-size="lg"
                                                    class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip"
                                                    title="{{ __('Edit') }}"
                                                    data-original-title="{{ __('Edit') }}"><i
                                                        class="ti ti-pencil text-white"></i></a>
                                            </div>

                                            <div class="action-btn bg-danger ms-2">
                                                {!! Form::open([
                                                    'method' => 'DELETE',
                                                    'route' => ['staff_leave.destroy', $leave->id],
                                                    'id' => 'delete-form-' . $leave->id,
                                                ]) !!}

                                                <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para"
                                                    data-bs-toggle="tooltip" title="{{ __('Delete') }}"
                                                    data-original-title="{{ __('Delete') }}"
                                                    data-confirm="{{ __('Are You Sure?') . '|' . __('This action can not be undone. Do you want to continue?') }}"
                                                    data-confirm-yes="document.getElementById('delete-form-{{ $leave->id }}').submit();"><i
                                                        class="ti ti-trash text-white"></i></a>
                                                {!! Form::close() !!}
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
