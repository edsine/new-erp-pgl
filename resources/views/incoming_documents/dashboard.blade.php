@extends('layouts.app')
@section('content')
    <div class="card mx-4">

        <div class="card-body">



            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
            <script type="text/javascript">
                google.charts.load('current', {
                    'packages': ['corechart']
                });
                google.charts.setOnLoadCallback(drawChart);

                function drawChart() {

                    var data = google.visualization.arrayToDataTable([
                        ['Category', 'Numbers'],

                        <?php
                        echo $alldata;
                        ?>
                    ]);

                    var options = {
                        title: 'Document By File No.',
                        is3D: true
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('piechart'));

                    chart.draw(data, options);
                }
            </script>

            <div id="piechart" style="width: 900px; height: 500px;"></div>


        </div>
    </div>
@endsection
