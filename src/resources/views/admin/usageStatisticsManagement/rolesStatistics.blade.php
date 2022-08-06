@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class='col'>
                <div class="card" style='max-height: 800px; max-width: 1600px'>
                    <div class="card-header h2 text-center">Overall Number of Bookings by Role

                    </div>
                    <div class="card-body">
                        <div id="container"></div>
                        <a href="{{route('usageStatistics')}}" class="mx-2 btn btn-secondary float-end" role="button">Back</a>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>


<script src="https://code.highcharts.com/highcharts.js"></script>
<script type="text/javascript">
    var roleData = {!! json_encode($roleData) !!};
    var roleCategories = {!! json_encode($roleCategories) !!};
    
    Highcharts.chart('container', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Overall Number of Bookings by Role'
        },
         xAxis: {
            categories: roleCategories
        },
        yAxis: {
            title: {
                text: 'Number of Bookings per Role'
            }
        },
        series: [{
            name: 'Roles',
            data: roleData
        }], 
});
</script>


@endsection