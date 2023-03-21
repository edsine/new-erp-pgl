<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Balance Sheet')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Balance Sheet')); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('script-page'); ?>
    <script type="text/javascript" src="<?php echo e(asset('js/html2pdf.bundle.min.js')); ?>"></script>
    <script>
        var filename = $('#filename').val();

        function saveAsPDF() {
            var element = document.getElementById('printableArea');
            var opt = {
                margin: 0.3,
                filename: filename,
                image: {
                    type: 'jpeg',
                    quality: 1
                },
                html2canvas: {
                    scale: 4,
                    dpi: 72,
                    letterRendering: true
                },
                jsPDF: {
                    unit: 'in',
                    format: 'A2'
                }
            };
            html2pdf().set(opt).from(element).save();
        }
    </script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('action-btn'); ?>
    <div class="float-end">

        <a href="#" class="btn btn-sm btn-primary" onclick="saveAsPDF()"data-bs-toggle="tooltip"
            title="<?php echo e(__('Download')); ?>" data-original-title="<?php echo e(__('Download')); ?>">
            <span class="btn-inner--icon"><i class="ti ti-download"></i></span>
        </a>

    </div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-sm-12">
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        <?php echo e(Form::open(['route' => ['report.balance.sheet'], 'method' => 'GET', 'id' => 'report_bill_summary'])); ?>

                        <div class="row align-items-center justify-content-end">
                            <div class="col-xl-10">
                                <div class="row">

                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                        </div>
                                    </div>



                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            <?php echo e(Form::label('start_date', __('Start Date'), ['class' => 'form-label'])); ?>

                                            <?php echo e(Form::date('start_date', $filter['startDateRange'], ['class' => 'month-btn form-control'])); ?>

                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            <?php echo e(Form::label('end_date', __('End Date'), ['class' => 'form-label'])); ?>

                                            <?php echo e(Form::date('end_date', $filter['endDateRange'], ['class' => 'month-btn form-control'])); ?>

                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="row">
                                    <div class="col-auto mt-4">

                                        <a href="#" class="btn btn-sm btn-primary"
                                            onclick="document.getElementById('report_bill_summary').submit(); return false;"
                                            data-bs-toggle="tooltip" title="<?php echo e(__('Apply')); ?>"
                                            data-original-title="<?php echo e(__('apply')); ?>">
                                            <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                        </a>

                                        <a href="<?php echo e(route('report.balance.sheet')); ?>" class="btn btn-sm btn-danger "
                                            data-bs-toggle="tooltip" title="<?php echo e(__('Reset')); ?>"
                                            data-original-title="<?php echo e(__('Reset')); ?>">
                                            <span class="btn-inner--icon"><i
                                                    class="ti ti-trash-off text-white-off "></i></span>
                                        </a>


                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo e(Form::close()); ?>

                </div>
            </div>
        </div>
    </div>

    <div id="printableArea">
        <div class="row mt-2">
            <div class="col">
                <input type="hidden"
                    value="<?php echo e(__('Balance Sheet') . ' ' . 'Report of' . ' ' . $filter['startDateRange'] . ' to ' . $filter['endDateRange']); ?>"
                    id="filename">
                <div class="card p-4 mb-4">
                    <h6 class="mb-0"><?php echo e(__('Report')); ?> :</h6>
                    <h7 class="text-sm mb-0"><?php echo e(__('Balance Sheet')); ?></h7>
                </div>
            </div>

            <div class="col">
                <div class="card p-4 mb-4">
                    <h6 class="mb-0"><?php echo e(__('Duration')); ?> :</h6>
                    <h7 class="text-sm mb-0"><?php echo e($filter['startDateRange'] . ' to ' . $filter['endDateRange']); ?></h7>
                </div>
            </div>
        </div>

        

        <div class="row mb-4">
            <?php $__currentLoopData = $chartAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $subTypes): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $typeTotalCredit = 0;
                    $typeTotalDebit = 0;
                ?>
                <div class="col-lg-12 mb-4">
                    <h3 class="text-muted mb-4"><?php echo e($type); ?>

                    </h3>
                    <div class="row">
                        <?php $__currentLoopData = $subTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-lg-12 col-md-12 mb-4">
                                <div class="card p-3">
                                    <h4 class="text-muted mb-3"><?php echo e($subType['subType']); ?></h4>
                                    <?php
                                        $subTypeTotalCredit = 0;
                                        $subTypeTotalDebit = 0;
                                    ?>
                                    <div class="row">
                                        <?php $__currentLoopData = $subType['subTypeLevel2']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subTypeLevel2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="col-lg-12 col-md-12 mb-4">
                                                <div class="card p-3">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="2" width="80%">
                                                                    <h6> <?php echo e($subTypeLevel2['subTypeLevel2']); ?></h6>
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <th width="80%"> <?php echo e(__('Account')); ?></th>
                                                                <th> <?php echo e(__('Amount')); ?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="balance-sheet-body">
                                                            <?php
                                                                $totalCredit = 0;
                                                                $totalDebit = 0;
                                                            ?>
                                                            <?php $__currentLoopData = $subTypeLevel2['account']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <?php
                                                                    $totalCredit += $record['totalCredit'];
                                                                    $totalDebit += $record['totalDebit'];
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo e($record['account_name']); ?></td>
                                                                    <td>
                                                                        <?php if($record['netAmount'] < 0): ?>
                                                                            <?php echo e(__('Dr') . '. ' . \Auth::user()->priceFormat(abs($record['netAmount']))); ?>

                                                                        <?php elseif($record['netAmount'] > 0): ?>
                                                                            <?php echo e(__('Cr') . '. ' . \Auth::user()->priceFormat($record['netAmount'])); ?>

                                                                        <?php else: ?>
                                                                            <?php echo e(\Auth::user()->priceFormat(0)); ?>

                                                                        <?php endif; ?>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </tbody>
                                                        <thead>
                                                            <tr>
                                                                <th><?php echo e(__('Total') . ' ' . $subTypeLevel2['subTypeLevel2']); ?>

                                                                </th>
                                                                <th>
                                                                    <?php $total= $totalCredit-$totalDebit; ?>
                                                                    <?php if($total < 0): ?>
                                                                        <?php echo e(__('Dr') . '. ' . \Auth::user()->priceFormat(abs($total))); ?>

                                                                    <?php elseif($total > 0): ?>
                                                                        <?php echo e(__('Cr') . '. ' . \Auth::user()->priceFormat($total)); ?>

                                                                    <?php else: ?>
                                                                        <?php echo e(\Auth::user()->priceFormat(0)); ?>

                                                                    <?php endif; ?>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                            <?php
                                                $subTypeTotalCredit += $totalCredit;
                                                $subTypeTotalDebit += $totalDebit;
                                            ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>

                                    <h5><?php echo e(__('Total') . ' ' . $subType['subType']); ?>

                                        <?php $subTypeTotal= $subTypeTotalCredit-$subTypeTotalDebit; ?>
                                        <?php if($subTypeTotal < 0): ?>
                                            <?php echo e(__('Dr') . '. ' . \Auth::user()->priceFormat(abs($subTypeTotal))); ?>

                                        <?php elseif($subTypeTotal > 0): ?>
                                            <?php echo e(__('Cr') . '. ' . \Auth::user()->priceFormat($subTypeTotal)); ?>

                                        <?php else: ?>
                                            <?php echo e(\Auth::user()->priceFormat(0)); ?>

                                        <?php endif; ?>
                                    </h5>
                                </div>
                            </div>
                            <?php
                                $typeTotalCredit += $subTypeTotalCredit;
                                $typeTotalDebit += $subTypeTotalDebit;
                            ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\projects\new-erp-pgl\resources\views/report/balance_sheet.blade.php ENDPATH**/ ?>