@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('campusManager')}}">{{$campus_name}} Campus</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Buildings</li>
                </ol>
            </nav>
            <div class="card">
                <div class="card-header text-center h2">
                    {{ __('Building Manager: ') }} {{$campus_name}} Campus
                </div>
                <div class="card-body p-2">
                    @if(Session::has('message'))
                    <div class="alert {{ Session::get('alert-class', 'alert-info') }} alert-dismissible fade show">{{ Session::get('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <div class='row'>
                        <div class='col'>
                            <a href="{{route('addBuilding',$campus_id)}}" class="btn btn-primary float-end mb-2">
                                Create Building <i class="bi bi-plus-lg"></i></a>
                        </div>
                    </div>
                    @if(count($buildings) < 1) 
                    <div class="alert alert-warning wizard">
                        <i class="bi bi-exclamation-circle-fill"></i> There are no buildings to display.
                    </div>
                    @else
                    <table class="table table-light">
                        <thead>
                            <tr class="table-primary">
                                <th class="text-center">Building Name</th>
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
                            @foreach($buildings as $building)
                            <tr>
                                <td class="text-center align-middle">{{$building->name}}</td>
                                @if($building->is_closed == TRUE)
                                <td class="text-center align-middle">Closed</td>
                                @else
                                <td class="text-center align-middle">Open</td>
                                @endif
                                <td>
                                    <a role="button" class="btn btn-danger float-end" data-bs-toggle="modal" data-bs-target="#deleteModal{{$building->id}}">
                                        <i class="bi bi-trash3-fill"></i></a>
                                    <a href="{{route('editBuilding',$building->id)}}" role="button" class="btn btn-secondary float-end mx-1">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="{{route('floorManager',$building->id)}}" role="button" class="btn btn-info text-white float-end">View Floors
                                    </a>
                                </td>
                                <div class="modal fade" id="deleteModal{{$building->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Delete Modal</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>
                                                    This Building is about to be permanently deleted. <br>
                                                    Click <button type="submit" class="btn btn-danger mb-1" disabled>Delete</button> to Confirm <br>
                                                    Click <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" disabled>Cancel</button> to go back
                                                </p>
                                            </div>
                                            <form action="{{route('building.destroy',$building->id)}}" method="POST">
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
                    {{ $buildings->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ asset('js/test.js') }}"></script>

@endsection