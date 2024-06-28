@extends('layouts.admin')

@section('page-title')
    {{__('Requisition Manager')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Chairman Approval')}}</li>
@endsection

@section('content')
    <div class="row">
        
        <div class="col-md-12">
            <div class="card">
                @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
            <div class="card-body table-border-style">
                    <div class="table-responsive">
                    <table class="table datatable">
                            <thead>
                            <tr>
                                <th>{{__('Pending Chairman Approvals')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody class="font-style">
                           @foreach($requisitions as $item)
                           <tr>
                            <td>{{substr($item->title,0, 30)}}</td>
                            <td>  
                                
                                <div class="action-btn bg-warning ms-2">
                                    <a href="#" 
   data-url="{{ URL::to('requisition/' . $item->id . '/action1') }}"
   data-size="lg" 
   data-ajax-popup="true"
   data-title="{{ __('Requisition Action') }}"
   data-bs-toggle="tooltip"
   title="{{ __('Requisition Action') }}"
   data-original-title="{{ __('Requisition Action') }}"
   class="btn btn-success float-end px-3 view-details-btn">View Details</a>

                                </div>
                            </td>
                           </tr>
                           @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="requisitionDetails" tabindex="-1" role="dialog" aria-labelledby="requisitionDetailsLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg my-5" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="requisitionDetailsLabel">Requisition Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Content will be dynamically loaded here -->
                </div>
            </div>
        </div>
    </div>
   
@push('script-page')
<script>
 jQuery.noConflict();
jQuery(document).ready(function($) {
    $('.view-details-btn').on('click', function(e) {
        e.preventDefault();
        var url = $(this).data('url');
        jQuery.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                // Assuming you have a modal with id="requisitionDetails"
                jQuery('#requisitionDetails .modal-body').html(response);
                jQuery('#requisitionDetails').modal('show'); // Show the modal after content is loaded
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert('Failed to fetch requisition details. Please try again.');
            }
        });
    });
});


</script>
<script>
    document.getElementById('client_id').onchange = function() {
        var clientId = $(this).val();
        console.log(clientId);
        $.ajax({
            url: '{{ route('customer.projects') }}',
            type: 'POST',
            data: {
                "client_id": clientId,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                console.log(data);
                $('#project_id').empty();
                $.each(data, function(key, value) {
                    $('#project_id').append('<option value="' + key + '">' + value +
                        '</option>');
                });
            }
        });
    }

    document.getElementById('expense_type').onchange = function() {
        const value = this.value;

        if (value == 1) {
            document.getElementById('client_dropdown').classList.remove('d-none');
            document.getElementById('project_dropdown').classList.remove('d-none');
            document.getElementById('department_dropdown').classList.add('d-none');
        } else if (value == 2) {
            document.getElementById('client_dropdown').classList.add('d-none');
            document.getElementById('project_dropdown').classList.add('d-none');
            document.getElementById('department_dropdown').classList.remove('d-none');
        } else {
            document.getElementById('client_dropdown').classList.add('d-none');
            document.getElementById('project_dropdown').classList.add('d-none');
            document.getElementById('department_dropdown').classList.add('d-none');
        }
    }
</script>
@endpush
@endsection