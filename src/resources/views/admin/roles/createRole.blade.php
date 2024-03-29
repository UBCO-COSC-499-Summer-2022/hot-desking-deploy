@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    {{ __('Create Role') }}
                </div>
                <div class="card-body">
                    <form action="{{route('role.store')}}" method="POST">
                        @csrf
                        {{method_field('POST')}}
                        <fieldset>
                            <div class="mb-3">
                                <label for="disabledTextInput" class="form-label">Role</label>
                                <input type="text" maxlength="30" class="form-control @error('role') is-invalid @enderror" placeholder="Role name" name="role" required>
                                @error('role')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="disabledSelect" class="form-label">Number of monthly bookings</label>
                                <input type="number" min="1" max="999" id="disabledTextInput" class="form-control @error('num_monthly_bookings') is-invalid @enderror"
                                    placeholder="Input number" name="num_monthly_bookings" required>
                                @error('num_monthly_bookings')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="disabledTextInput" class="form-label">How far in advance can a user make a booking (Days)</label>
                                <input type="number" min="1" max="999" id="disabledTextInput" placeholder="Input number" class="form-control @error('max_booking_window') is-invalid @enderror" name="max_booking_window" required>
                                @error('max_booking_window')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="disabledTextInput" class="form-label">Maximum length of a booking that a user can make (Hours)</label>
                                <input type="number" min="1" max="999" id="disabledTextInput" placeholder="Input number" class="form-control @error('max_booking_duration') is-invalid @enderror" name="max_booking_duration" required>
                                @error('max_booking_duration')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-success float-end">Submit</button>
                                <a href="{{route('rolesManager')}}" class="mx-2 btn btn-secondary float-end"
                                        role="button">Cancel</a>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection