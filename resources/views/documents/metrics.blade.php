
<?php

//coding starts here
/* $untreatedDocuments = \App\Models\Documents::where('status', '0')
                       ->where('branch_id', auth()->user()->staff->branch_id)
                       ->where('department_id', auth()->user()->staff->department_id)
                       ->where('created_by', Auth()->user()->id)->count();
$pendingDocuments = \App\Models\Documents::where('documents_manager.status', '1')
->join('documents_has_users', 'documents_has_users.document_id', '=', 'documents_manager.id')
->where('documents_has_users.user_id', auth()->user()->id)->count();
$treatedDocuments = \App\Models\Documents::where('documents_manager.status', '2')
->join('documents_has_users', 'documents_has_users.document_id', '=', 'documents_manager.id')
->where('documents_has_users.user_id', auth()->user()->id)->count(); 16, 3 = 19 */
// Count documents where status is 0 and created by the current user
$untreatedDocumentsManager = \App\Models\Documents::where('documents_manager.status', '0')
    ->where('documents_manager.created_by', Auth()->user()->id)
    ->count();

// Count documents that the current user is associated with from documents_has_users
$untreatedDocumentsHasUsers = \App\Models\Documents::join('documents_has_users', 'documents_has_users.document_id', '=', 'documents_manager.id')
    ->where('documents_has_users.user_id', Auth()->user()->id)
    ->where('documents_manager.status', '1')
    ->count();

// Combine both counts
$totalUntreatedDocuments = $untreatedDocumentsManager + $untreatedDocumentsHasUsers;

// If the count from documents_manager is 0 but documents_has_users has results, it won't affect the final result.

$pendingDocuments = \App\Models\Documents::where('documents_manager.status', '1')
->where('documents_manager.created_by', Auth()->user()->id)->count();


$treatedDocuments1 = \App\Models\Documents::where('documents_manager.status', '2')
->join('documents_has_users', 'documents_has_users.document_id', '=', 'documents_manager.id')
->where('documents_manager.created_by', Auth()->user()->id)->count();

$treatedDocuments2 = \App\Models\Documents::where('documents_manager.status', '2')
->join('documents_has_users', 'documents_has_users.document_id', '=', 'documents_manager.id')
->where('documents_has_users.user_id', auth()->user()->id)->count();

//comments query and new version documents query
$documentComment = \App\Models\DocumentComment::join('documents_manager', 'documents_comments.document_id', '=', 'documents_manager.id')
->where('documents_manager.status', '2')
->where('documents_comments.created_by', Auth()->user()->id)->count();

$newVersion = \App\Models\DocumentHistory::where('documents_manager.status', '2')
->join('documents_manager', 'documents_histories.document_id', '=', 'documents_manager.id')
->where('documents_histories.created_by', Auth()->user()->id)->count();

// Combine both counts
$totalTreatedDocuments = $treatedDocuments1 + $treatedDocuments2 + $documentComment + $newVersion;

?>
<style>
    span.d-block{
        font-weight: 600;
    }
    .counter-value{
        font-weight: 600;
    }
   
</style>
<div class="row">
    <div class="col-xl-8">
        <div class="row"> <!-- Add the row class here for the parent -->
            <!-- First Card -->
            <div class="col-md-4">
                <div class="card card-h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-10">
                                <span class="text-muted mb-3 lh-1 d-block">Untreated Documents</span>
                                <h4 class="mb-3">
                                    <span class="counter-value" data-target="{{ $totalUntreatedDocuments }}">{{ $totalUntreatedDocuments }}</span>
                                </h4>
                            </div>
                            <div class="col-2 mt-3" style="margin-left: 0px;padding-left:0px;">
                                <i class="fas fa-file-alt me-2" style="font-size: 30pt; color: #5156be;"></i>
                            </div>
                        </div>
                        <div class="text-nowrap">
                            <a href="{{ route('documents.by.files', 'u_id=1') }}" class="btn btn-primary btn-sm">View all <i class="mdi mdi-arrow-right ms-1"></i></a>
                        </div>
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col-->
    
            <!-- Second Card -->
            <div class="col-md-4">
                <div class="card card-h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-10">
                                <span class="text-muted mb-3 lh-1 d-block">Pending Documents</span>
                                <h4 class="mb-3">
                                    <span class="counter-value" data-target="{{ $pendingDocuments }}">{{ $pendingDocuments }}</span>
                                </h4>
                            </div>
                            <div class="col-2 mt-3" style="margin-left: 0px;padding-left:0px;">
                                <i class="fas fa-file-alt me-2 text-yellow" style="font-size: 30pt;"></i>
                            </div>
                        </div>
                        <div class="text-nowrap">
                            <a href="{{ route('documents.by.files.shared', 'p_id=1') }}" class="btn btn-primary btn-sm">View all <i class="mdi mdi-arrow-right ms-1"></i></a>
                        </div>
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col-->
    
            <!-- Third Card -->
            <div class=" col-md-4">
                <div class="card card-h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-10">
                                <span class="text-muted mb-3 lh-1 d-block">Treated Documents</span>
                                <h4 class="mb-3">
                                    <span class="counter-value" data-target="{{ $totalTreatedDocuments }}">{{ $totalTreatedDocuments }}</span>
                                </h4>
                            </div>
                            <div class="col-2 mt-3" style="margin-left: 0px;padding-left:0px;">
                                <i class="fas fa-file-alt me-2" style="font-size: 30pt; color: #309241;"></i>
                            </div>
                        </div>
                        <div class="text-nowrap">
                            <a href="{{ route('documents.by.files.shared', 't_id=1') }}" class="btn btn-primary btn-sm">View all <i class="mdi mdi-arrow-right ms-1"></i></a>
                        </div>
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col-->
        </div><!-- end row -->

        <div class="row mb-2">
            
           {{-- <div class="col-sm-6">
               <a class="open-modal-file btn btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#fileModal"
              ><i class="fas fa-file-alt me-2"></i> Add New File</a>
           </div> --}}
           <div class="col-md-4">
            <div class="card card-h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        @can("create files")
                        <div class="col-4 col-12 ">
                            <a class="open-modal-file btn btn-secondary btn-sm" style="background: #309241;width: 180px;padding: 8px; font-weight: 600;font-size: 10pt;" href="#" data-bs-toggle="modal" data-bs-target="#fileModal">
                                <i class="fas fa-file-alt me-2 text-white"></i> Add New File  
                            </a>
                        </div>
                        @endcan
                    
                        <div class="col-4 col-12 mt-3">
                            <a class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-file-alt me-2 text-white"></i> Upload Document <i class="mdi mdi-chevron-down"></i></a>
                            <div class="dropdown-menu" style="">                                   
                                 <a class="open-modal-document dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#documentModal">
                                        <i class="fas fa-file-alt me-2"></i> <!-- Document Icon -->
                                        Create Document
                                    </a>
                                    <a class="open-modal-upload-document dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                                        <i class="fas fa-upload me-2"></i> <!-- Upload Icon -->
                                        Upload Document
                                    </a>
                                    
                                </div>
                        </div>
                            
                        </div>
                        
                    </div>
                    
                </div><!-- end card body -->
            </div>
            <div class="col-sm-8">
                <div class="card card-h-100">
                    <div class="card-body">
                <h4>Track My Document</h4>
                {{-- <h5 class="font-size-14 mb-4"> Correspondence Number</h5> --}}
{{--                 <form class="row gx-3 gy-2 align-items-center mt-5" method="get" action="{{ route('documents.by.files') }}">
 --}}                    {{-- @csrf --}}
                    <div class="hstack gap-3">
                        <input id="searchInput" class="form-control me-auto" type="number" name="document_no" placeholder="Enter Correspondence Number..." required>
                        <button type="button" class="btn btn-secondary" onclick="getSearch()"><i class="fa fa-search"></i></button>
                    </div>
                    @push('page_scripts')
                    <script>
                     $(document).ready(function() {
    // Initialize DataTable
    var table = $('#datatable33').DataTable({pagingType: "simple",});

    // Function to perform custom search
    window.getSearch = function() {
        // Get the search term from the custom input
        var searchTerm = $('#searchInput').val().trim().toLowerCase();

        // Set the search term into the default DataTable search input
        $('input[type="search"]').val(searchTerm);

        // Trigger the DataTable search
        table.search(searchTerm).draw();

        // Scroll to the table to view the results
        $('html, body').animate({
            scrollTop: $("#datatable33").offset().top
        }, 500); // Adjust the scroll speed as necessary
    };
});

                    </script>
                    
                    @endpush
{{--                 </form>
 --}}            </div>
        </div>
            </div>
            
        </div><!-- end col-->
           
       </div>

    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <div class="row">
<div class="col-md-6">
    <h5 class="card-title mb-3">Assigned Documents</h5>
</div>
<div class="col-md-6">
    <a href="{{ route('documents.by.files.shared') }}" class="float-end">View All</a>
</div>
                </div>
                <div class="list-group list-group-flush">
                    @forelse ($sharedDocument as $sharedDocument1)
                    @if (!empty($sharedDocument1['lock_code']))
                    <a href="#" class="list-group-item list-group-item-action locked-file1" data-lock-code="{{ $sharedDocument1['lock_code'] }}" >
                        <div class="d-flex align-items-center locked-file">
                            <div class="avatar-sm flex-shrink-0 me-3 locked-content">
                                @if (isset($sharedDocument1->profile_picture))
                         
                              
            <img class="img-thumbnail rounded-circle" src="{{ asset('storage/' . $sharedDocument1->profile_picture) }}" alt="{{ $sharedDocument1->profile_picture }}">
            
                        @else
            
                        <img class="img-thumbnail rounded-circle" src="{{ asset('assets/images/blank.png') }}" alt="image" />
                        @endif                            </div>
                            <div class="flex-grow-1">
                                <div class="">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5 class="font-size-14 mb-1 locked-content">
                                                
                                                {{ (!empty($sharedDocument1->name)) ? $sharedDocument1->name : 'Name not available' }}

                                            </h5>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="font-size-13 text-muted mb-0 text-danger float-end locked-content"><b style="color:{{ $sharedDocument1->color_code }}">{{ !empty($sharedDocument1->priority_name) ? $sharedDocument1->priority_name : '' }} </b></p>
                                        </div>
                                    </div>
                                    <p class="font-size-13 text-muted mb-0 locked-content">{{ !empty($sharedDocument1->title) ? $sharedDocument1->title : 'NILL' }}</p>
                                    <p class="font-size-13 text-muted mb-0 locked-content">{{ !empty($sharedDocument1->createdAt) ? timeAgo($sharedDocument1->createdAt) : '' }}</p>
                                </div>
                                
                            </div>
                        </div>
                        <!-- Hidden lock input & verify button -->
                        <div class="locked-content1 lock-input" style="display: none;">
                            <input type="text" class="lock-code-input form-control" placeholder="Enter lock code">
                            <button class="btn btn-primary verify-lock-btn">Verify</button>
                            <p class="error-message text-danger" style="display: none;">Incorrect lock code!</p>
                        </div>
                    </a>
                    @else
                    <a href="#" class="list-group-item list-group-item-action" >
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm flex-shrink-0 me-3">
                                @if (isset($sharedDocument1->profile_picture))
                         
                                
            <img class="img-thumbnail rounded-circle" src="{{ asset('storage/' . $sharedDocument1->profile_picture) }}" alt="{{ $sharedDocument1->profile_picture }}">
            
                        @else
            
                        <img class="img-thumbnail rounded-circle" src="{{ asset('assets/images/blank.png') }}" alt="image" />
                        @endif                            </div>
                            <div class="flex-grow-1">
                                <div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5 class="font-size-14 mb-1">
                                                
                                                {{ (!empty($sharedDocument1->name)) ? $sharedDocument1->name : 'Name not available' }}

                                            </h5>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="font-size-13 text-muted mb-0 text-danger float-end"><b style="color:{{ $sharedDocument1->color_code }}">{{ !empty($sharedDocument1->priority_name) ? $sharedDocument1->priority_name : '' }} </b></p>
                                        </div>
                                    </div>
                                    <p class="font-size-13 text-muted mb-0">{{ !empty($sharedDocument1->title) ? $sharedDocument1->title : 'NILL' }}</p>
                                    <p class="font-size-13 text-muted mb-0">{{ !empty($sharedDocument1->createdAt) ? timeAgo($sharedDocument1->createdAt) : '' }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                     
                    @endif
                    @empty
                   <p>No shared documents found.</p>
                   @endforelse
                                        
                </div>
            </div>
            <!-- end card body -->
        </div>
    </div>
</div>

    <div class="row mt-5">
        <div class="col-xl-12">
            <h5>Recent Files</h5>
            <div class="row"> <!-- Add the row class here for the parent -->
                @forelse ($files as $file)
                @if (!empty($file['lock_code']))
                <div class="col-md-3 locked-file1" data-lock-code="{{ $file['lock_code'] }}">
                    <div class="card card-h-100">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-12 locked-file" >
                                    <!-- Initially Blurred Locked Content -->
                                    <span class="mb-3 lh-1 d-block locked-content">
                                        <a href="{{ route('documents_manager.details.shared', $file['catId']) }}" class="document-link" style="color: #333;">
                                            <i class="fa fa-folder text-yellow fs-19"></i> {{ $file['category_name'] }}
                                        </a>
                                    </span>
                                    <p class="font-size-13 text-muted mb-0 locked-content">
                                        {{ $file['date'] }} . {{ $file['file_size'] }} . {{ $file['shared_count'] }} Members
                                    </p>
        
                                    
                                </div>
                                <!-- Hidden lock input & verify button -->
                                <div class="locked-content1 lock-input" style="display: none;">
                                    <input type="text" class="lock-code-input form-control" placeholder="Enter lock code">
                                    <button class="btn btn-primary verify-lock-btn">Verify</button>
                                    <p class="error-message text-danger" style="display: none;">Incorrect lock code!</p>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col-->
                @else
                    <div class="col-md-3">
                        <div class="card card-h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-12">
                                        <span class="mb-3 lh-1 d-block">
                                            <a href="{{ route('documents_manager.details.shared', $file['catId']) }}" class="document-link" style="color: #333;">
                                                <i class="fa fa-folder text-yellow fs-19"></i> {{ $file['category_name'] }}
                                            </a>
                                        </span>
                                        <p class="font-size-13 text-muted mb-0">
                                            {{ $file['date'] }} . {{ $file['file_size'] }} . {{ $file['shared_count'] }} Members
                                        </p>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col-->
                @endif
            @empty
                <div class="col-md-3">
                    <div class="card card-h-100">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-12">
                <p>No files found.</p>
            </div>
            {{-- <div class="col-2">
                <!-- Optionally display file icon or image -->
                <a href="{{ $file['file_url'] }}" target="_blank" class="btn btn-sm btn-primary">Download</a>
            </div> --}}
        </div>
    </div><!-- end card body -->
</div><!-- end card -->
</div><!-- end col-->
                @endforelse
                <div class="col-md-3"><a href="{{ route('documents.by.files.shared', 'c_id=1') }}" class="btn btn-sm btn-primary">View All Files</a></div>
            </div><!-- end row -->
        </div>
        
    </div>

    <div class="row">
        <div class="col-xl-12">
            <h5>Recent Documents</h5>
            <div class="row"> <!-- Add the row class here for the parent -->
                @forelse ($shared_documents as $shared_document)
                @if (!empty($shared_document['lock_code']))
                <div class="col-md-3 locked-file1" data-lock-code="{{ $shared_document['lock_code'] }}" >
                    <div class="card card-h-100">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-12 locked-file">
                                    <span class="mb-3 lh-1 d-block locked-content">
                                        <a class="document-link" style="color: #333;"
                                href="{{ route('documents_manager.info.shared', $shared_document['d_id']) }}" >
                                <i class="fa fa-file text-primary fs-14"></i> {{ substr($shared_document['file_url'], 10) }}
                             </a>
                                       
                                    </span>
                                    <p class="font-size-13 text-muted mb-0 locked-content">
                                        {{ $shared_document['date'] }} . {{ $shared_document['file_size'] }} . {{ $shared_document['shared_count'] }} Members
                                    </p>
                                </div>
                                <!-- Hidden lock input & verify button -->
                                <div class="locked-content1 lock-input" style="display: none;">
                                    <input type="text" class="lock-code-input form-control" placeholder="Enter lock code">
                                    <button class="btn btn-primary verify-lock-btn">Verify</button>
                                    <p class="error-message text-danger" style="display: none;">Incorrect lock code!</p>
                                </div>
                                {{-- <div class="col-2">
                                    <!-- Optionally display file icon or image -->
                                    <a href="{{ $file['file_url'] }}" target="_blank" class="btn btn-sm btn-primary">Download</a>
                                </div> --}}
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col-->
                @else
                <div class="col-md-3">
                    <div class="card card-h-100">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <span class="mb-3 lh-1 d-block">
                                        <a class="document-link" style="color: #333;"
                                href="{{ route('documents_manager.info.shared', $shared_document['d_id']) }}" >
                                <i class="fa fa-file text-primary fs-14"></i> {{ substr($shared_document['file_url'], 10) }}
                             </a>
                                       
                                    </span>
                                    <p class="font-size-13 text-muted mb-0">
                                        {{ $shared_document['date'] }} . {{ $shared_document['file_size'] }} . {{ $shared_document['shared_count'] }} Members
                                    </p>
                                </div>
                                {{-- <div class="col-2">
                                    <!-- Optionally display file icon or image -->
                                    <a href="{{ $file['file_url'] }}" target="_blank" class="btn btn-sm btn-primary">Download</a>
                                </div> --}}
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col-->
                @endif
                @empty
                <div class="col-md-3">
                    <div class="card card-h-100">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-12">
                <p>No documents found.</p>
            </div>
            {{-- <div class="col-2">
                <!-- Optionally display file icon or image -->
                <a href="{{ $file['file_url'] }}" target="_blank" class="btn btn-sm btn-primary">Download</a>
            </div> --}}
        </div>
    </div><!-- end card body -->
</div><!-- end card -->
</div><!-- end col-->
                @endforelse
                <div class="col-md-3"><a href="{{ route('documents.by.files.shared', 'd_id=1') }}" class="btn btn-sm btn-primary">View All Documents</a></div>
            </div><!-- end row -->
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
    // Add click event to the blurred content to reveal the lock input
    document.querySelectorAll('.locked-file1').forEach(function (content) {
        content.addEventListener('click', function () {
            let lockedFile = this.closest('.locked-file1');
            // Show the lock code input and hide the blurred content
            lockedFile.querySelector('.locked-content1.lock-input').style.display = 'block';
            lockedFile.querySelector('.locked-content').style.display = 'none'; // Hide the blurred content
        });
    });

    // Verify the lock code when the user clicks the verify button
    document.querySelectorAll('.verify-lock-btn').forEach(function (button) {
        button.addEventListener('click', function () {
            let lockedFile = this.closest('.locked-file1');
            let input = lockedFile.querySelector('.lock-code-input');
            let enteredCode = input.value.trim();
            let correctCode = lockedFile.getAttribute('data-lock-code');

            // If the code is correct, reveal the real content and remove blur
            if (enteredCode === correctCode) {
                // Hide the lock input and show the real content
                input.style.display = 'none';
                this.style.display = 'none';
                lockedFile.querySelector('.locked-content1.lock-input').style.display = 'none'; // Hide lock input
                //lockedFile.querySelector('.real-content').style.display = 'block'; // Show real content
                lockedFile.querySelector('.error-message').style.display = 'none'; // Hide error message

                // Remove the blur effect from the locked content
                lockedFile.querySelectorAll('.locked-content').forEach(function (elem) {
                    elem.style.filter = 'none'; // Remove blur effect
                    elem.style.pointerEvents = 'auto'; // Enable interaction with content
                });
            } else {
                // Display error message if the code is incorrect
                lockedFile.querySelector('.error-message').style.display = 'block';
            }
        });
    });
});


        </script>