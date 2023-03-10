<?php echo e(Form::open(['url' => 'revenue', 'enctype' => 'multipart/form-data'])); ?>

<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-6">
            <?php echo e(Form::label('revenue_type', __('Revenue Source'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::select('revenue_type', ['' => 'Select...', '1' => 'Projects', '2' => 'Others'], null, ['class' => 'form-control select', 'required' => 'required', 'id' => 'revenue_type'])); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('date', __('Date'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::date('date', null, ['class' => 'form-control', 'required' => 'required'])); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('amount', __('Amount'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::number('amount', '', ['class' => 'form-control', 'required' => 'required', 'step' => '0.01'])); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('account_id', __('Account'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::select('account_id', $accounts, null, ['class' => 'form-control select', 'required' => 'required'])); ?>

        </div>
        <div id="client_dropdown" class="form-group col-md-6 d-none">
            <?php echo e(Form::label('customer_id', __('Customer'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::select('customer_id', $customers, null, ['class' => 'form-control select', 'required' => 'required', 'id' => 'client_id'])); ?>

        </div>
        <div id="project_dropdown" class="form-group col-md-6 d-none">
            <?php echo e(Form::label('project_id', __('Project'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::select('project_id', ['' => 'Select...'], null, ['class' => 'form-control select', 'required' => 'required', 'id' => 'project_id'])); ?>

        </div>
        <div class="form-group  col-md-12">
            <?php echo e(Form::label('description', __('Description'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::textarea('description', '', ['class' => 'form-control', 'rows' => 3])); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('category_id', __('Category'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::select('category_id', $categories, null, ['class' => 'form-control select', 'required' => 'required'])); ?>

        </div>
        

        <div class="form-group col-md-6">
            <?php echo e(Form::label('add_receipt', __('Payment Receipt'), ['class' => 'col-form-label'])); ?>

            <?php echo e(Form::file('add_receipt', ['class' => 'form-control', 'id' => 'files'])); ?>

            <img id="image" class="mt-3" style="width:25%;" />
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn  btn-primary">
</div>
<?php echo e(Form::close()); ?>


<script>
    document.getElementById('client_id').onchange = function() {
        var clientId = $(this).val();
        console.log(clientId);
        $.ajax({
            url: '<?php echo e(route('customer.projects')); ?>',
            type: 'POST',
            data: {
                "client_id": clientId,
                "_token": "<?php echo e(csrf_token()); ?>",
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
<?php /**PATH C:\laragon\projects\new-erp-pgl\resources\views/revenue/create.blade.php ENDPATH**/ ?>