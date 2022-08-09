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
/* calender style */
#bookings_event_{
    max-width: 1000px;
    margin: 40px auto;
}

/* the block below the calender page */
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
.fc-view-container { 
overflow-x: scroll; 
}
.fc-axis {
    position: sticky;
    background:#87CEFA;
}  
.fc-resource-cell
{
    position: sticky;
    left: 0;
    background:#87CEFA;
} 
.fc .fc-agendaDay-view .fc-bg tr > td{
    background-color: white;
} 
</style>
</head>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
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
                                <p>Monthly bookings used: <span  class="badge bg-info  text-dark">{{ $userMonthlyBookingCount}}  / {{($role_info->num_monthly_bookings)}}</span>.</p> 
                            </div>
                        </div>
                    <hr>
                    <div class="container mt-6" style="max-width: 1200px">
                        <div id='bookings_event'></div>
                        <div class="container">
                            <div class="row justify-content-start">
                                <div class="col-5">
                                    <b>Room Resources</b>
                                    @foreach($resources_array as $resource)
                                    <div class="row justify-content-start"  data-toggle="tooltip" title="{{$resource->pivot->description}}">
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
                    <!-- --option Modal  start -- -->
                    <div class="modal fade" id="optionModal" tabindex="-1" aria-labelledby="optionModal" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row justify-content-between">
                                        <div class="col-6 ">
                                            <button type="button" class="btn btn-info "  id= "repeatedBooking">Repeat this booking</button>
                                        </div>
                                        <div class="col-2 ">
                                            <button type="button" class="btn btn-danger offset-md-1 p-2" id= "deleteShow">Delete</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- --Modal  finish -- -->

                    <!-- --cancel Modal  start -- -->
                    <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="cancelModalLabel">Delete Confirmation</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                Are you sure to delete the bookings?
                                </div>
                            
                                    <div class="modal-footer">
                                        <!-- <button type="button" class="btn btn-secondary" id= "remove">Close</button> -->
                                        <button type="submit" class="btn btn-danger"  id= "deleteConfirm">Confirm</button>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <!-- --Modal  finish -- -->
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src=" https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@1.10.4/dist/scheduler.min.js"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.2/main.min.js'></script>
<script>
    $(document).ready(function () {
        // tooltip code
        $("body").tooltip({ selector: '[data-toggle=tooltip]' });

        // Encode the current room the user searched for into JSON
        var currentRoom = <?php

use App\Http\Controllers\User\CalendarViewController;

echo json_encode($currentRoom)?>;
        var display= <?php echo json_encode($allBooking)?>;   // this one will get all the booking from the booking table
        var user_id= <?php echo json_encode($user->id)?>;
        var role_info= <?php echo json_encode($role_info)?>;
        var currentRoom_id = <?php echo json_encode($currentRoom->id)?>;
        var max_booking_window=<?php echo json_encode($role_info->max_booking_window)?>;     // this one will use in  validRange function // it from today date to future date
        var max_booking_duration=<?php echo json_encode($role_info->max_booking_duration)?> *60;  //how long can the user make the booking for a day
        var userMonthlyBookingCount=<?php echo json_encode(  $userMonthlyBookingCount)?>; 
        var max_monthly_bookings=<?php echo json_encode($role_info->num_monthly_bookings)?>;  

        var message = <?php echo json_encode($message)?>;

        var site_url = "{{ url('/') }}";

        var clickerCounter=1; 

        // Grab all desks, then filter them down to the ones that belong to the current room
        // We won't display closed desks to users
        var desks = <?php echo json_encode($desks)?>;

        var filteredDesks = desks.filter(item => {
            if(!item.is_closed)
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

        displayStoreMessage();
        displayMaxCapacityMessage();

        //full calender js code 
        var calendar = $('#bookings_event').fullCalendar({
        schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
        //the header of the full calender
        header:{
            left:'title',
            center:'prev,today,next',
            right:'agendaDay, timelineWeek',  // timeline week place the resource under week view
        },
        footer:true,
        defaultView: 'agendaDay',
        resourceLabelText: 'Rooms',
        timeZone: ('America/Vancouver'),
        showNonCurrentDates: false,
        timeFormat: 'H(:mm)', // 24 hours period 
        slotDuration: '00:15:00', //  15 minutes
        // slotLabelFormat:'H(:mm)',
        // minTime: "08:00:00",
        // maxTime: "23:00:00",
    
        displayEventTime: true,
        allDaySlot:false,
        editable: true, // user can drag the event 
        selectable: true, // the event could be select
        selectHelper: true,
        eventOverlap:false,
        selectOverlap:false,
        resourceAreaWidth: '15%',
        stickyFooterScrollbar:true,
        selectAllow: function(selectInfo) {
            return moment().diff(selectInfo.start) <= 0
        },

        //selectable window 
        validRange: function(nowDate) {
            endString=getTime((nowDate.clone().add(max_booking_window, 'days'))._d);
            startString =getTime(nowDate._d );
            return { 
            start:  startString,
            end:   endString
            };
        },
       // end of selectable window 
        //start of render function
        eventRender: function (event) {
            getColumn();
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
           var desks_id= Number(resource.id);  // this code making the user could select the column to pass the desk id
            if( userMonthlyBookingCount<max_monthly_bookings){
                if (desks_id){
                        var book_time_start = $.fullCalendar.formatDate(book_time_start, "Y-MM-DD HH:mm");  // generate the date time and store into variable
                        var book_time_end = $.fullCalendar.formatDate(book_time_end, "Y-MM-DD HH:mm");
                        var sum_difference= timeDifference(book_time_start,book_time_end);
                        if (sum_difference<=max_booking_duration) {
                            if (clickerCounter==1){
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
                                        beforeSend: function() {
                                            clickerCounter++;
                                    },
                                    }).done(function(data) {
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
                                        sessionStorage.message = "booking successful updated";
                                        timeout(1); 
                                        calendar.fullCalendar('unselect');               
                                    }).fail(function(jqXHR, textStatus, errorThrown) {
                                        calendar.fullCalendar('unselect');  
                                        sessionStorage.status = "Booking Failed, Room at Max Capacity";
                                        timeout(1); 
                                    });
                        } } else { 
                            displayAlertMessage("Booking failed to created you can only " + max_booking_duration + " minutes per booking.");
                            calendar.fullCalendar('unselect');  
                    }
                }
            }else{  displayAlertMessage("Booking failed to created. you use all of your bookings this month.");   
                        calendar.fullCalendar('unselect');  }    
        },
        //end of event select function
        //start of resize function

        // the function call when the user change change the time of the event within the same day
        eventResize: function(event, delta, revertFunc, jsEvent, ui, view) {
            var modifyConfirm = changeApprove(event.start._i);
            if(modifyConfirm){
                eventResizeAndDrop(event, delta, revertFunc, jsEvent, ui, view) }
            else { 
                displayErrorMessage("Past bookings cannot be modify");
                revertFunc();}
            } ,
        //  end of event resize function
        //  start of drop function
        // this is the function handle the situation that users move the event to difference place
        eventDrop: function( event, delta, revertFunc, jsEvent, ui, view) {
            var originalStart='';
            var  originalEnd='';
            display.forEach(object => {
                if(object.id == event.id){
                    originalStart=object.start;
                    originalEnd=object.end;
                } 
            }) ;
            var  originalStart = changeApprove( originalStart);
            var difference = changeApproveHelper(originalStart,event.start._i);
            var modifyConfirm = changeApprove( event.start._i);

            if( originalStart){
                if(!modifyConfirm){
                    if (event.user_id == user_id){
                    displayInfoMessage("Booking failed to updated, invalid start time.");}
                    else {
                    displayErrorMessage("Past Bookings cannot be modify.");
                    displayErrorMessage("You cannot modify other booking");}
                    revertFunc();
                    }
                    else 
                eventResizeAndDrop(event, delta, revertFunc, jsEvent, ui, view); }  
                else {
                        displayErrorMessage("Past Bookings cannot be modify.");
                        revertFunc();
                }
        },
        //end of event drop function
        // delete the event 
        eventClick: function (event, delta, revertFunc, jsEvent, ui, view) {   
            // $('#optionModal').modal("show");// delete and make the repeated booking

            // // click the delete
            // $('#deleteShow').on("click", function () {  

            // are you sure you want to cancel model show up
                $('#cancelModal').modal("show");

                $('#deleteConfirm').on("click", function (){   
                    eventDelete=true;
                    var deleteConfirm=null;
                    var deleteConfirm = changeApprove(event.start._i);
                        if (event.user_id == user_id){ // if id is equal, which is true can delete
                            if(deleteConfirm){
                                if ( eventDelete) {
                                    $.ajax({
                                        type: "POST",
                                        url:  site_url+'/bookings-ajax',
                                        data: {
                                            id: event.id,
                                            type: 'delete'
                                            },
                                        success: function (response) {
                                            calendar.fullCalendar('removeEvents', event.id);
                                            sessionStorage.message = "Booking cancel successfully";
                                            timeout(1);  //1 minute (60,000 milliseconds)
                                            modalInfo();
                                            },
                                        error:function(error)
                                            {
                                            displayInfoMessage("my error is "+ error)
                                            },
                                        });
                                }else { 
                                    displayErrorMessage("Booking failed to cancel"); 
                                    modalInfo();}  
                            }else{ 
                                displayErrorMessage("You cannot cancel the past booking");
                                modalInfo();}
                        }else{ 
                            displayErrorMessage("You cannot cancel other booking");
                            modalInfo(); }
                });
            // });
        },
            });
        //end of event click function
        //end of full calendar component1
        
        function modalInfo(){
            $('#cancelModal').modal("hide");
            $('#optionModal').modal("hide");
        }

        function changeApprove(time){
            var book_time_start = moment(time).format('Y-MM-DD HH:mm');
            var current = moment().format('Y-MM-DD HH:mm');
            var confirm = moment(current).isBefore(book_time_start); 
            return confirm;
        }

        function changeApproveHelper(past,future){
            var past_start = moment(past).format('Y-MM-DD HH:mm');
            var new_start = moment(future).format('Y-MM-DD HH:mm');
            var confirm = moment(new_start).isBefore(past_start); 
            return confirm;
        }

        // getting the time window
        function getTime(time){
            var Holder= String( time );
            var monthString = moment().month(Holder.substring(4,7)).format("M");
            if(monthString.length==1){
                monthString="0"+monthString
            }
            var result=(Holder.substring(11,15)+"-"+monthString+'-'+ Holder.substring(8,11)).trim();
            return result;
        }


        // function of checking the booking duration
        function timeDifference(book_time_start,book_time_end){
            var startTime = moment(book_time_start, "YYYY-MM-DD HH:mm");
            var endTime = moment(book_time_end, "YYYY-MM-DD HH:mm");
            var duration = moment.duration(endTime.diff(startTime));
            var hours = parseInt(duration.asHours());
            var minutes = parseInt(duration.asMinutes())%60 ;
            var difference=  hours*60+  minutes;
            return difference;
        }

        function eventResizeAndDrop(event, delta, revertFunc, jsEvent, ui, view) {
            var id = event.id;
            var book_time_start = moment(event.start).format('Y-MM-DD HH:mm');
            var book_time_end= moment(event.end).format('Y-MM-DD HH:mm');
            var updated_desk_id= Number(event.resourceId);   
            var sum_difference= timeDifference(book_time_start,book_time_end);
                if (event.user_id == user_id){ // if id is equal, which is true can delete
                    if (sum_difference<=max_booking_duration) {
                        $.ajax({
                            url:"{{ route('calendarUpdate', '') }}" +'/'+ id,
                            type:"PATCH",
                            dataType:'json',
                            data:{ book_time_start, book_time_end, updated_desk_id},
                                success:function(response){
                                displayMessage("Booking updated successfully");
                                },

                                error:function(error){
                                displayInfoMessage("My error is "+ error)
                                },
                                });
                        }else { 
                        displayAlertMessage("Booking failed to updated. \n You can only select " + max_booking_duration + " minutes per booking.");
                        revertFunc();  }  
                }else {
                    displayErrorMessage("You cannot modify others bookings");
                    revertFunc();}
            }

             //print out the message      
        function displayMessage(message) {toastr.success(message); } 

        function displayErrorMessage(message) {toastr.error(message);} 

        function displayAlertMessage(message) {toastr.warning(message);} 

        function displayInfoMessage(message) {toastr.info(message);} 

        function  timeout(time){  setTimeout(location.reload.bind(location), time);}

        // this one is using the html api to storage the message and it will display it after the page reload
        function displayStoreMessage() {
            if (typeof(Storage) !== "undefined") {
                if (sessionStorage.message) {
                displayMessage(sessionStorage.message);
                sessionStorage.clear();}
            }else
                {console.log ("Sorry, your browser does not support web storage..."); 
                    displayMessage("Your request had been updated");    }
            }


        function displayMaxCapacityMessage() {
            if (typeof(Storage) !== "undefined") {
                if (sessionStorage.status) {
                    displayErrorMessage(sessionStorage.status);
                    sessionStorage.clear();}
            } else {
                console.log ("Sorry, your browser does not support web storage..."); 
                displayErrorMessage("Booking Failed, Room at Max Capacity");    
            }
        }

        // changing the column width base on different case 
        function getColumn(){
            var columnCount = $('.fc-agendaDay-view th.fc-resource-cell').length;
            if (11> columnCount && columnCount>5) {  
                setColumnWidth(150);}  
            else if (16 >columnCount && columnCount>=11) {
                setColumnWidth(200);}  
            else if (columnCount>=16) {
                setColumnWidth(250); }
            else 
                setColumnWidth(100);
            }

        function setColumnWidth(number){
            $('.fc-agendaDay-view').css('width', number  + '%'); 
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
                if (!item.is_closed)
                    return item.floor_id === floorId;
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
