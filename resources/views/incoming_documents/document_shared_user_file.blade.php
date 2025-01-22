@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>My Incoming Files</h1>
                </div>
                
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('layouts.messages')

        <div class="clearfix"></div>

        <div class="card">
            <div class="card-body p-5">
                <div class="table-responsive1" style="overflow-y: auto;">
                    <table class="table align-middle gs-0 gy-4" id="order-listing">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Letter Title</th>
                                {{-- <th>Created By</th> --}}
                                <th>Assigned By</th>
                                @if(Auth::user()->hasRole('super-admin') || Auth::user()->hasRole('MANAGING DIRECTOR'))
                                <th>Assigned To</th>
                                @endif
                                <th>Sender Name</th>
                                <th>Sender Email</th>
                                <th>Sender Phone</th>
                                <th>Letter URL</th>
                                <th>Department Name / File No.</th>
                                <th>Start Date</th>
                                <th>Expiry Date</th>
                                <th>Share User</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                           
                            @foreach ($documents as $index => $document)
                            @php
                            //$document->category = $categories[$document->d_m_c_id] ?? null;
                        @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $document->title }}</td>
                                    {{-- <td>{{ $document->description }}</td> --}}
                                   {{--  <td>{{ $document->created_by_name ?? 'NILL' }}</td> --}}
                                    <td>{{ $document->assigned_by_name ?? 'NILL' }}</td>
                                    @if(Auth::user()->hasRole('super-admin') || Auth::user()->hasRole('MANAGING DIRECTOR'))
                                    <td>{{ $document->assigned_to_name ?? 'NILL' }}</td>
                                    @endif
                                    <td>{{ $document->sender_full_name ?? 'NILL' }}</td>
                                    <td>{{ $document->sender_email ?? 'NILL' }}</td>
                                    <td>{{ $document->sender_phone ?? 'NILL' }}</td>
                                    <td><a target="_blank" href="{{ getDocumentUrl($document->document_url) }}">{{ substr($document->document_url, 10) }}</a>
                                    <td>{{ $document->dep_name ? $document->dep_name.' / ' : '' }}{{ $document->cat_name ?? 'NILL' }}</td>
                                    <td>{{ $document->start_date }}</td>
                                    <td>{{ $document->end_date }}</td>
                                    <td>
                                        @if(($document->allow_share == 1 && $document->user_id == Auth()->user()->id) || $document->assigned_by == Auth()->user()->id)
                                    <a class="open-modal-shareuser btn btn-primary" href="#" data-bs-toggle="modal" data-target="#shareuserModal"
                                        data-shareuser={{ $document->d_m_id }}>User</a>
                                @endif
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                View options
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right" style="z-index: 9999;" aria-labelledby="dropdownMenuButton">
                                                <a target="_blank" href="{{ getDocumentUrl($document->document_url) }}"
                                                    class='btn btn-default btn-xs dropdown-item'>
                                                    <i class="far fa-eye"></i> View
                                                </a>
                                                @if(Auth::user()->hasRole('super-admin'))
                                                <a href="{{ route('incoming_documents_manager.edit', [$document->d_m_id]) }}" class='btn btn-default btn-xs dropdown-item'>
                                                    <i class="far fa-edit"></i> Edit
                                                </a>
                                                @endif
                                                <a class="open-modal-share btn btn-default btn-xs dropdown-item" href="#" data-bs-toggle="modal" data-target="#shareModal"
                                                data-share={{ $document->d_m_id }}><i class="fa fa-share-alt"></i> Assign to</a>
                                                @if(Auth::user()->hasRole('super-admin'))
                                                <a class="btn btn-default btn-xs dropdown-item" href="{{ getDocumentUrl($document->document_url) }}" download><i class="fa fa-download"></i> Download</a>
                                                 @elseif(!empty($document->is_download) && $document->is_download == 1)
                                                <a class="btn btn-default btn-xs dropdown-item" href="{{ getDocumentUrl($document->document_url) }}" download><i class="fa fa-download"></i> Download</a>
                                                 {{-- @else 
                                                 {{ $document->document_url }} --}}
                                                 @endif
                                                <a class="open-modal-upload btn btn-default btn-xs dropdown-item" href="#" data-bs-toggle="modal" data-target="#uploadsModal"
                                                data-upload={{ $document->d_m_id }}><i class="fa fa-download"></i> Upload New Version File</a>
                                                <a class="open-modal-history btn btn-default btn-xs dropdown-item" href="#" data-bs-toggle="modal" data-target="#historyModal"
                                                data-history={{ $document->d_m_id }}><i class="fa fa-history"></i> Version History</a>
                                                <a class="open-modal-comment btn btn-default btn-xs dropdown-item" href="#" data-bs-toggle="modal" data-target="#commentModal"
                                                data-comment={{ $document->d_m_id }} data-commenter={{ $document->document_url }}><i class="fa fa-message"></i> Comment</a>
                                                <a class="btn btn-default btn-xs dropdown-item" href="#"><i class="fa fa-bell"></i> Add Reminder</a>
                                                <a class="open-modal-sendemail btn btn-default btn-xs dropdown-item" href="#"  data-bs-toggle="modal" data-target="#sendEmailModal"
                                                data-sendemail={{ $document->d_m_id }} data-sendemailer={{ $document->document_url }}><i class="far fa-envelope"></i> Send Email</a>
                                                @if(Auth::user()->hasRole('super-admin'))
                                                <a class="btn btn-default btn-xs dropdown-item" href="#" onclick="confirmDelete()">
                                                    <i class="far fa-trash-alt"></i> Delete
                                                </a>
                                                @endif
                                                                                     {!! Form::open(['route' => ['incoming_documents_manager.destroy', $document->d_m_id], 'method' => 'delete']) !!}
                                    
                                                {!! Form::button('Delete button', [
                                                    'type' => 'submit',
                                                    'id' => 'delete-btn',
                                                    'class' => 'btn btn-danger btn-xs',
                                                    'onclick' => "return confirm('Do you want to delete this document?')",
                                                    'style' => 'display: none;', // Add inline CSS to hide the button
                                                ]) !!}
                                    {!! Form::close() !!}
                                                <!-- Add more options as needed -->
                                            </div>
                                        </div>
                                    
                                   
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
    {!! Form::open(['route' => 'incoming_documents_manager.add', 'enctype' => 'multipart/form-data']) !!}
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
        {!! Form::open(['route' => 'incoming_documents_manager.add_comment', 'enctype' => 'multipart/form-data']) !!}
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

<div class="modal fade" id="sendEmailModal" tabindex="-1" role="dialog" aria-labelledby="sendEmailModalLabel"
aria-hidden="true" data-backdrop="false">
<div class="modal-dialog " role="document">
    <div class="modal-content">
        {!! Form::open(['route' => 'incoming_documents_manager.send_email', 'enctype' => 'multipart/form-data']) !!}
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
            {!! Form::open(['route' => 'incoming_documents_manager.shareuser', 'enctype' => 'multipart/form-data']) !!}
        @csrf
            <div class="modal-header">
                <h5 class="modal-title">User Permission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
<?php 
// Display the results
$options = '';
foreach ($users as $id => $name) {
    $options .= "<option value='$id'>$name</option>";
}


?>
                <div class="form-group">
                    {!! Form::label('users', 'Select User(s):') !!}
                    <?php echo "<select name='users[]' class='form-control' id='userSelect' multiple='multiple'>$options</select>"; ?>

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
        {!! Form::open(['route' => 'incoming_documents_manager.sharerole', 'enctype' => 'multipart/form-data']) !!}
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
                {!! Form::select('roles[]', $roles, null, ['class' => 'form-control', 'id' => 'userSelect', 'multiple' => 'multiple']) !!}
                
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
        {!! Form::open(['route' => 'incoming_documents_manager.add_comment', 'enctype' => 'multipart/form-data']) !!}
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
    url: '/incoming_documents_manager/version/' + documentId,
    type: 'GET',
    success: function(response) {
        // Populate table with fetched data
        response.forEach(function(history) {
let row = $('<tr>');
row.append($('<td>').text(history.firstName + ' ' + history.lastName));
if (history.document_url === history.doc_url) {
    row.append($('<td>').html(history.document_url + ' <span class="btn-primary" style="background: green;">current version</span>'));
} else {
    row.append($('<td>').text(history.document_url));
}
row.append($('<td>').text(history.createdAt));
    $('#curr').html(history.doc_url + ' <span class="btn-primary" style="background: green;">current version</span>')
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
    url: '/incoming_documents_manager/comment/' + documentId,
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
    url: '/incoming_documents_manager/share/' + share,
    type: 'GET',
    success: function(response) {
        // Populate table with fetched data
        response.forEach(function(history) {
let row = $('<tr>');
    row.append($('<td>').text('User'));
        if(history.is_download === 1){
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

@endsection
