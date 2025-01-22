<?php
    $totalFiles = \App\Models\DocumentsCategory::where('branch_id', auth()->user()->staff->branch_id)->count();
    $totalDocuments = \App\Models\Documents::where('branch_id', auth()->user()->staff->branch_id)->count();
    $pendingDocuments = \App\Models\Documents::where('branch_id', auth()->user()->staff->branch_id)->whereHas('notification', function($query) {
    $query->where('is_read', false);
    })->count();
    //$departmentFiles = \App\Models\Documents::where('branch_id', auth()->user()->staff->branch_id)->where('department_id', auth()->user()->staff->department_id)->count();
    $branchFiles = \App\Models\Documents::where('branch_id', auth()->user()->staff->branch_id)->count();
// Laravel code to get total number of shared documents
// Count the number of shared documents for the authenticated user's branch
$sharedDocumentsCount = \App\Models\DocumentHasUser::with('documents')  // Eager load the related documents
    ->join('documents_manager', 'documents_has_users.document_id', '=', 'documents_manager.id')  // Join with documents_manager table
    ->where('documents_manager.branch_id', auth()->user()->staff->branch_id)  // Filter by the user's branch
    ->count();  // Count the number of rows (shared documents)
    // Laravel code to get total number of documents uploaded today
$todayUploads = \App\Models\Documents::where('branch_id', auth()->user()->staff->branch_id)->whereDate('created_at', now()->toDateString())->count();
$todayCatUploads = \App\Models\DocumentsCategory::where('branch_id', auth()->user()->staff->branch_id)->whereDate('created_at', now()->toDateString())->count();
// Laravel code to get total number of files by category
$categoriesWithDocumentCount = \App\Models\DocumentsCategory::where('branch_id', auth()->user()->staff->branch_id)->withCount('documents')->count();


$documents_cat = \App\Models\DocumentsCategory::where('branch_id', auth()->user()->staff->branch_id)
  ->withCount('documents')
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
                            <i class="fas fa-file-alt me-2 text-yellow" style="font-size: 35pt; "></i>    
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
                            <i class="fas fa-file-pdf me-2" style="font-size: 35pt; color:green"></i>    
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
                            <i class="fas fa-file-alt me-2" style="font-size: 35pt; color:blue"></i>    
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
                                <span class="counter-value" data-target="{{ $branchFiles }}">{{ $branchFiles }}</span>
                            </h4>
                        </div>
                        <div class="col-2 mt-3" style="margin-left: 0px;padding-left:0px;">
                            <i class="fas fa-globe me-2" style="font-size: 35pt; color:brown"></i>    
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
                            <span class="text-muted mb-3 lh-1 d-block ">Total Documents Shared in My Location</span>
                            <h4 class="mb-3">
                                <span class="counter-value" data-target="{{ $sharedDocumentsCount }}">{{ $sharedDocumentsCount }}</span>
                            </h4>
                        </div>
                        <div class="col-2 mt-3" style="margin-left: 0px;padding-left:0px;">
                            <i class="fas fa-globe me-2" style="font-size: 35pt; color:red"></i>    
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
                            <i class="fas fa-upload me-2" style="font-size: 35pt; color:pink"></i>    
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
                            <i class="fas fa-upload me-2" style="font-size: 35pt; color:violet"></i>    
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
                            <i class="fas fa-file-alt me-2" style="font-size: 35pt; color:rgb(255, 192, 221)"></i>    
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