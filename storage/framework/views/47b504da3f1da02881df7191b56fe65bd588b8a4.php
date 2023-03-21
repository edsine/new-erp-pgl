<?php echo e(Form::model($chartOfAccount, ['route' => ['chart-of-account.update', $chartOfAccount->id], 'method' => 'PUT'])); ?>

<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-6">
            <?php echo e(Form::label('name', __('Name'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::text('name', null, ['class' => 'form-control', 'required' => 'required'])); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('code', __('Code'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::number('code', null, ['class' => 'form-control', 'required' => 'required'])); ?>

        </div>

        


        <div class="form-group col-md-6">
            <?php echo e(Form::label('is_enabled', __('Is Enabled'), ['class' => 'form-label'])); ?>

            <div class="form-check form-switch">
                <input type="checkbox" class="form-check-input" name="is_enabled" id="is_enabled"
                    <?php echo e($chartOfAccount->is_enabled == 1 ? 'checked' : ''); ?>>
                <label class="custom-control-label form-check-label" for="is_enabled"></label>
            </div>
        </div>


        <div class="form-group col-md-12">
            <?php echo e(Form::label('description', __('Description'), ['class' => 'form-label'])); ?>

            <?php echo Form::textarea('description', null, ['class' => 'form-control', 'rows' => '2']); ?>

        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Update')); ?>" class="btn  btn-primary">
</div>
<?php echo e(Form::close()); ?>



<script>
    type = document.getElementById('type').type;
    console.log("type ", type);
    if (type) {
        const currentSubType = "<?php echo e($chartOfAccount->sub_type); ?>";
        $.ajax({
            url: '<?php echo e(route('charofAccount.subType')); ?>',
            type: 'POST',
            data: {
                "type": type,
                "_token": "<?php echo e(csrf_token()); ?>",
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
<?php /**PATH C:\laragon\projects\new-erp-pgl\resources\views/chartOfAccount/edit.blade.php ENDPATH**/ ?>