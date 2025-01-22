@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Official Documents</h1>
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
                    <table class="table align-middle gs-0 gy-4" id="order-listing">
                        <thead>
                            <tr>
                                {{-- <th>S/N</th> --}}
                                <th>Document Name</th>
                                <th>Role Name</th>
                                <th>Document URL</th>
                                <th>File Name</th>
                                <th>Start Date</th>
                                <th>Expiry Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $n =1; @endphp
                            @foreach ($documents as $document)
                            @php
                            $document->category = $categories[$document->category_id] ?? null;
                        @endphp
                                <tr>
                                    {{-- <td>{{ $n++ }}</td> --}}
                                    <td>{{ $document->title }}</td>
                                    {{-- <td>{{ $document->description }}</td> --}}
                                    <td>{{ $document->role_name ?? 'NILL' }}</td>
                                    <td><a target="_blank"
                                            href="{{ getDocumentUrl($document->document_url) }}">{{ substr($document->document_url, 10) }}
                                        </a>
                                    </td>
                                      <td>
                                            @if ($document->category)
                                                {{ $document->category->department->name ?? '' }}
                                                /
                                                {{ $document->category_name ?? 'NILL' }}
                                            @else
                                                NILL
                                            @endif
                                        </td>                                        <td>{{ $document->start_date }}</td>
                                    <td>{{ $document->end_date }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                Click to view options
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right" style="z-index: 9999;"
                                                aria-labelledby="dropdownMenuButton">
                                                <a target="_blank" href="{{ getDocumentUrl($document->document_url) }}"
                                                    class='btn btn-default btn-xs dropdown-item'>
                                                    <i class="far fa-eye"></i> View
                                                </a>
                                                @if (Auth::user()->hasRole('super-admin'))
                                                    <a href="{{ route('incoming_documents_manager.edit', [$document->id]) }}"
                                                        class='btn btn-default btn-xs dropdown-item'>
                                                        <i class="far fa-edit"></i> Edit
                                                    </a>
                                                @endif
                                                <a class="open-modal-share btn btn-default btn-xs dropdown-item"
                                                    href="#" data-bs-toggle="modal" data-target="#shareModal"
                                                    data-share={{ $document->id }}><i class="fa fa-share-alt"></i> Assign to</a>
                                                @if (Auth::user()->hasRole('super-admin'))
                                                    <a class="btn btn-default btn-xs dropdown-item"
                                                        href="{{ getDocumentUrl($document->document_url) }}" download><i
                                                            class="fa fa-download"></i> Download</a>
                                                @elseif(isset($document->is_download) &&  $document->is_download == 1)
                                                    <a class="btn btn-default btn-xs dropdown-item"
                                                        href="{{ getDocumentUrl($document->document_url) }}" download><i
                                                            class="fa fa-download"></i> Download</a>
                                                    {{-- @else
                                                 {{ $document->document_url }} --}}
                                                @endif
                                                <a class="open-modal-upload btn btn-default btn-xs dropdown-item"
                                                    href="#" data-bs-toggle="modal" data-target="#uploadsModal"
                                                    data-upload={{ $document->id }}><i class="fa fa-download"></i> Upload
                                                    New Version File</a>
                                                <a class="open-modal-history btn btn-default btn-xs dropdown-item"
                                                    href="#" data-bs-toggle="modal" data-target="#historyModal"
                                                    data-history={{ $document->id }}><i class="fa fa-history"></i> Version
                                                    History</a>
                                                <a class="open-modal-comment btn btn-default btn-xs dropdown-item"
                                                    href="#" data-bs-toggle="modal" data-target="#commentModal"
                                                    data-comment={{ $document->id }}
                                                    data-commenter={{ $document->document_url }}><i
                                                        class="fa fa-message"></i> Comment</a>
                                                <a class="btn btn-default btn-xs dropdown-item" href="#"><i
                                                        class="fa fa-bell"></i> Add Reminder</a>
                                                <a class="open-modal-sendemail btn btn-default btn-xs dropdown-item"
                                                    href="#" data-bs-toggle="modal" data-target="#sendEmailModal"
                                                    data-sendemail={{ $document->id }}
                                                    data-sendemailer={{ $document->document_url }}><i
                                                        class="far fa-envelope"></i> Send Email</a>
                                                @if (Auth::user()->hasRole('super-admin'))
                                                    <a class="btn btn-default btn-xs dropdown-item" href="#"
                                                        onclick="confirmDelete()">
                                                        <i class="far fa-trash-alt"></i> Delete
                                                    </a>
                                                @endif
                                                {!! Form::open(['route' => ['incoming_documents_manager.destroy', $document->id], 'method' => 'delete']) !!}

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
@endsection
