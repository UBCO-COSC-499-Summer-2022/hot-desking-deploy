@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p>Hello {{$user->first_name}} {{$user->last_name}} <i class="bi bi-emoji-smile"></i> You are now logged in! <i class="bi bi-hand-thumbs-up"></i></p>
                    <p>Your current Role is {{$user->role->role}}</p>
                    <p>You can now book desks</p>
                    <p>You have a maximum booking window of <span class="badge bg-info  text-dark">{{$user->role->max_booking_window }}</span> days. 
                    and maximum booking duration of <span class="badge bg-info  text-dark">{{($user->role->max_booking_duration)*60}}</span> minutes.
                    You can book <span class="badge bg-info  text-dark">{{($user->role->num_monthly_bookings)}}</span> desks per month.</p>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
