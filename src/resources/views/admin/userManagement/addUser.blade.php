@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-header">
                    {{ __('Create User') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{route('userStore')}}">
                        @csrf
                        {{method_field('POST')}}
                        <fieldset>
                            <div class="mb-3">
                                <label for="disabledTextInput" class="form-label">User Name</label>
                                <input type="text" name="name" maxlength="255" id="disabledTextInput"
                                    class="form-control @error('name') is-invalid @enderror" placeholder="User Name"
                                    required>
                                @error('name')
                                <div class='alert alert-danger'>{{$message}}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="disabledTextInput" class="form-label">User Email</label>
                                <input type="text" name="email" maxlength="255" id="disabledTextInput"
                                    class="form-control @error('email') is-invalid @enderror" placeholder="User Email"
                                    required>
                                @error('email')
                                <div class='alert alert-danger'>{{$message}}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="disabledTextInput" class="form-label">User Password</label>
                                <input type="text" name="password" minlength='8' maxlength="255" id="disabledTextInput"
                                    class="form-control @error('password') is-invalid @enderror" placeholder="User Password"
                                    required>
                                @error('password')
                                <div class='alert alert-danger'>{{$message}}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" value='TRUE' name="is_admin"
                                        id="flexSwitchCheckDefault">
                                    <label class="form-check-label" for="flexSwitchCheckDefault">Is Admin</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-success float-end" role="button">Submit</button>
                                <a href="{{route('userManager')}}" class="mx-2 btn btn-secondary float-end" role="button">Cancel</a>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection