@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-header">
                    {{ __('User Manager') }}
                </div>

                <div class="card-body">
                    <fieldset>
                        <legend>User Information</legend>
                        <div class="mb-3">
                            <label for="disabledTextInput" class="form-label">Username: {{$user->name}}</label>
                        </div>
                        <div class="mb-3">
                            <label for="disabledTextInput" class="form-label">Email: {{$user->email}}</label>
                        </div>
                        <div class="mb-3">
                            @if($user->is_admin == 1)
                            <label for="disabledTextInput" class="form-label">Is Admin: Yes</label>
                            @else
                            <label for="disabledTextInput" class="form-label">Is Admin: No</label>
                            @endIf
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end"> <a href="{{route('userManager')}}"
                                class="btn btn-primary" role="button">Go back</a>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
    @endsection