@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class='col'>
                <div class="card" style='max-height: 1000px; max-width: 1600px'>
                    <div class="card-header h2 text-center">Resource Statistics

                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="daterange" id='dateFilter' style="height:37px" placeholder='Enter date' size="23">
                            </div>
                        </div>
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
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
            text: 'Desk Resources'
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
            name: 'Percentage',
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
            text: 'Room Resources'
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
            name: 'Percentage',
            colorByPoint: true,
            data: dataRooms
        }]

});

$(function() {

$('input[id="dateFilter"]').daterangepicker({
    autoUpdateInput: false,
    locale: {
        cancelLabel: 'Clear'
    }
});

$('input[id="dateFilter"]').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
    $.ajax({
        type: 'GET',
        url: '/getFilterResources',
        data: {dateRange: $('#dateFilter').val()},
        success: function(data){
            console.log(data[0], data[1]);
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
                        text: 'Desk Resources'
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
                        name: 'Percentage',
                        colorByPoint: true,
                        data: data[0]
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
                        text: 'Room Resources'
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
                        name: 'Percentage',
                        colorByPoint: true,
                        data: data[1]
                    }]
                
            });
        }
    });
});


$('input[id="dateFilter"]').on('cancel.daterangepicker', function(ev, picker) {
    $(this).val('');
});

});



</script>


@endsection
