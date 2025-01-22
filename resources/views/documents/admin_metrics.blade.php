
<?php
$totalFiles = \App\Models\DocumentsCategory::count();
$totalDocuments = \App\Models\Documents::count();
$pendingDocuments = \App\Models\Documents::whereHas('notification', function($query) {
$query->where('is_read', false);
})->count();
$departmentFiles = \App\Models\Documents::with('department')->count();
$branchFiles = \App\Models\Documents::with('branch')->count();
// Laravel code to get total number of shared documents
$sharedDocuments = \App\Models\DocumentHasUser::count();
// Laravel code to get total number of documents uploaded today
$todayUploads = \App\Models\Documents::whereDate('created_at', now()->toDateString())->count();
$todayCatUploads = \App\Models\DocumentsCategory::whereDate('created_at', now()->toDateString())->count();
// Laravel code to get total number of files by category
$categoriesWithDocumentCount = \App\Models\DocumentsCategory::withCount('documents')->count();


$documents_cat = \App\Models\DocumentsCategory::withCount('documents')
->where('branch_id', Auth()->user()->staff->branch_id)
->where('department_id', Auth()->user()->staff->department_id)
->count();
?>
<div class="row">
    <div class="col-xl-3 col-md-6">
        <!-- card -->
        <div class="card card-h-100">
            <!-- card body -->
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-10">
                        <span class="text-muted mb-3 lh-1 d-block ">Total Number of Files</span>
                        <h4 class="mb-3">
                            <span class="counter-value" data-target="{{ $totalFiles }}">{{ $totalFiles }}</span>
                        </h4>
                    </div>
                    <div class="col-2 mt-3" style="margin-left: 0px;padding-left:0px;">
                        <i class="fas fa-file-alt me-2 text-yellow" style="font-size: 35pt;"></i>    
                    </div>

                </div>
                {{-- <div class="text-nowrap">
                    <span class="badge bg-danger-subtle text-danger">-29 data</span>
                    <span class="ms-1 text-muted font-size-13">Since last week</span>
                </div> --}}
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col-->
    <div class="col-xl-3 col-md-6">
        <!-- card -->
        <div class="card card-h-100">
            <!-- card body -->
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-10">
                        <span class="text-muted mb-3 lh-1 d-block ">Total Number of Documents</span>
                        <h4 class="mb-3">
                            <span class="counter-value" data-target="{{ $totalDocuments }}">{{ $totalDocuments }}</span>
                        </h4>
                    </div>
                    <div class="col-2 mt-3" style="margin-left: 0px;padding-left:0px;">
                        <i class="fas fa-file me-2" style="font-size: 35pt; color: #5156be;"></i>
                    </div>
                    
                </div>
                {{-- <div class="text-nowrap">
                    <span class="badge bg-danger-subtle text-danger">-29 data</span>
                    <span class="ms-1 text-muted font-size-13">Since last week</span>
                </div> --}}
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col-->
    <div class="col-xl-3 col-md-6">
        <!-- card -->
        <div class="card card-h-100">
            <!-- card body -->
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-10">
                        <span class="text-muted mb-3 lh-1 d-block ">Total Number of Documents Pending Review</span>
                        <h4 class="mb-3">
                            <span class="counter-value" data-target="{{ $pendingDocuments }}">{{ $pendingDocuments }}</span>
                        </h4>
                    </div>
                    <div class="col-2 mt-3" style="margin-left: 0px;padding-left:0px;">
                        <i class="fas fa-file-word me-2" style="font-size: 35pt; color: #d9534f;"></i>
                    </div>
                    
                </div>
                {{-- <div class="text-nowrap">
                    <span class="badge bg-danger-subtle text-danger">-29 data</span>
                    <span class="ms-1 text-muted font-size-13">Since last week</span>
                </div> --}}
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col-->
    <div class="col-xl-3 col-md-6">
        <!-- card -->
        <div class="card card-h-100">
            <!-- card body -->
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-10">
                        <span class="text-muted mb-3 lh-1 d-block ">Total Files in all Departments</span>
                        <h4 class="mb-3">
                            <span class="counter-value" data-target="{{ $departmentFiles }}">{{ $departmentFiles }}</span>
                        </h4>
                    </div>
                    <div class="col-2 mt-3" style="margin-left: 0px;padding-left:0px;">
                        <i class="fas fa-briefcase me-2" style="font-size: 35pt; color:blue"></i>    
                    </div>
                    
                </div>
                {{-- <div class="text-nowrap">
                    <span class="badge bg-danger-subtle text-danger">-29 data</span>
                    <span class="ms-1 text-muted font-size-13">Since last week</span>
                </div> --}}
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col-->
    <div class="col-xl-3 col-md-6">
        <!-- card -->
        <div class="card card-h-100">
            <!-- card body -->
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-10">
                        <span class="text-muted mb-3 lh-1 d-block ">Total Files in all Location</span>
                        <h4 class="mb-3">
                            <span class="counter-value" data-target="{{ $branchFiles }}">{{ $branchFiles }}</span>
                        </h4>
                    </div>
                    <div class="col-2 mt-3" style="margin-left: 0px;padding-left:0px;">
                        <i class="fas fa-layer-group me-2 " style="font-size: 35pt;color:aqua"></i>    
                    </div>
                    
                </div>
                {{-- <div class="text-nowrap">
                    <span class="badge bg-danger-subtle text-danger">-29 data</span>
                    <span class="ms-1 text-muted font-size-13">Since last week</span>
                </div> --}}
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col-->

    <div class="col-xl-3 col-md-6">
        <!-- card -->
        <div class="card card-h-100">
            <!-- card body -->
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-10">
                        <span class="text-muted mb-3 lh-1 d-block ">Total Files in My Location</span>
                        <h4 class="mb-3">
                            <span class="counter-value" data-target="{{ $sharedDocuments }}">{{ $sharedDocuments }}</span>
                        </h4>
                    </div>
                    <div class="col-2 mt-3" style="margin-left: 0px;padding-left:0px;">
                        <i class="fas fa-globe me-2 " style="font-size: 35pt; color:#d9534f"></i>    
                    </div>
                    
                </div>
                {{-- <div class="text-nowrap">
                    <span class="badge bg-danger-subtle text-danger">-29 data</span>
                    <span class="ms-1 text-muted font-size-13">Since last week</span>
                </div> --}}
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col-->

    <div class="col-xl-3 col-md-6">
        <!-- card -->
        <div class="card card-h-100">
            <!-- card body -->
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-10">
                        <span class="text-muted mb-3 lh-1 d-block ">Total Number of Documents Uploaded Today</span>
                        <h4 class="mb-3">
                            <span class="counter-value" data-target="{{ $todayUploads }}">{{ $todayUploads }}</span>
                        </h4>
                    </div>
                    <div class="col-2 mt-3" style="margin-left: 0px;padding-left:0px;">
                        <i class="fas fa-user-shield me-2" style="font-size: 35pt; color:aquamarine"></i>    
                    </div>
                    
                </div>
                {{-- <div class="text-nowrap">
                    <span class="badge bg-danger-subtle text-danger">-29 data</span>
                    <span class="ms-1 text-muted font-size-13">Since last week</span>
                </div> --}}
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col-->
    <div class="col-xl-3 col-md-6">
        <!-- card -->
        <div class="card card-h-100">
            <!-- card body -->
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-10">
                        <span class="text-muted mb-3 lh-1 d-block ">Total Number of Files Added Today</span>
                        <h4 class="mb-3">
                            <span class="counter-value" data-target="{{ $todayCatUploads }}">{{ $todayCatUploads }}</span>
                        </h4>
                    </div>
                    <div class="col-2 mt-3" style="margin-left: 0px;padding-left:0px;">
                        <i class="fas fa-cogs me-2" style="font-size: 35pt; color:green"></i>    
                    </div>
                    
                </div>
                {{-- <div class="text-nowrap">
                    <span class="badge bg-danger-subtle text-danger">-29 data</span>
                    <span class="ms-1 text-muted font-size-13">Since last week</span>
                </div> --}}
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col-->
    <div class="col-xl-3 col-md-6">
        <!-- card -->
        <div class="card card-h-100">
            <!-- card body -->
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-10">
                        <span class="text-muted mb-3 lh-1 d-block ">Total Number of Documents by Files</span>
                        <h4 class="mb-3">
                            <span class="counter-value" data-target="{{ $categoriesWithDocumentCount }}">{{ $categoriesWithDocumentCount }}</span>
                        </h4>
                    </div>
                    <div class="col-2 mt-3" style="margin-left: 0px;padding-left:0px;">
                        <i class="fas fa-file-alt me-2 " style="font-size: 35pt; color:beige"></i>    
                    </div>
                    
                </div>
                {{-- <div class="text-nowrap">
                    <span class="badge bg-danger-subtle text-danger">-29 data</span>
                    <span class="ms-1 text-muted font-size-13">Since last week</span>
                </div> --}}
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col-->



</div>