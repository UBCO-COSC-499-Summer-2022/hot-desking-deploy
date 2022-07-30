@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('campusManager')}}">Campus</a></li>
                    <li class="breadcrumb-item"><a href="{{route('buildingManager',$campus_id)}}">Building</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Floor</li>
                </ol>
            </nav>
            <div class="card">

                <div class="card-header text-center h2">
                    {{ __('Floor Manager') }}
                </div>
                <div class="card-body p-2">
                    @if(Session::has('message'))
                    <div class="alert {{ Session::get('alert-class', 'alert-info') }} alert-dismissible fade show">{{ Session::get('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col">
                            <a href="{{route('addFloor',$building_id)}}" class="btn btn-primary float-end mb-2">Create Floor <i class="bi bi-plus-lg"></i></a>
                        </div>
                    </div>

                    @if(count($floors) < 1) <!-- If no floors created -> show alert -->
                        <div class="alert alert-warning wizard">
                            <i class="bi bi-exclamation-circle-fill"></i> There are no floors to display.
                        </div>
                        <!-- Display table of floors -->
                        @else
                        <table class="table table-light">
                            <thead>
                                <tr class="table-primary">
                                    <th class="text-center">Building Floor</th>
                                    <th class="text-center">Availability</th>
                                    <th>
                                        <div class="row">
                                            <div class="col-md-4"></div>
                                            <div class="col-md-4"></div>
                                            Actions
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($floors as $floor)
                                <tr>
                                    <td class="text-center align-middle">{{$floor->floor_num}}</td>
                                    @if($floor->is_closed == TRUE)
                                    <td class="text-center align-middle"><i class="bi bi-check"></i></td>
                                    @else
                                    <td class="text-center align-middle"><i class="bi bi-x-lg"></i></td>
                                    @endif
                                    <td>
                                        <a role="button" class="btn btn-danger float-end" data-bs-toggle="modal" data-bs-target="#deleteModal{{$floor->id}}">
                                            <i class="bi bi-trash3-fill"></i></a>
                                        <a href="{{route('editFloor',$floor->id)}}" role="button" class="btn btn-secondary float-end mx-1">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="{{route('roomManager',$floor->id)}}" role="button" class="btn btn-info text-white float-end">
                                            View Rooms
                                        </a>
                                    </td>
                                    <!-- Delete Modal Start -->
                                    <div class="modal fade" id="deleteModal{{$floor->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Delete Floor</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>
                                                        This Floor is about to be permanently deleted. <br>
                                                        Click Delete to Confirm <br>
                                                        Click Cancel to go back
                                                    </p>
                                                </div>
                                                <form action="{{route('floor.destroy',$floor->id)}}" method="POST">
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
                        {{ $floors->links() }}
                        @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ asset('js/test.js') }}"></script>

@endsection