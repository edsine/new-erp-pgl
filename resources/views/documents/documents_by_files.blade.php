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
{{-- @push('page_css')
<!-- choices css -->
<link href="{{ asset('assets/libs/choices.js/public/assets/styles/choices.min.css') }}" rel="stylesheet" type="text/css">

 @endpush --}}
@section('content')
@include('layouts.messages')
  
    @include('documents.metrics')

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

<div class="flex-shrink-0">
    <ul class="nav justify-content-start nav-tabs-custom rounded card-header-tabs" role="tablist">
        <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="tab" href="#profile" role="tab">Departmental File Archives</a>
       
      </li>
  {{-- <li class="nav-item">
    <a class="nav-link" data-bs-toggle="tab" href="#home" role="tab">Incoming File Archives</a>
  </li> --}}
      
    </ul>
</div>
        <div class="clearfix"></div>
       
       
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
                       {{--  <table id="datatable1" class="table table-bordered dt-responsive nowrap w-100">
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
                                          <a style="padding-right:10px;" href="{{ route('documents_category.edit', $documents_category->id) }}" title="Edit Document Category">
                                              <span class="nk-menu-icon text-info"><em class="fa fa-edit"></em></span>
                                          </a>
                                  @endcan       
                                                
                                                  
                                      </td>
                                  </tr>
                              @endforeach
                          </tbody>
                      </table>
                      
                       --}}
                                  </div>

                  </div> </div>
        </div>
        <div class="tab-pane active" id="profile" role="tabpanel">
            {{-- <div class="row mb-2">
                @can("create files")
               <div class="col-sm-6">
                   <a class="open-modal-file btn btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#fileModal"
                  ><i class="fas fa-file-alt me-2"></i> Add New File</a>
               </div>
               @endcan
           </div> --}}
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
        <div class="table-responsive">
            <table id="datatable33" class="table table-bordered dt-responsive nowrap w-100">
                <thead>
                    <tr>
                        <th>Document</th>
                        <th>Correspondence No. </th>
                        <th>Title</th>
                         <th>Date Added</th>
                        <th>Share User</th>
                        {{-- @endif --}}
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="documentTableBody" >
                    @php $n =1; @endphp
                    @foreach ($mergedDocuments as $index => $document)
                        
                        <tr class="document-row">
                            <td>
                                <?php 

                                    ?>
                                @if ($document['document_url'])
                                <a class="document-link" 
                                href="{{ route('documents_manager.info', $document['d_id']) }}" >
                                <i class="fa fa-file text-primary fs-14"></i> {{ substr($document['document_url'], 10) }}
                             </a>  
                                @else
                                
                                <a class="document-link"
                                   href="{{ route('documents_manager.details', $document['catId']) }}" >
                                   <i class="fa fa-folder text-yellow fs-19"></i> {{ $document['cat_name'] }}
                                </a>
                               
                                @endif
                                
                            </td>
                            <td> {{ !empty($document['document_no']) ? $document['document_no'] : 'NILL' }}</td>
                            <td>{{ $document['name'] ?? $document['description'] ?? 'NILL' }}</td>
                                                        <td>@if (!empty($document['document_created_at']))
                                {{ get_datetime_format($document['document_created_at']) }}
                                @else
                                {{ get_datetime_format($document['createdAt']) }}
                            @endif
                                </td>
                            {{-- @if(Auth::user()->hasRole('super-admin')) --}}
                            <td style="width: 120px;">
                                
                                <div class="btn-group" role="group">
                                    @if (!empty($document['d_id']))
                                    <a class="open-modal-shareuser btn btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#shareuserModal"
                                        data-shareuser={{ $document['d_id'] }}>Share</a>
                                      
                                    @else
                                    <a class="open-modal-shareuserfile btn btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#shareuserfileModal"
                                        data-shareuserfile={{ $document['catId'] }}>Share</a>
                                      
                                    @endif
                                      
                                   {{--  @if(($document->allow_share == 1 && $document->user_id == Auth()->user()->id) || $document->assigned_by == Auth()->user()->id)
                                    <a class="open-modal-shareuser btn btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#shareuserModal"
                                        data-shareuser={{ $document['d_id'] }}>User</a>
                                @endif
     --}}
    {{-- <a class="open-modal-sharerole btn btn-danger" href="#" data-bs-toggle="modal" data-bs-target="#shareroleModal"
    data-sharerole={{ $document['d_id'] }}>Role</a> --}}
    
                                </div>
                                
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
                                
                                            <!-- Delete Document (for Super Admin) -->
                                            {{-- @if(Auth::user()->hasRole('super-admin'))
                                                <a class="btn btn-default btn-xs dropdown-item" href="#" onclick="confirmDelete()">
                                                    <i class="far fa-trash-alt"></i> Delete
                                                </a>
                                            @endif
                                 --}}
                                        </div>
                                    </div>
                                </div>
                                @endif
                                
                               {{--  {!! Form::open(['route' => ['documents_manager.destroy', $document['d_id']], 'method' => 'delete']) !!}
                                <div class='btn-group'>
                                    <a href="{{ route('documents_manager.show', [$document['d_id']]) }}"
                                        class='btn btn-default btn-xs'>
                                        <i class="far fa-eye"></i>
                                    </a>
                                    
                                    <a href="{{ route('documents_manager.edit', [$document['d_id']]) }}" class='btn btn-default btn-xs'>
                                        <i class="far fa-edit"></i>
                                    </a>
                                    {!! Form::button('<i class="far fa-trash-alt"></i>', [
                                        'type' => 'submit',
                                        'class' => 'btn btn-danger btn-xs',
                                        'onclick' => "return confirm('Are you sure?')",
                                    ]) !!}
                                </div>
                                {!! Form::close() !!} --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    

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
        {!! Form::open(['route' => 'documents_manager.shareuser', 'enctype' => 'multipart/form-data', 'class' =>'userForm']) !!}
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
                <select class="form-control" data-trigger="" name="users[]" id="userSelect" placeholder="Select a user(s)" multiple>
                    @foreach ($userData as $user)
                                                            <option value="{{ $user['id'] }}">{{ $user['name'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="form-text">Hold down the Ctrl (Windows) or Command (Mac) button to select multiple options.</div>

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
            
            <!-- Select dropdown for priority -->
    <div class="form-group">
        <label for="priority_id">Priority</label>
        <select name="priority_id" id="priority_id" class="form-control" required onchange="shareDoc(this.value)">
            <option value="" disabled selected>Select Priority</option>
            @foreach ($priorities as $priority)
            <option value="{{ $priority->id }}">{{ $priority->name .' - '.$priority->description }} </option>
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
            <button type="submit" class="btn btn-primary" >SUBMIT</button>
        </div>
        {!! Form::close() !!}
    </div>
    
</div>
</div>

<div class="modal fade" id="shareuserfileModal" tabindex="-1" role="dialog" aria-labelledby="shareuserfileModalLabel"
aria-hidden="true" data-backdrop="false">
<div class="modal-dialog " role="document">
    <div class="modal-content">
        {!! Form::open(['route' => 'documents_manager.shareuserfile', 'enctype' => 'multipart/form-data', 'class' =>'filePForm']) !!}
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
                <select class="form-control" data-trigger="" name="users[]" id="fileSelect" placeholder="Select a user(s)" multiple>
                    @foreach ($userData as $user)
                                                            <option value="{{ $user['id'] }}">{{ $user['name'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="form-text">Hold down the Ctrl (Windows) or Command (Mac) button to select multiple options.</div>

                {!! Form::hidden('shareuser_id', null, ['id' => 'shareuserfile_id']) !!}
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
            
            <!-- Select dropdown for priority -->
    <div class="form-group">
        <label for="priority_id">Priority</label>
        <select name="priority_id" id="priority_id" class="form-control" required onchange="shareFile(this.value)">
            <option value="" disabled selected>Select Priority</option>
            @foreach ($priorities as $priority)
                <option value="{{ $priority->id }}">{{ $priority->name .' - '.$priority->description }} </option>
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
            <button type="submit" class="btn btn-primary" >SUBMIT</button>
        </div>
        {!! Form::close() !!}
    </div>
  
 
</div>
</div>

<div class="modal fade" id="documentModal" tabindex="-1" role="dialog" aria-labelledby="documentModalLabel"
aria-hidden="true" data-backdrop="false">
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Create New Document</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form id="createDocumentForm" action="{{ route('documents_manager.store.new') }}" method="POST" enctype="multipart/form-data">
            @csrf 
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        {{-- <div class="card-header">
                            <h4 class="card-title">Document</h4>
                            <p class="card-title-desc"></p>
                        </div> --}}
                        <p>
                            <label class="form-label-outlined" for="category_id">Select File</label>
                                        <select class="form-control" name="category_id" id="category_id" required>
                                            <option value="">Select File No.</option>
                                             @foreach($dCats as $dCat)
                                             <option value="{{ $dCat->id }}" {{ old('category_id') }}>
                                                    {{ $dCat->name. ' - '.$dCat->description }}
                                                </option>
                                            @endforeach
                                        </select>
                        </p>
                        {{-- {!! Form::hidden('category_id', $catID, ['id' => 'category_id']) !!} --}}
                        <div class="card-body">
                            <p><input type="text" name="title" class="form-control" placeholder="Document Title"  required/> </p>
                            <p><textarea name="comment" class="form-control" placeholder="Type comment" required></textarea> </p>


                            <!-- CKEditor content area -->
                        <textarea name="document_content" id="ckeditor-classic"></textarea>
                        <p class="text-danger">Maximum upload file size is 2MB</p>
                        </div>
                    </div>
                </div>
                <!-- end col -->
            </div>
            @push('page_scripts')
               <!-- ckeditor -->
{{--         <script src="{{ asset('assets/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script>
 --}}
{{--  <script src="https://cdn.ckeditor.com/ckeditor5/38.0.0/classic/ckeditor.js"></script>
 --}}
 <script src="https://cdn.ckeditor.com/4.12.1/standard/ckeditor.js"></script>
 <style>
    /* Hide CKEditor warning message */
.ck-editor__warning {
    display: none !important;
}
.cke_notification_warning, .cke_notification, .cke_notifications, .cke_notifications_area{
    display: none !important;
    display: none;
}
 </style>
 <script>
    CKEDITOR.replace('document_content', {
        filebrowserUploadUrl: "{{ route('ckeditor.image.upload', ['_token' => csrf_token()]) }}",
        filebrowserUploadMethod: 'form',
        
        // Remove the 'Source' button (and 'Code' button for CodeBlock)
        removeButtons: 'Source,Code', // Removes 'Source' and 'Code' buttons from the toolbar

        // Optionally, you can add more toolbar customizations
        toolbar: [
            ['Bold', 'Italic', 'Underline', 'Strike', '-', 'Subscript', 'Superscript', '-', 'RemoveFormat'],
            ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'Link', 'Unlink', '-', 'Anchor'],
            ['Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak'],
            ['JustifyLeft', 'JustifyCenter', 'JustifyRight'] // Added center alignment
        ],
    });
</script>
        <!-- init js -->
        {{-- <script src="{{ asset('assets/js/pages/form-editor.init.js') }}"></script> --}}
       {{--  <script>
      ClassicEditor
    .create(document.querySelector('#ckeditor-classic'), {
        ckfinder: {
            uploadUrl: '{{ route('ckeditor.image.upload') }}', // Route to handle image upload
            options: {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'  // Add CSRF token to the request headers
                }
            }
        },
        toolbar: [
            'heading', '|',
            'bold', 'italic', 'link', '|',
            'alignment', 'numberedList', 'bulletedList', '|',
            'blockQuote', '|',
            'imageUpload', 'mediaEmbed', '|',
            'undo', 'redo', '|',
            'fontFamily', 'fontSize', 'textColor', 'highlight', '|',
            'indent', 'outdent', '|',
           // 'code', 'codeBlock'
        ],
        image: {
            toolbar: ['imageTextAlternative', '|', 'imageStyle:full', 'imageStyle:side', '|', 'imageResize:25', 'imageResize:50', 'imageResize:75', 'imageResize:100']
        },
        paragraph: {
            styles: ['normal', 'heading1', 'heading2', 'heading3', 'heading4', 'heading5', 'heading6', 'blockquote'],
            alignment: ['left', 'center', 'right'],
        }
    })
    .then(editor => {
        // Listen for the image upload response and handle errors
        editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
            return new MyUploadAdapter(loader);
        };
    })
    .catch(error => {
        console.error(error);
    });

class MyUploadAdapter {
    constructor(loader) {
        this.loader = loader;
    }

    upload() {
        return new Promise((resolve, reject) => {
            const data = new FormData();
            data.append('upload', this.loader.file);

            // Send image upload request to the server
            fetch('{{ route('ckeditor.image.upload') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}', // Add CSRF token here
                },
                body: data
            })
                .then(response => response.json())
                .then(response => {
                    if (response.uploaded) {
                        // If upload successful, resolve the promise with image URL
                        resolve({
                            default: response.url
                        });
                    } else {
                        // If upload fails, reject with the error message
                        reject(response.error.message);
                    }
                })
                .catch(error => {
                    console.error(error);
                    reject('Failed to upload image: ' + error.message);
                });
        });
    }

    abort() {
        // Handle aborting the upload if necessary
    }
}
        </script> --}}
            @endpush
    
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" >SUBMIT</button>
        </div>
    </form>
       
    </div>
</div>
</div>

<div class="modal fade" id="uploadDocumentModal" tabindex="-1" role="dialog" aria-labelledby="uploadDocumentModalLabel"
aria-hidden="true" data-backdrop="false">
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Upload New Document</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @push('page_css')
            <!-- dropzone css -->
<link href="{{ asset('assets/libs/dropzone/min/dropzone.min.css') }}" rel="stylesheet" type="text/css">                        
@endpush
        {{-- <form class="dropzone" action="{{ route('documents_manager.store') }}" method="POST" enctype="multipart/form-data">
            @csrf  --}}
            
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            
                            <p class="card-title-desc"></p>
                        </div>
                        
                        <div class="card-body">
                            <p>
                                <label class="form-label-outlined" for="category_id1">Select File</label>
                                            <select class="form-control" name="category_id1" id="category_id1" required>
                                                <option value="">Select File</option>
                                                 @foreach($dCats as $dCat)
                                                 <option value="{{ $dCat->id }}" {{ old('category_id') }}>
                                                    {{ $dCat->name. ' - '.$dCat->description }}
                                                    </option>
                                                @endforeach
                                            </select>
                            </p>
                            <p><textarea name="comment" id="comment" class="form-control" placeholder="Type comment" required></textarea> </p>

                        <div>
                            <form class="dropzone" id="my-dropzone" action="{{ route('documents_manager.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf 

                                {{-- {!! Form::hidden('category_id', $catID, ['id' => 'category_id']) !!} --}}
                                                    {{-- <div class="fallback">
                                                        <!-- Allow user to select multiple files -->
                                                        <input name="file[]" type="file" multiple="multiple" id="file-input" accept=".png,.jpg,.jpeg,.pdf,.doc,.docx">
                                                    </div> --}}

                                                    <div class="dz-message needsclick">
                                                        <div class="mb-3">
                                                            <i class="display-4 text-muted bx bx-cloud-upload"></i>
                                                        </div>
        
                                                        <h5>Drop files here or click to upload.</h5>
                                                    </div>
                                                    
                                                </form>
                                            </div>  
                                            <h4 class="card-title text-danger">Allowered files are png,jpg,jpeg, pdf,doc,docx</h4>  
                                            <div class="text-center mt-4">
                                                <button type="button" onclick="submitForm()" id="btn_submit" class="btn btn-primary waves-effect waves-light">Send
                                                    Files</button>
                                            </div> 
                                        
                        </div>
                    </div>
                </div>
                <!-- end col -->
            </div>
            @push('page_scripts')
            <!-- dropzone css -->
       {{--  <link href="{{ asset('assets/libs/dropzone/min/dropzone.min.css') }}" rel="stylesheet" type="text/css"> --}}
       <!-- pace js -->
       {{-- <script src="{{ asset('assets/libs/pace-js/pace.min.js') }}"></script> --}}
              <!-- dropzone js -->
        <script src="{{  asset('assets/libs/dropzone/min/dropzone.min.js') }}"></script>
        <script>
            // Function to submit the form manually when the button is clicked
function submitForm() {
    // Get the comment value from the textarea
    var comment = document.getElementById('comment').value;
    var category_id = document.getElementById('category_id1').value;

    // Check if the comment is empty or category is not selected
   // if (category_id === "") {
     //       alert("Please provide a comment and select a category.");
      //      return;  // Stop the form submission if validation fails
       // }

        // Disable the submit button and change the text to "Uploading..."
        var submitButton = document.getElementById('btn_submit');
        submitButton.disabled = true;
        submitButton.innerHTML = 'Uploading...';

    // Create a hidden input element to append the comment to the form
    var commentInput = document.createElement('input');
    commentInput.type = 'hidden';
    commentInput.name = 'comment';  // The name attribute must match the one in your form
    commentInput.value = comment;   // Set the value to the comment entered by the user

    var categoryInput = document.createElement('input');
    categoryInput.type = 'hidden';
    categoryInput.name = 'category_id';  // The name attribute must match the one in your form
    categoryInput.value = category_id;   // Set the value to the category_id entered by the user in select option drop down list


    // Append the comment input to the form
    var form = document.getElementById('my-dropzone');
    form.appendChild(commentInput);
    form.appendChild(categoryInput);

    // Trigger Dropzone's processQueue to start uploading files
    let myDropzone = Dropzone.forElement("#my-dropzone");

    // If files are added, process them
    if (myDropzone.getQueuedFiles().length > 0) {
        myDropzone.processQueue();  // Process the files (upload them)
    } else {
        alert("Please select files before submitting.");
        // Re-enable the submit button and reset its text if no files are selected
        submitButton.disabled = false;
        submitButton.innerHTML = 'Send Files';
    }
}
        </script>
        <script>
            document.getElementById('file-input').addEventListener('change', function (e) {
        const files = e.target.files;
        const allowedTypes = ['image/png', 'image/jpg', 'image/jpeg', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (!allowedTypes.includes(file.type)) {
                alert('Invalid file type. Allowed types are: PNG, JPG, JPEG, PDF, DOC, DOCX.');
                e.target.value = '';  // Clear the file input
                return;
            }
            
            if (file.size > 1048576) {  // 1MB = 1048576 bytes
                alert('File size should not exceed 1 MB.');
                e.target.value = '';  // Clear the file input
                return;
            }
        }
    });
        </script>
        <script>
           Dropzone.options.myDropzone = {
    paramName: 'file[]', // The name that will be used to transfer the file
    maxFilesize: 1, // Maximum file size in MB
    acceptedFiles: '.png,.jpg,.jpeg,.pdf,.doc,.docx', // Allowed file types
    dictDefaultMessage: 'Drag & Drop files here or click to upload',
    autoProcessQueue: false, // Don't auto-submit (to handle validation before submitting)
    maxFiles: 1, // Limit the number of files to 1 (optional)

    // Handle file removal after upload
    init: function() {
        // Handle success event
        this.on("success", function(file, response) {
            this.removeFile(file);
            //alert('File upload successful.');
            window.location.reload(1);
            console.log("File uploaded successfully: " + file.name);
        });

        // Handle error event (file size or type errors)
        this.on("error", function(file, message) {
            console.log("Error: " + message);

            // If file is too large, show an alert and remove it
            if (file.size > 1048576) { // 1MB in bytes
                alert('File size is too large. Please upload a file smaller than 1MB.');
                this.removeFile(file); // Remove the file from the dropzone
                return;
            }

            // If file type is not allowed, show an alert and remove it
            if (file.accepted === false) {
                alert('Invalid file type. Allowed types are: PNG, JPG, JPEG, PDF, DOC, DOCX.');
                this.removeFile(file); // Remove the file from the dropzone
            }
        });

        // Handle file added event (before uploading)
        this.on("addedfile", function(file) {
            // If file exceeds 1MB, remove the file immediately
            if (file.size > 1048576) { // 1MB in bytes
                alert('File size is too large. Please upload a file smaller than 1MB.');
                this.removeFile(file); // Remove the file immediately
            }
        });

        // Handle file removed event
        this.on("removedfile", function(file) {
            console.log("File removed: " + file.name);
        });
    }
};



        </script>
            @endpush
    
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
           {{--  <button type="submit" class="btn btn-primary waves-effect waves-light">SUBMIT</button> --}}
        </div>
    {{-- </form> --}}
       
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
        @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
        <form id="fileForm" action="{{ route('documents_category.store') }}" method="POST">
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
                            id="file_no" name="name" value="{{ $fileNumber }}" required>
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
            <button type="submit"  class="btn btn-primary">SUBMIT</button>
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
                $(".modal-body #shareuserfile_id").val(shareuser);
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
