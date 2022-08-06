@extends('layouts.app')

@section('content')
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@1.10.4/dist/scheduler.min.css' rel='stylesheet' />
    <style>
#bookings_event_{
    max-width: 900px;
    margin: 40px auto;
}
#your_booking {  
color: white;
background-color: #3CB371;
width: 100px;
display: inline-block;
margin-right: 5px;
margin-bottom: 5px;
}
#other_booking {
color: white;
background-color: #3a87ad;
width: 100px;
display: inline-block;
margin-bottom: 5px;
}


</style>
</head>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    {{ __('Calendar View') }}
                </div> 
                <div class="card-body">
                    <!-- Search Modal -->
                    <form action="{{ route('calendar') }}" class="row g-2" >
                        @csrf

                        <!-- Campus Selection -->
                        <div class="col-2">
                            <label for="campus" class="label label-default">Campus</label>
                            <select id="campus" class="form-select @error('campus_id')is-invalid @enderror" name="campus_id">
                                <option disabled hidden selected></option>
                                @foreach ($campuses as $campus)
                                    <option value="{{$campus->id}}">{{$campus->name}} Campus</option>
                                @endforeach
                            </select>
                            @error('campus_id')
                                <div class='alert alert-danger'>{{$message}}</div>
                            @enderror
                        </div>

                        <!-- Building Selection -->
                        <div class="col-2">
                            <label for="building" class="label label-default">Building</label>
                            <select id="building" class="form-select @error('building_id')is-invalid @enderror" name="building_id" disabled>
                                <option disabled selected hidden></option>
                            </select>
                            @error('building_id')
                                <div class='alert alert-danger'>{{$message}}</div>
                            @enderror
                        </div>

                        <!-- Floor Selection -->
                        <div class="col-2 ">
                            <label for="floor" class="label label-default">Floor</label>
                            <select id="floor" class="form-select @error('floor_id')is-invalid @enderror" name="floor_id" disabled>
                                <option disabled selected hidden></option>
                            </select>
                            @error('floor_id')
                                <div class='alert alert-danger'>{{$message}}</div>
                            @enderror
                        </div>

                        <!-- Room Selection -->
                        <div class="col-2 ">
                            <label for="room" class="label label-default">Room</label>
                            <select id="room" class="form-select @error('room_id')is-invalid @enderror" name="room_id" disabled>
                                <option disabled selected hidden></option>
                            </select>
                            @error('room_id')
                                <div class='alert alert-danger'>{{$message}}</div>
                            @enderror
                        </div>
                        <div class="col-2 row ">
                            <label style="color:white;">Search</label>
                            <!-- <button type="submit" id="b1" class="btn btn-primary bt-sm float-end offset-md-1 p-2">Search</button> -->
                            <button type="submit" id="b1" class="btn btn-primary bt-sm float-end offset-md-1 p-2">Search</button>
                            
                        </div> 
                    </form>
                    <hr/>
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                        Note: You cannot create bookings for past dates and times. 
                    </div>
                        <div class="row">
                            <div class="col-auto">
                                <p>Your user role is  <span class="badge bg-info  text-dark">{{ strtolower($role_info->role)}}</span>. You have a maximum booking window of <span class="badge bg-info  text-dark">{{$role_info->max_booking_window }}</span> days 
                                and maximum booking duration of <span class="badge bg-info  text-dark">{{($role_info->max_booking_duration)*60}}</span> minutes.</p>
                            </div>
                        </div>
                        <div class="row ">
                            <!-- <div class="col-auto">
                                <p>Your maximum booking window is <span class="badge bg-info  text-dark">{{$role_info->max_booking_window }}</span> days.</p>
                            </div>
                            <div class="col-auto">
                                <p>Your maximum booking duration is <span class="badge bg-info  text-dark">{{($role_info->max_booking_duration)*60}}</span> mins.</p>
                            </div>   -->
                            <div class="col-auto"> 
                                <p>Monthly bookings used: <span class="badge bg-info  text-dark">{{ $your_booking_count}}  / {{($role_info->num_monthly_bookings)}}</span>.</p> 
                            </div>
                        </div>
                    <hr>
                    <div class="container mt-6" style="max-width: 700px">
                        <div id='bookings_event'></div>
                        <div class="container">
                            <div class="row justify-content-start">
                                <div class="col-5">
                                    <b>Room Resources</b>
                                    @foreach($resources_array as $resource)
                                    <div class="row justify-content-start" data-bs-toggle="tooltip" data-bs-placement="top"  title="{{$resource->pivot->description}}">
                                        <div class="col col-sm-5 " >
                                            {{ $resource->resource_type }}
                                        </div>
                                        <div class="col col-sm-1"  style="color:{{$resource->colour}}" >
                                            {!! $resource->icon !!}
                                        </div>
                                    </div>
                                        @endforeach
                                </div>
                                <div class="col-5">
                                    <b>Color Legend</b>
                                    <div class="row justify-content-start">
                                        <div  id ="your_booking"> Your Bookings </div>
                                        <div  id ="other_booking">Other Bookings </div>  
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- <div class="row justify-content-start">
                        <div class="col col-sm-4">
                        Room_Resources
                        </div>
                        @foreach($resources_array as $resource)
                            <div class="row justify-content-start" data-bs-toggle="tooltip" data-bs-placement="top" title="{{$resource->pivot->description}}">
                                <div class="col col-sm-3">
                                    {{ $resource->resource_type }}
                                </div>
                                <div class="col col-sm-1"  style="color:{{$resource->colour}}" >
                                {!! $resource->icon !!}
                                </div>
                            </div>
                        @endforeach
                    </div> -->



<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src=" https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@1.10.4/dist/scheduler.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
<script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
        })
</script>
<script>
    $(document).ready(function () {

        // Encode the current room the user searched for into JSON
        var currentRoom = <?php echo json_encode($currentRoom)?>;
        var display= <?php echo json_encode($allBooking)?>;   // this one will get all the booking from the booking table
        var user_id= <?php echo json_encode($user->id)?>;
        var role_info= <?php echo json_encode($role_info)?>;
        var currentRoom_id = <?php echo json_encode($currentRoom->id)?>;
        var max_booking_window=<?php echo json_encode($role_info->max_booking_window)?>;     // this one will use in  validRange function // it from today date to future date
        var max_booking_duration=<?php echo json_encode($role_info->max_booking_duration)?> *60;  //how long can the user make the booking for a day
    
        var booking_count=<?php echo json_encode(  $your_booking_count)?>; // to do: add the if statment restrict the total booking per month
        var num_monthly_bookings=<?php echo json_encode($role_info->num_monthly_bookings)?>;  

        var site_url = "{{ url('/') }}";

        // Grab all desks, then filter them down to the ones that belong to the current room
        // We won't display closed desks to users
        var desks = <?php echo json_encode($desks)?>;

        var filteredDesks = desks.filter(item => {
            if(item.is_closed)
            return item.room_id === currentRoom.id;
        });

        var desksResources = <?php echo json_encode($desks_resources)?>;
        // console.log(desksResources);
        var resourceIconHtml = [];
        for (i = 0; i < desksResources.length; i++) {
            html_output = '';
            desksResources[i].forEach(desk => {
                html_output += '<a style=\"color:" ' + desk.colour+ '"\">'+ desk.icon  +'</a>';
            });
            resourceIconHtml[i] = html_output;
        }

        // Push all desks from filteredDesks into the resourceHolder array   var resourceHolder=  [{ id: '1', title: 'Desk 1' ,resourICON: <i class="bi bi-outlet">},
        var resourceHolder = [];
        for (let i = 0; i < filteredDesks.length; i++) {                             
            resourceHolder.push({ id: String(filteredDesks[i].id) , title: String( "Desk " +filteredDesks[i].id), resourceIcon: resourceIconHtml[i]});
        }

        var resourceContent=  [];  // how many resources you have, or how many desks
        var distinctDeskNumber=filteredDesks.length;//the number of desk inside the room 

        for (let i = 0; i < distinctDeskNumber; i++) {
                resourceContent.push(resourceHolder[i]);
        }
        // [{id: 210, user_id: 4, desk_id: 4, start: '2022-07-13 15:30:00', end: '2022-07-13 16:00:00', resourceId: '2'}
        // assign the each event to have a resourceId base on the desk id
        display.forEach(object => {
            filteredDesks.forEach(desk => {  // display contain all the fliter booking data from booking table
            // console.log("object");
            // console.log(object);
            // console.log("desk");
                if(object.desk_id == desk.id){
                    object.resourceId = desk.id;
                } 
            }) ;
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //full calender js code 
        var calendar = $('#bookings_event').fullCalendar({
        schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
        //the header of the full calender
        header:{
            left:'title',
            center:'prev,today,next',
            right:'agendaDay, timelineWeek',  // timeline week place the resource under week view
            // right:'agendaDay, agendaWeek'
        },
        footer:true,
        defaultView: 'agendaDay',
        resourceLabelText: 'Rooms',
        timeZone: ('America/Los_Angeles'),
        showNonCurrentDates: false,
        timeFormat: 'H(:mm)', // 24 hours period 
        slotDuration: '00:15:00', //  15 minutes
        // minTime: "08:00:00",
        // maxTime: "23:00:00",
        displayEventTime: true,
        allDaySlot:false,
        editable: true, // user can drag the event 
        selectable: true, // the event could be select
        selectHelper: true,
        // eventLimit: true,// might be use for for day will 
        eventOverlap:false,

        //user cannot select the across the column
        // selectConstraint:{ 
        //     start: '08:00', 
        //     end: '23:00', 
        // },

        //selectable window 
        validRange: function(nowDate) {
            var endHolder= String( (nowDate.clone().add(max_booking_window, 'days'))._d );
            var result = moment().month(endHolder.substring(4,7)).format("M");
            if(result.length==1){
                result="0"+result
            }
            endString=(endHolder.substring(11,15)+"-"+result+'-'+ endHolder.substring(8,11)).trim();

            return { 
            start: nowDate,
            end:   endString
            };
        },
        //end of selectable window 

        //start of render function
        eventRender: function (event, element, view) {
            if (event.allDay === 'true') {
                event.allDay = true;
            } else {
                event.allDay = false;
            }
        },
        //end of event render function
        events:display,  // list of the data will be display on the calender from the controller
        // code for the resource

        resources:resourceContent, // full calender use this one to recognize the resources column
        // example
        //resources:[{ id: 2, title: 'Desk 1',icon: '<i class="bi bi-outlet">' }],
        // events:[{id: 210, user_id: 4, desk_id: 4, start: '2022-07-13 15:30:00', end: '2022-07-13 16:00:00', resourceId: 2]

        // this function create the hover effect of the column header
        resourceRender: function(resourceObj, $th) {
            if(resourceObj.resourceIcon.length==0){
                resourceObj.resourceIcon="undefine";
            }
            $th.append(
                $($th).popover({
                    title: "Desk Resources",
                    content: resourceObj.resourceIcon,
                    trigger: 'hover',
                    placement: 'top',
                    container: 'body',
                    html : true
                })  
            )
        },
        // '<a style=\"color:" ' + desk.colour+ '"\">'+ desk.icon  +'</a>';

        //start of select function
        select: function (book_time_start, book_time_end,  jsEvent, view, resource) {
        // console.log( 'select',  resource ? resource.id : '(no resource)' );
            var desks_id= Number(resource.id);  // this code making the user could select the column to pass the desk id
            if( booking_count<num_monthly_bookings){
                if (desks_id){
                    var book_time_start = $.fullCalendar.formatDate(book_time_start, "Y-MM-DD HH:mm");  // generate the date time and store into variable
                    var book_time_end = $.fullCalendar.formatDate(book_time_end, "Y-MM-DD HH:mm");
                    var  hours_difference= Number(book_time_end.slice(-5,-3))-Number(book_time_start.slice(-5,-3));
                    var  minutes_difference= Number(Number(book_time_end.slice(-2))-Number(book_time_start.slice(-2)));
                    var  sum_difference=  hours_difference*60+ minutes_difference;

                    if (sum_difference<=max_booking_duration) {
                        $.ajax({
                            url:  site_url+"/bookings-ajax",   
                            data: { // a list of data will be send to the serve
                                user_id:user_id,
                                room_id:currentRoom_id,
                                desk_id: desks_id,
                                book_time_start: book_time_start,
                                book_time_end: book_time_end,
                                type: 'create',
                                },
                                type: "POST",
                                success: function (data) {   // the function will be call if ajax execute successfully
                                    displayMessage("booking created Successfully.");
                                    calendar.fullCalendar('refetchEvents');// updated the color of the booking

                                    calendar.fullCalendar('renderEvent', { // making the event stick on the full calender
                                    id: data.id,
                                    desk_id: desks_id,
                                    start: book_time_start,
                                    end: book_time_end,
                                    resourceId: resource.id, // made the calender display the resource Synchronize
                                    color:'#3CB371',
                                    room_id:currentRoom_id
                                    }, true);
                                    
                                    calendar.fullCalendar('unselect');   
                                    // booking_count++;
                                    timeout(1000); //1 minute (60,000 milliseconds), refresh the page 10 seconds
                                },    
                                error:function(error)
                                    {
                                    displayInfoMessage("my error is "+ error)
                                    },                        
                            }); 
                    } else { 
                        displayAlertMessage("Booking failed to created you can only " + max_booking_duration + " minute per booking.");
                        timeout(1200); } 
                }
            }else{  displayAlertMessage("Booking failed. you use all of your booking this month.");   
                        timeout(1500);}     
        },
        //end of event select function
        //start of resize function

        // the function call when the user change change the time of the event within the same day
        eventResize: function(event, delta, revertFunc, jsEvent, ui, view) {
            eventResizeAndDrop(event, delta, revertFunc, jsEvent, ui, view) ;
            } ,
        //  end of event resize function
        //  start of drop function
        // this is the function handle the situation that users move the event to difference place
        eventDrop: function( event, delta, revertFunc, jsEvent, ui, view) {
            eventResizeAndDrop(event, delta, revertFunc, jsEvent, ui, view) ;
            } ,
        //end of event drop function

        //start of full event click
        // delete the event 
        eventClick: function (event, delta, revertFunc, jsEvent, ui, view) {   
            var eventDelete = confirm("Are you sure you want to cancel your booking?");
            var canCancel=null;
            var event_time=event.start._i;
            // console.log( event_time); 
            // console.log(Number( event_time.slice(0,4)));    //year 
            // console.log(Number( event_time.slice(5,7)));    // month 
            // console.log(Number( event_time.slice(-11,-9)));    //day 
            // console.log(Number( event_time.slice(-8,-6)));    //horus 
            // console.log(Number( event_time.slice(-5,-3)));// minutes
            var today= new Date();
            var current = today.toLocaleString('en-GB');
            // console.log(current);
            // console.log(Number(current.slice(6,10)));//year
            // console.log(Number(current.slice(3,5)));//months
            // console.log(Number(current.slice(0,2)));//days
            // console.log(Number(current.slice(-8,-6)));//horus 
            // console.log(Number(current.slice(-5,-3)));// minutes
            var year_difference=Number(current.slice(6,10))-Number( event_time.slice(0,4));
            var month_difference=Number(current.slice(3,5))-Number( event_time.slice(5,7));
            var day_difference=Number(current.slice(0,2))-Number( event_time.slice(-11,-9));
            var h_difference=Number(current.slice(-8,-6))-Number( event_time.slice(-8,-6));
            var min_difference=Number(current.slice(-5,-3))-Number( event_time.slice(-5,-3));
            
            // if ( year_difference>0 || month_difference>0 || day_difference>0 || h_difference>0 ||  min_difference>0 )
            if (year_difference>0) {
                canCancel=false;
            }else if ( month_difference>0 ){
                canCancel=false;
            } else if (day_difference>0 ){
                canCancel=false;
            }else if ( h_difference<0 ){
                console.log("h difference" + h_difference);
                canCancel=true; 

            }else if ( min_difference>0){
                console.log("min difference" + min_difference);
                canCancel=false;
            } else  {
            canCancel=true; }

            // console.log(canCancel);

            // if( canCancel){
            if (event.user_id == user_id){ // if id is equal, which is true can delete
                if(canCancel){
                        if (eventDelete) {
                            $.ajax({
                                type: "POST",
                                url:  site_url+'/bookings-ajax',
                                data: {
                                    id: event.id,
                                    type: 'delete'
                                },
                                success: function (response) {
                                    calendar.fullCalendar('removeEvents', event.id);
                                    displayMessage("booking cancel successfully");
                                    // booking_count--;
                                    timeout(500);  //1 minute (60,000 milliseconds), refresh the page 10 seconds
                                },
                                error:function(error)
                                    {
                                    displayInfoMessage("my error is "+ error)
                                    },
                                });
                        }else { 
                        displayErrorMessage("booking failed to cancel");  }  
                }else{ 
                    displayErrorMessage("you cannot cancel the past bookings");}
            }else{ 
                displayErrorMessage("you cannot cancel other appointments");}
        }
            ,
            });
        //end of event click function
        //end of full calendar component1
        //print out the message      
        function displayMessage(message) 
            { 
                toastr.success(message); 
            } 

        function displayErrorMessage(message) 
            {
                toastr.error(message);
            } 

        function displayAlertMessage(message) 
            {
                toastr.warning(message);
            } 

        function displayInfoMessage(message) 
            {
                toastr.info(message);
            } 

        function  timeout(time)
            {  
                setTimeout(location.reload.bind(location), time);
            }

        function eventResizeAndDrop(event, delta, revertFunc, jsEvent, ui, view) 
            {
            var id = event.id;
            var book_time_start = moment(event.start).format('Y-MM-DD HH:mm');
            var book_time_end= moment(event.end).format('Y-MM-DD HH:mm');
            var updated_desk_id= Number(event.resourceId);   
            var hours_difference= Number(book_time_end.slice(-5,-3))-Number(book_time_start.slice(-5,-3));
            var minutes_difference= Number(Number(book_time_end.slice(-2))-Number(book_time_start.slice(-2)));
            var sum_difference=  hours_difference*60+  minutes_difference;

            if (event.user_id == user_id){ // if id is equal, which is true can delete

                if (sum_difference<=max_booking_duration) {
                    $.ajax({
                        url:"{{ route('calendarUpdate', '') }}" +'/'+ id,
                        type:"PATCH",
                        dataType:'json',
                        data:{ book_time_start, book_time_end, updated_desk_id},

                            success:function(response){
                            displayMessage("booking updated successfully");
                            },

                            error:function(error){
                                displayInfoMessage("my error is "+ error)
                                },
                                });
                            }
                else { 
                    displayAlertMessage("Booking failed to updated. \n You can only select " + max_booking_duration + " minutes per booking.");
                    revertFunc();  }  
            }else {
                displayErrorMessage("You cannot modify others appointments");
                revertFunc();}
            } 
    });
</script>

<!-- filter code  -->
<script type="application/javascript">
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

        // enable the building select field
        if ($('#building').is(':disabled')) {
            $('#building').prop('disabled', false);
        }
    
        // Populate the building dropdown with all buildings that belong to the campus
        $('#building').append($('<option disabled selected hidden></option>'));
        filteredBuildings.forEach (building => $('#building').append($('<option/>').val(building.id).text(building.name)));

        // disable the floor field
        if (!($('#floor').is(':disabled'))) {
            $('#floor').empty();
            $('#floor').append($('<option disabled selected hidden></option>'));
            $('#floor').prop('disabled', true);
        }

        // disable the room field
        if (!($('#room').is(':disabled'))) {
            $('#room').empty();
            $('#room').append($('<option disabled selected hidden></option>'));
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

        // Populate the floor dropdown with all floors that belong to the building
        $('#floor').append($('<option disabled selected hidden></option>'));
        filteredFloors.forEach (floor => $('#floor').append($('<option/>').val(floor.id).text(floor.floor_num)));

        // enable the floor select field
        if ($('#floor').is(':disabled')) {
            $('#floor').prop('disabled', false);
        }

        // disable the room field
        if (!($('#room').is(':disabled'))) {
            $('#room').empty();
            $('#room').append($('<option disabled selected hidden></option>'));
            $('#room').prop('disabled', true);
        }
    });

    $('#floor').change( function() {
        // Clear the room dropdown 
        $('#room').empty();

        var floorId = parseInt($('#floor').find(':selected').attr('value'));
        var filteredRooms = rooms.filter(item => {
                if (!item.is_closed)
                    return item.floor_id === floorId;
            });
        
        // Check if filteredRooms.length > 1
        // If true, we have an option that pops up and informs the user that all rooms are closed. 

        // Populate the room dropdown with all rooms that belong to the floor
        $('#room').append($('<option disabled selected hidden></option>'));
        filteredRooms.forEach (room => $('#room').append($('<option/>').val(room.id).text(room.name)));

        // enable the floor select field
        if ($('#room').is(':disabled')) {
            $('#room').prop('disabled', false);
        }
    });

    // Resets the search modal dropdown to its original state
    $(window).on('load', function(){
        // Grab all of the default values for the search filter
        var currentRoom = <?php echo json_encode($currentRoom)?>;
        var currentFloor = <?php echo json_encode($currentRoom->floor)?>;
        var currentBuilding = <?php echo json_encode($currentRoom->floor->building)?>;
        var currentCampus = <?php echo json_encode($currentRoom->floor->building->campus)?>;

        // Grab all available resources to populate the search filter dropdowns
        var campuses = <?php echo json_encode($campuses)?>;
        var buildings = <?php echo json_encode($buildings)?>;
        var floors = <?php echo json_encode($floors)?>;
        var rooms = <?php echo json_encode($rooms)?>;

        // CAMPUS DROPDOWN ONLOAD SECTION

        // Clear the campus dropdown
        $('#campus').empty();

        // Populate the campus dropdown, selecting the current campus
        campuses.forEach (campus => {
            if (campus.id === currentCampus.id)
                $('#campus').append($('<option selected/>').val(campus.id).text(campus.name));
            else
                $('#campus').append($('<option/>').val(campus.id).text(campus.name));
        });

        // BUILDING DROPDOWN ONLOAD SECTION

        // Clear the building dropdown
        $('#building').empty();

        // Filter the buildings based off of the current campus
        var filteredBuildings = buildings.filter(item => {
                return item.campus_id === currentCampus.id;
            });
    
        // Populate the building dropdown, selecting the current building
        filteredBuildings.forEach(building => {
            if (building.id === currentBuilding.id)
                $('#building').append($('<option selected/>').val(building.id).text(building.name))
            else
                $('#building').append($('<option/>').val(building.id).text(building.name))
        });
        
        // Enable the building select field
        if ($('#building').is(':disabled')) {
            $('#building').prop('disabled', false);
        }

        // FLOOR DROPDOWN ONLOAD SECTION

        // Clear the floor dropdown 
        $('#floor').empty();

        // Filter the floors based off of the current building
        var filteredFloors = floors.filter(item => {
                return item.building_id === currentBuilding.id;
            });

        // Populate the floors dropdown, selecting the current floor
        filteredFloors.forEach(floor => {
            if (floor.id === currentFloor.id)
                $('#floor').append($('<option selected/>').val(floor.id).text(floor.floor_num))
            else
                $('#floor').append($('<option/>').val(floor.id).text(floor.floor_num))
        });

        // enable the floor select field
        if ($('#floor').is(':disabled')) {
            $('#floor').prop('disabled', false);
        }

        // ROOM DROPDOWN ONLOAD SECTION

        // Clear the room dropdown 
        $('#room').empty();

        // Filter the rooms based off of the current floor
        var filteredRooms = rooms.filter(item => {
            if (!item.is_closed)
                return item.floor_id === currentFloor.id;
        });

        // Populate the rooms dropdown, selecting the current room
        filteredRooms.forEach(room => {
            if (room.id === currentRoom.id)
                $('#room').append($('<option selected/>').val(room.id).text(room.name));
            else
                $('#room').append($('<option/>').val(room.id).text(room.name));
        });

        // enable the floor select field
        if ($('#room').is(':disabled')) {
            $('#room').prop('disabled', false);
        }
    });
</script>

@endsection
