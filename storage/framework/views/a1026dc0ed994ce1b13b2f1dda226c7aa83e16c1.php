
<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Chart of Account Type')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('action-button'); ?>
    <div class="all-button-box row d-flex justify-content-end">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create constant chart of account type')): ?>
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6">
                <a href="#" data-url="<?php echo e(route('chart-of-account-type.create')); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Create New Type')); ?>" class="btn btn-xs btn-white btn-icon-only width-auto">
                    <i class="ti ti-plus"></i> <?php echo e(__('Create')); ?>

                </a>
            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0 dataTable">
                            <thead>
                            <tr>
                                <th> <?php echo e(__('Name')); ?></th>
                                <th> <?php echo e(__('Action')); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($type->name); ?></td>
                                    <td class="Action">
                                        <span>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit constant chart of account type')): ?>
                                                <a href="#" class="edit-icon" data-url="<?php echo e(route('chart-of-account-type.edit',$type->id)); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Edit Unit')); ?>" data-bs-toggle="tooltip" data-original-title="<?php echo e(__('Edit')); ?>">
                                                <i class="ti ti-pencil text-white"></i>
                                            </a>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete constant chart of account type')): ?>
                                                <a href="#" class="delete-icon" data-bs-toggle="tooltip" data-original-title="<?php echo e(__('Delete')); ?>" data-confirm="<?php echo e(__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')); ?>" data-confirm-yes="document.getElementById('delete-form-<?php echo e($type->id); ?>').submit();">
                                                <i class="ti ti-trash"></i>
                                            </a>
                                                <?php echo Form::open(['method' => 'DELETE', 'route' => ['chart-of-account-type.destroy', $type->id],'id'=>'delete-form-'.$type->id]); ?>

                                                <?php echo Form::close(); ?>

                                            <?php endif; ?>
                                        </span>
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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\projects\new-erp-pgl\resources\views/chartOfAccountType/index.blade.php ENDPATH**/ ?>