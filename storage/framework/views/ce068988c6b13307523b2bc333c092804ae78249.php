<?php $__env->startPush('script-page'); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Customer-Detail')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('customer.index')); ?>"><?php echo e(__('Customer')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e($customer['name']); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('action-btn'); ?>
    <div class="float-end">
        

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit customer')): ?>
            <a href="#" data-size="lg" data-url="<?php echo e(route('customer.edit',$customer['id'])); ?>" data-ajax-popup="true" title="<?php echo e(__('Edit Customer')); ?>" data-bs-toggle="tooltip" data-original-title="<?php echo e(__('Edit')); ?>" class="btn btn-sm btn-primary">
                <i class="ti ti-pencil"></i>
            </a>
        <?php endif; ?>


        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete customer')): ?>
            <?php echo Form::open(['method' => 'DELETE','class' => 'delete-form-btn', 'route' => ['customer.destroy', $customer['id']]]); ?>


            <a href="#" data-bs-toggle="tooltip" title="<?php echo e(__('Delete Customer')); ?>" data-confirm="<?php echo e(__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')); ?>" data-confirm-yes="document.getElementById('delete-form-<?php echo e($customer['id']); ?>').submit();" class="btn btn-sm btn-danger bs-pass-para">
                <i class="ti ti-trash text-white"></i>
            </a>
            <?php echo Form::close(); ?>


        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-4 col-lg-4 col-xl-4">
            <div class="card customer-detail-box customer_card">
                <div class="card-body">
                    <h5 class="card-title"><?php echo e(__('Customer Info')); ?></h5>
                    <p class="card-text mb-0"><?php echo e($customer['name']); ?></p>
                    <p class="card-text mb-0"><?php echo e($customer['email']); ?></p>
                    <p class="card-text mb-0"><?php echo e($customer['contact']); ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-lg-4 col-xl-4">
            <div class="card customer-detail-box customer_card">
                <div class="card-body">
                    <h5 class="card-title"><?php echo e(__('Billing Info')); ?></h5>
                    <p class="card-text mb-0"><?php echo e($customer['billing_name']); ?></p>
                    <p class="card-text mb-0"><?php echo e($customer['billing_phone']); ?></p>
                    <p class="card-text mb-0"><?php echo e($customer['billing_address']); ?></p>
                    <p class="card-text mb-0"><?php echo e($customer['billing_city'].', '. $customer['billing_state'] .', '.$customer['billing_country']); ?></p>
                    <p class="card-text mb-0"><?php echo e($customer['billing_zip']); ?></p>
                </div>
            </div>

        </div>
        <div class="col-md-4 col-lg-4 col-xl-4">
            <div class="card customer-detail-box customer_card">
                <div class="card-body">
                    <h5 class="card-title"><?php echo e(__('Shipping Info')); ?></h5>
                    <p class="card-text mb-0"><?php echo e($customer['shipping_name']); ?></p>
                    <p class="card-text mb-0"><?php echo e($customer['shipping_phone']); ?></p>
                    <p class="card-text mb-0"><?php echo e($customer['shipping_address']); ?></p>
                    <p class="card-text mb-0"><?php echo e($customer['shipping_city'].', '. $customer['billing_state'] .', '.$customer['billing_country']); ?></p>
                    <p class="card-text mb-0"><?php echo e($customer['shipping_zip']); ?></p>
                </div>
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card pb-0">
                <div class="card-body">
                    <h5 class="card-title"><?php echo e(__('Company Info')); ?></h5>

                    <div class="row">
                        <?php
                            $totalInvoiceSum=$customer->customerTotalInvoiceSum($customer['id']);
                            $totalInvoice=$customer->customerTotalInvoice($customer['id']);
                            $averageSale=($totalInvoiceSum!=0)?$totalInvoiceSum/$totalInvoice:0;
                        ?>
                        <div class="col-md-3 col-sm-6">
                            <div class="p-4">
                                <p class="card-text mb-0"><?php echo e(__('Customer Id')); ?></p>
                                <h6 class="report-text mb-3"><?php echo e(AUth::user()->customerNumberFormat($customer['customer_id'])); ?></h6>
                                <p class="card-text mb-0"><?php echo e(__('Total Sum of Invoices')); ?></p>
                                <h6 class="report-text mb-0"><?php echo e(\Auth::user()->priceFormat($totalInvoiceSum)); ?></h6>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="p-4">
                                <p class="card-text mb-0"><?php echo e(__('Date of Creation')); ?></p>
                                <h6 class="report-text mb-3"><?php echo e(\Auth::user()->dateFormat($customer['created_at'])); ?></h6>
                                <p class="card-text mb-0"><?php echo e(__('Quantity of Invoice')); ?></p>
                                <h6 class="report-text mb-0"><?php echo e($totalInvoice); ?></h6>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="p-4">
                                <p class="card-text mb-0"><?php echo e(__('Balance')); ?></p>
                                <h6 class="report-text mb-3"><?php echo e(\Auth::user()->priceFormat($customer['balance'])); ?></h6>
                                <p class="card-text mb-0"><?php echo e(__('Average Sales')); ?></p>
                                <h6 class="report-text mb-0"><?php echo e(\Auth::user()->priceFormat($averageSale)); ?></h6>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="p-4">
                                <p class="card-text mb-0"><?php echo e(__('Overdue')); ?></p>
                                <h6 class="report-text mb-3"><?php echo e(\Auth::user()->priceFormat($customer->customerOverdue($customer['id']))); ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\projects\new-erp-pgl\resources\views/customer/show.blade.php ENDPATH**/ ?>