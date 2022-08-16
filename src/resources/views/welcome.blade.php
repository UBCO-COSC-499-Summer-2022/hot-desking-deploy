@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Home') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p>Hello</p>
                    <p>Welcome to the UBCO Faculty of Science Hot-Desking Application.</p>
                    <p>You can search for available desks and make bookings based on your role in the faculty.</p>
                    @if (!Auth()->check())
                        <p>Please login to begin.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
