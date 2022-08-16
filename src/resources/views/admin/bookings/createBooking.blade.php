@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">

                <div class="card-header">
                    {{ __('Create Booking') }}
                </div>

                <div class="card-body">
                    <form action="{{route('booking.store')}}" method="POST">
                        @csrf
                        {{method_field('POST')}}
                        <fieldset>
                            <div class="mb-3">
                                <label for="disabledTextInput" class="form-label">Booking User</label>
                                <select name="user_id" class="form-select @error('user_id') is-invalid @enderror">
                                    <option disabled selected>Select user</option>
                                    @foreach ($users as $user)
                                    <option value="{{$user->id}}">{{$user->first_name}}</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                <div class="alert alert-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="disabledSelect" class="form-label">Desk Id</label>
                                <select name="desk_id" class="form-select @error('desk_id') is-invalid @enderror">
                                    <option disabled selected>Select desk id</option>
                                    @foreach ($desks as $desk)
                                    <option value="{{$desk->id}}">{{$desk->id}}</option>
                                    @endforeach
                                </select>
                                @error('desk_id')
                                <div class="alert alert-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="bookingTime" class="form-label">Booking Start Time</label>
                                <input type="datetime-local" name="book_time_start" class="form-control @error('book_time_start') is-invalid @enderror">
                                @error('book_time_start')
                                <div class="alert alert-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="bookingTime" class="form-label">Booking End Time</label>
                                <input type="datetime-local" name="book_time_end" class="form-control @error('book_time_end') is-invalid @enderror">
                                @error('book_time_end')
                                <div class="alert alert-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-success float-end">Submit</button>
                                <a href="{{route('bookingsManager')}}" class="mx-2 btn btn-secondary float-end" role="button">Cancel</a>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
</div>
@endsection