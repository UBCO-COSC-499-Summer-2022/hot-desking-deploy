@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    {{ __('Edit Role') }}
                </div>
                <div class="card-body">
                    <form method="POST" action="{{route('roleUpdate',$role->id)}}">
                        @csrf
                        {{method_field('POST')}}
                        <fieldset>
                            <div class="mb-3">
                                <label for="role" class="form-label">Role Name</label>
                                <input type="text" id="role" class="form-control @error('role') is-invalid @enderror" maxlength="30" value="{{$role->role}}" name="role" required>
                                    @error('role')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                            </div>
                            <div class="mb-3">
                                <label for="nmb" class="form-label">number of monthly
                                    bookings</label>
                                <input type="number" min="1" max="999" id="nmb" class="form-control @error('num_monthly_bookings') is-invalid @enderror"
                                    value="{{$role->num_monthly_bookings}}" name="num_monthly_bookings" required>
                                    @error('num_monthly_bookings')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                            </div>
                            <div class="mb-3">
                                <label for="freq" class="form-label">Frequency of monthly bookings</label>
                                <input type="number" min="1" max="999" id="freq" class="form-control @error('frequency') is-invalid @enderror"
                                    value="{{$role->frequency}}" name="frequency" required>
                                    @error('frequency')
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
</div>
@endsection