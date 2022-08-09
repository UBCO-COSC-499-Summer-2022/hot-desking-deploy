@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class='col'>
            <div class="card" style='max-height: 800px; max-width: 1600px'>
                <div class="card-header h2 text-center">Role Statistics
                </div>
                <div class="card-body">
                    <div class='row'>
                        <!-- Search Modal -->
                        <form>
                                <!-- Campus Selection -->
                                <div class="row my-3">
                                    <div class="col-md-2">
                                        <select id="campus" class="form-select @error('campus_id')is-invalid @enderror" name="campus_id">
                                            <option value="" disabled hidden selected>Choose Campus</option>
                                            @foreach ($campuses as $campus)
                                                <option value="{{$campus->id}}">{{$campus->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('campus_id')
                                            <div class='alert alert-danger'>{{$message}}</div>
                                        @enderror
                                    </div>

                                <!-- Building Selection -->
                                    <div class="col-md-2">
                                        <select id="building" class="form-select @error('building_id')is-invalid @enderror" name="building_id" disabled>
                                            <option disabled selected hidden>Choose Building</option>
                                        </select>
                                        @error('building_id')
                                            <div class='alert alert-danger'>{{$message}}</div>
                                        @enderror
                                    </div>

                                <!-- Floor Selection -->
                                    <div class="col-md-2">
                                        <select id="floor" class="form-select @error('floor_id')is-invalid @enderror" name="floor_id" disabled>
                                            <option disabled selected hidden>Choose Floor</option>
                                        </select>
                                        @error('floor_id')
                                            <div class='alert alert-danger'>{{$message}}</div>
                                        @enderror
                                    </div>

                                 <!-- Room Selection -->
                                    <div class="col-md-2">
                                        <select id="room" class="form-select @error('room_id')is-invalid @enderror" name="room_id" disabled>
                                            <option disabled selected hidden>Choose Room</option>
                                        </select>
                                        @error('room_id')
                                            <div class='alert alert-danger'>{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class='col-md-2'>
                                        <input type="text" name="daterange" id='datefilter' value="" style="height:37px" placeholder='Enter Date' size="23" />
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" onclick="onSubmit()" class="btn btn-primary">Filter</button>
                                    </div>
                                </div>
                            </form>
                    </div>
                    <div id="container"></div>
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
<script type="application/javascript">
    var roleData = {!!json_encode($roleData) !!};
    var roleCategories = {!!json_encode($roleCategories) !!};
    var buildings = <?php echo json_encode($buildings)?>;
    var floors = <?php echo json_encode($floors)?>;
    var rooms = <?php echo json_encode($rooms)?>;

    $('#campus').change( function() {
        // Clear the building dropdown 
        $('#building').empty();

        var campusId = parseInt($('#campus').find(':selected').attr('value'));
        var filteredBuildings = buildings.filter(item => {
            return item.campus_id === campusId;
        });

        if (filteredBuildings.length < 1) {
            // enable the building select field
            if (!$('#building').is(':disabled')) {
                $('#building').prop('disabled', true);
            }
            $('#building').append($('<option disabled selected hidden>There are no available buildings</option>'));
            $('#displayMsg').empty();
            $('#displayMsg').hide();
        } else {
            // disable the building select field
            if ($('#building').is(':disabled')) {
                $('#building').prop('disabled', false);
            }
        
            // Populate the building dropdown with all buildings that belong to the campus
            $('#building').append($('<option disabled selected hidden>Choose Building</option>'));
            filteredBuildings.forEach (building => $('#building').append($('<option/>').val(building.id).text(building.name)));
        }
        // disable the floor field
        if (!($('#floor').is(':disabled'))) {
            $('#floor').empty();
            $('#floor').append($('<option disabled selected hidden>Choose Floor</option>'));
            $('#floor').prop('disabled', true);
        }
        if (!($('#room').is(':disabled'))) {
            $('#room').empty();
            $('#room').append($('<option disabled selected hidden>Choose Room</option>'));
            $('#room').prop('disabled', true);
        }

    });

    $('#building').change( function() {
        // Clear the floor dropdown 
        $('#floor').empty();
        var buildingId = parseInt($('#building').find(':selected').attr('value'));
        var filteredFloors = floors.filter(item => {
            return item.building_id === buildingId;
        });

        if (filteredFloors.length < 1) {
            // disable the floor select field
            if (!$('#floor').is(':disabled')) {
                $('#floor').prop('disabled', true);
            }
            $('#floor').append($('<option disabled selected hidden>There are no available floors</option>'));
            $('#room').append($('<option disabled selected hidden>There are no available rooms</option>'));
            $('#displayMsg').empty();
            $('#displayMsg').hide();
        } else {
            // enable the floor select field
            if ($('#floor').is(':disabled')) {
                $('#floor').prop('disabled', false);
            }

            // Populate the floor dropdown with all floors that belong to the building
            $('#floor').append($('<option disabled selected hidden>Choose Floor</option>'));
            filteredFloors.forEach (floor => $('#floor').append($('<option/>').val(floor.id).text(floor.floor_num)));
        }
        // enable the floor select field
        if ($('#floor').is(':disabled')) {
            $('#floor').prop('disabled', true);
        }
        if (!($('#room').is(':disabled'))) {
            $('#room').empty();
            $('#room').append($('<option disabled selected hidden>Choose Room</option>'));
            $('#room').prop('disabled', true);
        }
    });

    $('#floor').change( function() {
        // Clear the floor dropdown 
        $('#room').empty();

        var floorId = parseInt($('#floor').find(':selected').attr('value'));
        var filteredRooms = rooms.filter(item => {
            return item.floor_id === floorId;
        });

        if (filteredRooms.length < 1) {
            // disable the room select field
            if (!$('#room').is(':disabled')) {
                $('#room').prop('disabled', true);
            }
            $('#room').append($('<option disabled selected hidden>There are no available rooms</option>'));
            $('#displayMsg').empty();
            $('#displayMsg').hide();
        } else {
            // enable the room select field
            if ($('#room').is(':disabled')) {
                $('#room').prop('disabled', false);
            }

            // Populate the room dropdown with all rooms that belong to the building
            $('#room').append($('<option disabled selected hidden>Choose Room</option>'));
            filteredRooms.forEach (room => $('#room').append($('<option/>').val(room.id).text(room.name)));
        }
        // enable the room select field
        if ($('#room').is(':disabled')) {
            $('#room').prop('disabled', true);
        }
    });


    // Resets the search modal dropdown to its original state
    $(window).on('load', function(){
        $('#campus').append($('<option disabled selected hidden>Choose Campus</option>'));
        $('#building').prop( "hidden", false );
        $('#building').prop( "disabled", true );
        $('#building').append($('<option disabled selected hidden>Choose Building</option>'));
        $('#floor').prop( "hidden", false );
        $('#floor').prop( "disabled", true );
        $('#floor').append($('<option disabled selected hidden>Choose Floor</option>'));
        $('#room').prop( "hidden", false );
        $('#room').prop( "disabled", true );
        $('#room').append($('<option disabled selected hidden>Choose Room</option>'));
    });


    $(function() {

        $('input[id="datefilter"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });

        $('input[id="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
            $.ajax({
                type: 'GET',
                url: '/getFilterRoles',
                data: {
                    dateRange: $('#dateFilter').val()
                },
                success: function(data) {
                    Highcharts.chart('container', {
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: ''
                        },
                        xAxis: {
                            title: {
                                text: 'User Roles'
                            },
                            categories: roleCategories
                        },
                        yAxis: {
                            title: {
                                text: 'Number of Bookings'
                            }
                        },
                        series: [{
                            showInLegend: false,
                            name: 'No. of Bookings',
                            data: roleData
                        }],
                    });
                }
            });
        });

        $('input[id="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });

    });

    function onSubmit() {
        
      
        var roomId = $('#room').val();
        var dateRange = $('#datefilter').val();

        $.ajax({
            type: 'GET',
            url: '/getFilterRoles',
            data: {dateRange: dateRange, roomId: roomId},
            success: function(data){
                console.log(data[0], data[1]);
                Highcharts.chart('container', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: ''
                },
                xAxis: {
                    title: {
                        text: 'User Roles'
                    },
                    categories: data[1]
                },
                yAxis: {
                    title: {
                        text: 'Number of Bookings'
                    }
                },
                series: [{
                    showInLegend: false,             
                    name: 'No. of Bookings',
                    data: data[0]
                }], 
                });
            }
        });

    };
</script>


@endsection