@if (isset($tasks) && !empty($tasks) && count($tasks) > 0)
    <div class="row">
        @foreach ($tasks as $item)
            <div class="col-md-6 col-xxl-3">
                <div class="card">
                    <div class="card-header border-0 pb-0">

                        <div class="d-flex align-items-center">

                            <h5 class="mb-0"><a class="link-success"
                                    href="{{ route('projects.show', $item) }}">{{ $item->name }}</a>
                            </h5>
                        </div>

                        <div class="card-header-right">
                            <div class="btn-group card-option">
                                <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">

                                    <i class="ti ti-dots-vertical"></i>
                                </button>

                                <div class="dropdown-menu dropdown-menu-end">

                                    {{-- <a href="#!" data-size="lg" data-url="{{ route('sptask.edit', $item->id) }}"
                                        data-ajax-popup="true" class="dropdown-item"
                                        data-bs-original-title="{{ __('Edit Product') }}">
                                        <i class="ti ti-pencil"></i>
                                        <span>{{ __('Edit') }}</span>
                                    </a> --}}

                                    {!! Form::open(['method' => 'DELETE', 'route' => ['sptask.destroy', $item->id]]) !!}
                                    <a href="#!" class="dropdown-item bs-pass-para">
                                        <i class="ti ti-archive"></i>
                                        <span> {{ __('Delete') }}</span>
                                    </a>

                                    {!! Form::close() !!}

                                    {{-- <a href="#!" data-size="lg"
                                        data-url="{{ route('invite.project.member.view', $item->id) }}"
                                        data-ajax-popup="true" class="dropdown-item"
                                        data-bs-original-title="{{ __('Invite User') }}">
                                        <i class="ti ti-send"></i>
                                        <span>{{ __('Invite User') }}</span>
                                    </a> --}}
                                </div>

                            </div>
                        </div>


                        <div class="card-body">
                            <div class="row g-2 justify-content-between">

                                <p class="text-muted">{{ $item->title }}</p>

                                {{-- <small>{{ 'Assined to:' }}</small> --}}
                                <div class="user-group">
                                    @if (isset($item->user))



                                        <small class=" fw-bolder text-success float-end"> {{ $item->taskprogress?$item->taskprogress->name . ' '. 'Stage':''}}</small>





                                        <div class="col mt-3">
                                            <span class=" text-muted">created by:</span>
                                            <span>{{$item->user->name}}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- sub card start-->
                        <div class="card mb-0 mt-3">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-6">
                                        <h6 class=" mb-0">{{ $item->start_date }}</h6>
                                        <p class="text-muted text-sm mb-0">{{ __('Start Date') }}</p>
                                    </div>
                                    <div class="col-6 text-end">

                                        <h6 class=" mb-0">{{ $item->end_date }}</h6>
                                        <p class="text-muted text-sm mb-0">{{ __('Due Date') }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- the end of subcard -->
                        <div style="text-align: center;margin-top: 10px;">
                            <a href="{{ route('sptask.show', $item) }}" class="btn btn-sm btn-primary action-item"
                                role="button" aria-haspopup="true" aria-expanded="false">
                                <span class="btn-inner--icon">{{ __('View Task') }}</span>
                            </a>
                        </div>
                    </div> <!-- the end of the card of the main -->
                </div>

            </div>
        @endforeach
    </div>
@else
    <div class="col-xl-12 col-lg-12 col-sm-12">
        <div class="card">
            <div class="card-body">
                <h6 class="text-center mb-0">{{ __('No Task Found.') }}</h6>
            </div>
        </div>
    </div>
@endif
