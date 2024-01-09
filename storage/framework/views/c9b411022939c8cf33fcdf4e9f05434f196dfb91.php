<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Financial Project Report')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Financial Project Report')); ?></li>
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


        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('tbl_exporttable_to_xls');
            var wb = XLSX.utils.table_to_book(elt, {
                sheet: "sheet1"
            });
            return dl ?
                XLSX.write(wb, {
                    bookType: type,
                    bookSST: true,
                    type: 'base64'
                }) :
                XLSX.writeFile(wb, fn || ('MySheetName.' + (type || 'xlsx')));
        }
    </script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('action-btn'); ?>
    <div class="float-end">
        <a href="#" class="btn btn-sm btn-primary" onclick="saveAsPDF()"data-bs-toggle="tooltip"
            title="<?php echo e(__('Download')); ?>" data-original-title="<?php echo e(__('Download')); ?>">
            <span class="btn-inner--icon"><i class="ti ti-download"></i></span>
        </a>

        <a href="#" class="btn btn-sm btn-primary" onclick="ExportToExcel('xlsx')"data-bs-toggle="tooltip"
            title="<?php echo e(__('Excel')); ?>" data-original-title="<?php echo e(__('Excel')); ?>">
            <span class="btn-inner--icon"><i class="ti ti-file-export"></i></span>
        </a>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        <?php echo e(Form::open(['route' => ['report.finacialprojectreport'], 'method' => 'GET', 'id' => 'report_financial_project_report'])); ?>

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
                                            <?php echo e(Form::label('project', __('Project'), ['class' => 'form-label'])); ?>

                                            <?php echo e(Form::select('project', $projects, $filter['project'], ['class' => 'month-btn form-control'])); ?>

                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-auto mt-4">
                                <div class="row">
                                    <div class="col-auto">

                                        <a href="#" class="btn btn-sm btn-primary"
                                            onclick="document.getElementById('report_financial_project_report').submit(); return false;"
                                            data-bs-toggle="tooltip" title="<?php echo e(__('Apply')); ?>"
                                            data-original-title="<?php echo e(__('apply')); ?>">
                                            <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                        </a>

                                        <a href="<?php echo e(route('report.finacialprojectreport')); ?>"
                                            class="btn btn-sm btn-danger " data-bs-toggle="tooltip"
                                            title="<?php echo e(__('Reset')); ?>" data-original-title="<?php echo e(__('Reset')); ?>">
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
                <div class="card p-4 mb-4">
                    <h6 class="mb-0"><?php echo e(__('Project')); ?> :</h6>
                    <h7 class="text-sm mb-0"><?php echo e(!empty($data['project']) ? $data['project']->project_name : ''); ?></h7>
                </div>
            </div>

            <div class="col">
                <div class="card p-4 mb-4">
                    <h6 class="mb-0"><?php echo e(__('Duration')); ?> :</h6>
                    <h7 class="text-sm mb-0">
                        <?php echo e(!empty($data['project']) ? $data['project']->start_date . ' to ' . $data['project']->end_date : ''); ?>

                    </h7>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table id="tbl_exporttable_to_xls" class="table table-flush">
                                <thead>
                                    <tr>
                                        <th> <?php echo e(__('')); ?></th>
                                        <th> <?php echo e(__('Debit Total')); ?></th>
                                        <th> <?php echo e(__('Credit Total')); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Contract Sum</td>
                                        <td></td>
                                        <td><?php echo e(!empty($data['contract_sum']) ? \Auth::user()->priceFormat($data['contract_sum']) : '0'); ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Local Tax</td>
                                        <td></td>
                                        <td><?php echo e(!empty($data['tax_amount']) ? \Auth::user()->priceFormat($data['tax_amount']) : '0'); ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Net Contract Sum</td>
                                        <td></td>
                                        <td><?php echo e(!empty($data['net_contract_sum']) ? \Auth::user()->priceFormat($data['net_contract_sum']) : '0'); ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Revenue received</td>
                                        <td></td>
                                        <td><?php echo e(!empty($data['revenue']) ? \Auth::user()->priceFormat($data['revenue']) : '0'); ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Expense</td>
                                        <td><?php echo e(!empty($data['expense']) ? \Auth::user()->priceFormat($data['expense']) : '0'); ?>

                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Balance to be paid</td>
                                        <td><?php echo e(!empty($data['balance_to_be_paid']) ? \Auth::user()->priceFormat($data['balance_to_be_paid']) : '0'); ?>

                                        </td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>Expected Net Profit</td>
                                        <td><?php echo e(!empty($data['expected_net_profit']) ? \Auth::user()->priceFormat($data['expected_net_profit']) : '0'); ?>

                                        </td>
                                        <td></td>
                                    </tr>

                                </tbody>
                                <tfooter>
                                    <td class="text-dark"><strong><?php echo e(__('Actual Net Profit')); ?></strong></td>
                                    <td><?php echo e(!empty($data['actual_net_profit']) ? ($data['actual_net_profit'] > 0 ? \Auth::user()->priceFormat($data['actual_net_profit']) : '') : ''); ?>

                                    </td>
                                    <td>(<?php echo e(!empty($data['actual_net_profit']) ? ($data['actual_net_profit'] < 0 ? \Auth::user()->priceFormat(abs($data['actual_net_profit'])) : '') : ''); ?>)
                                    </td>
                                </tfooter>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\projects\new-erp-pgl\resources\views/report/financial_project_report.blade.php ENDPATH**/ ?>