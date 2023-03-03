<?php $__env->startPush('script-page'); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Support Reply')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('title'); ?>
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block font-weight-400 mb-0 "><?php echo e(__('Support Reply')); ?></h5>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('support.index')); ?>"><?php echo e(__('Support')); ?></a></li>
    <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Support Reply')); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('action-btn'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('filter'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><?php echo e($support->subject); ?></h6>
                </div>
                <?php if(!empty($support->descroption)): ?>
                    <div class="card-body py-3 flex-grow-1">
                        <p class="text-sm mb-0">
                            <?php echo e($support->descroption); ?>

                        </p>
                    </div>
                <?php endif; ?>
                <div class="card-footer py-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <span class="form-label"><?php echo e(__('Created By')); ?>:</span>
                                </div>
                                <div class="col-6 text-end">
                                    <?php echo e(!empty($support->createdBy)?$support->createdBy->name:''); ?>

                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <span class="form-label"><?php echo e(__('Ticket Code')); ?>:</span>
                                </div>
                                <div class="col-6 text-end">
                                    <?php echo e($support->ticket_code); ?>

                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <span class="form-label"><?php echo e(__('Priority')); ?>:</span>
                                </div>
                                <div class="col-6 text-end">
                                    <?php if($support->priority == 0): ?>
                                        <span class="badge bg-primary p-2 px-3 rounded">   <?php echo e(__(\App\Models\Support::$priority[$support->priority])); ?></span>
                                    <?php elseif($support->priority == 1): ?>
                                        <span class="badge bg-info p-2 px-3 rounded">   <?php echo e(__(\App\Models\Support::$priority[$support->priority])); ?></span>
                                    <?php elseif($support->priority == 2): ?>
                                        <span class="badge bg-warning p-2 px-3 rounded">   <?php echo e(__(\App\Models\Support::$priority[$support->priority])); ?></span>
                                    <?php elseif($support->priority == 3): ?>
                                        <span class="badge bg-danger p-2 px-3 rounded">   <?php echo e(__(\App\Models\Support::$priority[$support->priority])); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <span class="form-label"><?php echo e(__('Status')); ?>:</span>
                                </div>
                                <div class="col-6 text-end">
                                    <?php if($support->status == 'Open'): ?>
                                        <span class="badge bg-primary p-2 px-3 rounded"> <?php echo e(__('Open')); ?></span>
                                    <?php elseif($support->status == 'Close'): ?>
                                        <span class="badge bg-danger p-2 px-3 rounded">   <?php echo e(__('Closed')); ?></span>
                                    <?php elseif($support->status == 'On Hold'): ?>
                                        <span class="badge bg-warning p-2 px-3 rounded">   <?php echo e(__('On Hold')); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <small><?php echo e(__('Start Date')); ?>:</small>
                                    <div class="h6 mb-0"><?php echo e(\Auth::user()->dateFormat($support->created_at)); ?></div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <?php if($support->status == 'Open'): ?>
                    <h5 class="mt-0 mb-3"><?php echo e(__('Comments')); ?></h5>
                    <?php echo e(Form::open(array('route' => array('support.reply.answer',$support->id)))); ?>

                    <textarea class="form-control form-control-light mb-2" name="description" placeholder="Your comment" id="example-textarea" rows="3" required=""></textarea>
                    <div class="text-end">
                        <div class=" mb-2 ml-2">
                            <?php echo e(Form::submit(__('Send'),array('class'=>'btn btn-primary'))); ?>

                        </div>
                    </div>
                    <?php echo e(Form::close()); ?>

                    <?php endif; ?>

                    <div class="scrollbar-inner">
                        <div class="list-group list-group-flush support-reply-box">
                            <?php $__currentLoopData = $replyes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reply): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              


                                 <div class="list-group-item px-0">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <a href="#" class="avatar avatar-sm ms-2">
                                <img alt="" class=" " <?php if(!empty($reply->users) && !empty($reply->users->avatar)): ?> src="<?php echo e(asset(Storage::url('uploads/avatar/')).'/'.$reply->users->avatar); ?>" <?php else: ?>  src="<?php echo e(asset(Storage::url('uploads/avatar/')).'/avatar.png'); ?>" <?php endif; ?>>
                                </a>
                            </div>
                            <div class="col ml-n2">
                                <span class="text-dark text-sm"><?php echo e(!empty($reply->users)?$reply->users->name:''); ?> </span>
                                <a class="d-block h6 text-sm font-weight-light mb-0"><?php echo e($reply->description); ?></a>
                                <small class="d-block"><?php echo e($reply->created_at); ?></small>
                            </div>
                        </div>
                    </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\pglnigeriaerp\resources\views/support/reply.blade.php ENDPATH**/ ?>