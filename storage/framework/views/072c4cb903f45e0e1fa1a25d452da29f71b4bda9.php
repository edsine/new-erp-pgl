<?php echo e(Form::open(array('url'=>'announcement','method'=>'post'))); ?>

<div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('title',__('Announcement Title'),['class'=>'form-label'])); ?>

                <?php echo e(Form::text('title',null,array('class'=>'form-control','placeholder'=>__('Enter Announcement Title')))); ?>

            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('branch_id',__('Branch'),['class'=>'form-label'])); ?>

                <select class="form-control select" name="branch_id" id="branch_id" placeholder="Select Branch">
                    <option value=""><?php echo e(__('Select Branch')); ?></option>
                    <option value="0"><?php echo e(__('All Branch')); ?></option>
                    <?php $__currentLoopData = $branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($branch->id); ?>"><?php echo e($branch->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('department_id',__('Department'),['class'=>'form-label'])); ?>

                <select class="form-control select" name="department_id[]" id="department_id" placeholder="Select Department" >
                    <option value=""><?php echo e(__('Select Department')); ?></option>

                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('employee_id',__('Employee'),['class'=>'form-label'])); ?>

                <select class="form-control select" name="employee_id[]" id="employee_id" placeholder="Select Employee" >
                    <option value=""><?php echo e(__('Select Employee')); ?></option>

                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('start_date',__('Announcement start Date'),['class'=>'form-label'])); ?>

                <?php echo e(Form::date('start_date',null,array('class'=>'form-control '))); ?>

            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('end_date',__('Announcement End Date'),['class'=>'form-label'])); ?>

                <?php echo e(Form::date('end_date',null,array('class'=>'form-control '))); ?>

            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <?php echo e(Form::label('description',__('Announcement Description'),['class'=>'form-label'])); ?>

                <?php echo e(Form::textarea('description',null,array('class'=>'form-control','placeholder'=>__('Enter Announcement Title')))); ?>

            </div>
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn  btn-primary">
</div>

<?php echo e(Form::close()); ?>



<script>

    //Branch Wise Deapartment Get
    $(document).ready(function () {
        var b_id = $('#branch_id').val();
        getDepartment(b_id);
    });

    $(document).on('change', 'select[name=branch_id]', function () {
        var branch_id = $(this).val();
        getDepartment(branch_id);
    });

    function getDepartment(bid) {

        $.ajax({
            url: '<?php echo e(route('announcement.getdepartment')); ?>',
            type: 'POST',
            data: {
                "branch_id": bid, "_token": "<?php echo e(csrf_token()); ?>",
            },
            success: function (data) {
                $('#department_id').empty();
                $('#department_id').append('<option value=""><?php echo e(__('Select Department')); ?></option>');

                $('#department_id').append('<option value="0"> <?php echo e(__('All Department')); ?> </option>');
                $.each(data, function (key, value) {
                    $('#department_id').append('<option value="' + key + '">' + value + '</option>');
                });
            }
        });
    }

    $(document).on('change', '#department_id', function () {
        var department_id = $(this).val();
        getEmployee(department_id);
    });

    function getEmployee(did) {

        $.ajax({
            url: '<?php echo e(route('announcement.getemployee')); ?>',
            type: 'POST',
            data: {
                "department_id": did, "_token": "<?php echo e(csrf_token()); ?>",
            },
            success: function (data) {

                $('#employee_id').empty();
                $('#employee_id').append('<option value=""><?php echo e(__('Select Employee')); ?></option>');
                $('#employee_id').append('<option value="0"> <?php echo e(__('All Employee')); ?> </option>');

                $.each(data, function (key, value) {
                    $('#employee_id').append('<option value="' + key + '">' + value + '</option>');
                });
            }
        });
    }
</script>

<?php /**PATH C:\laragon\www\pglnigeriaerp\resources\views/announcement/create.blade.php ENDPATH**/ ?>