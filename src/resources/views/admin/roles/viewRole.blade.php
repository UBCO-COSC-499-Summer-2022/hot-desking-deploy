@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-header">
                    {{ __('View Role') }}
                </div>

                <div class="card-body">
                    <fieldset>
                        <legend>Booking Information</legend>
                        <div class="mb-3">
                            <label for="disabledTextInput" class="form-label">Role name: {{$role->role}}</label>
                        </div>
                        <div class="mb-3">
                            <label for="disabledTextInput" class="form-label">Number of monthly bookings:
                                {{$role->num_monthly_bookings}}</label>
                        </div>
                        <div class="mb-3">
                            <label for="disabledTextInput" class="form-label">Frequency of bookings:
                                {{$role->frequency}}</label>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end"> <a href="{{route('rolesManager')}}"
                                class="btn btn-primary" role="button">Go back</a>
                        </div>
                    </fieldset>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection