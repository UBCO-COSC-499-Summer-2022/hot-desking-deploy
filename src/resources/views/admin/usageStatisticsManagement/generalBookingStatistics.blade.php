@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class='col'>
            <div class="card" style='max-height: 1000px; max-width: 1600px'>

                <div class="card-header h2 text-center">Overall Number of Bookings
                </div>
                <div class="card-body">
                    <div class='row'>
                        <div class='col-md-9'>
                        </div>
                        <div class='col-md-3'>
                            <select class="year float-end form-select" id="year">
                                @foreach ($booking_years as $booking_year)
                                <option value="{{$booking_year}}">{{$booking_year}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id="container"></div>
                    <div id="container2"></div>
                    <a href="{{route('usageStatistics')}}" class="mx-2 btn btn-secondary float-end" role="button">Back</a>
                </div>

            </div>
        </div>
    </div>
</div>



<script src="https://code.highcharts.com/highcharts.js"></script>
<script type="text/javascript">
    var booking_count = {!!json_encode($booking_count) !!};
    console.log(booking_count);

    Highcharts.chart('container', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Overall Number of Bookings'
        },
        xAxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
        },
        yAxis: {
            title: {
                text: 'Number of Bookings per Month'
            }
        },
        series: [{
            name: 'Users',
            data: booking_count
        }],
    });

    $('#year').change(function() {
        year = $('#year').val();
        $.ajax({
            type: 'GET',
            url: `/getYear/${year}`,
            data: year,
            success: function(data) {
                Highcharts.chart('container', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'Overall Number of Bookings'
                    },
                    xAxis: {
                        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                    },
                    yAxis: {
                        title: {
                            text: 'Number of Bookings per Month'
                        }
                    },
                    series: [{
                        name: 'Users',
                        data: data
                    }],
                });
            }
        });
    });
</script>

@endsection