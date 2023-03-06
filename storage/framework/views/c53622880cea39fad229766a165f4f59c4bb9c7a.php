
<?php echo e(Form::model($attendanceEmployee,array('route' => array('attendanceemployee.update', $attendanceEmployee->id), 'method' => 'PUT'))); ?>

<div class="modal-body">

    <div class="row">
        <div class="form-group col-lg-6  ">
            <?php echo e(Form::label('employee_id',__('Employee'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::select('employee_id',$employees,null,array('class'=>'form-control select'))); ?>

        </div>
        <div class="form-group col-lg-6 ">
            <?php echo e(Form::label('date',__('Date'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::date('date',null,array('class'=>'form-control '))); ?>

        </div>
    </div>
    <div class="row">

        <div class="form-group col-lg-6 ">
            <?php echo e(Form::label('clock_in',__('Clock In'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::time('clock_in',null,array('class'=>'form-control '))); ?>

        </div>

        <div class="form-group col-lg-6 ">
            <?php echo e(Form::label('clock_out',__('Clock Out'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::time('clock_out',null,array('class'=>'form-control '))); ?>

        </div>

    </div>

</div>

<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Update')); ?>" class="btn btn-primary">
</div>
<?php echo e(Form::close()); ?>




<?php /**PATH C:\laragon\www\pglnigeriaerp\resources\views/attendance/edit.blade.php ENDPATH**/ ?>