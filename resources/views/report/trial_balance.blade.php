@extends('layouts.admin')
@section('page-title')
    {{ __('Trial Balance') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Trial Balance') }}</li>
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

        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('tbl_exporttable_to_xls');
            var wb = XLSX.utils.table_to_book(elt, {
                sheet: "sheet1"
            });
            return dl ?
                XLSX.write(wb, {
                    bookType: type,
                    bookSST: true,
                    type: 'base64'
                }) :
                XLSX.writeFile(wb, fn || ('Trail_Balance.' + (type || 'xlsx')));
        }
    </script>
@endpush

@section('action-btn')
    <div class="float-end">
        <a href="#" class="btn btn-sm btn-primary" onclick="saveAsPDF()"data-bs-toggle="tooltip"
            title="{{ __('Download') }}" data-original-title="{{ __('Download') }}">
            <span class="btn-inner--icon"><i class="ti ti-download"></i></span>
        </a>

        <a href="#" class="btn btn-sm btn-primary" onclick="ExportToExcel('xlsx')"data-bs-toggle="tooltip"
            title="{{ __('Excel') }}" data-original-title="{{ __('Excel') }}">
            <span class="btn-inner--icon"><i class="ti ti-file-export"></i></span>
        </a>

    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(['route' => ['trial.balance'], 'method' => 'GET', 'id' => 'report_trial_balance']) }}
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
                                            {{ Form::label('start_date', __('Start Date'), ['class' => 'form-label']) }}
                                            {{ Form::date('start_date', $filter['startDateRange'], ['class' => 'month-btn form-control']) }}
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            {{ Form::label('end_date', __('End Date'), ['class' => 'form-label']) }}
                                            {{ Form::date('end_date', $filter['endDateRange'], ['class' => 'month-btn form-control']) }}
                                        </div>
                                    </div>


                                </div>
                            </div>
                            <div class="col-auto mt-4">
                                <div class="row">
                                    <div class="col-auto">

                                        <a href="#" class="btn btn-sm btn-primary"
                                            onclick="document.getElementById('report_trial_balance').submit(); return false;"
                                            data-bs-toggle="tooltip" title="{{ __('Apply') }}"
                                            data-original-title="{{ __('apply') }}">
                                            <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                        </a>

                                        <a href="{{ route('trial.balance') }}" class="btn btn-sm btn-danger "
                                            data-bs-toggle="tooltip" title="{{ __('Reset') }}"
                                            data-original-title="{{ __('Reset') }}">
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
                <input type="hidden"
                    value="{{ __('Trial Balance') . ' ' . 'Report of' . ' ' . $filter['startDateRange'] . ' to ' . $filter['endDateRange'] }}"
                    id="filename">
                <div class="card p-4 mb-4">
                    <h6 class="mb-0">{{ __('Report') }} :</h6>
                    <h7 class="text-sm mb-0">{{ __('Trial Balance Summary') }}</h7>
                </div>
            </div>

            <div class="col">
                <div class="card p-4 mb-4">
                    <h6 class="mb-0">{{ __('Duration') }} :</h6>
                    <h7 class="text-sm mb-0">{{ $filter['startDateRange'] . ' to ' . $filter['endDateRange'] }}</h7>
                </div>
            </div>
        </div>
        @if (!empty($account))
            <div class="row mt-4">
                <div class="col">
                    <div class="card p-4 mb-4">
                        <h6 class="mb-0">{{ __('Total Credit') }} :</h6>
                        <h7 class="text-sm mb-0">0</h7>
                    </div>
                </div>
                <div class="col">
                    <div class="card p-4 mb-4">
                        <h6 class="mb-0">{{ __('Total Debit') }} :</h6>
                        <h7 class="text-sm mb-0">0</h7>
                    </div>
                </div>
            </div>
        @endif
        <div class="row mb-4">
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table id="tbl_exporttable_to_xls" class="table table-flush">
                                <thead>
                                    <tr>
                                        <th> {{ __('Account Name') }}</th>
                                        <th> {{ __('Debit Total') }}</th>
                                        <th> {{ __('Credit Total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $debitTotal = 0;
                                        $creditTotal = 0;
                                    @endphp
                                    @foreach ($journalItem as $item)
                                        <tr>
                                            <td>
                                                {{ Form::open(['route' => ['report.ledger'], 'method' => 'GET', 'id' => 'report_ledger_' . $item['id']]) }}
                                                <a href="#" class="btn btn-sm btn-primary"
                                                    onclick="document.getElementById('report_ledger_{{ $item['id'] }}').submit(); return false;"
                                                    data-bs-toggle="tooltip" title="{{ $item['name'] }}"
                                                    data-original-title="{{ $item['name'] }}">
                                                    <span class="btn-inner--icon">{{ $item['name'] }}</span>
                                                </a>
                                                {{ Form::hidden('account', $item['id']) }}
                                                {{ Form::close() }}
                                            </td>
                                            <td>
                                                @if ($item['netAmount'] < 0)
                                                    @php
                                                        $debitTotal += abs($item['netAmount']);
                                                    @endphp
                                                    {{ \Auth::user()->priceFormat(abs($item['netAmount'])) }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if ($item['netAmount'] > 0)
                                                    @php
                                                        $creditTotal += $item['netAmount'];
                                                    @endphp
                                                    {{ \Auth::user()->priceFormat($item['netAmount']) }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfooter>
                                    <td class="text-dark">{{ __('Total') }}</td>
                                    <td class="text-dark">{{ \Auth::user()->priceFormat($debitTotal) }}</td>
                                    <td class="text-dark">{{ \Auth::user()->priceFormat($creditTotal) }}</td>
                                </tfooter>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
