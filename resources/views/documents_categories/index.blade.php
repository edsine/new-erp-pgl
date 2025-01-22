@extends('layouts.admin')

@section('title', 'Document Categories')


@push('page_scripts')
<link rel="stylesheet" type="text/css" href=".css/style.css">
<script src="./js/bundle.js?ver=1.0.0"></script>
<script src="./js/scripts.js?ver=1.0.0"></script>
<script src="./js/datatable-btns.js?ver=1.0.0"></script>
@endpush

@section('content')
{{-- @include('layouts.messages') --}}
    {{-- <div class="components-preview wide-md mx-auto"> --}}
    <div class="nk-block-head nk-block-head-sm">
        <div class="nk-block-between">
            <div class="nk-block-head-content">
                <h3 class="nk-block-title page-title">File Indexing</h3>
                <div class="row">
                <div class="col-md-12">
                    @can("create files")
                    
                    <a href="{{ route('documents_category.create') }}" class="btn btn-primary float-end" ><em
                        class="fa fa-user-add"></em> <span>Add New File</span></a>
                    @endcan
                </div>
                </div>
            </div><!-- .nk-block-head-content -->
           <!-- .nk-block-head-content -->
        </div><!-- .nk-block-between -->
    </div><!-- .nk-block-head -->
    @can("read files")
    <div class="container">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">List Of File No.</a>
          </li>
          {{-- <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Share Files</a>
          </li>
           --}}
        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="card">
                <div class="card-body p-5">
                    <div class="table-responsive">
                        {{-- <table class="table align-middle gs-0 gy-4" id="document-category"> --}}
                            <table class="datatable-init-export nowrap table" data-export-title="Export">
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
                        
                        
                                    </div>
                    </div> </div>
          </div>
          <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <div class="card">
                <div class="card-body p-5">
                    <div class="table-responsive">
                        <table class="table align-middle gs-0 gy-4" id="document-category">
                            <thead>
                                <tr>
                                    <th>Serial No.</th>
                                    <th>File No.</th>
                                    <th>Subject</th>
                                    <th>Total Documents</th>
                                    <th>Share User</th>
                                    <th>Manage</th>
                                </tr>
                            </thead>
                            <tbody> 
                                @foreach ($documents_categories1 as $index => $documents_category)
                                    <tr>
                                        <td>{{ $index + 1 }}</td> <!-- Use $index + 1 as the serial number -->
                                        <td>{{ $documents_category->d_name ? $documents_category->d_name.' / ' : '' }}{{ $documents_category->category_name }}</td>
                                        <td>{{ $documents_category->description }}</td>
                                        <td>{{ $documents_category->count_documents_manager ?? 'N/A' }}</td>
                                        <td><a class="open-modal-shareuser btn btn-primary" href="#" data-bs-toggle="modal" data-target="#shareuserModal"
                                            data-shareuser={{ $documents_category->doc_id }}>User</a></td>
                                        <td>
                                            <a style="padding-right:10px;" href="{{ route('documents_category.edit', $documents_category->cat_id) }}" title="Edit Document Category">
                                                <span class="nk-menu-icon text-info"><em class="fa fa-edit"></em></span>
                                            </a>
                                           
                                                  
                                                    
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        
                                    </div>
                    </div> </div>
          </div>
          
        </div>
      </div>
    <!-- .card-preview -->
    @endcan

        <div class="modal fade" id="shareuserModal" tabindex="-1" role="dialog" aria-labelledby="shareuserModalLabel"
aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            {!! Form::open(['route' => 'documents_manager.shareuserfile', 'enctype' => 'multipart/form-data']) !!}
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
@push('page_scripts')
<script>
    $(document).on("click", ".open-modal-shareuser", function() {
let shareuser = $(this).data('shareuser');
$(".modal-body #shareuser_id").val(shareuser);

});
</script>
@endpush
        <!-- JavaScript -->
   {{--  <script src="./assets/js/libs/datatable-btns.js?ver=3.1.3"></script> --}}
         <script>
            /* (function($) {
                'use strict';
                $(function() {
                    $('#order-listing1').DataTable({
                        "aLengthMenu": [
                            [5, 10, 15, -1],
                            [5, 10, 15, "All"]
                        ],
                        "iDisplayLength": 10,
                        "language": {
                            search: ""
                        },
                        "columnDefs": [ // Add this to specify column rendering
                            { "orderable": false, "targets": 0 } // Disable sorting for the first column
                        ]
                    });
                    $('#order-listing1').each(function() {
                        var datatable = $(this);
                        // SEARCH - Add the placeholder for Search and Turn this into in-line form control
                        var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
                        search_input.attr('placeholder', 'Search');
                        search_input.removeClass('form-control-sm');
                        // LENGTH - Inline-Form control
                        var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
                        length_sel.removeClass('form-control-sm');
                    });
                });
            })(jQuery); */

            function confirmDelete(id) {
    // Show confirmation dialog
    if (confirm('Are you sure you want to delete this item?')) {
        // Send AJAX request
        $.ajax({
            url: '/documents-category/' + id,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Handle success response
                console.log(response);
                // Reload the page or update UI as needed
            },
            error: function(xhr) {
                // Handle error response
                console.error(xhr.responseText);
            }
        });
    }
}

        </script>
@endsection


