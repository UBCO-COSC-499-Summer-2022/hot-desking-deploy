@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-left">
        <div class="col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('campusManager')}}">{{$campus_name}} Campus</a></li>
                    <li class="breadcrumb-item"><a href="{{route('buildingManager',$campus_id)}}">{{$building_name}}</a></li>
                    <li class="breadcrumb-item"><a href="{{route('floorManager',$building_id)}}">Floor #{{$floor_num}}</a></li>
                    <li class="breadcrumb-item"><a href="{{route('roomManager',$floor_id)}}">{{$room->name}}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Desks</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="card px-0 pb-2" style="max-height: 800px;">
            <div class="card-header text-center h2">
                {{ __('Desk Manager: ') }} {{$room->name}} 
            </div>
            <div class="card-body px-4">
                @if(Session::has('message'))
                <div class="alert {{ Session::get('alert-class', 'alert-info') }} alert-dismissible fade show">
                    {{ Session::get('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if($room->rows == 0 || $room->cols == 0)
                <div id="dimensionsForm">
                    <h3>Define Room Dimensions</h3>
                    <form method="POST" action="{{route('roomSizeUpdate', $room->id)}}">
                        @csrf
                        {{method_field('POST')}}
                        <!-- funkyMathThings -->
                        <fieldset>
                        <div class="mb-3">
                            <div id="rowsHelp" class="form-text">Example: Occupancy of 30, square room = 6 rows, 6 columns; rectangle room = 5 rows, 6 columns
                            <br> MAX 50 Rows, 50 Columns
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-3 mb-3">
                                <label for="rows" class="form-label">Define Number of Rows</label>
                                <input type="number" class="form-control" id="rows" name="rows" aria-describedby="rows">
                            </div>
                            <div class="col-3 mb-3">
                                <label for="cols" class="form-label">Define Number of Columns</label>
                                <input type="number" class="form-control" id="cols" name="cols" aria-describedby="cols">
                            </div>
                        </div>
                        <div class="mb-3">
                            <input type="hidden" name="room_id" value="{{$room->id}}" class="form-check-input">
                            <input type="hidden" name="floor_id" value="{{$room->floor_id}}" class="form-check-input">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                        </fieldset>
                    </form>
                </div>
                    
                @else
                <div class="row">
                    <div class="col-md-4 scrollable">
                        @if(count($desks) < 1) 
                        <div class="alert alert-warning wizard">
                            <i class="bi bi-exclamation-circle-fill"></i> There are no desks to display.
                        </div>
                        @else
                        <table class="table table-light table-bordered table-responsive">
                            <thead>
                                <tr class="table-primary">
                                    <th class="text-center" style="width: 8rem;">Desk ID</th>
                                    <th class="text-center" style="width: 8rem;">Availability</th>
                                    <th class="text-center" style="width: 8rem;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($desks as $desk)
                                <tr>
                                    <td class="text-center align-middle">{{$desk->id}}</td>
                                    @if($desk->is_closed == FALSE)
                                    <td class="text-center align-middle">Open</td>
                                    @else
                                    <td class="text-center align-middle">Closed</td>
                                    @endif
                                    <td class="text-center">
                                        <button type="button" onclick="editDesk({{$desk->id}},{{$desk->pos_x}},{{$desk->pos_y}},{{$desk->room_id}},{{$desk->is_closed}})" class="btn btn-secondary">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <a role="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{$desk->id}}">
                                            <i class="bi bi-trash3-fill"></i>
                                        </a>
                                    </td>
                                    <div class="modal fade" id="deleteModal{{$desk->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Delete Modal</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                            <div class="modal-body">
                                                <p>
                                                    This Desk is about to be permanently deleted. <br>
                                                    Click Delete to Confirm <br>
                                                    Click Cancel to go back
                                                </p>
                                            </div>
                                            <form action="{{route('desk.destroy',$desk->id)}}" method="POST">
                                                @csrf
                                                {{method_field("DELETE")}}
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <form method="POST" id="updateRoomDimension" action="{{route('roomSizeUpdate', $room->id)}}">
                        @csrf
                        {{method_field('POST')}}
                        <!-- funkyMathThings -->
                            <fieldset>
                                <div class="row">
                                    <label for="rows" class="col col-sm-auto col-form-label mx-3">Rows</label>
                                    <div class="col mb-3">
                                        <input type="number" class="form-control" id="updateRows" name="rows" placeholder="Rows" aria-describedby="rows" value="{{$room->rows}}">
                                    </div>
                                    <label for="cols" class="col col-sm-auto col-form-label">Columns</label>
                                    <div class="col mb-3">     
                                        <input type="number" class="form-control" id="updateCols" name="cols" placeholder="Columns" aria-describedby="cols" value="{{$room->cols}}">
                                    </div>
                                    <div class="col mb-3">
                                        <input type="hidden" id="roomId" name="room_id" value="{{$room->id}}" class="form-check-input">
                                        <button type="submit" class="btn btn-primary">Update Dimensions</button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                        <div class="row">
                            <div class="col scrollable p-0">
                                <table class="table table-borderless table-responsive">
                                    <tbody>
                                        @for($i = -1; $i < $room->rows; $i++) 
                                        <tr class="rowsToSearch">
                                        <!-- For each column -->
                                            @for ($j = -1; $j < $room->cols+1; $j++)
                                                @if($i == -1 && $j == -1)
                                                    <td scope="col" class="rowIndicator text-center align-middle bg-white overflow-y-auto"></td>
                                                @else
                                                    @if($j < 0)
                                                    <td scope="col" class="rowIndicator text-center align-middle bg-white overflow-y-auto">{{$i}}</td>
                                                    @endif 
                                                    @if($i < 0)
                                                    <td scope="col" class="colIndicator text-center align-middle bg-white overflow-x-auto">{{$j}}</td>
                                                    @else
                                                        @if($j == $room->cols)
                                                        @else
                                                        <td scope="col" class="text-center align-middle colsToSearch overflow-auto" id="r{{$i}}c{{$j+1}}">
                                                            <button type="button" class="btn btn-light btn-outline-success newDeskButton{{$i}}{{$j+1}}" id="newDeskBtn{{$i}},{{$j+1}}" style="height: 130px; width:130px;"
                                                                onclick="selectDesk({{$i}},{{$j+1}})">
                                                            </button>
                                                        </td>
                                                        @endif
                                                    @endif
                                                @endif
                                            @endfor
                                        </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>            
    </div>
</div>
<div class="modal fade" id="newDeskModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">New Desk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" data-bs-toggle="button" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{route('deskStore')}}">
                    @csrf
                    {{method_field('POST')}}
                    <fieldset>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" value='TRUE' name="is_closed" id="is_closed">
                                <label class="form-check-label" for="is_closed">Close Desk</label>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <button onclick="addNewResource(false)" type="button" id="addResource" class="btn btn-primary float-start">Add Resource +</button>
                            </div>
                        </div>
                        <div class="alert alert-warning wizard align-items-center" id="resourcesWarningAdd" style="text-align:justify vert">
                            <div class="row">
                                <div class="col col-md-1 text-center align-middle">
                                    <i class="bi bi-exclamation-circle-fill fs-2"></i>
                                </div>
                                <div class="col col-md-11">
                                    This Room has no resources set. If you would like to add a resource then you can select them using the <button disabled type="button" class="btn btn-primary">Add Resource +</button> button
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center mb-3" id="resourcesListAdd">
                            <table id="addResourcesTbl" class="table table-light table-borderless">
                                <thead class="table-primary">
                                    <th class="p-2">Resource Name</th>
                                    <th class="text-center p-2">Actions</th>
                                </thead>
                                <tbody id="addResourcesBodyAdd">
                                </tbody>
                            </table>
                        </div>
                        <div>
                            <input type="hidden" id="row" class="form-control" name="pos_x" >
                            <input type="hidden" id="col" class="form-control" name="pos_y" >
                            <input name='room_id' value='{{$room_id}}' type='hidden' class='form-check-input'>
                        </div>
                    </fieldset>
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" id="bV">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editDeskModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDeskModalTitle">Edit Desk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" data-bs-toggle="button" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{route('deskUpdate')}}">
                    @csrf
                    {{method_field('POST')}}
                    <fieldset>
                        <div class="mb-3">
                                <label for="pos_x" class="form-label">X Position</label>
                                <input type="number" min="0" max="9999" id="pos_x" class="form-control @error('pos_x') is-invalid @enderror" name="pos_x" value="" required>
                                @error('pos_x')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                        </div>
                        <div class="mb-3">
                            <label for="pos_y" class="form-label">Y Position</label>
                            <input type="number" min="0" max="9999" id="pos_y" class="form-control @error('pos_y') is-invalid @enderror" name="pos_y" value="" required>
                            @error('pos_y')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <div class="isClosedSwitch form-check form-switch">
                                <input class="form-check-input" type="checkbox" value='TRUE' name="is_closed" id="is_closedEdit">
                                <label class="form-check-label" for="is_closed">Close Desk</label>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <button onclick="addNewResource(true)" type="button" id="addResource" class="btn btn-primary float-start">Add Resource +</button>
                            </div>
                        </div>
                        <div class="alert alert-warning wizard align-items-center" id="resourcesWarningEdit" style="text-align:justify vert">
                            <div class="row">
                                <div class="col col-md-1 text-center align-middle">
                                    <i class="bi bi-exclamation-circle-fill fs-2"></i>
                                </div>
                                <div class="col col-md-11">
                                    This Room has no resources set. If you would like to add a resource then you can select them using the <button disabled type="button" class="btn btn-primary">Add Resource +</button> button
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center mb-3" id="resourcesListEdit">
                            <table id="addResourcesTbl" class="table table-light table-borderless">
                                <thead class="table-primary">
                                    <th class="p-2">Resource Name</th>
                                    <th class="text-center p-2">Actions</th>
                                </thead>
                                <tbody id="addResourcesBodyEdit">
                                </tbody>
                            </table>
                        </div>
                        <div>
                            <input id="room_id" name='room_id' value='' type='hidden' class='form-check-input'>
                            <input id="id" name='id' value='' type='hidden' class='form-check-input'>
                        </div>
                    </fieldset>
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="validateDimUpdateModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Warning</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="listDeletedDesks">
        <div id="sizeConfirmMessage">
        </div>
        <form method="POST" action="{{route('roomSizeUpdate', $room->id)}}">
            @csrf
            {{method_field('POST')}}
                <fieldset>
                <div id="listOfDeletedDesks">
                
                </div>
                <input type="hidden" id="newRows" name="rows" value="" class="form-check-input">
                <input type="hidden" id="newCols" name="cols" value="" class="form-check-input">
                </fieldset>
      </div>
      <div class="modal-footer">
        <input type="hidden" id="roomId" name="room_id" value="{{$room->id}}" class="form-check-input">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button id="dimUpdateModalSubmitBtn" type="submit" class="btn btn-danger">Confirm</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- <script type="text/javascript" src="{{ asset('js/alert.js') }}"></script> -->
<script type="text/javascript" src="{{ asset('js/select-desk.js') }}"></script>
<script type="text/javascript">
$(window).on('load', function(){
    var desks = <?php echo json_encode($desks)?>;
    var resources = <?php echo json_encode($resources)?>;
    var cols = jQuery("#updateCols").val();
    console.log(desks);
    console.log(resources);
    
    desks.forEach(function(desk) {
        // $(`.newDeskButton${desk.pos_x}c${desk.pos_x}`).remove();    
        if(desk.pos_y == cols){
            $(`button.newDeskButton${desk.pos_x}${desk.pos_y}`).remove();
        }
        $(`button.newDeskButton${desk.pos_x}${desk.pos_y}`).remove();

         $.ajax({
            type: "GET",
            url: "/get-resources-desk",
            data: {data: desk.id},
            success: function (data) {
                $('#r'+desk.pos_x+'c'+desk.pos_y).append(data);
            }
            
        });

        // if (desk.is_closed){
        //     $('#r'+desk.pos_x+'c'+desk.pos_y).append(`<div class="editClosedDesk w-100 h-100 text-center" id="${desk.id}" onclick="editDesk(${desk.id},${desk.pos_x},${desk.pos_y},${desk.room_id},${desk.is_closed})">Desk #${desk.id} CLOSED
            
        //     </div>`);
        // }
        // else{
        //     $('#r'+desk.pos_x+'c'+desk.pos_y).append(`<div class="editDeskButton w-100 h-100 text-center" id="${desk.id}" onclick="editDesk(${desk.id},${desk.pos_x},${desk.pos_y},${desk.room_id},${desk.is_closed})">Desk #${desk.id}                
        //     </div>`);
        // }
    });
});

$("#updateRoomDimension").submit(function(event) {
    var route = "{{route('roomSizeUpdate', $room->id)}}"
    var desksinroom = <?php echo json_encode($desks)?>;
    var rows = jQuery("#updateRows").val();
    var cols = jQuery("#updateCols").val();
    var deleted = false;
    desksinroom.forEach(function(deskinroom){
        if(deskinroom.pos_x >= rows || deskinroom.pos_y >= cols){
            deleted = true;
        }
    });
    if(deleted) {
        event.preventDefault();
        $("#validateDimUpdateModal").modal("show");
        $("#sizeConfirmMessage").empty();
        $("#listOfDeletedDesks").empty();
        $("#listOfDeletedDesks").append("Updating room dimensions will delete the following desks: <br>");
        desksinroom.forEach(function(deskinroom){
            if(deskinroom.pos_x >= rows || deskinroom.pos_y >= cols){
                console.log(deskinroom.id);
                $("#listOfDeletedDesks").append(`<span>Desk #${deskinroom.id}</span><br>`);
                $("#newRows").val(rows);
                $("#newCols").val(cols);
            }
        });
        if($("#dimUpdateModalSubmitBtn").hasClass("btn-success")){
            $("#dimUpdateModalSubmitBtn").removeClass("btn-success").addClass("btn-danger");
        }
    } else {
        event.preventDefault();
        $("#validateDimUpdateModal").modal("show");
        $("#sizeConfirmMessage").empty();
        $("#listOfDeletedDesks").empty();
        $("#sizeConfirmMessage").append(`<p>The dimensions of the room will be updated to ${rows} rows, ${cols} columns</p><br>`);
        $("#newRows").val(rows);
        $("#newCols").val(cols);
        if($("#dimUpdateModalSubmitBtn").hasClass("btn-danger")){
            $("#dimUpdateModalSubmitBtn").removeClass("btn-danger").addClass("btn-success");
        }
    }
    // event.preventDefault();
    // $.ajax({
    //     url: route,
    //     type: "POST",
    //     data: { data: jQuery("#roomId").val()
    //     },
    //     success: function(result) {    
    //         if(result.errors){
    //             $("#validateDimUpdateModal").modal("show");
    //             jQuery.each(result.errors, function(key, value){
    //                 $("#listDeletedDesks").append(value);

    //             });
    //         } else {
    //             $("#validateDimUpdateModal").modal("show");
    //             $("#listDeletedDesks").append(result);
    //         }
            
    //     }
    // })
});

</script>
<style>
    .scrollable {
        max-height: 500px;
        overflow-y: auto;
        overflow-x: auto;
    }
    
    .deskIndex {
        background-color: #334155;
    }

    .editDeskButton {
        color:black;
        font-weight: 900;
        background-color: #ADB5BD;
        height: 130px;
        width: 130px;
        padding: 0.375rem 0.75rem;
        font-size: 0.9rem;
        border-radius: 0.25rem;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .editDeskButton:hover {
        color: #ffffff;
        background-color: #8E99A4;
    }

    .editClosedDesk {
        color:black;
        font-weight: 900;
        background-color: #F5E050;
        height: 130px;
        width: 130px;
        padding: 0.375rem 0.75rem;
        font-size: 0.9rem;
        border-radius: 0.25rem;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .editClosedDesk:hover {
        color: #ffffff;
        background-color: #8E99A4;
    }
    .newDeskButton {
        background-color: grey;
        /* height: 100px;
        width: 100px; */
    }
    
    td.rowIndicator {
        position: -webkit-sticky;
        position: sticky; 
        left: 0;
        top: 0;
        z-index: 2;
        flex: content;
        /* width: 160px; */
        /* padding: 150px; */
    }

    td.colIndicator {
        left:0;
        top: 0;
        position: sticky;
        z-index: 1;
    }

    tr.rowsToSearch td:first-child {
        position: sticky;
        left:0;
        /* right: 10px; */
        
        /* width: 150px; */
        /* z-index: 1;  */
    }
 
    td.colsToSearch {
        position: static;
        /* height: 100px;*/
        /* width: 150px; */
        left:20px; 
        
        /* z-index: 1; */

    }
</style>
@endsection