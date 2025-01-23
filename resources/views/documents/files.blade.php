@extends('layouts.app')
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
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> Files</h1>
                </div>
                @can("create files")
                <div class="col-sm-6">
                <a href="{{ route('documents_category.create') }}" class="btn btn-primary float-end" ><em
                    class="fa fa-user-add"></em> <span>Add New File</span></a>
                </div>
                @endcan
            </div>
            
        </div>
    </section>

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
@can("read files")
<ul class="nav justify-content-start nav-tabs-custom rounded card-header-tabs" role="tablist">
    <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#profile" role="tab">Departmental File Archive</a>
          </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#home" role="tab">Incoming File Archive</a>
      </li>
      
      
    </ul>
@endcan
        <div class="clearfix"></div>
       
       
    </div>

    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
     <div class="tab-content" id="myTabContent" style="margin-bottom: 70px;">
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
          <div class="card">
              <div class="card-body p-5">
                <form method="post" action="{{ route('documents.by.files.post') }}">
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
                    </form>
        <div class="table-responsive">
            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                <thead>
                    <tr>
                        <th style="color: white">S/N</th>
                        <th>S/N</th>
                        <th>Document Title</th>
                        {{-- <th>Assigned To</th> --}}
                        <th>Document URL</th>
                        <th>Department Name / File No.</th>
                        <th>Subject</th>
                        <th>Created Date</th>
                       {{--  @if(Auth::user()->hasRole('super-admin')) --}}
                        <th>Share User</th>
                        {{-- @endif --}}
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $n =1; @endphp
                    @foreach ($documents as $index => $document)
                        
                        <tr>
                            <td style="color: white">{{ $document->d_id }}</td>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $document->title }}</td>
                            {{-- <td>{{ $document->description }}</td> --}}
                            {{-- <td>{{ $document->assigned_to_name ?? 'NILL' }}</td> --}}
                            <td>
                                <a class="document-link" target="_blank" 
                                   href="{{ getDocumentUrl($document->document_url) }}" 
                                   onClick="saveData('{{ $document->document_url }}')">
                                   {{ substr($document->document_url, 10) }}
                                </a>
                            </td>
                            
                            <td>{{ $document->dep_name ? $document->dep_name.' / ' : '' }}{{ $document->cat_name ?? 'NILL' }}</td>
                            <td>{{ $document->doc_description ?? 'NILL' }}</td>
                            <td>{{ get_datetime_format($document->document_created_at) }}</td>
                            {{-- @if(Auth::user()->hasRole('super-admin')) --}}
                            <td style="width: 120px;">
                                
                                <div class="btn-group" role="group">
                                    <a class="open-modal-shareuser btn btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#shareuserModal"
                                        data-shareuser={{ $document->d_id }}>User</a>
                                        
                                   {{--  @if(($document->allow_share == 1 && $document->user_id == Auth()->user()->id) || $document->assigned_by == Auth()->user()->id)
                                    <a class="open-modal-shareuser btn btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#shareuserModal"
                                        data-shareuser={{ $document->d_id }}>User</a>
                                @endif
     --}}
    {{-- <a class="open-modal-sharerole btn btn-danger" href="#" data-bs-toggle="modal" data-bs-target="#shareroleModal"
    data-sharerole={{ $document->d_id }}>Role</a> --}}
    
                                </div>
                                
                            </td>
                           {{--  @endif --}}
                            <td>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                            Click to view options
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" style="z-index: 9999; margin-bottom: 30px;" aria-labelledby="dropdownMenuButton">
                                            <a target="_blank" 
   href="{{ getDocumentUrl($document->document_url) }}" 
   class='btn btn-default btn-xs dropdown-item'>
    <i class="far fa-eye"></i> View
</a>
                                            @if(Auth::user()->hasRole('super-admin'))
                                            <a style="display: none" href="{{ route('documents_manager.edit', [$document->d_id]) }}" class='btn btn-default btn-xs dropdown-item'>
                                                <i class="far fa-edit"></i> Edit
                                            </a>
                                            @endif
                                            <a class="open-modal-share btn btn-default btn-xs dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#shareModal"
                                            data-share={{ $document->d_id }}><i class="fa fa-share-alt"></i> Assign to</a>
                                            @if(Auth::user()->hasRole('super-admin'))
                                            <a class="btn btn-default btn-xs dropdown-item" 
                                            href="{{ getDocumentUrl($document->document_url) }}" 
                                            download>
                                            <i class="fa fa-download"></i> Download
                                         </a>                                             @elseif(!empty($document->is_download) && $document->is_download == 1)
                                         <a class="btn btn-default btn-xs dropdown-item" 
                                         href="{{ getDocumentUrl($document->document_url) }}" 
                                         download>
                                         <i class="fa fa-download"></i> Download
                                      </a>                                             {{-- @else 
                                             {{ $document->document_url }} --}}
                                             @endif
                                            <a class="open-modal-upload btn btn-default btn-xs dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#uploadsModal"
                                            data-upload={{ $document->d_id }}><i class="fa fa-download"></i> Upload New Version File</a>
                                            <a class="open-modal-history btn btn-default btn-xs dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#historyModal"
                                            data-history={{ $document->d_id }}><i class="fa fa-history"></i> Version History</a>
                                            <a class="open-modal-comment btn btn-default btn-xs dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#commentModal"
                                            data-comment={{ $document->d_id }} data-commenter={{ $document->document_url }}><i class="fa fa-message"></i> Comment</a>
                                            <a class="btn btn-default btn-xs dropdown-item" href="#"><i class="fa fa-bell"></i> Add Reminder</a>
                                            <a class="open-modal-sendemail btn btn-default btn-xs dropdown-item" href="#"  data-bs-toggle="modal" data-bs-target="#sendEmailModal"
                                            data-sendemail={{ $document->d_id }} data-sendemailer={{ $document->document_url }}><i class="far fa-envelope"></i> Send Email</a>
                                            @if(Auth::user()->hasRole('super-admin'))
                                            <a class="btn btn-default btn-xs dropdown-item" href="#" onclick="confirmDelete()">
                                                <i class="far fa-trash-alt"></i> Delete
                                            </a>
                                            @endif
                                                                                 {{-- {!! Form::open(['route' => ['documents_manager.destroy', $document->d_id], 'method' => 'delete']) !!}
                                
                                            {!! Form::button('Delete button', [
                                                'type' => 'submit',
                                                'id' => 'delete-btn',
                                                'class' => 'btn btn-danger btn-xs',
                                                'onclick' => "return confirm('Do you want to delete this document?')",
                                                'style' => 'display: none;', // Add inline CSS to hide the button
                                            ]) !!}
                                {!! Form::close() !!} --}}
                                            <!-- Add more options as needed -->
                                        </div>
                                    </div>
                                
                               {{--  {!! Form::open(['route' => ['documents_manager.destroy', $document->d_id], 'method' => 'delete']) !!}
                                <div class='btn-group'>
                                    <a href="{{ route('documents_manager.show', [$document->d_id]) }}"
                                        class='btn btn-default btn-xs'>
                                        <i class="far fa-eye"></i>
                                    </a>
                                    
                                    <a href="{{ route('documents_manager.edit', [$document->d_id]) }}" class='btn btn-default btn-xs'>
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
                        {!! Form::label('users', 'Select User(s):') !!}
                        {!! Form::select('users[]', $users, null, ['class' => 'form-control', 'id' => 'userSelect', 'multiple' => 'multiple']) !!}
    
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
        
    @endpush
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
        
        <script>
           jQuery(document).ready(function($) {
        console.log("Select2 initialization script started.");
        $('#userSelect').select2();
        console.log("Select2 initialization script executed.");
    });
        </script>
@endsection
