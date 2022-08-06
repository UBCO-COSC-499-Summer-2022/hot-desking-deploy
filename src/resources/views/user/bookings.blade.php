@extends('layouts.app')

@section('content')


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-header">
                    {{ __('Bookings') }}
                </div>

                <div class="card-body">
                    @if(Session::has('message'))
                        <div class="alert {{ Session::get('alert-class', 'alert-info') }} alert-dismissible fade show">{{ Session::get('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if(count($bookings) < 1)
                        <div class="alert alert-warning wizard">
                            <i class="bi bi-exclamation-circle-fill"></i> There are no bookings to display.
                        </div>
                    @else
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-primary">
                                <tr>
                                    <th scope="col" colspan="2">Building</th>
                                    <th scope="col" colspan="2">Room</th>
                                    <th scope="col" colspan="2">Date</th>
                                    <th scope="col" colspan="2">Duration</th>
                                    <th scope="col" colspan="2">Actions</th>
                                </tr>
                            </thead>
                            <tbody> 
                                @foreach ($bookings as $booking) 
                                    <tr>
                                        <td colspan="2">{{$booking->room->floor->building->name}}</td>
                                        <td colspan="2">{{$booking->room->name}}</td>
                                        <td colspan="2">{{date('F d, Y',strtotime($booking->pivot->book_time_start))}}</td>
                                        <td colspan="2">{{date('g:ia',strtotime($booking->pivot->book_time_start))}} - {{date('g:ia',strtotime($booking->pivot->book_time_end))}}</td>
                                        <td colspan="2">                           
                                            <a href="{{route('viewUserBooking', $booking->pivot->id)}}" role="button" class="btn btn-info">
                                                <i class="bi bi-eye-fill text-white"></i>
                                            </a>
                                            <a href="{{route('modify')}}" role="button" class="btn btn-secondary">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <a role="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal{{$booking->pivot->id}}">
                                                <i class="bi bi-trash3-fill"></i> 
                                            </a>
                                        </td>
                                    </tr>
                                    <!-- --Modal  start -- -->
                                    <div class="modal fade" id="cancelModal{{$booking->pivot->id}}" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="cancelModalLabel">Delete Confirmation</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                Are you sure to delete the bookings?
                                                </div>
                                                <form action="{{route('cancelBooking', $booking->pivot->id)}}">
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-danger" data-bs-dismiss="modal">Delete</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- --Modal  finish -- -->
                                @endforeach    
                            </tbody>
                        </table>
                    @endif           
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="{{ asset('js/alert.js') }}"></script>
@endsection