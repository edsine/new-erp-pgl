@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Documents Audit Trail</h1>
                </div>
                
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('layouts.messages')

        <div class="clearfix"></div>
        
@can('view document audit trail')

        <div class="card">
            <div class="card-body p-5">
                <div class="table-responsive">
                    <table class="table align-middle gs-0 gy-4" id="order-listing">
                        <thead>
                            <tr>
                                <th>Document Title</th>
                                <th>By Whom</th>
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
                                    <td>{{ $document->created_by_first_name ? $document->created_by_first_name : '' }}</td>
                                    <td>{{ $document->assigned_to_first_name }} </td>
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
        @endcan
    </div>

@endsection
