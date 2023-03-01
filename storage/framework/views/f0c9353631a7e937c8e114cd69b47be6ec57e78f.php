
<?php echo e(Form::open(array('url'=>'interview-schedule','method'=>'post'))); ?>

<div class="modal-body">

    <div class="row">
        <div class="form-group col-md-6">
            <?php echo e(Form::label('candidate',__('Interviewer'),['class'=>'form-label'])); ?>

            <?php echo e(Form::select('candidate', $candidates,null, array('class' => 'form-control select','required'=>'required'))); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('employee',__('Assign Employee'),['class'=>'form-label'])); ?>

            <?php echo e(Form::select('employee', $employees,null, array('class' => 'form-control select','required'=>'required'))); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('date',__('Interview Date'),['class'=>'form-label'])); ?>

            <?php echo e(Form::date('date',null,array('class'=>'form-control'))); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('time',__('Interview Time'),['class'=>'form-label'])); ?>

            <?php echo e(Form::time('time',null,array('class'=>'form-control timepicker'))); ?>

        </div>
        <div class="form-group col-md-12">
            <?php echo e(Form::label('comment',__('Comment'),['class'=>'form-label'])); ?>

            <?php echo e(Form::textarea('comment',null,array('class'=>'form-control'))); ?>

        </div>

        <?php if(isset($settings['google_calendar_enable']) && $settings['google_calendar_enable'] == 'on'): ?>
            <div class="form-group col-md-12">
                <?php echo e(Form::label('synchronize_type',__('Synchronize in Google Calendar ?'),array('class'=>'form-label'))); ?>

                <div class=" form-switch">
                    <input type="checkbox" class="form-check-input mt-2" name="synchronize_type" id="switch-shadow" value="google_calender">
                    <label class="form-check-label" for="switch-shadow"></label>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn btn-primary">
</div>
<?php echo e(Form::close()); ?>

<?php if($candidate!=0): ?>
    <script>
        $('select#candidate').val(<?php echo e($candidate); ?>).trigger('change');
    </script>
<?php endif; ?>
<?php /**PATH C:\laragon\www\pglnigeriaerp\resources\views/interviewSchedule/create.blade.php ENDPATH**/ ?>