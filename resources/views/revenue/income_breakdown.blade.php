@extends('layouts.admin')
@section('page-title')
    {{ __('Revenues for ' . $month_name) }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Revenue') }}</li>
@endsection

@section('action-btn')
    <div class="float-end">

        @can('create revenue')
            <a href="#" data-url="{{ route('revenue.create') }}" data-size="lg" data-ajax-popup="true"
                data-title="{{ __('Create New Revenue') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
                title="{{ __('Create') }}">
                <i class="ti ti-plus"></i>
            </a>
        @endcan

    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="mt-2 " id="multiCollapseExample1">
                <div class="card">
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style mt-2">
                    <h5></h5>
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th> {{ __('Date') }}</th>
                                    <th> {{ __('Amount') }}</th>
                                    <th> {{ __('Account') }}</th>
                                    <th> {{ __('Customer') }}</th>
                                    <th> {{ __('Project') }}</th>
                                    <th> {{ __('Ledger Accounts') }}</th>
                                    <th> {{ __('Reference') }}</th>
                                    <th> {{ __('Description') }}</th>
                                    <th>{{ __('Payment Receipt') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $revenuepath = \App\Models\Utility::get_file('uploads/revenue');
                                @endphp
                                @foreach ($revenues as $revenue)
                                    <tr class="font-style">
                                        <td>{{ Auth::user()->dateFormat($revenue->date) }}</td>
                                        <td>{{ Auth::user()->priceFormat($revenue->amount) }}</td>
                                        <td>{{ !empty($revenue->bankAccount) ? $revenue->bankAccount->bank_name . ' ' . $revenue->bankAccount->holder_name : '' }}
                                        </td>
                                        <td>{{ !empty($revenue->customer) ? $revenue->customer->name : '-' }}</td>
                                        <td>{{ !empty($revenue->project) ? $revenue->project->project_name : '-' }}</td>
                                        {{-- <td>{{ !empty($revenue->category) ? $revenue->category->name : '-' }}</td> --}}
                                        <td>{{ !empty($revenue->expenseHeadDebit) ? $revenue->expenseHeadDebit->name : '-' }}
                                            (DR)
                                            <br>
                                            {{ !empty($revenue->expenseHeadDebit) ? $revenue->expenseHeadCredit->name : '-' }}
                                            (CR)
                                        </td>
                                        <td>{{ !empty($revenue->reference) ? $revenue->reference : '-' }}</td>
                                        <td>{{ !empty($revenue->description) ? $revenue->description : '-' }}</td>
                                        <td>
                                            @if (!empty($revenue->add_receipt))
                                                <a class="action-btn bg-primary ms-2 btn btn-sm align-items-center"
                                                    href="{{ $revenuepath . '/' . $revenue->add_receipt }}" download="">
                                                    <i class="ti ti-download text-white"></i>
                                                </a>
                                                <a href="{{ $revenuepath . '/' . $revenue->add_receipt }}"
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
