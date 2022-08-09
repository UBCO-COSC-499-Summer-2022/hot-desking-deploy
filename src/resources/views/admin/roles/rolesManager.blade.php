@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-header text-center h2">
                    {{ __('Roles Manager') }}
                </div>
                <div class="card-body p-2">
                    @if(Session::has('message'))
                    <div class="alert {{ Session::get('alert-class', 'alert-info') }} alert-dismissible fade show">{{ Session::get('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col">
                            <a href="{{route('createRole')}}" class="btn btn-primary float-end mb-2">Create Role <i class="bi bi-plus-lg"></i></a>
                        </div>
                    </div>
                    @if(count($roles) < 1)
                        <div class="alert alert-warning wizard">
                            <i class="bi bi-exclamation-circle-fill"></i> There are no roles to display.                    
                        </div>
                    @else
                        <table class="table table-light">
                            <thead>
                                <tr class="table-primary">
                                    <th>Role name</th>
                                    <th>Number of Monthly Bookings</th>
                                    <th>Booking Window (Days)</th>
                                    <th>Booking Duration (Hours)</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roles as $role)
                                <!-- Run if role_id != 0 -->
                                @if($role->role_id != 1)
                                <tr>
                                    <td>{{$role->role}}</td>
                                    <td>{{$role->num_monthly_bookings}}</td>
                                    <td>{{$role->max_booking_window}}</td>
                                    <td>{{$role->max_booking_duration}}</td>
                                    <td>
                                        <a href="{{route('viewRole',$role->role_id)}}" class="btn btn-info"><i
                                                class="bi bi-eye-fill text-white"></i></a>
                                        <a href="{{route('editRole',$role->role_id)}}" class="btn btn-secondary">
                                        <i class="bi bi-pencil-square"></i></a>
                                        @if($role->role_id > 5)
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal{{$role->role_id}}">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                        @else
                                        @endif
                                    </td>
                                </tr>

                                <!-- Delete Modal Start -->
                                <div class="modal fade" id="deleteModal{{$role->role_id}}" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Delete Role</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>
                                                    This Role is about to be permanently deleted. <br>
                                                    Click Delete to Confirm <br>
                                                    Click Cancel to go back
                                                </p>
                                            </div>
                                            <form method="POST" action="{{route('role.destroy',$role->role_id)}}">
                                                @csrf
                                                {{method_field('DELETE')}}
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- Delete Modal End-->
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                        {{ $roles->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ asset('js/test.js') }}"></script>

@endsection