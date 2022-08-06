@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-header">
                    {{ __('Filter') }}
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                    </div>
                    <label for="indicateFloor" class="col-md-6 col-form-label text-md-end">{{ __('Current Date: XXX') }}</label>
                    <label for="indicateFloor" class="col-md-6 col-form-label text-md-end">{{ __('Current location: XXX') }}</label>
                    <div>
                        <select class="col-md-6 offset-md-4">
                            <option value="select building">Select Building</option>
                            <option value="select building">Building X</option>
                            <option value="select building">Building Y</option>
                            <option value="select building">Building Z</option>
                        </select>
                        <select class="col-md-6 offset-md-4">
                            <option value="select floor">Select Floor</option>
                            <option value="select floor">Floor 1</option>
                            <option value="select floor">Floor 2</option>
                            <option value="select floor">Floor 3</option>
                        </select>
                        <select class="col-md-6 offset-md-4">
                            <option value="select room">Select Room</option>
                            <option value="select room">ABC 123</option>
                        </select>    
                    </div>
                    <div class="row mb-3">
                    <label for="indicateFloor" class="col-md-6 col-form-label text-md-end">{{ __('Available desks: ') }}</label>
                    </div>
                    <a href="{{ route('bookings') }}" type="button" class="btn btn-secondary">Book this desk</a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection