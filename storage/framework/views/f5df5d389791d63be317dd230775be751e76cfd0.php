<?php echo e(Form::model($leave,array('route' => array('leave.update', $leave->id), 'method' => 'PUT'))); ?>

<div class="modal-body">
 
<?php if($leave->status == "Pending"): ?>
<div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <?php echo e(Form::label('employee_id',__('Employee') ,['class'=>'form-label'])); ?>

                <?php echo e(Form::select('employee_id',$employees,null,array('class'=>'form-control select','placeholder'=>__('Select Employee')))); ?>

            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <?php echo e(Form::label('reliever_id',__('Reliever') ,['class'=>'form-label'])); ?>

                    <?php echo e(Form::select('reliever_id',$employees,null,array('class'=>'form-control select','id'=>'reliever_id','placeholder'=>__('Select Reliever')))); ?>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <?php echo e(Form::label('leave_type_id',__('Leave Type'),['class'=>'form-label'])); ?>

                <?php echo e(Form::select('leave_type_id',$leavetypes,null,array('class'=>'form-control select','placeholder'=>__('Select Leave Type')))); ?>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('start_date',__('Start Date'),['class'=>'form-label'])); ?>

                <?php echo e(Form::date('start_date',null,array('class'=>'form-control '))); ?>

            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('end_date',__('End Date'),['class'=>'form-label'])); ?>

                <?php echo e(Form::date('end_date',null,array('class'=>'form-control '))); ?>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <?php echo e(Form::label('leave_reason',__('Leave Description'),['class'=>'form-label'])); ?>

                <?php echo e(Form::textarea('leave_reason',null,array('class'=>'form-control','placeholder'=>__('Leave Reason')))); ?>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <?php echo e(Form::label('remark',__('Remark'),['class'=>'form-label'])); ?>

                <?php echo e(Form::textarea('remark',null,array('class'=>'form-control','placeholder'=>__('Leave Remark')))); ?>

            </div>
        </div>
    </div>
    <?php if(\Spatie\Permission\PermissionServiceProvider::bladeMethodWrapper('hasRole', 'Company')): ?>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <?php echo e(Form::label('status',__('Status'))); ?>

                <select name="status" id="" class="form-control select2">
                    <option value=""><?php echo e(__('Select Status')); ?></option>
                    <option value="pending" <?php if($leave->status=='Pending'): ?> selected="" <?php endif; ?>><?php echo e(__('Pending')); ?></option>
                    <option value="approval" <?php if($leave->status=='Approval'): ?> selected="" <?php endif; ?>><?php echo e(__('Approval')); ?></option>
                    <option value="reject" <?php if($leave->status=='Reject'): ?> selected="" <?php endif; ?>><?php echo e(__('Reject')); ?></option>
                </select>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>
<?php else: ?>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <?php echo e(Form::label('employee_id',__('Employee') ,['class'=>'form-label'])); ?>

            <?php echo e(Form::select('employee_id',$employees,null,array('class'=>'form-control select','readonly','placeholder'=>__('Select Employee')))); ?>

        </div>    
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <?php echo e(Form::label('reliever_id',__('Reliever') ,['class'=>'form-label'])); ?>

            <?php echo e(Form::select('reliever_id',$employees,null,array('class'=>'form-control select','readonly','id'=>'reliever_id','placeholder'=>__('Select Reliever')))); ?>

        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <?php echo e(Form::label('leave_type_id',__('Leave Type'),['class'=>'form-label'])); ?>

            <?php echo e(Form::select('leave_type_id',$leavetypes,null,array('class'=>'form-control select','readonly','placeholder'=>__('Select Leave Type')))); ?>

        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <?php echo e(Form::label('start_date',__('Start Date'),['class'=>'form-label'])); ?>

            <?php echo e(Form::date('start_date',null,array('class'=>'form-control ','readonly'))); ?>

        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <?php echo e(Form::label('end_date',__('End Date'),['class'=>'form-label'])); ?>

            <?php echo e(Form::date('end_date',null,array('class'=>'form-control ','readonly'))); ?>

        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <?php echo e(Form::label('leave_reason',__('Leave Description'),['class'=>'form-label'])); ?>

            <?php echo e(Form::textarea('leave_reason',null,array('class'=>'form-control','readonly','placeholder'=>__('Leave Description')))); ?>

        </div>
    </div>
</div>
<?php if(\Auth::user()->type == 'HR'): ?>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <?php echo e(Form::label('remark',__('Remark'),['class'=>'form-label'])); ?>

            <?php echo e(Form::textarea('remark',null,array('class'=>'form-control','placeholder'=>__('Leave Remark')))); ?>

        </div>
    </div>
</div>
<?php else: ?>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <?php echo e(Form::label('remark',__('Remark'),['class'=>'form-label'])); ?>

            <?php echo e(Form::textarea('remark',null,array('class'=>'form-control','readonly','placeholder'=>__('Leave Remark')))); ?>

        </div>
    </div>
</div>
<?php endif; ?>


</div>
<?php endif; ?>

<?php if($leave->status == "Pending"): ?>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Update')); ?>" class="btn  btn-primary">
</div>
<?php else: ?>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
</div>
<?php endif; ?>
    <?php echo e(Form::close()); ?>


<?php /**PATH C:\laragon\www\pglnigeriaerp\resources\views/leave/edit.blade.php ENDPATH**/ ?>