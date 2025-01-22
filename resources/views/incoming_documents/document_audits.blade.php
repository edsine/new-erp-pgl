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

<!-- Responsive examples -->
{{-- <script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script> --}}

<!-- Datatable init js -->
<script src="{{ asset('assets/js/pages/datatables.init.js') }}"></script> 
@endpush
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Incoming Documents Audit Trail</h1>
                </div>
                
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('layouts.messages')

        <div class="clearfix"></div>

        <div class="card">
            <div class="card-body p-5">
                <div class="table-responsive">
                    <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Document Title</th>
                                {{-- <th>By Whom</th> --}}
                                <th>Assigned To</th>
                                <th>Document URL</th>
                                <th>Department Name / File No.</th>
                                <th>Action Date</th>
                                <th>Operation</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $n =1; @endphp
                            @foreach ($documents as $document)
                            @php
                            $document->category = $categories[$document->category_id] ?? null;
                        @endphp
                                <tr>
                                    
                                    <td>{{ $document->title }}</td>
                                    {{-- <td>{{ $document->description }}</td> --}}
{{--                                     <td>{{ $document->created_by_first_name ? $document->created_by_first_name. ' '.$document->created_by_last_name : '' }}</td>
 --}}                                    <td>{{ $document->assigned_to_first_name }}</td>
                                    <td>{{ substr($document->document_url, 10) }}</td>
                                    <td>
                                        @if ($document->category)
                                            {{ $document->category->department->name ?? '' }}
                                            /
                                            {{ $document->category_name ?? 'NILL' }}
                                        @else
                                        {{ $document->category_name ?? 'NILL' }}
                                        @endif
                                    </td>
                                    <td>{{ $document->createdAt }}</td>
                                    <td>{{ $document->event }}</td>
                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            
                
            </div>
            
            
           
            
        </div>
    </div>

@endsection
