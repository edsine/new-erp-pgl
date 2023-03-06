<?php echo e(Form::model($asset, array('route' => array('account-assets.update', $asset->id), 'method' => 'PUT'))); ?>

<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12">
            <?php echo e(Form::label('name', __('Name'),['class'=>'form-label'])); ?>

            <?php echo e(Form::text('name', null, array('class' => 'form-control','required'=>'required'))); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('amount', __('Amount'),['class'=>'form-label'])); ?>

            <?php echo e(Form::number('amount', null, array('class' => 'form-control','required'=>'required','step'=>'0.01'))); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('purchase_date', __('Purchase Date'),['class'=>'form-label'])); ?>

            <?php echo e(Form::date('purchase_date',null, array('class' => 'form-control '))); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('supported_date', __('Supported Date'),['class'=>'form-label'])); ?>

            <?php echo e(Form::date('supported_date',null, array('class' => 'form-control '))); ?>

        </div>
        <div class="form-group col-md-12">
            <?php echo e(Form::label('description', __('Description'),['class'=>'form-label'])); ?>

            <?php echo e(Form::textarea('description', null, array('class' => 'form-control','rows'=>3))); ?>

        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Update')); ?>" class="btn  btn-primary">
</div>

<?php echo e(Form::close()); ?>

<?php /**PATH C:\laragon\www\pglnigeriaerp\resources\views/assets/edit.blade.php ENDPATH**/ ?>