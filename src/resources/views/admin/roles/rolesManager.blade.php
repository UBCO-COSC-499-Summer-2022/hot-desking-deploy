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
                    @if(count($roles) < 1) <div class="alert alert-warning wizard">
                        <i class="bi bi-exclamation-circle-fill"></i> There are no roles to display.
                </div>
                @else
                <table class="table table-light">
                    <thead>
                        <tr class="table-primary">
                            <th class="text-center">Role name</th>
                            <th class="text-center">Number of Monthly Bookings</th>
                            <th class="text-center">Frequency</th>
                            <th>
                                <div class="row">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-4"></div>
                                    Actions
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                        <tr>
                            <td class="text-center align-middle">{{$role->role}}</td>
                            <td class="text-center align-middle">{{$role->num_monthly_bookings}}</td>
                            <td class="text-center align-middle">{{$role->frequency}}</td>
                            <td>
                                <a role="button" class="btn btn-danger float-end" data-bs-toggle="modal" data-bs-target="#deleteModal{{$role->id}}"><i class="bi bi-trash3-fill"></i></a>
                                <a href="{{route('editRole',$role->id)}}" class="btn btn-secondary float-end mx-1"><i class="bi bi-pencil-square"></i></a>
                                <a href="{{route('viewRole',$role->id)}}" class="btn btn-info float-end"><i class="bi bi-eye-fill text-white"></i></a>
                            </td>
                        </tr>

                        <!-- Delete Modal Start -->
                        <div class="modal fade" id="deleteModal{{$role->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Delete Role</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>
                                            This Role is about to be permanently deleted. <br>
                                            Click Delete to Confirm <br>
                                            Click Cancel to go back
                                        </p>
                                    </div>
                                    <form method="POST" action="{{route('role.destroy',$role->id)}}">
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
                {{ $roles->links() }}
                @endif
            </div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript" src="{{ asset('js/test.js') }}"></script>

@endsection