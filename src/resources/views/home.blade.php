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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection