@extends('layouts.admin')
@section('page-title')
    {{__('Dashboard')}}
@endsection
@push('script-page')
    <script>

        (function () {
            var etitle;
            var etype;
            var etypeclass;
            var calendar = new FullCalendar.Calendar(document.getElementById('event_calendar'), {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'timeGridDay,timeGridWeek,dayGridMonth'
                },
                buttonText: {
                    timeGridDay: "{{__('Day')}}",
                    timeGridWeek: "{{__('Week')}}",
                    dayGridMonth: "{{__('Month')}}"
                },
                themeSystem: 'bootstrap',
                navLinks: true,
                droppable: true,
                selectable: true,
                selectMirror: true,
                editable: true,
                dayMaxEvents: true,
                handleWindowResize: true,
                events: {!! json_encode($arrEvents) !!},
                locale: '{{basename(App::getLocale())}}',
                dayClick: function (e) {
                    var t = moment(e).toISOString();
                    $("#new-event").modal("show"), $(".new-event--title").val(""), $(".new-event--start").val(t), $(".new-event--end").val(t)
                },
                eventResize: function (event) {
                    var eventObj = {
                        start: event.start.format(),
                        end: event.end.format(),
                    };
                },
                viewRender: function (t) {
                    e.fullCalendar("getDate").month(), $(".fullcalendar-title").html(t.title)
                },
                eventClick: function (e, t) {
                    var title = e.title;
                    var url = e.url;

                    if (typeof url != 'undefined') {
                        $("#commonModal .modal-title").html(title);
                        $("#commonModal .modal-dialog").addClass('modal-md');
                        $("#commonModal").modal('show');
                        $.get(url, {}, function (data) {
                            $('#commonModal .modal-body').html(data);
                        });
                        return false;
                    }
                }
            });
            calendar.render();
        })();
    </script>
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Chairman')}}</li>
@endsection
@section('content')
    @if(\Auth::user()->type != 'client' && \Auth::user()->type != 'company')
        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-xxl-12">
                        <div class="card lis pb-4">
                            <div class="card-header pb-2">
                                <h4 class="text-secondary">{{__('Pending Approvals')}}</h4>
                            </div>
                            <div class="row d-flex mx-1">
                                <div class="col-6 ms-2 my-auto fs-2 align-items-center">
                                    <p class="mt-2 " style="font-size: 15px; font-weight: 600;">Fuel Requisition</p>
                                </div>
                                <div class="col  m-3">
                                    <a href="" data-toggle="modal" data-target="#requisitionDetails" data-whatever="Requisition name" class="btn btn-success float-end px-3">View Details</a>
                                </div>
                            </div>

                            <div class="row flex mx-1">
                                <div class="col-6 ms-2 my-auto fs-2 align-items-center">
                                    <p class="mt-2 " style="font-size: 15px; font-weight: 600;">Fuel Requisition</p>
                                </div>
                                <div class="col m-3">
                                    <a href="#" class="btn btn-success float-end px-3">View Details</a>
                                </div>
                            </div>
                            <div class="row flex mx-1">
                                <div class="col-6 ms-2 my-auto fs-2 align-items-center">
                                    <p class="mt-2" style="font-size: 15px; font-weight: 600;">Fuel Requisition for my money innit</p>
                                </div>
                                <div class="col m-3">
                                    <a href="#" class="btn btn-success float-end px-3">View Details</a>
                                </div>
                            </div>
                            <div class="row flex mx-1">
                                <div class="col-6 ms-2 my-auto fs-2 align-items-center">
                                    <p class="mt-2" style="font-size: 15px; font-weight: 600;">Fuel Requisition for my money innit</p>
                                </div>
                                <div class="col m-3">
                                    <a href="#" class="btn btn-success float-end px-3">View Details</a>
                                </div>
                            </div>
                            
                        </div>
                    </div>

                    {{-- meetings --}}
                    <div class="col-xxl-12">
                        <div class="card pb-4 list_card">
                            <div class="row card-header pb-2">
                                <div class="col my-auto">
                                    <h4 class="text-secondary">{{__('Meeting List')}}</h4>
                                </div>
                                
                                <div class="col my-auto">
                                    <a href="#" data-size="lg" data-url="{{route("users.create")}}" data-ajax-popup="true" data-bs-toggle="tooltip" class="btn float-end">
                                        <i class="ti ti-plus"></i>
                                    </a>
                                </div>
                            </div>
                            @if(count($meetings) > 0)
                            <div class="row d-flex mx-1">
                                <div class="col-6 ms-2 my-auto fs-2 align-items-center">
                                    <p class="mt-2 " style="font-size: 15px; font-weight: 600;">Board Meeting</p>
                                </div>
                                <div class="col  m-3">
                                    <a href="#" class="btn btn-success float-end px-3">View Details</a>
                                </div>
                            </div>

                            <div class="row flex mx-1">
                                <div class="col-6 ms-2 my-auto fs-2 align-items-center">
                                    <p class="mt-2 " style="font-size: 15px; font-weight: 600;">Investor Meeting</p>
                                </div>
                                <div class="col m-3">
                                    <a href="#" class="btn btn-success float-end px-3">View Details</a>
                                </div>
                            </div>
                            <div class="row flex mx-1">
                                <div class="col-6 ms-2 my-auto fs-2 align-items-center">
                                    <p class="mt-2" style="font-size: 15px; font-weight: 600;">Meeting with Elon Musk</p>
                                </div>
                                <div class="col m-3">
                                    <a href="#" class="btn btn-success float-end px-3">View Details</a>
                                </div>
                            </div>

                            @else
                            <div class="mt-3 ms-4 my-auto align-items-center" style="font-size: 15px; font-weight: 400;">
                                {{__('No meeting scheduled yet.')}}
                            </div>
                            @endif
                        </div>
                    </div>


                    {{-- announcement --}}
                    <div class="col-xxl-6">
                        <div class="card list_card">
                            <div class="card-header">
                                <h4>{{__('Announcement List')}}</h4>
                            </div>
                            <div class="card-body dash-card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped mb-0">
                                        <thead>
                                        <tr>
                                            <th>{{__('Title')}}</th>
                                            <th>{{__('Start Date')}}</th>
                                            <th>{{__('End Date')}}</th>
                                            <th>{{__('description')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($announcements as $announcement)
                                            <tr>
                                                <td>{{ $announcement->title }}</td>
                                                <td>{{ \Auth::user()->dateFormat($announcement->start_date)  }}</td>
                                                <td>{{ \Auth::user()->dateFormat($announcement->end_date) }}</td>
                                                <td>{{ $announcement->description }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4">
                                                    <div class="text-center">
                                                        <h6>{{__('There is no Announcement List')}}</h6>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        

                    </div>
                </div>
            </div>
        </div>

        {{-- requisiton information Modal--}}
        <div class="modal fade " id="requisitionDetails" tabindex="-1" role="dialog" aria-labelledby="requisitionDetailsTitle" aria-hidden="true">
            <div class="modal-dialog my-5" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="requisitionDetailsTitle">Requisition Action</h5>
                  <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body ">
                  <form action="" class="mb-0">
                    <div> 
                        <div class="form-group">
                            <label for="" class="pb-1 form-label">Employee</label><br>
                            <input type="text" class="form-control" value="Odaa Jennifer" aria-label="employee" readonly>
                        </div>
                        <div class="form-group">
                            <label for="" class="pb-1 form-label">Issue Date</label><br>
                            <input type="text" class="form-control" value="21/06/2024" aria-label="employee" readonly>
                        </div>
                        <div class="form-group">
                            <label for="" class="pb-1 form-label">Reference Number</label><br>
                            <input type="text" class="form-control" value="req/202406211323" aria-label="employee" readonly>
                        </div>
                        <div class="form-group">
                            <label for="" class="pb-1 form-label">Requisition Title</label><br>
                            <input type="text" class="form-control" value="Fuel Requisition" aria-label="employee" readonly>
                        </div>
                    </div>
                    <div>
                        <h6>Requisition</h6>
                        <div>
                            <div class="table-responsive">
                                <table class="table table-custom-style mb-0" data-repeater-list="items" id="sortable-table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Items') }}</th>
                                            <th></th>
                                            <th>{{ __('Quantity') }}</th>
                                            <th></th>
                                            <th>{{ __('Rate') }} </th>
                                            <th class="text-end">{{ __('Amount') }}</th>
                                        </tr>
                                    </thead>
                                    {{-- @foreach ($requisitionItem as $value) --}}
                                    <tbody class="ui-sortable" data-repeater-item>
                                        <tr>
                                            <td width="45%" class="pt-3">
                                                <p>Odaa Jennifer</p>
                                            </td>
                                            <td></td>
                                            <td width="15%">
                                                <div class="form-group price-input input-group search-form my-auto pt-2">
                                                    <p>10 litres</p>
                                                </div>
                                            </td>
                                            <td></td>
                                            <td>
                                                <div class="form-group price-input input-group search-form my-auto pt-2">
                                                    <p>65</p>
                                                    {{-- <span class="input-group-text bg-transparent">
                                                        {{ \Auth::user()->currencySymbol() }}
                                                    </span> --}}
                                                </div>
                                            </td>
        
                                            <td >
                                                <div class="text-end amount pt-3">
                                                    <p>0.00</p>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row p-0">
                                {{-- <div class="col-5 my-3">
                                    <p><b>additional document</b></p>
                                </div> --}}
                                <div class="col my-2">
                                    <p>view additional document</p>
                                    <div class="action-btn bg-secondary ms-2">
                                        <a class=" btn btn-sm align-items-center " href="" target="_blank"  >
                                            <i class="ti ti-crosshair text-white" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Preview Document') }}"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col p-2">
                                    <p class="float-end"><b>Total Amount(N):</b> <span>N200000</span></p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label("chairmans_remark", __("Chairman's Remark"), ['class' => 'form-label']) }}<br>
                                    <textarea class="form-control" id=""  rows="5"></textarea>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    
                  </form>
                </div>
                <div class="modal-footer mt-0">
                  <button type="button" class="btn btn-success" data-dismiss="modal">Approve</button>
                  <button type="button" class="btn btn-danger">Dis Approve</button>
                </div>
              </div>
            </div>
          </div>


    @else
        <div class="row">
            <div class="col-xxl-12">
                <div class="card">
                    <div class="card-header">
                        <h5>{{__("Today's Not Clock In")}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-12">
                                <div class="row g-3 flex-nowrap team-lists horizontal-scroll-cards">
                                    @foreach($notClockIns as $notClockIn)

                                        <div class="col-auto">
                                            <img src="{{(!empty($notClockIn->user))? $notClockIn->user->profile : asset(Storage::url('uploads/avatar/avatar.png'))}}" alt="">

                                            <p class="mt-2">{{ $notClockIn->name }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-md-9">
                        <div class="card">
                            <div class="card-header">
                                <h5>{{__('Event')}}</h5>
                            </div>
                            <div class="card-body">
                                <div id='event_calendar' class='calendar'></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="col-xxl-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5>{{__('Staff')}}</h5>
                                    <div class="row  mt-4">
                                        <div class="col-md-6 col-sm-6">
                                            <div class="d-flex align-items-start mb-3">
                                                <div class="theme-avtar bg-primary">
                                                    <i class="ti ti-users"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Total Staff')}}</p>
                                                    <h4 class="mb-0 text-success">{{ $countUser +   $countClient}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 my-3 my-sm-0">
                                            <div class="d-flex align-items-start mb-3">
                                                <div class="theme-avtar bg-info">
                                                    <i class="ti ti-user"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Employee')}}</p>
                                                    <h4 class="mb-0 text-primary">{{$countUser}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <div class="d-flex align-items-start mb-3">
                                                <div class="theme-avtar bg-danger">
                                                    <i class="ti ti-user"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Client')}}</p>
                                                    <h4 class="mb-0 text-danger">{{$countClient}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-xxl-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5>{{__('Job')}}</h5>
                                    <div class="row  mt-4">
                                        <div class="col-md-6 col-sm-6">
                                            <div class="d-flex align-items-start mb-3">
                                                <div class="theme-avtar bg-primary">
                                                    <i class="ti ti-award"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Total Jobs')}}</p>
                                                    <h4 class="mb-0 text-success">{{$activeJob + $inActiveJOb}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 my-3 my-sm-0">
                                            <div class="d-flex align-items-start mb-3">
                                                <div class="theme-avtar bg-info">
                                                    <i class="ti ti-check"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Active Job')}}</p>
                                                    <h4 class="mb-0 text-primary">{{$activeJob}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <div class="d-flex align-items-start mb-3">
                                                <div class="theme-avtar bg-danger">
                                                    <i class="ti ti-x"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Inactive Job ')}}</p>
                                                    <h4 class="mb-0 text-danger">{{$inActiveJOb}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div> --}}
                        <div class="col-xxl-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5>{{__('Training')}}</h5>
                                    <div class="row  mt-4">
                                        <div class="col-md-6 col-sm-6">
                                            <div class="d-flex align-items-start mb-3">
                                                <div class="theme-avtar bg-primary">
                                                    <i class="ti ti-users"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Total Training')}}</p>
                                                    <h4 class="mb-0 text-success">{{ $onGoingTraining +   $doneTraining}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 my-3 my-sm-0">
                                            <div class="d-flex align-items-start mb-3">
                                                <div class="theme-avtar bg-info">
                                                    <i class="ti ti-user"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Trainer')}}</p>
                                                    <h4 class="mb-0 text-primary">{{$countTrainer}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <div class="d-flex align-items-start mb-3">
                                                <div class="theme-avtar bg-danger">
                                                    <i class="ti ti-user-check"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Active Training')}}</p>
                                                    <h4 class="mb-0 text-danger">{{$onGoingTraining}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <div class="d-flex align-items-start mb-3">
                                                <div class="theme-avtar bg-secondary">
                                                    <i class="ti ti-user-minus"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Done Training')}}</p>
                                                    <h4 class="mb-0 text-secondary">{{$doneTraining}}</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">

                                <h5>{{__('Announcement List')}}</h5>
                            </div>
                            <div class="card-body" style="min-height: 295px;">
                                <div class="table-responsive">
                                    @if(count($announcements) > 0)
                                        <table class="table align-items-center">
                                            <thead>
                                            <tr>
                                                <th>{{__('Title')}}</th>
                                                <th>{{__('Start Date')}}</th>
                                                <th>{{__('End Date')}}</th>

                                            </tr>
                                            </thead>
                                            <tbody class="list">
                                            @foreach($announcements as $announcement)
                                                <tr>
                                                    <td>{{ $announcement->title }}</td>
                                                    <td>{{ \Auth::user()->dateFormat($announcement->start_date) }}</td>
                                                    <td>{{ \Auth::user()->dateFormat($announcement->end_date) }}</td>

                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <div class="p-2">
                                            No accouncement present yet.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>{{__('Meeting schedule')}}</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    @if(count($meetings) > 0)
                                        <table class="table align-items-center">
                                            <thead>
                                            <tr>
                                                <th>{{__('Title')}}</th>
                                                <th>{{__('Date')}}</th>
                                                <th>{{__('Time')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody class="list">
                                            @foreach($meetings as $meeting)
                                                <tr>
                                                    <td>{{ $meeting->title }}</td>
                                                    <td>{{ \Auth::user()->dateFormat($meeting->date) }}</td>
                                                    <td>{{  \Auth::user()->timeFormat($meeting->time) }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <div class="p-2">
                                            No meeting scheduled yet.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ sample-page ] end -->
        </div>
    @endif
@endsection




<script>
    $('#requisitionDetails').on('show.bs.modal', function (event) {
        var link = $(event.relatedTarget); // Link that triggered the modal
        var recipient = link.data('whatever'); // Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this);
        modal.find('.modal-title').text('New message to ' + recipient);
        modal.find('.modal-body').text('New message to ' + recipient);
    });
</script>