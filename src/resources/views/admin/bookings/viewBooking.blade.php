@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">

                <div class="card-header">
                    {{ __('View Booking') }}
                </div>

                <div class="card-body">
                    <fieldset>
                        <legend>Booking Information</legend>
                        <div class="mb-3">
                            <label for="disabledTextInput" class="form-label">Desk Id: {{$booking->desk_id}}</label>
                        </div>
                        <div class="mb-3">
                            <label for="disabledTextInput" class="form-label">User Id: {{$booking->user_id}}</label>
                        </div>
                        <div class="mb-3">
                            <label for="disabledTextInput" class="form-label">Booking Start Time:
                                {{$booking->book_time_start}}</label>
                        </div>
                        <div class="mb-3">
                            <label for="disabledTextInput" class="form-label">Booking End Time:
                                {{$booking->book_time_end}}</label>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end"> <a href="{{route('bookingsManager')}}"
                                class="btn btn-primary" role="button">Go back</a>
                        </div>
                    </fieldset>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection