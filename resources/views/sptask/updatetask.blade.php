@extends('layouts.admin')
@section('page-title')
    {{ $tasks->title }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('sptask.index') }}">{{ __('Tasks') }}</a></li>
    <li class="breadcrumb-item">{{ $tasks->title }}</li>
@endsection

@section('action-btn')
    <div class="float-end">

        {{-- @can('edit project') --}}
        {{-- <a href="#" data-size="lg" data-url="{{ route('projects.edit', $tasks->id) }}" data-ajax-popup="true"
                data-bs-toggle="tooltip" title="{{ __('') }}" class="btn btn-sm btn-primary">
                <i class="ti ti-pencil"></i>
            </a> --}}
        {{-- @endcan --}}
        <a href="#" data-bs-toggle="modal" title="{{ __('Update Task') }}" data-bs-target="#staticBackdrop"
            class="btn btn-sm btn-primary"> <i class="ti ti-pencil"></i></a>
        <!-- Button trigger modal -->
        {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
            <i class="ti ti-pencil"></i>
        </button> --}}

    </div>
@endsection

@section('content')
    <div class="container">

        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-auto mb-3 mb-sm-0">
                                <div class="d-flex align-items-center">
                                    <div class="theme-avtar bg-warning">
                                        <i class="ti ti-list"></i>
                                    </div>
                                    <div class="ms-3">
                                        <small class="text-muted h6">{{ __('TASK ') }}</small>
                                        <h6 class="m-0">{{ 'STATUS' }}</h6>
                                    </div>
                                </div>
                            </div>
                            {{-- @dd( $tasks->taskprogress->name) --}}
                            <div class="col-auto text-end">
                                <h4 class="m-0"></h4>
                                <small class="text-success h6">{{ $tasks->taskprogress?$tasks->taskprogress->name:'' }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-auto mb-3 mb-sm-0">
                                <div class="d-flex align-items-center">
                                    <div class="theme-avtar bg-danger">
                                        <i class="ti ti-report-money"></i>
                                    </div>
                                    <div class="ms-3">
                                        <small class="text-muted">{{ __('START') }}</small>
                                        <h6 class="m-0">{{ __('DATE') }}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto text-end">
                                <h4 class="m-0">{{ $tasks->start_date }}</h4>
                                <h4 class="m-0"></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-auto mb-3 mb-sm-0">
                                <div class="d-flex align-items-center">
                                    <div class="theme-avtar bg-danger">
                                        <i class="ti ti-report-money"></i>
                                    </div>
                                    <div class="ms-3">
                                        <small class="text-muted">{{ __('END') }}</small>
                                        <h6 class="m-0">{{ __('DATE') }}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto text-end">
                                <h4 class="m-0">{{ $tasks->end_date }}</h4>

                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-auto mb-3 mb-sm-0">
                                <div class="d-flex align-items-center">
                                    <div class="theme-avtar bg-warning">
                                        <i class="ti ti-list"></i>
                                    </div>
                                    <div class="ms-3">
                                        <small class="text-muted h6">{{ __('TASK DESCRIPTION ') }}</small>
                                        <h6 class="m-0">{{ $tasks->description }}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto text-end">
                                <h4 class="m-0"></h4>
                                <small class="text-muted h6"></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-auto mb-3 mb-sm-0">
                                <div class="d-flex align-items-center">
                                    <div class="theme-avtar bg-danger">
                                        <i class="ti ti-report-money"></i>
                                    </div>
                                    <div class="ms-3">
                                        <small class="text-muted">{{ __('CREATED BY') }}</small>
                                        <h6 class="m-0">{{ $tasks->user->name }}</h6>
                                    </div>
                                </div>
                            </div>
                            {{-- @dd($tasks->user ); --}}
                            <div class="col-auto text-end">
                                <h4 class="m-0"></h4>
                                <h4 class="m-0"></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-auto mb-3 mb-sm-0">
                                <div class="d-flex align-items-center">
                                    <div class="theme-avtar bg-danger">
                                        <i class="ti ti-report-money"></i>
                                    </div>
                                    <div class="ms-3">
                                        <small class="text-muted">{{ __('MEMBERS') }}</small>

                                        <h6 class="m-0">{{$tasks->user->name}}</h6>

                                        @if ($tasks->sptaskuser )

                                        @foreach ($tasks->sptaskuser as $item)


                                        <h6 class="m-0">{{$item->name }}</h6>
                                        @endforeach
                                        @else

                                        <h6 class="m-0">{{'NO USER'}}</h6>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto text-end">
                                {{-- <h4 class="m-0">{{ $tasks->end_date }}</h4> --}}

                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>


    </div>
    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">UPDATE TASK STATUS</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('sptask.update', $tasks) }}" method="post">
                    <div class="modal-body">
                        <div class="row">
                            @csrf
                            @method('PUT')

                            <div class="col-12">
                                {!! Form::label('progress', 'Update Your TASK', ['class' => 'form-label']) !!}
                                {!! Form::select('progress', $progress, null, ['class' => 'form-control select']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
            </div>
            </form>
        </div>
    </div>
@endsection
