@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="mb-3">Assigned Users to {{ $memo->title }}</h1>

                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary float-end" href="{{ route('memos.index') }}">
                        Back
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('layouts.messages')

        <div class="clearfix"></div>

        <div class="card p-5">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="documents-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th colspan="3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($assigned_users as $assigned_user)
                            <tr>
                                <td>{{ $assigned_user->user->email }}</td>
                                <td style="width: 120px">
                                    {!! Form::open(['route' => ['memos.assignedUsers.destroy',$assigned_user->user_id,  $memo->id], 'method' => 'delete']) !!}
                                    <div class='btn-group'>
                                        {!! Form::button('<i class="far fa-trash-alt"></i>', [
                                            'type' => 'submit',
                                            'class' => 'btn btn-danger btn-xs',
                                            'onclick' => "return confirm('Are you sure?')",
                                        ]) !!}
                                    </div>
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer clearfix">
                <div class="float-right">
                    @include('adminlte-templates::common.paginate', ['records' => $assigned_users])
                </div>
            </div>
        </div>
    </div>
@endsection
