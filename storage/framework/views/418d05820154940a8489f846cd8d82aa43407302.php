<?php echo e(Form::open(array('url'=>'holiday','method'=>'post'))); ?>

<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12">
            <?php echo e(Form::label('occasion',__('Occasion'),['class'=>'form-label'])); ?>

            <?php echo e(Form::text('occasion',null,array('class'=>'form-control'))); ?>

        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-6">
            <?php echo e(Form::label('date',__('Start Date'),['class'=>'form-label'])); ?>

            <?php echo e(Form::date('date',null,array('class'=>'form-control '))); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('end_date',__('End Date'),['class'=>'form-label'])); ?>

            <?php echo e(Form::date('end_date',null,array('class'=>'form-control'))); ?>

        </div>
    </div>
    <?php if(isset($settings['google_calendar_enable']) && $settings['google_calendar_enable'] == 'on'): ?>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('synchronize_type',__('Synchronize in Google Calendar ?'),array('class'=>'form-label'))); ?>

            <div class=" form-switch">
                <input type="checkbox" class="form-check-input mt-2" name="synchronize_type" id="switch-shadow" value="google_calender">
                <label class="form-check-label" for="switch-shadow"></label>
            </div>
        </div>
    <?php endif; ?>
</div>

<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn  btn-primary">
</div>

<?php echo e(Form::close()); ?>


<?php /**PATH C:\laragon\www\pglnigeriaerp\resources\views/holiday/create.blade.php ENDPATH**/ ?>