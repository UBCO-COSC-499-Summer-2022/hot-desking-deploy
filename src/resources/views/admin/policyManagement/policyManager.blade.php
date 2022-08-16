@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">

                <div class="card-header text-center h2">
                    {{__('Policy Manager')}}
                </div>

                <div class="card-body">
                    @if(Session::has('message'))
                        <div class="alert {{ Session::get('alert-class', 'alert-info') }} alert-dismissible fade show">{{ Session::get('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <nav id="stats-views">
                        <div class="nav nav-tabs justify-content-center" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-restrictions-tab" data-bs-toggle="tab" data-bs-target="#nav-restrictions" type="button" role="tab" aria-controls="nav-restrictions" aria-selected="true">Room Restrictions Policy</button>
                            <button class="nav-link" id="nav-occupation-tab" data-bs-toggle="tab" data-bs-target="#nav-occupation" type="button" role="tab" aria-controls="nav-occupation" aria-selected="false">Room Occupancy Policy</button>
                            <button class="nav-link" id="nav-booking-tab" data-bs-toggle="tab" data-bs-target="#nav-booking" type="button" role="tab" aria-controls="nav-booking" aria-selected="true">Booking Policy</button>
                        </div>
                    </nav>

                    <div class="tab-content" id="nav-tabContent">

                        <!-- Room Restrictions Policy -->
                        <div class="tab-pane fade show active" id="nav-restrictions" role="tabpanel" aria-labelledby="nav-restrictions-tab">
                            <div class="row alert alert-primary m-2 p-2">
                                <div class="col col-md-1 text-center">
                                    <p class="my-2 bi bi-info-circle-fill fs-3"></p>
                                </div>
                                <div class="col col-md-11">
                                    <p class="my-2">This is where room restrictions policies are displayed. These restrictions are applied to rooms based on the roles assigned to a room. 
                                        <br>If a room does not have any restricts defined then it is available to all users.
                                    </p>
                                </div>
                            </div>
                            <!-- Search Modal -->
                            <form>
                                <!-- Campus Selection -->
                                <div class="row my-3">
                                    <label for="campus" class="col-md-4 col-form-label text-md-end">Campus</label>
                                    <div class="col-md-4">
                                        <select id="campus" class="form-select @error('campus_id')is-invalid @enderror" name="campus_id">
                                            <option value="" disabled hidden selected>Open list of campuses</option>
                                            @foreach ($campuses as $campus)
                                                <option value="{{$campus->id}}">{{$campus->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('campus_id')
                                            <div class='alert alert-danger'>{{$message}}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Building Selection -->
                                <div class="row mb-3">
                                    <label for="building" class="col-md-4 col-form-label text-md-end">Building</label>
                                    <div class="col-md-4">
                                        <select id="building" class="form-select @error('building_id')is-invalid @enderror" name="building_id" disabled>
                                            <option disabled selected hidden>Open list of buildings</option>
                                        </select>
                                        @error('building_id')
                                            <div class='alert alert-danger'>{{$message}}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Floor Selection -->
                                <div class="row mb-3">
                                    <label for="floor" class="col-md-4 col-form-label text-md-end">Floor</label>
                                    <div class="col-md-4">
                                        <select id="floor" class="form-select @error('floor_id')is-invalid @enderror" name="floor_id" disabled>
                                            <option disabled selected hidden>Open list of floors</option>
                                        </select>
                                        @error('floor_id')
                                            <div class='alert alert-danger'>{{$message}}</div>
                                        @enderror
                                    </div>
                                </div>
                            </form>

                            <div id="displayMsg"></div>

                            <table class="table table-light" id="roomsTable">
                                <thead class="table-primary">
                                    <tr>
                                        <th class="text-center">Room</th>
                                        <th class="text-center">Roles That Can Access Room</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="roomsBody">
                                </tbody>
                            </table>

                        </div>
                        <!-- End Room Restrictions Policy -->

                        <!-- Room Occupation Policy -->
                        <div class="tab-pane fade" id="nav-occupation" role="tabpanel" aria-labelledby="nav-occupation-tab">
                            <div class="row alert alert-primary m-2 p-2">
                                <div class="col col-md-1 text-center">
                                    <p class="my-2 bi bi-info-circle-fill fs-3"></p>
                                </div>
                                <div class="col col-md-11">
                                    <p class="my-2">This is where the room occupancy policy is displayed and adjusted. This policy is applied to all room's maximum occupancy in the event that the maximum occupancy needs to be reduced due to external factors such as COVID-19.</p>
                                </div>
                            </div>
                            @if (isset($occupation))
                            <form action="{{ route('editOccupationPolicy', 1) }}" method="POST">
                                @csrf
                                <div class="row m-3">
                                    <label for="percentage" class="col-md-5 col-form-label text-md-end">Policy Occupancy Percentage</label>
                                    <div class="col-md-4">
                                        <input id="percentage" type="number" step="1" min="0" max="100" class="form-control @error('percentage')is-invalid @enderror" name="percentage" value="{{ $occupation->percentage }}"></input>
                                        @error('percentage')
                                            <div class='alert alert-danger'>{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-1 col-form-label text-md-front"><b>%</b></div>
                                </div>

                                <div class="row mb-0">
                                    <div class="col-md-10 offset-md-10">
                                        <!-- Button trigger modal -->
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmOccupationLimitModal">Submit</button>
                                    </div>
                                </div>

                                <!-- Modal -->
                                <div class="modal fade" id="confirmOccupationLimitModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Confirm Occupancy Limit Policy Changes</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                        <div class="modal-body">
                                            Are you sure you would like to update the occupancy limit? These changes will apply all rooms. 
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-success">Submit</button>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            @else
                                <div class="alert alert-danger m-2 text-center">
                                    <p class="align-middle"><i class="bi bi-exclamation-octagon-fill fs-3"></i> ERROR: POLICY OCCUPANCY LIMIT NOT FOUND <i class="bi bi-exclamation-octagon-fill fs-3"></i></p>
                                    <form action="{{ route('restoreOccupationPolicy') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success">Restore</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                        <!-- End Room Occupation Policy -->

                        <!-- Booking Policy  -->
                        <div class="tab-pane fade" id="nav-booking" role="tabpanel" aria-labelledby="nav-booking-tab">
                            <div class="row alert alert-primary m-2 p-2">
                                <div class="col col-md-1 text-center">
                                    <p class="my-2 bi bi-info-circle-fill fs-3"></p>
                                </div>
                                <div class="col col-md-11">
                                    <p class="my-2">This is where Booking Policies are displayed. These booking policies are applied to the user role groups.
                                    <ul>
                                        <li>Booking Window (Days): Number of days in advance a user is allowed to book a desk</li>
                                        <li>Booking Duration (Hours): Maximum number of hours a user can book a desk</li>
                                    </ul>
                                    </p>
                                </div>
                            </div>

                            <table class="table table-light">
                                <thead class="table-primary">
                                    <tr>
                                        <th class="text-center">Role</th>
                                        <th class="text-center">Booking Window (Days)</th>
                                        <th class="text-center">Booking Duration (Hours)</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($roles as $role)
                                    @if($role->role_id != 1)
                                        <tr>
                                            <td class="text-center">{{$role->role}}</td>
                                            <td class="text-center">{{$role->max_booking_window}}</td>
                                            <td class="text-center">{{$role->max_booking_duration}}</td>
                                            <td class="text-center">
                                                <a href="{{route('editRolesBookingPolicy', $role->role_id)}}" class="btn btn-secondary"><i class="bi bi-pencil-square"></i></a>
                                            </td>
                                        </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- End Booking Policy -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ asset('js/alert.js') }}"></script>
<script type="application/javascript">
    var buildings = <?php echo json_encode($buildings)?>;
    var floors = <?php echo json_encode($floors)?>;
    var rooms = <?php echo json_encode($rooms)?>;

    $('#campus').change( function() {
        // Clear the building dropdown 
        $('#building').empty();
        $('#roomsTable').hide();

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
            $('#building').append($('<option disabled selected hidden>Open list of buildings</option>'));
            filteredBuildings.forEach (building => $('#building').append($('<option/>').val(building.id).text(building.name)));
        }
        // disable the floor field
        if (!($('#floor').is(':disabled'))) {
            $('#floor').empty();
            $('#floor').append($('<option disabled selected hidden>Open list of floors</option>'));
            $('#floor').prop('disabled', true);
        }
    });

    $('#building').change( function() {
        // Clear the floor dropdown 
        $('#floor').empty();
        $('#roomsTable').hide();

        var buildingId = parseInt($('#building').find(':selected').attr('value'));
        var filteredFloors = floors.filter(item => {
            return item.building_id === buildingId;
        });

        if (filteredFloors.length < 1) {
            // disable the building select field
            if (!$('#floor').is(':disabled')) {
                $('#floor').prop('disabled', true);
            }
            $('#floor').append($('<option disabled selected hidden>There are no available floors</option>'));
            $('#displayMsg').empty();
            $('#displayMsg').hide();
        } else {
            // enable the floor select field
            if ($('#floor').is(':disabled')) {
                $('#floor').prop('disabled', false);
            }

            // Populate the floor dropdown with all floors that belong to the building
            $('#floor').append($('<option disabled selected hidden>Open list of floors</option>'));
            filteredFloors.forEach (floor => $('#floor').append($('<option/>').val(floor.id).text(floor.floor_num)));
        }
        // enable the floor select field
        if ($('#floor').is(':disabled')) {
            $('#floor').prop('disabled', true);
        }
    });

    $('#floor').change( function() {
        var floorId = parseInt($('#floor').find(':selected').attr('value'));
        var filteredRooms = rooms.filter(item => {
            return item.floor_id === floorId;
        });

        if (filteredRooms.length < 1) {
            $('#displayMsg').empty();
            $('#displayMsg').append($('<div class="alert alert-warning wizard"><i class="bi bi-exclamation-circle-fill"></i> There are no rooms to display.</div>'));
            $('#displayMsg').show();
            $('#roomsTable').hide();
        } else {
            $('#displayMsg').empty();
            $('#displayMsg').hide();
            $('#roomsBody').empty();

            console.log(filteredRooms);
            $.ajax({
                type: "GET",
                url: "/get-filteredRooms",
                data: {data:filteredRooms},
                success: function (data) {
                    console.log(data);
                    $('#roomsBody').append(data);
                }
            });
            $('#roomsTable').show();
        }
    });

    // Resets the search modal dropdown to its original state
    $(window).on('load', function(){
        $('#roomsTable').hide();
        $('#campus').append($('<option disabled selected hidden>Open list of campuses</option>'));
        $('#building').prop( "hidden", false );
        $('#building').prop( "disabled", true );
        $('#building').append($('<option disabled selected hidden>Open list of buildings</option>'));
        $('#floor').prop( "hidden", false );
        $('#floor').prop( "disabled", true );
        $('#floor').append($('<option disabled selected hidden>Open list of floors</option>'));
        $('#room').prop( "hidden", false );
        $('#room').prop( "disabled", true );
        $('#room').append($('<option disabled selected hidden>Open list of rooms</option>'));

        var tab = <?php echo json_encode($tab)?>;
        switch (tab) {
            case 0:
                break;
            case 1:
                var campus_id = <?php echo json_encode($campus_id)?>;
                var building_id = <?php echo json_encode($building_id)?>;
                var floor_id = <?php echo json_encode($floor_id)?>;
                // load campus, building, floor and generate room table
                $("#campus").val(campus_id).change();
                $("#building").val(building_id).change();
                $("#floor").val(floor_id).change();
                break;
            case 2:
                // remove classes for default option
                $('#nav-restrictions-tab').removeClass('active');
                $('#nav-restrictions').removeClass('show');
                $('#nav-restrictions').removeClass('active');
                // add classes
                $('#nav-occupation-tab').addClass('active');
                $('#nav-occupation').addClass('show');
                $('#nav-occupation').addClass('active');
                break;
            case 3:
                // remove classes for default option
                $('#nav-restrictions-tab').removeClass('active');
                $('#nav-restrictions').removeClass('show');
                $('#nav-restrictions').removeClass('active');
                // add classes
                $('#nav-booking-tab').addClass('active');
                $('#nav-booking').addClass('show');
                $('#nav-booking').addClass('active');
                break;
        }
    });
</script>
@endsection