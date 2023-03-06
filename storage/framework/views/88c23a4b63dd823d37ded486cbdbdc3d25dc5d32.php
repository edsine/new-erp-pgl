<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Custom Question for interview')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Custom-Question')); ?></li>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('action-btn'); ?>
    <div class="float-end">
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create custom question')): ?>
        <a href="#" data-size="lg" data-url="<?php echo e(route('custom-question.create')); ?>" data-ajax-popup="true" data-bs-toggle="tooltip" title="<?php echo e(__('Create')); ?>" data-title="<?php echo e(__('Create New Custom Question')); ?>" class="btn btn-sm btn-primary">
            <i class="ti ti-plus"></i>
        </a>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
            <div class="card-body table-border-style">
                    <div class="table-responsive">
                    <table class="table datatable">
                            <thead>
                            <tr>
                                <th><?php echo e(__('Question')); ?></th>
                                <th><?php echo e(__('Is Required')); ?></th>
                                <th width="200px"><?php echo e(__('Action')); ?></th>
                            </tr>
                            </thead>
                            <tbody class="font-style">
                            <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($question->question); ?></td>
                                    <td>
                                        <?php if($question->is_required=='yes'): ?>
                                            <span class="badge bg-success p-2 px-3 rounded"><?php echo e(\App\Models\CustomQuestion::$is_required[$question->is_required]); ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-danger p-2 px-3 rounded"><?php echo e(\App\Models\CustomQuestion::$is_required[$question->is_required]); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit custom question')): ?>
                                        <div class="action-btn bg-primary ms-2">
                                            <a href="#" data-url="<?php echo e(route('custom-question.edit',$question->id)); ?>" data-size="lg" title="<?php echo e(__('Edit')); ?>"  data-ajax-popup="true" data-title="<?php echo e(__('Edit Custom Question')); ?>" class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip" data-original-title="<?php echo e(__('Edit')); ?>"><i class="ti ti-pencil text-white"></i></a>
                                        </div>
                                            <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete custom question')): ?>
                                        <div class="action-btn bg-danger ms-2">
                                        <?php echo Form::open(['method' => 'DELETE', 'route' => ['custom-question.destroy', $question->id],'id'=>'delete-form-'.$question->id]); ?>


                                            <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="<?php echo e(__('Delete')); ?>" data-original-title="<?php echo e(__('Delete')); ?>" data-confirm="<?php echo e(__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')); ?>" data-confirm-yes="document.getElementById('delete-form-<?php echo e($question->id); ?>').submit();"><i class="ti ti-trash text-white"></i></a>
                                            <?php echo Form::close(); ?>

                                        </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\pglnigeriaerp\resources\views/customQuestion/index.blade.php ENDPATH**/ ?>