@extends('layouts.admin')
@push('page_scripts')
<!-- Required datatable js -->
<script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<!-- Buttons examples -->
<script src="{{ asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/libs/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>

<!-- Datatable init js -->
<script src="{{ asset('assets/js/pages/datatables.init.js') }}"></script> 
@endpush

@section('content')
@include('layouts.messages')
    {{-- <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> My Departmental Files</h1>
                </div>
                
            </div>
            
        </div>
    </section> --}}

    <div class="content px-3">

        @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


        <div class="clearfix"></div>
       
       <h3>Departmental File Archives</h3>
    </div>

    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
     <div class="tab-content" id="myTabContent" style="">
        <div class="tab-pane" id="home" role="tabpanel">   
                   <div class="card">
              <div class="card-body p-5">
                  <div class="table-responsive">
                      {{-- <table class="table align-middle gs-0 gy-4" id="document-category"> --}}
                        <table id="datatable1" class="table table-bordered dt-responsive nowrap w-100">
                            <thead>
                              <tr>
                                  <th>File Index No.</th>
                                  <th>File No.</th>
                                  <th>Subject</th>
                                  <th>Total Documents</th>
                                  <th>Manage</th>
                              </tr>
                          </thead>
                          <tbody> 
                              @foreach ($documents_categories as $index => $documents_category)
                                  <tr>
                                      <td>{{ $documents_category->id }}</td> <!-- Use $index + 1 as the serial number -->
                                      <td>{{ $documents_category->name ? $documents_category->name : '' }}</td>
                                      <td>{{ $documents_category->description }}</td>
                                      <td>{{ $documents_category->documents()->count() ?? 'N/A' }}</td>
                              <td>
                                  @can("update files")
                                          <a style="display: none;padding-right:10px;" href="{{ route('documents_category.edit', $documents_category->id) }}" title="Edit Document Category">
                                              <span class="nk-menu-icon text-info"><em class="fa fa-edit"></em></span>
                                          </a>
                                  @endcan       
                                                
                                                  
                                      </td>
                                  </tr>
                              @endforeach
                          </tbody>
                      </table>
                      
                      
                                  </div>

                  </div> </div>
        </div>
        <div class="tab-pane active" id="profile" role="tabpanel">
            <div class="row mb-2">
                {{-- @can("create files")
               <div class="col-sm-6">
                   <a class="open-modal-file btn btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#fileModal"
                  >Add New File</a>
               </div>
               @endcan --}}
           </div>
          <div class="card">
              <div class="card-body p-5">
                
                {{-- <form method="post" action="{{ route('documents.by.files.post') }}">
                    <div class="row mb-5">
                       
                            @csrf
                            <div class="col-md-3">
                                <label for="category_id">File No.</label>
                                <select name="category_id" id="category_id" class="form-control form-control-solid border border-2 form-select">
                                    <option value="">Select File No.</option> 
                                    @foreach($documents_categories1 as $documents_category)
                                        <option value="{{ $documents_category->id }}" {{ (!empty($_POST['category_id']) && $_POST['category_id'] == $documents_category->id) ? 'selected' : '' }}>
                                            {{ $documents_category->name }}
                                        </option>
                                    @endforeach
                                </select>        
                            </div>        
                            <div class="col-md-3 mt-4"> <button class="btn btn-primary" type="submit" name="submit">Search</button> </div>
                        
                    </div>
                    </form> --}}
                    <form method="GET" action="{{ route('documents.by.files.shared') }}" class="mb-5">
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                {{-- <label for="priority_id" class="col-form-label">Priority</label> --}}
                                <select name="priority_id" id="priority_id" class="form-control" required>
                                    <option value="" disabled selected>Select Priority</option>
                                    @foreach ($priorities as $priority)
                                        <option value="{{ $priority->id }}" {{ request('priority_id') == $priority->id ? 'selected' : '' }}>
                                            {{ $priority->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                        
                    </form>
                    
        <div class="table-responsive">
            <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                <thead>
                    <tr>
                        <th>Document</th>
                        <th>Title</th>
                         <th>Date Added</th>
                        <th>Share User</th>
                        {{-- @endif --}}
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $n =1; @endphp
                    @foreach ($mergedDocuments as $index => $document)
                        
                       <tr>
                            @if (!empty($document['lock_code']))
                            
                            <td>
                                <div class="locked-file1" data-lock-code="{{ $document['lock_code'] }}">
                                    <div class="locked-file">
                                       
                                <?php 

                                    ?>
                                @if ($document['document_url'])
                                <a class="document-link real-content" style="display: none;" 
                                href="{{ route('documents_manager.info.shared', $document['d_id']) }}" >
                                <i class="fa fa-file text-primary fs-14"></i> {{ substr($document['document_url'], 10) }}
                             </a>  
                                @else
                                <a class="document-link real-content" style="display: none;" 
                                   href="{{ route('documents_manager.details.shared', $document['catId']) }}" >
                                   <i class="fa fa-folder text-yellow fs-19"></i> {{ $document['cat_name'] }}
                                </a>
                                @endif
                                       
                            </div>
                            <!-- Hidden lock input & verify button -->
                        <div class="locked-content1 lock-input1" >
                            <input type="text" class="lock-code-input form-control" placeholder="Enter lock code">
                            <button class="btn btn-primary verify-lock-btn">Verify</button>
                            <p class="error-message text-danger" style="display: none;">Incorrect lock code!</p>
                        </div>
                    </div>  
                            </td>
                            <td class="locked-content">{{ $document['name'] ?? $document['description'] ?? 'NILL' }}</td>
                            <td class="locked-content">@if (!empty($document['document_created_at']))
                                {{ get_datetime_format($document['document_created_at']) }}
                                @else
                                {{ get_datetime_format($document['createdAt']) }}
                            @endif
                                </td>
                            {{-- @if(Auth::user()->hasRole('super-admin')) --}}
                            <td class="locked-content" style="width: 120px;">
                                
                                @if(!empty($document['allow_share']) && $document['allow_share'] == 1)
                                <div class="btn-group" role="group">
                                    @if (!empty($document['d_id']))
                                    <a class="open-modal-shareuser btn btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#shareuserModal"
                                        data-shareuser={{ $document['d_id'] }}>Share</a>
                                      
                                    @else
                                    <a class="open-modal-shareuserfile btn btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#shareuserfileModal"
                                        data-shareuserfile={{ $document['catId'] }} data-documentId={{ $document['d_id'] }}>Share</a>
                                      
                                    @endif
                                      
                                  
                                </div>
                                @endif
                            </td>
                           {{--  @endif --}}
                            <td class="locked-content">
                                @if (!empty($document['d_id']))
                                 <div class="flex-shrink-0 text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" style="background: green;"  type="button" id="dropdownMenuButton" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                            Click to View Options
                                        </button>
                                
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <!-- View Document -->
                                            <a target="_blank" 
                                               href="{{ getDocumentUrl($document['document_url']) }}" 
                                               class="btn btn-default btn-xs dropdown-item">
                                                <i class="far fa-eye"></i> View
                                            </a>
                                
                                            <!-- Edit Document (for Super Admin) -->
                                            @if(Auth::user()->hasRole('super-admin') && !empty($document['d_id']))
                                                <a style="display: none" href="{{ route('documents_manager.edit', [$document['d_id']]) }}" 
                                                   class="btn btn-default btn-xs dropdown-item">
                                                    <i class="far fa-edit"></i> Edit
                                                </a>
                                            @endif
                                
                                            <!-- Assign Document -->
                                            <a class="open-modal-share btn btn-default btn-xs dropdown-item" href="#" 
                                               data-bs-toggle="modal" data-bs-target="#shareModal" data-share="{{ $document['d_id'] }}">
                                                <i class="fa fa-share-alt"></i> Assign to
                                            </a>
                                
                                            <!-- Download Document (for Super Admin or if the document is downloadable) -->
                                            @if(Auth::user()->hasRole('super-admin'))
                                                <a class="btn btn-default btn-xs dropdown-item" 
                                                   href="{{ getDocumentUrl($document['document_url']) }}" download>
                                                    <i class="fa fa-download"></i> Download
                                                </a>
                                            @elseif(!empty($document['is_download']) && $document['is_download'] == 1)
                                                <a class="btn btn-default btn-xs dropdown-item" 
                                                   href="{{ getDocumentUrl($document['document_url']) }}" download>
                                                    <i class="fa fa-download"></i> Download
                                                </a>
                                            @endif
                                
                                            <!-- Upload New Version File -->
                                            <a class="open-modal-upload btn btn-default btn-xs dropdown-item" href="#" 
                                               data-bs-toggle="modal" data-bs-target="#uploadsModal" data-upload="{{ $document['d_id'] }}">
                                                <i class="fa fa-upload"></i> Upload New Version File
                                            </a>
                                
                                            <!-- Document Version History -->
                                            <a class="open-modal-history btn btn-default btn-xs dropdown-item" href="#" 
                                               data-bs-toggle="modal" data-bs-target="#historyModal" data-history="{{ $document['d_id'] }}">
                                                <i class="fa fa-history"></i> Version History
                                            </a>
                                
                                            <!-- Comment on Document -->
                                            <a class="open-modal-comment btn btn-default btn-xs dropdown-item" href="#" 
                                               data-bs-toggle="modal" data-bs-target="#commentModal" data-comment="{{ $document['d_id'] }}" 
                                               data-commenter="{{ $document['document_url'] }}">
                                                <i class="fa fa-comment"></i> Comment
                                            </a>
                                
                                            <!-- Add Reminder -->
                                            {{-- <a class="btn btn-default btn-xs dropdown-item" href="#">
                                                <i class="fa fa-bell"></i> Add Reminder
                                            </a> --}}
                                
                                            <!-- Send Email -->
                                            <a class="open-modal-sendemail btn btn-default btn-xs dropdown-item" href="#" 
                                               data-bs-toggle="modal" data-bs-target="#sendEmailModal" 
                                               data-sendemail="{{ $document['d_id'] }}" data-sendemailer="{{ $document['document_url'] }}">
                                                <i class="far fa-envelope"></i> Send Email
                                            </a>
                                
                                        </div>
                                    </div>
                                </div>
                                @endif
                                
                              
                            </td>
                           
                            
                            @else
                            <div>
                            <td>
                                <?php 

                                    ?>
                                @if ($document['document_url'])
                                <a class="document-link" 
                                href="{{ route('documents_manager.info.shared', $document['d_id']) }}" >
                                <i class="fa fa-file text-primary fs-14"></i> {{ substr($document['document_url'], 10) }}
                             </a>  
                                @else
                                <a class="document-link"
                                   href="{{ route('documents_manager.details.shared', $document['catId']) }}" >
                                   <i class="fa fa-folder text-yellow fs-19"></i> {{ $document['cat_name'] }}
                                </a>
                                @endif
                                
                            </td>
                            <td>{{ $document['name'] ?? $document['description'] ?? 'NILL' }}</td>
                            <td>@if (!empty($document['document_created_at']))
                                {{ get_datetime_format($document['document_created_at']) }}
                                @else
                                {{ get_datetime_format($document['createdAt']) }}
                            @endif
                                </td>
                            {{-- @if(Auth::user()->hasRole('super-admin')) --}}
                            <td style="width: 120px;">
                                
                                @if(!empty($document['allow_share']) && $document['allow_share'] == 1)
                                <div class="btn-group" role="group">
                                    @if (!empty($document['d_id']))
                                    <a class="open-modal-shareuser btn btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#shareuserModal"
                                        data-shareuser={{ $document['d_id'] }}>Share</a>
                                      
                                    @else
                                    <a class="open-modal-shareuserfile btn btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#shareuserfileModal"
                                        data-shareuserfile={{ $document['catId'] }} data-documentId={{ $document['d_id'] }}>Share</a>
                                      
                                    @endif
                                      
                                  
                                </div>
                                @endif
                            </td>
                           {{--  @endif --}}
                            <td>
                                @if (!empty($document['d_id']))
                                 <div class="flex-shrink-0 text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" style="background: green;"  type="button" id="dropdownMenuButton" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                            Click to View Options
                                        </button>
                                
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <!-- View Document -->
                                            <a target="_blank" 
                                               href="{{ getDocumentUrl($document['document_url']) }}" 
                                               class="btn btn-default btn-xs dropdown-item">
                                                <i class="far fa-eye"></i> View
                                            </a>
                                
                                            <!-- Edit Document (for Super Admin) -->
                                            @if(Auth::user()->hasRole('super-admin') && !empty($document['d_id']))
                                                <a style="display: none" href="{{ route('documents_manager.edit', [$document['d_id']]) }}" 
                                                   class="btn btn-default btn-xs dropdown-item">
                                                    <i class="far fa-edit"></i> Edit
                                                </a>
                                            @endif
                                
                                            <!-- Assign Document -->
                                            <a class="open-modal-share btn btn-default btn-xs dropdown-item" href="#" 
                                               data-bs-toggle="modal" data-bs-target="#shareModal" data-share="{{ $document['d_id'] }}">
                                                <i class="fa fa-share-alt"></i> Assign to
                                            </a>
                                
                                            <!-- Download Document (for Super Admin or if the document is downloadable) -->
                                            @if(Auth::user()->hasRole('super-admin'))
                                                <a class="btn btn-default btn-xs dropdown-item" 
                                                   href="{{ getDocumentUrl($document['document_url']) }}" download>
                                                    <i class="fa fa-download"></i> Download
                                                </a>
                                            @elseif(!empty($document['is_download']) && $document['is_download'] == 1)
                                                <a class="btn btn-default btn-xs dropdown-item" 
                                                   href="{{ getDocumentUrl($document['document_url']) }}" download>
                                                    <i class="fa fa-download"></i> Download
                                                </a>
                                            @endif
                                
                                            <!-- Upload New Version File -->
                                            <a class="open-modal-upload btn btn-default btn-xs dropdown-item" href="#" 
                                               data-bs-toggle="modal" data-bs-target="#uploadsModal" data-upload="{{ $document['d_id'] }}">
                                                <i class="fa fa-upload"></i> Upload New Version File
                                            </a>
                                
                                            <!-- Document Version History -->
                                            <a class="open-modal-history btn btn-default btn-xs dropdown-item" href="#" 
                                               data-bs-toggle="modal" data-bs-target="#historyModal" data-history="{{ $document['d_id'] }}">
                                                <i class="fa fa-history"></i> Version History
                                            </a>
                                
                                            <!-- Comment on Document -->
                                            <a class="open-modal-comment btn btn-default btn-xs dropdown-item" href="#" 
                                               data-bs-toggle="modal" data-bs-target="#commentModal" data-comment="{{ $document['d_id'] }}" 
                                               data-commenter="{{ $document['document_url'] }}">
                                                <i class="fa fa-comment"></i> Comment
                                            </a>
                                
                                            <!-- Add Reminder -->
                                            {{-- <a class="btn btn-default btn-xs dropdown-item" href="#">
                                                <i class="fa fa-bell"></i> Add Reminder
                                            </a> --}}
                                
                                            <!-- Send Email -->
                                            <a class="open-modal-sendemail btn btn-default btn-xs dropdown-item" href="#" 
                                               data-bs-toggle="modal" data-bs-target="#sendEmailModal" 
                                               data-sendemail="{{ $document['d_id'] }}" data-sendemailer="{{ $document['document_url'] }}">
                                                <i class="far fa-envelope"></i> Send Email
                                            </a>
                                
                                        </div>
                                    </div>
                                </div>
                                @endif
                                
                              
                            </td>
                        </tr>
                            @endif
                        </tr>
                        
                    @endforeach
                </tbody>
            </table>
        </div>
    
        <script>
            document.addEventListener('DOMContentLoaded', function () {
        // Add click event to the blurred content to reveal the lock input
        document.querySelectorAll('.locked-file1').forEach(function (content) {
            content.addEventListener('click', function () {
                let lockedFile = this.closest('.locked-file1');
                // Show the lock code input and hide the blurred content
                lockedFile.querySelector('.locked-content1.lock-input1').style.display = 'block';
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
                    lockedFile.querySelector('.locked-content1.lock-input1').style.display = 'none'; // Hide lock input
                    lockedFile.querySelector('.real-content').style.display = 'block'; // Show real content
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
</div>
</div>
</div>

<!-- Modal -->
<div class="modal fade" id="uploadsModal" tabindex="-1" role="dialog" aria-labelledby="uploadsModalModalLabel"
aria-hidden="true" data-backdrop="false">
<div class="modal-dialog" role="document">
    {!! Form::open(['route' => 'documents_manager.add', 'enctype' => 'multipart/form-data']) !!}
    @csrf
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Upload New Version</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">

            <div class="form-group">
                {!! Form::label('file', 'Upload any file:') !!}
<div class="input-group">
    <div class="custom-file">
        {!! Form::file('file', ['class' => ' form-control']) !!}
    </div>
</div>
            </div>

            <!-- Memo Id Field -->
            {!! Form::hidden('upload_id', null, ['id' => 'upload_id']) !!}

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">SUBMIT</button>
        </div>
    </div>
    {!! Form::close() !!}
</div>
</div>

<div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel"
aria-hidden="true" data-backdrop="false">
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Document History</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">

            <div class="form-group">
                <table class="table" id="document-table-history">
                    <thead>
                        <tr>
                            <th>Created By</th>
                            <th>Document URL <span id="curr"></span> </th>
                            <th>Created Date</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
                </table>
            </div>

            

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
           
        </div>
    </div>
  
</div>
</div>

<div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="commentModalLabel"
aria-hidden="true" data-backdrop="false">
<div class="modal-dialog " role="document">
    <div class="modal-content">
        {!! Form::open(['route' => 'documents_manager.add_comment', 'enctype' => 'multipart/form-data']) !!}
    @csrf
        <div class="modal-header">
            <h5 class="modal-title" id="curr1"></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">

            <div class="form-group">
                <table class="table" id="document-table-comment" style="display:none;">
                    <thead>
                        <tr>
                            <th>Comment</th>
                            <th>Created Date</th>
                            <th>Created By</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
                </table>
                {!! Form::label('comment', 'Type your comment:') !!}
                <div class="form-group">
                    <div class="custom-comment">
                        {!! Form::textarea('comment', null, ['class' => 'form-control']) !!}
                    </div>
                </div>
                {!! Form::hidden('comment_id', null, ['id' => 'comment_id']) !!}
            </div>

            

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">SUBMIT</button>
        </div>
        {!! Form::close() !!}
    </div>
  
</div>
</div>

<div class="modal fade" id="sendEmailModal" tabindex="-1" role="dialog" aria-labelledby="sendEmailModalLabel"
aria-hidden="true" data-backdrop="false">
<div class="modal-dialog " role="document">
    <div class="modal-content">
        {!! Form::open(['route' => 'documents_manager.send_email', 'enctype' => 'multipart/form-data']) !!}
    @csrf
        <div class="modal-header">
            <h5 class="modal-title"> Send Email</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">

            <div class="form-group">
                <table class="table" id="document-table-sendemail" style="display:none;">
                    <thead>
                        <tr>
                            <th>Comment</th>
                            <th>Created Date</th>
                            <th>Created By</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
                </table>
                <div class="form-group">
                {!! Form::label('email', 'To:') !!}
                <div class="input-group">
                   
                        {!! Form::email('to', null, ['class' => 'form-control', 'required' => 'required']) !!}
                   
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('subject', 'Subject:') !!}
                <div class="input-group">
                   
                        {!! Form::text('subject', null, ['class' => 'form-control', 'required' => 'required']) !!}
                    
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('body', 'Body:') !!}
                <div class="input-group">
                        {!! Form::textarea('body', null, ['class' => 'form-control', 'required' => 'required']) !!}
                   
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('attachment', 'Attachment:') !!}
                <div class="input-group">
                        {!! Form::text('attachment', null, ['class' => 'form-control', 'id' => 'sendemailer', 'readonly' => 'readonly']) !!}
                 </div>
            </div>
                {!! Form::hidden('sendemail_id', null, ['id' => 'sendemail_id']) !!}
            </div>

            

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary"><i class="fa fa-send"></i> SEND EMAIL</button>
        </div>
        {!! Form::close() !!}
    </div>
  
</div>
</div>

<div class="modal fade" id="shareModal" tabindex="-1" role="dialog" aria-labelledby="shareModalLabel"
aria-hidden="true" data-backdrop="false">
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Share Document</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <p><span id="share1"></span></p>

            <div class="form-group">
                <table class="table" id="document-table-share">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Allow Download</th>
                            <th>User/Role Name</th>
                            <th>Email</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
                </table>
            </div>

            

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
           
        </div>
    </div>
  
</div>
</div>

<div class="modal fade" id="shareuserModal" tabindex="-1" role="dialog" aria-labelledby="shareuserModalLabel"
aria-hidden="true" data-backdrop="false">
<div class="modal-dialog " role="document">
    <div class="modal-content">
        {!! Form::open(['route' => 'documents_manager.shareuser', 'enctype' => 'multipart/form-data']) !!}
    @csrf
        <div class="modal-header">
            <h5 class="modal-title">User Permission</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">

            <div class="form-group">
                <label for="userSelect" class="form-label font-size-13 text-muted">Select User(s)</label>
                                                        <select class="form-control" data-trigger="" name="users[]" id="userSelect" placeholder="This is a placeholder" multiple="">
                                                            @foreach ($userData as $user)
                                                            <option value="{{ $user['id'] }}">{{ $user['name'] }}</option>
                                                            @endforeach
                                                        </select>
                
                {!! Form::hidden('shareuser_id', null, ['id' => 'shareuser_id']) !!}
                {!! Form::hidden('notify_id', null, ['id' => 'notify_id']) !!}
            </div>
            <div class="form-group">
                {!! Form::checkbox('specify_su', 0, null, ['id' => 'specify_su']) !!}
                {!! Form::label('specify_su', 'Specify the period') !!}
            </div>
            <div class="form-group" id="enable_date" style="display: none">
                {!! Form::label('start_date', 'Start Date') !!}
                {!! Form::date('start_date', null, ['class' => 'form-control','id' => 'start_date1']) !!}<br/>
                {!! Form::label('end_date', 'End Date') !!}
                {!! Form::date('end_date', null, ['class' => 'form-control','id' => 'end_date1']) !!}
            </div>
            <div class="form-group">
                {!! Form::checkbox('is_download', 1, ['id' => 'is_download']) !!}
                {!! Form::label('is_download', 'Allow Download') !!}
            </div>
            <div class="form-group">
                {!! Form::checkbox('allow_share', 1, ['id' => 'allow_share']) !!}
                {!! Form::label('allow_share', 'Allow Share') !!}
            </div>
            
            <div class="form-group">
                <label for="priority_id">Priority</label>
                <select name="priority_id" id="priority_id" class="form-control" required onchange="shareDoc(this.value)">
                    <option value="" disabled selected>Select Priority</option>
                    @foreach ($priorities as $priority)
                        <option value="{{ $priority->id }}">{{ $priority->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" id="lock_code_field" style="display: none;">
                {!! Form::label('lock_code', 'Lock Code') !!}
                {!! Form::number('lock_code', null, ['class' => 'form-control', 'placeholder' => 'Enter Lock Code']) !!}
            </div>
            
            <script>
                function shareDoc(confidentialCheckbox) {
                    var lockCodeField = document.getElementById('lock_code_field');
            
                    // Check the initial state of the checkbox and set lock code field visibility accordingly
                    if (confidentialCheckbox == 7) {
                        lockCodeField.style.display = 'block';  // Show if checked
                    } else {
                        lockCodeField.style.display = 'none';   // Hide if unchecked
                    }
            
                    // Toggle visibility when checkbox state changes
                    confidentialCheckbox.addEventListener('change', function() {
                        lockCodeField.style.display = this.checked ? 'block' : 'none';
                    });
                };
            </script>
            {!! Form::label('comment', 'Type your comment:') !!}
                <div class="form-group">
                    <div class="custom-comment">
                        {!! Form::textarea('comment', null, ['class' => 'form-control']) !!}
                    </div>
                </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">SUBMIT</button>
        </div>
        {!! Form::close() !!}
    </div>
  
</div>
</div>

<div class="modal fade" id="shareuserfileModal" tabindex="-1" role="dialog" aria-labelledby="shareuserfileModalLabel"
aria-hidden="true" data-backdrop="false">
<div class="modal-dialog " role="document">
    <div class="modal-content">
        {!! Form::open(['route' => 'documents_manager.shareuserfile', 'enctype' => 'multipart/form-data']) !!}
    @csrf
        <div class="modal-header">
            <h5 class="modal-title">File Permission</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">

            <div class="form-group">
                <label for="fileSelect" class="form-label font-size-13 text-muted">Select User(s)</label>
                                                        <select class="form-control" data-trigger="" name="users[]" id="fileSelect" placeholder="This is a placeholder" multiple="">
                                                            @foreach ($userData as $user)
                                                            <option value="{{ $user['id'] }}">{{ $user['name'] }}</option>
                                                            @endforeach
                                                        </select>
                
                {!! Form::hidden('shareuser_id', null, ['id' => 'shareuserfile_id']) !!}
                {!! Form::hidden('document_id', null, ['id' => 'shareuserdocumentId']) !!}
                {!! Form::hidden('notify_id', null, ['id' => 'notify_id']) !!}
            </div>
            <div class="form-group">
                {!! Form::checkbox('specify_su', 0, null, ['id' => 'specify_su']) !!}
                {!! Form::label('specify_su', 'Specify the period') !!}
            </div>
            <div class="form-group" id="enable_date" style="display: none">
                {!! Form::label('start_date', 'Start Date') !!}
                {!! Form::date('start_date', null, ['class' => 'form-control','id' => 'start_date1']) !!}<br/>
                {!! Form::label('end_date', 'End Date') !!}
                {!! Form::date('end_date', null, ['class' => 'form-control','id' => 'end_date1']) !!}
            </div>
            <div class="form-group">
                {!! Form::checkbox('is_download', 1, ['id' => 'is_download']) !!}
                {!! Form::label('is_download', 'Allow Download') !!}
            </div>
            <div class="form-group">
                {!! Form::checkbox('allow_share', 1, ['id' => 'allow_share']) !!}
                {!! Form::label('allow_share', 'Allow Share') !!}
            </div>
            
            <div class="form-group">
                <label for="priority_id">Priority</label>
                <select name="priority_id" id="priority_id" class="form-control" required onchange="shareFile(this.value)">
                    <option value="" disabled selected>Select Priority</option>
                    @foreach ($priorities as $priority)
                        <option value="{{ $priority->id }}">{{ $priority->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" id="lock_code_field1" style="display: none;">
                {!! Form::label('lock_code', 'Lock Code') !!}
                {!! Form::number('lock_code', null, ['class' => 'form-control', 'placeholder' => 'Enter Lock Code']) !!}
            </div>
            
            <script>
                function shareFile(confidentialCheckbox) {
                    var lockCodeField = document.getElementById('lock_code_field1');
            
                    // Check the initial state of the checkbox and set lock code field visibility accordingly
                    if (confidentialCheckbox == 7) {
                        lockCodeField.style.display = 'block';  // Show if checked
                    } else {
                        lockCodeField.style.display = 'none';   // Hide if unchecked
                    }
            
                    // Toggle visibility when checkbox state changes
                    confidentialCheckbox.addEventListener('change', function() {
                        lockCodeField.style.display = this.checked ? 'block' : 'none';
                    });
                };
            </script>
            
            {!! Form::label('comment', 'Type your comment:') !!}
                <div class="form-group">
                    <div class="custom-comment">
                        {!! Form::textarea('comment', null, ['class' => 'form-control']) !!}
                    </div>
                </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">SUBMIT</button>
        </div>
        {!! Form::close() !!}
    </div>
  
</div>
</div>

<div class="modal fade" id="fileModal" tabindex="-1" role="dialog" aria-labelledby="fileModalLabel"
aria-hidden="true" data-backdrop="false">
<div class="modal-dialog " role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Add New File</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="{{ route('documents_category.store') }}" method="POST">
            @csrf 
        <div class="modal-body">
            
        <div class="row" style="display: none">
            <div class="col-12">
                <label class="form-label-outlined" for="department_id">Select Department</label>
                <select class="form-control" name="department_id" id="department_id">
                    {{-- <option value="">Select Department</option> --}}
                     @foreach($departments as $department)
                     <option value="{{ $department->id }}" {{ old('department_id') }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-12 mt-3">
                <div class="form-group" >
                    <div class="form-control-wrap" >
                        <div class="form-icon form-icon-right">
                            <em class="icon ni ni-user"></em>
                        </div>
                        <label class="form-label-outlined" for="flle_no">File No.</label>
                        <input type="text" class="form-control form-control-xl form-control-outlined"
                            id="file_no" name="name" value="{{ rand(11111, 99999) }}" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 mt-3">
                <div class="form-group">
                    <div class="form-control-wrap">
                        <div class="form-icon form-icon-right">
                            <em class="icon ni ni-user"></em>
                        </div>
                        <label class="form-label-outlined" for="description">Subject</label>
                        <input type="text" class="form-control form-control-xl form-control-outlined"
                            id="description" name="description" value="{{old('description')}}" required>
                        
                    </div>
                </div>
            </div>
        </div>
       
    {{-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> --}}
    @push('page_scripts')
    <script>
        $(document).ready(function () {
            $('#department_id').change(function () {
                var departmentId = $(this).val();
                $('.loader-demo-box1').show();
                if (departmentId) {
                    $.ajax({
                        url: '/generate-file-no',
                        type: 'POST',
                        data: {
                            department_id: departmentId,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            $('.loader-demo-box1').hide();
                           // alert(JSON.stringify(response));
                            $('#file_no').val(response.data.name);
                        },
                        error: function (response) {
                            $('.loader-demo-box1').hide();
                            alert("Can not generate file no. Contact administrator for help");
                        }
                    });
                }
            });
        });
    </script>  
    @endpush
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">SUBMIT</button>
        </div>
    </form>
       
    </div>
</div>
</div>


<div class="modal fade" id="shareroleModal" tabindex="-1" role="dialog" aria-labelledby="shareroleModalLabel"
aria-hidden="true" data-backdrop="false">
<div class="modal-dialog " role="document">
    <div class="modal-content">
        {!! Form::open(['route' => 'documents_manager.sharerole', 'enctype' => 'multipart/form-data']) !!}
    @csrf
        <div class="modal-header">
            <h5 class="modal-title">Role Permission</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">

            <div class="form-group">
                {!! Form::label('roles', 'Select Roles(s):') !!}
                {!! Form::select('roles[]', $roles, null, ['class' => 'form-control', 'id' => 'roleSelect', 'multiple' => 'multiple']) !!}
                
                {!! Form::hidden('sharerole_id', null, ['id' => 'sharerole_id']) !!}
            </div>
            <div class="form-group">
                {!! Form::checkbox('specify_role', 0, null, ['id' => 'specify_role']) !!}
                {!! Form::label('specify_role', 'Specify the period') !!}
            </div>
            <div class="form-group" id="enable_date1" style="display: none">
                {!! Form::label('start_date', 'Start Date') !!}
                {!! Form::date('start_date', null, ['class' => 'form-control','id' => 'start_date1']) !!}<br/>
                {!! Form::label('end_date', 'End Date') !!}
                {!! Form::date('end_date', null, ['class' => 'form-control','id' => 'end_date1']) !!}
            </div>
            <div class="form-group">
                {!! Form::checkbox('is_download', 1, ['id' => 'is_download']) !!}
                {!! Form::label('is_download', 'Allow Download') !!}
            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">SUBMIT</button>
        </div>
        {!! Form::close() !!}
    </div>
  
</div>
</div>

<div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="commentModalLabel"
aria-hidden="true" data-backdrop="false">
<div class="modal-dialog " role="document">
    <div class="modal-content">
        {!! Form::open(['route' => 'documents_manager.add_comment', 'enctype' => 'multipart/form-data']) !!}
    @csrf
        <div class="modal-header">
            <h5 class="modal-title" id="curr1"></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">

            <div class="form-group">
                <table class="table" id="document-table-comment" style="display:none;">
                    <thead>
                        <tr>
                            <th>Comment</th>
                            <th>Created Date</th>
                            <th>Created By</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
                </table>
                {!! Form::label('comment', 'Type your comment:') !!}
                <div class="input-group">
                    <div class="custom-comment">
                        {!! Form::textarea('comment', null, ['class' => 'form-control']) !!}
                    </div>
                </div>
                {!! Form::hidden('comment_id', null, ['id' => 'comment_id']) !!}
            </div>

            

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">SUBMIT</button>
        </div>
        {!! Form::close() !!}
    </div>
  
</div>
</div>

</div>
    
   
    @push('page_scripts')
        {{-- @if ($errors->has('subject') || $errors->has('answer_1') || $errors->has('answer_2') || $errors->has('answer_3'))
            <script>
                $('#feedbackModal').modal();
            </script>
        @endif --}}
        <script>
            $(document).ready(function() {
                // When the checkbox is clicked
                $('#specify_su').change(function() {
                    if ($(this).is(':checked')) {
                        // If checked, show the enable_date div
                        $('#enable_date').show();
                    } else {
                        // If unchecked, hide the enable_date div
                        $('#enable_date').hide();
                    }
                });
                $('#specify_role').change(function() {
                    if ($(this).is(':checked')) {
                        // If checked, show the enable_date div
                        $('#enable_date1').show();
                    } else {
                        // If unchecked, hide the enable_date div
                        $('#enable_date1').hide();
                    }
                });
            });
        </script>
        <script>
            function confirmDelete() {
                $("#delete-btn").click();
             }
         
         </script>
         
        <script>
           
            $(document).on("click", ".open-modal-upload", function() {
                let upload = $(this).data('upload');
                $(".modal-body #upload_id").val(upload);
            });
            $(document).on("click", ".open-modal-shareuser", function() {
                let shareuser = $(this).data('shareuser');
                $(".modal-body #shareuser_id").val(shareuser);
            });
            $(document).on("click", ".open-modal-shareuserfile", function() {
                let shareuser = $(this).data('shareuserfile');
                let documentId = $(this).data('documentId');
                $(".modal-body #shareuserfile_id").val(shareuser);
                $(".modal-body #shareuserdocumentId").val(documentId);
            });
            $(document).on("click", ".open-modal-sharerole", function() {
                let sharerole = $(this).data('sharerole');
                $(".modal-body #sharerole_id").val(sharerole);
            });
            $(document).on("click", ".open-modal-sendemail", function() {
                let sendemail = $(this).data('sendemail');
                let sendemailer = $(this).data('sendemailer');
                $(".modal-body #sendemail_id").val(sendemail);
                $(".modal-body #sendemailer").val(sendemailer);
            });
    
            $(document).on("click", ".open-modal-history", function() {
        let documentId = $(this).data('history');
    
        // Clear previous table data
        $('#document-table-history tbody').empty();
    
        // AJAX request to fetch document history
        $.ajax({
            url: '/documents_manager/version/' + documentId,
            type: 'GET',
            success: function(response) {
                // Populate table with fetched data
                response.forEach(function(history) {
        let row = $('<tr>');
        row.append($('<td>').text(history.firstName + ' ' + history.lastName));
        /* if (history.document_url == history.doc_url) {
            row.append($('<td>').html(history.document_url + ' <span class="btn-primary" style="background: green;">current version</span>'));
        } else {
            row.append($('<td>').text(history.document_url));
        } */
        row.append($('<td>').text(history.document_url));
        row.append($('<td>').text(history.createdAt));
            $('#curr').html(history.document_url + ' <span class="btn-primary" style="background: green;">current version</span>')
        $('#document-table-history tbody').append(row);
    });
    
    
                // Show the modal
                $('#historyModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error(error);
                alert('Failed to fetch document history. ');
            }
        });
    });
    
    $(document).on("click", ".open-modal-comment", function() {
       // $('#mycomment').css('display','none');
        let documentId = $(this).data('comment');
        let commenter = $(this).data('commenter');
        $(".modal-body #comment_id").val(documentId);
        $('#curr1').html(commenter + "'s Comment");
        // Clear previous table data
        $('#document-table-comment tbody').empty();
    
        // AJAX request to fetch document comment
        $.ajax({
            url: '/documents_manager/comment/' + documentId,
            type: 'GET',
            success: function(response) {
                // Populate table with fetched data
                $('#document-table-comment').css('display','block');
                response.forEach(function(history) {
        let row = $('<tr>');
        row.append($('<td>').text(history.comment));
        row.append($('<td>').text(history.createdAt));
        row.append($('<td>').text(history.firstName + ' ' + history.lastName));
        $('#document-table-comment tbody').append(row);
    });
    
    
                // Show the modal
                $('#commentModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error(error);
                alert('Failed to fetch document comments. ');
            }
        });
    });
    
    $(document).on("click", ".open-modal-share", function() {
        let share = $(this).data('share');
    
        // Clear previous table data
        $('#document-table-share tbody').empty();
    
        // AJAX request to fetch document share
        $.ajax({
            url: '/documents_manager/share/' + share,
            type: 'GET',
            success: function(response) {
                // Populate table with fetched data
                response.forEach(function(history) {
        let row = $('<tr>');
            row.append($('<td>').text('User'));
                if(history.is_download == 1){
            row.append($('<td>').text('Yes'));
            }else{
                row.append($('<td>').text('No'));
            }
                if(history.firstName){
        row.append($('<td>').text(history.firstName + ' ' + history.lastName));
        }
            
            row.append($('<td>').text(history.uemail));
        row.append($('<td>').text(history.start_date));
            row.append($('<td>').text(history.end_date));
            $('#share1').html("<b>Document Name:</b> "+history.doc_url+' '+ "<b>Description:</b> "+history.doc_desc)
        $('#document-table-share tbody').append(row);
    });
    
    
                // Show the modal
                $('#shareModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error(error);
                alert('Failed to fetch document share history. ');
            }
        });
    });
        </script>
        
        
    
 <!-- choices js -->
 <script src="{{ asset('assets/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>
 <!-- init js -->
 <script>
     document.addEventListener('DOMContentLoaded', function () {
         // Initialize Choices.js with remove item button enabled for userData
         new Choices('#userSelect', {
             removeItemButton: true, // Enable the remove button
             placeholder: true,
             placeholderValue: 'Select user(s)', // Placeholder for the select field
             searchPlaceholderValue: 'Search for a user...', // Placeholder for the search field
             noResultsText: 'No users found', // Text when no results are found
             searchEnabled: true, // Enable search functionality
             itemSelectText: '', // Text when selecting an item (remove the "Select" text)
         });
     });
     document.addEventListener('DOMContentLoaded', function () {
         // Initialize Choices.js with remove item button enabled for userData
         new Choices('#fileSelect', {
             removeItemButton: true, // Enable the remove button
             placeholder: true,
             placeholderValue: 'Select user(s)', // Placeholder for the select field
             searchPlaceholderValue: 'Search for a user...', // Placeholder for the search field
             noResultsText: 'No users found', // Text when no results are found
             searchEnabled: true, // Enable search functionality
             itemSelectText: '', // Text when selecting an item (remove the "Select" text)
         });
     });
 </script>
  @endpush
@endsection
