@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">{{ __('Search') }}</div>
                <div class="card-body">
                    <form method="GET" action="{{ route('calendar') }}">
                        @csrf

                        <!-- Campus Selection -->
                        <div class="row mb-3">
                            <label for="campus" class="col-md-4 col-form-label text-md-end">Campus</label>
                            <div class="col-md-6">
                                <select id="campus" class="form-select @error('campus_id')is-invalid @enderror" name="campus_id" required>
                                    <option disabled hidden selected></option>
                                    @foreach ($campuses as $campus)
                                        <option value="{{$campus->id}}">{{$campus->name}} Campus</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('campus_id')
                                <div class='alert alert-danger'>{{$message}}</div>
                            @enderror
                        </div>

                        <!-- Building Selection -->
                        <div class="row mb-3">
                            <label for="building" class="col-md-4 col-form-label text-md-end">Building</label>
                            <div class="col-md-6">
                                <select id="building" class="form-select @error('building_id')is-invalid @enderror" name="building_id" disabled required>
                                    <option disabled selected hidden></option>
                                </select>
                                @error('building_id')
                                    <div class='alert alert-danger'>{{$message}}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Floor Selection -->
                        <div class="row mb-3">
                            <label for="floor" class="col-md-4 col-form-label text-md-end">Floor</label>
                            <div class="col-md-6">
                                <select id="floor" class="form-select @error('floor_id')is-invalid @enderror" name="floor_id" disabled required>
                                    <option disabled selected hidden></option>
                                </select>
                                @error('floor_id')
                                    <div class='alert alert-danger'>{{$message}}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Room Selection -->
                        <div class="row mb-3">
                            <label for="room" class="col-md-4 col-form-label text-md-end">Room</label>
                            <div class="col-md-6">
                                <select id="room" class="form-select @error('room_id')is-invalid @enderror" name="room_id" disabled required>
                                    <option disabled selected hidden></option>
                                </select>
                                @error('room_id')
                                    <div class='alert alert-danger'>{{$message}}</div>
                                @enderror
                            </div>
                        </div>

                        <div id="displayMsg"></div>

                        <!-- Submit Button -->
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary float-end">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="application/javascript">
    var buildings = <?php echo json_encode($buildings)?>;
    var floors = <?php echo json_encode($floors)?>;
    var rooms = <?php echo json_encode($rooms)?>;

    $('#campus').change( function() {
        // Clear the building dropdown 
        $('#building').empty();

        var campusId = parseInt($('#campus').find(':selected').attr('value'));
        var filteredBuildings = buildings.filter(item => {
                if (!item.is_closed)
                    return item.campus_id === campusId;
                });

        if (filteredBuildings.length < 1) {
            // disable the building select field
            $('#building').prop('disabled', true);
            $('#building').append($('<option disabled selected hidden></option>'));
            $('#displayMsg').empty();
            $('#displayMsg').hide();
        } else {
            // enable the building select field
            if ($('#building').is(':disabled')) {
                $('#building').prop('disabled', false);
            }

            // Populate the building dropdown with all buildings that belong to the campus
            $('#building').append($('<option disabled selected hidden></option>'));
            filteredBuildings.forEach (building => $('#building').append($('<option/>').val(building.id).text(building.name)));
        }
    
        // disable the floor field
        $('#floor').empty();
        $('#floor').append($('<option disabled selected hidden></option>'));
        $('#floor').prop('disabled', true);

        // disable the room field
        $('#room').empty();
        $('#room').append($('<option disabled selected hidden></option>'));
        $('#room').prop('disabled', true);      
    });

    $('#building').change( function() {
        // Clear the floor dropdown 
        $('#floor').empty();

        var buildingId = parseInt($('#building').find(':selected').attr('value'));
        var filteredFloors = floors.filter(item => {
                if (!item.is_closed)
                    return item.building_id === buildingId;
            });

        if (filteredFloors.length < 1) {
            // enable the building select field
            $('#floor').prop('disabled', true);
            $('#floor').append($('<option disabled selected hidden></option>'));
            $('#displayMsg').empty();
            $('#displayMsg').hide();
        } else {
            // enable the floor select field
            if ($('#floor').is(':disabled')) {
                $('#floor').prop('disabled', false);
            }

            // Populate the floor dropdown with all floors that belong to the building
            $('#floor').append($('<option disabled selected hidden></option>'));
            filteredFloors.forEach (floor => $('#floor').append($('<option/>').val(floor.id).text(floor.floor_num)));
        }

        // disable the room field
        $('#room').empty();
        $('#room').append($('<option disabled selected hidden></option>'));
        $('#room').prop('disabled', true);
    });

    $('#floor').change( function() {
        // Clear the room dropdown 
        $('#room').empty();

        var floorId = parseInt($('#floor').find(':selected').attr('value'));
        var filteredRooms = rooms.filter(item => {
                if (!item.is_closed) {
                    return item.floor_id === floorId;
                }
            });
        
        // Check if filteredRooms.length > 1
        // If true, we have an option that pops up and informs the user that all rooms are closed. 
        if (filteredRooms.length < 1) {
            // disable the room select field
            $('#room').prop('disabled', true);
            $('#room').append($('<option disabled selected hidden></option>'));
            $('#displayMsg').empty();
            $('#displayMsg').hide();
        } else {
            // Populate the room dropdown with all rooms that belong to the floor
            $('#room').append($('<option disabled selected hidden></option>'));
            filteredRooms.forEach (room => $('#room').append($('<option/>').val(room.id).text(room.name)));

            // enable the floor select field
            $('#room').prop('disabled', false);
        }
    });

    // Resets the search modal dropdown to its original state
    $(window).on('load', function(){
        $('#campus').append($('<option disabled selected hidden></option>'));
        $('#building').prop( "hidden", false );
        $('#building').prop( "disabled", true );
        $('#building').append($('<option disabled selected hidden></option>'));
        $('#floor').prop( "hidden", false );
        $('#floor').prop( "disabled", true );
        $('#floor').append($('<option disabled selected hidden></option>'));
        $('#room').prop( "hidden", false );
        $('#room').prop( "disabled", true );
        $('#room').append($('<option disabled selected hidden></option>'));
    });
</script>

@endsection