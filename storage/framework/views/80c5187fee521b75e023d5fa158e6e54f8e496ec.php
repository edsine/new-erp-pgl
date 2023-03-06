<?php echo e(Form::open(array('url'=>'overtime','method'=>'post'))); ?>

<div class="modal-body">

    <?php echo e(Form::hidden('employee_id',$employee->id, array())); ?>


    <div class="row">
        <div class="form-group col-md-6">
            <?php echo e(Form::label('title', __('Overtime Title'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
            <?php echo e(Form::text('title',null, array('class' => 'form-control ','required'=>'required'))); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('number_of_days', __('Number of days'),['class'=>'form-label'])); ?>

            <?php echo e(Form::number('number_of_days',null, array('class' => 'form-control ','required'=>'required','step'=>'0.01'))); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('hours', __('Hours'),['class'=>'form-label'])); ?>

            <?php echo e(Form::number('hours',null, array('class' => 'form-control ','required'=>'required','step'=>'0.01'))); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('rate', __('Rate'),['class'=>'form-label'])); ?>

            <?php echo e(Form::number('rate',null, array('class' => 'form-control ','required'=>'required','step'=>'0.01'))); ?>

        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn  btn-primary">
</div>
<?php echo e(Form::close()); ?>


<?php /**PATH C:\laragon\www\pglnigeriaerp\resources\views/overtime/create.blade.php ENDPATH**/ ?>