@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

            <div class="card-header">
                    {{ __('Profile') }}
            </div>

            <div class="card-body">
                    <!-- <div class="form-group row mb-3">
                        <label for="first_name" class="col-md-3 col-form-label text-md-right">{{ __('First Name') }}</label>
                        <div class="col-md-4">
                            {{Auth::user()->first_name}}
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="last_name" class="col-md-3 col-form-label text-md-right">{{ __('Last Name') }}</label>
                        <div class="col-md-4">
                            {{Auth::user()->last_name}}
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="email" class="col-md-3 col-form-label text-md-right">{{ __('Email Address') }}</label>
                        <div class="col-md-4">
                            {{Auth::user()->email}}
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="password" class="col-md-3 col-form-label text-md0tight">{{ __('Password')}}</label>
                        <a href="{{ route('changePasswordGet') }}"><button type="button" class="btn btn-secondary">Change Password</button></a>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="role" class="col-md-3 col-form-label text-md-right">{{ __('User Role') }}</label>
                        <div class="col-md-4">
                            {{Auth::user()->role->role}}
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="supervisor" class="col-md-3 col-form-label text-md-right">{{ __('User Supervisor') }}</label>
                        <div class="col-md-4">
                            {{Auth::user()->supervisor}}
                        </div>
                    </div>         -->
                    <table class="table table-bordered table-hover align-middle">
                    <tbody>
                        <tr>
                            <th colspan="2" >First Name: </th>
                            <td colspan="2">{{Auth::user()->first_name}}</td>
                        </tr>
                        <tr>
                            <th colspan="2" >Last Name:</th>
                            <td colspan="2">{{Auth::user()->last_name}}</td>
                        </tr>
                        <tr>
                            <th colspan="2" >Email Address:</th>
                            <td colspan="2">{{Auth::user()->email}}</td>
                        </tr>
                        <tr>
                            <!-- <th colspan="2" >Password:</th> -->
                            <!-- <a href="{{ route('changePasswordGet') }}"><button type="button" class="btn btn-secondary">Change Password</button></a> -->
                            <td colspan="2"><a href="{{ route('changePasswordGet') }}"><button type="button" class="btn btn-secondary">Change Password</button></a></td>
                        </tr>
                        <tr>
                            <th colspan="2" >User Role:</th>
                            <td colspan="2">{{Auth::user()->role->role}}</td>
                        </tr>
                        <tr>
                            <th colspan="2" >User Supervisor:</th>
                            <td colspan="2">{{Auth::user()->supervisor}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>
</div>
@endsection