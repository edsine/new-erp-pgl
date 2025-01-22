@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Memos Assigned to Me</h1>
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
                    <table class="table" id="memos-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Created By</th>
                                <th>Document URL</th>
                                <th>Created At</th>
                                {{-- <th>Action</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($memos_has_user as $memo_has_user)
                                @php
                                    $memo = $memo_has_user->memo;
                                    $latestDocumentUrl = $memo->document
                                        ->documentVersions()
                                        ->latest()
                                        ->first()
                                        ? $memo->document
                                            ->documentVersions()
                                            ->latest()
                                            ->first()->document_url
                                        : '#';
                                @endphp
                                <tr>
                                    <td>{{ $memo->title }}</td>
                                    <td>{{ $memo->createdBy ? $memo->createdBy->email : '' }}</td>
                                    <td><a target="_blank" href="{{ asset($latestDocumentUrl) }}">View</a>
                                    </td>
                                    <td>{{ $memo->created_at }}</td>
                                    {{-- <td style="width: 120px">
                                        <a href="{{ route('memos.show', [$memo->id]) }}" class='btn btn-default btn-xs'>
                                            <i class="far fa-eye"></i>
                                        </a>
                                    </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card-footer clearfix">
                    <div class="float-right">
                        @include('adminlte-templates::common.paginate', ['records' => $memos_has_user])
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
