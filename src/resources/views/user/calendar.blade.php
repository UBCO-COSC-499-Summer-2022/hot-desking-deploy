@extends('layouts.app')

@section('content')
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@1.10.4/dist/scheduler.min.css' rel='stylesheet' />
    <style>

/* calender style */
#bookings_event_{
    max-width: 900px;
    margin: 20px auto;
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
    /* background:#87CEFA; */
    background: #e6f3ff;
}  
.fc-resource-cell
{
    position: sticky;
    left: 0;
    background: #e6f3ff;
} 
.fc .fc-agendaDay-view .fc-bg tr > td{
    background-color: white;
} 

</style>
</head>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    {{ __('Calendar View') }}
                </div> 
                <div class="card-body">
                    <div id="alert_container" style="display:none"></div>
                    <!-- Search Modal -->
                    <form action="{{ route('calendar') }}" class="row g-2" >
                        @csrf
                        <!-- Campus Selection -->
                        <div class="col-2">
                            <label for="campus" class="label label-default">Campus</label>
                            <select id="campus" class="form-select @error('campus_id')is-invalid @enderror" name="campus_id" required>
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
                            <select id="building" class="form-select @error('building_id')is-invalid @enderror" name="building_id" disabled required>
                                <option disabled selected hidden></option>
                            </select>
                            @error('building_id')
                                <div class='alert alert-danger'>{{$message}}</div>
                            @enderror
                        </div>

                        <!-- Floor Selection -->
                        <div class="col-2 ">
                            <label for="floor" class="label label-default">Floor</label>
                            <select id="floor" class="form-select @error('floor_id')is-invalid @enderror" name="floor_id" disabled required>
                                <option disabled selected hidden></option>
                            </select>
                            @error('floor_id')
                                <div class='alert alert-danger'>{{$message}}</div>
                            @enderror
                        </div>

                        <!-- Room Selection -->
                        <div class="col-2 ">
                            <label for="room" class="label label-default">Room</label>
                            <select id="room" class="form-select @error('room_id')is-invalid @enderror" name="room_id" disabled required>
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
                    <div class="row">
                        <div class="container accordion accordion-flush " id="accordionFlushExample">
                            <div class="row border">
                                <div class="col accordion-item">
                                    <h2 class="accordion-header" id="flush-headingOne">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                            Instructions
                                        </button>
                                    </h2>
                                    <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne"  style="background-color:#e6f3ff;">
                                        <div class="accordion-body">
                                            Note: 
                                            <ul>
                                            <li>You cannot create bookings for past dates and times. </li>
                                            <li> You can modify bookings up until half an hour before they start.</li>
                                            </ul>
                                            <hr/>
                                            Steps:
                                                <ul>
                                                <li> Click and drag an empty cell to make a new booking.</li>
                                                <li> To delete a booking, click on it and confirm.</li>
                                                <li> To modify a booking, drag to change the duration or hold down click to move.</li>
                                                <li> To make a booking a repeated booking, click on it and confirm.</li>
                                                </ul>
                                                <hr/>
                                            <div class="row">
                                                <div class="col-auto">
                                                    <p>Your user role is  <span class="badge bg-info  text-dark">{{ strtolower($role_info->role)}}</span>. You have a maximum booking window of <span class="badge bg-info  text-dark">{{$role_info->max_booking_window }}</span> days 
                                                    and maximum booking duration of <span class="badge bg-info  text-dark">{{($role_info->max_booking_duration)*60}}</span> minutes.</p>
                                                </div>
                                            </div> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row ">
                        <div class="col-auto"> 
                            <p>Monthly bookings used: <span  class="badge bg-info  text-dark">{{ $userMonthlyBookingCount}}  / {{($role_info->num_monthly_bookings)}}</span>.</p> 
                        </div>
                        <div class="col-auto"> 
                            <p>Maximum Room Occupancy: <span  class="badge bg-info  text-dark">{{ $maxRoomOccupancy }}</span>.</p> 
                        </div>
                    </div>
                    <hr>
                    <div id="calendar_container" class="container mt-6" style="max-width: 1000px">
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
                </div>
            </div>
        </div>
    </div>
</div>

<!-- --option Modal start -- -->
<div class="modal fade" id="optionModal" tabindex="-1" aria-labelledby="optionModal" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="optionModal">Modification Options</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    1. Make a repeated booking: Repeat this booking every week for the current month.
                                    <hr>
                                    2. Delete this booking.
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-info "  id= "repeatedBookingShow">Repeat</button>
                                    <button type="button" class="btn btn-danger" id= "deleteShow">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- --Modal  finish -- -->

                    <!-- --repeatedBooking Modal start -- -->
                    <div class="modal fade" id="repeatedBooking" tabindex="-1" aria-labelledby="repeatedBooking" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="optionModal">Repeated Booking Confirmation</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Are you sure want to repeat this booking every week within your valid booking window? -->
                                    You have {{($role_info->num_monthly_bookings) - $userMonthlyBookingCount}} remaining bookings for this month. You will be using up a booking for each week of the repeated booking.
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-info"  id= "repeatedBookingConfirm">Confirm</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- --Modal  finish -- -->

                    <!-- --delete Modal  start -- -->
                    <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="cancelModalLabel">Delete Confirmation</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                Are you sure you want to delete this booking?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-danger"  id= "deleteConfirm">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- --Modal  finish -- -->


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
<script src=" https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@1.10.4/dist/scheduler.min.js"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.2/main.min.js'></script>
<script type="text/javascript" src="{{ asset('js/test.js') }}"></script>
<script>
    $(document).ready(function () {

        getColumn();
        // tooltip code
        $("body").tooltip({ selector: '[data-toggle=tooltip]' });


        // Encode the current room the user searched for into JSON
        var currentRoom = <?php

use App\Http\Controllers\User\CalendarViewController;
use Illuminate\Support\Facades\Log;

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

        // to do: remove it before the cutoff
        var buttonClickCount =0;
        var buttonHelper=0;


        // Grab all desks, then filter them down to the ones that belong to the current room
        // We won't display closed desks to users
        var desks = <?php echo json_encode($desks)?>;

        var filteredDesks = desks.filter(item => {
            if(!item.is_closed)
                return item.room_id === currentRoom.id;
        });
        // Hide the calendar timeslot if there are no available desks to display to the user
        if (filteredDesks.length < 1) {
            $('#alert_container').empty();
            $('#alert_container').append('<div class="alert alert-danger alert-dismissible fade show">This room currently has no open desks</div>');
            $('#alert_container').show();
            $('#calendar_container').hide();
        } 
        var desksResources = <?php echo json_encode($desks_resources)?>;
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
        displayBookingCollisionMessage();
        partialCompleted();

        localStorage.userMonthlyBookingCountCopy = Number(userMonthlyBookingCount);  // this one will store the message in the browser long period of time

        sessionStorage.clear();
        
        //full calender js code 
        var calendar = $('#bookings_event').fullCalendar({
        schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
        //the header of the full calender
        header:{
            // left:'prev,next,nextWeek, today',
            left:'prev,next, today',
            center:'title',
            right:'agendaDay,timelineWeek',  // timeline week place the resource under week view
        },
// to do: remove it before the cutoff
        // customButtons: {
    // preWeek: {
    // icon: 'bi bi-chevron-double-left',   
    // click: function() {
    //     if ( buttonHelper==0 &&  buttonClickCount==1){
    //         $("#bookings_event").fullCalendar('gotoDate', moment());
    //     buttonHelper==1;
    //     buttonClickCount--;
    //     }
    //     else if ( buttonHelper==0 &&  buttonClickCount==0){
    //             $("#bookings_event").fullCalendar('gotoDate', moment().add(7, 'days'));
    //             buttonHelper==1;
    //         }
    //     else if (buttonHelper==1&& buttonClickCount==0){
    //         $("#bookings_event").fullCalendar('gotoDate', moment()); }
    //     else if (buttonHelper==0){
    //         $("#bookings_event").fullCalendar('gotoDate', moment());
    //         buttonHelper==0;}
            
    //     }
    // },

        // nextWeek: {
        //     icon: 'bi bi-chevron-double-right',   
        //     click: function() {
        //         if (buttonClickCount==0){
        //         $("#bookings_event").fullCalendar('gotoDate', moment().add(7, 'days'));
        //         buttonClickCount=1;}
        //         else if (buttonClickCount==1){
        //             $("#bookings_event").fullCalendar('gotoDate', moment().add(14, 'days'));
        //             buttonClickCount =0;
        //         }
        //     }
        // },
        // next: {
        //     icon: 'bi bi-chevron-right',
        //     click: function() {
        //         $("#bookings_event").fullCalendar('next');
        //     }
        //     },
        //     prev: {
        //     icon: 'bi bi-chevron-left',
        //     click: function() {
        //         $("#bookings_event").fullCalendar('prev');
        //     }
        // },
        // },

        footer:true,
        defaultView: 'agendaDay',
        resourceLabelText: 'Rooms',
        timeZone: ('America/Vancouver'),
        showNonCurrentDates: false,
        timeFormat: 'H(:mm)', // 24 hours period 
        slotDuration: '00:15:00', //  15 minutes
        // slotLabelFormat:'H(:mm)',
    
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
            endString=getTime((nowDate.clone().add(max_booking_window+1, 'days'))._d);
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
                resourceObj.resourceIcon="nothing";
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

            var doubleCheck=Math.max( Number(localStorage.userMonthlyBookingCountCopy),userMonthlyBookingCount);

            if( doubleCheck<max_monthly_bookings){
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
                                        sessionStorage.message = "Booking successfully created.";
                                        calendar.fullCalendar('unselect');   
                                        
                                        localStorage.userMonthlyBookingCountCopy=1+ Number(localStorage.userMonthlyBookingCountCopy);
                                        timeout(1);             
                                    }).fail(function(data) {
                                        calendar.fullCalendar('unselect');  
        
                                        if (data.status) {

                                        if (data.responseText === '{"error":"Booking failed, a booking collision was detected."}') {
                                            sessionStorage.collisionDetected = "Booking failed: A booking collision was detected.";
                                        } else {
                                            sessionStorage.status = "Booking Failed: Room at maximum capacity.";
                                        }
                                    }
                                    
                                        timeout(1); 
                                    });
                        } } else { 
                            displayAlertMessage("Booking attempt failed: You exceeded your maximum booking duration of " + max_booking_duration + " minutes per booking.");
                            calendar.fullCalendar('unselect');  
                    }
                }
            }else{  displayAlertMessage("Booking attempt failed: You have no remaining bookings for this month.");   
                        calendar.fullCalendar('unselect');  }    
        },
        //end of event select function
        //start of resize function

        // the function call when the user change change the time of the event within the same day
        eventResize: function(event, delta, revertFunc, jsEvent, ui, view) {
            var modifyConfirm = changeApprove(event.start._i);
            var deadlineConfirm= deadline(event.start._i);
            if(modifyConfirm){
                if(deadlineConfirm){eventResizeAndDrop(event, delta, revertFunc, jsEvent, ui, view); }
                else {
                    if (event.user_id == user_id){   
                    displayAlertMessage("Booking modification failed: You passed the deadline to resize your booking.");
                    revertFunc();
                    }
                    else{
                    displayErrorMessage("Booking modification failed: You cannot resize another user's booking.");
                    revertFunc();}
                }
            }
            else { 
                if (event.user_id == user_id){   
                    displayAlertMessage("Booking modification failed: You cannot change the duration of your past booking.");
                    revertFunc();
                }
                else{
                displayErrorMessage("Booking modification failed: You cannot change the duration of another user's booking.");
                revertFunc();
                }
            }
            } ,
        //  end of event resize function
        //  start of drop function
        // this is the function handle the situation that users move the event to difference place
        // this function need to consider the booking time before and after the move, 
        eventDrop: function( event, delta, revertFunc, jsEvent, ui, view) { 
            var originalStart='';
            var  originalEnd='';
            display.forEach(object => {
                if(object.id == event.id){
                    originalStart=object.start;
                    originalEnd=object.end;
                } 
            }) ;
            
            var  originalStartCopy = changeApprove( originalStart);
            var deadlineConfirm= deadline(originalStart);
            // check if new updated booking time is before current
            var current = moment().format('Y-MM-DD HH:mm');
            var new_booked_time = moment(event.start._i).format('Y-MM-DD HH:mm');
            var modifyFail = moment(new_booked_time).isBefore(current); 
            // var difference = changeApproveHelper(originalStart,event.start._i);
            if( originalStartCopy){
                if(deadlineConfirm){
                    if(modifyFail){
                            if (event.user_id == user_id){
                            displayInfoMessage("Booking modification failed: You chose an invalid booking start time."); 
                            revertFunc();
                            }
                            else {displayErrorMessage("Booking modification failed: You cannot change the duration of another user's booking.");
                            revertFunc();
                            }
                        }
                        else {
                        eventResizeAndDrop(event, delta, revertFunc, jsEvent, ui, view); 
                    } 
                }
                    else {  
                        if (event.user_id == user_id){   
                            displayInfoMessage("Booking modification failed: You are past the deadline to modify your booking.");
                                revertFunc();
                        }
                        else{
                        displayErrorMessage("Booking modification failed: You cannot modify another user's booking.");
                                revertFunc();    }          
                    }       
            }  
                else {
                    if (event.user_id == user_id){   
                            displayAlertMessage("Booking modification failed: You cannot modify your past booking.");
                                revertFunc();
                        }
                    else
                        {displayErrorMessage("Booking modification failed: You cannot modify another user's past booking.");
                        revertFunc(); } }
        },
        //end of event drop function
        // delete the event 
        eventClick: function (event, delta, revertFunc, jsEvent, ui, view) {  
            
            $('#optionModal').modal("show");// delete and make the repeated booking

                // repeated booking option 
            $('#repeatedBookingShow').on("click", function () {  
                $('#optionModal').modal("hide");
            // are you sure you want to cancel model show up
                $('#repeatedBooking').modal("show");
                $('#repeatedBookingConfirm').on("click", function (){   

                    sessionStorage.clear();
                    sessionStorage.counterFail=Number('0');
                    sessionStorage.counterSuccess=Number('0');
                    sessionStorage.collision=Number('0');

                    var  listOfFutureBooking = [];

                    // only make the repeated booking with current month
                    var endOfMonth= moment(event.start._i).endOf('month');
                    var current = moment(event.start._i);

                    // checking how many day left fot this month
                    var dayLeftForThisMonth = endOfMonth.diff(current, 'days');

                    // take the minimum between booking window and dayLeftForThisMonth 
                    var valid_max_booking_window= Math.min(dayLeftForThisMonth, max_booking_window);

                    // use the valid_max_booking_window to calculate how many repeated booking the user can make for this month
                    var numberRepeatBookingRemain=Math.floor( valid_max_booking_window/7);  

                    var doubleCheck2=Math.max( Number(localStorage.userMonthlyBookingCountCopy),userMonthlyBookingCount);

                    var bookingCountLeaveMonthly = max_monthly_bookings-doubleCheck2;  


                    var validRepeatBookingRemain = Math.min(numberRepeatBookingRemain, bookingCountLeaveMonthly);
                    var same_desk_id=Number(event.desk_id);

                    var deadlineConfirm= deadline(event.start._i);

                    if (event.user_id == user_id){ // if id is equal, which is true can delete

                        if (bookingCountLeaveMonthly==0){ 

                            if (dayLeftForThisMonth<8){
                            displayAlertMessage("Repeated booking attempt cancelled: The date is less than a week until the end of the month."); 
                            }else{
                            displayAlertMessage("Repeated booking attempt cancelled: You have no remaining bookings for this month.");  }
                            
                        }else {

                            if (validRepeatBookingRemain==0){
                                if (dayLeftForThisMonth<8){
                                    displayAlertMessage("Repeated booking attempt cancelled: The date is less than a week until the end of the month."); 
                                }else{
                                    displayAlertMessage("Repeated booking attempt cancelled: You have no remaining bookings for this month."); }  
                            }else {

                                // if(deadlineConfirm){
                                function failCallback(response){
                                    result = response; 
                                    // the ajax response will only have status when have a failure
                                    if (result.status) { 
                                        if (result.responseText === '{"error":"Booking failed, a booking collision was detected."}') {
                                            sessionStorage.collision = Number(sessionStorage.collision) + 1;
                                        } else {
                                            sessionStorage.counterFail = Number(sessionStorage.counterFail) + 1;
                                        }
                                    }
                                    if ( Number(sessionStorage.counterFail) == validRepeatBookingRemain ) {
                                        sessionStorage.allFail= "Repeat booking failed: No space available due to the maximum room occupancy.";
                                        sessionStorage.removeItem('partialA');
                                        sessionStorage.removeItem('counterFail');
                                        timeout(1);

                                    } else if (Number(sessionStorage.collision) == validRepeatBookingRemain) {
                                        sessionStorage.counterCollision = "Repeat booking failed: No space available due to conflicting bookings.";
                                        sessionStorage.removeItem('partialA');
                                        sessionStorage.removeItem('collision');
                                        timeout(1);
                                    } else {
                                        
                                        var actualResult= validRepeatBookingRemain - Number(sessionStorage.counterFail) - Number(sessionStorage.collision);

                                        if ( actualResult <=0)  
                                        { actualResult=0  
                                            sessionStorage.partialA =" Repeat booking failed: No space available due to conflicting bookings and maximum room occupancy."; 
                                        } else{  
                            
                                            sessionStorage.partialA ="Created " + Number(actualResult)+ " out of "
                                        + validRepeatBookingRemain+" possible bookings. Check the 'My Bookings' page for more information."; 
                                        }
                
                                    }    
                                }


                                function successCallback(response){
                                    data= response; 
                                    if (data.status===undefined) {
                                        sessionStorage.counterSuccess = Number(sessionStorage.counterSuccess) + 1; 
                                        localStorage.userMonthlyBookingCountCopy= 1+ Number(localStorage.userMonthlyBookingCountCopy);
                                    
                                    }
                                    if( Number(sessionStorage.counterSuccess) == validRepeatBookingRemain){
                                        sessionStorage.success = "Repeated booking successfully created "+ validRepeatBookingRemain+" bookings. View the 'My Booking' page for more information.";
                                        sessionStorage.removeItem('partialB');
                                        sessionStorage.removeItem('counterSuccess');
                                        timeout(1);
                                    }
                                    else {
                                        sessionStorage.partialB = "Repeated booking partially successful: Created "+  Number(sessionStorage.counterSuccess) + " out of "
                                        + validRepeatBookingRemain+" bookings. View the 'My Booking' page for more information."; 
                                    }    
                                    }  

                                for (var i = 0; i<validRepeatBookingRemain; i++){
                                    listOfFutureBooking[0]=moment(event.start._i).add(7*(i+1), 'days').format('Y-MM-DD HH:mm');
                                    listOfFutureBooking[1]= moment(event.end._i).add(7*(i+1), 'days').format('Y-MM-DD HH:mm');

                                    if (Number(localStorage.userMonthlyBookingCountCopy)< max_monthly_bookings){

                                        $.ajax({
                                        url:  site_url+"/bookings-ajax",   
                                        data: { // a list of data will be send to the serve
                                            user_id:event.user_id,
                                            room_id:currentRoom_id,
                                            desk_id: same_desk_id,
                                            book_time_start:listOfFutureBooking[0],
                                            book_time_end:  listOfFutureBooking[1],
                                            type: 'create',
                                            },
                                            type: "POST",
                                            success: successCallback,
                                            //  success:function (data) {
                                            // calendar.fullCalendar('refetchEvents');// updated the color of the booking
                                            // calendar.fullCalendar('renderEvent', { // making the event stick on the full calender 
                                            // id: data.id,
                                            // desk_id: same_desk_id,
                                            // start:  book_time_start,
                                            // end: book_time_end,
                                            // resourceId: event.resourceId, // made the calender display the resource Synchronize
                                            // color:'#3CB371',
                                            // room_id:currentRoom_id
                                            // }, true);
                                            // calendar.fullCalendar('unselect'); }, 
                                            error:failCallback,
                                        }); 
                                    }
                                    // timeout(1);
                                }
                                        // } else{   displayInfoMessage("Booking cancellation failed, You are past the deadline to cancel your booking.");
                                        // }
                                timeout(1);
                            }
                        }
                    }else{  displayErrorMessage("Repeated booking attempt failed: You cannot make a repeated booking for another user's booking.");  }
                    $('#repeatedBooking').modal("hide");
                });
            });

            // delete option 
            $('#deleteShow').on("click", function () {  
                $('#optionModal').modal("hide");
            // are you sure you want to cancel model show up
                $('#cancelModal').modal("show");
                $('#deleteConfirm').on("click", function (){   
                    sessionStorage.clear();

                    eventDelete=true;
                    var deleteConfirm=null;
                    var deleteConfirm = changeApprove(event.start._i);
                    var deadlineConfirm= deadline(event.start._i);
                        if (event.user_id == user_id){ // if id is equal, which is true can delete
                            if(deleteConfirm){
                                if(deadlineConfirm){
                                    if ( eventDelete) {
                                        $.ajax({
                                            type: "POST",
                                            url:  site_url+'/bookings-ajax',
                                            data: {
                                                id: event.id,
                                                user_id: user_id,
                                                type: 'delete'
                                                },
                                            success: function (response) {
                                                calendar.fullCalendar('removeEvents', event.id);
                                                sessionStorage.message = "Booking successfully cancelled.";
                                                // sessionStorage.removeItem('counterFail');
                                                // sessionStorage.removeItem('counterSuccess');
                                                // sessionStorage.removeItem('partialA');
                                                // sessionStorage.removeItem('partialB');
                                                localStorage.userMonthlyBookingCountCopy= Number(localStorage.userMonthlyBookingCountCopy) -1;
                                                timeout(1);  //1 minute (60,000 milliseconds)
                                                },
                                            error:function(error)
                                                {
                                                console.log("my error is "+ error);
                                                },
                                        });
                                    
                                    }
                                }  
                                else{
                                    displayInfoMessage("Booking cancellation failed: You are past the deadline to cancel your booking.");
                                }
                            }else{ 
                                displayAlertMessage("Booking cancellation failed: You cannot cancel a past booking.");
                                }
                        }else{ 
                            displayErrorMessage("Booking cancellation failed: You cannot cancel another user's booking.");
                            }  
                    $('#cancelModal').modal("hide");
                });
            });
        },
});
        //end of event click function
        //end of full calendar component1

        // determined whether the user pass the deadline to cancel or modify
        function deadline(startTime){
            var deadline = moment(startTime).subtract(0.5, 'hours').format('Y-MM-DD HH:mm');
            var current = moment().format('Y-MM-DD HH:mm');
            var confirm = moment(current).isBefore(deadline); 
            return confirm;
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
                                displaySuccessMessage("Booking successfully updated.");
                                // sessionStorage.message = "Booking successfully updated.";
                                // timeout(1);
                                },
                                });
                        }else { 
                        displayInfoMessage("Booking modification failed: You can only select " + max_booking_duration + " minutes per booking.");
                        revertFunc();  }  
                }else {
                    displayErrorMessage("Booking modification failed: You cannot modify another user's booking.");
                    revertFunc();}
            }

             //print out the message      
        function displaySuccessMessage(message) {
            $('#alert_container').empty();
            $('#alert_container').append('<div class="alert alert-success alert-dismissible fade show">' + message + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
            $('#alert_container').show();
            // $('#floor').append($('<option disabled selected hidden></option>'));
        } 

        function displayErrorMessage(message)  {
            $('#alert_container').empty();
            $('#alert_container').append('<div class="alert alert-danger alert-dismissible fade show">' + message + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
            $('#alert_container').show();
            // $('#floor').append($('<option disabled selected hidden></option>'));
        } 

        function displayAlertMessage(message) { 
            $('#alert_container').empty();
            $('#alert_container').append('<div class="alert alert-warning alert-dismissible fade show">' + message + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
            $('#alert_container').show();
            // $('#floor').append($('<option disabled selected hidden></option>'));
        } 

        function displayInfoMessage(message) {
            $('#alert_container').empty();
            $('#alert_container').append('<div class="alert alert-info alert-dismissible fade show">' + message + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
            $('#alert_container').show();
            // $('#floor').append($('<option disabled selected hidden></option>'));
        } 

        function  timeout(time){  setTimeout(location.reload.bind(location), time);}

        

        // this one is using the html api to storage the message and it will display it after the page reload
        function displayStoreMessage() {
            if (typeof(Storage) !== "undefined") {
                if (sessionStorage.message) {
                displaySuccessMessage(sessionStorage.message);
                // sessionStorage.clear();
                sessionStorage.removeItem('message');
                sessionStorage.clear();
            }
            }else
                {console.log ("Sorry, your browser does not support web storage."); 
                    displaySuccessMessage("Your request had been updated.");    }
            }


        function displayMaxCapacityMessage() {
            if (typeof(Storage) !== "undefined") {
                if (sessionStorage.status) {
                    displayInfoMessage(sessionStorage.status);
                    sessionStorage.clear();
                    // sessionStorage.removeItem('status');
                    // sessionStorage.removeItem('counterFail'); 
                    // sessionStorage.removeItem('allFail'); 
                    // sessionStorage.removeItem('partialA');
                    // sessionStorage.removeItem('counterSuccess'); 
                    // sessionStorage.removeItem('counterCollision'); 
                    // sessionStorage.removeItem('collision'); 
            
                    }
            } else {
                console.log ("Sorry, your browser does not support web storage."); 
                displayInfoMessage("Booking attempt failed: Room is at maximum capacity.");    
            }
        
        }

                function displayBookingCollisionMessage() {
            if (typeof(Storage) !== "undefined") {
                if (sessionStorage.collisionDetected) {
                    displayInfoMessage(sessionStorage.collisionDetected);
                    sessionStorage.clear();
                    // sessionStorage.removeItem('collisionDetected');
                    // sessionStorage.removeItem('counterFail'); 
                    // sessionStorage.removeItem('allFail'); 
                    // sessionStorage.removeItem('partialA');
                    // sessionStorage.removeItem('counterSuccess'); 
                    // sessionStorage.removeItem('counterCollision'); 
                    // sessionStorage.removeItem('collision'); 
                }
            } else {
                console.log ("Sorry, your browser does not support web storage."); 
                displayInfoMessage("Booking failed: A booking collision was detected.");    
            }
        }

        function partialCompleted() {
            if (typeof(Storage) !== "undefined") {
                if (sessionStorage.allFail) {
                    displayAlertMessage(sessionStorage.allFail);
                    sessionStorage.removeItem('allFail'); 
                }else if 
                (sessionStorage.partialA) {
                    displayAlertMessage(sessionStorage.partialA);
                    sessionStorage.removeItem('partialA');
                }
                else if 
                (sessionStorage.success) {
                    displaySuccessMessage(sessionStorage.success);
                    sessionStorage.removeItem('success');
                }
                else if 
                (sessionStorage.partialB) {
                    displayAlertMessage(sessionStorage.partialB);
                    sessionStorage.removeItem('partialB');
                }
                else if
                (sessionStorage.counterCollision) {
                    displayAlertMessage(sessionStorage.counterCollision);
                    sessionStorage.removeItem('counterCollision')
                }
            } else {
                console.log ("Sorry, your browser does not support web storage."); 
            }
            sessionStorage.clear();
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

        // function of changing calender day view css
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
    var desks = <?php echo json_encode($desks)?>;

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
        // Disable the search button
        $('#b1').prop('disabled', true);    
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
        // Disable the search button
        $('#b1').prop('disabled', true);
    });

    $('#floor').change( function() {
        // Clear the room dropdown 
        $('#room').empty();

        var floorId = parseInt($('#floor').find(':selected').attr('value'));
        var filteredRooms = rooms.filter(item => {
                if (!item.is_closed) {
                    // Check the number of open desks for the current room
                    roomId = item.id; 
                    openDesks = desks.filter(item => {
                        if (!item.is_closed)
                            return item.room_id === roomId;
                    });
                    if (openDesks.length !== 0)
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
        // Disable the search button
        $('#b1').prop('disabled', true);
    });

    $('#room').change( function() {
        if ($('#room').val() !== null) {
            $('#b1').prop('disabled', false);
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
