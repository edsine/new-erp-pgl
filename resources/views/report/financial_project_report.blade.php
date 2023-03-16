@extends('layouts.admin')
@section('page-title')
    {{ __('Financial Project Report') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Financial Project Report') }}</li>
@endsection
@push('script-page')
    <script type="text/javascript" src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
    <script>
        var filename = $('#filename').val();

        function saveAsPDF() {
            var element = document.getElementById('printableArea');
            var opt = {
                margin: 0.3,
                filename: filename,
                image: {
                    type: 'jpeg',
                    quality: 1
                },
                html2canvas: {
                    scale: 4,
                    dpi: 72,
                    letterRendering: true
                },
                jsPDF: {
                    unit: 'in',
                    format: 'A2'
                }
            };
            html2pdf().set(opt).from(element).save();
        }
    </script>
@endpush

@section('action-btn')
    <div class="float-end">
        <a href="#" class="btn btn-sm btn-primary" onclick="saveAsPDF()"data-bs-toggle="tooltip"
            title="{{ __('Download') }}" data-original-title="{{ __('Download') }}">
            <span class="btn-inner--icon"><i class="ti ti-download"></i></span>
        </a>

    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(['route' => ['report.finacialprojectreport'], 'method' => 'GET', 'id' => 'report_financial_project_report']) }}
                        <div class="row align-items-center justify-content-end">
                            <div class="col-xl-10">
                                <div class="row">
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            {{ Form::label('project', __('Project'), ['class' => 'form-label']) }}
                                            {{ Form::select('project', $projects, $filter['project'], ['class' => 'month-btn form-control']) }}
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-auto mt-4">
                                <div class="row">
                                    <div class="col-auto">

                                        <a href="#" class="btn btn-sm btn-primary"
                                            onclick="document.getElementById('report_financial_project_report').submit(); return false;"
                                            data-bs-toggle="tooltip" title="{{ __('Apply') }}"
                                            data-original-title="{{ __('apply') }}">
                                            <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                        </a>

                                        <a href="{{ route('report.finacialprojectreport') }}"
                                            class="btn btn-sm btn-danger " data-bs-toggle="tooltip"
                                            title="{{ __('Reset') }}" data-original-title="{{ __('Reset') }}">
                                            <span class="btn-inner--icon"><i
                                                    class="ti ti-trash-off text-white-off "></i></span>
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>


    <div id="printableArea">
        <div class="row mt-2">
            <div class="col">
                <div class="card p-4 mb-4">
                    <h6 class="mb-0">{{ __('Project') }} :</h6>
                    <h7 class="text-sm mb-0">{{ !empty($data['project']) ? $data['project']->project_name : '' }}</h7>
                </div>
            </div>

            <div class="col">
                <div class="card p-4 mb-4">
                    <h6 class="mb-0">{{ __('Duration') }} :</h6>
                    <h7 class="text-sm mb-0">
                        {{ !empty($data['project']) ? $data['project']->start_date . ' to ' . $data['project']->end_date : '' }}
                    </h7>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table table-flush">
                                <thead>
                                    <tr>
                                        <th> {{ __('') }}</th>
                                        <th> {{ __('Debit Total') }}</th>
                                        <th> {{ __('Credit Total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Contract Sum</td>
                                        <td></td>
                                        <td>{{ !empty($data['contract_sum']) ? \Auth::user()->priceFormat($data['contract_sum']) : '0' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Local Tax</td>
                                        <td></td>
                                        <td>{{ !empty($data['tax_amount']) ? \Auth::user()->priceFormat($data['tax_amount']) : '0' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Net Contract Sum</td>
                                        <td></td>
                                        <td>{{ !empty($data['net_contract_sum']) ? \Auth::user()->priceFormat($data['net_contract_sum']) : '0' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Revenue received</td>
                                        <td>{{ !empty($data['revenue']) ? \Auth::user()->priceFormat($data['revenue']) : '0' }}</td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>Balance to be paid</td>
                                        <td>{{ !empty($data['balance_to_be_paid']) ? \Auth::user()->priceFormat($data['balance_to_be_paid']) : '0' }}</td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>Expected Net Profit</td>
                                        <td>{{ !empty($data['expected_net_profit']) ? \Auth::user()->priceFormat($data['expected_net_profit']) : '0' }}</td>
                                        <td></td>
                                    </tr>

                                </tbody>
                                <tfooter>
                                    <td class="text-dark"><strong>{{ __('Actual Net Profit') }}</strong></td>
                                    <td>{{ !empty($data['actual_net_profit']) ? ($data['actual_net_profit'] > 0 ? \Auth::user()->priceFormat($data['actual_net_profit']) : '') : '' }}</td>
                                    <td>({{ !empty($data['actual_net_profit']) ? ($data['actual_net_profit'] < 0 ? \Auth::user()->priceFormat(abs($data['actual_net_profit'])) : '') : '' }})</td>
                                </tfooter>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
