@extends('layouts.admin')
@section('page-title')
    {{ __(' TASKS') }}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Task') }}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        @if ($view == 'grid')
            <a href="{{ route('project_products.list', 'list') }}" data-bs-toggle="tooltip" title="{{ __('List View') }}"
                class="btn btn-sm btn-primary">
                <i class="ti ti-list"></i>
            </a>
        @else
            <a href="{{ route('project_products.index') }}" data-bs-toggle="tooltip" title="{{ __('Grid View') }}"
                class="btn btn-sm btn-primary">
                <i class="ti ti-layout-grid"></i>
            </a>
        @endif


        {{-- ---------- Start Filter -------------- --}}
        <a href="#" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            <i class="ti ti-filter"></i>
        </a>
        <div class="dropdown-menu  dropdown-steady" id="project_sort">
            <a class="dropdown-item active" href="#" data-val="created_at-desc">
                <i class="ti ti-sort-descending"></i>{{ __('Newest') }}
            </a>
            <a class="dropdown-item" href="#" data-val="created_at-asc">
                <i class="ti ti-sort-ascending"></i>{{ __('Oldest') }}
            </a>

            <a class="dropdown-item" href="#" data-val="project_name-desc">
                <i class="ti ti-sort-descending-letters"></i>{{ __('From Z-A') }}
            </a>
            <a class="dropdown-item" href="#" data-val="project_name-asc">
                <i class="ti ti-sort-ascending-letters"></i>{{ __('From A-Z') }}
            </a>
        </div>

        {{-- ---------- End Filter -------------- --}}

        {{-- ---------- Start Status Filter -------------- --}}
        <a href="#" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            <span class="btn-inner--icon">{{ __('Status') }}</span>
        </a>
        <div class="dropdown-menu  project-filter-actions dropdown-steady" id="project_status">
            <a class="dropdown-item filter-action filter-show-all pl-4 active" href="#">{{ __('Show All') }}</a>
            @foreach (\App\Models\Project::$project_status as $key => $val)
                <a class="dropdown-item filter-action pl-4" href="#"
                    data-val="{{ $key }}">{{ __($val) }}</a>
            @endforeach
        </div>
        {{-- ---------- End Status Filter -------------- --}}


        @can('create project')
            <a href="#" data-size="lg" data-bs-toggle="modal" data-bs-target="#taskmodule"
                title="{{ __('Create New Task') }}" class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i>
            </a>
        @endcan
    </div>
@endsection

@section('content')
    <div class="row min-750">
       @include('sptask.viewpage');


</div>




<!-- Modal -->
<div class="modal fade" id="taskmodule" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('sptask.store') }}" method="post">

                @csrf


                <div class="modal-body">

                    <div class="form-group">
                        {!! Form::label('title', 'TITLE', ['class' => 'form-label']) !!}
                        {!! Form::text('title', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('description', 'DESCRIPTION', ['class' => 'form-label']) !!}
                        {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
                    </div>

                    <div class="row ">

                        <div class="col-6">

                            <div class="form-group">
                                {!! Form::label('start_date', 'START DATE', ['class' => 'form-label']) !!}
                                {!! Form::date('start_date', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-6">

                            <div class="form-group">
                                {!! Form::label('end_date', 'END DATE', ['class' => 'form-label']) !!}
                                {!! Form::date('end_date', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row ">

                        <div class="col-6">
                            <div class="form-group">
                                {!! Form::label('user_id', 'USER', ['class' => 'form-label']) !!}
                                {!! Form::select('user_id', $user->pluck('name', 'id'), null, ['class' => 'form-control']) !!}
                            </div>

                        </div>
                        <div class="col-6">

                            <div class="form-group">
                                {!! Form::label('department_id', 'DEPARTMENT', ['class' => 'form-label']) !!}
                                {!! Form::select('department_id', $dept->pluck('name', 'id'), null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('tags', 'TAGS', ['class' => 'form-label']) !!}
                        {!! Form::text('tags', null, ['class' => 'form-control']) !!}
                    </div>

                    {!! Form::hidden('created_by', auth::check() ? auth()->user()->id : '') !!}

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="sumbit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script-page')
<script>
    $(document).ready(function() {
        var sort = 'created_at-desc';
        var status = '';
        ajaxFilterProjectView('created_at-desc');
        $(".project-filter-actions").on('click', '.filter-action', function(e) {
            if ($(this).hasClass('filter-show-all')) {
                $('.filter-action').removeClass('active');
                $(this).addClass('active');
            } else {
                $('.filter-show-all').removeClass('active');
                if ($(this).hasClass('active')) {
                    $(this).removeClass('active');
                    $(this).blur();
                } else {
                    $(this).addClass('active');
                }
            }

            var filterArray = [];
            var url = $(this).parents('.project-filter-actions').attr('data-url');
            $('div.project-filter-actions').find('.active').each(function() {
                filterArray.push($(this).attr('data-val'));
            });

            status = filterArray;

            ajaxFilterProjectView(sort, $('#project_keyword').val(), status);
        });

        // when change sorting order
        $('#project_sort').on('click', 'a', function() {
            sort = $(this).attr('data-val');
            ajaxFilterProjectView(sort, $('#project_keyword').val(), status);
            $('#project_sort a').removeClass('active');
            $(this).addClass('active');
        });

        // when searching by project name
        $(document).on('keyup', '#project_keyword', function() {
            ajaxFilterProjectView(sort, $(this).val(), status);
        });


        $(document).on('click', '.invite_usr', function() {
            var project_id = $('#project_id').val();
            var user_id = $(this).attr('data-id');

            $.ajax({
                url: '{{ route('invite.project.user.member') }}',
                method: 'POST',
                dataType: 'json',
                data: {
                    'project_id': project_id,
                    'user_id': user_id,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(data) {
                    if (data.code == '200') {
                        show_toastr(data.status, data.success, 'success')
                        setInterval('location.reload()', 5000);
                    } else if (data.code == '404') {
                        show_toastr(data.status, data.errors, 'error')
                    }
                }
            });
        });
    });

    var currentRequest = null;

    function ajaxFilterProjectView(project_sort, keyword = '', status = '') {
        var mainEle = $('#project_view');
        var view = '{{ $view }}';
        var data = {
            view: view,
            sort: project_sort,
            keyword: keyword,
            status: status,
        }

        currentRequest = $.ajax({
            url: '{{ route('filter.project_products.view') }}',
            data: data,
            beforeSend: function() {
                if (currentRequest != null) {
                    currentRequest.abort();
                }
            },
            success: function(data) {
                mainEle.html(data.html);
                $('[id^=fire-modal]').remove();
                loadConfirm();
            }
        });
    }
</script>
@endpush
