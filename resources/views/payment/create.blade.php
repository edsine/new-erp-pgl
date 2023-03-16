{{ Form::open(['url' => 'payment', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-6">
            {{ Form::label('expense_type', __('Expense Type'), ['class' => 'form-label']) }}
            {{ Form::select('expense_type', ['' => 'Select...', '1' => 'Project', '2' => 'Admin'], null, ['class' => 'form-control select', 'required' => 'required', 'id' => 'expense_type']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('date', __('Date'), ['class' => 'form-label']) }}
            {{ Form::date('date', null, ['class' => 'form-control', 'required' => 'required']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('amount', __('Amount'), ['class' => 'form-label']) }}
            {{ Form::number('amount', '', ['class' => 'form-control', 'required' => 'required', 'step' => '0.01']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('account_id', __('Account'), ['class' => 'form-label']) }}
            {{ Form::select('account_id', $accounts, null, ['class' => 'form-control select', 'required' => 'required']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('vender_id', __('Vendor'), ['class' => 'form-label']) }}
            {{ Form::select('vender_id', $venders, null, ['class' => 'form-control select', 'required' => 'required']) }}
        </div>
        <div id="client_dropdown" class="form-group col-md-6 d-none">
            {{ Form::label('client_id', __('Customer'), ['class' => 'form-label']) }}
            {{ Form::select('client_id', $customers, null, ['class' => 'form-control select', 'id' => 'client_id']) }}
        </div>
        <div id="project_dropdown" class="form-group col-md-6 d-none">
            {{ Form::label('project_id', __('Project'), ['class' => 'form-label']) }}
            {{ Form::select('project_id', ['' => 'Select...'], null, ['class' => 'form-control select', 'id' => 'project_id']) }}
        </div>
        <div id="department_dropdown" class="form-group col-md-6 d-none">
            {{ Form::label('department_id', __('Department'), ['class' => 'form-label']) }}
            {{ Form::select('department_id', $departments, null, ['class' => 'form-control select', 'id' => 'department_id']) }}
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
            {{ Form::textarea('description', '', ['class' => 'form-control', 'rows' => 3]) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('category_id', __('Category'), ['class' => 'form-label']) }}
            {{ Form::select('category_id', $categories, null, ['class' => 'form-control select', 'required' => 'required']) }}
        </div>
        {{-- <div class="form-group col-md-6">
            {{ Form::label('reference', __('Reference'), ['class' => 'form-label']) }}
            {{ Form::text('reference', '', ['class' => 'form-control']) }}
        </div> --}}

        <div class="form-group col-md-6">
            {{ Form::label('add_receipt', __('Payment Receipt'), ['class' => 'col-form-label']) }}
            {{ Form::file('add_receipt', ['class' => 'form-control', 'id' => 'files']) }}
            <img id="image" class="mt-2" style="width:25%;" />
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>

{{ Form::close() }}


<script>
    document.getElementById('client_id').onchange = function() {
        var clientId = $(this).val();
        console.log(clientId);
        $.ajax({
            url: '{{ route('customer.projects') }}',
            type: 'POST',
            data: {
                "client_id": clientId,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                console.log(data);
                $('#project_id').empty();
                $.each(data, function(key, value) {
                    $('#project_id').append('<option value="' + key + '">' + value +
                        '</option>');
                });
            }
        });
    }

    document.getElementById('files').onchange = function() {
        var src = URL.createObjectURL(this.files[0])
        document.getElementById('image').src = src
    }


    document.getElementById('expense_type').onchange = function() {
        const value = this.value;

        if (value == 1) {
            document.getElementById('client_dropdown').classList.remove('d-none');
            document.getElementById('project_dropdown').classList.remove('d-none');
            document.getElementById('department_dropdown').classList.add('d-none');
        } else if (value == 2) {
            document.getElementById('client_dropdown').classList.add('d-none');
            document.getElementById('project_dropdown').classList.add('d-none');
            document.getElementById('department_dropdown').classList.remove('d-none');
        } else {
            document.getElementById('client_dropdown').classList.add('d-none');
            document.getElementById('project_dropdown').classList.add('d-none');
            document.getElementById('department_dropdown').classList.add('d-none');
        }
    }
</script>
