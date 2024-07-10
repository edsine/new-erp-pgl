@extends('layouts.admin')
@section('page-title')
    {{__('Manage Productivity')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Staff Productiviy')}}</li>
@endsection

@push('script-page')

    <script>
        (function () {
            var options = {
                chart: {
                    height: 350,
                    type: 'bar',
                    toolbar: {
                        show: false,
                    },
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val) {
                        return val + "%";
                    },
                    style: {
                        fontSize: '12px',
                        colors: ["#304758"]
                    }
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                series: [{
                    name: "Employee Productivity",
                    data: [60, 70, 57, 71, 75, 72, 88, 83, 78, 74, 79, 60]
                }],
                xaxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    title: {
                        text: 'Months'
                    }
                },
                yaxis: {
                    title: {
                        text: 'Productivity (%)'
                    },
                    min: 0,
                    max: 100
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val + "%";
                        }
                    }
                },
                colors: ['#3ec9d6'],
                title: {
                    text: 'Monthly Staff Productivity',
                    align: 'center',
                    margin: 20,
                    offsetY: 20,
                    style: {
                        fontSize: '18px'
                    }
                }
            };
            
            var chart = new ApexCharts(document.querySelector("#incExpBarChart"), options);
            chart.render();
        })();
    </script>


@endpush

@section('content')




        <div>
            <div class="col-xxl-12">
                <div class="card">
                    <div class="card-header">
                        <h5>{{__('Staff Productivity')}}
                            <span class="float-end text-muted">{{__('Current Year 2024')}}</span>
                        </h5>

                    </div>
                    <div class="card-body">
                        <div id="incExpBarChart"></div>
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

