@extends('layouts.admin')
@section('page-title')
    {{__('Dashboard')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Chairman')}}</li>
@endsection
@section('content')
    
        <div class="row border-bottom border-4">
            <div class="col-sm-8 min-vh-50">
                {{-- Accounts statement div --}}
                <div class="row border-bottom mb-3">
                    <p class="text-bold fs-5">Account Statement</p>
                    <div class="col-md-6 card rounded-1 shadow p-2 ">
                        <p class="text-black-50">Month/Year</p>
                        <div class="position-relative pb-2">
                            <p class=" align-middle fw-bolder my-1" style="font-size: 0.8rem">Income- <span>N0000000</span></p>
                            <button class="text-white btn-success btn-sm border-none btn border-0 align-middle top-0 end-0 position-absolute"> view details</button>
                        </div>
                    </div>
                    <div class="col-md-6 card rounded-1 shadow p-2 ">
                        <p class="text-black-50">Month/Year</p>
                        <div class="position-relative pb-2">
                            <p class=" align-middle fw-bolder my-1" style="font-size: 0.8rem">Expenses- <span>N0000000</span></p>
                            <button class="text-white btn-success btn-sm border-none btn border-0 align-middle top-0 end-0 position-absolute "> view details</button>
                        </div>
                    </div>
                </div>

                {{-- Requisition div --}}
                <div class="row border-bottom mb-3">
                    <p class="text-bold fs-5">Requisition</p>
                    <div class="col-md-6 card rounded-1 shadow p-2 ">
                        <p class="text-black-50">Need Approvals</p>
                        <div class="position-relative pb-2">
                            <p class=" align-middle fw-bolder my-1" style="font-size: 0.75rem">Request for fuel</p>
                            <button class="text-white btn-success btn-sm btn border-0 align-middle top-0 end-0 position-absolute"> view details</button>
                        </div>
                        <div class="position-relative pb-2">
                            <p class=" align-middle fw-bolder my-1" style="font-size: 0.75rem">Request for increment</p>
                            <button class="text-white btn-success btn-sm btn border-0 align-middle top-0 end-0 position-absolute"> view details</button>
                        </div>
                        <div class="position-relative pb-2">
                            <p class=" align-middle fw-bolder my-1" style="font-size: 0.75rem">Request for training</p>
                            <button class="text-white btn-success btn-sm btn border-0 align-middle top-0 end-0 position-absolute"> view details</button>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-secondary float-end mt-3">view others</button>
                        </div>
                    </div>
                    <div class="col-md-6 card rounded-1 shadow p-2 ">
                        <p class="text-black-50">Requisition History</p>
                        <div class="position-relative pb-2">
                            <p class=" align-middle fw-bolder my-1" style="font-size: 0.75rem">Dollar account</p>
                            <button class="text-white btn-success btn-sm border-none btn border-0 align-middle top-0 end-0 position-absolute "> view details</button>
                        </div>
                        <div class="position-relative pb-2">
                            <p class=" align-middle fw-bolder my-1" style="font-size: 0.75rem">requisition for internet</span></p>
                            <button class="text-white btn-success btn-sm btn border-0 align-middle top-0 end-0 position-absolute"> view details</button>
                        </div>
                        <div class="position-relative pb-2">
                            <p class=" align-middle fw-bolder my-1" style="font-size: 0.75rem">salary increment</span></p>
                            <button class="text-white btn-success btn-sm btn border-0 align-middle top-0 end-0 position-absolute"> view details</button>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-secondary float-end mt-3">view others</button>
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
                            <p class=" align-middle fw-bolder mt-1 ps-2 text-black-50" style="font-size: 0.8rem">Ongoing <br>Projects</p>
                            <span class=" border-start border-2 align-middle border-secondary top-0 end-0 position-absolute fs-2 fw-bold"> 02</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-5 card rounded-1 shadow p-2 ">
                        <div class="mb-1">
                            <p class="text-black-50"></p>
                        </div>
                        
                        <div class="position-relative pb-2">
                            <p class=" align-middle fw-bolder mt-1 ps-2 text-black-50" style="font-size: 0.8rem">Concluded <br>Projects</p>
                            <span class=" border-start border-2 align-middle border-secondary  top-0 end-0 position-absolute fs-2 fw-bold"> 02</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-5 card rounded-1 shadow p-2 ">
                        <div class="mb-1">
                            <p class="text-black-50"></p>
                        </div>
                        
                        <div class="position-relative pb-2">
                            <p class=" align-middle fw-bolder mt-1 ps-2 text-black-50" style="font-size: 0.8rem">Prospective <br>Projects</p>
                            <span class=" border-start border-2 align-middle border-secondary top-0 end-0 position-absolute fs-2 fw-bold"> 02</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- letters div --}}
            <div class="col-sm-4 min-vh-50 bg-red">
                <p class="text-bold fs-5">Letters</p>
                <div class="row card" style="min-height: 250px">
                    <div class="col p-3">
                        <p class="text-black-50">Incoming/ Recieved Letters</p>
                        <div class="position-relative pb-2">
                            <p class=" align-middle fw-bolder my-1" style="font-size: 0.75rem">Title of letter<p>
                            <button class="text-white btn-success btn-sm border-none btn border-0 align-middle top-0 end-0 position-absolute "> view details</button>
                        </div>
                        <div class="position-relative pb-2">
                            <p class=" align-middle fw-bolder my-1" style="font-size: 0.75rem">Title of letter</span></p>
                            <button class="text-white btn-success btn-sm btn border-0 align-middle top-0 end-0 position-absolute"> view details</button>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-secondary bottom-0 end-0 position-absolute m-3">view others</button>
                        </div>
                    </div>
                </div>

                <div class="row card" style="min-height: 250px">
                    <div class="col p-3">
                        <p class="text-black-50">Ongoing/ Sent Letters</p>
                        <div class="position-relative pb-2">
                            <p class=" align-middle fw-bolder my-1" style="font-size: 0.75rem">Title of letter</p>
                            <button class="text-white btn-success btn-sm border-none btn border-0 align-middle top-0 end-0 position-absolute "> view details</button>
                        </div>
                        <div class="position-relative pb-2">
                            <p class=" align-middle fw-bolder my-1" style="font-size: 0.75rem">Title of letter</span></p>
                            <button class="text-white btn-success btn-sm btn border-0 align-middle top-0 end-0 position-absolute"> view details</button>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-secondary bottom-0 end-0 position-absolute m-3">view others</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- staff register div --}}
        <div class="row  my-3 gap-3 ">
            <p class="text-bold fs-5">Staff Register</p>
            <div class="col-md-2 col-5 card rounded-1 shadow p-2">
                <div class="mb-1">
                    <p class=""></p>
                </div>
                
                <div class="position-relative pb-2">
                    <p class=" align-middle fw-bolder mt-0 ps-2" style="font-size: 0.6rem">Total number <br>of staff <br><span class="text-warning" style="font-size: 0.9rem !important">On Leave</span></p>
                    <span class=" border-start border-2 align-middle border-secondary top-0 end-0 position-absolute fs-2 fw-bold"> 02</span>
                </div>
            </div>
            <div class="col-md-2 col-5 card rounded-1 shadow p-2 ">
                <div class="mb-1">
                    <p class="text-black-50"></p>
                </div>
                
                <div class="position-relative pb-2">
                    <p class=" align-middle fw-bolder mt-0 ps-2" style="font-size: 0.6rem">Total number <br>of staff <br><span style="font-size: 0.9rem !important; color: rgb(213, 213, 32);">On Leave</span></p>
                    <span class=" border-start border-2 align-middle border-secondary  top-0 end-0 position-absolute fs-2 fw-bold"> 02</span>
                </div>
            </div>
            <div class="col-md-2 col-5 card rounded-1 shadow p-2 ">
                <div class="mb-1">
                    <p class="text-black-50"></p>
                </div>
                
                <div class="position-relative pb-2">
                    <p class=" align-middle fw-bolder mt-0 ps-2" style="font-size: 0.6rem">Total number <br>of staff <br><span class="text-success" style="font-size: 0.9rem !important">On Leave</span></p>
                    <span class=" border-start border-2 align-middle border-secondary top-0 end-0 position-absolute fs-2 fw-bold"> 02</span>
                </div>
            </div>
            <div class="col-md-2 col-5 card rounded-1 shadow p-2 ">
                <div class="mb-1">
                    <p class="text-black-50"></p>
                </div>
                
                <div class="position-relative pb-2">
                    <p class=" align-middle fw-bolder mt-0 ps-2" style="font-size: 0.6rem">Total number <br>of staff <br><span class="text-danger" style="font-size: 0.9rem !important">On Leave</span></p>
                    <span class=" border-start border-2 align-middle border-secondary top-0 end-0 position-absolute fs-2 fw-bold"> 02</span>
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