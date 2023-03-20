<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Journal Detail')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('journal-entry.index')); ?>"><?php echo e(__('Journal Entry')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(AUth::user()->journalNumberFormat($journalEntry->journal_id)); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="invoice">
                        <div class="invoice-print">
                            <div class="row invoice-title mt-2">
                                <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12">
                                    <h2><?php echo e(__('Journal')); ?></h2>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12 text-end">
                                    <h3 class="invoice-number">
                                        <?php echo e(\AUth::user()->journalNumberFormat($journalEntry->journal_id)); ?></h3>
                                </div>
                                <div class="col-12">
                                    <hr>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="font-style">
                                        <strong><?php echo e(__('To')); ?> :</strong><br>
                                        <?php echo e(!empty($settings['company_name']) ? $settings['company_name'] : ''); ?><br>
                                        <?php echo e(!empty($settings['company_telephone']) ? $settings['company_telephone'] : ''); ?><br>
                                        <?php echo e(!empty($settings['company_address']) ? $settings['company_address'] : ''); ?><br>
                                        <?php echo e(!empty($settings['company_city']) ? $settings['company_city'] : '' . ', '); ?>

                                        <?php echo e(!empty($settings['company_state']) ? $settings['company_state'] : '' . ', '); ?>

                                        <?php echo e(!empty($settings['company_country']) ? $settings['company_country'] : '' . '.'); ?>

                                    </small>
                                </div>
                                <div class="col-md-6 text-end">
                                    <small>
                                        <strong><?php echo e(__('Journal No')); ?> :</strong>
                                        <?php echo e(\Auth::user()->journalNumberFormat($journalEntry->journal_id)); ?>

                                    </small><br>
                                    <small>
                                        <strong><?php echo e(__('Journal Ref')); ?> :</strong>
                                        <?php echo e($journalEntry->reference); ?>

                                    </small> <br>
                                    <small>
                                        <strong><?php echo e(__('Journal Date')); ?> :</strong>
                                        <?php echo e(\Auth::user()->dateFormat($journalEntry->date)); ?>

                                    </small>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="font-weight-bold"><?php echo e(__('Journal Account Summary')); ?></div>
                                    <div class="table-responsive mt-2">
                                        <table class="table mb-0 ">
                                            <tr>
                                                <th data-width="40" class="text-dark">#</th>
                                                <th class="text-dark"><?php echo e(__('Account')); ?></th>
                                                <th class="text-dark" width="25%"><?php echo e(__('Description')); ?></th>
                                                <th class="text-dark"><?php echo e(__('Debit')); ?></th>
                                                <th class="text-dark"><?php echo e(__('Credit')); ?></th>
                                                <th class="text-dark text-end"><?php echo e(__('Amount')); ?></th>
                                            </tr>

                                            <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                
                                                <?php if(count($accounts) > 1 && $account->accounts->code == 100 && $account->debit != 0): ?>
                                                    <?php continue; ?>
                                                <?php endif; ?>
                                                
                                                <tr>
                                                    <td><?php echo e($key + 1); ?></td>
                                                    <td><?php echo e(!empty($account->accounts) ? $account->accounts->code . ' - ' . $account->accounts->name : ''); ?>

                                                    </td>
                                                    <td><?php echo e(!empty($account->description) ? $account->description : '-'); ?>

                                                    </td>
                                                    <td><?php echo e(\Auth::user()->priceFormat($account->debit)); ?></td>
                                                    <td><?php echo e(\Auth::user()->priceFormat($account->credit)); ?></td>
                                                    <td class="text-end">
                                                        <?php if($account->debit != 0): ?>
                                                            <?php echo e(\Auth::user()->priceFormat($account->debit)); ?>

                                                        <?php else: ?>
                                                            <?php echo e(\Auth::user()->priceFormat($account->credit)); ?>

                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                            <tfoot>

                                                <tr>
                                                    <td colspan="4"></td>
                                                    <td class="text-end"><b><?php echo e(__('Total Credit')); ?></b></td>
                                                    <td class="text-end">
                                                        <?php echo e(\Auth::user()->priceFormat($journalEntry->totalCredit())); ?></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4"></td>
                                                    <td class="text-end"><b><?php echo e(__('Total Debit')); ?></b></td>
                                                    <td class="text-end">
                                                        <?php echo e(\Auth::user()->priceFormat($journalEntry->totalDebit())); ?></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="font-weight-bold">
                                        <?php echo e(__('Description')); ?> : <br>
                                    </div>
                                    <small><?php echo e($journalEntry->description); ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\projects\new-erp-pgl\resources\views/journalEntry/view.blade.php ENDPATH**/ ?>