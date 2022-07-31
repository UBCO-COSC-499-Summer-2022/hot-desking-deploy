@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class='col'>
            <div class="card" style='max-height: 800px; max-width: 1600px'>
                <div class="card-header h2 text-center">Number of Bookings per Room by User Faculty
                </div>
                <div class="card-body">
                    <div class='row'>
                        <div class="col-md-8">
                        </div>
                        <div class='col-md-2'>
                            <select class="room float-end form-select" id="room">
                                <option value=''>Campus</option>
                                <option value="Okanagan">Okanagan</option>
                                <option value="Vancouver">Vancouver</option>
                            </select>
                        </div>
                        <div class='col-md-2'>
                            <select class="room float-end form-select" id="room">
                                <option value=''>Building</option>
                                <option value="Fipke">Fipke</option>
                                <option value="Science">Science</option>
                                <option value="Arts">Arts</option>
                            </select>
                        </div>
                    </div>
                    <div id="container"></div>
                    <a href="{{route('usageStatistics')}}" class="mx-2 btn btn-secondary float-end" role="button">Back</a>
                </div>
            </div>
        </div>

    </div>
</div>


<script src="https://code.highcharts.com/highcharts.js"></script>
<script type="text/javascript">
    var facultyData = {!!json_encode($facultyData) !!};
    var facultyCategories = {!!json_encode($facultyCategories) !!};

    Highcharts.chart('container', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Number of Bookings per Room by User Faculty'
        },
        xAxis: {
            categories: facultyCategories
        },
        yAxis: {
            title: {
                text: 'Number of Bookings per Room by User Faculty'
            }
        },
        series: [{
            name: 'Faculty',
            data: facultyData
        }],
    });
</script>

@endsection