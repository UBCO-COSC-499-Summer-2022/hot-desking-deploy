@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-header">
                    {{ __('Modify') }}
                </div>

                <div class="card-body">
                <a href="{{ route('bookings') }}" type="button" class="btn btn-primary">Previous Page</a>
                
                </div>

            </div>
        </div>
    </div>
</div>
@endsection