@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-header text-center h2">
                    {{ __('Campus Manager') }}
                </div>
                <div class="card-body p-2">
                    @if(Session::has('message'))
                    <div class="alert {{ Session::get('alert-class', 'alert-info') }} alert-dismissible fade show">{{ Session::get('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <div class='row'>
                        <div class='col'>
                            <a href="{{route('addCampus')}}" class="btn btn-primary float-end mb-2">
                                Create Campus <i class="bi bi-plus-lg"></i>
                            </a>
                        </div>
                    </div>
                    @if(count($campuses) < 1) <div class="alert alert-warning wizard">
                        <i class="bi bi-exclamation-circle-fill"></i> There are no campuses to display.
                </div>
                @else
                <table class="table table-light">
                    <thead>
                        <tr class="table-primary">
                            <th class="text-center">Campus Name</th>
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
                        @foreach($campuses as $campus)
                        <tr>
                            <td class="text-center align-middle">{{$campus->name}}</td>
                            @if($campus->is_closed == TRUE)
                            <td class="text-center align-middle">Closed</td>
                            @else
                            <td class="text-center align-middle">Open</td>
                            @endif
                            <td>
                                <a role="button" class="btn btn-danger float-end" data-bs-toggle="modal" data-bs-target="#deleteModal{{$campus->id}}">
                                    <i class="bi bi-trash3-fill"></i>
                                </a>
                                <a href="{{route('editCampus',$campus->id)}}" role="button" class="btn btn-secondary float-end mx-1">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="{{route('buildingManager',$campus->id)}}" role="button" class="btn btn-info text-white float-end ">View Buildings
                                </a>
                            </td>
                        </tr>

                        <!-- Delete Modal Start -->
                        <div class="modal fade" id="deleteModal{{$campus->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Delete Campus</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>
                                            This Campus is about to be permanently deleted. <br>
                                            Click <button type="submit" class="btn btn-danger mb-1" disabled>Delete</button> to Confirm <br>
                                            Click  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" disabled>Cancel</button> to go back
                                        </p>
                                    </div>
                                    <form method="POST" action="{{route('campus.destroy',$campus->id)}}">
                                        @csrf
                                        {{method_field('DELETE')}}
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Delete Modal End-->
                        @endforeach
                    </tbody>
                </table>
                {{ $campuses->links() }}
                @endif
            </div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript" src="{{ asset('js/test.js') }}"></script>

@endsection