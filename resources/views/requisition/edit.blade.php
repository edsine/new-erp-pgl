@extends('layouts.admin')
@section('page-title')
    {{ __('Requisition Edit') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('requisition.index') }}">{{ __('Requisition') }}</a></li>
    <li class="breadcrumb-item">{{ __('Requisition Edit') }}</li>
@endsection
@push('script-page')
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/jquery.repeater.min.js') }}"></script>
    <script>
        var selector = "body";
        if ($(selector + " .repeater").length) {
            var $dragAndDrop = $("body .repeater tbody").sortable({
                handle: '.sort-handler'
            });
            var $repeater = $(selector + ' .repeater').repeater({
                initEmpty: false,
                defaultValues: {
                    'status': 1
                },
                show: function() {
                    $(this).slideDown();
                    var file_uploads = $(this).find('input.multi');
                    if (file_uploads.length) {
                        $(this).find('input.multi').MultiFile({
                            max: 3,
                            accept: 'png|jpg|jpeg',
                            max_size: 2048
                        });
                    }
                    if ($('.select2').length) {
                        $('.select2').select2();
                    }

                },
                hide: function(deleteElement) {
                    if (confirm('Are you sure you want to delete this element?')) {
                        $(this).slideUp(deleteElement);
                        $(this).remove();

                        var inputs = $(".amount");
                        var subTotal = 0;
                        for (var i = 0; i < inputs.length; i++) {
                            subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
                        }
                        $('.subTotal').html(subTotal.toFixed(2));
                        $('.totalAmount').html(subTotal.toFixed(2));
                    }
                },
                ready: function(setIndexes) {

                    $dragAndDrop.on('drop', setIndexes);
                },
                isFirstItemUndeletable: true
            });
            var value = $(selector + " .repeater").attr('data-value');
            if (typeof value != 'undefined' && value.length != 0) {
                value = JSON.parse(value);
                $repeater.setList(value);
            }

        }

        $(document).on('keyup', '.rate', function() {
            var el = $(this).parent().parent().parent().parent();
            var amount = $(this).val();

            var quantity = parseInt(el.find('.quantity').val());

            el.find('.amount').html((amount * quantity).toFixed(2));


            var inputs = $(".amount");
            var totalAmount = 0;
            for (var i = 0; i < inputs.length; i++) {
                totalAmount = parseFloat(totalAmount) + parseFloat(inputs[i].innerText);
            }

            $('.totalAmount').html(totalAmount.toFixed(2));

        });

        $(document).on('keyup', '.quantity', function() {
            var el = $(this).parent().parent().parent().parent();

            var quantity = parseInt($(this).val());
            var amount = parseFloat(el.find('.rate').val());
            // if (!isNaN(quantity)) {

            // }

            el.find('.amount').html((amount * quantity).toFixed(2));


            var inputs = $(".amount");
            var totalAmount = 0;
            for (var i = 0; i < inputs.length; i++) {
                totalAmount = parseFloat(totalAmount) + parseFloat(inputs[i].innerText);
            }

            $('.totalAmount').html(totalAmount.toFixed(2));

        });
    </script>
    <script>
        $(document).on('click', '[data-repeater-delete]', function() {
            $(".rate").change();
            $(".discount").change();
        });
    </script>
@endpush
@section('content')
    <div class="row">
        {{ Form::model($requisition, ['route' => ['requisition.update', $requisition->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'class' => 'w-100']) }}
        <div class="col-12">
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="col-md-6">
                                <div class="form-group">

                                    {{ Form::label('employee_id', __('Employee'), ['class' => 'form-label']) }}
                                    <input type="hidden" name="employee_id" class="form-control" required
                                        value="{{ $employee ? $employee->id : '' }}">
                                    {!! Form::text('', $employee ? $employee->name : '', ['class' => 'form-control disabled', 'readonly']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('requisition_date', __('Issue Date'), ['class' => 'form-label']) }}
                                        <div class="form-icon-user">
                                            {{ Form::date('requisition_date', $requisition->requisition_date, ['class' => 'form-control', 'required' => 'required']) }}

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('ref_number', __('Reference Number'), ['class' => 'form-label']) }}
                                        <div class="form-icon-user">
                                            <span><i class="ti ti-joint"></i></span>
                                            {{ Form::text('ref_number', $requisition->ref_number, ['class' => 'form-control', 'readonly']) }}
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('title', __('Requisition Title'), ['class' => 'form-label']) }}
                                    {!! Form::text('title', $requisition->title, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <h5 class=" d-inline-block mb-4">{{ __('Requisition') }}</h5>
            <div class="card repeater">
                <div class="item-section py-2">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-md-12 d-flex align-items-center justify-content-between justify-content-md-end">
                            <div class="all-button-box me-2">
                                <a href="#" data-repeater-create="" class="btn btn-primary" data-bs-toggle="modal"
                                    data-target="#add-bank">
                                    <i class="ti ti-plus"></i> {{ __('Add item') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-border-style mt-2">
                    <div class="table-responsive">
                        <table class="table  mb-0 table-custom-style" data-repeater-list="items" id="sortable-table">
                            <thead>
                                <tr>
                                    <th>{{ __('Items') }}</th>
                                    <th>{{ __('Quantity') }}</th>
                                    <th>{{ __('Rate') }} </th>
                                    <th class="text-end">{{ __('Amount') }} <br><small
                                            class="text-danger font-weight-bold"></small></th>
                                    <th></th>
                                </tr>
                            </thead>
                            @foreach ($requisitionItem as $value)
                                <tbody class="ui-sortable" data-repeater-item>
                                    {{-- @foreach ($requisitionItem as $value) --}}
                                    <tr>
                                        {{ Form::hidden('id', $value->id, []) }}
                                        <td width="25%" class="form-group pt-0">
                                            {{ Form::text('item', $value->item, ['class' => 'form-control item', 'required' => 'required', 'placeholder' => __('Item'), 'required' => 'required']) }}
                                        </td>
                                        <td>
                                            <div class="form-group price-input input-group search-form">
                                                {{ Form::number('quantity', $value->quantity, ['class' => 'form-control quantity', 'required' => 'required', 'placeholder' => __('Qty'), 'required' => 'required', 'step' => 'any']) }}
                                                <span class="unit input-group-text bg-transparent"></span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group price-input input-group search-form">
                                                {{ Form::number('rate', $value->rate, ['class' => 'form-control rate', 'required' => 'required', 'placeholder' => __('Rate'), 'required' => 'required', 'step' => 'any']) }}
                                                <span
                                                    class="input-group-text bg-transparent">{{ \Auth::user()->currencySymbol() }}</span>
                                            </div>
                                        </td>

                                        <td class="text-end amount">0.00</td>
                                        <td>
                                            <a href="#"
                                                class="ti ti-trash text-white repeater-action-btn bg-danger ms-2  delete_item"
                                                data-repeater-delete></a>
                                        </td>
                                    </tr>
                                    {{-- @endforeach --}}

                                </tbody>
                            @endforeach

                            <tfoot>
                                <tr>
                                    <td>
                                        {{ Form::label('document', __('Additional Document'), ['class' => 'form-label']) }}
                                        <div class="choose-file ">
                                            <label for="document" class="form-label">
                                                <input type="file" class="form-control" name="document" id="document"
                                                    data-filename="document_create">
                                                <img id="image" class="mt-3" style="width:25%;" />
                                            </label>
                                        </div>
                                        @if (isset($requisition->document))
                                            <div class="action-btn bg-secondary ms-2">
                                                <a class="mx-3 btn btn-sm align-items-center"
                                                    href="{{ URL::to('/') }}/storage/requisition/{{ $requisition->employee_id . '/' . $requisition->document }}"
                                                    target="_blank">
                                                    <i class="ti ti-crosshair text-white" data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('Preview Previous Document') }}"></i>
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
                                    <td class="blue-text"><strong>{{ __('Old Total Amount') }}
                                            ({{ \Auth::user()->currencySymbol() }})</strong></td>
                                    <td class="text-end blue-text">{{ $requisition->totalAmount() }}</td>
                                    <td></td>

                                    <td class="blue-text"><strong>{{ __('New Total Amount') }}
                                            ({{ \Auth::user()->currencySymbol() }})</strong></td>
                                    <td class="text-end blue-text totalAmount"></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">

            <input type="button" value="{{ __('Cancel') }}" onclick="location.href = '{{ route('requisition.index') }}'"
                class="btn btn-light">
            <input type="submit" value="{{ __('Edit') }}" class="btn  btn-primary">
        </div>
        {{ Form::close() }}
    </div>
@endsection
