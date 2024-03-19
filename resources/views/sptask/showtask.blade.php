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

        @can('edit project')
            <a href="#" data-size="lg" data-url="{{ route('projects.edit', $tasks->id) }}" data-ajax-popup="true"
                data-bs-toggle="tooltip" title="{{ __('Edit Project') }}" class="btn btn-sm btn-primary">
                <i class="ti ti-pencil"></i>
            </a>
        @endcan

    </div>
@endsection

@section('content')

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
                                    <h6 class="m-0">{{ 'Title' }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto text-end">
                            <h4 class="m-0"></h4>
                            <small class="text-muted h6">{{ $tasks->title }}</small>
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
                            {{-- <h4 class="m-0"></h4> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- @if (Auth::user()->type != 'client') --}}
        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mb-3 mb-sm-0">
                            <div class="d-flex align-items-center">
                                <div class="theme-avtar bg-success">
                                    <i class="ti ti-report-money"></i>
                                </div>
                                <div class="ms-3">
                                    <small class="text-muted">{{ __('CREATED BY') }}</small>
                                    <h6 class="m-0">{{ $tasks->user->name }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto text-end">
                            {{-- <h4 class="m-0">{{ \Auth::user()->priceFormat($project_data['expense']['total']) }}</h4> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- @else
            <div class="col-lg-4 col-md-6"></div>
        @endif --}}
        <div class="col-lg-4 col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            {{-- <img {{ $project->img_image }} alt="" class="img-user wid-45 rounded-circle"> --}}
                        </div>
                        <div class="d-block  align-items-center justify-content-between w-100">
                            <div class="mb-3 mb-sm-0">
                                <h5 class="mb-1"> TASK STATUS</h5>
                                <p class="mb-0 text-sm">
                                <div class="progress-wrapper">
                                    <span class="progress-percentage"><small class="font-weight-bold">
                                            @if ($tasks->progress == 0)
                                                <small class="font-weight-bold text-warning">{{ 'Awaiting Task' }} </small>
                                            @elseif ($tasks->progress == 1)
                                                <small class="font-weight-bold text-warning">{{ 'Todo' }} </small>
                                            @elseif ($tasks->progress == 2)
                                                <small class="font-weight-bold text-primary">{{ 'In Progress' }} </small>
                                            @elseif ($tasks->progress == 3)
                                                <small class="font-weight-bold text-success">{{ 'Done' }} </small>
                                            @endif

                                            :
                                            {{-- </small>{{ $project->project_progress()['percentage'] }}</span> --}}
                                            <div class="progress progress-xs mt-2">
                                                <div class="progress-bar bg-info" role="progressbar" {{-- aria-valuenow="{{ $project->project_progress()['percentage'] }}" --}}
                                                    aria-valuemin="0" aria-valuemax="100" style="width: ;"></div>

                                            </div>
                                </div>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-10">
                            <h4 class="mt-3 mb-1"></h4>
                            <p> {{ $tasks->description }}</p>
                        </div>
                    </div>
                    <div class="card bg-primary mb-0">
                        <div class="card-body">
                            <div class="d-block d-sm-flex align-items-center justify-content-between">
                                <div class="row align-items-center">
                                    <span class="text-white text-sm">{{ __('Start Date') }}</span>
                                    <h5 class="text-white text-nowrap">
                                        {{-- {{ Utility::getDateFormated($project->start_date) }} --}}

                                    </h5>
                                </div>
                                <div class="row align-items-center">
                                    <span class="text-white text-sm">{{ __('End Date') }}</span>
                                    <h5 class="text-white text-nowrap">{{ $tasks->end_date }}
                                    </h5>
                                </div>

                            </div>
                            <div class="row">
                                <span class="text-white text-sm">{{ __('Client') }}</span>
                                <h5 class="text-white text-nowrap">
                                    {{-- {{ !empty($project->client) ? $project->client->name : '-' }} --}}
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="theme-avtar bg-primary">
                            <i class="ti ti-clipboard-list"></i>
                        </div>
                        <div class="ms-3">
                            <p class="text-muted mb-0">{{ __('Last 7 days task done') }}</p>
                            <h4 class="mb-0">

                                {{-- {{ $project_data['task_chart']['total'] }} --}}
                                {{-- {{ $project_data['task_chart']['total'] }} --}}

                            </h4>

                        </div>
                    </div>
                    <div id="task_chart"></div>
                </div>

                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center">
                            <span class="text-muted">{{ __('Day Left') }}</span>
                        </div>
                        <span>
                            {{-- {{ $project_data['day_left']['day'] }} --}}

                        </span>
                    </div>
                    <div class="progress mb-3">
                        <div class="progress-bar bg-primary" {{-- style="width: {{ $project_data['day_left']['percentage'] }}%"> --}} style="width:2">

                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center">

                            <span class="text-muted">{{ __('Open Task') }}</span>
                        </div>
                        {{-- <span>{{ $project_data['open_task']['tasks'] }}</span> --}}
                        <span></span>
                    </div>
                    <div class="progress mb-3">
                        <div class="progress-bar bg-primary" {{-- style="width: {{ $project_data['open_task']['percentage'] }}%" --}}>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center">
                            <span class="text-muted">{{ __('Completed Milestone') }}</span>
                        </div>
                        <span>
                            {{-- {{ $project_data['milestone']['total'] }} --}}

                        </span>
                    </div>
                    <div class="progress mb-3">
                        {{-- <div class="progress-bar bg-primary"
                            style="width:
                             {{ $project_data['milestone']['percentage'] }}%"

                             >

                            </div> --}}
                    </div>
                </div>
            </div>

        </div>
        <div class="col-lg-4 col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="theme-avtar bg-primary">
                            <i class="ti ti-clipboard-list"></i>
                        </div>
                        <div class="ms-3">
                            <p class="text-muted mb-0">{{ __('Last 7 days hours spent') }}</p>
                            <h4 class="mb-0">
                                {{-- {{ $project_data['timesheet_chart']['total'] }} --}}
                            </h4>

                        </div>
                    </div>
                    <div id="timesheet_chart"></div>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center">
                            <span class="text-muted">{{ __('Total project time spent') }}</span>
                        </div>
                        <span>
                            {{-- {{ $project_data['time_spent']['total'] }} --}}

                        </span>
                    </div>
                    <div class="progress mb-3">
                        <div class="progress-bar bg-primary" {{-- style="width: {{ $project_data['time_spent']['percentage'] }}%" --}}>

                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center">

                            <span class="text-muted">{{ __('Allocated hours on task') }}</span>
                        </div>
                        <span>
                            {{-- {{ $project_data['task_allocated_hrs']['hrs'] }} --}}
                        </span>
                    </div>
                    <div class="progress mb-3">
                        <div class="progress-bar bg-primary" {{-- style="width: {{ $project_data['task_allocated_hrs']['percentage'] }}%" --}}></div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center">
                            <span class="text-muted">{{ __('User Assigned') }}</span>
                        </div>
                        <span>
                            {{-- {{ $project_data['user_assigned']['total'] }} --}}
                        </span>
                    </div>
                    <div class="progress mb-3">
                        <div class="progress-bar bg-primary" {{-- style="width: {{ $project_data['user_assigned']['percentage'] }}%" --}}></div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-lg-6 col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5>{{ __('Members') }}</h5>
                        @can('edit project')
                            <div class="float-end">
                                <a href="#" data-size="lg"
                                    data-url="{{ route('invite.project.member.view', $tasks->id) }}" data-ajax-popup="true"
                                    data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
                                    data-bs-original-title="{{ __('Add Member') }}">
                                    <i class="ti ti-plus"></i>
                                </a>
                            </div>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush list" id="project_users">
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="card">
                <div class="card-header">
                    @can('create milestone')
                        <div class="d-flex align-items-center justify-content-between">
                            <h5>{{ __('Milestones') }}

                                {{-- ({{ count($tasks->milestones) }}) --}}
                            </h5>

                            <div class="float-end">
                                {{-- <a href="#" data-size="md" data-url="{{ route('project.milestone', $tasks>id) }}"
                                    data-ajax-popup="true" data-bs-toggle="tooltip" title=""
                                    class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create New Milestone') }}">
                                    <i class="ti ti-plus"></i>
                                </a> --}}
                            </div>
                        </div>
                    @endcan

                </div>
                {{-- <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @if ($project->milestones->count() > 0)
                            @foreach ($project->milestones as $milestone)
                                <li class="list-group-item px-0">
                                    <div class="row align-items-center justify-content-between">
                                        <div class="col-sm-auto mb-3 mb-sm-0">
                                            <div class="d-flex align-items-center">
                                                <div class="div">
                                                    <h6 class="m-0">{{ $milestone->title }}
                                                        <span
                                                            class="badge-xs badge bg-{{ \App\Models\Project::$status_color[$milestone->status] }} p-2 px-3 rounded">{{ __(\App\Models\Project::$project_status[$milestone->status]) }}</span>
                                                    </h6>
                                                    <small
                                                        class="text-muted">{{ $milestone->tasks->count() . ' ' . __('Tasks') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-auto text-sm-end d-flex align-items-center">
                                            <div class="action-btn bg-warning ms-2">
                                                <a href="#" data-size="md"
                                                    data-url="{{ route('project.milestone.show', $milestone->id) }}"
                                                    data-ajax-popup="true" data-bs-toggle="tooltip"
                                                    title="{{ __('View') }}" class="btn btn-sm">
                                                    <i class="ti ti-eye text-white"></i>
                                                </a>
                                            </div>
                                            <div class="action-btn bg-info ms-2">
                                                <a href="#" data-size="md"
                                                    data-url="{{ route('project.milestone.edit', $milestone->id) }}"
                                                    data-ajax-popup="true" data-bs-toggle="tooltip"
                                                    title="{{ __('Edit') }}" class="btn btn-sm">
                                                    <i class="ti ti-pencil text-white"></i>
                                                </a>
                                            </div>
                                            <div class="action-btn bg-danger ms-2">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['project.milestone.destroy', $milestone->id]]) !!}
                                                <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                    data-bs-toggle="tooltip" title="{{ __('Delete') }}"><i
                                                        class="ti ti-trash text-white"></i></a>

                                                {!! Form::close() !!}
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        @else
                            <div class="py-5">
                                <h6 class="h6 text-center">{{ __('No Milestone Found.') }}</h6>
                            </div>
                        @endif
                    </ul>

                </div> --}}
            </div>
        </div>
        @can('view activity')
            <div class="col-xl-6">
                <div class="card activity-scroll">
                    <div class="card-header">
                        <h5>{{ __('Activity Log') }}</h5>
                        <small>{{ __('Activity Log of this project') }}</small>
                    </div>
                    <div class="card-body vertical-scroll-cards">
                        @foreach ($tasks->user as $activity)
                            <div class="card p-2 mb-2">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="theme-avtar bg-primary">
                                            <i class="ti ti"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="mb-0"></h6>
                                            <p class="text-muted text-sm mb-0"></p>
                                        </div>
                                    </div>
                                    <p class="text-muted text-sm mb-0"></p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endcan
        <div class="col-lg-6 col-md-6">
            <div class="card activity-scroll">
                <div class="card-header">
                    <h5>{{ __('Attachments') }}</h5>
                    <small>{{ __('Attachment that uploaded in this project') }}</small>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        {{-- @if ($tasks->projectAttachments()->count() > 0)
                            @foreach ($project->projectAttachments() as $attachment)
                                <li class="list-group-item px-0">
                                    <div class="row align-items-center justify-content-between">
                                        <div class="col mb-3 mb-sm-0">
                                            <div class="d-flex align-items-center">
                                                <div class="div">
                                                    <h6 class="m-0">{{ $attachment->name }}</h6>
                                                    <small class="text-muted">{{ $attachment->file_size }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto text-sm-end d-flex align-items-center">
                                            <div class="action-btn bg-info ms-2">
                                                <a href="{{ asset(Storage::url('tasks/' . $attachment->file)) }}" download
                                                    data-bs-toggle="tooltip" title="{{ __('Download') }}"
                                                    class="btn btn-sm">
                                                    <i class="ti ti-download text-white"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        @else
                            <div class="py-5">
                                <h6 class="h6 text-center">{{ __('No Attachments Found.') }}</h6>
                            </div>
                        @endif --}}
                    </ul>

                </div>
            </div>
        </div>
    </div>
@endsection
