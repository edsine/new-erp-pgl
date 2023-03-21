{{ Form::model($revenue, ['route' => ['revenue.update', $revenue->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-6">
            {{ Form::label('revenue_type', __('Revenue Source'), ['class' => 'form-label']) }}
            {{ Form::select('revenue_type', ['' => 'Select...', '1' => 'Projects', '2' => 'Others'], null, ['class' => 'form-control select', 'required' => 'required', 'id' => 'revenue_type']) }}
        </div>
        <div class="form-group  col-md-6">
            {{ Form::label('date', __('Date'), ['class' => 'form-label']) }}
            {{ Form::date('date', null, ['class' => 'form-control', 'required' => 'required']) }}
        </div>
        <div class="form-group  col-md-6">
            {{ Form::label('amount', __('Amount'), ['class' => 'form-label']) }}
            {{ Form::number('amount', null, ['class' => 'form-control', 'required' => 'required', 'step' => '0.01']) }}
        </div>
        <div class="form-group  col-md-6">
            {{ Form::label('account_id', __('Account'), ['class' => 'form-label']) }}
            {{ Form::select('account_id', $accounts, null, ['class' => 'form-control select', 'required' => 'required']) }}
        </div>
        <div id="client_dropdown" class="form-group col-md-6 d-none">
            {{ Form::label('customer_id', __('Customer'), ['class' => 'form-label']) }}
            {{ Form::select('customer_id', $customers, null, ['class' => 'form-control select', 'required' => 'required', 'id' => 'client_id']) }}
        </div>
        <div id="project_dropdown" class="form-group col-md-6 d-none">
            {{ Form::label('project_id', __('Project'), ['class' => 'form-label']) }}
            {{ Form::select('project_id', ['' => 'Select...'], null, ['class' => 'form-control select', 'required' => 'required', 'id' => 'project_id']) }}
        </div>
        <div class="form-group row col-md-12">
            <div id="expense_head_debit" class="form-group col-md-6">
                {{ Form::label('expense_head_debit', __('Expense Head (Debit)'), ['class' => 'form-label']) }}
                {{ Form::select('expense_head_debit', $chart_of_accounts, null, ['class' => 'form-control select', 'id' => 'expense_head_debit']) }}
            </div>
            <div id="expense_head_credit" class="form-group col-md-6">
                {{ Form::label('expense_head_credit', __('Expense Head (Credit)'), ['class' => 'form-label']) }}
                {{ Form::select('expense_head_credit', $chart_of_accounts, null, ['class' => 'form-control select', 'id' => 'expense_head_credit']) }}
            </div>
        </div>
        <div class="form-group  col-md-12">
            {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}
            {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => 3]) }}
        </div>
        {{-- <div class="form-group  col-md-6">
            {{ Form::label('category_id', __('Category'), ['class' => 'form-label']) }}
            {{ Form::select('category_id', $categories, null, ['class' => 'form-control select', 'required' => 'required']) }}
        </div> --}}

        {{-- <div class="form-group  col-md-6">
            {{ Form::label('reference', __('Reference'), ['class' => 'form-label']) }}
            {{ Form::text('reference', null, ['class' => 'form-control']) }}

        </div> --}}


        <div class="form-group col-md-6">

            {{ Form::label('add_receipt', __('Payment Receipt'), ['class' => 'col-form-label']) }}
            {{ Form::file('add_receipt', ['class' => 'form-control', 'id' => 'files']) }}
            <img id="image" src="{{ asset(Storage::url('uploads/revenue')) . '/' . $revenue->add_receipt }}"
                class="mt-2" style="width:25%;" />
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn  btn-primary">
</div>
{{ Form::close() }}



<script>
    document.getElementById('client_id').onchange = function() {
        var clientId = $(this).val();
        $.ajax({
            url: '{{ route('customer.projects') }}',
            type: 'POST',
            data: {
                "client_id": clientId,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                $('#project_id').empty();
                $.each(data, function(key, value) {
                    $('#project_id').append('<option value="' + key + '">' + value +
                        '</option>');
                });
            }
        });
    }


    revenueType = document.getElementById('revenue_type').value;
    if (revenueType == 1) {
        const clientId = document.getElementById('client_id').value;
        $.ajax({
            url: '{{ route('customer.projects') }}',
            type: 'POST',
            data: {
                "client_id": clientId,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                const currentProjectId = "{{ $revenue->project_id }}";
                $('#project_id').empty();
                $.each(data, function(key, value) {
                    if (key == currentProjectId) {
                        $('#project_id').append('<option selected value="' + key + '">' + value +
                            '</option>');
                    } else {
                        $('#project_id').append('<option value="' + key + '">' + value +
                            '</option>');
                    }
                });
            }
        });
        document.getElementById('client_dropdown').classList.remove('d-none');
        document.getElementById('project_dropdown').classList.remove('d-none');
    }

    document.getElementById('files').onchange = function() {
        var src = URL.createObjectURL(this.files[0])
        document.getElementById('image').src = src
    }


    document.getElementById('revenue_type').onchange = function() {
        const value = this.value;

        if (value == 1) {
            document.getElementById('client_dropdown').classList.remove('d-none');
            document.getElementById('project_dropdown').classList.remove('d-none');
        } else {
            document.getElementById('client_dropdown').classList.add('d-none');
            document.getElementById('project_dropdown').classList.add('d-none');
        }
    }
</script>
