@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class='col'>
                <div class="card" style='max-height: 1000px; max-width: 1600px'>
                    <div class="card-header h2 text-center">Resource Statistics

                    </div>
                    <div class="card-body">
                        <div class='row'>
                            <div class='col-md-6'>
                            <div id="container"></div>
                            </div>
                            <div class='col-md-6'>
                            <div id="container2"></div>
                            </div>
                        </div>
                        <a href="{{route('usageStatistics')}}" class="mx-2 btn btn-secondary float-end" role="button">Back</a>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>



<script src="https://code.highcharts.com/highcharts.js"></script>
<script type="text/javascript">
    var dataDesks = {!! json_encode($dataDesks) !!};
    var dataRooms = {!! json_encode($dataRooms) !!};



Highcharts.chart('container', {
        chart: {
            type: 'pie'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                }
            }
        },
        title: {
            text: 'Desk Resources by Popularity'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        series: [{
            name: 'Popularity',
            colorByPoint: true,
            data: dataDesks
        }]

});

Highcharts.chart('container2', {
        chart: {
            type: 'pie'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                }
            }
        },
        title: {
            text: 'Room Resources by Popularity'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        series: [{
            name: 'Popularity',
            colorByPoint: true,
            data: dataRooms
        }]

});
</script>


@endsection
