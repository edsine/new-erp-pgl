@extends('layouts.admin')
@section('page-title')
    {{__('Dashboard')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Chairman')}}</li>
@endsection

@push('script-page')

    <script>
        
        (function () {
    var options = {
        chart: {
            height: 180,
            type: 'bar',
            toolbar: {
                show: false,
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: 2,
            curve: 'smooth'
        },
        series: [{
            name: "Income",
            data: [{{ $incomesData }}]
        }, {
            name: "Expense",
            data: [{{ $paymentsData }}]
        }],
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        },
        colors: ['#3ec9d6', '#FF3A6E'],
        fill: {
            type: 'solid',
        },
        grid: {
            strokeDashArray: 4,
        },
        legend: {
            show: true,
            position: 'top',
            horizontalAlign: 'right',
        },
    };
    var chart = new ApexCharts(document.querySelector("#incExpBarChart"), options);
    chart.render();
})();


        (function () {
            var options = {
                series: [{{ $users_at_work }}, {{ $users_on_leave }}], // [Staff at work, Staff absent]
                chart: {
                    width: 550,
                    height: 400,
                    type: 'pie',
                },
                labels: ['Staff at Work', 'Staff Absent'],
                colors: ['#4CAF50', '#FF5252'], 
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 400,
                            height: 300,
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }],
                title: {
                    text: 'Staff Attendance',
                    align: 'center',
                    margin: 10,
                    offsetX: 0,
                    offsetY: 0,
                    floating: false,
                    style: {
                        fontSize:  '24px',
                        fontWeight:  'bold',
                        fontFamily:  undefined,
                        color:  '#263238'
                    },
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val + "%"
                        }
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#staffAttendanceChart"), options);
            chart.render();
        })();
       
    </script>



@endpush

@section('content')


        {{-- <div class="row p-3">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-5 col-5 bg-info rounded-0 position-relative me-5 mb-2">
                        <h5 class="text-white my-3">Primary card</h5>
                        <hr class="mb-1 bg-body">
                        <p class="mt-0 text-white"><a href="" class="text-white"><u>view details </u></a> <span class="position-absolute end-0 me-3">></span></p> 
                    </div>
                    <div class="col-md-5 col-5 bg-warning rounded-0 position-relative mb-2">
                        <h5 class="text-white mt-3">Primary card</h5>
                        <hr class="mb-1 bg-body">
                        <p class="mt-0 text-white"><a href="" class="text-white"><u>view details </u></a> <span class="position-absolute end-0 me-3">></span></p> 
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-5 col-5 bg-success rounded-0 position-relative me-5 mb-2">
                        <h5 class="text-white mt-3">Primary card</h5>
                        <hr class="mb-1 bg-body">
                        <p class="mt-0 text-white"><a href="" class="text-white"><u>view details </u></a> <span class="position-absolute end-0 me-3">></span></p> 
                    </div>
                    <div class="col-md-5 col-5 bg-danger rounded-0 position-relative mb-2">
                        <h5 class="text-white mt-3">Primary card</h5>
                        <hr class="mb-1 bg-body">
                        <p class="mt-0 text-white"><a href="" class="text-white"><u>view details </u></a> <span class="position-absolute end-0 me-3">></span></p> 
                    </div>
                </div>
            </div>
            
        </div> --}}

        <div>
            <div class="col-xxl-12">
                <div class="card">
                    <div class="card-header">
                        <h5>{{__('Income & Expense')}}
                            <span class="float-end text-muted">{{__('Current Year 2024')}}</span>
                        </h5>

                    </div>
                    <div class="card-body">
                        <div id="incExpBarChart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="mt-1 mb-0">{{__('Income Vs Expense')}}</h5>
                <div class="row mt-4">

                    <div class="col-md-6 col-6 my-2">
                        <div class="d-flex align-items-start mb-2">
                            <div class="theme-avtar bg-primary">
                                <i class="ti ti-report-money"></i>
                            </div>
                            <div class="ms-2">
                                <p class="text-muted text-sm mb-0">{{__('Income Today')}}</p>
                                <h4 class="mb-0 text-success">{{\Auth::user()->priceFormat($incomes1)}}</h4>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-6 my-2">
                        <div class="d-flex align-items-start mb-2">
                            <div class="theme-avtar bg-info">
                                <i class="ti ti-file-invoice"></i>
                            </div>
                            <div class="ms-2">
                                <p class="text-muted text-sm mb-0">{{__('Expense Today')}}</p>
                                <h4 class="mb-0 text-info">{{\Auth::user()->priceFormat($payments1)}}</h4>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-6 my-2">
                        <div class="d-flex align-items-start mb-2">
                            <div class="theme-avtar bg-warning">
                                <i class="ti ti-report-money"></i>
                            </div>
                            <div class="ms-2">
                                <p class="text-muted text-sm mb-0">{{__('Income This Month')}}</p>
                                <h4 class="mb-0 text-warning">{{\Auth::user()->priceFormat($incomes)}}</h4>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-6 my-2">
                        <div class="d-flex align-items-start mb-2">
                            <div class="theme-avtar bg-danger">
                                <i class="ti ti-file-invoice"></i>
                            </div>
                            <div class="ms-2">
                                <p class="text-muted text-sm mb-0">{{__('Expense This Month')}}</p>
                                <h4 class="mb-0 text-danger">{{\Auth::user()->priceFormat($payments)}}</h4>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

         {{-- staff register div --}}
         <div class="row my-3">
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header">
                        <h5>{{__('Staff Attendance')}}
                            <span class="float-end text-muted">{{__('Current Year 2024')}}</span>
                        </h5>

                    </div>
                    <div class="card-body ms-0">
                        <div id="staffAttendanceChart"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <p class="text-bold fs-5">Staff Register</p>
                <div class="row gap-3">
                    <div class="col-md-12 col-5 card rounded-1 shadow p-2">
                        <div class="mb-1">
                            <p class=""></p>
                        </div>
                        
                        <div class="position-relative pb-2">
                            <p class=" align-middle fw-bolder mt-0 ps-2" style="font-size: 0.9rem"><a href="{{ route('users.index') }}">Total number <br>of staff <br></a><span style="font-size: 0.9rem !important; color:blueviolet;">At Work</span></p>
                            <span class=" border-start border-2 align-middle border-secondary top-0 end-0 position-absolute fs-2 fw-bold"> {{ $users_at_work }}</span>
                        </div>
                    </div>
                    <div class="col-md-12 col-5 card rounded-1 shadow p-2 ">
                        <div class="mb-1">
                            <p class="text-black-50"></p>
                        </div>
                        
                        <div class="position-relative pb-2">
                            <p class=" align-middle fw-bolder mt-0 ps-2" style="font-size: 0.9rem"><a href="{{ route('users.index') }}">Total number <br>of staff <br></a><span style="font-size: 0.9rem !important; color: #FF5252;">On Leave</span></p>
                            <span class=" border-start border-2 align-middle border-secondary  top-0 end-0 position-absolute fs-2 fw-bold"> {{ $users_on_leave }}</span>
                        </div>
                    </div>
                    <div class="col-md-12 col-5 card rounded-1 shadow p-2 ">
                        <div class="mb-1">
                            <p class="text-black-50"></p>
                        </div>
                        
                        <div class="position-relative pb-2">
                            <p class=" align-middle fw-bolder mt-0 ps-2" style="font-size: 0.9rem"><a href="{{ route('customer.index') }}">Total number <br>of clients <br></a><span class="" style="font-size: 0.9rem !important;color:aqua">Client</span></p>
                            <span class=" border-start border-2 align-middle border-secondary top-0 end-0 position-absolute fs-2 fw-bold"> {{ $users_as_client }}</span>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    
        <div class="row border-bottom border-4">
            <div class="col-sm-12 min-vh-50">
                {{-- Accounts statement div --}}
                {{-- <div class="row border-bottom mb-3">
                    <p class="text-bold fs-5">Account Statement</p>
                    <div class="col-md-6 card rounded-1 shadow p-2 ">
                        <p class="text-black-50">Month/Year</p>
                        <div class="position-relative pb-2">
                            <p class=" align-middle fw-bolder my-1" style="font-size: 0.8rem">Income- <span>{{ Auth::user()->priceFormat($incomes) }}</span></p>
                            <a href="{{ route('revenue.index') }}" class="text-white btn-success btn-sm border-none btn border-0 align-middle top-0 end-0 position-absolute"> view details</a>
                        </div>
                    </div>
                    <div class="col-md-6 card rounded-1 shadow p-2 ">
                        <p class="text-black-50">Month/Year</p>
                        <div class="position-relative pb-2">
                            <p class=" align-middle fw-bolder my-1" style="font-size: 0.8rem">Expenses- <span>{{ Auth::user()->priceFormat($payments) }}</span></p>
                            <a href="{{ route('payment.index') }}" class="text-white btn-success btn-sm border-none btn border-0 align-middle top-0 end-0 position-absolute "> view details</a>
                        </div>
                    </div>
                </div> --}}

                {{-- Requisition div --}}
                <div class="row border-bottom mb-3">
                    <p class="text-bold fs-5">Requisition</p>
                    <div class="col-md-5 card rounded-1 shadow p-2 me-5">
                        <p class="text-black-50">Need Approvals</p>
                        @foreach($approvals as $item)
                        <div class="position-relative pb-2">
                            <p class=" align-middle fw-bolder my-1" style="font-size: 0.75rem">{{substr($item->title,0, 30)}}</p>
                            <a href="#" 
                            data-url="{{ URL::to('requisition/' . $item->id . '/action1') }}"
                            data-size="lg" 
                            data-ajax-popup="true"
                            data-title="{{ __('Requisition Action') }}"
                            data-bs-toggle="tooltip"
                            title="{{ __('Requisition Action') }}"
                            data-original-title="{{ __('Requisition Action') }}" class="text-white btn-success btn-sm btn border-0 align-middle top-0 end-0 position-absolute"> view details</a>
                        </div>
                        @endforeach
                        <div>
                            <a href="{{ route('chairman.dashboard') }}" class="btn btn-sm btn-secondary float-end mt-3">view all</a>
                        </div>
                    </div>
                    <div class="col-md-5 card rounded-1 shadow p-2 ">
                        <p class="text-black-50">Requisition History</p>
                        @foreach($requisitions as $item)
                        <div class="position-relative pb-2">
                            <p class=" align-middle fw-bolder my-1" style="font-size: 0.75rem">{{substr($item->title,0, 30)}}</p>
                            <a href="#" 
                            data-url="{{ URL::to('requisition/' . $item->id . '/action1') }}"
                            data-size="lg" 
                            data-ajax-popup="true"
                            data-title="{{ __('Requisition Action') }}"
                            data-bs-toggle="tooltip"
                            title="{{ __('Requisition Action') }}"
                            data-original-title="{{ __('Requisition Action') }}" class="text-white btn-success btn-sm btn border-0 align-middle top-0 end-0 position-absolute"> view details</a>
                        </div>
                        @endforeach
                        <div>
                            <a href="{{ route('requisition.index') }}" class="btn btn-sm btn-secondary mt-3 bottom-0 position-absolute end-0 m-2">view all</a>
                        </div>
                    </div>
                </div>

                {{-- Projects div --}}
                <div class="row  mb-3 gap-3">
                    <p class="text-bold fs-5">Projects</p>
                    <div class="col-md-3 col-5 card rounded-1 shadow p-2">
                        <div class="mb-1">
                            <p class=""></p>
                        </div>
                        
                        <div class="position-relative pb-2">
                            <p class=" align-middle fw-bolder mt-1 ps-2 text-black-50" style="font-size: 0.8rem"><a href="{{ route('projects.index') }}">Ongoing <br>Projects</a></p>
                            <span class=" border-start border-2 align-middle border-secondary top-0 end-0 position-absolute fs-2 fw-bold"> {{ $ongoing_projects}}</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-5 card rounded-1 shadow p-2 ">
                        <div class="mb-1">
                            <p class="text-black-50"></p>
                        </div>
                        
                        <div class="position-relative pb-2">
                            <p class=" align-middle fw-bolder mt-1 ps-2 text-black-50" style="font-size: 0.8rem"><a href="{{ route('projects.index') }}">Concluded <br>Projects</a></p>
                            <span class=" border-start border-2 align-middle border-secondary  top-0 end-0 position-absolute fs-2 fw-bold"> {{ $completed_projects}}</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-5 card rounded-1 shadow p-2 ">
                        <div class="mb-1">
                            <p class="text-black-50"></p>
                        </div>
                        
                        <div class="position-relative pb-2">
                            <p class=" align-middle fw-bolder mt-1 ps-2 text-black-50" style="font-size: 0.8rem"><a href="{{ route('projects.index') }}">Prospective <br>Projects</a></p>
                            <span class=" border-start border-2 align-middle border-secondary top-0 end-0 position-absolute fs-2 fw-bold"> {{ $on_hold_projects}}</span>
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
                                    <p class="><b>Total Amount(N):</b> <span>N200000</span></p>
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

