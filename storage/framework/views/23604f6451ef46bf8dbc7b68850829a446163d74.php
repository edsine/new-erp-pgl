<?php echo e(Form::model($appraisal, ['route' => ['appraisal.update', $appraisal->id], 'method' => 'PUT'])); ?>

<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <?php echo e(Form::label('branch', __('Branch*'), ['class' => 'col-form-label'])); ?>

                <select name="branch" id="branch" required class="form-control ">
                    <?php $__currentLoopData = $brances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option  value="<?php echo e($value->id); ?>" <?php if($appraisal->branch == $value->id): ?> selected <?php endif; ?>><?php echo e($value->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('employees', __('Employee*'), ['class' => 'col-form-label'])); ?>

                <div class="employee_div">
                    <select name="employee" id="employee" class="form-control " required>

                    </select>
                </div>

            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('appraisal_date', __('Select Month*'), ['class' => 'col-form-label'])); ?>

                <?php echo e(Form::text('appraisal_date', null, ['class' => 'form-control d_filter' ,'required' => 'required'])); ?>

            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <?php echo e(Form::label('remark', __('Remarks'), ['class' => 'col-form-label'])); ?>

                <?php echo e(Form::textarea('remark', null, ['class' => 'form-control', 'rows' => '3'])); ?>

            </div>
        </div>
    </div>
    <div class="row"  id="stares">

    </div>



    <div class="modal-footer">
        <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="<?php echo e(__('Update')); ?>" class="btn btn-primary">
    </div>
    <?php echo e(Form::close()); ?>




    <script>

        $('#employee').change(function(){

            var emp_id = $('#employee').val();
            $.ajax({
                url: "<?php echo e(route('empByStar')); ?>",
                type: "post",
                data:{
                    "employee": emp_id,
                    "_token": "<?php echo e(csrf_token()); ?>",
                },

                cache: false,
                success: function(data) {

                    $('#stares').html(data.html);
                }
            })
        });
    </script>

    <script>
        var branch_ids = '<?php echo e($appraisal->branch); ?>';
        var employee_id = '<?php echo e($appraisal->employee); ?>';
        var appraisal_id = '<?php echo e($appraisal->id); ?>';



        $( document ).ready(function() {
            $.ajax({
                url: "<?php echo e(route('getemployee')); ?>",
                type: "post",
                data:{
                    "branch_id": branch_ids,
                    "_token": "<?php echo e(csrf_token()); ?>",
                },

                cache: false,
                success: function(data) {

                    $('#employee').html('<option value="">Select Employee</option>');
                    $.each(data.employee, function (key, value) {
                        if(value.id == <?php echo e($appraisal->employee); ?>){
                            $("#employee").append('<option  selected value="' + value.id + '">' + value.name + '</option>');
                        }else{
                            $("#employee").append('<option value="' + value.id + '">' + value.name + '</option>');
                        }
                    });
                }
            })

            $.ajax({
                url: "<?php echo e(route('empByStar1')); ?>",
                type: "post",
                data:{
                    "employee": employee_id,
                    "appraisal": appraisal_id,

                    "_token": "<?php echo e(csrf_token()); ?>",
                },

                cache: false,
                success: function(data) {

                    $('#stares').html(data.html);
                }
            })

        });

        $('#branch').on('change', function() {
            var branch_id = this.value;

            $.ajax({
                url: "<?php echo e(route('getemployee')); ?>",
                type: "post",
                data:{
                    "branch_id": branch_id,
                    "_token": "<?php echo e(csrf_token()); ?>",
                },

                cache: false,
                success: function(data) {

                    $('#employee').html('<option value="">Select Employee</option>');
                    $.each(data.employee, function (key, value) {
                        $("#employee").append('<option value="' + value.id + '">' + value.name + '</option>');
                    });

                }
            })
        });


    </script>
<?php /**PATH C:\laragon\www\pglnigeriaerp\resources\views/appraisal/edit.blade.php ENDPATH**/ ?>