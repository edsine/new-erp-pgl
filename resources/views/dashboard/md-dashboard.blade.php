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