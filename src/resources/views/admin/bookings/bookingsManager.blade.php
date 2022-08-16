@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">

                <div class="card-header h2 text-center">
                    {{ __('Bookings Manager') }}
                </div>
                </button>
                <div class="card-body p-2">
                    @if(Session::has('message'))
                    <div class="alert {{ Session::get('alert-class', 'alert-info') }} alert-dismissible fade show">{{ Session::get('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <div class=" row">
                        <div class="col">
                            <a href="{{route('createBooking')}}" class="btn btn-primary float-end mb-2">
                                Create Booking <i class="bi bi-plus-lg"></i>
                            </a>
                        </div>
                    </div>
                    @if(count($bookings) < 1)
                        <div class="alert alert-warning wizard">
                            <i class="bi bi-exclamation-circle-fill"></i> There are no bookings to display.
                        </div>
                    @else
                        <table class="table table-light">
                            <thead>
                                <tr class="table-primary">
                                    <th>Desk ID</th>
                                    <th>First Name</th>
                                    <th>
                                        <div class="row">
                                            <div class="col-md-5"></div>
                                            <div class="col-md-4"></div>
                                                Actions</th>
                                        </div>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bookings as $booking)
                                <tr>
                                    <td>{{$booking->desk_id}}</td>
                                    <td>{{$booking->first_name}}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger float-end mx-1" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal{{$booking->id}}">
                                            <i class="bi bi-trash3-fill"></i></button>
                                        <a href="{{route('editBooking',$booking->id)}}" class="btn btn-secondary float-end">
                                            <i class="bi bi-pencil-square"></i></a>
                                        <a href="{{route('viewBooking',$booking->id)}}" class="btn btn-info float-end mx-1">
                                            <i class="bi bi-eye-fill text-white"></i></a>
                                    </td>
                                </tr>
    
                                <!-- Delete Modal Start -->
                                <div class="modal fade" id="deleteModal{{$booking->id}}" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Delete Booking</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>
                                                    This Booking is about to be permanently deleted. <br>
                                                    Click <button type="submit" class="btn btn-danger mb-1" disabled>Delete</button> to Confirm <br>
                                                    Click <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" disabled>Cancel</button> to go back
                                                </p>
                                            </div>
                                            <form method="POST" action="{{route('booking.destroy',$booking->id)}}">
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
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center">
                            {{ $bookings->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="{{ asset('js/alert.js') }}"></script>
@endsection