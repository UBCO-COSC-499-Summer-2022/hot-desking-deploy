@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('campusManager')}}">Campus</a></li>
                    <li class="breadcrumb-item"><a href="{{route('buildingManager',$campus_id)}}">Building</a></li>
                    <li class="breadcrumb-item"><a href="{{route('floorManager',$building_id)}}">Floor</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Room</li>
                </ol>
            </nav>

            <div class="card">

                <div class="card-header text-center h2">
                    {{ __('Room Manager') }}
                </div>
                <div class="card-body p-2">

                    @if(Session::has('message'))
                    <div class="alert {{ Session::get('alert-class', 'alert-info') }} alert-dismissible fade show">{{ Session::get('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col">
                            <a href="{{route('addRoom',$floor_id)}}" class="btn btn-primary float-end mb-2">Create Room <i class="bi bi-plus-lg"></i></a>
                        </div>
                    </div>

                    @if(count($rooms) < 1) <!-- If no rooms created -> show alert -->
                        <div class="alert alert-warning wizard">
                            <i class="bi bi-exclamation-circle-fill"></i> There are no rooms to display.
                        </div>
                        @else
                        <table class="table table-light">
                            <thead>
                                <tr class="table-primary">
                                    <th class="text-center">Room Name</th>
                                    <th class="text-center">Has Printer</th>
                                    <th class="text-center">Has Projector</th>
                                    <th class="text-center">Room Availability</th>
                                    <th>
                                        <div class="row">
                                            <div class="col-md-2"></div>
                                            <div class="col-md-4"></div>
                                            Actions
                                    </th>
                </div>
                </tr>
                </thead>
                <tbody>
                    @foreach($rooms as $room)
                    <tr>
                        <td class="text-center align-middle">{{$room->name}}</td>
                        @if($room->has_printer == TRUE)
                        <td class="text-center align-middle">
                            <i class="bi bi-check-lg"></i>
                        </td>
                        @else
                        <td class="text-center align-middle"><i class="bi bi-x-lg"></i></td>
                        @endif

                        @if($room->has_projector == TRUE)
                        <td class="text-center align-middle"><i class="bi bi-check-lg"></i></td>
                        @else
                        <td class="text-center align-middle"><i class=" bi bi-x-lg"></i></td>
                        @endif

                        @if($room->is_closed == TRUE)
                        <td class="text-center align-middle"><i class="bi bi-check-lg"></i></td>
                        @else
                        <td class="text-center align-middle"><i class="bi bi-x-lg"></i></td>
                        @endif

                        <td>
                            <a role="button" class="btn btn-danger float-end" data-bs-toggle="modal" data-bs-target="#deleteModal{{$room->id}}">
                                <i class="bi bi-trash3-fill"></i></a>
                            <a href="{{route('editRoom',$room->id)}}" role="button" class="btn btn-secondary float-end mx-1">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="{{route('deskManager',$room->id)}}" role="button" class="btn btn-info text-white float-end">
                                View Desks
                            </a>
                        </td>
                        <!-- Delete Modal Start -->
                        <div class="modal fade" id="deleteModal{{$room->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Delete Room</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>
                                            This Room is about to be permanently deleted. <br>
                                            Click Delete to Confirm <br>
                                            Click Cancel to go back
                                        </p>
                                    </div>
                                    <form action="{{route('room.destroy',$room->id)}}" method="POST">
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
                        <!-- Delete Modal End -->
                    </tr>
                    @endforeach
                </tbody>
                </table>
                {{ $rooms->links() }}
                @endif
            </div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript" src="{{ asset('js/test.js') }}"></script>

@endsection