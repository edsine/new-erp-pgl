@extends('layouts.admin')
@section('page-title')
    {{ __('Expenses for ' . $month_name) }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Expense') }}</li>
@endsection

@section('action-btn')
    <div class="float-end">
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card">
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Account') }}</th>
                                    <th>{{ __('Vendor') }}</th>
                                    <th>{{ __('Client') }}</th>
                                    <th>{{ __('Project') }}</th>
                                    <th>{{ __('Department') }}</th>
                                    <th>{{ __('Ledger Accounts') }}</th>
                                    <th>{{ __('Reference') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    <th>{{ __('Payment Receipt') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $paymentpath = \App\Models\Utility::get_file('uploads/payment');
                                @endphp


                                @foreach ($payments as $payment)
                                    <tr class="font-style">
                                        <td>{{ Auth::user()->dateFormat($payment->date) }}</td>
                                        <td>{{ Auth::user()->priceFormat($payment->amount) }}</td>
                                        <td>{{ !empty($payment->bankAccount) ? $payment->bankAccount->bank_name . ' ' . $payment->bankAccount->holder_name : '' }}
                                        </td>
                                        <td>{{ !empty($payment->vender) ? $payment->vender->name : '-' }}</td>
                                        <td>{{ !empty($payment->client) ? $payment->client->name : '-' }}</td>
                                        <td>{{ !empty($payment->project) ? $payment->project->project_name : '-' }}</td>
                                        <td>{{ !empty($payment->department) ? $payment->department->name : '-' }}</td>
                                        {{-- <td>{{  !empty($payment->category)?$payment->category->name:'-'}}</td> --}}
                                        <td>{{ !empty($payment->expenseHeadDebit) ? $payment->expenseHeadDebit->name : '-' }}
                                            (DR)
                                            <br>
                                            {{ !empty($payment->expenseHeadDebit) ? $payment->expenseHeadCredit->name : '-' }}
                                            (CR)
                                        </td>
                                        <td>{{ !empty($payment->reference) ? $payment->reference : '-' }}</td>
                                        <td>{{ !empty($payment->description) ? $payment->description : '-' }}</td>
                                        <td>
                                            @if (!empty($payment->add_receipt))
                                                <a class="action-btn bg-primary ms-2 btn btn-sm align-items-center"
                                                    href="{{ $paymentpath . '/' . $payment->add_receipt }}" download="">
                                                    <i class="ti ti-download text-white"></i>
                                                </a>
                                                <a href="{{ $paymentpath . '/' . $payment->add_receipt }}"
                                                    class="action-btn bg-secondary ms-2 mx-3 btn btn-sm align-items-center"
                                                    data-bs-toggle="tooltip" title="{{ __('Download') }}"
                                                    target="_blank"><span class="btn-inner--icon"><i
                                                            class="ti ti-crosshair text-white"></i></span></a>
                                            @else
                                                -
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
