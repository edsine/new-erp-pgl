{{ Form::model($chartOfAccount, ['route' => ['chart-of-account.update', $chartOfAccount->id], 'method' => 'PUT']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-6">
            {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
            {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('code', __('Code'), ['class' => 'form-label']) }}
            {{ Form::number('code', null, ['class' => 'form-control', 'required' => 'required']) }}
        </div>

        {{-- <div class="form-group col-md-6">
            {{ Form::label('type', __('Type'), ['class' => 'form-label']) }}
            {{ Form::select('type', $types, null, ['class' => 'form-control select', 'required' => 'required', 'id' => 'type']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('sub_type', __('Group'), ['class' => 'form-label']) }}
            <select class="form-control select" name="sub_type" id="sub_type" required>

            </select>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('sub_type_level_2', __('Sub-Group'), ['class' => 'form-label']) }}
            <select class="form-control select" name="sub_type_level_2" id="sub_type_level_2">

            </select>
        </div> --}}


        <div class="form-group col-md-6">
            {{ Form::label('is_enabled', __('Is Enabled'), ['class' => 'form-label']) }}
            <div class="form-check form-switch">
                <input type="checkbox" class="form-check-input" name="is_enabled" id="is_enabled"
                    {{ $chartOfAccount->is_enabled == 1 ? 'checked' : '' }}>
                <label class="custom-control-label form-check-label" for="is_enabled"></label>
            </div>
        </div>


        <div class="form-group col-md-12">
            {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}
            {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => '2']) !!}
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn  btn-primary">
</div>
{{ Form::close() }}


<script>
    type = document.getElementById('type').type;
    console.log("type ", type);
    if (type) {
        const currentSubType = "{{ $chartOfAccount->sub_type }}";
        $.ajax({
            url: '{{ route('charofAccount.subType') }}',
            type: 'POST',
            data: {
                "type": type,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                $('#sub_type').empty();
                $('#sub_type').append('<option>...</option>');
                $.each(data, function(key, value) {
                    console.log(currentSubType);
                    if (key == currentSubType) {
                        $('#sub_type').append('<option selected value="' + key + '">' + value +
                            '</option>');
                    } else {
                        $('#sub_type').append('<option value="' + key + '">' + value +
                            '</option>');
                    }

                });
            }
        });
    }
</script>
