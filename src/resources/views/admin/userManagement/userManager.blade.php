@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-header text-center h2">
                    {{ __('User Manager') }}
                </div>
                <div class="card-body p-2">
                    @if(Session::has('message'))
                    <div class="alert {{ Session::get('alert-class', 'alert-info') }} alert-dismissible fade show">{{ Session::get('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <div class='row'>
                        <div class='col'>
                            <a href="{{route('addUser')}}" class="btn btn-primary float-end mb-2">Create User <i class="bi bi-plus-lg"></i></a>
                        </div>
                    </div>
                    @if(count($users) < 1) <div class="alert alert-warning wizard">
                        <i class="bi bi-exclamation-circle-fill"></i> There are no users to display.
                </div>
                @else
                <table class="table table-light">
                    <thead>
                        <tr class="table-primary">
                            <th class="text-center">#</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Admin</th>
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
                        @foreach($users as $user)
                        <tr>
                            <td class="text-center align-middle">{{$user->id}}</td>
                            <td class="text-center align-middle">{{$user->name}}</td>
                            <td class="text-center align-middle">{{$user->email}}</td>
                            @if($user->is_admin == TRUE)
                            <td class="text-center align-middle"><i class="bi bi-check-lg"></i></td>
                            @else
                            <td class="text-center align-middle"><i class="bi bi-x-lg"></i></td>
                            @endif
                            <td>
                                <a role="button" class="btn btn-danger float-end" data-bs-toggle="modal" data-bs-target="#deleteModal{{$user->id}}">
                                    <i class="bi bi-trash3-fill"></i></a>
                                <a role="button" class="btn btn-warning float-end mx-1" data-bs-toggle="modal" data-bs-target="#suspendModal{{$user->id}}">
                                    <i class="bi bi-pause-circle-fill text-white"></i>
                                </a>
                                <a href="{{route('editUser',$user->id)}}" role="button" class="btn btn-secondary float-end ">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="{{route('viewUser',$user->id)}}" role="button" class="btn btn-info float-end mx-1">
                                    <i class="bi bi-eye-fill text-white"></i>
                                </a>
                            </td>
                            <div class="modal fade" id="deleteModal{{$user->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Delete USer</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>
                                                This User is about to be permanently deleted. <br>
                                                Click Delete to Confirm <br>
                                                Click Cancel to go back
                                            </p>
                                        </div>
                                        <form action="{{route('user.destroy',$user->id)}}" method="POST">
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
                            <div class="modal fade" id="suspendModal{{$user->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Suspend User</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>
                                                This User is about to be suspended. <br>
                                                Click Suspend to Confirm <br>
                                                Click Cancel to go back
                                            </p>
                                        </div>
                                        <form action="{{route('user.destroy',$user->id)}}" method="POST">
                                            @csrf
                                            {{method_field("DELETE")}}
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-warning">Suspend</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $users->links() }}
                @endif
            </div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript" src="{{ asset('js/test.js') }}"></script>

@endsection